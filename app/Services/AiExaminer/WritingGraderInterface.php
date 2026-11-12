<?php

namespace App\Services\AiExaminer;

/**
 * Hợp đồng (contract) giữa EDTIKA và AI service chấm Writing.
 *
 * Bản mẫu UI dùng MockWritingGrader. Khi team AI bàn giao, viết một class mới
 * (vd: HttpWritingGrader) implement interface này, trả dữ liệu ĐÚNG cấu trúc bên dưới,
 * rồi đổi config('ai_examiner.driver'). Controller + giao diện không phải sửa.
 *
 * ── INPUT (submission) ───────────────────────────────────────────────
 * [
 *   'id'         => 'aiw_xxx',          // EDTIKA tự sinh, dùng chống trùng
 *   'task'       => 1 | 2,
 *   'prompt'     => 'Đề bài...',
 *   'chart_url'  => 'https://...png' | null,   // chỉ Task 1
 *   'essay'      => 'Bài làm...',
 *   'created_at' => unix timestamp,
 * ]
 *
 * ── STATUS (màn "Đang chấm bài của bạn...") ──────────────────────────
 * [
 *   'status'   => 'pending' | 'processing' | 'done' | 'failed',
 *   'progress' => 0..100,
 *   'criteria' => [ ['key' => 'ta', 'name' => 'Task Achievement', 'status' => 'done|processing|waiting'], ... ],
 *   'error'    => null | ['code' => '...', 'message' => '...'],
 * ]
 *
 * ── RESULT (màn Overall + Breakdown) ─────────────────────────────────
 * [
 *   'submission_id' => 'aiw_xxx',
 *   'task'          => 1,
 *   'model_version' => 'mock-0.1',
 *   'overall_band'  => 6.0,
 *   'focus_criterion' => 'cc',                  // tiêu chí yếu nhất → "Gợi ý cải thiện" ở Overall
 *   'criteria' => [
 *     [
 *       'key' => 'ta', 'code' => 'TA', 'name' => 'Task Achievement',
 *       'band' => 6.0,
 *       'summary' => 'Nhận xét ngắn cho thẻ ở Overall',
 *       'priorities' => ['Ưu tiên cải thiện 1', ...],
 *       'tips' => [ ['text' => '...', 'link_label' => '...', 'link_url' => '...'] ],
 *       'sub_criteria' => [
 *         [
 *           'key' => 'task_fulfillment', 'name' => 'Task Fulfillment', 'band' => 6.0,
 *           'comment'      => 'Nhận xét chung',
 *           'strengths'    => ['Điểm mạnh cụ thể', ...],
 *           'issues'       => [
 *             [
 *               'no' => 1,                       // số thứ tự badge (đánh số trong 1 tiêu chí lớn)
 *               'title' => 'Missing key comparison',
 *               'description' => 'Mô tả ngắn',
 *               'category' => 'Phân loại lỗi',
 *               'anchor' => 'đoạn văn trích từ bài làm',
 *               'start' => 123, 'end' => 160,     // vị trí ký tự trong essay (null nếu không định vị được)
 *               'explanation' => 'Giải thích chi tiết',
 *               'suggestion' => 'Đề xuất khắc phục',
 *               'correction' => 'Câu sửa',
 *             ],
 *           ],
 *           'improvements' => ['Hướng cải thiện', ...],
 *         ],
 *       ],
 *     ],
 *   ],
 *   'samples' => [ '6.5' => ['text' => '...', 'words' => 170], '7.0' => [...], '8.0' => [...] ],
 * ]
 */
interface WritingGraderInterface
{
    /** Gửi bài sang AI. Trả về submission đã bổ sung thông tin job. */
    public function submit(array $submission): array;

    /** Trạng thái chấm (dùng cho màn chờ). */
    public function status(array $submission): array;

    /** Kết quả chấm đầy đủ (chỉ gọi khi status = done). */
    public function result(array $submission): array;
}
