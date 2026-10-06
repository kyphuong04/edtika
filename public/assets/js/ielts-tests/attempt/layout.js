/**
 * layout.js — dựng khung UI thi thật, đồng nhất 100% với
 * ielts-tests-manage/preview/layout.js về header/body/navigator/FAB/resizer.
 *
 * Khác biệt DUY NHẤT so với preview (giữ nguyên logic thi thật):
 *  - mount() lấy testTitle từ ATTEMPT_META/test title nếu có, không từ
 *    PREVIEW_TEST_META.
 *  - Timer KHÔNG tự sinh duration local — app.js điều phối bằng
 *    ATTEMPT_INITIAL_REMAINING_SECONDS + scopeStatus poll + speakingStartPart.
 *  - Listening: practice cho phép tua/dừng (controls đầy đủ), mock chặn
 *    tua/tạm dừng — hiện gate "Phát" 1 lần duy nhất rồi auto-play.
 *  - Device check overlay (mic+loa) hiện trước khi vào bài thi.
 */

const ExamLayout = {
    root: null,
    els: {},
    timerHandle: null,
    timerMode: 'up',
    timerValue: 0,
    onTimerZero: null,
    listeningPhase: 'idle',
    listeningGateShown: false,
    listeningGateEl: null,
    deviceOverlayEl: null,
    deviceDone: false,

    isMock() {
        const meta = (typeof window !== 'undefined' && window.ATTEMPT_META) ? window.ATTEMPT_META : {};
        return !!(meta.isMockTest || meta.testType === 'mock');
    },

    mount(rootEl) {
        this.root = rootEl;
        rootEl.innerHTML = '';

        const header = document.createElement('div');
        header.className = 'exam-header';

        const logoWrap = document.createElement('div');
        logoWrap.className = 'exam-header-logo';
        logoWrap.innerHTML = '<span class="exam-header-logo-text">EDTIKA</span>';

        const headerMain = document.createElement('div');
        headerMain.className = 'exam-header-main';

        const testTitle = document.createElement('div');
        testTitle.className = 'exam-header-title';
        const meta = (typeof window !== 'undefined' && window.ATTEMPT_META) ? window.ATTEMPT_META : {};
        const skillForTitle = (AttemptState && AttemptState.skill) || meta.skill || '';
        testTitle.textContent = meta.testTitle || meta.title || (skillForTitle ? skillForTitle.toUpperCase() : '');

        const audioIndicator = document.createElement('div');
        audioIndicator.className = 'exam-audio-indicator hidden';
        audioIndicator.innerHTML = '<i class="fas fa-volume-up"></i><span>Playing sound</span>';

        const skillTabs = document.createElement('div');
        skillTabs.className = 'exam-skill-tabs';

        const timer = document.createElement('div');
        timer.className = 'exam-timer';
        timer.textContent = '00:00';

        headerMain.appendChild(testTitle);
        headerMain.appendChild(audioIndicator);
        headerMain.appendChild(skillTabs);
        headerMain.appendChild(timer);

        header.appendChild(logoWrap);
        header.appendChild(headerMain);

        const body = document.createElement('div');
        body.className = 'exam-body';

        const context = document.createElement('div');
        context.className = 'exam-context';

        const resizer = document.createElement('div');
        resizer.className = 'exam-resizer';

        const questions = document.createElement('div');
        questions.className = 'exam-questions';

        body.appendChild(context);
        body.appendChild(resizer);
        body.appendChild(questions);

        const navigator = document.createElement('div');
        navigator.className = 'exam-navigator';

        const partNavBar = document.createElement('div');
        partNavBar.className = 'exam-part-nav-bar';

        const submitBtn = document.createElement('button');
        submitBtn.type = 'button';
        submitBtn.className = 'exam-submit-check-btn';
        const isWritingOrSpeaking = AttemptState.skill === 'speaking' || AttemptState.skill === 'writing';
        submitBtn.title = isWritingOrSpeaking ? 'Submit' : 'Submit this section';
        submitBtn.innerHTML = '<i class="fas fa-check"></i>';

        const navRow = document.createElement('div');
        navRow.style.display = 'flex';
        navRow.style.alignItems = 'stretch';
        partNavBar.style.flex = '1';

        navRow.appendChild(partNavBar);
        navRow.appendChild(submitBtn);
        navigator.appendChild(navRow);

        const fabNav = document.createElement('div');
        fabNav.className = 'exam-fab-nav';

        const fabPrev = document.createElement('button');
        fabPrev.type = 'button';
        fabPrev.className = 'exam-fab-btn';
        fabPrev.title = 'Previous question';
        fabPrev.innerHTML = '<i class="fas fa-chevron-left"></i>';

        const fabNext = document.createElement('button');
        fabNext.type = 'button';
        fabNext.className = 'exam-fab-btn';
        fabNext.title = 'Next question';
        fabNext.innerHTML = '<i class="fas fa-chevron-right"></i>';

        fabNav.appendChild(fabPrev);
        fabNav.appendChild(fabNext);

        rootEl.appendChild(header);
        rootEl.appendChild(body);
        rootEl.appendChild(navigator);
        rootEl.appendChild(fabNav);

        this.els = {
            header, logoWrap, headerMain, testTitle,
            skillTabs, audioIndicator, timer,
            body, context, resizer, questions,
            navigator, partNavBar, submitBtn,
            fabPrev, fabNext,
        };

        this.els.finishBtn = submitBtn;

        this.listeningPhase = 'idle';
        this._pendingListeningContext = null;
        this.bindResizer();
        this.renderSkillTabs();
    },


    ensureListeningGate() {
        if (this.listeningGateEl) return this.listeningGateEl;
        const overlay = document.createElement('div');
        overlay.className = 'exam-listening-gate-overlay hidden';
        overlay.id = 'examListeningGate';
        overlay.innerHTML = ''
            + '<div class="exam-listening-gate-card">'
            + '  <div class="elg-icon">🎧</div>'
            + '  <div class="elg-text">You will hear a recording in this test. You will not be able to pause or rewind the audio while answering the questions.</div>'
            + '  <div class="elg-hint">Press Play to continue.</div>'
            + '  <button type="button" class="elg-play">▶ Play</button>'
            + '</div>';
        document.body.appendChild(overlay);
        this.listeningGateEl = overlay;
        return overlay;
    },

    showListeningGate(onPlay) {
        const gate = this.ensureListeningGate();
        gate.classList.remove('hidden');

        const btn = gate.querySelector('.elg-play');
        if (btn) {
            btn.onclick = () => {
                this.listeningGateShown = true;
                this.hideListeningGate();
                if (typeof onPlay === 'function') onPlay();
            };
        }
    },

    hideListeningGate() {
        if (this.listeningGateEl) this.listeningGateEl.classList.add('hidden');
    },

        /**
     * Practice Test: chọn thời gian làm bài. Hiện TRƯỚC overlay kiểm tra
     * thiết bị; đồng hồ chỉ bắt đầu sau khi server ghi nhận lựa chọn.
     */
    showDurationOverlay(options, onPick) {
        const overlay = document.createElement('div');
        overlay.className = 'exam-duration-overlay';
        overlay.innerHTML = ''
            + '<div class="exam-duration-card">'
            + '  <div class="exam-duration-title">THIẾT LẬP CẤU TRÚC BÀI THI</div>'
            + '  <div class="exam-duration-sub">Vui lòng chọn thời gian làm bài phù hợp</div>'
            + '  <div class="exam-duration-icon">'
            + '    <div class="exam-duration-icon-circle"><i class="fas fa-clock"></i></div>'
            + '    <div class="exam-duration-icon-label">CHỌN THỜI GIAN LÀM BÀI</div>'
            + '  </div>'
            + '  <select class="exam-duration-select" id="examDurationSelect"></select>'
            + '  <div class="exam-duration-error" id="examDurationError"></div>'
            + '  <button type="button" class="exam-duration-start" id="examDurationStart">'
            + '    BẮT ĐẦU LÀM BÀI NGAY <i class="fas fa-arrow-right"></i>'
            + '  </button>'
            + '</div>';
        document.body.appendChild(overlay);

        const select = overlay.querySelector('#examDurationSelect');
        const startBtn = overlay.querySelector('#examDurationStart');
        const errorEl = overlay.querySelector('#examDurationError');

        (options || []).forEach((opt) => {
            const option = document.createElement('option');
            option.value = opt.value;
            option.textContent = opt.label;
            if (Number(opt.value) === 60) option.selected = true;
            select.appendChild(option);
        });

        startBtn.addEventListener('click', () => {
            startBtn.disabled = true;
            errorEl.textContent = '';

            onPick(parseInt(select.value, 10), {
                close: () => overlay.remove(),
                fail: (msg) => {
                    startBtn.disabled = false;
                    errorEl.textContent = msg;
                },
            });
        });

        return overlay;
    },

    // ── Device check overlay (mic + loa) ──────────────────────────────
    mountDeviceOverlay() {
        if (this.deviceOverlayEl) return this.deviceOverlayEl;
        const overlay = document.createElement('div');
        overlay.className = 'exam-device-overlay';
        overlay.id = 'examDeviceOverlay';
        overlay.innerHTML = ''
            + '<div class="exam-device-card">'
            + '  <div class="exam-device-title">Device check</div>'
            + '  <div class="exam-device-sub">Check your microphone and speakers before starting. You can skip this if you prefer.</div>'
            + '  <button type="button" class="exam-device-mic-btn" id="edMicBtn"><i class="fas fa-microphone"></i></button>'
            + '  <div class="exam-device-status" id="edMicStatus">Tap to record a test clip (play it back to check your speakers)</div>'
            + '  <div class="exam-device-error" id="edMicError">Microphone not available. Allow browser access to your microphone and try again.</div>'
            + '  <div class="exam-device-player" id="edPlayer">'
            + '    <button type="button" id="edSeekBack" title="Back 5s"><i class="fas fa-undo"></i></button>'
            + '    <button type="button" class="ed-play" id="edPlayToggle"><i class="fas fa-play"></i></button>'
            + '    <button type="button" id="edSeekForward" title="Forward 5s"><i class="fas fa-redo"></i></button>'
            + '    <span class="exam-device-time" id="edTimeLabel">00:00</span>'
            + '    <div class="exam-device-track" id="edProgressTrack"><div class="exam-device-fill" id="edProgressFill"></div></div>'
            + '    <button type="button" id="edVolumeBtn"><i class="fas fa-volume-up"></i></button>'
            + '    <div class="exam-device-vol-track" id="edVolumeTrack"><div class="exam-device-vol-fill" id="edVolumeFill"></div></div>'
            + '    <span class="exam-device-speed" id="edSpeedBadge"><i class="fas fa-clock"></i> 1x</span>'
            + '  </div>'
            + '  <span class="exam-device-rerecord" id="edRerecordLink" style="display:none;">Record again</span>'
            + '  <hr class="exam-device-divider">'
            + '  <button type="button" class="exam-device-continue" id="edContinueBtn" disabled>Start the test</button>'
            + '  <button type="button" class="exam-device-skip" id="edSkipBtn">Skip device check</button>'
            + '  <audio id="edAudioPlayback" style="display:none;"></audio>'
            + '</div>';
        document.body.appendChild(overlay);
        this.deviceOverlayEl = overlay;
        return overlay;
    },

    showDeviceOverlay(onDone) {
        const overlay = this.mountDeviceOverlay();
        overlay.classList.remove('hidden');
        this.deviceDone = false;
        this._initDeviceCheckEvents(onDone);
    },

    hideDeviceOverlay() {
        if (this.deviceOverlayEl) this.deviceOverlayEl.classList.add('hidden');
        this.deviceDone = true;
    },

    _initDeviceCheckEvents(onDone) {
        const micBtn = document.getElementById('edMicBtn');
        const micStatus = document.getElementById('edMicStatus');
        const micError = document.getElementById('edMicError');
        const player = document.getElementById('edPlayer');
        const rerecordLink = document.getElementById('edRerecordLink');
        const continueBtn = document.getElementById('edContinueBtn');
        const skipBtn = document.getElementById('edSkipBtn');
        const audioEl = document.getElementById('edAudioPlayback');
        if (!micBtn || micBtn.dataset.bound === '1') return;
        micBtn.dataset.bound = '1';

        let mediaRecorder = null;
        let audioChunks = [];
        let isRecording = false;
        let recordedBlobUrl = null;

        function setIdle() {
            isRecording = false;
            micBtn.classList.remove('is-recording');
            micBtn.innerHTML = '<i class="fas fa-microphone"></i>';
        }
        function setRecording() {
            isRecording = true;
            micBtn.classList.add('is-recording');
            micBtn.innerHTML = '<i class="fas fa-stop"></i>';
            micStatus.textContent = 'Recording... tap again to stop';
        }
        function showRecorded() {
            setIdle();
            micStatus.textContent = 'Recording saved — play it back to check your speakers';
            player.classList.add('is-visible');
            document.getElementById('edRerecordLink').style.display = 'block';
            continueBtn.disabled = false;
            continueBtn.classList.add('is-ready');
        }
        function resetPlayerUI() {
            const pt = document.getElementById('edPlayToggle');
            const tl = document.getElementById('edTimeLabel');
            const pf = document.getElementById('edProgressFill');
            if (pt) pt.innerHTML = '<i class="fas fa-play"></i>';
            if (tl) tl.textContent = '00:00';
            if (pf) pf.style.width = '0%';
        }

        async function startRecording() {
            if (micError) micError.style.display = 'none';
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                audioChunks = [];
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = function(e) { if (e.data.size > 0) audioChunks.push(e.data); };
                mediaRecorder.onstop = function() {
                    const blob = new Blob(audioChunks, { type: 'audio/webm' });
                    if (recordedBlobUrl) URL.revokeObjectURL(recordedBlobUrl);
                    recordedBlobUrl = URL.createObjectURL(blob);
                    audioEl.src = recordedBlobUrl;
                    stream.getTracks().forEach(function(t){ t.stop(); });
                    showRecorded();
                };
                mediaRecorder.start();
                setRecording();
            } catch (err) {
                if (micError) micError.style.display = 'block';
                micStatus.textContent = 'Tap to record';
            }
        }
        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
        }

        micBtn.addEventListener('click', function() {
            if (isRecording) stopRecording(); else startRecording();
        });
        rerecordLink.addEventListener('click', function() {
            player.classList.remove('is-visible');
            rerecordLink.style.display = 'none';
            continueBtn.disabled = true;
            continueBtn.classList.remove('is-ready');
            micStatus.textContent = 'Tap to record a test clip (play it back to check your speakers)';
            audioEl.pause();
            resetPlayerUI();
        });

        // Player controls — giống placement mic_check
        const playToggle = document.getElementById('edPlayToggle');
        const seekBack = document.getElementById('edSeekBack');
        const seekForward = document.getElementById('edSeekForward');
        const timeLabel = document.getElementById('edTimeLabel');
        const progressTrack = document.getElementById('edProgressTrack');
        const progressFill = document.getElementById('edProgressFill');
        const volumeBtn = document.getElementById('edVolumeBtn');
        const volumeTrack = document.getElementById('edVolumeTrack');
        const volumeFill = document.getElementById('edVolumeFill');
        const speedBadge = document.getElementById('edSpeedBadge');
        const speedOptions = [0.75, 1, 1.25, 1.5];
        let speedIndex = 1;
        let isMuted = false;
        let lastVolume = 1;

        function formatTime(sec) {
            sec = isFinite(sec) ? sec : 0;
            const m = String(Math.floor(sec / 60)).padStart(2, '0');
            const s = String(Math.floor(sec % 60)).padStart(2, '0');
            return m + ':' + s;
        }
        if (playToggle) playToggle.addEventListener('click', function() {
            if (audioEl.paused) { audioEl.play(); playToggle.innerHTML = '<i class="fas fa-pause"></i>'; }
            else { audioEl.pause(); playToggle.innerHTML = '<i class="fas fa-play"></i>'; }
        });
        audioEl.addEventListener('ended', function(){ if (playToggle) playToggle.innerHTML = '<i class="fas fa-play"></i>'; });
        audioEl.addEventListener('timeupdate', function() {
            if (timeLabel) timeLabel.textContent = formatTime(audioEl.currentTime);
            const pct = audioEl.duration ? (audioEl.currentTime / audioEl.duration) * 100 : 0;
            if (progressFill) progressFill.style.width = pct + '%';
        });
        if (seekBack) seekBack.addEventListener('click', function(){ audioEl.currentTime = Math.max(0, audioEl.currentTime - 5); });
        if (seekForward) seekForward.addEventListener('click', function(){ audioEl.currentTime = Math.min(audioEl.duration || 0, audioEl.currentTime + 5); });
        if (progressTrack) progressTrack.addEventListener('click', function(e){
            if (!audioEl.duration) return;
            const rect = progressTrack.getBoundingClientRect();
            const pct = (e.clientX - rect.left) / rect.width;
            audioEl.currentTime = pct * audioEl.duration;
        });
        if (volumeBtn) volumeBtn.addEventListener('click', function(){
            isMuted = !isMuted;
            audioEl.volume = isMuted ? 0 : lastVolume;
            if (volumeFill) volumeFill.style.width = (isMuted ? 0 : lastVolume * 100) + '%';
            volumeBtn.innerHTML = isMuted ? '<i class="fas fa-volume-mute"></i>' : '<i class="fas fa-volume-up"></i>';
        });
        if (volumeTrack) volumeTrack.addEventListener('click', function(e){
            const rect = volumeTrack.getBoundingClientRect();
            const pct = Math.min(1, Math.max(0, (e.clientX - rect.left) / rect.width));
            lastVolume = pct;
            isMuted = false;
            audioEl.volume = pct;
            if (volumeFill) volumeFill.style.width = (pct * 100) + '%';
            if (volumeBtn) volumeBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
        });
        if (speedBadge) speedBadge.addEventListener('click', function(){
            speedIndex = (speedIndex + 1) % speedOptions.length;
            const speed = speedOptions[speedIndex];
            audioEl.playbackRate = speed;
            speedBadge.innerHTML = '<i class="fas fa-clock"></i> ' + speed + 'x';
        });

        function done() {
            // Dừng ghi nếu đang ghi dở
            if (isRecording && mediaRecorder && mediaRecorder.state !== 'inactive') {
                try { mediaRecorder.stop(); } catch(e) {}
            }
            audioEl.pause();
            if (typeof onDone === 'function') onDone();
            // Ẩn overlay sau khi callback
            const ov = document.getElementById('examDeviceOverlay');
            if (ov) ov.classList.add('hidden');
        }
        continueBtn.addEventListener('click', done);
        skipBtn.addEventListener('click', done);
    },

    // ── Timer API giữ nguyên để app.js gọi (server-authoritative) ────────
    setTimerMode(mode) {
        this.timerMode = mode; // 'up' | 'down'
    },

    setTimerSeconds(seconds) {
        this.timerValue = Math.max(0, seconds);
        this.renderTimerValue();
    },

    startTimerTick() {
        this.stopTimerTick();
        this.els.timer.classList.remove('is-time-up');

        this.timerHandle = window.setInterval(() => {
            if (this.timerMode === 'down') {
                if (this.timerValue <= 0) {
                    this.stopTimerTick();
                    this.els.timer.classList.add('is-time-up');
                    if (typeof this.onTimerZero === 'function') this.onTimerZero();
                    return;
                }
                this.timerValue--;
                if (this.timerValue <= 0) {
                    this.els.timer.classList.add('is-time-up');
                    if (typeof this.onTimerZero === 'function') this.onTimerZero();
                }
            } else {
                this.timerValue++;
            }
            this.renderTimerValue();
        }, 1000);
    },

    stopTimerTick() {
        if (this.timerHandle) {
            window.clearInterval(this.timerHandle);
            this.timerHandle = null;
        }
    },

    stopTimerInterval() { this.stopTimerTick(); },
    restartTimerInterval() { this.startTimerTick(); },

    renderTimerValue() {
        const total = Math.max(0, this.timerValue);
        const m = String(Math.floor(total / 60)).padStart(2, '0');
        const s = String(total % 60).padStart(2, '0');
        this.els.timer.textContent = m + ':' + s;

        // Dưới 2 phút -> chữ đỏ (chỉ khi đang đếm ngược).
        const warn = this.timerMode === 'down' && total > 0 && total <= 120;
        this.els.timer.classList.toggle('is-warning', warn);
    },

    syncTimerForContext(skill, partIndex, testType) {
        const meta = (typeof window !== 'undefined' && window.ATTEMPT_META) ? window.ATTEMPT_META : {};
        const isMock = !!(meta.isMockTest || testType === 'mock');
        if (skill === 'listening' && isMock) {
            this.listeningPhase = 'idle';
            this.els.timer.classList.add('hidden');
            this.els.timer.classList.remove('is-time-up', 'is-wrapup-warning');
        }
    },

    bindListeningAudio(audioEl) {
        if (!audioEl || audioEl.dataset.listeningBound === '1') return;
        audioEl.dataset.listeningBound = '1';

        const isMockListening = this.isMock() && AttemptState.skill === 'listening';

        // Mock: chặn tua & tạm dừng — practice thì cho phép tự do
        if (isMockListening) {
            // Ngăn seek bằng cách khóa currentTime
            let lastTime = 0;
            audioEl.addEventListener('timeupdate', () => {
                if (!audioEl.seeking) lastTime = audioEl.currentTime;
            });
            audioEl.addEventListener('seeking', () => {
                // Cho phép seek chỉ khi chưa bắt đầu (0) hoặc đã ended; còn lại chặn
                if (Math.abs(audioEl.currentTime - lastTime) > 0.5) {
                    audioEl.currentTime = lastTime;
                }
            });
            audioEl.addEventListener('pause', () => {
                if (!audioEl.ended && !audioEl.dataset.allowPause) {
                    // Tự động phát lại nếu người dùng cố tạm dừng
                    setTimeout(() => { if (!audioEl.ended) audioEl.play().catch(()=>{}); }, 80);
                }
                this.els.audioIndicator.classList.add('hidden');
            });
            audioEl.addEventListener('play', () => {
                this.els.audioIndicator.classList.remove('hidden');
            });
        } else {
            audioEl.addEventListener('play', () => {
                this.els.audioIndicator.classList.remove('hidden');
            });
            audioEl.addEventListener('pause', () => {
                if (!audioEl.ended) this.els.audioIndicator.classList.add('hidden');
            });
        }

        audioEl.addEventListener('ended', () => {
            this.els.audioIndicator.classList.add('hidden');
            if (this.isMock() && AttemptState.skill === 'listening') this.startListeningWrapUp();
        });
    },

    startListeningWrapUp() {
        if (this.listeningPhase === 'wrapup' || this.listeningPhase === 'locked') return;
        this.listeningPhase = 'wrapup';

        this.els.timer.classList.remove('hidden');
        this.els.timer.classList.add('is-wrapup-warning');
        this.timerMode = 'down';
        this.timerValue = 120;
        this.renderWrapUpMessage();

        this.stopTimerTick();
        this.timerHandle = window.setInterval(() => {
            if (this.timerValue <= 0) {
                this.stopTimerTick();
                this.listeningPhase = 'locked';
                this.els.timer.textContent = 'Time is up';
                this.els.timer.classList.remove('is-wrapup-warning');
                this.els.timer.classList.add('is-time-up');
                if (typeof this.onTimerZero === 'function') this.onTimerZero();
                return;
            }
            this.timerValue--;
            this.renderWrapUpMessage();
        }, 1000);
    },

    renderWrapUpMessage() {
        const minutesLeft = this.timerValue > 60 ? 2 : 1;
        this.els.timer.textContent = minutesLeft === 2 ? '2 minutes remaining' : '1 minute remaining';
    },

    lockSkill() {
        this.els.questions.classList.add('is-locked');
        if (!this.els.questions.querySelector('.exam-lock-banner')) {
            const banner = document.createElement('div');
            banner.className = 'exam-lock-banner';
            banner.innerHTML = '<i class="fas fa-lock mr-6"></i> Time is up — answers are locked';
            this.els.questions.prepend(banner);
        }
        this.lockAllInputs();
        if (this.els.submitBtn) this.els.submitBtn.disabled = true;
    },

    unlockSkill() {
        this.els.questions.classList.remove('is-locked');
        const banner = this.els.questions.querySelector('.exam-lock-banner');
        if (banner) banner.remove();
    },

    renderSkillTabs() {
        if (!this.els.skillTabs) return;
        this.els.skillTabs.innerHTML = '';

        const skill = AttemptState.skill;
        if (!skill) return;

        const tab = document.createElement('div');
        tab.className = 'exam-skill-tab active';
        tab.textContent = SKILL_LABELS[skill] || skill.toUpperCase();
        this.els.skillTabs.appendChild(tab);
    },

    renderPartTabs(onSelect) {
        this.renderPartNav(onSelect, onSelect ? (entry) => {
            this.scrollToQuestion(entry.question.id);
        } : null);
    },

    renderPartNav(onSelectPart, onJumpQuestion) {
        const bar = this.els.partNavBar;
        if (!bar) return;
        bar.innerHTML = '';

        AttemptState.parts.forEach((part, partIndex) => {
            const partEntries = AttemptState.entries.filter((e) => e.partIndex === partIndex);
            const isActive = partIndex === AttemptState.part.index;

            const segment = document.createElement('div');
            segment.className = 'exam-part-nav-segment' + (isActive ? ' active' : '');

            const label = document.createElement('div');
            label.className = 'epn-part-label';
            label.textContent = part.title || ('Part ' + (partIndex + 1));
            segment.appendChild(label);

            if (isActive) {
                const numsWrap = document.createElement('div');
                numsWrap.className = 'epn-question-nums';

                partEntries.forEach((entry) => {
                    for (let n = entry.startNumber; n <= entry.endNumber; n++) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'epn-question-num';
                        btn.textContent = n;

                        if (AttemptState.isSlotAnswered(entry, n - entry.startNumber)) btn.classList.add('answered');

                        if (entry.question.id === AttemptState.current.questionId) btn.classList.add('current');

                        btn.addEventListener('click', () => {
                            if (typeof onJumpQuestion === 'function') onJumpQuestion(entry);
                            else if (typeof onSelectPart === 'function') onSelectPart(entry.partIndex);
                        });
                        numsWrap.appendChild(btn);
                    }
                });

                segment.appendChild(numsWrap);
            } else {
                const total = partEntries.reduce((sum, e) => sum + e.slotCount, 0);
                const answered = partEntries.reduce((sum, e) => sum + AttemptState.answeredSlotCount(e), 0);
                const summary = document.createElement('div');
                summary.className = 'epn-part-summary';
                summary.textContent = answered + '/' + total + ' question';
                segment.appendChild(summary);

                segment.addEventListener('click', () => {
                    if (typeof onSelectPart === 'function') onSelectPart(partIndex);
                });
            }

            bar.appendChild(segment);
        });
    },

    renderNavigator(onJump) {
        this.renderPartNav((idx) => {
            if (typeof onJump === 'function') {
                const firstEntry = AttemptState.entries.find((e) => e.partIndex === idx);
                if (firstEntry) onJump(firstEntry);
            }
        }, onJump);
    },

        /** Ô thả đặt trong bài đọc (chỗ giáo viên gõ [[Q]]). */
    createMatchSlot(entry, groupId) {
        const q = entry.question;
        const options = q.options || {};

        const slot = document.createElement('span');
        slot.className = 'exam-match-slot exam-match-slot-inline';
        slot.id = 'exam-q-' + q.id;           // thanh điều hướng cuộn tới đây
        slot.dataset.groupId = groupId;
        slot.dataset.questionId = q.id;
        slot.dataset.number = entry.startNumber;
        slot.__question = q;

        const saved = String(AttemptState.getAnswer(q.id) || '').trim();
        slot.dataset.value = saved;
        slot.dataset.label = saved ? ExamRenderers.matchingLabel(options, saved) : '';
        ExamMatchingDnD.paint(slot);

        return slot;
    },

    /**
     * Thay mỗi [[Q]] trong bài đọc bằng một ô thả, gán lần lượt cho các
     * statement của group Matching (kiểu kéo thả) trong part này.
     */
    mountPassageMatchSlots(container, part) {
        const DROP_TYPES = ['matching_headings', 'matching_features', 'matching_sentence_endings'];

        const queue = [];
        (part.groups || []).forEach((group, idx) => {
            if (!DROP_TYPES.includes(group.question_type)) return;
            const groupId = 'mg-' + (group.id || (AttemptState.part.index + '-' + idx));
            (group.questions || []).forEach((q) => {
                const entry = AttemptState.entries.find((e) => e.question.id === q.id);
                if (entry) queue.push({ entry: entry, groupId: groupId });
            });
        });

        if (!queue.length) return;

        const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null);
        const nodes = [];
        while (walker.nextNode()) {
            if (walker.currentNode.nodeValue.indexOf('[[Q]]') !== -1) nodes.push(walker.currentNode);
        }
        if (!nodes.length) return;

        let pos = 0;
        nodes.forEach((node) => {
            const pieces = node.nodeValue.split('[[Q]]');
            const frag = document.createDocumentFragment();

            pieces.forEach((piece, i) => {
                if (piece) frag.appendChild(document.createTextNode(piece));
                if (i < pieces.length - 1) {
                    const item = queue[pos++];
                    if (item) {
                        frag.appendChild(this.createMatchSlot(item.entry, item.groupId));
                        this._passageSlotIds.add(item.entry.question.id);
                    }
                }
            });

            node.parentNode.replaceChild(frag, node);
        });

        ExamMatchingDnD.ensureBound();
    },

    renderContext(part) {
        const el = this.els.context;
        if (!el) return;
        el.innerHTML = '';
        this._passageSlotIds = new Set();
        if (this.els.body) this.els.body.classList.remove('is-speaking', 'is-listening');

        const partFiles = (part && part.files) || {};
        const sectionAudio = (AttemptState.sectionFiles || {}).audio || null;
        const files = {
            audio: partFiles.audio || sectionAudio,
            image: partFiles.image,
            video: partFiles.video,
        };

        if (AttemptState.skill === 'listening') {
            el.classList.add('hidden');
            if (this.els.resizer) this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            if (this.els.body) this.els.body.classList.add('is-listening');
            this._pendingListeningContext = { part, files };
            return;
        }

        this._pendingListeningContext = null;

        const isSpeaking = AttemptState.skill === 'speaking';

        // Speaking dùng màn hình 2 cột riêng (speaking-stage.css) nằm gọn
        // trong cột câu hỏi -> ẩn cột ngữ cảnh, cho cột câu hỏi full width.
        if (isSpeaking) {
            el.classList.add('hidden');
            if (this.els.resizer) this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            if (this.els.body) this.els.body.classList.add('is-speaking');
            this._pendingSpeakingContext = { part };
            return;
        }

        this._pendingSpeakingContext = null;

        const hasMedia = files.audio || files.image || files.video;
        const hasText = part && (part.instructions || part.passage);

        if (!hasMedia && !hasText) {
            el.classList.add('hidden');
            if (this.els.resizer) this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            return;
        }

        el.classList.remove('hidden');
        if (this.els.resizer) this.els.resizer.classList.remove('hidden');
        this.els.questions.classList.remove('full-width');

        this.appendMediaBlock(el, files);

        if (part.instructions) {
            const h = document.createElement('h4');
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.instructions;
            el.appendChild(div);
        }

        if (part.passage) {
            const h = document.createElement('h4');
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.passage;
            el.appendChild(div);
            this.mountPassageMatchSlots(div, part);

            // Highlight/note chỉ cho phép trong bài đọc.
            if (window.ExamHighlights) window.ExamHighlights.mountRoot(div, part.id);
        }
    },

    bindResizer() {
        if (!this.els.resizer || !this.els.body || !this.els.context) return;
        const MIN_PCT = 20;
        const MAX_PCT = 75;
        let dragging = false;

        const onPointerDown = (e) => {
            if (this.els.context.classList.contains('hidden')) return;
            dragging = true;
            this.els.resizer.classList.add('is-dragging');
            document.body.classList.add('is-resizing-exam');
            e.preventDefault();
        };

        const onPointerMove = (e) => {
            if (!dragging) return;
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const bodyRect = this.els.body.getBoundingClientRect();
            let pct = ((clientX - bodyRect.left) / bodyRect.width) * 100;
            pct = Math.min(MAX_PCT, Math.max(MIN_PCT, pct));
            this.els.context.style.width = pct + '%';
        };

        const onPointerUp = () => {
            if (!dragging) return;
            dragging = false;
            this.els.resizer.classList.remove('is-dragging');
            document.body.classList.remove('is-resizing-exam');
        };

        this.els.resizer.addEventListener('mousedown', onPointerDown);
        document.addEventListener('mousemove', onPointerMove);
        document.addEventListener('mouseup', onPointerUp);
        this.els.resizer.addEventListener('touchstart', onPointerDown, { passive: false });
        document.addEventListener('touchmove', onPointerMove, { passive: false });
        document.addEventListener('touchend', onPointerUp);
    },

    appendMediaBlock(container, files) {
        files = files || {};

        if (files.audio) {
            const audio = document.createElement('audio');
            audio.src = files.audio;
            audio.preload = 'metadata';
            const isMockListening = this.isMock() && AttemptState.skill === 'listening';
            audio.controls = !isMockListening;
            if (isMockListening) {
                audio.controlsList = 'nodownload';
                audio.disableRemotePlayback = true;
            }
            audio.dataset.listeningAudio = '1';
            audio.dataset.srcUrl = files.audio;
            container.appendChild(audio);
        }

        if (files.video) {
            const video = document.createElement('video');
            video.controls = true;
            video.src = files.video;
            container.appendChild(video);
        }

        if (files.image) {
            const img = document.createElement('img');
            img.src = files.image;
            img.alt = '';
            container.appendChild(img);
        }
    },

        /**
     * Gap / Short answer: gộp cả group vào MỘT khối, ô nhập nằm ngay trong
     * câu, số câu đứng cạnh ô. Bỏ viền thẻ và badge "Câu N" của từng câu để
     * thí sinh không phải cuộn qua lại khi nghe.
     */
    renderBlankSheet(group, maxWords) {
        const entries = (group.questions || [])
            .map((q) => AttemptState.entries.find((e) => e.question.id === q.id))
            .filter(Boolean);

        if (!entries.length) return null;

        const cards = entries.map((entry) => ExamRenderers.render(entry));

        // Không câu nào có ô inline -> dùng cách hiển thị thường.
        if (!cards.some((c) => c.querySelector('.exam-blank-input'))) return null;

        const frag = document.createDocumentFragment();

        if (maxWords > 0) {
            const instruction = document.createElement('div');
            instruction.className = 'exam-blank-sheet-instruction';
            instruction.textContent = 'Write NO MORE THAN ' + maxWords + ' WORD'
                + (maxWords > 1 ? 'S' : '') + ' AND/OR A NUMBER for each answer.';
            frag.appendChild(instruction);
        }

        const sheet = document.createElement('div');
        sheet.className = 'exam-blank-sheet';

        if (group.title) {
            const title = document.createElement('div');
            title.className = 'exam-blank-sheet-title';
            title.textContent = group.title;
            sheet.appendChild(title);
        }

        if (group.passage) {
            const intro = document.createElement('div');
            intro.className = 'exam-blank-sheet-intro';
            intro.innerHTML = group.passage;
            sheet.appendChild(intro);
        }

        cards.forEach((card) => {
            if (card.querySelector('.exam-blank-input')) card.classList.add('is-compact');
            sheet.appendChild(card);
        });

        frag.appendChild(sheet);
        return frag;
    },


    renderQuestions(part) {
        const el = this.els.questions;
        const existingAudio = el.querySelector('audio[data-listening-audio="1"]');
        el.innerHTML = '';

        const isListening = AttemptState.skill === 'listening';
        const isMockListening = isListening && this.isMock();

        let sameFileAsBefore = false;

        if (this._pendingListeningContext) {
            const files = this._pendingListeningContext.files;
            const listeningBlock = document.createElement('div');
            listeningBlock.className = 'exam-listening-block' + (isMockListening ? ' is-mock' : '');

            sameFileAsBefore = !!(existingAudio && files.audio && existingAudio.dataset.srcUrl === files.audio);

            if (sameFileAsBefore) {
                listeningBlock.appendChild(existingAudio);
            } else {
                this.appendMediaBlock(listeningBlock, files);
                const aEl = listeningBlock.querySelector('audio');
                if (aEl) this.bindListeningAudio(aEl);
            }

            if (part.instructions) {
                const div = document.createElement('div');
                div.className = 'exam-part-instructions';
                div.innerHTML = part.instructions;
                listeningBlock.appendChild(div);
            }

            el.appendChild(listeningBlock);

            if (isMockListening && !this.listeningGateShown) {
                this.showListeningGate(() => {
                    const a = el.querySelector('audio[data-listening-audio="1"]');
                    if (a) a.play().catch(()=>{});
                });
            } else if (isMockListening && this.listeningGateShown && !sameFileAsBefore) {
                setTimeout(() => {
                    const a = el.querySelector('audio[data-listening-audio="1"]');
                    if (a) a.play().catch(()=>{});
                }, 180);
            }
        }

        if (AttemptState.skill === 'speaking') {
            this.renderSpeakingQuestions(part);
            return;
        }


        const TEXT_INPUT_TYPES = ['sentence_completion', 'summary_completion', 'note_completion', 'table_completion', 'diagram_labeling', 'short_answer'];
        const MATCHING_TYPES = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'];
        const groups = Array.isArray(part.groups) ? part.groups : [];

        groups.forEach((group, gIdx) => {
            const BLANK_SHEET_TYPES = ['sentence_completion', 'summary_completion', 'note_completion', 'short_answer'];
            const useSheet = AttemptState.skill === 'listening' && BLANK_SHEET_TYPES.includes(group.question_type);
            const maxWords = parseInt(group.max_words, 10);
            const showMaxWords = maxWords > 0 && TEXT_INPUT_TYPES.includes(group.question_type);

            if (!useSheet && (group.title || group.passage || showMaxWords)) {
                const groupBox = document.createElement('div');
                groupBox.className = 'exam-part-instructions';
                groupBox.innerHTML = (group.title ? '<strong>' + group.title + '</strong><br>' : '')
                    + (group.passage || '');

                if (showMaxWords) {
                    const limit = document.createElement('div');
                    limit.className = 'exam-max-words';
                    limit.textContent = 'Write NO MORE THAN ' + maxWords + ' WORD' + (maxWords > 1 ? 'S' : '')
                        + ' AND/OR A NUMBER for each answer.';
                    groupBox.appendChild(limit);
                }

                el.appendChild(groupBox);
            }

            const DIAGRAM_TYPES = ['diagram_labeling', 'diagram_label', 'map_labeling'];
            const isDiagram = DIAGRAM_TYPES.includes(group.question_type);
            const groupFiles = group.files || {};
            const diagramImage = groupFiles.image || (part.files || {}).image || null;

            // Diagram: ảnh nằm trong khối 2 cột bên dưới, không in lại ở đây.
            this.appendMediaBlock(el, isDiagram ? { audio: groupFiles.audio, video: groupFiles.video } : groupFiles);

            if (isDiagram) {
                const diagram = ExamRenderers.buildDiagramMatrix(group, diagramImage);
                if (diagram) {
                    el.appendChild(diagram);
                    return; // sang group tiếp theo
                }
                // Đề cũ dạng điền ___ (chưa có danh sách lựa chọn) -> giữ cách hiển thị cũ.
                this.appendMediaBlock(el, { image: diagramImage });
            }

            if (useSheet) {
                const sheet = this.renderBlankSheet(group, maxWords);
                if (sheet) {
                    el.appendChild(sheet);
                    return; // sang group tiếp theo
                }
            }

            // Matching Information / Features: làm bài trên ma trận.
            // const MATRIX_TYPES = ['matching_information', 'matching_features'];
            const MATRIX_TYPES = ['matching_information'];
            const BOARD_TYPES = ['matching_information', 'matching_features'];
            if (AttemptState.skill === 'listening' && BOARD_TYPES.includes(group.question_type)) {
                const boardId = 'mg-' + (group.id || (AttemptState.part.index + '-' + gIdx));
                const board = ExamRenderers.buildMatchingBoard(group, boardId);
                if (board) {
                    el.appendChild(board);
                    ExamMatchingDnD.syncChips(boardId);
                    return; // sang group tiếp theo
                }
            }
            if (MATRIX_TYPES.includes(group.question_type)) {
                (group.questions || []).forEach((q) => {
                    const prompt = q.question_data && q.question_data.prompt;
                    if (prompt) {
                        const promptBox = document.createElement('div');
                        promptBox.className = 'exam-part-instructions';
                        promptBox.innerHTML = prompt;
                        el.appendChild(promptBox);
                    }
                });

                const matrix = ExamRenderers.buildMatchingMatrix(group);
                if (matrix) {
                    el.appendChild(matrix);
                    return; // bỏ qua render từng thẻ, sang group tiếp theo
                }
                // Không có lựa chọn nào -> rơi xuống cách render thường bên dưới
            }

            // Matching Headings / Sentence Endings: kéo thả với kho lựa chọn dùng chung.
            const isMatching = MATCHING_TYPES.includes(group.question_type);
            const matchGroupId = isMatching ? ('mg-' + (group.id || (AttemptState.part.index + '-' + gIdx))) : null;

            if (isMatching) {
                const pool = ExamRenderers.buildMatchingPool(group, matchGroupId);
                if (pool) el.appendChild(pool);
            }

            let lastTitle = '';

            (group.questions || []).forEach((question) => {
                if (this._passageSlotIds && this._passageSlotIds.has(question.id)) return;
                const entry = AttemptState.entries.find((e) => e.question.id === question.id);
                if (!entry) return;

                const questionData = question.question_data || {};
                if (questionData.prompt) {
                    const promptBox = document.createElement('div');
                    promptBox.className = 'exam-part-instructions';
                    promptBox.innerHTML = questionData.prompt;
                    el.appendChild(promptBox);
                }

                const title = String(question.title || '').trim();
                if (title && title !== lastTitle) {
                    const titleEl = document.createElement('div');
                    titleEl.className = 'exam-question-title';
                    titleEl.textContent = title;
                    el.appendChild(titleEl);
                }
                lastTitle = title;

                const cardEl = ExamRenderers.render(entry);
                if (matchGroupId) ExamRenderers.tagMatchingCard(cardEl, matchGroupId);
                el.appendChild(cardEl);
            });

            if (matchGroupId) ExamMatchingDnD.syncChips(matchGroupId);
        });
    },

    /**
     * Speaking: mỗi lần chỉ hiện 1 câu (câu đang làm), các câu trước đã xong
     * thì vẫn cho quay lại xem. Thứ tự do IeltsSpeakingFlow quyết định —
     * xong câu này mới mở câu sau (yêu cầu Part 1 & Part 3).
     */
    renderSpeakingQuestions(part) {
        const el = this.els.questions;
        const flow = window.IeltsSpeakingFlow;

        if (window.IeltsSpeakingStage) window.IeltsSpeakingStage.disposeAll();

        const ctx = this._pendingSpeakingContext;
        if (ctx && ctx.part && (ctx.part.instructions || ctx.part.passage)) {
            const note = document.createElement('div');
            note.className = 'exam-speaking-part-note';
            note.innerHTML = (ctx.part.instructions || '') + (ctx.part.passage || '');
            el.appendChild(note);
        }

        const partEntries = AttemptState.entries.filter((e) => e.partIndex === AttemptState.part.index);
        if (!partEntries.length) return;

        let index = partEntries.findIndex((e) => e.question.id === AttemptState.current.questionId);
        if (index === -1) index = 0;

        // Không cho nhảy vượt quá câu đang được mở.
        if (flow) {
            const maxIndex = flow.firstOpenIndex(partEntries);
            if (index > maxIndex) index = maxIndex;
            AttemptState.current.questionId = partEntries[index].question.id;
        }

        if (partEntries.length > 1) {
            const progress = document.createElement('div');
            progress.className = 'exam-speaking-progress';
            progress.textContent = 'Question ' + (index + 1) + ' / ' + partEntries.length;
            el.appendChild(progress);
        }

        el.appendChild(ExamRenderers.render(partEntries[index]));
    },
    
    scrollToQuestion(questionId) {
        const card = document.getElementById('exam-q-' + questionId);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            card.style.outline = '2px solid #511D99';
            window.setTimeout(() => { card.style.outline = ''; }, 1200);
        }
    },

    lockAllInputs() {
        if (!this.root) return;
        this.root.querySelectorAll(
            'input, textarea, select, button.exam-mic-btn, button.speaking-mic-btn, button.speaking-listen-btn'
        ).forEach((el) => {
            el.disabled = true;
        });
    },
};
