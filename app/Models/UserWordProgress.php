<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserWordProgress extends Model
{
    protected $table = 'user_word_progress';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'word_list_id',
        'academic_word_list_word_id',
        'word',
        'practice_count',
        'correct_count',
        'last_practiced_at',
        'is_learned',
        'learned_at',
    ];

    protected $casts = [
        'practice_count' => 'integer',
        'correct_count' => 'integer',
        'is_learned' => 'boolean',
        'last_practiced_at' => 'datetime',
        'learned_at' => 'datetime',
    ];

    /*
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class, 'flashcard_id', 'id');
    }

    public function wordList()
    {
        return $this->belongsTo(WordList::class, 'word_list_id', 'id');
    }

    public function academicWordListWord()
    {
        return $this->belongsTo(AcademicWordListWord::class, 'academic_word_list_word_id', 'id');
    }

    /*
     * Scopes
     */
    public function scopeLearned($query)
    {
        return $query->where('is_learned', true);
    }

    public function scopePracticedToday($query)
    {
        return $query->whereDate('last_practiced_at', today());
    }

    public function scopeRecentPractices($query, $days = 30)
    {
        return $query->where('last_practiced_at', '>=', now()->subDays($days));
    }
}
