<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GĐ6 - Pre Lesson của học viên trong một lesson: đã hoàn thành chưa (completed_at)
 * và Word List flashcard "Khóa học: ..." chứa từ của khóa (word_list_id).
 * Số từ "đã thuộc" lấy từ user_word_progress (đồng bộ với Từ điển & Flashcard).
 */
class BundlePreLessonProgress extends Model
{
    protected $table = 'bundle_pre_lesson_progress';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];
}
