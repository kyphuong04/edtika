@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css">
<style>
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:1000px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }

.pt-mic-wrap { max-width:700px; margin:60px auto 80px; padding:0 20px; }
.pt-mic-card { background:#fff; border-radius:24px; border:1px solid #e5e7eb; box-shadow:0 10px 30px rgba(0,0,0,.06); padding:40px; text-align:center; }
.pt-mic-title { font-size:22px; font-weight:900; color:#111827; margin-bottom:10px; }
.pt-mic-question { background:#f5f3ff; border:1px dashed #a78bfa; border-radius:14px; padding:18px; color:#511D99; font-weight:600; font-size:16px; margin-bottom:28px; text-align:left; }

.pt-mic-btn { width:80px; height:80px; border-radius:50%; border:none; background:#511D99; color:#fff; font-size:30px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 8px 18px rgba(81,29,153,.3); transition:.15s; }
.pt-mic-btn:hover { background:#3f1677; }
.pt-mic-btn.is-recording { background:#dc2626; animation:pt-pulse 1.2s infinite; }
.pt-mic-btn:disabled { opacity:.5; cursor:not-allowed; }
@keyframes pt-pulse { 0% { box-shadow:0 0 0 0 rgba(220,38,38,.5);} 70% { box-shadow:0 0 0 16px rgba(220,38,38,0);} 100% { box-shadow:0 0 0 0 rgba(220,38,38,0);} }

.pt-mic-status { margin-top:14px; font-size:15px; color:#4b5563; font-weight:600; }
.pt-mic-error { margin-top:14px; font-size:13px; color:#dc2626; font-weight:600; display:none; }

.pt-player { display:none; align-items:center; gap:10px; margin-top:24px; padding:12px 14px; background:#f5f3ff; border-radius:12px; }
.pt-player.is-visible { display:flex; }
.pt-player button { width:32px; height:32px; border-radius:50%; background:#fff; border:1px solid #ddd6fe; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; color:#511D99; flex-shrink:0; }
.pt-player button.pt-play-toggle { width:36px; height:36px; background:#511D99; color:#fff; border:none; }
.pt-player .pt-time { font-size:13px; font-weight:600; color:#374151; white-space:nowrap; }
.pt-progress-track { flex:1; height:5px; background:#ddd6fe; border-radius:99px; position:relative; cursor:pointer; min-width:60px; }
.pt-progress-fill { position:absolute; left:0; top:0; height:100%; background:#511D99; border-radius:99px; width:0%; }

.pt-rerecord-link { display:block; margin-top:10px; font-size:13px; color:#9ca3af; text-decoration:underline; cursor:pointer; }

.pt-mic-divider { border:none; border-top:1px solid #e5e7eb; margin:28px 0 20px; }
.pt-mic-continue-btn { width:100%; padding:14px; border-radius:14px; border:none; font-weight:700; font-size:16px; cursor:pointer; background:#511D99; color:#fff; box-shadow:0 8px 20px rgba(81,29,153,.3); }
.pt-mic-skip-link { display:block; margin-top:14px; font-size:13px; color:#9ca3af; text-decoration:underline; cursor:pointer; }
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
</div>

<div class="pt-mic-wrap">
    <div class="pt-mic-card">
        <h2 class="pt-mic-title">Phần thi Nói (Speaking)</h2>

        @if($question)
            <div class="pt-mic-question">{{ $question->question_text }}</div>
        @endif

        <button type="button" class="pt-mic-btn" id="ptMicBtn">
            <i class="fas fa-microphone"></i>
        </button>
        <div class="pt-mic-status" id="ptMicStatus">Nhấn để ghi âm câu trả lời của bạn</div>
        <div class="pt-mic-error" id="ptMicError">
            Không truy cập được microphone. Hãy cho phép trình duyệt sử dụng mic rồi thử lại.
        </div>

        <div class="pt-player" id="ptPlayer">
            <button type="button" class="pt-play-toggle" id="ptPlayToggle"><i class="fas fa-play"></i></button>
            <span class="pt-time" id="ptTimeLabel">00:00</span>
            <div class="pt-progress-track" id="ptProgressTrack"><div class="pt-progress-fill" id="ptProgressFill"></div></div>
        </div>

        <div id="ptRerecordWrap" style="display:none;">
            <span class="pt-rerecord-link" id="ptRerecordLink">Ghi âm lại</span>
        </div>

        <hr class="pt-mic-divider">

        <form id="ptSpeakingForm" action="{{ route('placement.submit_speaking') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="recording" id="ptRecordingInput" style="display:none;">
            <button type="submit" class="pt-mic-continue-btn">Nộp bài</button>
            <span class="pt-mic-skip-link" id="ptSkipLink">Bỏ qua phần này</span>
        </form>
    </div>
</div>

<audio id="ptAudioPlayback" style="display:none;"></audio>

<script>
(function () {
    var micBtn = document.getElementById('ptMicBtn');
    var micStatus = document.getElementById('ptMicStatus');
    var micError = document.getElementById('ptMicError');
    var player = document.getElementById('ptPlayer');
    var rerecordWrap = document.getElementById('ptRerecordWrap');
    var rerecordLink = document.getElementById('ptRerecordLink');
    var audioEl = document.getElementById('ptAudioPlayback');
    var recordingInput = document.getElementById('ptRecordingInput');

    var mediaRecorder = null;
    var audioChunks = [];
    var isRecording = false;
    var recordedBlobUrl = null;

    function setMicIdle() {
        isRecording = false;
        micBtn.classList.remove('is-recording');
        micBtn.innerHTML = '<i class="fas fa-microphone"></i>';
    }

    function setMicRecording() {
        isRecording = true;
        micBtn.classList.add('is-recording');
        micBtn.innerHTML = '<i class="fas fa-stop"></i>';
        micStatus.textContent = 'Đang ghi âm... nhấn lại để dừng';
    }

    function showRecordedState() {
        setMicIdle();
        micStatus.textContent = 'Đã ghi âm xong — nghe lại bên dưới';
        player.classList.add('is-visible');
        rerecordWrap.style.display = 'block';
    }

    async function startRecording() {
        micError.style.display = 'none';

        try {
            var stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            audioChunks = [];
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = function (e) {
                if (e.data.size > 0) audioChunks.push(e.data);
            };

            mediaRecorder.onstop = function () {
                var blob = new Blob(audioChunks, { type: 'audio/webm' });
                if (recordedBlobUrl) URL.revokeObjectURL(recordedBlobUrl);
                recordedBlobUrl = URL.createObjectURL(blob);
                audioEl.src = recordedBlobUrl;
                stream.getTracks().forEach(function (t) { t.stop(); });

                // Đóng gói Blob thành File thật, gán vào input[type=file] ẩn
                // để form submit thông thường (không cần fetch/XHR thủ công).
                var file = new File([blob], 'speaking-answer.webm', { type: 'audio/webm' });
                var dt = new DataTransfer();
                dt.items.add(file);
                recordingInput.files = dt.files;

                showRecordedState();
            };

            mediaRecorder.start();
            setMicRecording();
        } catch (err) {
            micError.style.display = 'block';
            micStatus.textContent = 'Nhấn để ghi âm';
        }
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
    }

    micBtn.addEventListener('click', function () {
        if (isRecording) {
            stopRecording();
        } else {
            startRecording();
        }
    });

    rerecordLink.addEventListener('click', function () {
        player.classList.remove('is-visible');
        rerecordWrap.style.display = 'none';
        micStatus.textContent = 'Nhấn để ghi âm câu trả lời của bạn';
        audioEl.pause();
        recordingInput.value = '';
        playToggle.innerHTML = '<i class="fas fa-play"></i>';
        timeLabel.textContent = '00:00';
        progressFill.style.width = '0%';
    });

    var playToggle = document.getElementById('ptPlayToggle');
    var timeLabel = document.getElementById('ptTimeLabel');
    var progressTrack = document.getElementById('ptProgressTrack');
    var progressFill = document.getElementById('ptProgressFill');

    function formatTime(sec) {
        sec = isFinite(sec) ? sec : 0;
        var m = String(Math.floor(sec / 60)).padStart(2, '0');
        var s = String(Math.floor(sec % 60)).padStart(2, '0');
        return m + ':' + s;
    }

    playToggle.addEventListener('click', function () {
        if (audioEl.paused) {
            audioEl.play();
            playToggle.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            audioEl.pause();
            playToggle.innerHTML = '<i class="fas fa-play"></i>';
        }
    });

    audioEl.addEventListener('ended', function () {
        playToggle.innerHTML = '<i class="fas fa-play"></i>';
    });

    audioEl.addEventListener('timeupdate', function () {
        timeLabel.textContent = formatTime(audioEl.currentTime);
        var pct = audioEl.duration ? (audioEl.currentTime / audioEl.duration) * 100 : 0;
        progressFill.style.width = pct + '%';
    });

    progressTrack.addEventListener('click', function (e) {
        if (!audioEl.duration) return;
        var rect = progressTrack.getBoundingClientRect();
        var pct = (e.clientX - rect.left) / rect.width;
        audioEl.currentTime = pct * audioEl.duration;
    });

    // Bỏ qua — submit form mà KHÔNG gán file (input.files rỗng), Controller đã
    // xử lý sẵn: nếu không có file thì chỉ đánh dấu completed, không lưu path.
    document.getElementById('ptSkipLink').addEventListener('click', function () {
        recordingInput.value = '';
        document.getElementById('ptSpeakingForm').submit();
    });
})();
</script>
@endsection