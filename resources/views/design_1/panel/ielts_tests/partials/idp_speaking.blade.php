{{-- Speaking Section - IDP Style --}}
{{-- Left: Question/Prompt + Audio (if any), Right: Recording Interface --}}

@php
    $task = $question ?? null;
    $taskText = $task->question_text ?? '';
    $partNum = $section->part_number ?? $section->section_number ?? 1;
    $prepTime = $task->preparation_time ?? ($partNum == 2 ? 60 : 0); // Part 2 has 1 min prep
    $speakTime = $task->speaking_time ?? ($partNum == 2 ? 120 : 60); // Part 2 has 2 min speaking
    $audioUrl = $task->audio_url ?? $section->audio_url ?? '';
    // Prefer question group video, then section video, then audio fallback
    $groupVideoFile = $task->questionGroup->video_file ?? null;
    $videoUrl = $groupVideoFile 
        ? \Storage::disk('public')->url($groupVideoFile) 
        : ($section->video_url ?? $audioUrl);
@endphp

<div class="idp-speaking-layout" style="display: flex; height: 100%;">
    {{-- Left Panel - Question/Prompt --}}
    <div class="idp-speaking-left" style="flex: 1; overflow-y: auto; padding: 20px 28px; border-right: 1px solid #ccc; background: #fff;">
        @if($partNum == 1)
            <div style="margin-bottom: 16px;">
                <strong style="font-size: 15px; color: #333;">Part 1: Introduction and Interview</strong>
            </div>
            <p style="font-size: 14px; color: #666; margin-bottom: 16px;">
                In this part, the examiner will ask you general questions about yourself and familiar topics.
            </p>
        @elseif($partNum == 2)
            <div style="margin-bottom: 16px;">
                <strong style="font-size: 15px; color: #333;">Part 2: Individual Long Turn</strong>
            </div>
            <p style="font-size: 14px; color: #666; margin-bottom: 16px;">
                You will have 1 minute to prepare your answer. Then speak for 1-2 minutes.
            </p>
        @elseif($partNum == 3)
            <div style="margin-bottom: 16px;">
                <strong style="font-size: 15px; color: #333;">Part 3: Two-way Discussion</strong>
            </div>
            <p style="font-size: 14px; color: #666; margin-bottom: 16px;">
                The examiner will ask more abstract questions related to the Part 2 topic.
            </p>
        @endif

        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
            <div style="font-size: 16px; line-height: 1.7; color: #000;">
                {!! nl2br(e($taskText)) !!}
            </div>
        </div>

        @if(!empty($videoUrl))
            <div style="margin-top: 16px; border-radius: 8px; overflow: hidden; background: #000;">
                <video controls controlsList="nodownload" style="width: 100%; max-height: 260px; display: block;">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    <source src="{{ $videoUrl }}" type="video/webm">
                    <source src="{{ $videoUrl }}" type="audio/mpeg">
                    Your browser does not support the video element.
                </video>
            </div>
        @endif

        @if(!empty($task->image_url))
            <div style="margin-top: 16px;">
                <img src="{{ $task->image_url }}" alt="Task image" style="max-width: 100%; border: 1px solid #ccc; border-radius: 8px;">
            </div>
        @endif

        @if(!empty($task->cue_card_points))
            <div style="margin-top: 16px; background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 16px;">
                <strong style="font-size: 14px; color: #92400e; display: block; margin-bottom: 10px;">You should say:</strong>
                <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.8;">
                    @foreach(explode("\n", $task->cue_card_points) as $point)
                        @if(trim($point))
                            <li>{{ trim($point) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Right Panel - Recording Interface --}}
    <div class="idp-speaking-right" style="flex: 1; display: flex; flex-direction: column; padding: 30px; background: #f5f5f0; align-items: center; justify-content: center;">
        
        {{-- Timer Section --}}
        <div id="timerSection" style="text-align: center; margin-bottom: 30px;">
            @if($prepTime > 0)
                <div id="prepPhase">
                    <p style="font-size: 14px; color: #666; margin-bottom: 8px;">Preparation Time</p>
                    <div id="prepTimer" style="font-size: 48px; font-weight: 700; color: #0066CC; font-variant-numeric: tabular-nums;">
                        {{ sprintf('%02d:%02d', floor($prepTime/60), $prepTime%60) }}
                    </div>
                </div>
            @endif
            <div id="speakPhase" style="{{ $prepTime > 0 ? 'display: none;' : '' }}">
                <p style="font-size: 14px; color: #666; margin-bottom: 8px;">Speaking Time</p>
                <div id="speakTimer" style="font-size: 48px; font-weight: 700; color: #333; font-variant-numeric: tabular-nums;">
                    {{ sprintf('%02d:%02d', floor($speakTime/60), $speakTime%60) }}
                </div>
            </div>
        </div>

        {{-- Microphone Visualization --}}
        <div id="micVisualization" style="margin-bottom: 30px;">
            <div id="micIcon" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4); transition: all 0.3s ease; margin: 0 auto;">
                <svg width="48" height="48" fill="white" viewBox="0 0 24 24">
                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                </svg>
            </div>
            
            {{-- Audio Wave Bars --}}
            <div id="audioBars" style="display: none; justify-content: center; gap: 6px; margin-top: 20px; height: 60px; align-items: flex-end;">
                <div class="audio-bar" style="width: 6px; background: linear-gradient(to top, #667eea, #764ba2); border-radius: 3px; animation: audioWave 1s ease-in-out infinite;"></div>
                <div class="audio-bar" style="width: 6px; background: linear-gradient(to top, #667eea, #764ba2); border-radius: 3px; animation: audioWave 1s ease-in-out infinite 0.1s;"></div>
                <div class="audio-bar" style="width: 6px; background: linear-gradient(to top, #667eea, #764ba2); border-radius: 3px; animation: audioWave 1s ease-in-out infinite 0.2s;"></div>
                <div class="audio-bar" style="width: 6px; background: linear-gradient(to top, #667eea, #764ba2); border-radius: 3px; animation: audioWave 1s ease-in-out infinite 0.3s;"></div>
                <div class="audio-bar" style="width: 6px; background: linear-gradient(to top, #667eea, #764ba2); border-radius: 3px; animation: audioWave 1s ease-in-out infinite 0.4s;"></div>
            </div>
        </div>

        {{-- Status Message --}}
        <div id="statusMessage" style="text-align: center; margin-bottom: 20px; min-height: 40px;">
            <div style="display: inline-flex; align-items: center; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; background: #dbeafe; color: #1e40af;">
                <span style="margin-right: 8px;">ℹ️</span>
                <span id="statusText">Click the button below to start recording</span>
            </div>
        </div>

        {{-- Control Buttons --}}
        <div id="controlButtons" style="display: flex; gap: 12px; justify-content: center;">
            @if($prepTime > 0)
                <button type="button" id="btnStartPrep" onclick="startPreparation()" style="padding: 14px 28px; font-size: 16px; font-weight: 600; border-radius: 10px; border: none; cursor: pointer; background: #0066CC; color: white; display: flex; align-items: center; gap: 8px;">
                    <span>▶</span> Start Preparation
                </button>
            @endif
            <button type="button" id="btnRecord" onclick="toggleRecording()" style="padding: 14px 28px; font-size: 16px; font-weight: 600; border-radius: 10px; border: none; cursor: pointer; background: #E31837; color: white; display: {{ $prepTime > 0 ? 'none' : 'flex' }}; align-items: center; gap: 8px;">
                <span>🎤</span> <span id="recordBtnText">Start Recording</span>
            </button>
            <button type="button" id="btnPlayback" onclick="playRecording()" style="padding: 14px 28px; font-size: 16px; font-weight: 600; border-radius: 10px; border: none; cursor: pointer; background: #10b981; color: white; display: none; align-items: center; gap: 8px;">
                <span>▶</span> Play Recording
            </button>
        </div>

        {{-- Recorded Audio (hidden, used for playback) --}}
        <audio id="recordedAudio" style="display: none;"></audio>
        
        {{-- Hidden input to store the audio blob URL --}}
        <input type="hidden" id="speakingAnswerData" name="speaking_answer" data-question-id="{{ $task->id ?? 0 }}">
    </div>
</div>

<style>
    @keyframes audioWave {
        0%, 100% { height: 20%; }
        50% { height: 100%; }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    #micIcon.recording {
        animation: pulse 1.5s infinite;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.6) !important;
    }
</style>

<script>
(function() {
    let mediaRecorder = null;
    let audioStream = null;
    let audioChunks = [];
    let prepTimeRemaining = {{ $prepTime }};
    let speakTimeRemaining = {{ $speakTime }};
    let timerInterval = null;
    let isRecording = false;
    const questionId = {{ $task->id ?? 0 }};
    
    window.startPreparation = function() {
        document.getElementById('btnStartPrep').style.display = 'none';
        document.getElementById('statusText').textContent = 'Preparation time - plan your answer...';
        
        timerInterval = setInterval(function() {
            prepTimeRemaining--;
            const mins = Math.floor(prepTimeRemaining / 60);
            const secs = prepTimeRemaining % 60;
            document.getElementById('prepTimer').textContent = 
                String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            
            if (prepTimeRemaining <= 0) {
                clearInterval(timerInterval);
                // Automatically transition to speaking phase
                document.getElementById('prepPhase').style.display = 'none';
                document.getElementById('speakPhase').style.display = 'block';
                document.getElementById('btnRecord').style.display = 'flex';
                document.getElementById('statusText').textContent = 'Preparation complete! Click to start recording.';
            }
        }, 1000);
    };
    
    window.toggleRecording = async function() {
        if (!isRecording) {
            // Start recording
            try {
                audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(audioStream);
                audioChunks = [];
                
                mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) {
                        audioChunks.push(e.data);
                    }
                };
                
                mediaRecorder.onstop = function() {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    document.getElementById('recordedAudio').src = audioUrl;
                    document.getElementById('speakingAnswerData').value = audioUrl;
                    
                    // Upload the audio
                    uploadAudio(audioBlob);
                };
                
                mediaRecorder.start();
                isRecording = true;
                
                // Update UI
                document.getElementById('micIcon').classList.add('recording');
                document.getElementById('audioBars').style.display = 'flex';
                document.getElementById('recordBtnText').textContent = 'Stop Recording';
                document.getElementById('statusText').textContent = 'Recording in progress...';
                document.querySelector('#statusMessage > div').style.background = '#fee2e2';
                document.querySelector('#statusMessage > div').style.color = '#991b1b';
                
                // Start speaking timer
                timerInterval = setInterval(function() {
                    speakTimeRemaining--;
                    const mins = Math.floor(speakTimeRemaining / 60);
                    const secs = speakTimeRemaining % 60;
                    document.getElementById('speakTimer').textContent = 
                        String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                    
                    if (speakTimeRemaining <= 10) {
                        document.getElementById('speakTimer').style.color = '#ef4444';
                    }
                    
                    if (speakTimeRemaining <= 0) {
                        stopRecording();
                    }
                }, 1000);
                
            } catch (err) {
                console.error('Error accessing microphone:', err);
                document.getElementById('statusText').textContent = 'Microphone access denied. Please allow microphone access.';
                document.querySelector('#statusMessage > div').style.background = '#fee2e2';
                document.querySelector('#statusMessage > div').style.color = '#991b1b';
            }
        } else {
            stopRecording();
        }
    };
    
    function stopRecording() {
        clearInterval(timerInterval);
        isRecording = false;
        
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
        
        if (audioStream) {
            audioStream.getTracks().forEach(track => track.stop());
        }
        
        // Update UI
        document.getElementById('micIcon').classList.remove('recording');
        document.getElementById('audioBars').style.display = 'none';
        document.getElementById('btnRecord').style.display = 'none';
        document.getElementById('btnPlayback').style.display = 'flex';
        document.getElementById('statusText').textContent = 'Recording complete! You can play it back or continue.';
        document.querySelector('#statusMessage > div').style.background = '#d1fae5';
        document.querySelector('#statusMessage > div').style.color = '#065f46';
    }
    
    window.playRecording = function() {
        const audio = document.getElementById('recordedAudio');
        if (audio.src) {
            audio.play();
        }
    };
    
    function uploadAudio(audioBlob) {
        const formData = new FormData();
        formData.append('audio', audioBlob, 'speaking_answer.webm');
        formData.append('question_id', questionId);
        
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Audio uploaded successfully:', data);
        })
        .catch(error => {
            console.error('Error uploading audio:', error);
        });
    }
})();
</script>
