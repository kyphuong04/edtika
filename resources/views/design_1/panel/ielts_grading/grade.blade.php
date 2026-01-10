@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .grading-page {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 24px;
        max-width: 1600px;
        margin: 0 auto;
    }
    
    @media (max-width: 1200px) {
        .grading-page {
            grid-template-columns: 1fr;
        }
    }
    
    .grading-content {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .content-header {
        background: linear-gradient(135deg, {{ $skill === 'writing' ? '#f59e0b' : '#ef4444' }} 0%, {{ $skill === 'writing' ? '#d97706' : '#dc2626' }} 100%);
        color: #fff;
        padding: 24px;
    }
    
    .content-header h2 {
        margin: 0 0 8px 0;
        font-size: 20px;
    }
    
    .content-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }
    
    .student-banner {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .student-avatar-lg {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 20px;
    }
    
    .student-details h3 {
        margin: 0 0 4px 0;
        font-size: 18px;
        color: #1f2937;
    }
    
    .student-details p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }
    
    .answer-section {
        padding: 24px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .task-prompt {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.7;
        color: #78350f;
    }
    
    .answer-content {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        min-height: 300px;
        font-size: 15px;
        line-height: 1.9;
        color: #1f2937;
        white-space: pre-wrap;
        font-family: Georgia, serif;
    }
    
    .audio-player-container {
        background: #f3f4f6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    
    .audio-player-container audio {
        width: 100%;
        max-width: 500px;
    }
    
    .no-answer {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
        font-style: italic;
    }
    
    .word-stats {
        display: flex;
        gap: 20px;
        padding: 12px 20px;
        background: #f0f9ff;
        border-radius: 8px;
        margin-top: 16px;
        font-size: 13px;
    }
    
    .word-stats span {
        color: #0369a1;
    }
    
    /* Grading Panel */
    .grading-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: sticky;
        top: 100px;
    }
    
    .panel-header {
        background: #1f2937;
        color: #fff;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }
    
    .panel-header h3 {
        margin: 0;
        font-size: 16px;
    }
    
    .panel-body {
        padding: 24px;
    }
    
    .band-selector {
        margin-bottom: 24px;
    }
    
    .band-selector label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
    }
    
    .band-input {
        width: 100%;
        padding: 14px;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        border: 2px solid #d1d5db;
        border-radius: 10px;
        color: #1f2937;
    }
    
    .band-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }
    
    .band-quick-select {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-top: 12px;
    }
    
    .band-btn {
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .band-btn:hover {
        background: #f3f4f6;
    }
    
    .band-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-color: #667eea;
    }
    
    .criteria-section {
        margin-bottom: 24px;
    }
    
    .criteria-section h4 {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }
    
    .criteria-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .criteria-label {
        flex: 1;
        font-size: 13px;
        color: #4b5563;
    }
    
    .criteria-input {
        width: 60px;
        padding: 8px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .feedback-section {
        margin-bottom: 24px;
    }
    
    .feedback-section label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
    }
    
    .feedback-textarea {
        width: 100%;
        height: 150px;
        padding: 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.6;
        resize: vertical;
    }
    
    .feedback-textarea:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .submit-section {
        display: flex;
        gap: 12px;
    }
    
    .btn-submit {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-submit-grade {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }
    
    .btn-submit-grade:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-cancel:hover {
        background: #e5e7eb;
    }
    
    .band-descriptor-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 14px;
        margin-top: 12px;
        font-size: 12px;
        line-height: 1.6;
        color: #6b7280;
    }
    
    .band-descriptor-card strong {
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="grading-page mt-20">
    {{-- Main Content - Student Answer --}}
    <div class="grading-content">
        <div class="content-header">
            <h2>{{ $skill === 'writing' ? '✍️ Writing Assessment' : '🎤 Speaking Assessment' }}</h2>
            <p>{{ $test->title }} - Attempt #{{ $attempt->attempt_number }}</p>
        </div>
        
        <div class="student-banner">
            <div class="student-avatar-lg">
                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
            </div>
            <div class="student-details">
                <h3>{{ $user->full_name ?? 'Unknown User' }}</h3>
                <p>{{ $user->email ?? '' }} • Completed {{ $attempt->completed_at ? date('M j, Y \a\t g:i A', $attempt->completed_at) : 'N/A' }}</p>
            </div>
        </div>
        
        <div class="answer-section">
            @foreach($sections as $section)
                <div class="section-title">
                    {{ $skill === 'writing' ? 'Task' : 'Part' }} {{ $section->part_number ?? $loop->iteration }}
                    @if($section->title)
                        - {{ $section->title }}
                    @endif
                </div>
                
                {{-- Task Prompt --}}
                @if($section->content || $section->passage_text)
                    <div class="task-prompt">
                        <strong>Task:</strong><br>
                        {!! nl2br(e($section->content ?? $section->passage_text)) !!}
                    </div>
                @endif
                
                {{-- Student Answer --}}
                @php
                    $sectionAnswers = $answers->filter(function($a) use ($section) {
                        return $a->question && $a->question->section_id == $section->id;
                    });
                @endphp
                
                @if($sectionAnswers->count() > 0)
                    @foreach($sectionAnswers as $answer)
                        @if($skill === 'writing')
                            <div class="answer-content">{{ $answer->answer_text ?? 'No answer provided' }}</div>
                            
                            @php
                                $text = $answer->answer_text ?? '';
                                $words = $text ? count(preg_split('/\s+/', trim($text))) : 0;
                                $chars = strlen($text);
                            @endphp
                            
                            <div class="word-stats">
                                <span>📝 Words: <strong>{{ $words }}</strong></span>
                                <span>📊 Characters: <strong>{{ $chars }}</strong></span>
                                <span>📋 Min Required: <strong>{{ $section->part_number == 1 ? 150 : 250 }}</strong></span>
                            </div>
                        @else
                            {{-- Speaking - Audio Player --}}
                            @if($answer->answer_text && strpos($answer->answer_text, '/storage/') !== false)
                                <div class="audio-player-container">
                                    <p style="margin-bottom: 12px; color: #6b7280;">🎧 Student's Recording:</p>
                                    <audio controls>
                                        <source src="{{ $answer->answer_text }}" type="audio/webm">
                                        Your browser does not support audio playback.
                                    </audio>
                                </div>
                            @else
                                <div class="no-answer">
                                    No audio recording available for this task.
                                </div>
                            @endif
                        @endif
                    @endforeach
                @else
                    <div class="no-answer">
                        No answer submitted for this section.
                    </div>
                @endif
                
                @if(!$loop->last)
                    <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
                @endif
            @endforeach
        </div>
    </div>
    
    {{-- Grading Panel --}}
    <div class="grading-panel">
        <div class="panel-header">
            <h3>📝 Grade {{ ucfirst($skill) }}</h3>
        </div>
        
        <form action="{{ route('panel.ielts_grading.submit', $attempt->id) }}" method="POST">
            @csrf
            <input type="hidden" name="skill" value="{{ $skill }}">
            
            <div class="panel-body">
                {{-- Band Score --}}
                <div class="band-selector">
                    <label>Overall Band Score</label>
                    <input type="number" name="band_score" id="bandScore" class="band-input" 
                           min="0" max="9" step="0.5" value="{{ $skill === 'writing' ? $attempt->writing_band : $attempt->speaking_band }}"
                           required placeholder="0.0">
                    
                    <div class="band-quick-select">
                        @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                            <button type="button" class="band-btn" onclick="selectBand({{ $band }})">{{ $band }}</button>
                        @endforeach
                    </div>
                    
                    <div class="band-descriptor-card" id="bandDescriptor">
                        Select a band score to see the descriptor.
                    </div>
                </div>
                
                {{-- Criteria Scores --}}
                <div class="criteria-section">
                    <h4>Assessment Criteria</h4>
                    
                    @if($skill === 'writing')
                        <div class="criteria-item">
                            <span class="criteria-label">Task Achievement/Response</span>
                            <input type="number" name="criteria_scores[task_achievement]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Coherence & Cohesion</span>
                            <input type="number" name="criteria_scores[coherence]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Lexical Resource</span>
                            <input type="number" name="criteria_scores[lexical]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Grammatical Range & Accuracy</span>
                            <input type="number" name="criteria_scores[grammar]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                    @else
                        <div class="criteria-item">
                            <span class="criteria-label">Fluency & Coherence</span>
                            <input type="number" name="criteria_scores[fluency]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Lexical Resource</span>
                            <input type="number" name="criteria_scores[lexical]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Grammatical Range & Accuracy</span>
                            <input type="number" name="criteria_scores[grammar]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                        <div class="criteria-item">
                            <span class="criteria-label">Pronunciation</span>
                            <input type="number" name="criteria_scores[pronunciation]" class="criteria-input" min="0" max="9" step="0.5" placeholder="0">
                        </div>
                    @endif
                </div>
                
                {{-- Feedback --}}
                <div class="feedback-section">
                    <label>Feedback for Student (Optional)</label>
                    <textarea name="feedback" class="feedback-textarea" placeholder="Provide constructive feedback to help the student improve...">{{ $skill === 'writing' ? $attempt->writing_feedback : $attempt->speaking_feedback }}</textarea>
                </div>
                
                {{-- Submit Buttons --}}
                <div class="submit-section">
                    <a href="{{ route('panel.ielts_grading.index') }}" class="btn-submit btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit btn-submit-grade">
                        ✓ Submit Grade
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const bandDescriptors = {
    9: '<strong>Band 9 - Expert:</strong> Full operational command of the language. Appropriate, accurate, and fluent with complete understanding.',
    8.5: '<strong>Band 8.5:</strong> Very good command approaching expert level.',
    8: '<strong>Band 8 - Very Good:</strong> Fully operational command with occasional unsystematic inaccuracies.',
    7.5: '<strong>Band 7.5:</strong> Good command with occasional inaccuracies in unfamiliar situations.',
    7: '<strong>Band 7 - Good:</strong> Operational command with occasional inaccuracies and misunderstandings.',
    6.5: '<strong>Band 6.5:</strong> Generally effective command despite some inaccuracies.',
    6: '<strong>Band 6 - Competent:</strong> Generally effective command despite inaccuracies and misunderstandings.',
    5.5: '<strong>Band 5.5:</strong> Partial command, coping with overall meaning in most situations.',
    5: '<strong>Band 5 - Modest:</strong> Partial command, coping with overall meaning in most situations.',
    4.5: '<strong>Band 4.5:</strong> Limited command, basic competence in familiar situations.',
    4: '<strong>Band 4 - Limited:</strong> Basic competence limited to familiar situations.',
    3.5: '<strong>Band 3.5:</strong> Extremely limited command in very familiar situations.',
    3: '<strong>Band 3 - Extremely Limited:</strong> Conveys and understands only general meaning.',
};

function selectBand(band) {
    document.getElementById('bandScore').value = band;
    
    // Update active button
    document.querySelectorAll('.band-btn').forEach(btn => {
        btn.classList.remove('active');
        if (parseFloat(btn.textContent) === band) {
            btn.classList.add('active');
        }
    });
    
    // Update descriptor
    updateBandDescriptor(band);
}

function updateBandDescriptor(band) {
    const descriptor = document.getElementById('bandDescriptor');
    const key = Object.keys(bandDescriptors).find(k => parseFloat(k) === band) || 
                Object.keys(bandDescriptors).find(k => parseFloat(k) <= band);
    
    if (key && bandDescriptors[key]) {
        descriptor.innerHTML = bandDescriptors[key];
    } else {
        descriptor.innerHTML = 'Select a band score to see the descriptor.';
    }
}

// Initialize
document.getElementById('bandScore').addEventListener('input', function() {
    const band = parseFloat(this.value);
    if (!isNaN(band)) {
        updateBandDescriptor(band);
    }
});

// Check if there's a current value
const currentBand = parseFloat(document.getElementById('bandScore').value);
if (!isNaN(currentBand) && currentBand > 0) {
    selectBand(currentBand);
}
</script>
@endsection
