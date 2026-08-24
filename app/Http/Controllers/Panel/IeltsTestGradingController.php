<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTestAttempt;
use App\Support\Ielts\GradesIeltsAttempts;
use Illuminate\Http\Request;

class IeltsTestGradingController extends Controller
{
    use GradesIeltsAttempts;

    private const WRITING_CRITERIA_KEYS = ['task_achievement', 'coherence', 'lexical', 'grammar'];
    private const SPEAKING_CRITERIA_KEYS = ['fluency', 'lexical', 'grammar', 'pronunciation'];

    public function queue(Request $request)
    {
        $this->authorizeGrader();

        $skillFilter = $request->query('skill');

        $query = IeltsTestAttempt::with('test', 'user')->where('status', 'completed');

        if ($skillFilter === 'writing') {
            $query->whereHas('test.sections', fn ($q) => $q->where('skill', 'writing'))
                ->whereNull('writing_graded_at');
        } elseif ($skillFilter === 'speaking') {
            $query->whereHas('test.sections', fn ($q) => $q->where('skill', 'speaking'))
                ->whereNull('speaking_graded_at');
        } else {
            $query->where(function ($q) {
                $q->whereHas('test.sections', fn ($qq) => $qq->where('skill', 'writing'))
                    ->whereNull('writing_graded_at');
            })->orWhere(function ($q) {
                $q->whereHas('test.sections', fn ($qq) => $qq->where('skill', 'speaking'))
                    ->whereNull('speaking_graded_at');
            });
        }

        $attempts = $query->orderBy('completed_at')->paginate(20);

        return view('design_1.panel.ielts_tests_manage.grading.queue', [
            'pageTitle' => 'Chấm bài Writing / Speaking',
            'attempts' => $attempts,
            'skillFilter' => $skillFilter,
        ]);
    }

    public function showWriting($attemptId)
    {
        $this->authorizeGrader();

        $attempt = IeltsTestAttempt::with(['test.sections.questions', 'answers', 'user'])
            ->findOrFail($attemptId);

        $writingSections = $attempt->test->sections->where('skill', 'writing')->values();

        $tasks = [];
        foreach ($writingSections as $section) {
            foreach ($section->questions->sortBy('question_number') as $question) {
                $answer = $attempt->answers->firstWhere('question_id', $question->id);
                $tasks[] = [
                    'question' => $question,
                    'essay' => $answer->answer_text ?? '',
                ];
            }
        }

        return view('design_1.panel.ielts_tests_manage.grading.writing', [
            'pageTitle' => 'Chấm Writing — ' . $attempt->test->title,
            'attempt' => $attempt,
            'tasks' => $tasks,
            'criteriaKeys' => self::WRITING_CRITERIA_KEYS,
            'existingCriteria' => $attempt->writing_criteria ?? [],
            'existingFeedback' => $attempt->writing_feedback ?? '',
        ]);
    }

    public function saveWriting(Request $request, $attemptId)
    {
        $this->authorizeGrader();

        $attempt = IeltsTestAttempt::with('test.sections')->findOrFail($attemptId);

        $validated = $request->validate([
            'criteria' => 'required|array',
            'criteria.task_achievement' => 'required|numeric|min:0|max:9',
            'criteria.coherence' => 'required|numeric|min:0|max:9',
            'criteria.lexical' => 'required|numeric|min:0|max:9',
            'criteria.grammar' => 'required|numeric|min:0|max:9',
            'feedback' => 'nullable|string',
        ]);

        $criteriaValues = array_map('floatval', $validated['criteria']);
        $band = round((array_sum($criteriaValues) / count($criteriaValues)) * 2) / 2;

        $attempt->writing_criteria = $criteriaValues;
        $attempt->writing_band = $band;
        $attempt->writing_feedback = $validated['feedback'] ?? null;
        $attempt->writing_graded_by = auth()->id();
        $attempt->writing_graded_at = time();
        $attempt->save();

        $this->refreshOverallBand($attempt);

        return redirect()
            ->route('panel.ielts_grading.queue')
            ->with(['toast' => [
                'title' => 'Đã lưu',
                'msg' => 'Đã chấm Writing cho bài của ' . ($attempt->user->full_name ?? $attempt->user->name ?? ''),
                'status' => 'success',
            ]]);
    }

    public function showSpeaking($attemptId)
    {
        $this->authorizeGrader();

        $attempt = IeltsTestAttempt::with(['test.sections.questions', 'answers', 'user'])
            ->findOrFail($attemptId);

        $speakingSections = $attempt->test->sections->where('skill', 'speaking')->sortBy('sort_order')->values();

        $existingCriteria = $attempt->speaking_criteria ?? [];
        $existingSections = $existingCriteria['sections'] ?? [];

        $parts = [];
        foreach ($speakingSections as $section) {
            $questions = $section->questions->sortBy('question_number')->map(function ($question) use ($attempt) {
                $answer = $attempt->answers->firstWhere('question_id', $question->id);
                return [
                    'question' => $question,
                    'audioUrl' => $answer->audio_url ?? null,
                ];
            })->values();

            $parts[] = [
                'section' => $section,
                'questions' => $questions,
                'existing' => $existingSections[(string) $section->id] ?? [],
            ];
        }

        return view('design_1.panel.ielts_tests_manage.grading.speaking', [
            'pageTitle' => 'Chấm Speaking — ' . $attempt->test->title,
            'attempt' => $attempt,
            'parts' => $parts,
            'criteriaKeys' => self::SPEAKING_CRITERIA_KEYS,
        ]);
    }

    public function saveSpeaking(Request $request, $attemptId)
    {
        $this->authorizeGrader();

        $attempt = IeltsTestAttempt::with('test.sections')->findOrFail($attemptId);

        $validated = $request->validate([
            'parts' => 'required|array',
            'parts.*.fluency' => 'required|numeric|min:0|max:9',
            'parts.*.lexical' => 'required|numeric|min:0|max:9',
            'parts.*.grammar' => 'required|numeric|min:0|max:9',
            'parts.*.pronunciation' => 'required|numeric|min:0|max:9',
            'parts.*.feedback' => 'nullable|string',
        ]);

        $sectionsPayload = [];
        $criterionTotals = array_fill_keys(self::SPEAKING_CRITERIA_KEYS, []);

        foreach ($validated['parts'] as $sectionId => $partData) {
            $entry = [];
            foreach (self::SPEAKING_CRITERIA_KEYS as $key) {
                $value = (float) $partData[$key];
                $entry[$key] = $value;
                $criterionTotals[$key][] = $value;
            }
            $entry['feedback'] = $partData['feedback'] ?? null;
            $sectionsPayload[(string) $sectionId] = $entry;
        }

        $flatCriteria = [];
        foreach (self::SPEAKING_CRITERIA_KEYS as $key) {
            $values = $criterionTotals[$key];
            $flatCriteria[$key] = !empty($values) ? array_sum($values) / count($values) : 0;
        }

        $band = round((array_sum($flatCriteria) / count($flatCriteria)) * 2) / 2;

        $attempt->speaking_criteria = $flatCriteria + ['sections' => $sectionsPayload];
        $attempt->speaking_band = $band;
        $attempt->speaking_graded_by = auth()->id();
        $attempt->speaking_graded_at = time();
        $attempt->save();

        $this->refreshOverallBand($attempt);

        return redirect()
            ->route('panel.ielts_grading.queue')
            ->with(['toast' => [
                'title' => 'Đã lưu',
                'msg' => 'Đã chấm Speaking cho bài của ' . ($attempt->user->full_name ?? $attempt->user->name ?? ''),
                'status' => 'success',
            ]]);
    }

    private function authorizeGrader(): void
    {
        $user = auth()->user();
        $isAllowed = $user && (
            (method_exists($user, 'isTeacher') && $user->isTeacher())
            || (method_exists($user, 'isAdmin') && $user->isAdmin())
            || (method_exists($user, 'isOrganization') && $user->isOrganization())
            || (method_exists($user, 'isManager') && $user->isManager())
        );

        if (!$isAllowed) {
            abort(403);
        }
    }
}