<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $table = 'flashcards';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'word',
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
