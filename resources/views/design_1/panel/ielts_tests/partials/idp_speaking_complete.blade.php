{{-- Speaking Section - Wireframe Design --}}
@php
    $partNum = $section->part_number ?? $section->section_number ?? 1;
    $speakQuestions = isset($allQuestions) ? $allQuestions->sortBy('question_number')->values() : collect();
    $sectionVideoUrl = $section->video_url ?? null;
@endphp

<style>
    .spk-layout{display:flex;flex:1;min-width:0;height:100%;background:#ececec;overflow:hidden;}
    .spk-left{width:50%;flex-shrink:0;overflow-y:auto;padding:20px 24px;display:flex;flex-direction:column;align-items:center;gap:16px;}
    .spk-instruction{font-size:13px;color:#333;line-height:1.5;width:100%;}
    .spk-instruction strong{font-size:13px;font-weight:700;}
    .spk-player-wrap{background:#d4d4d4;border-radius:8px;overflow:hidden;width:100%;max-width:490px;}
    .spk-player-wrap video{width:100%;display:block;background:#d4d4d4;max-height:190px;}
    .spk-player-placeholder{width:100%;max-width:490px;aspect-ratio:16/9;background:#d4d4d4;border-radius:8px;display:flex;align-items:center;justify-content:center;}
    .spk-mic-btn-wrap{display:flex;justify-content:center;width:100%;max-width:490px;}
    .spk-mic-btn{width:64px;height:64px;border-radius:50%;background:#b0b0b0;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,transform .15s;}
    .spk-mic-btn:hover{background:#999;transform:scale(1.05);}
    .spk-mic-btn.recording{background:#e53935;animation:spkPulse 1.2s ease-in-out infinite;}
    .spk-mic-btn.done{background:#43a047;}
    @keyframes spkPulse{0%,100%{box-shadow:0 0 0 0 rgba(229,57,53,.5)}50%{box-shadow:0 0 0 14px rgba(229,57,53,0)}}
    .spk-answer-box{background:#d4d4d4;border-radius:10px;padding:12px 14px;width:100%;max-width:490px;}
    .spk-answer-label{font-size:12px;font-weight:700;color:#555;text-align:center;margin-bottom:10px;}
    .spk-answer-player{display:flex;align-items:center;gap:10px;background:#fff;border-radius:6px;padding:8px 12px;margin-bottom:10px;}
    .spk-play-mini{width:28px;height:28px;border-radius:50%;background:#333;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .spk-answer-time{font-size:12px;color:#444;white-space:nowrap;flex-shrink:0;}
    .spk-answer-progress{flex:1;height:4px;background:#ccc;border-radius:2px;overflow:hidden;}
    .spk-answer-progress-fill{height:100%;width:0%;background:#555;border-radius:2px;transition:width .2s;}
    .spk-answer-btns{display:flex;gap:10px;}
    .spk-answer-btns button{flex:1;padding:8px 12px;border-radius:6px;border:1.5px solid #888;background:transparent;font-size:12px;font-weight:600;color:#333;cursor:pointer;transition:background .15s;}
    .spk-answer-btns button:hover{background:rgba(0,0,0,.07);}
    .spk-no-recording{font-size:12px;color:#888;text-align:center;padding:6px 0 4px;}
    .spk-right{width:50%;flex-shrink:0;border-left:1px solid #ccc;overflow-y:hidden;padding:20px;display:flex;flex-direction:column;gap:14px;background:#ececec;}
    .spk-notes-label,.spk-model-label{font-size:13px;font-weight:700;color:#222;margin-bottom:6px;}
    .spk-right-notes{flex:1;display:flex;flex-direction:column;min-height:0;}
    .spk-right-model{flex-shrink:0;}
    .spk-notes-area{width:100%;flex:1;min-height:0;padding:10px 12px;border:1px solid #bbb;border-radius:6px;background:#fff;font-size:13px;color:#333;resize:none;font-family:Arial,sans-serif;line-height:1.5;}
    .spk-notes-area:focus{outline:none;border-color:#888;}
    .spk-model-box{background:#fff;border:1px solid #ccc;border-radius:6px;padding:12px;font-size:13px;color:#444;line-height:1.6;}
    .spk-model-box.empty{color:#aaa;font-style:italic;}
    .spk-q-panel{display:none;height:100%;width:100%;}
    .spk-q-panel.active{display:flex;flex:1;min-width:0;height:100%;width:100%;}
</style>

<div class="spk-layout" id="spkLayout">
    @foreach($speakQuestions as $qIndex => $task)
    @php
        $qNum      = $task->question_number ?? ($qIndex + 1);
        $qId       = $task->id;
        $taskText  = $task->question_text ?? $task->content ?? '';
        $audioUrl  = $task->audio_url ?? $section->audio_url ?? '';
        // Prefer question group video, then section video, then audio fallback
        $groupVideoFile = $task->questionGroup->video_file ?? null;
        $videoUrl  = $groupVideoFile 
            ? \Storage::disk('public')->url($groupVideoFile) 
            : ($sectionVideoUrl ?? $audioUrl);
        $modelAns  = $task->model_answer ?? '';
        $savedAns  = $userAnswers[$qId] ?? '';
        $speakTime = $task->speaking_time ?? ($partNum == 2 ? 120 : ($partNum == 3 ? 90 : 60));
    @endphp
    <div class="spk-q-panel {{ $qIndex === 0 ? 'active' : '' }}"
         id="spk-q-panel-{{ $qNum }}"
         data-q-num="{{ $qNum }}"
         data-q-id="{{ $qId }}">

        {{-- LEFT --}}
        <div class="spk-left">
            <div class="spk-instruction">
                <strong>Questions {{ $qNum }}:</strong><br>
                @if(!empty($taskText))
                    {!! nl2br(e($taskText)) !!}
                @else
                    Press the play button to listen to the question and then record your answer.
                @endif
                @if(!empty($task->cue_card_points))
                    <div style="margin-top:10px;padding:10px 12px;background:#fff;border-radius:6px;border:1px solid #ccc;">
                        <div style="font-weight:700;margin-bottom:6px;">You should say:</div>
                        <ul style="padding-left:18px;margin:0;line-height:1.8;">
                            @foreach(explode("\n", $task->cue_card_points) as $pt)
                                @if(trim($pt))<li>{{ trim($pt) }}</li>@endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @if(!empty($videoUrl))
                <div class="spk-player-wrap">
                    <video controls controlsList="nodownload" style="width:100%;border-radius:8px;background:#000;">
                        <source src="{{ $videoUrl }}" type="video/mp4">
                        <source src="{{ $videoUrl }}" type="video/webm">
                        <source src="{{ $videoUrl }}" type="audio/mpeg">
                        <source src="{{ $videoUrl }}" type="audio/webm">
                        Your browser does not support the video element.
                    </video>
                </div>
            @else
                <div class="spk-player-placeholder">
                    <svg width="48" height="48" fill="#888" viewBox="0 0 24 24">
                        <path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/>
                    </svg>
                </div>
            @endif

            <div class="spk-mic-btn-wrap">
                <button type="button" class="spk-mic-btn" id="spk-mic-{{ $qId }}"
                    onclick="SpkTest.toggleMic({{ $qId }}, {{ $speakTime }})" title="Click to record">
                    <svg width="26" height="26" fill="white" viewBox="0 0 24 24">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                    </svg>
                </button>
            </div>

            <div class="spk-answer-box">
                <div class="spk-answer-label">Your answer</div>
                <div id="spk-no-rec-{{ $qId }}" class="spk-no-recording"
                     style="{{ !empty($savedAns) ? 'display:none' : '' }}">No recording yet</div>
                <div id="spk-player-row-{{ $qId }}"
                     style="display:{{ !empty($savedAns) ? 'flex' : 'none' }};flex-direction:column;gap:10px;">
                    <div class="spk-answer-player">
                        <button class="spk-play-mini" onclick="SpkTest.playBack({{ $qId }})">
                            <svg width="10" height="12" viewBox="0 0 10 12" fill="white">
                                <polygon points="0,0 10,6 0,12"/>
                            </svg>
                        </button>
                        <span class="spk-answer-time" id="spk-time-{{ $qId }}">0:00 / 0:00</span>
                        <div class="spk-answer-progress">
                            <div class="spk-answer-progress-fill" id="spk-prog-{{ $qId }}"></div>
                        </div>
                    </div>
                    <div class="spk-answer-btns">
                        <button onclick="SpkTest.reRecord({{ $qId }})">Record again</button>
                        <button onclick="SpkTest.saveAnswer({{ $qId }})">Save answer</button>
                    </div>
                </div>
                <audio id="spk-audio-{{ $qId }}"
                       src="{{ !empty($savedAns) ? $savedAns : '' }}"
                       style="display:none;"></audio>
                <input type="hidden" id="spk-input-{{ $qId }}"
                       data-q-id="{{ $qId }}" value="{{ $savedAns }}">
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="spk-right">
            <div class="spk-right-notes">
                <div class="spk-notes-label">Take notes</div>
                <textarea class="spk-notes-area" id="spk-notes-{{ $qId }}"
                    oninput="SpkTest.saveNote({{ $qId }}, this.value)"
                    placeholder=""></textarea>
            </div>
            <div class="spk-right-model">
                <div class="spk-model-label">Model answer</div>
                <div class="spk-model-box {{ empty($modelAns) ? 'empty' : '' }}">
                    {!! !empty($modelAns) ? nl2br(e($modelAns)) : 'No model answer available.' !!}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
(function(){
    var state = {};
    function gs(q) {
        if (!state[q]) state[q] = {
            mediaRecorder: null, audioStream: null, audioChunks: [],
            isRecording: false, timerInterval: null, blob: null, url: null
        };
        return state[q];
    }

    window.SpkTest = {
        toggleMic: async function(qId, maxSecs) {
            var s = gs(qId);
            if (s.isRecording) this.stopRecording(qId);
            else await this.startRecording(qId, maxSecs);
        },
        startRecording: async function(qId, maxSecs) {
            var s = gs(qId), self = this;
            try {
                s.audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                s.mediaRecorder = new MediaRecorder(s.audioStream);
                s.audioChunks = [];
                var elapsed = 0;
                s.mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) s.audioChunks.push(e.data);
                };
                s.mediaRecorder.onstop = function() { self.onDone(qId, elapsed); };
                s.mediaRecorder.start();
                s.isRecording = true;
                var btn = document.getElementById('spk-mic-' + qId);
                if (btn) btn.className = 'spk-mic-btn recording';
                s.timerInterval = setInterval(function() {
                    elapsed++;
                    if (elapsed >= maxSecs) self.stopRecording(qId);
                }, 1000);
            } catch(e) {
                console.error(e);
                alert('Microphone access denied. Please allow microphone access.');
            }
        },
        stopRecording: function(qId) {
            var s = gs(qId);
            clearInterval(s.timerInterval);
            s.isRecording = false;
            if (s.mediaRecorder && s.mediaRecorder.state !== 'inactive') s.mediaRecorder.stop();
            if (s.audioStream) s.audioStream.getTracks().forEach(function(t) { t.stop(); });
        },
        onDone: function(qId, elapsed) {
            var s = gs(qId), self = this;
            s.blob = new Blob(s.audioChunks, { type: 'audio/webm' });
            s.url  = URL.createObjectURL(s.blob);
            var audio = document.getElementById('spk-audio-' + qId);
            if (audio) {
                audio.src = s.url;
                audio.load();
                audio.addEventListener('timeupdate', function() { self.updateProg(qId, audio); });
                audio.addEventListener('loadedmetadata', function() {
                    var t = document.getElementById('spk-time-' + qId);
                    if (t) t.textContent = '0:00 / ' + self.fmt(audio.duration);
                });
            }
            document.getElementById('spk-no-rec-' + qId).style.display = 'none';
            document.getElementById('spk-player-row-' + qId).style.display = 'flex';
            var btn = document.getElementById('spk-mic-' + qId);
            if (btn) btn.className = 'spk-mic-btn done';
            var panel = document.querySelector('.spk-q-panel[data-q-id="' + qId + '"]');
            if (panel) {
                var n = panel.dataset.qNum;
                var c = document.querySelector('.idp-q-circle[data-q-num="' + n + '"]');
                if (c) c.classList.add('answered');
            }
        },
        updateProg: function(qId, audio) {
            if (!audio.duration) return;
            var p = document.getElementById('spk-prog-' + qId);
            var t = document.getElementById('spk-time-' + qId);
            if (p) p.style.width = (audio.currentTime / audio.duration * 100) + '%';
            if (t) t.textContent = this.fmt(audio.currentTime) + ' / ' + this.fmt(audio.duration);
        },
        playBack: function(qId) {
            var a = document.getElementById('spk-audio-' + qId);
            if (a && a.src) a.play();
        },
        reRecord: function(qId) {
            var s = gs(qId);
            s.blob = null; s.url = null;
            document.getElementById('spk-no-rec-' + qId).style.display = '';
            document.getElementById('spk-player-row-' + qId).style.display = 'none';
            var btn = document.getElementById('spk-mic-' + qId);
            if (btn) btn.className = 'spk-mic-btn';
        },
        saveAnswer: function(qId) {
            var s = gs(qId);
            if (!s.blob) { alert('No recording to save yet.'); return; }
            var fd = new FormData();
            fd.append('audio', s.blob, 'speaking_' + qId + '.webm');
            fd.append('question_id', qId);
            fetch(saveUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }, body: fd })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (d.audio_url) document.getElementById('spk-input-' + qId).value = d.audio_url;
                    var panel = document.querySelector('.spk-q-panel[data-q-id="' + qId + '"]');
                    if (panel) {
                        var n = panel.dataset.qNum;
                        var c = document.querySelector('.idp-q-circle[data-q-num="' + n + '"]');
                        if (c) c.classList.add('answered');
                    }
                })
                .catch(function(e) { console.error(e); });
        },
        saveNote: function(qId, val) {
            try { localStorage.setItem('spk_note_' + qId, val); } catch(e) {}
        },
        fmt: function(s) {
            if (!s || isNaN(s)) return '0:00';
            return Math.floor(s / 60) + ':' + String(Math.floor(s % 60)).padStart(2, '0');
        }
    };

    // Restore notes from localStorage
    document.querySelectorAll('[id^="spk-notes-"]').forEach(function(ta) {
        var qId = ta.id.replace('spk-notes-', '');
        var v = localStorage.getItem('spk_note_' + qId);
        if (v) ta.value = v;
    });

    // Override goToQuestion for speaking navigation
    window.currentQuestionIndex = 0;
    window.goToQuestion = function(num, index) {
        document.querySelectorAll('.spk-q-panel').forEach(function(p) { p.classList.remove('active'); });
        var t = document.getElementById('spk-q-panel-' + num);
        if (t) t.classList.add('active');
        document.querySelectorAll('.idp-q-circle').forEach(function(c) { c.classList.remove('active'); });
        var c = document.querySelector('.idp-q-circle[data-q-num="' + num + '"]');
        if (c) c.classList.add('active');
        window.currentQuestionIndex = index;
    };
})();
</script>
