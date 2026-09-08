<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlacementTest extends Model
{
    // Số đề bắt buộc theo từng level, dùng để hiển thị tiến độ ở trang index.
    public const REQUIRED_POOL = [
        'A1'  => 1,
        'A2'  => 2,
        'B1'  => 3,
        'B2'  => 2,
        'B2+' => 1,
    ];

    public const MAX_QUESTIONS = 10;

    // 4 dạng câu hỏi được phép trong đề Placement Test.
    // "Listening" (Q9-10 đề mẫu) = listening_image_choice: MC với 3 ảnh A/B/C + audio bắt buộc.
    public const QUESTION_TYPES = [
        'multiple_choice'        => 'Multiple Choice',
        'sentence_completion'    => 'Sentence Completion',
        'error_correction'       => 'Find & Correct the Mistake',
        'listening_image_choice' => 'Listening - Choose the Image (A/B/C)',
    ];

    protected $fillable = [
        'level',
        'title',
        'description',
        'reading_passages',
        'audio_clips',  
        'status',
        'created_by',
    ];

    protected $casts = [
        'reading_passages' => 'array',
        'audio_clips'      => 'array',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(PlacementQuestion::class)->orderBy('order_index');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isReadyToPublish(): bool
    {
        return $this->questions()->count() === self::MAX_QUESTIONS;
    }

    public function audioClipById(?string $id): ?array
    {
        if (!$id) {
            return null;
        }

        return collect($this->audio_clips ?? [])->firstWhere('id', $id);
    }

    /**
     * Danh sách câu hỏi (dạng public array) đã kèm thông tin NHÓM AUDIO.
     *
     * Nhóm audio = các câu LIỀN KỀ nhau (theo order_index) cùng audio_clip_id.
     * Chỉ câu MỞ ĐẦU nhóm mới có audio_url + audio_group_range; các câu sau
     * trong nhóm không render player riêng mà dùng chung player của câu đầu.
     *
     * Nhờ dựa vào tính liền kề thay vì lưu cứng "từ câu 9 đến câu 10", việc
     * thêm/xoá/đổi thứ tự câu không làm hỏng nhóm.
     */
    public function questionsWithAudioGroups(): \Illuminate\Support\Collection
    {
        $list = $this->questions->values();

        // Đánh dấu index của các câu mở đầu nhóm
        $groupStarts = [];
        $prevClipId = null;

        foreach ($list as $i => $question) {
            if ($question->audio_clip_id && $question->audio_clip_id !== $prevClipId) {
                $groupStarts[$i] = true;
            }
            $prevClipId = $question->audio_clip_id;
        }

        return $list->map(function (PlacementQuestion $question, int $i) use ($list, $groupStarts) {
            $data = $question->toPublicArray();

            $data['audio_group_start'] = isset($groupStarts[$i]);
            $data['audio_url']         = null;
            $data['audio_group_range'] = null;

            if (isset($groupStarts[$i])) {
                $clip = $this->audioClipById($question->audio_clip_id);
                $data['audio_url'] = $clip
                    ? \Illuminate\Support\Facades\Storage::url($clip['path'])
                    : null;

                // Quét tới để biết nhóm kết thúc ở câu nào -> nhãn "9–10"
                $end = $i;
                while (isset($list[$end + 1]) && $list[$end + 1]->audio_clip_id === $question->audio_clip_id) {
                    $end++;
                }

                $data['audio_group_range'] = $end > $i
                    ? ($i + 1) . '–' . ($end + 1)
                    : (string) ($i + 1);
            }

            return $data;
        });
    }
}