<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Lý do học viên làm sai 1 số câu ("Vì sao bạn làm sai câu này?") ở trang
 * chữa bài. Hiện chỉ lưu lại; sau này có thể gửi cho giáo viên tạo đề.
 * Thời gian lưu dạng Unix timestamp (int) giống các bảng IELTS khác.
 */
class IeltsAttemptMistakeReason extends Model
{
    public const OTHER = 'other';

    public const OTHER_MAX = 200;

    protected $table = 'ielts_attempt_mistake_reasons';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

    protected $casts = [
        'slot_index' => 'integer',
    ];

    /** Danh sách lý do theo kỹ năng (mã => nội dung hiển thị). */
    public const REASONS = [
        'listening' => [
            'not_heard' => 'Tôi không nghe rõ thông tin quan trọng.',
            'distractor' => 'Tôi bị đánh lừa bởi thông tin gây nhiễu.',
            'paraphrase' => 'Tôi không nhận ra cách diễn đạt khác của đáp án.',
            'speed' => 'Tôi không theo kịp tốc độ của bài nghe.',
            'vocabulary' => 'Tôi bị vướng từ vựng nên chưa hiểu ý.',
            'torn' => 'Tôi hiểu bài nhưng phân vân giữa các đáp án.',
            'guessed' => 'Tôi chưa xác định được đáp án nên đã chọn ngẫu nhiên.',
            self::OTHER => 'Lý do khác.',
        ],
        'reading' => [
            'focus' => 'Tôi không xác định được câu hỏi đang tập trung vào thông tin nào.',
            'locate' => 'Tôi không biết nên tìm thông tin ở phần nào của bài đọc.',
            'question_words' => 'Tôi chưa hiểu ý nghĩa của các từ để hỏi trong câu hỏi.',
            'wrong_subject' => 'Tôi nhầm đối tượng hoặc thông tin mà câu hỏi đang đề cập đến.',
            'scope' => 'Tôi chưa xác định được phạm vi thông tin cần tìm.',
            'distinguish' => 'Tôi không phân biệt được các ý được hỏi trong câu hỏi.',
            'approach' => 'Tôi hiểu câu hỏi nhưng chưa biết cách tiếp cận để tìm đáp án.',
            'rushed' => 'Tôi đọc câu hỏi quá nhanh nên bỏ sót yêu cầu.',
            'connect' => 'Tôi chưa kết nối được câu hỏi với đoạn văn chứa đáp án.',
            'confused' => 'Tôi bị nhầm giữa thông tin câu hỏi yêu cầu và thông tin được nêu trong bài.',
            self::OTHER => 'Lý do khác.',
        ],
    ];

    public static function reasonsFor(?string $skill): array
    {
        return self::REASONS[$skill] ?? [];
    }

    /** [{ code, label }] cho giao diện. */
    public static function optionsFor(?string $skill): array
    {
        $out = [];
        foreach (self::reasonsFor($skill) as $code => $label) {
            $out[] = ['code' => $code, 'label' => $label];
        }
        return $out;
    }

    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id')->withoutGlobalScope('not_preview');
    }

    public function question()
    {
        return $this->belongsTo(IeltsTestQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
