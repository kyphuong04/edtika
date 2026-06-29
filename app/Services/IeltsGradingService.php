<?php

namespace App\Services;

use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IeltsGradingService
{
    // band corenversion table
    protected array $bandConversion = [
        40 => 9.0, 39 => 8.5, 38 => 8.5, 37 => 8.0, 36 => 8.0,
        35 => 7.5, 34 => 7.5, 33 => 7.0, 32 => 7.0, 31 => 6.5,
        30 => 6.5, 29 => 6.5, 28 => 6.0, 27 => 6.0, 26 => 6.0,
        25 => 5.5, 24 => 5.5, 23 => 5.5, 22 => 5.0, 21 => 5.0,
        20 => 5.0, 19 => 5.0, 18 => 4.5, 17 => 4.5, 16 => 4.5,
        15 => 4.0, 14 => 4.0, 13 => 4.0, 12 => 3.5, 11 => 3.5,
        10 => 3.0, 9 => 3.0, 8 => 2.5, 7 => 2.5, 6 => 2.0,
        5 => 2.0, 4 => 1.5, 3 => 1.0, 2 => 1.0, 1 => 0.5, 0 => 0.0,
    ];

   /// atuo-grade entire attempt
    public function gradeAttempt(IeltsTestAttempt $attempt): array
    {
        $results = [
            'listening' => null,
            'reading' => null,
            'writing' => null,
            'speaking' => null,
            'overall' => null,
        ];

        // Auto-grade Listening
        if ($attempt->test->has_listening) {
            $results['listening'] = $this->gradeListening($attempt);
        }

        // Auto-grade Reading
        if ($attempt->test->has_reading) {
            $results['reading'] = $this->gradeReading($attempt);
        }

        // Writing & Speaking need manual/AI grading
        if ($attempt->test->has_writing) {
            $results['writing'] = $this->checkWritingStatus($attempt);
        }

        if ($attempt->test->has_speaking) {
            $results['speaking'] = $this->checkSpeakingStatus($attempt);
        }

        // Calculate overall
        $results['overall'] = $this->calculateOverall($results);

        return $results;
    }

    // auto-grade Listening section
    public function gradeListening(IeltsTestAttempt $attempt): array
    {
        $answers = $attempt->answers()
            ->whereHas('question.section', fn($q) => $q->where('skill', 'listening'))
            ->with('question')
            ->get();

        $correct = 0;
        $total = $answers->count();

        foreach ($answers as $answer) {
            if ($answer->question && $answer->question->auto_gradable) {
                $isCorrect = $this->checkAnswer($answer);
                $answer->update([
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? ($answer->question->points ?? 1) : 0,
                ]);
                if ($isCorrect) $correct++;
            }
        }

        $band = $this->rawToBand($correct);
        
        $attempt->update([
            'listening_score' => $correct,
            'listening_completed' => true,
        ]);

        return [
            'raw_score' => $correct,
            'total' => $total,
            'band' => $band,
            'status' => 'graded',
        ];
    }

    // auto-grade Reading section
    public function gradeReading(IeltsTestAttempt $attempt): array
    {
        $answers = $attempt->answers()
            ->whereHas('question.section', fn($q) => $q->where('skill', 'reading'))
            ->with('question')
            ->get();

        $correct = 0;
        $total = $answers->count();

        foreach ($answers as $answer) {
            if ($answer->question && $answer->question->auto_gradable) {
                $isCorrect = $this->checkAnswer($answer);
                $answer->update([
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? ($answer->question->points ?? 1) : 0,
                ]);
                if ($isCorrect) $correct++;
            }
        }

        $band = $this->rawToBand($correct);
        
        $attempt->update([
            'reading_score' => $correct,
            'reading_completed' => true,
        ]);

        return [
            'raw_score' => $correct,
            'total' => $total,
            'band' => $band,
            'status' => 'graded',
        ];
    }

    // check correct answer
    protected function checkAnswer(IeltsTestAnswer $answer): bool
    {
        $question = $answer->question;
        $submittedAnswer = $answer->answer_text ?? $answer->answer_options;

        if ($submittedAnswer === null || $submittedAnswer === '') {
            return false;
        }

        return (bool) $question->checkAnswer($submittedAnswer);
    }

    /**
     * Convert raw score to band score
     */
    public function rawToBand(int $score): float
    {
        return $this->bandConversion[$score] ?? 0.0;
    }

    /**
     * Check writing grading status
     */
    protected function checkWritingStatus(IeltsTestAttempt $attempt): array
    {
        $answers = $attempt->answers()
            ->whereHas('question.section', fn($q) => $q->where('skill', 'writing'))
            ->get();

        $graded = $answers->whereNotNull('graded_at')->count();
        $total = $answers->count();

        if ($graded === $total && $total > 0) {
            // Calculate average band from criteria
            $bands = [];
            foreach ($answers as $answer) {
                if ($answer->writing_bands) {
                    $bands[] = array_sum($answer->writing_bands) / count($answer->writing_bands);
                }
            }
            $avgBand = count($bands) > 0 ? array_sum($bands) / count($bands) : null;

            $attempt->update([
                'writing_score' => $avgBand,
                'writing_completed' => true,
            ]);

            return [
                'band' => $avgBand,
                'status' => 'graded',
                'graded_count' => $graded,
                'total' => $total,
            ];
        }

        return [
            'band' => null,
            'status' => 'pending',
            'graded_count' => $graded,
            'total' => $total,
        ];
    }

    /**
     * Check speaking grading status
     */
    protected function checkSpeakingStatus(IeltsTestAttempt $attempt): array
    {
        $answers = $attempt->answers()
            ->whereHas('question.section', fn($q) => $q->where('skill', 'speaking'))
            ->get();

        $graded = $answers->whereNotNull('graded_at')->count();
        $total = $answers->count();

        if ($graded === $total && $total > 0) {
            $bands = [];
            foreach ($answers as $answer) {
                if ($answer->speaking_bands) {
                    $bands[] = array_sum($answer->speaking_bands) / count($answer->speaking_bands);
                }
            }
            $avgBand = count($bands) > 0 ? array_sum($bands) / count($bands) : null;

            $attempt->update([
                'speaking_score' => $avgBand,
                'speaking_completed' => true,
            ]);

            return [
                'band' => $avgBand,
                'status' => 'graded',
                'graded_count' => $graded,
                'total' => $total,
            ];
        }

        return [
            'band' => null,
            'status' => 'pending',
            'graded_count' => $graded,
            'total' => $total,
        ];
    }

    /**
     * Calculate overall band score
     */
    protected function calculateOverall(array $results): ?float
    {
        $bands = [];

        if (isset($results['listening']['band'])) {
            $bands[] = $results['listening']['band'];
        }
        if (isset($results['reading']['band'])) {
            $bands[] = $results['reading']['band'];
        }
        if (isset($results['writing']['band'])) {
            $bands[] = $results['writing']['band'];
        }
        if (isset($results['speaking']['band'])) {
            $bands[] = $results['speaking']['band'];
        }

        if (empty($bands)) {
            return null;
        }

        $average = array_sum($bands) / count($bands);
        
        // Round to nearest 0.5
        return round($average * 2) / 2;
    }

    /**
     * =========================================
     * AI GRADING METHODS
     * =========================================
     */

    /**
     * Grade writing with AI (OpenAI/Gemini)
     */
    public function gradeWritingWithAI(IeltsTestAnswer $answer, string $provider = 'openai'): array
    {
        $essay = $answer->answer_text;
        $question = $answer->question;
        $taskType = $question->question_type; // essay_task1 or essay_task2

        $prompt = $this->buildWritingPrompt($essay, $taskType, $question->question_text);

        try {
            $response = match($provider) {
                'openai' => $this->callOpenAI($prompt),
                'gemini' => $this->callGemini($prompt),
                'ollama' => $this->callOllama($prompt),
                default => throw new \Exception("Unknown AI provider: $provider")
            };

            $parsed = $this->parseAIResponse($response, 'writing');

            // Save grades
            $answer->update([
                'writing_bands' => $parsed['bands'],
                'grader_feedback' => $parsed['feedback'],
                'is_correct' => true, // Writing is always "correct" if answered
                'points_earned' => array_sum($parsed['bands']) / 4, // Average band
                'graded_at' => time(),
                'graded_by' => null, // AI graded
            ]);

            return $parsed;

        } catch (\Exception $e) {
            Log::error('AI Grading Error: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build writing prompt for AI
     */
    protected function buildWritingPrompt(string $essay, string $taskType, string $taskDescription): string
    {
        $taskInfo = $taskType === 'essay_task1' 
            ? 'IELTS Writing Task 1 (minimum 150 words) - describing a graph/chart/diagram'
            : 'IELTS Writing Task 2 (minimum 250 words) - essay in response to an argument';

        return <<<PROMPT
You are an experienced IELTS examiner. Grade the following essay according to official IELTS criteria.

**Task Type:** {$taskInfo}

**Task Description:**
{$taskDescription}

**Student's Essay:**
{$essay}

**Please evaluate using these 4 criteria (each scored 0-9):**

1. **Task Achievement** (TA): How well does the response address all parts of the task?
2. **Coherence and Cohesion** (CC): How well organized and connected is the writing?
3. **Lexical Resource** (LR): Range and accuracy of vocabulary?
4. **Grammatical Range and Accuracy** (GRA): Range and accuracy of grammar?

**Respond in this exact JSON format:**
{
    "bands": {
        "task_achievement": <0-9>,
        "coherence_cohesion": <0-9>,
        "lexical_resource": <0-9>,
        "grammatical_range": <0-9>
    },
    "overall_band": <calculated average to nearest 0.5>,
    "word_count": <actual word count>,
    "feedback": {
        "strengths": ["strength 1", "strength 2"],
        "improvements": ["improvement 1", "improvement 2"],
        "detailed_feedback": "Paragraph of detailed feedback for the student"
    }
}
PROMPT;
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $prompt): string
    {
        $apiKey = config('openai.api_key');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured. Add OPENAI_API_KEY to .env');
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert IELTS examiner.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
            'max_tokens' => 1000,
        ]);

        if ($response->failed()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        return $response->json('choices.0.message.content');
    }

    /**
     * Call Google Gemini API
     */
    protected function callGemini(string $prompt): string
    {
        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            throw new \Exception('Gemini API key not configured. Add GEMINI_API_KEY to .env');
        }

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$apiKey", [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 1000,
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API error: ' . $response->body());
        }

        return $response->json('candidates.0.content.parts.0.text');
    }

    /**
     * Call local Ollama (free, local LLM)
     */
    protected function callOllama(string $prompt): string
    {
        $host = config('services.ollama.host', 'http://localhost:11434');
        $model = config('services.ollama.model', 'llama2');

        $response = Http::timeout(120)->post("$host/api/generate", [
            'model' => $model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            throw new \Exception('Ollama API error: ' . $response->body());
        }

        return $response->json('response');
    }

    /**
     * Parse AI response to structured data
     */
    protected function parseAIResponse(string $response, string $type): array
    {
        // Try to extract JSON from response
        preg_match('/\{[\s\S]*\}/', $response, $matches);
        
        if (empty($matches)) {
            throw new \Exception('Could not parse AI response');
        }

        $data = json_decode($matches[0], true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in AI response');
        }

        if ($type === 'writing') {
            return [
                'bands' => [
                    $data['bands']['task_achievement'] ?? 5,
                    $data['bands']['coherence_cohesion'] ?? 5,
                    $data['bands']['lexical_resource'] ?? 5,
                    $data['bands']['grammatical_range'] ?? 5,
                ],
                'overall_band' => $data['overall_band'] ?? 5,
                'word_count' => $data['word_count'] ?? 0,
                'feedback' => $data['feedback']['detailed_feedback'] ?? '',
                'strengths' => $data['feedback']['strengths'] ?? [],
                'improvements' => $data['feedback']['improvements'] ?? [],
            ];
        }

        return $data;
    }
}
