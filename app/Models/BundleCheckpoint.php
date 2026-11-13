<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bài kiểm tra cấp bundle (Mid-term / Final), gắn với một Quiz.
 */
class BundleCheckpoint extends Model
{
    const TYPE_MID = 'mid';
    const TYPE_FINAL = 'final';

    const TYPES = [self::TYPE_MID, self::TYPE_FINAL];

    protected $table = 'bundle_checkpoints';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

    /**
     * Số module cần hoàn thành để mở checkpoint.
     * Mid mặc định là một nửa số module, Final mặc định là tất cả.
     */
    public function getRequiredModulesCount(int $totalModules): int
    {
        if (!is_null($this->unlock_after_modules)) {
            return min((int)$this->unlock_after_modules, $totalModules);
        }

        if ($this->type === self::TYPE_MID) {
            return (int)ceil($totalModules / 2);
        }

        return $totalModules;
    }
}
