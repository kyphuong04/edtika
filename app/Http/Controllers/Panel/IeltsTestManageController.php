<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsPracticeCategory;
use App\Models\IeltsTest;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsTestSection;
use App\Models\Notification;
use App\User;
use Illuminate\Http\Request;

class IeltsTestManageController extends Controller
{
    /**
     * Simple index that forwards to the inline creation chooser or lists user's tests.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = IeltsTest::with(['sections.questions', 'attempts'])
            ->where('created_by', $user->id);

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $tests = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $tests->count(),
            'published' => $tests->where('status', 'published')->count(),
        ];

        return view('design_1.panel.ielts_tests_manage.index', compact('tests', 'stats'));
    }

    public function edit(Request $request, $id)
    {
        $test = $this->findOwnedTestOrFail($id);
        $categories = IeltsPracticeCategory::query()->get()->groupBy('skill');

        return view('design_1.panel.ielts_tests_manage.edit', compact('test', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:mock,practice'],
            'format' => ['required', 'in:computer,paper,both'],
            'total_duration' => ['required', 'integer', 'min:1'],
            'target_band_min' => ['nullable', 'numeric'],
            'target_band_max' => ['nullable', 'numeric'],
            'practice_mode' => ['nullable', 'string', 'max:50'],
            'practice_category_id' => ['nullable', 'integer'],
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';
        $data['created_at'] = time();

        $test = IeltsTest::create($data);

        return redirect()
            ->route('panel.my_ielts_tests.edit', $test->id)
            ->with('success', trans('update.created_successfully'));
    }

    public function update(Request $request, $id)
    {
        $test = $this->findOwnedTestOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_duration' => ['required', 'integer', 'min:1'],
            'target_band_min' => ['nullable', 'numeric'],
            'target_band_max' => ['nullable', 'numeric'],
            'practice_mode' => ['nullable', 'string', 'max:50'],
            'practice_category_id' => ['nullable', 'integer'],
        ]);

        $test->update($data);

        return redirect()
            ->route('panel.my_ielts_tests.edit', $test->id)
            ->with('success', trans('update.updated_successfully'));
    }

    public function destroy($id)
    {
        $test = $this->findOwnedTestOrFail($id);

        foreach ($test->sections as $section) {
            $section->questions()->delete();
            $section->delete();
        }

        $test->attempts()->delete();
        $test->delete();

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with('success', trans('update.deleted_successfully'));
    }

    public function duplicate($id)
    {
        $test = $this->findOwnedTestOrFail($id);

        $copy = $test->replicate();
        $copy->title = $test->title . ' (Copy)';
        $copy->status = 'draft';
        $copy->created_by = auth()->id();
        $copy->created_at = time();
        $copy->save();

        foreach ($test->sections as $section) {
            $sectionCopy = $section->replicate();
            $sectionCopy->test_id = $copy->id;
            $sectionCopy->created_at = time();
            $sectionCopy->save();

            foreach ($section->questions as $question) {
                $questionCopy = $question->replicate();
                $questionCopy->section_id = $sectionCopy->id;
                $questionCopy->created_at = time();
                $questionCopy->save();
            }
        }

        return redirect()
            ->route('panel.my_ielts_tests.edit', $copy->id)
            ->with('success', trans('update.duplicated_successfully'));
    }

    public function sections($id)
    {
        $test = $this->findOwnedTestOrFail($id);

        return view('design_1.panel.ielts_tests_manage.sections', compact('test'));
    }

    public function createSection($id)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = new IeltsTestSection([
            'skill' => 'listening',
            'duration_minutes' => 30,
            'sort_order' => ($test->sections->count() ?? 0) + 1,
            'question_start' => 1,
            'question_end' => 1,
        ]);

        return view('design_1.panel.ielts_tests_manage.section_create', compact('test', 'section'));
    }

    public function storeSection(Request $request, $id)
    {
        $test = $this->findOwnedTestOrFail($id);

        $data = $request->validate([
            'skill' => ['required', 'in:listening,reading,writing,speaking'],
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'passage_text' => ['nullable', 'string'],
        ]);

        $data['test_id'] = $test->id;
        $data['created_at'] = time();

        IeltsTestSection::create($data);

        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with('success', trans('update.created_successfully'));
    }

    public function editSection($id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::with('questions')->where('test_id', $test->id)->findOrFail($sectionId);

        return view('design_1.panel.ielts_tests_manage.section_edit', compact('test', 'section'));
    }

    public function updateSection(Request $request, $id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);

        $data = $request->validate([
            'skill' => ['required', 'in:listening,reading,writing,speaking'],
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'passage_text' => ['nullable', 'string'],
        ]);

        $section->update($data);

        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with('success', trans('update.updated_successfully'));
    }

    public function deleteSection($id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);

        $section->questions()->delete();
        $section->delete();

        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with('success', trans('update.deleted_successfully'));
    }

    public function questions($id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::with('questions')->where('test_id', $test->id)->findOrFail($sectionId);

        return view('design_1.panel.ielts_tests_manage.questions', compact('test', 'section'));
    }

    public function createQuestion($id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);

        return view('design_1.panel.ielts_tests_manage.question_create', compact('test', 'section'));
    }

    public function storeQuestion(Request $request, $id, $sectionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);

        $data = $request->validate([
            'question_number' => ['required', 'integer'],
            'question_type' => ['required', 'string', 'max:50'],
            'points' => ['required', 'numeric', 'min:0'],
            'question_text' => ['required', 'string'],
            'instruction' => ['nullable', 'string'],
            'correct_answer' => ['nullable', 'string'],
            'accept_synonyms' => ['nullable', 'string'],
            'max_words' => ['nullable', 'integer', 'min:1'],
            'explanation' => ['nullable', 'string'],
            'table_structure' => ['nullable', 'string'],
        ]);

        $answerOptions = array_filter([
            'A' => $request->input('option_a'),
            'B' => $request->input('option_b'),
            'C' => $request->input('option_c'),
            'D' => $request->input('option_d'),
        ], static fn ($value) => $value !== null && $value !== '');

        $questionData = [];
        if ($request->filled('task_image_url')) {
            $questionData['task_image'] = $request->input('task_image_url');
        }

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_number' => $data['question_number'],
            'question_order' => $data['question_number'],
            'sort_order' => $data['question_number'],
            'question_type' => $data['question_type'],
            'question_text' => $data['question_text'],
            'instruction' => $data['instruction'] ?? null,
            'correct_answer' => $data['correct_answer'] ?? null,
            'accept_synonyms' => $data['accept_synonyms'] ?? null,
            'auto_gradable' => $request->boolean('auto_gradable'),
            'case_sensitive' => $request->boolean('case_sensitive'),
            'max_words' => $data['max_words'] ?? null,
            'explanation' => $data['explanation'] ?? null,
            'points' => $data['points'],
            'answer_options' => !empty($answerOptions) ? json_encode($answerOptions) : null,
            'table_structure' => $data['table_structure'] ?? null,
            'question_data' => !empty($questionData) ? json_encode($questionData) : null,
            'created_at' => time(),
        ]);

        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with('success', trans('update.created_successfully'));
    }

    public function editQuestion($id, $sectionId, $questionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);
        $question = IeltsTestQuestion::where('section_id', $section->id)->findOrFail($questionId);

        return view('design_1.panel.ielts_tests_manage.question_edit', compact('test', 'section', 'question'));
    }

    public function updateQuestion(Request $request, $id, $sectionId, $questionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);
        $question = IeltsTestQuestion::where('section_id', $section->id)->findOrFail($questionId);

        $data = $request->validate([
            'question_number' => ['required', 'integer'],
            'question_type' => ['required', 'string', 'max:50'],
            'points' => ['required', 'numeric', 'min:0'],
            'question_text' => ['required', 'string'],
            'instruction' => ['nullable', 'string'],
            'correct_answer' => ['nullable', 'string'],
            'accept_synonyms' => ['nullable', 'string'],
            'max_words' => ['nullable', 'integer', 'min:1'],
            'explanation' => ['nullable', 'string'],
            'table_structure' => ['nullable', 'string'],
        ]);

        $answerOptions = array_filter([
            'A' => $request->input('option_a'),
            'B' => $request->input('option_b'),
            'C' => $request->input('option_c'),
            'D' => $request->input('option_d'),
        ], static fn ($value) => $value !== null && $value !== '');

        $questionData = [];
        if ($request->filled('task_image_url')) {
            $questionData['task_image'] = $request->input('task_image_url');
        }

        $question->update([
            'question_number' => $data['question_number'],
            'question_order' => $data['question_number'],
            'sort_order' => $data['question_number'],
            'question_type' => $data['question_type'],
            'question_text' => $data['question_text'],
            'instruction' => $data['instruction'] ?? null,
            'correct_answer' => $data['correct_answer'] ?? null,
            'accept_synonyms' => $data['accept_synonyms'] ?? null,
            'auto_gradable' => $request->boolean('auto_gradable'),
            'case_sensitive' => $request->boolean('case_sensitive'),
            'max_words' => $data['max_words'] ?? null,
            'explanation' => $data['explanation'] ?? null,
            'points' => $data['points'],
            'answer_options' => !empty($answerOptions) ? json_encode($answerOptions) : null,
            'table_structure' => $data['table_structure'] ?? null,
            'question_data' => !empty($questionData) ? json_encode($questionData) : null,
        ]);

        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with('success', trans('update.updated_successfully'));
    }

    public function deleteQuestion($id, $sectionId, $questionId)
    {
        $test = $this->findOwnedTestOrFail($id);
        $section = IeltsTestSection::where('test_id', $test->id)->findOrFail($sectionId);
        $question = IeltsTestQuestion::where('section_id', $section->id)->findOrFail($questionId);

        $question->delete();

        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with('success', trans('update.deleted_successfully'));
    }

    /**
     * Submit a test for approval (mark pending and notify approvers)
     */
    public function submitForApproval($id)
    {
        $test = IeltsTest::findOrFail($id);

        /** @var \App\User $user */
        $user = auth()->user();

        // Only allow owner or users who can approve tests
        if ($test->created_by !== $user->id && !$user->canApproveIeltsTests()) {
            abort(403);
        }

        $test->update([
            'status' => 'pending_approval',
            'submitted_for_approval_at' => time(),
        ]);

        // notify approvers
        $approvers = User::query()
            ->where('id', '!=', $user->id)
            ->get()
            ->filter(function (User $u) {
                return $u->canApproveIeltsTests();
            });

        foreach ($approvers as $approver) {
            $notificationData = [
                'user_id' => $approver->id,
                'sender' => Notification::$SystemSender,
                'title' => 'New IELTS Test Pending Approval',
                'message' => "'{$test->title}' is ready for review.",
                'type' => 'single',
                'created_at' => time(),
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'action_url')) {
                $notificationData['action_url'] = getAdminPanelUrl("/ielts-tests/{$test->id}/review");
            }

            Notification::create($notificationData);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Submitted',
                'msg' => 'Test submitted for approval.',
                'status' => 'success',
            ]]);
    }

    private function findOwnedTestOrFail($id): IeltsTest
    {
        return IeltsTest::with(['sections.questions', 'attempts'])
            ->where('created_by', auth()->id())
            ->findOrFail($id);
    }
}
