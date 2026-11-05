<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Lịch sử tra từ ở Dictionary Hub (theo tài khoản).
 */
class DictionarySearchHistory extends Model
{
    /** Số mục hiện trong dropdown. */
    public const SHOW_LIMIT = 10;

    /** Số mục tối đa giữ lại cho mỗi người dùng. */
    public const KEEP_LIMIT = 30;

    protected $table = 'dictionary_search_histories';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

    protected $casts = [
        'searched_at' => 'integer',
    ];

    /** Ghi (hoặc đẩy lên đầu) 1 từ vào lịch sử, xoá bớt mục cũ. */
    public static function record(int $userId, string $query): void
    {
        $query = mb_substr(trim(mb_strtolower($query)), 0, 100);

        if ($query === '') {
            return;
        }

        self::updateOrCreate(
            ['user_id' => $userId, 'query' => $query],
            ['searched_at' => time()]
        );

        $keepIds = self::where('user_id', $userId)
            ->orderByDesc('searched_at')
            ->orderByDesc('id')
            ->limit(self::KEEP_LIMIT)
            ->pluck('id');

        self::where('user_id', $userId)->whereNotIn('id', $keepIds)->delete();
    }
}
