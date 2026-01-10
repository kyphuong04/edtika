{{-- 
    IELTS Speaking Section - Complete Interface
    Includes: Mic Check Modal, Question Display, Recording Interface
--}}
@php
    $task = $question ?? $section ?? null;
    $taskText = $task->question_text ?? $task->content ?? $section->content ?? '';
    $partNum = $section->part_number ?? 1;
    
    // Preparation and speaking times based on part
    $prepTime = $task->preparation_time ?? ($partNum == 2 ? 60 : 0);
    $speakTime = $task->speaking_time ?? ($partNum == 2 ? 120 : ($partNum == 3 ? 90 : 60));
    
    // Get cue card points for Part 2
    $cueCardPoints = $task->cue_card_points ?? '';
    if (is_string($cueCardPoints) && !empty($cueCardPoints)) {
        $cueCardPoints = explode("\n", $cueCardPoints);
    } else {
        $cueCardPoints = [];
    }
    
    // Get audio URL for examiner question (if any)
    $audioUrl = $task->audio_url ?? $section->audio_url ?? '';
    
    // Get saved answer
    $savedAnswer = $userAnswer ?? '';
@endphp

{{-- Include Mic Check Modal --}}
@include('design_1.panel.ielts_tests.partials.speaking_mic_check')

<div class="speaking-container" style="display: flex; height: 100%; background: #f5f5f5;">
    {{-- LEFT PANEL - Question/Prompt Display --}}
    <div class="speaking-question-panel" style="flex: 1; background: #fff; overflow-y: auto; padding: 30px 36px; border-right: 1px solid #ddd;">
        
        {{-- Part Header --}}
        <div class="part-header" style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #E31837;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <span style="background: #E31837; color: #fff; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    SPEAKING
                </span>
                <span style="background: #f3f4f6; color: #1f2937; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    Part {{ $partNum }}
                </span>
            </div>
            
            <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0;">
                @if($partNum == 1)
                    Introduction and Interview
                @elseif($partNum == 2)
                    Individual Long Turn (Cue Card)
                @else
                    Two-way Discussion
                @endif
            </h2>
            
            <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.5;">
                @if($partNum == 1)
                    The examiner will ask you questions about yourself and familiar topics such as home, family, work, studies and interests.
                @elseif($partNum == 2)
                    You will be given a topic on a card. You have <strong>1 minute</strong> to prepare your answer. Then you must speak for <strong>1-2 minutes</strong>.
                @else
                    The examiner will ask further questions connected to the topic in Part 2. These will be more abstract and discussion-based.
                @endif
            </p>
        </div>
        
        {{-- Part 2: Cue Card --}}
        @if($partNum == 2)
            <div class="cue-card" style="background: linear-gradient(145deg, #fffbeb 0%, #fef3c7 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span style="font-size: 24px;">📝</span>
                    <span style="font-size: 15px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">Topic Card</span>
                </div>
                
                <div style="font-size: 17px; color: #78350f; line-height: 1.7; font-weight: 500; margin-bottom: 16px;">
                    {!! nl2br(e($taskText)) !!}
                </div>
                
                @if(!empty($cueCardPoints))
                    <div style="background: rgba(255,255,255,0.7); border-radius: 8px; padding: 16px; margin-top: 16px;">
                        <p style="font-size: 14px; font-weight: 600; color: #92400e; margin: 0 0 12px 0;">You should say:</p>
                        <ul style="margin: 0; padding-left: 24px; color: #78350f; font-size: 15px; line-height: 2;">
                            @foreach($cueCardPoints as $point)
                                @if(trim($point))
                                    <li>{{ trim($point) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px dashed #d97706;">
                    <p style="font-size: 13px; color: #92400e; margin: 0; font-style: italic;">
                        📌 You will have to talk about the topic for one to two minutes. You have one minute to prepare.
                    </p>
                </div>
            </div>
        @else
            {{-- Part 1 & 3: Question Display --}}
            <div class="question-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 20px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <span style="background: #E31837; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;">
                        Q
                    </span>
                    <div style="font-size: 17px; color: #1a1a1a; line-height: 1.7;">
                        {!! nl2br(e($taskText)) !!}
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Examiner Audio (if available) --}}
        @if(!empty($audioUrl))
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                <p style="font-size: 14px; color: #1e40af; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                    <span>🔊</span>
                    <span>Listen to the examiner's question:</span>
                </p>
                <audio controls style="width: 100%;">
                    <source src="{{ $audioUrl }}" type="audio/mpeg">
                    Your browser does not support audio.
                </audio>
            </div>
        @endif
        
        {{-- Image (if available) --}}
        @if(!empty($task->image_url))
            <div style="margin-top: 16px;">
                <img src="{{ $task->image_url }}" alt="Task image" style="max-width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
            </div>
        @endif
        
        {{-- Tips Box --}}
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; margin-top: 20px;">
            <p style="font-size: 14px; font-weight: 600; color: #166534; margin: 0 0 8px 0;">💡 Speaking Tips:</p>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #166534; line-height: 1.8;">
                @if($partNum == 1)
                    <li>Answer in complete sentences, not just "yes" or "no"</li>
                    <li>Give reasons and examples for your answers</li>
                    <li>Speak naturally and at a normal pace</li>
                @elseif($partNum == 2)
                    <li>Use the preparation time to make brief notes</li>
                    <li>Cover all the points on the card</li>
                    <li>Aim to speak for the full 2 minutes</li>
                @else
                    <li>Discuss ideas in depth with examples</li>
                    <li>Use a range of vocabulary and structures</li>
                    <li>Give balanced arguments where appropriate</li>
                @endif
            </ul>
        </div>
    </div>
    
    {{-- RIGHT PANEL - Recording Interface --}}
    <div class="speaking-record-panel" style="flex: 1; display: flex; flex-direction: column; padding: 40px; background: linear-gradient(145deg, #f9fafb 0%, #f3f4f6 100%); align-items: center; justify-content: center;">
        
        {{-- Timer Section --}}
        <div id="speakingTimerSection" style="text-align: center; margin-bottom: 32px;">
            @if($prepTime > 0)
                <div id="prepPhaseSection">
                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Preparation Time</p>
                    <div id="prepTimerDisplay" style="font-size: 56px; font-weight: 700; color: #3b82f6; font-variant-numeric: tabular-nums; text-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);">
                        {{ sprintf('%02d:%02d', floor($prepTime/60), $prepTime%60) }}
                    </div>
                    <div style="width: 200px; height: 6px; background: #e5e7eb; border-radius: 3px; margin: 16px auto;">
                        <div id="prepProgressBar" style="width: 100%; height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 3px; transition: width 1s linear;"></div>
                    </div>
                </div>
            @endif
            
            <div id="speakPhaseSection" style="{{ $prepTime > 0 ? 'display: none;' : '' }}">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Speaking Time</p>
                <div id="speakTimerDisplay" style="font-size: 56px; font-weight: 700; color: #1f2937; font-variant-numeric: tabular-nums;">
                    {{ sprintf('%02d:%02d', floor($speakTime/60), $speakTime%60) }}
                </div>
                <div style="width: 200px; height: 6px; background: #e5e7eb; border-radius: 3px; margin: 16px auto;">
                    <div id="speakProgressBar" style="width: 100%; height: 100%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 3px; transition: width 1s linear;"></div>
                </div>
            </div>
        </div>
        
        {{-- Microphone Visualization --}}
        <div id="micVisualization" style="margin-bottom: 32px;">
            <div id="speakingMicIcon" style="width: 140px; height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 32px rgba(102, 126, 234, 0.35); transition: all 0.3s ease; margin: 0 auto;">
                <svg width="56" height="56" fill="white" viewBox="0 0 24 24">
                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                </svg>
            </div>
            
            {{-- Audio Wave Animation --}}
            <div id="speakingAudioBars" style="display: none; justify-content: center; gap: 6px; margin-top: 24px; height: 60px; align-items: flex-end;">
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.1s;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.2s;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.3s;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.4s;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.5s;"></div>
                <div class="speak-bar" style="width: 6px; background: linear-gradient(to top, #ef4444, #f87171); border-radius: 3px; animation: speakWave 0.8s ease-in-out infinite 0.4s;"></div>
            </div>
        </div>
        
        {{-- Status Message --}}
        <div id="speakingStatus" style="text-align: center; margin-bottom: 24px;">
            <div id="speakingStatusBadge" style="display: inline-flex; align-items: center; padding: 12px 20px; border-radius: 10px; font-size: 15px; font-weight: 500; background: #dbeafe; color: #1e40af;">
                <span style="margin-right: 10px;">ℹ️</span>
                <span id="speakingStatusText">Complete the microphone check to begin</span>
            </div>
        </div>
        
        {{-- Control Buttons --}}
        <div id="speakingControls" style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            @if($prepTime > 0)
                <button type="button" id="btnStartPrep" onclick="SpeakingTest.startPreparation()" style="padding: 16px 32px; font-size: 16px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.35); transition: all 0.2s;">
                    <span>▶</span> Start Preparation
                </button>
            @endif
            
            <button type="button" id="btnStartRecording" onclick="SpeakingTest.toggleRecording()" style="padding: 16px 32px; font-size: 16px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; background: linear-gradient(135deg, #E31837 0%, #be123c 100%); color: white; display: {{ $prepTime > 0 ? 'none' : 'flex' }}; align-items: center; gap: 10px; box-shadow: 0 4px 14px rgba(227, 24, 55, 0.35); transition: all 0.2s;">
                <span>🎤</span> <span id="recordBtnLabel">Start Recording</span>
            </button>
            
            <button type="button" id="btnPlayRecording" onclick="SpeakingTest.playRecording()" style="padding: 16px 32px; font-size: 16px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: none; align-items: center; gap: 10px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35); transition: all 0.2s;">
                <span>▶</span> Play Recording
            </button>
            
            <button type="button" id="btnReRecord" onclick="SpeakingTest.reRecord()" style="padding: 16px 32px; font-size: 16px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; background: #f3f4f6; color: #374151; display: none; align-items: center; gap: 10px; transition: all 0.2s;">
                <span>🔄</span> Re-record
            </button>
        </div>
        
        {{-- Hidden Audio Element --}}
        <audio id="speakingRecordedAudio" style="display: none;"></audio>
        
        {{-- Hidden input for answer data --}}
        <input type="hidden" id="speakingAnswerInput" name="speaking_answer" data-question-id="{{ $task->id ?? 0 }}" value="{{ $savedAnswer }}">
    </div>
</div>

<style>
    @keyframes speakWave {
        0%, 100% { height: 15px; }
        50% { height: 55px; }
    }
    
    @keyframes recordingPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 12px 32px rgba(239, 68, 68, 0.35); }
        50% { transform: scale(1.05); box-shadow: 0 16px 40px rgba(239, 68, 68, 0.5); }
    }
    
    #speakingMicIcon.recording {
        animation: recordingPulse 1.5s ease-in-out infinite;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }
    
    #speakingMicIcon.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }
    
    #speakTimerDisplay.warning {
        color: #f59e0b !important;
    }
    
    #speakTimerDisplay.danger {
        color: #ef4444 !important;
        animation: timerBlink 1s infinite;
    }
    
    @keyframes timerBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>

<script>
const SpeakingTest = {
    mediaRecorder: null,
    audioStream: null,
    audioChunks: [],
    prepTimeRemaining: {{ $prepTime }},
    speakTimeRemaining: {{ $speakTime }},
    totalPrepTime: {{ $prepTime }},
    totalSpeakTime: {{ $speakTime }},
    timerInterval: null,
    isRecording: false,
    hasRecording: false,
    questionId: {{ $task->id ?? 0 }},
    
    init: function() {
        // Wait for mic check to complete before enabling controls
        window.onMicCheckComplete = () => {
            this.enableControls();
            this.updateStatus('info', '✅ Microphone ready. Click to start!');
        };
    },
    
    enableControls: function() {
        const startBtn = document.getElementById('btnStartPrep') || document.getElementById('btnStartRecording');
        if (startBtn) {
            startBtn.disabled = false;
            startBtn.style.opacity = '1';
        }
    },
    
    startPreparation: function() {
        document.getElementById('btnStartPrep').style.display = 'none';
        this.updateStatus('info', '📝 Preparation time - plan your answer...');
        
        this.timerInterval = setInterval(() => {
            this.prepTimeRemaining--;
            this.updatePrepTimer();
            
            // Update progress bar
            const pct = (this.prepTimeRemaining / this.totalPrepTime) * 100;
            document.getElementById('prepProgressBar').style.width = pct + '%';
            
            if (this.prepTimeRemaining <= 10) {
                document.getElementById('prepTimerDisplay').style.color = '#f59e0b';
            }
            
            if (this.prepTimeRemaining <= 0) {
                clearInterval(this.timerInterval);
                this.endPreparation();
            }
        }, 1000);
    },
    
    updatePrepTimer: function() {
        const mins = Math.floor(this.prepTimeRemaining / 60);
        const secs = this.prepTimeRemaining % 60;
        document.getElementById('prepTimerDisplay').textContent = 
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    },
    
    endPreparation: function() {
        document.getElementById('prepPhaseSection').style.display = 'none';
        document.getElementById('speakPhaseSection').style.display = 'block';
        document.getElementById('btnStartRecording').style.display = 'flex';
        this.updateStatus('success', '✅ Preparation complete! Click to start recording.');
    },
    
    toggleRecording: async function() {
        if (!this.isRecording) {
            await this.startRecording();
        } else {
            this.stopRecording();
        }
    },
    
    startRecording: async function() {
        try {
            this.audioStream = await navigator.mediaDevices.getUserMedia({
                audio: { echoCancellation: true, noiseSuppression: true }
            });
            
            this.mediaRecorder = new MediaRecorder(this.audioStream);
            this.audioChunks = [];
            
            this.mediaRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) this.audioChunks.push(e.data);
            };
            
            this.mediaRecorder.onstop = () => this.onRecordingComplete();
            
            this.mediaRecorder.start();
            this.isRecording = true;
            
            // Update UI
            document.getElementById('speakingMicIcon').classList.add('recording');
            document.getElementById('speakingAudioBars').style.display = 'flex';
            document.getElementById('recordBtnLabel').textContent = 'Stop Recording';
            this.updateStatus('recording', '🔴 Recording in progress...');
            
            // Start speaking timer
            this.timerInterval = setInterval(() => {
                this.speakTimeRemaining--;
                this.updateSpeakTimer();
                
                // Update progress bar
                const pct = (this.speakTimeRemaining / this.totalSpeakTime) * 100;
                document.getElementById('speakProgressBar').style.width = pct + '%';
                
                if (this.speakTimeRemaining <= 30) {
                    document.getElementById('speakTimerDisplay').classList.add('warning');
                }
                
                if (this.speakTimeRemaining <= 10) {
                    document.getElementById('speakTimerDisplay').classList.remove('warning');
                    document.getElementById('speakTimerDisplay').classList.add('danger');
                }
                
                if (this.speakTimeRemaining <= 0) {
                    this.stopRecording();
                }
            }, 1000);
            
        } catch (err) {
            console.error('Microphone error:', err);
            this.updateStatus('error', '❌ Microphone access denied. Please allow permission.');
        }
    },
    
    updateSpeakTimer: function() {
        const mins = Math.floor(this.speakTimeRemaining / 60);
        const secs = this.speakTimeRemaining % 60;
        document.getElementById('speakTimerDisplay').textContent = 
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    },
    
    stopRecording: function() {
        clearInterval(this.timerInterval);
        this.isRecording = false;
        
        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
            this.mediaRecorder.stop();
        }
        
        if (this.audioStream) {
            this.audioStream.getTracks().forEach(track => track.stop());
        }
    },
    
    onRecordingComplete: function() {
        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
        const audioUrl = URL.createObjectURL(audioBlob);
        
        document.getElementById('speakingRecordedAudio').src = audioUrl;
        this.hasRecording = true;
        
        // Update UI
        document.getElementById('speakingMicIcon').classList.remove('recording');
        document.getElementById('speakingMicIcon').classList.add('success');
        document.getElementById('speakingAudioBars').style.display = 'none';
        
        document.getElementById('btnStartRecording').style.display = 'none';
        document.getElementById('btnPlayRecording').style.display = 'flex';
        document.getElementById('btnReRecord').style.display = 'flex';
        
        this.updateStatus('success', '✅ Recording complete! You can play it back or continue.');
        
        // Upload the audio
        this.uploadAudio(audioBlob);
    },
    
    playRecording: function() {
        const audio = document.getElementById('speakingRecordedAudio');
        if (audio.src) {
            audio.play();
        }
    },
    
    reRecord: function() {
        // Reset
        this.speakTimeRemaining = this.totalSpeakTime;
        this.hasRecording = false;
        
        document.getElementById('speakingMicIcon').classList.remove('success');
        document.getElementById('speakTimerDisplay').classList.remove('warning', 'danger');
        this.updateSpeakTimer();
        
        // Update progress bar
        document.getElementById('speakProgressBar').style.width = '100%';
        
        // Show/hide buttons
        document.getElementById('btnPlayRecording').style.display = 'none';
        document.getElementById('btnReRecord').style.display = 'none';
        document.getElementById('btnStartRecording').style.display = 'flex';
        document.getElementById('recordBtnLabel').textContent = 'Start Recording';
        
        this.updateStatus('info', 'ℹ️ Click to start a new recording');
    },
    
    uploadAudio: function(audioBlob) {
        const formData = new FormData();
        formData.append('audio', audioBlob, 'speaking_' + this.questionId + '.webm');
        formData.append('question_id', this.questionId);
        
        fetch(saveUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            console.log('Audio uploaded:', data);
            if (data.audio_url) {
                document.getElementById('speakingAnswerInput').value = data.audio_url;
            }
        })
        .catch(err => console.error('Upload error:', err));
    },
    
    updateStatus: function(type, message) {
        const badge = document.getElementById('speakingStatusBadge');
        const bgColors = {
            info: '#dbeafe',
            recording: '#fee2e2',
            success: '#d1fae5',
            error: '#fee2e2'
        };
        const textColors = {
            info: '#1e40af',
            recording: '#991b1b',
            success: '#065f46',
            error: '#991b1b'
        };
        
        badge.style.background = bgColors[type] || bgColors.info;
        badge.style.color = textColors[type] || textColors.info;
        
        const icon = message.match(/^[^\s]+/)[0];
        const text = message.replace(/^[^\s]+\s/, '');
        badge.innerHTML = '<span style="margin-right: 10px;">' + icon + '</span><span id="speakingStatusText">' + text + '</span>';
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    SpeakingTest.init();
});
</script>
