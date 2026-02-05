{{-- 
    Speaking Microphone Check Modal
    This modal appears before starting a speaking test to ensure the mic works
    Records and allows playback without saving to database
--}}
<style>
    .mic-check-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }
    
    .mic-check-overlay.hidden {
        display: none;
    }
    
    .mic-check-card {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 520px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        text-align: center;
    }
    
    .mic-check-header {
        margin-bottom: 28px;
    }
    
    .mic-check-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .mic-check-icon svg {
        width: 36px;
        height: 36px;
        fill: white;
    }
    
    .mic-check-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .mic-check-subtitle {
        font-size: 15px;
        color: #666;
        line-height: 1.5;
    }
    
    /* Steps Indicator */
    .mic-check-steps {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 28px;
    }
    
    .mic-step {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #999;
    }
    
    .mic-step.active {
        color: #667eea;
        font-weight: 600;
    }
    
    .mic-step.completed {
        color: #10b981;
    }
    
    .mic-step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
    
    .mic-step.active .mic-step-num {
        background: #667eea;
        color: #fff;
    }
    
    .mic-step.completed .mic-step-num {
        background: #10b981;
        color: #fff;
    }
    
    /* Recording Area */
    .mic-recording-area {
        background: #f9fafb;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
    }
    
    /* Mic Visualization */
    .mic-visual {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        transition: all 0.3s ease;
    }
    
    .mic-visual.recording {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        animation: micPulse 1.5s ease-in-out infinite;
    }
    
    .mic-visual.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .mic-visual svg {
        width: 40px;
        height: 40px;
        fill: white;
    }
    
    @keyframes micPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); }
    }
    
    /* Audio Bars */
    .mic-audio-bars {
        display: flex;
        justify-content: center;
        gap: 5px;
        height: 50px;
        align-items: flex-end;
        margin-bottom: 20px;
    }
    
    .mic-audio-bars.hidden {
        display: none;
    }
    
    .mic-bar {
        width: 6px;
        background: linear-gradient(to top, #667eea, #764ba2);
        border-radius: 3px;
        transition: height 0.1s ease;
    }
    
    .mic-bar.recording {
        animation: barWave 0.8s ease-in-out infinite;
    }
    
    .mic-bar:nth-child(1) { animation-delay: 0s; }
    .mic-bar:nth-child(2) { animation-delay: 0.1s; }
    .mic-bar:nth-child(3) { animation-delay: 0.2s; }
    .mic-bar:nth-child(4) { animation-delay: 0.3s; }
    .mic-bar:nth-child(5) { animation-delay: 0.4s; }
    .mic-bar:nth-child(6) { animation-delay: 0.3s; }
    .mic-bar:nth-child(7) { animation-delay: 0.2s; }
    
    @keyframes barWave {
        0%, 100% { height: 10px; }
        50% { height: 45px; }
    }
    
    /* Timer */
    .mic-timer {
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
        font-variant-numeric: tabular-nums;
    }
    
    .mic-timer.danger {
        color: #ef4444;
    }
    
    .mic-timer-label {
        font-size: 13px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Status Message */
    .mic-status {
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .mic-status.info {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .mic-status.recording {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .mic-status.success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .mic-status.error {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Playback */
    .mic-playback {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        margin-top: 16px;
    }
    
    .mic-playback.hidden {
        display: none;
    }
    
    .mic-playback-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .mic-playback audio {
        width: 100%;
        height: 40px;
    }
    
    /* Buttons */
    .mic-check-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    
    .mic-check-buttons .hidden {
        display: none !important;
    }
    
    .mic-btn {
        padding: 14px 28px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .mic-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .mic-btn-record {
        background: #E31837;
        color: white;
    }
    
    .mic-btn-record:hover:not(:disabled) {
        background: #c41530;
    }
    
    .mic-btn-retry {
        background: #f3f4f6;
        color: #1f2937;
    }
    
    .mic-btn-retry:hover {
        background: #e5e7eb;
    }
    
    .mic-btn-continue {
        background: #10b981;
        color: white;
    }
    
    .mic-btn-continue:hover:not(:disabled) {
        background: #059669;
    }
    
    .mic-btn-skip {
        background: transparent;
        color: #666;
        border: 1px solid #ddd;
    }
    
    .mic-btn-skip:hover {
        background: #f9fafb;
    }
    
    /* Post-recording buttons row */
    .mic-post-record-btns {
        display: flex;
        gap: 10px;
        width: 100%;
    }
    
    .mic-post-record-btns .mic-btn {
        flex: 1;
    }
</style>

<div class="mic-check-overlay" id="micCheckOverlay">
    <div class="mic-check-card">
        <div class="mic-check-header">
            <div class="mic-check-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                </svg>
            </div>
            <h2 class="mic-check-title">{{ trans('update.ielts_mic_check') }}</h2>
            <p class="mic-check-subtitle">{{ trans('update.ielts_mic_check_subtitle') }}</p>
        </div>
        
        <!-- Steps Indicator -->
        <div class="mic-check-steps">
            <div class="mic-step active" id="step1">
                <span class="mic-step-num">1</span>
                <span>{{ trans('update.ielts_record') }}</span>
            </div>
            <div class="mic-step" id="step2">
                <span class="mic-step-num">2</span>
                <span>{{ trans('update.ielts_listen') }}</span>
            </div>
            <div class="mic-step" id="step3">
                <span class="mic-step-num">3</span>
                <span>{{ trans('update.ielts_confirm') }}</span>
            </div>
        </div>
        
        <!-- Recording Area -->
        <div class="mic-recording-area">
            <div class="mic-visual" id="micVisual">
                <svg viewBox="0 0 24 24">
                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                </svg>
            </div>
            
            <div class="mic-audio-bars hidden" id="audioBarsCheck">
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
                <div class="mic-bar"></div>
            </div>
            
            <div class="mic-timer" id="micTimer">00:10</div>
            <div class="mic-timer-label">{{ trans('update.ielts_recording_time') }}</div>
            
            <div class="mic-status info" id="micStatus">
                <span>ℹ️</span>
                <span id="micStatusText">{{ trans('update.ielts_mic_check_instruction') }}</span>
            </div>
            
            <!-- Playback Section -->
            <div class="mic-playback hidden" id="playbackSection">
                <div class="mic-playback-label">{{ trans('update.ielts_listen_recording') }}</div>
                <audio id="checkPlayback" controls></audio>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="mic-check-buttons" id="micButtons">
            <button type="button" class="mic-btn mic-btn-record" id="btnStartRecord" onclick="MicCheck.startRecording()">
                <span>🎤</span> {{ trans('update.ielts_speaking_start_recording') }}
            </button>
            
            <div class="mic-post-record-btns hidden" id="postRecordBtns">
                <button type="button" class="mic-btn mic-btn-retry" id="btnRetry" onclick="MicCheck.retry()">
                    <span>🔄</span> {{ trans('update.ielts_retry') }}
                </button>
                
                <button type="button" class="mic-btn mic-btn-continue" id="btnContinue" onclick="MicCheck.proceed()">
                    <span>✓</span> {{ trans('update.ielts_sounds_good') }}
                </button>
            </div>
            
            <button type="button" class="mic-btn mic-btn-skip" id="btnSkip" onclick="MicCheck.skip()">
                {{ trans('update.ielts_skip_check') }}
            </button>
        </div>
    </div>
</div>

<script>
const MicCheck = {
    mediaRecorder: null,
    audioStream: null,
    audioChunks: [],
    timeRemaining: 10,
    timerInterval: null,
    isRecording: false,
    
    startRecording: async function() {
        try {
            this.audioStream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                }
            });
            
            this.mediaRecorder = new MediaRecorder(this.audioStream);
            this.audioChunks = [];
            
            this.mediaRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) this.audioChunks.push(e.data);
            };
            
            this.mediaRecorder.onstop = () => this.onRecordingComplete();
            
            this.mediaRecorder.start();
            this.isRecording = true;
            this.timeRemaining = 10;
            
            // Update UI
            document.getElementById('micVisual').classList.add('recording');
            document.getElementById('audioBarsCheck').classList.remove('hidden');
            document.querySelectorAll('.mic-bar').forEach(bar => bar.classList.add('recording'));
            
            document.getElementById('btnStartRecord').classList.add('hidden');
            document.getElementById('btnSkip').classList.add('hidden');
            
            this.updateStatus('recording', '🔴 {{ trans('update.ielts_recording') }}... {{ trans('update.ielts_mic_test_speak_instruction') }}');
            
            // Start timer
            this.timerInterval = setInterval(() => {
                this.timeRemaining--;
                this.updateTimer();
                
                if (this.timeRemaining <= 3) {
                    document.getElementById('micTimer').classList.add('danger');
                }
                
                if (this.timeRemaining <= 0) {
                    this.stopRecording();
                }
            }, 1000);
            
            // Update steps
            document.getElementById('step1').classList.add('active');
            
        } catch (err) {
            console.error('Microphone access error:', err);
            this.updateStatus('error', '❌ Unable to access microphone. Please allow microphone permission.');
        }
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
        
        // Update UI
        document.getElementById('micVisual').classList.remove('recording');
        document.getElementById('audioBarsCheck').classList.add('hidden');
        document.querySelectorAll('.mic-bar').forEach(bar => bar.classList.remove('recording'));
    },
    
    onRecordingComplete: function() {
        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
        const audioUrl = URL.createObjectURL(audioBlob);
        
        // Set up playback
        const playback = document.getElementById('checkPlayback');
        playback.src = audioUrl;
        document.getElementById('playbackSection').classList.remove('hidden');
        
        // Update UI
        document.getElementById('micVisual').classList.add('success');
        this.updateStatus('success', '✅ {{ trans('update.ielts_recording_complete') }}! {{ trans('update.ielts_listen_quality_check') }}');
        
        // Show buttons
        document.getElementById('postRecordBtns').classList.remove('hidden');
        
        // Update steps
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step1').classList.add('completed');
        document.getElementById('step2').classList.add('active');
    },
    
    retry: function() {
        // Reset everything
        document.getElementById('micVisual').classList.remove('success');
        document.getElementById('playbackSection').classList.add('hidden');
        document.getElementById('micTimer').classList.remove('danger');
        this.timeRemaining = 10;
        this.updateTimer();
        
        // Reset buttons
        document.getElementById('postRecordBtns').classList.add('hidden');
        document.getElementById('btnStartRecord').classList.remove('hidden');
        document.getElementById('btnSkip').classList.remove('hidden');
        
        // Reset steps
        document.getElementById('step1').classList.remove('completed');
        document.getElementById('step1').classList.add('active');
        document.getElementById('step2').classList.remove('active');
        
        this.updateStatus('info', 'ℹ️ {{ trans('update.ielts_mic_check_instruction') }}');
    },
    
    proceed: function() {
        // Hide overlay and continue to test
        document.getElementById('micCheckOverlay').classList.add('hidden');
        
        // Update steps
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step2').classList.add('completed');
        document.getElementById('step3').classList.add('completed');
        
        // Notify parent that mic check is complete
        if (typeof onMicCheckComplete === 'function') {
            onMicCheckComplete();
        }
    },
    
    skip: function() {
        // Skip the check and continue
        document.getElementById('micCheckOverlay').classList.add('hidden');
        
        if (typeof onMicCheckComplete === 'function') {
            onMicCheckComplete();
        }
    },
    
    updateTimer: function() {
        const mins = Math.floor(this.timeRemaining / 60);
        const secs = this.timeRemaining % 60;
        document.getElementById('micTimer').textContent = 
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    },
    
    updateStatus: function(type, message) {
        const status = document.getElementById('micStatus');
        status.className = 'mic-status ' + type;
        document.getElementById('micStatusText').textContent = message.replace(/^[^\s]+\s/, '');
        
        const icon = message.match(/^[^\s]+/)[0];
        status.innerHTML = '<span>' + icon + '</span><span id="micStatusText">' + message.replace(/^[^\s]+\s/, '') + '</span>';
    }
};

// Callback function to be called when mic check is complete
function onMicCheckComplete() {
    console.log('Mic check complete, starting speaking test...');
    // The speaking test will handle this
}
</script>
