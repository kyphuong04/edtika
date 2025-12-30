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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 100;
    }
    .timer-display {
        font-size: 36px;
        font-weight: bold;
        text-align: center;
        font-family: 'Courier New', monospace;
    }
    .timer-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
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
        background: linear-gradient(90deg, #10b981, #3b82f6);
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
        background: #f3f4f6;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
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
                    <span x-text="answeredCount"></span> / <span x-text="totalQuestions"></span> answered
                    (<span x-text="Math.round(progress)"></span>%)
                </p>
            </div>

            {{-- Audio Player (for Listening) --}}
            @if($currentSection->skill === 'listening' && $currentSection->audio_file)
            <div class="audio-player">
                <h4 class="mb-3">
                    <i class="fas fa-headphones mr-2"></i>
                    Audio Section
                </h4>
                <audio id="listeningAudio" controls class="w-100" 
                       @if($test->isMockTest()) 
                       controlsList="nodownload noplaybackrate"
                       @endif>
                    <source src="{{ $currentSection->audio_file }}" type="audio/mpeg">
                    Your browser does not support audio.
                </audio>
                @if($test->isMockTest())
                <p class="text-warning font-12 mt-2 mb-0">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Mock test: Audio will play only once
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

            {{-- Questions --}}
            <template x-for="(question, index) in questions" :key="question.id">
                <div class="question-card" 
                     x-show="currentQuestionIndex === index"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-10"
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="mb-0">
                            Question <span x-text="index + 1"></span>
                        </h4>
                        <span class="badge badge-primary">
                            <span x-text="question.points"></span> point(s)
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
                                       placeholder="Type your answer here...">
                                <small class="text-gray mt-2" x-show="question.max_words">
                                    Maximum words: <span x-text="question.max_words"></span>
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
                                    <span>True</span>
                                </label>
                                <label class="d-block mb-3 p-3 border rounded cursor-pointer hover-bg-light">
                                    <input type="radio" 
                                           :name="'q_' + question.id" 
                                           value="False"
                                           x-model="answers[question.id]"
                                           @change="saveAnswer(question.id)"
                                           class="mr-2">
                                    <span>False</span>
                                </label>
                            </div>
                        </template>

                        {{-- Essay (Writing) --}}
                        <template x-if="question.question_type === 'essay'">
                            <div class="form-group">
                                <textarea 
                                    class="form-control" 
                                    rows="12"
                                    x-model="answers[question.id]"
                                    @input.debounce.1000ms="saveAnswer(question.id)"
                                    placeholder="Write your essay here..."></textarea>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-gray">
                                        Words: <span x-text="countWords(answers[question.id] || '')"></span>
                                        <template x-if="question.max_words">
                                            / <span x-text="question.max_words"></span>
                                        </template>
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
                            Previous
                        </button>
                        
                        <button class="btn btn-primary" 
                                @click="nextQuestion()"
                                x-show="currentQuestionIndex < questions.length - 1">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <button class="btn btn-success" 
                                @click="finishSection()"
                                x-show="currentQuestionIndex === questions.length - 1">
                            Finish Section
                            <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-3">
            {{-- Timer --}}
            <div class="timer-box" :class="timeWarning ? 'timer-warning' : ''">
                <p class="mb-2 text-center opacity-90">Time Remaining</p>
                <div class="timer-display" x-text="formatTime(timeRemaining)"></div>
                <template x-if="timeWarning">
                    <p class="mb-0 mt-2 text-center font-12 animate-pulse">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Hurry up!
                    </p>
                </template>
            </div>

            {{-- Question Navigation --}}
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Questions</h5>
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
                            Answered
                        </small>
                        <small class="d-block text-gray mt-1">
                            <span class="bg-primary" style="display:inline-block; width:12px; height:12px; border-radius:2px;"></span>
                            Current
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Indicator --}}
    <div class="save-indicator" :class="{'show': showSaveIndicator}">
        <i class="fas fa-check-circle mr-2"></i>
        Answer saved
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
            if (confirm('Are you sure you want to finish this section? You cannot return to it.')) {
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
            alert('Time is up! Your test will be submitted automatically.');
            window.location.href = '{{ route("panel.ielts_tests.submit", $attempt->id) }}';
        }
    }
}
</script>
@endpush
