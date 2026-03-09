<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Flashcard extends Model
{
    protected $table = 'user_flashcards';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'word',
        'part_of_speech',
        'pronunciation',
        'definition',
        'example',
        'translation',
    ];

    /*
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wordLists()
    {
        return $this->belongsToMany(WordList::class, 'flashcard_word_list')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('flashcard_word_list.order');
    }
}