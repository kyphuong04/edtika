<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Folder phân cấp bộ từ vựng (tab Academic Word List) do teacher tự tạo.
 */
class VocabularyFolder extends Model
{
    protected $table = 'vocabulary_folders';

    protected $guarded = ['id'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public const LEVELS = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];

    /** Icon có sẵn cho thẻ folder: key => nhãn hiển thị khi chọn. */
    public const ICONS = [
        'book'   => 'Sách',
        'folder' => 'Thư mục',
        'target' => 'Mục tiêu',
        'chart'  => 'Biểu đồ',
        'globe'  => 'Quả địa cầu',
        'chat'   => 'Hội thoại',
        'bulb'   => 'Bóng đèn',
        'star'   => 'Ngôi sao',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function sets()
    {
        return $this->hasMany(BundleVocabularySet::class, 'folder_id', 'id');
    }

    public static function iconKey(?string $icon): string
    {
        return array_key_exists((string) $icon, self::ICONS) ? (string) $icon : 'book';
    }
}
