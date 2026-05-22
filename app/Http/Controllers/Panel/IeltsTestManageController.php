<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\Notification;
use App\User;
use App\Http\Controllers\Panel\IeltsTestInlineController;
use Illuminate\Http\Request;

class IeltsTestManageController extends Controller
{
    /**
     * Simple index that forwards to the inline creation chooser or lists user's tests.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = IeltsTest::with(['sections', 'attempts'])
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

    /**
     * Submit a test for approval (mark pending and notify approvers)
     */
    public function submitForApproval($id)
    {
        $test = IeltsTest::findOrFail($id);

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
            Notification::create([
                'user_id' => $approver->id,
                'sender' => Notification::$SystemSender,
                'title' => 'New IELTS Test Pending Approval',
                'message' => "'{$test->title}' is ready for review.",
                'type' => 'single',
                'created_at' => time(),
            ]);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Submitted',
                'msg' => 'Test submitted for approval.',
                'status' => 'success',
            ]]);
    }

    // (no additional helpers required)
}
