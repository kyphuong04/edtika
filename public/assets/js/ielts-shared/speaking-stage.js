/**
 * speaking-stage.js — giao diện Speaking 2 cột, hiện 1 câu mỗi lần.
 *
 * DÙNG CHUNG cho cả 2 trang:
 *  - Preview của giáo viên (ielts-tests-manage/preview)
 *  - Bài thi thật của học viên (ielts-tests/attempt)
 * Mỗi trang chỉ cần truyền vào một "adapter" (xem JSDoc của render()).
 * Sửa ở đây là sửa cho cả hai — đừng copy logic sang renderers.js.
 */
(function (window, document) {
    'use strict';

    /* ── Thời gian từng part (giây) ───────────────────────────────────────
       P1: 45s trả lời. P2: 60s chuẩn bị + 120s nói. P3: 75s trả lời. */
    var TIMING = {
        1: { prepare: 0, answer: 45 },
        2: { prepare: 60, answer: 120 },
        3: { prepare: 0, answer: 75 }
    };
    var DEFAULT_TIMING = { prepare: 0, answer: 45 };

    var RING_RADIUS = 32;
    var RING_CIRCUMFERENCE = 2 * Math.PI * RING_RADIUS;

    function timingFor(partNumber) {
        var n = parseInt(partNumber, 10);
        return TIMING[n] ? TIMING[n] : DEFAULT_TIMING;
    }

    function el(tag, className, text) {
        var node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined && text !== null) node.textContent = text;
        return node;
    }

    function icon(classes) {
        var i = document.createElement('i');
        i.className = classes;
        return i;
    }

    function fmtTime(seconds) {
        var s = Math.max(0, Math.round(seconds || 0));
        var m = Math.floor(s / 60);
        var r = s % 60;
        return (m < 10 ? '0' + m : m) + ':' + (r < 10 ? '0' + r : r);
    }

    /** Bỏ thẻ HTML để lấy text thuần (dùng cho TTS đọc câu hỏi). */
    function stripHtml(html) {
        if (!html) return '';
        var tmp = document.createElement('div');
        tmp.innerHTML = html;
        return (tmp.textContent || '').replace(/\s+/g, ' ').trim();
    }

    /** Nội dung từ DB có thể là HTML hoặc plain text nhiều dòng. */
    function fillRichText(node, value) {
        var raw = (value === undefined || value === null) ? '' : String(value);
        if (!raw.trim()) return false;
        if (/<[a-z][\s\S]*>/i.test(raw)) {
            node.innerHTML = raw;
        } else {
            raw.split(/\n{2,}/).forEach(function (block) {
                var p = el('p');
                block.split(/\n/).forEach(function (line, i) {
                    if (i > 0) p.appendChild(document.createElement('br'));
                    p.appendChild(document.createTextNode(line));
                });
                node.appendChild(p);
            });
        }
        return true;
    }

    /* ── Điều phối tuần tự: xong câu này mới mở câu tiếp theo ──────────── */
    var IeltsSpeakingFlow = {
        done: {},

        reset: function () { this.done = {}; },

        markDone: function (questionId) {
            if (questionId === undefined || questionId === null) return;
            this.done[String(questionId)] = true;
        },

        isDone: function (questionId) {
            return !!this.done[String(questionId)];
        },

        /** Nạp sẵn các câu đã có bản thu (quay lại bài đang làm dở). */
        hydrate: function (entries, hasAnswerFn) {
            var self = this;
            (entries || []).forEach(function (entry) {
                var q = entry && entry.question;
                if (q && hasAnswerFn(q.id)) self.markDone(q.id);
            });
        },

        /** Câu thứ `index` của part chỉ mở khi mọi câu trước đó đã xong. */
        isUnlocked: function (partEntries, index) {
            if (index <= 0) return true;
            for (var i = 0; i < index; i++) {
                var q = partEntries[i] && partEntries[i].question;
                if (!q || !this.isDone(q.id)) return false;
            }
            return true;
        },

        /** Câu đầu tiên chưa xong — vị trí học viên nên đứng khi vào part. */
        firstOpenIndex: function (partEntries) {
            var list = partEntries || [];
            for (var i = 0; i < list.length; i++) {
                var q = list[i] && list[i].question;
                if (!q || !this.isDone(q.id)) return i;
            }
            return Math.max(0, list.length - 1);
        },

        /** Số câu được phép hiện (đã xong + 1 câu đang làm). */
        visibleCount: function (partEntries) {
            return this.firstOpenIndex(partEntries) + 1;
        }
    };

    function humanDuration(sec) {
        var s = parseInt(sec, 10) || 0;
        if (s >= 60 && s % 60 === 0) {
            var m = s / 60;
            return m + (m === 1 ? ' minute' : ' minutes');
        }
        return s + ' seconds';
    }

    function answerHint(t) {
        if (t.prepare > 0) {
            return 'You will have ' + humanDuration(t.prepare) + ' to prepare and ' +
                humanDuration(t.answer) + ' to speak.';
        }
        return 'You will have ' + humanDuration(t.answer) + ' to respond.';
    }

    function svgRing() {
        var NS = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(NS, 'svg');
        svg.setAttribute('class', 'speaking-mic-ring');
        svg.setAttribute('viewBox', '0 0 68 68');
        var bg = document.createElementNS(NS, 'circle');
        bg.setAttribute('class', 'speaking-mic-ring-bg');
        bg.setAttribute('cx', '34');
        bg.setAttribute('cy', '34');
        bg.setAttribute('r', String(RING_RADIUS));
        var pg = document.createElementNS(NS, 'circle');
        pg.setAttribute('class', 'speaking-mic-ring-progress');
        pg.setAttribute('cx', '34');
        pg.setAttribute('cy', '34');
        pg.setAttribute('r', String(RING_RADIUS));
        pg.setAttribute('stroke-dasharray', String(RING_CIRCUMFERENCE));
        pg.setAttribute('stroke-dashoffset', String(RING_CIRCUMFERENCE));
        svg.appendChild(bg);
        svg.appendChild(pg);
        return { svg: svg, progress: pg };
    }

    /** ratio 0..1 phần vòng tròn đã chạy. */
    function setRing(inst, ratio) {
        if (!inst.els.ring) return;
        var r = Math.min(1, Math.max(0, ratio || 0));
        inst.els.ring.setAttribute('stroke-dashoffset', String(RING_CIRCUMFERENCE * (1 - r)));
    }

    /* ── Cột trái: tiêu đề, nghe câu hỏi, đếm giờ + mic, Show question ──── */
    function buildLeft(inst, left) {
        var q = inst.q, cfg = inst.cfg, t = inst.timing;

        left.appendChild(el('h3', 'speaking-stage-title', cfg.questionLabel || 'Question'));
        if (cfg.stepLabel) left.appendChild(el('div', 'speaking-stage-step', cfg.stepLabel));

        var controls = el('div', 'speaking-controls');

        var listenCol = el('div', 'speaking-control');
        var listenBtn = el('button', 'speaking-listen-btn');
        listenBtn.type = 'button';
        listenBtn.setAttribute('aria-label', 'Listen to the question');
        listenBtn.appendChild(icon('fas fa-volume-up'));
        listenCol.appendChild(listenBtn);
        listenCol.appendChild(el('div', 'speaking-control-caption', 'Click to listen to the question'));
        controls.appendChild(listenCol);
        inst.els.listenBtn = listenBtn;
        listenBtn.addEventListener('click', function () { toggleQuestionAudio(inst); });

        var micCol = el('div', 'speaking-control');
        micCol.appendChild(el('span', 'speaking-timer-label', t.prepare > 0 ? 'Preparation' : 'Recording time'));
        var timer = el('div', 'speaking-timer', fmtTime(t.prepare > 0 ? t.prepare : t.answer));
        micCol.appendChild(timer);

        var micWrap = el('div', 'speaking-mic-wrap');
        var ring = svgRing();
        micWrap.appendChild(ring.svg);
        var micBtn = el('button', 'speaking-mic-btn');
        micBtn.type = 'button';
        micBtn.setAttribute('aria-label', 'Start recording');
        micBtn.appendChild(icon('fas fa-microphone'));
        micWrap.appendChild(micBtn);
        micCol.appendChild(micWrap);

        micCol.appendChild(el('div', 'speaking-control-caption', 'Click the microphone to start recording'));
        micCol.appendChild(el('div', 'speaking-control-sub', answerHint(t)));
        var micError = el('div', 'speaking-mic-error');
        micError.style.display = 'none';
        micCol.appendChild(micError);
        controls.appendChild(micCol);
        left.appendChild(controls);

        inst.els.timer = timer;
        inst.els.timerLabel = micCol.querySelector('.speaking-timer-label');
        inst.els.micWrap = micWrap;
        inst.els.micBtn = micBtn;
        inst.els.micIcon = micBtn.querySelector('i');
        inst.els.micError = micError;
        inst.els.ring = ring.progress;
        micBtn.addEventListener('click', function () { onMicClick(inst); });

        buildReveal(inst, left);
        buildPlayback(inst, left);
        buildFooter(inst, left);
    }

    /** "Show question" — ẩn/hiện nội dung câu hỏi. */
    function buildReveal(inst, left) {
        var q = inst.q;
        var wrap = el('div', 'speaking-reveal');
        var btn = el('button', 'speaking-reveal-btn');
        btn.type = 'button';
        btn.appendChild(icon('fas fa-eye'));
        btn.appendChild(document.createTextNode('Show question'));
        wrap.appendChild(btn);

        var body = el('div', 'speaking-reveal-body');
        var text = el('div');
        if (!fillRichText(text, q.text)) {
            text.appendChild(el('em', null, 'This question has no text content.'));
        }
        body.appendChild(text);
        if (q.instruction) {
            var ins = el('div');
            ins.style.marginTop = '10px';
            ins.style.fontWeight = '500';
            fillRichText(ins, q.instruction);
            body.appendChild(ins);
        }
        var hide = el('span', 'speaking-reveal-hide', 'Hide question');
        body.appendChild(hide);
        wrap.appendChild(body);
        left.appendChild(wrap);

        btn.addEventListener('click', function () { wrap.classList.add('is-open'); });
        hide.addEventListener('click', function () { wrap.classList.remove('is-open'); });
        inst.els.reveal = wrap;
    }

    /** Player nghe lại bản thu của học viên. */
    function buildPlayback(inst, left) {
        var wrap = el('div', 'speaking-playback');
        var toggle = el('button', 'speaking-playback-toggle');
        toggle.type = 'button';
        toggle.appendChild(icon('fas fa-play'));
        var time = el('span', 'speaking-playback-time', '00:00 / 00:00');
        var track = el('div', 'speaking-playback-track');
        var fill = el('div', 'speaking-playback-fill');
        track.appendChild(fill);
        wrap.appendChild(toggle);
        wrap.appendChild(track);
        wrap.appendChild(time);

        var audio = document.createElement('audio');
        audio.preload = 'metadata';
        audio.style.display = 'none';
        wrap.appendChild(audio);
        left.appendChild(wrap);

        var rerecord = el('span', 'speaking-rerecord', 'Record again');
        left.appendChild(rerecord);

        toggle.addEventListener('click', function () {
            if (audio.paused) { audio.play().catch(function () {}); } else { audio.pause(); }
        });
        audio.addEventListener('play', function () {
            toggle.firstChild.className = 'fas fa-pause';
        });
        audio.addEventListener('pause', function () {
            toggle.firstChild.className = 'fas fa-play';
        });
        audio.addEventListener('timeupdate', function () {
            var dur = isFinite(audio.duration) ? audio.duration : 0;
            fill.style.width = dur ? ((audio.currentTime / dur) * 100) + '%' : '0%';
            time.textContent = fmtTime(audio.currentTime) + ' / ' + fmtTime(dur);
        });
        audio.addEventListener('loadedmetadata', function () {
            time.textContent = '00:00 / ' + fmtTime(isFinite(audio.duration) ? audio.duration : 0);
        });
        track.addEventListener('click', function (ev) {
            var dur = isFinite(audio.duration) ? audio.duration : 0;
            if (!dur) return;
            var rect = track.getBoundingClientRect();
            audio.currentTime = dur * Math.min(1, Math.max(0, (ev.clientX - rect.left) / rect.width));
        });
        rerecord.addEventListener('click', function () { resetForRerecord(inst); });

        inst.els.playback = wrap;
        inst.els.playbackAudio = audio;
        inst.els.rerecord = rerecord;
    }

    /** Dòng trạng thái + nút sang câu kế tiếp. */
    function buildFooter(inst, left) {
        var status = el('div', 'speaking-status');
        left.appendChild(status);
        inst.els.status = status;

        var next = el('button', 'speaking-next');
        next.type = 'button';
        next.appendChild(document.createTextNode('Next question'));
        next.appendChild(icon('fas fa-chevron-right'));
        left.appendChild(next);
        next.addEventListener('click', function () {
            if (typeof inst.cfg.onNext === 'function') inst.cfg.onNext(inst.q.id);
        });
        inst.els.next = next;
    }

    function setStatus(inst, message, isError) {
        if (!inst.els.status) return;
        inst.els.status.textContent = message || '';
        inst.els.status.classList.toggle('is-error', !!isError);
    }

    function showNextButton(inst) {
        if (!inst.els.next) return;
        if (inst.cfg.hasNext && typeof inst.cfg.onNext === 'function') {
            inst.els.next.classList.add('is-visible');
        }
    }

    /* ── Cột phải: Note / Hint / Model Answer ─────────────────────────────
       Mock test: Part 1 & 3 bỏ khung note, Hint + Model Answer đẩy lên trên. */
    function buildRight(inst, right) {
        var cfg = inst.cfg;
        var partNumber = parseInt(cfg.partNumber, 10) || 1;
        var showNote = !(cfg.isMock && partNumber !== 2);

        if (showNote) {
            right.appendChild(buildNote(inst));
        } else {
            right.classList.add('has-no-note');
        }

        right.appendChild(buildHint(inst));
        right.appendChild(buildModelAnswer(inst));
    }

    function buildNote(inst) {
        var q = inst.q, cfg = inst.cfg;
        var wrap = el('div', 'speaking-note');
        wrap.appendChild(el('div', 'speaking-panel-title', 'Note'));
        var ta = document.createElement('textarea');
        ta.placeholder = 'Write your notes here...';
        ta.setAttribute('aria-label', 'Notes');
        var key = noteKey(cfg, q.id);
        try { ta.value = window.localStorage.getItem(key) || ''; } catch (e) { /* ignore */ }
        ta.addEventListener('input', function () {
            try { window.localStorage.setItem(key, ta.value); } catch (e) { /* ignore */ }
        });
        wrap.appendChild(ta);
        inst.els.note = ta;
        return wrap;
    }

    function noteKey(cfg, questionId) {
        return 'ielts-speaking-note:' + (cfg.storageKey || 'default') + ':' + questionId;
    }

    /** Khung accordion dùng chung cho Hint và Model Answer. */
    function buildAccordion(label) {
        var wrap = el('div', 'speaking-accordion');
        var head = el('button', 'speaking-accordion-head');
        head.type = 'button';
        head.appendChild(el('span', 'speaking-accordion-label', label));
        var eye = icon('fas fa-eye speaking-accordion-icon');
        head.appendChild(eye);
        wrap.appendChild(head);
        wrap.appendChild(el('div', 'speaking-accordion-rule'));
        var body = el('div', 'speaking-accordion-body');
        wrap.appendChild(body);

        head.addEventListener('click', function () {
            if (wrap.classList.contains('is-locked')) return;
            var open = wrap.classList.toggle('is-open');
            eye.className = (open ? 'fas fa-eye-slash' : 'fas fa-eye') + ' speaking-accordion-icon';
        });

        return { wrap: wrap, head: head, body: body, eye: eye };
    }

    function lockedBox(text) {
        var box = el('div', 'speaking-accordion-locked');
        box.appendChild(icon('fas fa-lock'));
        box.appendChild(document.createTextNode(text));
        return box;
    }

    function buildHint(inst) {
        var acc = buildAccordion('Hint');
        if (!fillRichText(acc.body, inst.q.hint)) {
            acc.body.appendChild(el('div', 'speaking-accordion-empty', 'No hint for this question.'));
        }
        inst.els.hint = acc;
        return acc.wrap;
    }

    /** Mock test: Model Answer chỉ mở sau khi đã trả lời xong câu đó. */
    function buildModelAnswer(inst) {
        var acc = buildAccordion('Model Answer');
        inst.els.model = acc;
        inst.modelFetched = false;

        if (inst.cfg.isMock && !IeltsSpeakingFlow.isDone(inst.q.id)) {
            acc.wrap.classList.add('is-locked', 'is-open');
            acc.head.disabled = true;
            acc.eye.className = 'fas fa-lock speaking-accordion-icon';
            acc.body.appendChild(lockedBox('Submit your answer to see this'));
        } else {
            fillModelAnswer(inst);
        }
        return acc.wrap;
    }

    function fillModelAnswer(inst) {
        var acc = inst.els.model;
        if (!acc) return;
        acc.body.innerHTML = '';

        if (fillRichText(acc.body, inst.q.model_answer)) return;

        if (typeof inst.cfg.fetchModelAnswer === 'function' && !inst.modelFetched) {
            inst.modelFetched = true;
            acc.body.appendChild(el('div', 'speaking-accordion-loading', 'Loading model answer...'));
            Promise.resolve(inst.cfg.fetchModelAnswer(inst.q.id)).then(function (text) {
                acc.body.innerHTML = '';
                if (!fillRichText(acc.body, text)) {
                    acc.body.appendChild(el('div', 'speaking-accordion-empty', 'No model answer for this question.'));
                }
            }).catch(function () {
                acc.body.innerHTML = '';
                acc.body.appendChild(el('div', 'speaking-accordion-empty', 'Could not load the model answer.'));
            });
            return;
        }

        acc.body.appendChild(el('div', 'speaking-accordion-empty', 'No model answer for this question.'));
    }

    function unlockModelAnswer(inst) {
        var acc = inst.els.model;
        if (!acc || !acc.wrap.classList.contains('is-locked')) return;
        acc.wrap.classList.remove('is-locked', 'is-open');
        acc.head.disabled = false;
        acc.eye.className = 'fas fa-eye speaking-accordion-icon';
        fillModelAnswer(inst);
    }

    /* ── Nghe câu hỏi: dùng file thu sẵn, không có thì đọc bằng TTS ─────── */
    function setListenPlaying(inst, playing) {
        var btn = inst.els.listenBtn;
        if (!btn) return;
        btn.classList.toggle('is-playing', !!playing);
        var i = btn.querySelector('i');
        if (i) i.className = playing ? 'fas fa-pause' : 'fas fa-volume-up';
    }

    function toggleQuestionAudio(inst) {
        var url = inst.q.question_audio || inst.q.questionAudio || null;
        if (!url) { speakQuestion(inst); return; }

        if (!inst.questionAudio) {
            var audio = new Audio(url);
            audio.addEventListener('play', function () { setListenPlaying(inst, true); });
            audio.addEventListener('pause', function () { setListenPlaying(inst, false); });
            audio.addEventListener('ended', function () { setListenPlaying(inst, false); });
            audio.addEventListener('error', function () {
                setListenPlaying(inst, false);
                inst.questionAudio = null;
                inst.q.question_audio = null;
                speakQuestion(inst);
            });
            inst.questionAudio = audio;
        }
        if (inst.questionAudio.paused) {
            inst.questionAudio.play().catch(function () { speakQuestion(inst); });
        } else {
            inst.questionAudio.pause();
        }
    }

    function speakQuestion(inst) {
        var synth = window.speechSynthesis;
        if (!synth || typeof window.SpeechSynthesisUtterance !== 'function') {
            setStatus(inst, 'This question has no audio available.', true);
            return;
        }
        if (synth.speaking || synth.pending) {
            synth.cancel();
            setListenPlaying(inst, false);
            return;
        }
        var text = stripHtml(inst.q.text) || stripHtml(inst.q.instruction);
        if (!text) {
            setStatus(inst, 'This question has no audio available.', true);
            return;
        }
        var u = new window.SpeechSynthesisUtterance(text);
        u.lang = 'en-US';
        u.rate = 0.95;
        u.onend = function () { setListenPlaying(inst, false); };
        u.onerror = function () { setListenPlaying(inst, false); };
        setListenPlaying(inst, true);
        synth.speak(u);
    }

    function stopQuestionAudio(inst) {
        if (inst.questionAudio && !inst.questionAudio.paused) inst.questionAudio.pause();
        if (window.speechSynthesis && (window.speechSynthesis.speaking || window.speechSynthesis.pending)) {
            window.speechSynthesis.cancel();
        }
        setListenPlaying(inst, false);
    }

    /* ── Đồng hồ: giai đoạn chuẩn bị (P2) rồi giai đoạn nói ─────────────── */
    function clearTick(inst) {
        if (inst.tickId) { window.clearInterval(inst.tickId); inst.tickId = null; }
    }

    function paintTimer(inst, label, className) {
        if (inst.els.timer) {
            inst.els.timer.textContent = fmtTime(inst.remaining);
            inst.els.timer.className = 'speaking-timer' + (className ? ' ' + className : '');
        }
        if (inst.els.timerLabel && label) inst.els.timerLabel.textContent = label;
    }

    function startPrepare(inst) {
        if (inst.timing.prepare <= 0) return;
        clearTick(inst);
        inst.phase = 'prepare';
        inst.remaining = inst.timing.prepare;
        paintTimer(inst, 'Preparation', 'is-prepare');
        setRing(inst, 0);
        setStatus(inst, 'Preparation time — make notes, then press the microphone when you are ready.');
        inst.tickId = window.setInterval(function () {
            inst.remaining -= 1;
            setRing(inst, 1 - (inst.remaining / inst.timing.prepare));
            paintTimer(inst, 'Preparation', inst.remaining <= 10 ? 'is-prepare is-low' : 'is-prepare');
            if (inst.remaining <= 0) {
                clearTick(inst);
                setRing(inst, 0);
                if (inst.cfg.isMock) {
                    startRecording(inst);
                } else {
                    armAnswerPhase(inst, 'Preparation finished — press the microphone to start speaking.');
                }
            }
        }, 1000);
    }

    function armAnswerPhase(inst, message) {
        clearTick(inst);
        inst.phase = 'idle';
        inst.remaining = inst.timing.answer;
        paintTimer(inst, 'Recording time', '');
        setRing(inst, 0);
        if (message !== undefined) setStatus(inst, message);

        if (inst.cfg.isMock && !inst.cfg.locked) startMockAnswerWindow(inst);
    }

    /**
     * Mock test: đồng hồ trả lời chạy ngay từ lúc câu hiện ra (thi thật không
     * chờ thí sinh bấm). Hết giờ mà chưa thu là mất lượt câu đó — và đây cũng
     * là lúc Model Answer được mở ("chỉ hiện ra sau khi hết thời gian trả lời").
     * Practice test thì đứng chờ bấm mic, không tự trừ thời gian.
     */
    function startMockAnswerWindow(inst) {
        clearTick(inst);
        inst.phase = 'answer-window';
        inst.remaining = inst.timing.answer;
        paintTimer(inst, 'Recording time', '');
        setRing(inst, 0);
        inst.tickId = window.setInterval(function () {
            inst.remaining -= 1;
            setRing(inst, 1 - (inst.remaining / inst.timing.answer));
            paintTimer(inst, 'Recording time', inst.remaining <= 10 ? 'is-low' : '');
            if (inst.remaining <= 0) {
                clearTick(inst);
                inst.phase = 'timeup';
                setRing(inst, 1);
                if (inst.els.micBtn) inst.els.micBtn.disabled = true;
                setStatus(inst, 'Time is up — no recording was made for this question.', true);
                completeQuestion(inst);
            }
        }, 1000);
    }

    function showMicError(inst, message) {
        if (!inst.els.micError) return;
        inst.els.micError.textContent = message;
        inst.els.micError.style.display = '';
    }

    function hideMicError(inst) {
        if (!inst.els.micError) return;
        inst.els.micError.textContent = '';
        inst.els.micError.style.display = 'none';
    }

    function micErrorMessage(err) {
        var name = err && err.name ? err.name : '';
        if (name === 'NotAllowedError' || name === 'SecurityError') {
            return 'Microphone access was blocked. Allow it in your browser settings and try again.';
        }
        if (name === 'NotFoundError' || name === 'DevicesNotFoundError') {
            return 'No microphone was found on this device.';
        }
        if (name === 'NotReadableError' || name === 'TrackStartError') {
            return 'The microphone is already in use by another application.';
        }
        return 'Could not start recording. Please check your microphone and try again.';
    }

    function pickRecorderOptions() {
        var candidates = ['audio/webm;codecs=opus', 'audio/webm', 'audio/ogg;codecs=opus', 'audio/mp4'];
        if (!window.MediaRecorder || typeof window.MediaRecorder.isTypeSupported !== 'function') return {};
        for (var i = 0; i < candidates.length; i++) {
            if (window.MediaRecorder.isTypeSupported(candidates[i])) return { mimeType: candidates[i] };
        }
        return {};
    }

    function onMicClick(inst) {
        if (inst.cfg.locked) return;
        if (inst.phase === 'recording') { stopRecording(inst); return; }
        if (inst.phase === 'uploading' || inst.phase === 'timeup') return;
        startRecording(inst);
    }

    function startRecording(inst) {
        if (inst.phase === 'recording' || inst.phase === 'timeup' || inst.cfg.locked) return;
        stopQuestionAudio(inst);
        clearTick(inst);

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia ||
            typeof window.MediaRecorder !== 'function') {
            showMicError(inst, 'This browser does not support audio recording.');
            return;
        }
        if (activeInstance && activeInstance !== inst) stopRecording(activeInstance);

        inst.els.micBtn.disabled = true;
        setStatus(inst, 'Requesting microphone access...');

        navigator.mediaDevices.getUserMedia({ audio: true }).then(function (stream) {
            inst.stream = stream;
            var recorder;
            try {
                recorder = new window.MediaRecorder(stream, pickRecorderOptions());
            } catch (e) {
                recorder = new window.MediaRecorder(stream);
            }
            inst.recorder = recorder;
            inst.chunks = [];
            recorder.ondataavailable = function (ev) {
                if (ev.data && ev.data.size) inst.chunks.push(ev.data);
            };
            recorder.onstop = function () { handleRecorded(inst); };
            recorder.start();

            activeInstance = inst;
            inst.phase = 'recording';
            hideMicError(inst);
            markMicRecording(inst, true);
            // Mock: đồng hồ đã chạy từ lúc câu hiện ra nên chỉ dùng phần thời
            // gian còn lại, không cấp thêm lượt mới.
            if (!(inst.cfg.isMock && inst.remaining > 0 && inst.remaining < inst.timing.answer)) {
                inst.remaining = inst.timing.answer;
            }
            paintTimer(inst, 'Recording', 'is-recording');
            setRing(inst, 0);
            setStatus(inst, 'Recording... press the microphone to stop.');
            inst.tickId = window.setInterval(function () {
                inst.remaining -= 1;
                setRing(inst, 1 - (inst.remaining / inst.timing.answer));
                paintTimer(inst, 'Recording', 'is-recording');
                if (inst.remaining <= 0) { clearTick(inst); stopRecording(inst); }
            }, 1000);
        }).catch(function (err) {
            inst.els.micBtn.disabled = false;
            showMicError(inst, micErrorMessage(err));
            setStatus(inst, '');
        });
    }

    function markMicRecording(inst, recording) {
        var btn = inst.els.micBtn;
        if (!btn) return;
        btn.disabled = false;
        btn.classList.toggle('is-recording', !!recording);
        btn.setAttribute('aria-label', recording ? 'Stop recording' : 'Start recording');
        if (inst.els.micWrap) inst.els.micWrap.classList.toggle('is-recording', !!recording);
        if (inst.els.micIcon) inst.els.micIcon.className = recording ? 'fas fa-stop' : 'fas fa-microphone';
    }

    function releaseStream(inst) {
        if (inst.stream) {
            try {
                inst.stream.getTracks().forEach(function (track) { track.stop(); });
            } catch (e) { /* ignore */ }
            inst.stream = null;
        }
    }

    function stopRecording(inst) {
        clearTick(inst);
        if (inst.recorder && inst.recorder.state !== 'inactive') {
            try { inst.recorder.stop(); } catch (e) { releaseStream(inst); }
        } else {
            releaseStream(inst);
        }
    }

    function handleRecorded(inst) {
        releaseStream(inst);
        if (activeInstance === inst) activeInstance = null;
        markMicRecording(inst, false);
        clearTick(inst);

        var type = (inst.recorder && inst.recorder.mimeType) || 'audio/webm';
        var blob = new Blob(inst.chunks, { type: type });
        inst.chunks = [];

        if (!blob.size) {
            armAnswerPhase(inst, 'No audio was captured. Please record again.');
            return;
        }

        var used = Math.max(1, inst.timing.answer - Math.max(0, inst.remaining));
        inst.remaining = used;
        paintTimer(inst, 'Recorded', '');
        setRing(inst, 1);

        if (inst.blobUrl) { try { URL.revokeObjectURL(inst.blobUrl); } catch (e) { /* ignore */ } }
        inst.blobUrl = URL.createObjectURL(blob);
        showPlayback(inst, inst.blobUrl);

        inst.phase = 'uploading';
        setStatus(inst, 'Saving your recording...');
        Promise.resolve(
            typeof inst.cfg.saveRecording === 'function' ? inst.cfg.saveRecording(inst.q.id, blob) : null
        ).then(function () {
            inst.phase = 'done';
            setStatus(inst, 'Recording saved.');
        }).catch(function () {
            inst.phase = 'done';
            setStatus(inst, 'Could not save the recording. Please record again.', true);
        });

        completeQuestion(inst);
    }

    function showPlayback(inst, url) {
        if (!inst.els.playback) return;
        inst.els.playbackAudio.src = url;
        inst.els.playback.classList.add('is-visible');
    }

    /** Đánh dấu câu đã xong: mở Model Answer, hiện nút sang câu tiếp theo. */
    function completeQuestion(inst, silent) {
        IeltsSpeakingFlow.markDone(inst.q.id);
        unlockModelAnswer(inst);
        showNextButton(inst);
        if (!inst.cfg.isMock && !inst.cfg.locked && inst.els.rerecord) {
            inst.els.rerecord.classList.add('is-visible');
        }
        if (!silent && typeof inst.cfg.onDone === 'function') inst.cfg.onDone(inst.q.id);
    }

    /** Thu lại (chỉ practice test) — giữ nguyên tiến độ đã đạt. */
    function resetForRerecord(inst) {
        if (inst.cfg.isMock || inst.cfg.locked) return;
        if (inst.phase === 'recording' || inst.phase === 'uploading') return;
        if (inst.els.playbackAudio) {
            try { inst.els.playbackAudio.pause(); } catch (e) { /* ignore */ }
        }
        if (inst.els.playback) inst.els.playback.classList.remove('is-visible');
        armAnswerPhase(inst, 'Press the microphone to record again.');
    }

    function applyLocked(inst) {
        if (!inst.cfg.locked) return;
        clearTick(inst);
        if (inst.els.micBtn) inst.els.micBtn.disabled = true;
        if (inst.els.rerecord) inst.els.rerecord.classList.remove('is-visible');
    }

    function dispose(inst) {
        clearTick(inst);
        stopQuestionAudio(inst);
        if (inst.phase === 'recording') {
            try { if (inst.recorder && inst.recorder.state !== 'inactive') inst.recorder.stop(); }
            catch (e) { /* ignore */ }
        }
        releaseStream(inst);
        if (inst.els.playbackAudio) {
            try { inst.els.playbackAudio.pause(); } catch (e) { /* ignore */ }
        }
        if (activeInstance === inst) activeInstance = null;
        var idx = instances.indexOf(inst);
        if (idx >= 0) instances.splice(idx, 1);
    }

    var instances = [];
    var activeInstance = null;

    /**
     * Dựng 1 màn hình Speaking cho 1 câu hỏi.
     *
     * @param {Object} entry  { question, part, group, ... } — entry của state
     * @param {Object} adapter
     *   isMock            {boolean} mock test hay practice test
     *   partNumber        {number}  1 | 2 | 3 (quyết định thời gian)
     *   questionLabel     {string}  "Question 3"
     *   stepLabel         {string}  "Question 3 of 5" (tuỳ chọn)
     *   locked            {boolean} hết giờ / đã submit → khoá thao tác
     *   storageKey        {string}  namespace lưu note trong localStorage
     *   hasNext           {boolean} còn câu sau trong part
     *   getRecordingUrl   {function(questionId): string|null}
     *   saveRecording     {function(questionId, blob): Promise}
     *   fetchModelAnswer  {function(questionId): Promise<string>} (mock test)
     *   onDone            {function(questionId)}
     *   onNext            {function(questionId)}
     * @returns {HTMLElement}
     */
    function render(entry, adapter) {
        var cfg = adapter || {};
        var q = (entry && entry.question) || {};
        var timing = timingFor(cfg.partNumber);
        var inst = {
            entry: entry, q: q, cfg: cfg, timing: timing,
            phase: 'idle', remaining: timing.answer,
            tickId: null, recorder: null, chunks: [], stream: null,
            blobUrl: null, questionAudio: null, modelFetched: false,
            els: {}
        };

        var root = el('div', 'speaking-stage' + (cfg.isMock ? ' is-mock' : ''));
        if (q.id !== undefined && q.id !== null) {
            root.setAttribute('data-question-id', q.id);
            // Cùng quy ước id với card câu hỏi thường (makeCard) để
            // scrollToQuestion()/highlight của cả 2 trang vẫn tìm thấy.
            root.id = 'exam-q-' + q.id;
        }
        inst.root = root;

        var left = el('div', 'speaking-stage-left');
        var right = el('div', 'speaking-stage-right');
        root.appendChild(left);
        root.appendChild(right);

        buildLeft(inst, left);
        buildRight(inst, right);

        root.__speakingStage = inst;
        instances.push(inst);

        hydrate(inst);
        applyLocked(inst);
        return root;
    }

    /** Vào lại câu đã thu: hiện luôn bản thu, mở Model Answer, khỏi thu lại. */
    function hydrate(inst) {
        var cfg = inst.cfg;
        var existing = typeof cfg.getRecordingUrl === 'function' ? cfg.getRecordingUrl(inst.q.id) : null;

        if (existing) {
            showPlayback(inst, existing);
            inst.phase = 'done';
            inst.remaining = 0;
            paintTimer(inst, 'Recorded', '');
            setRing(inst, 1);
            setStatus(inst, 'Your answer has been recorded.');
            completeQuestion(inst, true);
            return;
        }
        // Đã hết lượt (mock: hết giờ mà không thu) — không mở lại đồng hồ.
        if (IeltsSpeakingFlow.isDone(inst.q.id)) {
            inst.phase = 'timeup';
            inst.remaining = 0;
            paintTimer(inst, 'Recording time', '');
            setRing(inst, 1);
            if (inst.els.micBtn) inst.els.micBtn.disabled = true;
            setStatus(inst, 'No recording was made for this question.', true);
            completeQuestion(inst, true);
            return;
        }
        if (inst.timing.prepare > 0 && !cfg.locked) {
            startPrepare(inst);
            return;
        }
        armAnswerPhase(inst, '');
    }

    function disposeAll() {
        instances.slice().forEach(dispose);
        instances.length = 0;
    }

    function stopActiveRecording() {
        if (activeInstance) stopRecording(activeInstance);
    }

    window.IeltsSpeakingFlow = IeltsSpeakingFlow;
    window.IeltsSpeakingStage = {
        TIMING: TIMING,
        timingFor: timingFor,
        render: render,
        disposeAll: disposeAll,
        stopActiveRecording: stopActiveRecording,
        hasActiveRecording: function () { return !!activeInstance; },
        Flow: IeltsSpeakingFlow
    };

})(window, document);
