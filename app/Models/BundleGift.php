<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Quà tặng đi kèm khi mua khóa học (mục "Quà tặng khóa học" trên trang chi tiết).
 */
class BundleGift extends Model
{
    protected $table = 'bundle_gifts';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }
}
