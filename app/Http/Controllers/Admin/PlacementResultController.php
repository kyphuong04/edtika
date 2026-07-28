<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
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
}