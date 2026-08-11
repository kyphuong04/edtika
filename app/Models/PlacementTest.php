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
        'status',
        'created_by',
    ];

    protected $casts = [
        'reading_passages' => 'array',
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
}