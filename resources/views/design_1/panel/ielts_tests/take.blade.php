@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .test-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    .timer-box {
        position: sticky;
        top: 80px;
        background: #ffffff;
        color: #511D99;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
        z-index: 100;
    }
    .timer-display {
        font-size: 36px;
        font-weight: bold;
        text-align: center;
        font-family: 'Courier New', monospace;
    }
    .timer-warning {
        background: #fff5f8;
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    .question-nav {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        gap: 8px;
        margin-top: 15px;
    }
    .question-nav-btn {
        aspect-ratio: 1;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    .question-nav-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .question-nav-btn.current {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .question-nav-btn.answered {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }
    .question-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
        margin-bottom: 20px;
    }
    .section-header {
        background: #ffffff;
        color: #1f2937;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid rgba(81, 29, 153, 0.10);
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .progress-bar-custom {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 10px;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #511D99, #7c4dff);
        transition: width 0.3s ease;
    }
    .save-indicator {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: #10b981;
        color: white;
        border-radius: 8px;
        font-size: 14px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s;
        z-index: 1000;
    }
    .save-indicator.show {
        opacity: 1;
        transform: translateY(0);
    }
    .audio-player {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(81, 29, 153, 0.10);
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    
    /* Writing Section Styles */
    .writing-section-header {
        background: #ffffff;
        padding: 16px 24px;
        border-radius: 8px 8px 0 0;
        margin: -30px -30px 0 -30px;
        border-bottom: 1px solid rgba(81, 29, 153, 0.10);
    }
    .writing-section-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px 0;
    }
    .writing-section-header p {
        font-size: 14px;
        color: #4b5563;
        margin: 0;
    }
    .writing-layout {
        display: grid;
        grid-template-columns: 55% 45%;
        gap: 24px;
        margin-top: 24px;
    }
    .writing-question-box {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 8px;
        padding: 24px;
        font-size: 15px;
        line-height: 1.7;
        color: #1f2937;
    }
    .writing-question-box h4 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 16px 0;
    }
    .writing-question-box ul {
        margin: 12px 0;
        padding-left: 24px;
    }
    .writing-question-box li {
        margin-bottom: 8px;
    }
    .writing-question-box strong {
        font-weight: 600;
        color: #111827;
    }
    .writing-answer-box {
        background: white;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 8px;
        padding: 24px;
        position: sticky;
        top: 100px;
        height: fit-content;
    }
    .writing-answer-box textarea {
        width: 100%;
        min-height: 400px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 16px;
        font-size: 15px;
        line-height: 1.6;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        resize: vertical;
    }
    .writing-answer-box textarea:focus {
        outline: none;
        border-color: #511D99;
        box-shadow: 0 0 0 3px rgba(81, 29, 153, 0.10);
    }
    .word-counter {
        text-align: right;
        margin-top: 12px;
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }
    .word-counter.warning {
        color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="test-container mt-30" x-data="testTakingApp()">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-9">
            {{-- Section Header --}}
            <div class="section-header">
                <h2 class="mb-0">{{ $test->title }}</h2>
                <p class="mb-0 mt-2 opacity-90">{{ $currentSection->title }}</p>
                <div class="progress-bar-custom">
                    <div class="progress-fill" :style="`width: ${progress}%`"></div>
                </div>
                <p class="mb-0 mt-2 font-12">
                    {!! trans('update.ielts_answered_count', ['answered' => '<span x-text="answeredCount"></span>', 'total' => '<span x-text="totalQuestions"></span>']) !!}
                    (<span x-text="Math.round(progress)"></span>%)
                </p>
            </div>

            {{-- Audio Player (for Listening) --}}
            @php
                $firstQ = $questions->first() ?? null;
                $currentPartId = $firstQ->part_id ?? null;
                $currentPart = $currentPartId ? \App\Models\IeltsTestPart::find($currentPartId) : null;
                $currentPartAudioUrl = null;
                if (!empty($currentPart->audio_file)) {
                    $audioFile = $currentPart->audio_file;
                    if (str_starts_with($audioFile, '/') || str_starts_with($audioFile, 'http')) {
                        $currentPartAudioUrl = $audioFile;
                    } else {
                        $currentPartAudioUrl = \Storage::disk('public')->url($audioFile);
                    }
                }
                $sectionAudioUrl = $currentSection->audio_url ?? null;
            @endphp
            @if($currentSection->skill === 'listening' && ($currentPartAudioUrl || $sectionAudioUrl))
            <div class="audio-player">
                <h4 class="mb-3">
                    <i class="fas fa-headphones mr-2"></i>
                    {{ trans('update.ielts_audio_section') }}
                </h4>
                @if($test->isPracticeTest())
                    <audio id="listeningAudio" controls class="w-100">
                        <source src="{{ $currentPartAudioUrl ?? $sectionAudioUrl ?? '' }}" type="audio/mpeg">
                        {{ trans('update.ielts_browser_no_audio_support') }}
                    </audio>
                    <p class="text-muted font-12 mt-2 mb-0">Practice mode: you can seek and replay the audio.</p>
                @else
                    {{-- Mock tests: do not display a seekable audio bar; audio will be controlled by exam flow/overlay --}}
                    <audio id="listeningAudio" preload="none" style="display:none;">
                        <source src="{{ $currentPartAudioUrl ?? $sectionAudioUrl ?? '' }}" type="audio/mpeg">
                    </audio>
                @endif
                </audio>
                @if($test->isMockTest())
                    <p class="text-warning font-12 mt-2 mb-0">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ trans('update.ielts_mock_test_audio_warning') }}
                    </p>
                @endif
            </div>
            @endif

            {{-- Reading Passage --}}
            @if($currentSection->skill === 'reading' && $currentSection->passage_text)
            <div class="question-card">
                @if($currentSection->passage_title)
                <h3 class="mb-3">{{ $currentSection->passage_title }}</h3>
                @endif
                <div class="passage-text" style="line-height: 1.8;">
                    {!! nl2br(e($currentSection->passage_text)) !!}
                </div>
            </div>
            @endif

            {{-- Writing Section Special Layout --}}
            @if($currentSection->skill === 'writing' && !$questions->isEmpty())
            <div class="question-card">
                {{-- Writing Header --}}
                <div class="writing-section-header">
                    <h3>{{ $currentSection->title }}</h3>
                    <p>{!! trans('update.ielts_writing_task_instruction', ['duration' => '<strong>' . ($currentSection->duration ?? 20) . '</strong>', 'min_words' => '<strong>' . ($questions->first()->min_words ?? 150) . '</strong>']) !!}</p>
                </div>

                {{-- Two Column Layout --}}
                <div class="writing-layout">
                    {{-- Left: Question Content --}}
                    <div class="writing-question-box">
                        <template x-for="(question, index) in questions" :key="question.id">
                            <div x-show="currentQuestionIndex === index">
                                <div x-html="question.question_text"></div>
                                <div x-show="question.instruction" class="mt-3">
                                    <div x-html="question.instruction"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Right: Answer Area --}}
                    <div class="writing-answer-box">
                        <template x-for="(question, index) in questions" :key="question.id">
                            <div x-show="currentQuestionIndex === index">
                                <textarea 
                                    x-model="answers[question.id]"
                                    @input.debounce.1000ms="saveAnswer(question.id)"
                                    x-model="answers[question.id]"
                                    @input.debounce.1000ms="saveAnswer(question.id)"
                                    placeholder="{{ trans('update.ielts_write_essay') }}"></textarea>
                                <div class="word-counter" 
                                     :class="countWords(answers[question.id] || '') < (question.min_words || 0) ? 'warning' : ''">
                                    {!! trans('update.ielts_word_count_min', ['count' => '<strong x-text="countWords(answers[question.id] || \'\')"></strong>', 'min' => '<span x-text="question.min_words"></span>']) !!}
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Navigation for Writing --}}
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" 
                            @click="previousQuestion()"
                            x-show="questions.length > 1">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ trans('public.previous') }}
                    </button>
                    
                    <button class="btn btn-primary" 
                            @click="nextQuestion()"
                            x-show="currentQuestionIndex < questions.length - 1">
                        {{ trans('public.next') }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <button class="btn btn-success" 
                            @click="finishSection()"
                            x-show="currentQuestionIndex === questions.length - 1">
                        {{ trans('update.ielts_finish_section') }}
                        <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </div>
            @endif

            {{-- No Questions Warning --}}
            @if($questions->isEmpty() && $currentSection->skill !== 'writing')
            <div class="question-card text-center">
                <div class="py-5">  
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
                    <h3 class="text-gray">{{ trans('update.ielts_no_questions_available') }}</h3>
                    <p class="text-gray">{{ trans('update.ielts_section_no_questions') }}</p>
                    <hr class="my-4">
                    <p class="text-muted font-12">
                        <strong>Debug Info:</strong><br>
                        Section ID: {{ $currentSection->id ?? 'N/A' }}<br>
                        Section Title: {{ $currentSection->title ?? 'N/A' }}<br>
                        Skill: {{ $currentSection->skill ?? 'N/A' }}<br>
                        Questions Count: {{ $questions->count() }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> {{ trans('update.ielts_back_to_test') }}
                        </a>
                        <form action="{{ route('panel.ielts_tests.finish_section', $attempt->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ trans('update.ielts_skip_to_next_section') }} <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif($currentSection->skill !== 'writing')

            {{-- Questions (Non-Writing) --}}
            <template x-for="(question, index) in questions" :key="question.id">
                <div class="question-card" 
                     x-show="currentQuestionIndex === index"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-10"
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="mb-0">
                            {!! trans('update.ielts_question_number', ['number' => '<span x-text="index + 1"></span>']) !!}
                        </h4>
                        <span class="badge badge-primary">
                            {!! trans('update.ielts_points', ['points' => '<span x-text="question.points"></span>']) !!}
                        </span>
                    </div>

                    {{-- Question Text --}}
                    <p class="font-16 mb-4" x-html="question.question_text"></p>

                    {{-- Instructions --}}
                    <p class="text-gray font-14 mb-3" x-show="question.instruction" x-html="question.instruction"></p>

                    {{-- Answer Input Based on Type --}}
                    <div class="answer-section">
                        {{-- Multiple Choice --}}
                        <template x-if="question.question_type === 'multiple_choice'">
                            <div class="form-group">
                                <template x-for="(option, optIndex) in question.answer_options" :key="optIndex">
                                    <label class="d-block mb-3 p-3 border rounded cursor-pointer hover-bg-light">
                                        <input type="radio" 
                                               :name="'q_' + question.id" 
                                               :value="option"
                                               x-model="answers[question.id]"
                                               @change="saveAnswer(question.id)"
                                               class="mr-2">
                                        <span x-text="option"></span>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- Fill in the Blank --}}
                        <template x-if="question.question_type === 'fill_blank'">
                            <div class="form-group">
                                <input type="text" 
                                       class="form-control form-control-lg"
                                       x-model="answers[question.id]"
                                       @input.debounce.500ms="saveAnswer(question.id)"
                                       placeholder="{{ trans('update.ielts_type_answer') }}">
                                <small class="text-gray mt-2" x-show="question.max_words">
                                    {!! trans('update.ielts_max_words', ['max' => '<span x-text="question.max_words"></span>']) !!}
                                </small>
                            </div>
                        </template>

                        {{-- True/False --}}
                        <template x-if="question.question_type === 'true_false'">
                            <div class="form-group">
                                <label class="d-block mb-3 p-3 border rounded cursor-pointer hover-bg-light">
                                    <input type="radio" 
                                           :name="'q_' + question.id" 
                                           value="True"
                                           x-model="answers[question.id]"
                                           @change="saveAnswer(question.id)"
                                           class="mr-2">
                                    <span>{{ trans('update.ielts_true') }}</span>
                                </label>
                                <label class="d-block mb-3 p-3 border rounded cursor-pointer hover-bg-light">
                                    <input type="radio" 
                                           :name="'q_' + question.id" 
                                           value="False"
                                           x-model="answers[question.id]"
                                           @change="saveAnswer(question.id)"
                                           class="mr-2">
                                    <span>{{ trans('update.ielts_false') }}</span>
                                </label>
                            </div>
                        </template>

                        {{-- Essay (Writing) --}}
                        <template x-if="question.question_type === 'essay'">
                            <div class="form-group">
                                    class="form-control" 
                                    rows="12"
                                    x-model="answers[question.id]"
                                    @input.debounce.1000ms="saveAnswer(question.id)"
                                    placeholder="{{ trans('update.ielts_write_essay') }}"></textarea>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-gray">
                                        {!! trans('update.ielts_word_count_max', ['count' => '<span x-text="countWords(answers[question.id] || \'\')"></span>', 'max' => '<span x-text="question.max_words"></span>']) !!}
                                    </small>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-secondary" 
                                @click="previousQuestion()"
                                :disabled="currentQuestionIndex === 0">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ trans('public.previous') }}
                        </button>
                        
                        <button class="btn btn-primary" 
                                @click="nextQuestion()"
                                x-show="currentQuestionIndex < questions.length - 1">
                            {{ trans('public.next') }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <button class="btn btn-success" 
                                @click="finishSection()"
                                x-show="currentQuestionIndex === questions.length - 1">
                            {{ trans('update.ielts_finish_section') }}
                            <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </div>
            </template>
            @endif {{-- End if questions is empty --}}
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-3">
            {{-- Timer --}}
            <div class="timer-box" :class="timeWarning ? 'timer-warning' : ''">
                <p class="mb-2 text-center opacity-90">{{ trans('update.ielts_time_remaining') }}</p>
                <div class="timer-display" x-text="formatTime(timeRemaining)"></div>
                <template x-if="timeWarning">
                    <p class="mb-0 mt-2 text-center font-12 animate-pulse">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ trans('update.ielts_hurry_up') }}
                    </p>
                </template>
            </div>

            {{-- Question Navigation --}}
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">{{ trans('update.ielts_questions_list') }}</h5>
                    <div class="question-nav">
                        <template x-for="(question, index) in questions" :key="index">
                            <button 
                                class="question-nav-btn"
                                :class="{
                                    'current': currentQuestionIndex === index,
                                    'answered': answers[question.id]
                                }"
                                @click="goToQuestion(index)"
                                x-text="index + 1">
                            </button>
                        </template>
                    </div>
                    <div class="mt-3">
                        <small class="d-block text-gray">
                            <span class="bg-success" style="display:inline-block; width:12px; height:12px; border-radius:2px;"></span>
                            {{ trans('update.ielts_answered') }}
                        </small>
                        <small class="d-block text-gray mt-1">
                            <span class="bg-primary" style="display:inline-block; width:12px; height:12px; border-radius:2px;"></span>
                            {{ trans('update.ielts_current') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Indicator --}}
    <div class="save-indicator" :class="{'show': showSaveIndicator}">
        <i class="fas fa-check-circle mr-2"></i>
        {{ trans('update.ielts_answer_saved') }}
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
function testTakingApp() {
    return {
        attemptId: {{ $attempt->id }},
        questions: @json($questions),
        answers: @json($userAnswers),
        currentQuestionIndex: 0,
        timeRemaining: {{ $attempt->remaining_time_seconds }},
        showSaveIndicator: false,
        timer: null,

        init() {
            this.startTimer();
            
            // Prevent accidental navigation
            window.addEventListener('beforeunload', (e) => {
                e.preventDefault();
                e.returnValue = '';
            });

            // Disable audio replay for mock tests
            @if($test->isMockTest() && $currentSection->skill === 'listening')
            const audio = document.getElementById('listeningAudio');
            if (audio) {
                audio.addEventListener('ended', function() {
                    this.currentTime = 0;
                    this.removeAttribute('controls');
                });
            }
            @endif
        },

        startTimer() {
            this.timer = setInterval(() => {
                this.timeRemaining--;
                
                if (this.timeRemaining <= 0) {
                    this.autoSubmit();
                }
            }, 1000);
        },

        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) {
                return `${hours}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${minutes}:${String(secs).padStart(2, '0')}`;
        },

        get timeWarning() {
            return this.timeRemaining <= 300; // 5 minutes
        },

        get progress() {
            return (this.answeredCount / this.totalQuestions) * 100;
        },

        get answeredCount() {
            return Object.keys(this.answers).length;
        },

        get totalQuestions() {
            return this.questions.length;
        },

        saveAnswer(questionId) {
            fetch('{{ route("panel.ielts_tests.save_answer", $attempt->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer_text: this.answers[questionId]
                })
            })
            .then(response => response.json())
            .then(data => {
                this.showSaveIndicator = true;
                setTimeout(() => {
                    this.showSaveIndicator = false;
                }, 2000);
            })
            .catch(error => console.error('Error:', error));
        },

        nextQuestion() {
            if (this.currentQuestionIndex < this.questions.length - 1) {
                this.currentQuestionIndex++;
            }
        },

        previousQuestion() {
            if (this.currentQuestionIndex > 0) {
                this.currentQuestionIndex--;
            }
        },

        goToQuestion(index) {
            this.currentQuestionIndex = index;
        },

        countWords(text) {
            return text.trim().split(/\s+/).filter(w => w.length > 0).length;
        },

        finishSection() {
            if (confirm('{{ trans('update.ielts_confirm_finish_section') }}')) {
                fetch('{{ route("panel.ielts_tests.finish_section", $attempt->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'completed') {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            }
        },

        autoSubmit() {
            clearInterval(this.timer);
            Swal.fire({
                title: '{{ trans('update.ielts_times_up') }}',
                text: '{{ trans('update.ielts_auto_submit_message') }}',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a3a5c',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '{{ route("panel.ielts_tests.submit", $attempt->id) }}';
            });
        }
    }
}
</script>
@endpush
