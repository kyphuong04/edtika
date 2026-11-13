<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Một ô trong slider "Chi tiết và nội dung" trên trang chi tiết khóa học.
 */
class BundleHighlight extends Model
{
    protected $table = 'bundle_highlights';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }
}
