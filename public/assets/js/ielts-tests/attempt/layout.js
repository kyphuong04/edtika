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

        const headerRow1 = document.createElement('div');
        headerRow1.className = 'exam-header-row1';

        const testTitle = document.createElement('div');
        testTitle.className = 'exam-header-title';
        const meta = (typeof window !== 'undefined' && window.ATTEMPT_META) ? window.ATTEMPT_META : {};
        const skillForTitle = (AttemptState && AttemptState.skill) || meta.skill || '';
        testTitle.textContent = meta.testTitle || meta.title || (skillForTitle ? skillForTitle.toUpperCase() : '');

        headerRow1.appendChild(testTitle);

        const headerRow2 = document.createElement('div');
        headerRow2.className = 'exam-header-row2';

        const audioIndicator = document.createElement('div');
        audioIndicator.className = 'exam-audio-indicator hidden';
        audioIndicator.innerHTML = '<i class="fas fa-volume-up"></i><span>Playing sound</span>';

        const headerRow2Right = document.createElement('div');
        headerRow2Right.className = 'exam-header-row2-right';

        const skillTabs = document.createElement('div');
        skillTabs.className = 'exam-skill-tabs';

        const timer = document.createElement('div');
        timer.className = 'exam-timer';
        timer.textContent = '00:00';

        headerRow2Right.appendChild(skillTabs);
        headerRow2Right.appendChild(timer);

        headerRow2.appendChild(audioIndicator);
        headerRow2.appendChild(headerRow2Right);

        headerMain.appendChild(headerRow1);
        headerMain.appendChild(headerRow2);

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
        submitBtn.title = isWritingOrSpeaking ? 'Nộp bài' : 'Nộp phần thi này';
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
        fabPrev.title = 'Câu trước';
        fabPrev.innerHTML = '<i class="fas fa-chevron-left"></i>';

        const fabNext = document.createElement('button');
        fabNext.type = 'button';
        fabNext.className = 'exam-fab-btn';
        fabNext.title = 'Câu tiếp theo';
        fabNext.innerHTML = '<i class="fas fa-chevron-right"></i>';

        fabNav.appendChild(fabPrev);
        fabNav.appendChild(fabNext);

        rootEl.appendChild(header);
        rootEl.appendChild(body);
        rootEl.appendChild(navigator);
        rootEl.appendChild(fabNav);

        this.els = {
            header, logoWrap, headerMain, headerRow1, headerRow2, testTitle,
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

    // ── Listening mock gate ──────────────────────────────────────────
    // ensureListeningGate() {
    //     if (this.listeningGateEl) return this.listeningGateEl;
    //     const gate = document.createElement('div');
    //     gate.className = 'exam-listening-gate hidden';
    //     gate.id = 'examListeningGate';
    //     gate.innerHTML = ''
    //         + '<div class="elg-icon">🎧</div>'
    //         + '<div class="elg-text">Bạn sẽ nghe một đoạn âm thanh trong bài kiểm tra này. Bạn sẽ không được phép tạm dừng hoặc tua lại âm thanh trong khi trả lời câu hỏi.</div>'
    //         + '<div class="elg-hint">Để tiếp tục, hãy nhấn Phát.</div>'
    //         + '<button type="button" class="elg-play">▶ Phát (Play)</button>';
    //     this.els.questions.appendChild(gate);
    //     this.listeningGateEl = gate;
    //     return gate;
    // },

    // showListeningGate(onPlay) {
    //     const gate = this.ensureListeningGate();
    //     gate.classList.remove('hidden');
    //     const block = this.els.questions.querySelector('.exam-listening-block');
    //     if (block) block.style.display = 'none';
    //     this.els.questions.querySelectorAll('.exam-question-card').forEach(el => { el.style.display = 'none'; });

    //     const btn = gate.querySelector('.elg-play');
    //     if (btn) {
    //         btn.onclick = () => {
    //             this.listeningGateShown = true;
    //             this.hideListeningGate();
    //             if (typeof onPlay === 'function') onPlay();
    //         };
    //     }
    // },

    // hideListeningGate() {
    //     if (this.listeningGateEl) this.listeningGateEl.classList.add('hidden');
    //     const block = this.els.questions.querySelector('.exam-listening-block');
    //     if (block) block.style.display = '';
    //     this.els.questions.querySelectorAll('.exam-question-card').forEach(el => { el.style.display = ''; });
    // },

    ensureListeningGate() {
        if (this.listeningGateEl) return this.listeningGateEl;
        const overlay = document.createElement('div');
        overlay.className = 'exam-listening-gate-overlay hidden';
        overlay.id = 'examListeningGate';
        overlay.innerHTML = ''
            + '<div class="exam-listening-gate-card">'
            + '  <div class="elg-icon">🎧</div>'
            + '  <div class="elg-text">Bạn sẽ nghe một đoạn âm thanh trong bài kiểm tra này. Bạn sẽ không được phép tạm dừng hoặc tua lại âm thanh trong khi trả lời câu hỏi.</div>'
            + '  <div class="elg-hint">Để tiếp tục, hãy nhấn Phát.</div>'
            + '  <button type="button" class="elg-play">▶ Phát (Play)</button>'
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

    // ── Device check overlay (mic + loa) ──────────────────────────────
    mountDeviceOverlay() {
        if (this.deviceOverlayEl) return this.deviceOverlayEl;
        const overlay = document.createElement('div');
        overlay.className = 'exam-device-overlay';
        overlay.id = 'examDeviceOverlay';
        overlay.innerHTML = ''
            + '<div class="exam-device-card">'
            + '  <div class="exam-device-title">Kiểm tra thiết bị</div>'
            + '  <div class="exam-device-sub">Kiểm tra microphone và loa trước khi vào thi. Bạn có thể bỏ qua nếu không cần.</div>'
            + '  <button type="button" class="exam-device-mic-btn" id="edMicBtn"><i class="fas fa-microphone"></i></button>'
            + '  <div class="exam-device-status" id="edMicStatus">Nhấn để ghi âm thử (giữ loa để nghe lại)</div>'
            + '  <div class="exam-device-error" id="edMicError">Không truy cập được microphone. Hãy cho phép trình duyệt sử dụng mic rồi thử lại.</div>'
            + '  <div class="exam-device-player" id="edPlayer">'
            + '    <button type="button" id="edSeekBack" title="Lùi 5s"><i class="fas fa-undo"></i></button>'
            + '    <button type="button" class="ed-play" id="edPlayToggle"><i class="fas fa-play"></i></button>'
            + '    <button type="button" id="edSeekForward" title="Tới 5s"><i class="fas fa-redo"></i></button>'
            + '    <span class="exam-device-time" id="edTimeLabel">00:00</span>'
            + '    <div class="exam-device-track" id="edProgressTrack"><div class="exam-device-fill" id="edProgressFill"></div></div>'
            + '    <button type="button" id="edVolumeBtn"><i class="fas fa-volume-up"></i></button>'
            + '    <div class="exam-device-vol-track" id="edVolumeTrack"><div class="exam-device-vol-fill" id="edVolumeFill"></div></div>'
            + '    <span class="exam-device-speed" id="edSpeedBadge"><i class="fas fa-clock"></i> 1x</span>'
            + '  </div>'
            + '  <span class="exam-device-rerecord" id="edRerecordLink" style="display:none;">Ghi âm lại</span>'
            + '  <hr class="exam-device-divider">'
            + '  <button type="button" class="exam-device-continue" id="edContinueBtn" disabled>Tiếp tục vào thi</button>'
            + '  <button type="button" class="exam-device-skip" id="edSkipBtn">Bỏ qua kiểm tra</button>'
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
            micStatus.textContent = 'Đang ghi âm... nhấn lại để dừng';
        }
        function showRecorded() {
            setIdle();
            micStatus.textContent = 'Đã ghi âm xong — nghe lại bên dưới để kiểm tra loa';
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
                micStatus.textContent = 'Nhấn để ghi âm';
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
            micStatus.textContent = 'Nhấn để ghi âm thử (giữ loa để nghe lại)';
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
                this.els.timer.textContent = 'Đã hết giờ';
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
            banner.innerHTML = '<i class="fas fa-lock mr-6"></i> Đã hết giờ — không thể thay đổi đáp án';
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

                        if (AttemptState.isAnswered(entry.question.id)) btn.classList.add('answered');

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
                const answered = partEntries.reduce((sum, e) => sum + (AttemptState.isAnswered(e.question.id) ? e.slotCount : 0), 0);

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

    renderContext(part) {
        const el = this.els.context;
        if (!el) return;
        el.innerHTML = '';
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

        const groups = Array.isArray(part.groups) ? part.groups : [];

        groups.forEach((group) => {
            if (group.title || group.passage) {
                const groupBox = document.createElement('div');
                groupBox.className = 'exam-part-instructions';
                groupBox.innerHTML = (group.title ? '<strong>' + group.title + '</strong><br>' : '')
                    + (group.passage || '');
                el.appendChild(groupBox);
            }

            this.appendMediaBlock(el, group.files || {});

            (group.questions || []).forEach((question) => {
                const entry = AttemptState.entries.find((e) => e.question.id === question.id);
                if (!entry) return;
                el.appendChild(ExamRenderers.render(entry));
            });
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
