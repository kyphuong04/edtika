<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Represents a single test attempt by a student.
 * 
 * Each attempt tracks progress through listening, reading, writing, and speaking
 * sections, along with scores and band calculations.
 */
class IeltsTestAttempt extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_attempts';
    
    protected $guarded = ['id'];
    
    // SAU
    protected $casts = [
        'started_at' => 'integer',
        'paused_at' => 'integer',
        'completed_at' => 'integer',
        'updated_at' => 'integer',
        'listening_finished_at' => 'integer',
        'reading_finished_at' => 'integer',
        'writing_finished_at' => 'integer',
        'speaking_finished_at' => 'integer',
        'listening_completed' => 'boolean',
        'reading_completed' => 'boolean',
        'writing_completed' => 'boolean',
        'speaking_completed' => 'boolean',
        'listening_score' => 'float',
        'reading_score' => 'float',
        'writing_score' => 'float',
        'speaking_score' => 'float',
        'listening_band' => 'float',
        'reading_band' => 'float',
        'writing_band' => 'float',
        'speaking_band' => 'float',
        'overall_band' => 'float',
        'progress_percentage' => 'float',
        'writing_criteria' => 'array',
        'speaking_criteria' => 'array',
        'writing_graded_at' => 'integer',
        'speaking_graded_at' => 'integer',
        'skill_time_budget' => 'array',
    ];

    /**
     * Thời lượng CỐ ĐỊNH cho Mock Test theo từng skill (giây). Practice Test
     * không dùng map này — đếm lên không giới hạn. PHẢI khớp với
     * MOCK_SKILL_DURATIONS_SECONDS trong state.js (preview) để 2 nơi không
     * lệch nhau; nếu đổi số phút, sửa cả 2 chỗ.
     */
    public const MOCK_SKILL_DURATIONS_SECONDS = [
        'listening' => 32 * 60,
        'reading' => 60 * 60,
        'writing' => 60 * 60,
    ];

    /** Mock Test — mỗi Part của Speaking có ngân sách riêng 5 phút. */
    public const MOCK_SPEAKING_PART_DURATION_SECONDS = 5 * 60;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }
    
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
    
    public function answers()
    {
        return $this->hasMany(IeltsTestAnswer::class, 'attempt_id');
    }
    
    public function currentSection()
    {
        return $this->belongsTo(IeltsTestSection::class, 'current_section_id');
    }
    
    public function quizResult()
    {
        return $this->belongsTo(\App\QuizzesResult::class, 'quiz_result_id');
    }
    
    public function writingGrader()
    {
        return $this->belongsTo(\App\User::class, 'writing_graded_by');
    }
    
    public function speakingGrader()
    {
        return $this->belongsTo(\App\User::class, 'speaking_graded_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checks
    |--------------------------------------------------------------------------
    */

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    public function isPaused()
    {
        return $this->status === 'paused';
    }
    
    /*
    |--------------------------------------------------------------------------
    | Section Navigation
    |--------------------------------------------------------------------------
    */

    /**
     * Find the next section in sequence.
     *
     * @return IeltsTestSection|null
     */
    public function getNextSection()
    {
        $allSections = $this->test->sections()->orderBy('sort_order')->get();

        if ($allSections->isEmpty()) {
            return null;
        }

        $currentSectionId = $this->current_section_id;
        $currentIndex = -1;

        foreach ($allSections as $index => $section) {
            if ($section->id == $currentSectionId) {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex >= 0 && $currentIndex < $allSections->count() - 1) {
            return $allSections[$currentIndex + 1];
        }

        return null;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Progress Tracking
    |--------------------------------------------------------------------------
    */

    /**
     * Update the progress percentage based on answered questions.
     *
     * @return void
     */
    public function updateProgress()
    {
        $totalAnswered = $this->answers()->whereNotNull('answer_text')->count();
        $this->total_questions_answered = $totalAnswered;

        if ($this->total_questions > 0) {
            $this->progress_percentage = ($totalAnswered / $this->total_questions) * 100;
        }

        $this->updated_at = time();
        $this->save();
    }
    
    /**
     * Mark a specific skill section as completed.
     *
     * @param string $skill One of: listening, reading, writing, speaking
     * @return void
     */
    public function completeSection($skill)
    {
        $completedField = $skill . '_completed';
        $finishedField = $skill . '_finished_at';

        if (Schema::hasColumn($this->getTable(), $completedField)) {
            $this->{$completedField} = true;
        }

        if (Schema::hasColumn($this->getTable(), $finishedField)) {
            $this->{$finishedField} = time();
        }

        $this->updated_at = time();
        $this->save();
    }
    
    // SAU
    /*
    |--------------------------------------------------------------------------
    | Time Management (legacy — tổng thời lượng cả bài, giữ để không phá vỡ
    | code cũ còn gọi tới, nhưng KHÔNG dùng cho luồng attempt mới)
    |--------------------------------------------------------------------------
    */

    /**
     * @deprecated Dùng getScopeTimeRemaining()/hasScopeExpired() thay thế —
     * hàm này tính theo total_duration CẢ BÀI, không còn đúng với mô hình
     * thời lượng riêng từng skill.
     */
    public function getTimeRemaining()
    {
        if ($this->remaining_time_seconds !== null) {
            return $this->remaining_time_seconds;
        }

        $totalDuration = $this->test->total_duration * 60;
        $elapsed = time() - $this->started_at;

        return max(0, $totalDuration - $elapsed);
    }

    /**
     * @deprecated Dùng hasScopeExpired() thay thế.
     */
    public function hasExpired()
    {
        $totalDuration = $this->test->total_duration ?? 0;

        if ($totalDuration <= 0) {
            return false;
        }

        return $this->getTimeRemaining() <= 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Time Budget theo từng "scope" (skill, hoặc từng Part của Speaking)
    |--------------------------------------------------------------------------
    |
    | scope_key:
    |   - "listening" / "reading" / "writing" cho 3 skill tính theo cả section
    |   - "speaking-part-{partId}" cho từng Part của Speaking (Mock only)
    |
    | Server là nguồn sự thật duy nhất cho thời gian còn lại — client chỉ
    | hiển thị, không được tự báo cáo số giây, tránh gian lận đổi giờ máy.
    */

    /**
     * Xác định scope_key hiện tại dựa theo section/part đang active.
     * $activePartId chỉ có ý nghĩa khi skill là 'speaking'.
     */
    public function resolveScopeKey(string $skill, ?int $activePartId = null): string
    {
        if ($skill === 'speaking' && $this->test->isMockTest() && $activePartId) {
            return 'speaking-part-' . $activePartId;
        }

        return $skill;
    }

    /**
     * Ngân sách thời gian (giây) cho 1 scope — null nghĩa là KHÔNG giới hạn
     * (Practice Test, hoặc skill không nằm trong MOCK_SKILL_DURATIONS_SECONDS
     * như Grammar/Vocabulary).
     */
    public function getScopeBudgetSeconds(string $scopeKey): ?int
    {
        if (!$this->test->isMockTest()) {
            return null;
        }

        if (str_starts_with($scopeKey, 'speaking-part-')) {
            return self::MOCK_SPEAKING_PART_DURATION_SECONDS;
        }

        return self::MOCK_SKILL_DURATIONS_SECONDS[$scopeKey] ?? null;
    }

    /**
     * Kích hoạt 1 scope: nếu scope này đang active rồi (started_at đã có)
     * thì KHÔNG làm gì (đồng hồ tiếp tục chạy, không reset) — đây là yêu
     * cầu "chuyển Part cùng skill không reset giờ". Nếu scope trước đó
     * (khác scope này) đang active, tự động chốt nó lại (cộng dồn used_seconds)
     * trước khi mở scope mới.
     *
     * Gọi hàm này ở: lúc vào takeTest() lần đầu cho 1 section, trong
     * finishSection() khi chuyển sang section kế tiếp, và ở endpoint mới
     * cho việc chuyển Part của Speaking.
     */
    public function activateScope(string $scopeKey): void
    {
        $budget = $this->skill_time_budget ?? [];

        // Nếu scope này đã đang active (started_at != null và chưa bị chốt)
        // thì không đụng gì — giữ nguyên đồng hồ đang chạy.
        if (!empty($budget[$scopeKey]['started_at'])) {
            return;
        }

        // Chốt mọi scope khác đang active (phòng trường hợp có scope cũ
        // chưa kịp chốt do lỗi mạng/crash trước đó).
        foreach ($budget as $key => $entry) {
            if ($key !== $scopeKey && !empty($entry['started_at'])) {
                $budget[$key] = $this->settleScopeEntry($entry);
            }
        }

        if (!isset($budget[$scopeKey])) {
            $budget[$scopeKey] = [
                'budget_seconds' => $this->getScopeBudgetSeconds($scopeKey),
                'used_seconds' => 0,
                'started_at' => null,
            ];
        }

        $budget[$scopeKey]['started_at'] = time();

        $this->skill_time_budget = $budget;
        $this->updated_at = time();
        $this->save();
    }

    /**
     * Chốt 1 scope: cộng dồn thời gian đã trôi qua vào used_seconds, xoá
     * started_at (đánh dấu không còn active). Gọi khi rời khỏi scope này
     * (chuyển section/part khác) hoặc khi hết giờ tự động nộp.
     */
    public function settleScope(string $scopeKey): void
    {
        $budget = $this->skill_time_budget ?? [];

        if (empty($budget[$scopeKey])) {
            return;
        }

        $budget[$scopeKey] = $this->settleScopeEntry($budget[$scopeKey]);

        $this->skill_time_budget = $budget;
        $this->updated_at = time();
        $this->save();
    }

    private function settleScopeEntry(array $entry): array
    {
        if (!empty($entry['started_at'])) {
            $entry['used_seconds'] = (int) ($entry['used_seconds'] ?? 0) + (time() - (int) $entry['started_at']);
            $entry['started_at'] = null;
        }

        return $entry;
    }

    /**
     * Số giây còn lại của 1 scope. null = không giới hạn (Practice Test).
     * Luôn tính "live" dựa trên started_at hiện tại nếu scope đang active,
     * không cần client tự báo cáo.
     */
    public function getScopeTimeRemaining(string $scopeKey): ?int
    {
        $budgetSeconds = $this->getScopeBudgetSeconds($scopeKey);

        if ($budgetSeconds === null) {
            return null;
        }

        $entry = ($this->skill_time_budget ?? [])[$scopeKey] ?? [
            'used_seconds' => 0,
            'started_at' => null,
        ];

        $usedSeconds = (int) ($entry['used_seconds'] ?? 0);

        if (!empty($entry['started_at'])) {
            $usedSeconds += time() - (int) $entry['started_at'];
        }

        return max(0, $budgetSeconds - $usedSeconds);
    }

    /**
     * Scope đã hết giờ chưa. Practice Test (budget null) không bao giờ hết
     * giờ theo scope — chỉ có Mock Test mới bị chặn ở đây.
     */
    public function hasScopeExpired(string $scopeKey): bool
    {
        $remaining = $this->getScopeTimeRemaining($scopeKey);

        return $remaining !== null && $remaining <= 0;
    }
}
