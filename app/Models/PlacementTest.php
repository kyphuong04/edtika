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

    protected $fillable = [
        'level',
        'title',
        'description',
        'status',
        'created_by',
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