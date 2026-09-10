<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Flashcard extends Model
{
    const SOURCE_USER   = 'user';
    const SOURCE_BUNDLE = 'bundle';

    protected $table = 'user_flashcards';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'source',
        'bundle_vocabulary_word_id',
        'vocabulary_set_id',
        'word',
        'part_of_speech',
        'pronunciation',
        'definition',
        'example',
        'translation',
    ];

    protected $casts = [
        'bundle_vocabulary_word_id' => 'integer',
        'vocabulary_set_id'         => 'integer',
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

    public function bundleWord()
    {
        return $this->belongsTo(BundleVocabularyWord::class, 'bundle_vocabulary_word_id', 'id');
    }

    /*
     * Helpers
     */
    public function isTeacherWord(): bool
    {
        return $this->source === self::SOURCE_BUNDLE && !empty($this->bundle_vocabulary_word_id);
    }

    /*
     * Scopes
     */
    public function scopeFromBundle($query)
    {
        return $query->where('source', self::SOURCE_BUNDLE);
    }

    public function scopeFromUser($query)
    {
        return $query->where('source', self::SOURCE_USER);
    }
}