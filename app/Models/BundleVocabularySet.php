<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BundleVocabularySet extends Model
{
    protected $table = 'bundle_vocabulary_sets';

    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function words()
    {
        return $this->hasMany(BundleVocabularyWord::class, 'vocabulary_set_id', 'id')->orderBy('sort_order');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
