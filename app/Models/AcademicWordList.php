<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AcademicWordList extends Model
{
    protected $table = 'academic_word_lists';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'band_level',
        'word_count',
        'creator_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'word_count' => 'integer',
    ];

    /*
     * Relations
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function words()
    {
        return $this->hasMany(AcademicWordListWord::class, 'academic_word_list_id', 'id')
            ->orderBy('order');
    }

    public function accessUsers()
    {
        return $this->belongsToMany(User::class, 'user_word_list_access', 'academic_word_list_id', 'user_id')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    /*
     * Helper methods
     */
    public function updateWordCount()
    {
        $this->word_count = $this->words()->count();
        $this->save();
    }

    public function hasAccess($userId)
    {
        return $this->accessUsers()->where('user_id', $userId)->exists();
    }
}
