<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementQuestion extends Model
{
    protected $fillable = [
        'placement_test_id',
        'order_index',
        'type',
        'has_audio',
        'audio_path',
        'linked_to_passage',
        'question_text',
        'options',
        'word_bank',
        'blank_hints',
        'correct_answer',
        'answer_help',
        'points',
    ];

    protected $casts = [
        'has_audio'          => 'boolean',
        'linked_to_passage'  => 'boolean',
        'options'            => 'array',
        'word_bank'          => 'array',
        'blank_hints'        => 'array',
        'correct_answer'     => 'array',
        'points'             => 'float',
    ];

    public function placementTest(): BelongsTo
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function skillLabel(): string
    {
        return $this->has_audio ? 'Listening' : 'Reading/Language use';
    }

    public function toPublicArray(): array
    {
        $blankCount = $this->type === 'sentence_completion'
            ? preg_match_all('/_{2,}/', $this->question_text)
            : 0;

        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'question_text'     => $this->question_text,
            'blank_count'       => $blankCount,
            'options'           => $this->type === 'multiple_choice' ? ($this->options ?? []) : [],
            'image_options'     => $this->type === 'listening_image_choice'
                ? collect($this->options ?? [])->map(fn ($path, $i) => [
                    'label' => chr(65 + $i),
                    'url'   => $path ? \Illuminate\Support\Facades\Storage::url($path) : null,
                ])->values()->all()
                : [],
            'has_audio'         => (bool) $this->has_audio,
            'audio_url'         => $this->audio_path ? \Illuminate\Support\Facades\Storage::url($this->audio_path) : null,
            'word_bank'         => $this->word_bank ?? [],
            'blank_hints'       => $this->blank_hints ?? [],
            'linked_to_passage' => (bool) $this->linked_to_passage,
        ];
    }

    /**
     * Hiển thị đáp án đúng dưới dạng text dễ đọc cho trang admin xem chi tiết bài làm.
     */
    public function correctAnswerDisplay(): string
    {
        $correct = $this->correct_answer;

        if (empty($correct)) {
            return '—';
        }

        switch ($this->type) {
            case 'multiple_choice':
            case 'error_correction':
                return is_array($correct) ? ($correct[0] ?? '—') : (string) $correct;

            case 'listening_image_choice':
                $letter = is_array($correct) ? ($correct[0] ?? '—') : $correct;
                return 'Ảnh ' . $letter;

            case 'sentence_completion':
                if (!is_array($correct)) {
                    return '—';
                }
                return collect($correct)->map(function ($variants, $i) {
                    $variants = is_array($variants) ? $variants : [$variants];
                    return 'Chỗ trống ' . ($i + 1) . ': ' . implode(' / ', $variants);
                })->implode('; ');
        }

        return '—';
    }
}