<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class WordList extends Model
{
    protected $table = 'word_lists';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'level',
        'is_public',
        'word_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'word_count' => 'integer',
    ];

    /*
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function flashcards()
    {
        return $this->belongsToMany(Flashcard::class, 'flashcard_word_list')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('flashcard_word_list.order');
    }

    /*
     * Helper methods
     */
    public function updateWordCount()
    {
        $this->word_count = $this->flashcards()->count();
        $this->save();
    }
}
