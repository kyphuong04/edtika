<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleVocabularyWord extends Model
{
    protected $table = 'bundle_vocabulary_words';

    protected $guarded = ['id'];

    public function vocabularySet()
    {
        return $this->belongsTo(BundleVocabularySet::class, 'vocabulary_set_id', 'id');
    }
}
