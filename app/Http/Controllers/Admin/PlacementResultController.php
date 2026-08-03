<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\PlacementTest;
use App\Models\PlacementQuestion;
use App\Models\IeltsPlacementAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlacementResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !(method_exists($user, 'isManager') && ($user->isManager() || $user->isCeo()))) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    /**
     * Trả về HTML (partial) hiển thị kết quả Placement Test + Speaking của 1
     * học viên, dùng để load vào modal qua AJAX trên trang danh sách học viên.
     */
    public function show(User $user)
    {
        $attempt = $user->latestPlacementResult;

        if ($attempt) {
            $attempt->load('speakingQuestion');
        }

        $speakingAudioUrl = ($attempt && $attempt->speaking_recording_path)
            ? Storage::url($attempt->speaking_recording_path)
            : null;

        return view('admin.placement_tests.result_modal_content', compact('user', 'attempt', 'speakingAudioUrl'));
    }

    public function detail(User $user)
    {
        $attempt = $user->latestPlacementResult;

        if (!$attempt) {
            abort(404, 'Học viên này chưa có kết quả Placement Test.');
        }

        $attempt->load('speakingQuestion');

        $testIds = $attempt->test_ids_taken ?? [];
        $tests = PlacementTest::with('questions')->whereIn('id', $testIds)->get()->keyBy('id');

        $answersByQuestion = IeltsPlacementAttemptAnswer::where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('placement_question_id');

        $testBlocks = collect($testIds)->values()->map(function ($testId, $i) use ($tests, $answersByQuestion, $attempt) {
            $test = $tests->get($testId);

            if (!$test) {
                return null;
            }

            $questions = $test->questions->map(function (PlacementQuestion $q) use ($answersByQuestion) {
                $record = $answersByQuestion->get($q->id);

                return [
                    'question'        => $q->toPublicArray(),
                    'given'           => $record->answer_given ?? null,
                    'is_correct'      => $record->is_correct ?? false,
                    'correct_display' => $q->correctAnswerDisplay(),
                    'answer_help'     => $q->answer_help,
                ];
            });

            return [
                'test'      => $test,
                'index'     => $i,
                'is_scored' => $attempt->isStepScored($i),
                'questions' => $questions,
            ];
        })->filter()->values();

        $speakingAudioUrl = $attempt->speaking_recording_path
            ? Storage::url($attempt->speaking_recording_path)
            : null;

        return view('admin.placement_tests.attempt_detail', compact('user', 'attempt', 'testBlocks', 'speakingAudioUrl'));
    }
}