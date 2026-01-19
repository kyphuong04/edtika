<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicWordListWord extends Model
{
    protected $table = 'academic_word_list_words';

    protected $guarded = ['id'];

    protected $fillable = [
        'academic_word_list_id',
        'word',
        'pronunciation',
        'definition',
        'example',
        'translation',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /*
     * Relations
     */
    public function academicWordList()
    {
        return $this->belongsTo(AcademicWordList::class, 'academic_word_list_id', 'id');
    }

    public function userProgress()
    {
        return $this->hasMany(UserWordProgress::class, 'academic_word_list_word_id', 'id');
    }

    /*
     * Helper methods
     */
    public function isLearnedBy($userId)
    {
        return $this->userProgress()
            ->where('user_id', $userId)
            ->where('is_learned', true)
            ->exists();
    }
}
