<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementSpeakingQuestion;
use Illuminate\Http\Request;

class PlacementSpeakingQuestionController extends Controller
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

    public function index()
    {
        $questions = PlacementSpeakingQuestion::orderByDesc('created_at')->get();

        return view('admin.placement_speaking.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        PlacementSpeakingQuestion::create([
            'question_text' => $validated['question_text'],
            'is_active'     => true,
            'created_by'    => auth()->id(),
        ]);

        return back()->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã thêm câu hỏi Speaking.']);
    }

    public function update(Request $request, PlacementSpeakingQuestion $placementSpeakingQuestion)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        $placementSpeakingQuestion->update(['question_text' => $validated['question_text']]);

        return back()->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã cập nhật câu hỏi.']);
    }

    public function toggleStatus(PlacementSpeakingQuestion $placementSpeakingQuestion)
    {
        $placementSpeakingQuestion->update(['is_active' => !$placementSpeakingQuestion->is_active]);

        return back()->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã cập nhật trạng thái.']);
    }

    public function destroy(PlacementSpeakingQuestion $placementSpeakingQuestion)
    {
        $placementSpeakingQuestion->delete();

        return back()->with('toast', ['status' => 'success', 'title' => 'Đã xoá', 'msg' => 'Đã xoá câu hỏi Speaking.']);
    }
}