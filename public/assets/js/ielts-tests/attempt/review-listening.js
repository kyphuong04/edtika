/**
 * review-listening.js — phần Listening của trang CHỮA BÀI (Breakdown).
 *
 * Cột trái: ô hướng dẫn + transcript (người nói, đoạn đáp án tô tím) +
 * thanh audio (−10s / phát / +10s / tốc độ). Dòng đang đọc tự sáng lên và
 * transcript tự cuộn theo (tạm dừng cuộn 4 giây khi học viên tự cuộn).
 *
 * Nút ▶ cạnh số câu: tua audio tới dòng chứa đáp án câu đó, phát, tô đậm đoạn
 * đáp án trong transcript và đánh dấu câu đang xem ở cột câu hỏi.
 *
 * Dữ liệu: part.transcript_data (xem App\Support\Ielts\ListeningTranscript)
 *   { speakers: [{id, name, narrator}], lines: [{speaker, text, start}] }
 *   text chứa [[qN]]...[[/qN]] = đoạn đáp án câu N.
 * Đề cũ chỉ có part.transcript (HTML) -> hiển thị nguyên văn, không đồng bộ.
 *
 * Đoạn nghe từng câu (part.transcript_data.segments = { "5": {start, end} }, giáo viên
 * nhập): nút ▶ phát đúng đoạn rồi tự dừng; đoạn được tô trên thanh tua, có đếm
 * ngược, Lặp đoạn / Nghe lại đoạn / Nghe tiếp. Câu chưa có đoạn -> phát từ dòng
 * chứa đáp án và chạy tiếp như bình thường.
 *
 * review.js gọi: init(), renderPart(), decorateNumber(), focusEntry().
 */
(function () {
    'use strict';

    var MARK_RE = /\[\[q(\d{1,3})\]\]([\s\S]*?)\[\[\/q\1\]\]/g;
    var RATES = [0.75, 1, 1.25, 1.5];
    var FOLLOW_PAUSE_MS = 4000;

    function fmt(sec) {
        if (!isFinite(sec) || sec < 0) sec = 0;
        var m = Math.floor(sec / 60);
        var s = Math.floor(sec % 60);
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function el(tag, cls, text) {
        var node = document.createElement(tag);
        if (cls) node.className = cls;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    var RL = {
        enabled: false,
        audio: null,
        audioUrl: null,
        els: {},
        part: null,
        lines: [],          // [{ start, node }]
        activeLine: -1,
        lastUserScroll: 0,
        noteTimer: null,
        entries: [],
        sectionFiles: {},
        segments: {},       // đoạn nghe của Part đang xem
        seg: null,          // đoạn đang phát: { start, end, label, ended }
        loop: false,        // Lặp đoạn (giữ khi chuyển câu)
        onPlayEntry: null,  // review.js: đánh dấu câu đang xem

        init: function (opts) {
            this.enabled = true;
            this.els.context = opts.context;
            this.els.player = opts.player;
            this.entries = opts.entries || [];
            this.sectionFiles = opts.sectionFiles || {};
            this.onPlayEntry = opts.onPlayEntry || null;

            this.audio = new Audio();
            this.audio.preload = 'metadata';
            this.buildPlayer();
            this.bindAudio();

            var self = this;
            ['wheel', 'touchmove', 'keydown'].forEach(function (type) {
                self.els.context.addEventListener(type, function () { self.lastUserScroll = Date.now(); }, { passive: true });
            });

            // Bấm vào 1 dòng có mốc giờ -> nghe từ dòng đó.
            this.els.context.addEventListener('click', function (e) {
                var line = e.target.closest && e.target.closest('.rvl-line.has-time');
                if (!line || window.getSelection().toString()) return;
                self.clearSegment();
                self.seek(parseFloat(line.dataset.start), true);
            });
        },

        // ── Thanh audio ────────────────────────────────────────────────
        buildPlayer: function () {
            var p = this.els.player;
            p.innerHTML = ''
                + '<div class="rvl-segbar" hidden>'
                + '  <span class="rvl-seg-label"></span>'
                + '  <button type="button" class="rvl-segbtn rvl-seg-loop rv-allow" aria-pressed="false" title="Phát lặp lại đoạn này"><i class="fas fa-redo-alt"></i> Lặp đoạn</button>'
                + '  <button type="button" class="rvl-segbtn rvl-seg-replay rv-allow"><i class="fas fa-undo-alt"></i> Nghe lại đoạn</button>'
                + '  <button type="button" class="rvl-segbtn rvl-seg-continue rv-allow"><i class="fas fa-forward"></i> Nghe tiếp</button>'
                + '  <button type="button" class="rvl-segbtn rvl-seg-close rv-allow" aria-label="Thoát chế độ nghe đoạn" title="Thoát chế độ nghe đoạn"><i class="fas fa-times"></i></button>'
                + '</div>'
                + '<div class="rvl-seekwrap">'
                + '  <div class="rvl-segrange" hidden></div>'
                + '  <input type="range" class="rvl-seek rv-allow" min="0" max="1000" value="0" step="1" aria-label="Tua audio">'
                + '</div>'
                + '<div class="rvl-controls">'
                + '  <span class="rvl-time">00:00 / 00:00</span>'
                + '  <div class="rvl-vol">'
                + '    <button type="button" class="rvl-btn rvl-mute rv-allow" aria-label="Tắt tiếng"><i class="fas fa-volume-up"></i></button>'
                + '    <input type="range" class="rvl-volume rv-allow" min="0" max="1" step="0.05" value="1" aria-label="Âm lượng">'
                + '  </div>'
                + '  <div class="rvl-main">'
                + '    <button type="button" class="rvl-btn rvl-skip rv-allow" data-skip="-10" aria-label="Lùi 10 giây"><i class="fas fa-undo"></i><span>10</span></button>'
                + '    <button type="button" class="rvl-btn rvl-play-main rv-allow" aria-label="Phát"><i class="fas fa-play"></i></button>'
                + '    <button type="button" class="rvl-btn rvl-skip rv-allow" data-skip="10" aria-label="Tới 10 giây"><i class="fas fa-redo"></i><span>10</span></button>'
                + '  </div>'
                + '  <div class="rvl-extra">'
                + '    <button type="button" class="rvl-btn rvl-restart rv-allow" aria-label="Nghe lại từ đầu" title="Nghe lại từ đầu"><i class="fas fa-history"></i></button>'
                + '    <button type="button" class="rvl-btn rvl-rate rv-allow" aria-label="Tốc độ phát" title="Tốc độ phát">1x</button>'
                + '  </div>'
                + '</div>'
                + '<div class="rvl-note" role="status" aria-live="polite" hidden></div>';

            var self = this;
            var a = this.audio;
            this.els.seek = p.querySelector('.rvl-seek');
            this.els.time = p.querySelector('.rvl-time');
            this.els.playBtn = p.querySelector('.rvl-play-main');
            this.els.note = p.querySelector('.rvl-note');

            this.els.segbar = p.querySelector('.rvl-segbar');
            this.els.segLabel = p.querySelector('.rvl-seg-label');
            this.els.segRange = p.querySelector('.rvl-segrange');
            this.els.segLoop = p.querySelector('.rvl-seg-loop');

            this.els.playBtn.addEventListener('click', function () {
                // Hết đoạn mà bấm phát -> nghe lại đoạn đó.
                if (self.seg && self.seg.ended) self.replaySegment();
                else self.toggle();
            });
            p.querySelectorAll('.rvl-skip').forEach(function (b) {
                b.addEventListener('click', function () {
                    self.clearSegment();
                    self.seek(a.currentTime + parseFloat(b.dataset.skip), false);
                });
            });
            p.querySelector('.rvl-restart').addEventListener('click', function () {
                self.clearSegment();
                self.seek(0, true);
            });

            this.els.segLoop.addEventListener('click', function () {
                self.loop = !self.loop;
                self.renderSegment();
                if (self.loop && self.seg && self.seg.ended) self.replaySegment();
            });
            p.querySelector('.rvl-seg-replay').addEventListener('click', function () { self.replaySegment(); });
            p.querySelector('.rvl-seg-continue').addEventListener('click', function () {
                self.clearSegment();
                if (self.audioUrl) self.audio.play().catch(function () {});
            });
            p.querySelector('.rvl-seg-close').addEventListener('click', function () { self.clearSegment(); });

            var rateBtn = p.querySelector('.rvl-rate');
            rateBtn.addEventListener('click', function () {
                var idx = (RATES.indexOf(a.playbackRate) + 1) % RATES.length;
                a.playbackRate = RATES[idx];
                rateBtn.textContent = RATES[idx] + 'x';
            });

            var vol = p.querySelector('.rvl-volume');
            var mute = p.querySelector('.rvl-mute');
            vol.addEventListener('input', function () {
                a.volume = parseFloat(vol.value);
                a.muted = a.volume === 0;
                self.syncVolume(mute, vol);
            });
            mute.addEventListener('click', function () {
                a.muted = !a.muted;
                self.syncVolume(mute, vol);
            });

            var seeking = false;
            this.els.seek.addEventListener('input', function () {
                seeking = true;
                if (isFinite(a.duration)) self.renderTime(a.duration * self.els.seek.value / 1000);
            });
            this.els.seek.addEventListener('change', function () {
                seeking = false;
                self.clearSegment();
                if (isFinite(a.duration)) self.seek(a.duration * self.els.seek.value / 1000, false);
            });
            this._isSeeking = function () { return seeking; };
        },

        syncVolume: function (mute, vol) {
            var off = this.audio.muted || this.audio.volume === 0;
            mute.innerHTML = '<i class="fas ' + (off ? 'fa-volume-mute' : 'fa-volume-up') + '"></i>';
            mute.setAttribute('aria-label', off ? 'Bật tiếng' : 'Tắt tiếng');
            vol.style.setProperty('--p', (off ? 0 : this.audio.volume * 100) + '%');
        },

        bindAudio: function () {
            var self = this;
            var a = this.audio;

            a.addEventListener('play', function () { self.syncPlay(); });
            a.addEventListener('pause', function () { self.syncPlay(); });
            a.addEventListener('ended', function () { self.syncPlay(); });
            a.addEventListener('loadedmetadata', function () {
                self.renderTime(a.currentTime);
                self.renderSegment();
            });
            a.addEventListener('timeupdate', function () {
                if (!self._isSeeking()) self.renderTime(a.currentTime);
                self.syncActiveLine(a.currentTime);
                self.checkSegment(a.currentTime);
            });
        },

        renderTime: function (t) {
            var d = this.audio.duration;
            var pct = isFinite(d) && d > 0 ? (t / d) * 100 : 0;
            this.els.time.textContent = fmt(t) + ' / ' + fmt(isFinite(d) ? d : 0);
            if (!this._isSeeking()) this.els.seek.value = String(Math.round(pct * 10));
            this.els.seek.style.setProperty('--p', pct + '%');
        },

        syncPlay: function () {
            var playing = !this.audio.paused && !this.audio.ended;
            this.els.playBtn.innerHTML = '<i class="fas ' + (playing ? 'fa-pause' : 'fa-play') + '"></i>';
            this.els.playBtn.setAttribute('aria-label', playing ? 'Tạm dừng' : 'Phát');
        },

        toggle: function () {
            if (!this.audioUrl) return;
            if (this.audio.paused) this.audio.play().catch(function () {});
            else this.audio.pause();
        },

        seek: function (t, play) {
            if (!this.audioUrl || !isFinite(t)) return;
            var d = this.audio.duration;
            t = Math.max(0, isFinite(d) ? Math.min(d, t) : t);
            this.audio.currentTime = t;
            this.renderTime(t);
            this.syncActiveLine(t, true);
            if (play) this.audio.play().catch(function () {});
        },

        // ── Nghe theo đoạn ─────────────────────────────────────────────
        /** Đoạn nghe của câu (hoặc 1 số trong câu nhiều ô); câu nhiều ô: từ đoạn đầu tới hết đoạn cuối. */
        segmentFor: function (entry, onlyNumber) {
            var from = onlyNumber || entry.startNumber;
            var to = onlyNumber || entry.endNumber;
            var start = null;
            var end = null;
            for (var n = from; n <= to; n++) {
                var sg = this.segments[String(n)];
                if (!sg || !(sg.end > sg.start)) continue;
                start = start === null ? sg.start : Math.min(start, sg.start);
                end = end === null ? sg.end : Math.max(end, sg.end);
            }
            if (start === null) return null;
            return { start: Number(start), end: Number(end), label: from === to ? String(from) : (from + ' – ' + to), ended: false };
        },

        playSegment: function (seg) {
            this.seg = seg;
            this.seek(seg.start, true);
            this.renderSegment();
        },

        replaySegment: function () {
            if (!this.seg) return;
            this.seg.ended = false;
            this.seek(this.seg.start, true);
            this.renderSegment();
        },

        clearSegment: function () {
            if (!this.seg) return;
            this.seg = null;
            this.renderSegment();
        },

        /** Gọi mỗi timeupdate: tới mốc kết thúc -> lặp hoặc dừng. */
        checkSegment: function (t) {
            var seg = this.seg;
            if (!seg || seg.ended) return;

            // Người dùng tua ra ngoài đoạn bằng cách khác -> thoát chế độ đoạn.
            if (t < seg.start - 1 || t > seg.end + 1.5) {
                this.clearSegment();
                return;
            }

            if (t >= seg.end - 0.05) {
                if (this.loop) {
                    this.audio.currentTime = seg.start;
                } else {
                    seg.ended = true;
                    this.audio.pause();
                }
            }
            this.renderSegment();
        },

        renderSegment: function () {
            var seg = this.seg;
            var d = this.audio.duration;

            this.els.segbar.hidden = !seg;
            this.els.segRange.hidden = !seg || !(isFinite(d) && d > 0);
            this.els.player.classList.toggle('is-segment', !!seg);
            this.els.segLoop.classList.toggle('is-on', this.loop);
            this.els.segLoop.setAttribute('aria-pressed', this.loop ? 'true' : 'false');
            if (!seg) return;

            if (isFinite(d) && d > 0) {
                this.els.segRange.style.left = (seg.start / d * 100) + '%';
                this.els.segRange.style.width = (Math.max(0.5, (seg.end - seg.start) / d * 100)) + '%';
            }

            var left = Math.max(0, seg.end - Math.max(seg.start, this.audio.currentTime));
            this.els.segLabel.innerHTML = seg.ended
                ? '<b>Câu ' + seg.label + '</b> · hết đoạn'
                : '<b>Câu ' + seg.label + '</b> · ' + (this.loop ? 'đang lặp · ' : '') + 'còn ' + fmt(Math.ceil(left));
            this.els.segbar.classList.toggle('is-ended', !!seg.ended);
        },

        note: function (text) {
            var n = this.els.note;
            n.textContent = text;
            n.hidden = false;
            window.clearTimeout(this.noteTimer);
            this.noteTimer = window.setTimeout(function () { n.hidden = true; }, 2800);
        },

        // ── Dựng 1 Section (Part) ──────────────────────────────────────
        renderPart: function (part, partEntries) {
            this.part = part;
            this.lines = [];
            this.activeLine = -1;
            this.segments = (part.transcript_data && part.transcript_data.segments) || {};
            this.clearSegment();

            var ctx = this.els.context;
            ctx.classList.remove('hidden');
            ctx.classList.add('rvl-context');
            ctx.innerHTML = '';

            // Ô hướng dẫn: người ra đề nhập, không có thì tự sinh.
            var instr = el('div', 'rvl-instr');
            instr.innerHTML = '<i class="far fa-edit" aria-hidden="true"></i>';
            var instrText = el('div', 'rvl-instr-text');
            if (part.instructions) {
                instrText.innerHTML = part.instructions;
            } else if (partEntries.length) {
                var a = partEntries[0].startNumber;
                var b = partEntries[partEntries.length - 1].endNumber;
                instrText.textContent = 'Listen and answer Questions ' + a + (b > a ? ' - ' + b : '') + '.';
            }
            instr.appendChild(instrText);
            if (instrText.textContent.trim()) ctx.appendChild(instr);

            ctx.appendChild(this.buildScript(part));
            ctx.scrollTop = 0;

            // Audio của Part, không có thì audio chung của Section.
            var url = (part.files && part.files.audio) || (this.sectionFiles && this.sectionFiles.audio) || null;
            if (url !== this.audioUrl) {
                this.audio.pause();
                this.audioUrl = url;
                if (url) this.audio.src = url;
                else this.audio.removeAttribute('src');
                this.renderTime(0);
            }
            this.els.player.hidden = false;
            this.els.player.classList.toggle('is-disabled', !url);
            this.syncPlay();
            this.syncActiveLine(this.audio.currentTime, true);
        },

        buildScript: function (part) {
            var data = part.transcript_data;
            var box = el('div', 'rvl-script rv-allow'); // rv-allow: bấm dòng để nghe (không bị lớp chặn thao tác của review.js chặn)

            if (data && Array.isArray(data.lines) && data.lines.length) {
                var speakers = {};
                (data.speakers || []).forEach(function (s) { speakers[s.id] = s; });

                var turn = null;
                var prevSpeaker;
                var self = this;

                data.lines.forEach(function (line, i) {
                    var sp = speakers[line.speaker] || null;

                    if (!turn || line.speaker !== prevSpeaker) {
                        turn = el('div', 'rvl-turn' + (sp && sp.narrator ? ' is-narrator' : ''));
                        turn.appendChild(el('div', 'rvl-who', sp ? sp.name + ':' : ''));
                        turn.appendChild(el('div', 'rvl-says'));
                        box.appendChild(turn);
                        prevSpeaker = line.speaker;
                    }

                    var hasTime = line.start === 0 || (line.start !== null && line.start !== undefined && line.start !== '');
                    var node = el('span', 'rvl-line' + (hasTime ? ' has-time' : ''));
                    node.dataset.li = String(i);
                    if (hasTime) {
                        node.dataset.start = String(line.start);
                        node.title = 'Nghe từ ' + fmt(Number(line.start));
                    }
                    self.fillLine(node, String(line.text || ''));
                    turn.lastChild.appendChild(node);

                    self.lines.push({ start: hasTime ? Number(line.start) : null, node: node });
                });

                return box;
            }

            if (part.transcript) {
                box.classList.add('rvl-script--legacy');
                box.innerHTML = part.transcript; // đã clean() ở server
                return box;
            }

            box.appendChild(el('p', 'rvl-empty', 'Chưa có transcript cho Section này.'));
            return box;
        },

        /** Chữ thường + <mark> cho [[qN]]...[[/qN]] — không chèn HTML của người ra đề. */
        fillLine: function (node, text) {
            var last = 0;
            var m;
            MARK_RE.lastIndex = 0;
            while ((m = MARK_RE.exec(text))) {
                if (m.index > last) node.appendChild(document.createTextNode(text.slice(last, m.index)));
                var mark = el('mark', 'rvl-ans', m[2]);
                mark.dataset.q = m[1];
                mark.title = 'Câu ' + m[1];
                node.appendChild(mark);
                last = m.index + m[0].length;
            }
            if (last < text.length) node.appendChild(document.createTextNode(text.slice(last)));
        },

        // ── Đồng bộ dòng đang đọc ──────────────────────────────────────
        lineAt: function (t) {
            var found = -1;
            for (var i = 0; i < this.lines.length; i++) {
                var s = this.lines[i].start;
                if (s === null) continue;
                if (s <= t + 0.05) found = i;
                else break;
            }
            return found;
        },

        syncActiveLine: function (t, force) {
            if (!this.lines.length) return;
            // Chưa phát lần nào (đang ở 0:00) -> chưa tô dòng nào.
            var idx = (t <= 0 && this.audio.paused) ? -1 : this.lineAt(t);
            if (idx === this.activeLine && !force) return;

            if (this.activeLine >= 0 && this.lines[this.activeLine]) this.lines[this.activeLine].node.classList.remove('is-playing');
            this.activeLine = idx;
            if (idx < 0) return;

            var node = this.lines[idx].node;
            node.classList.add('is-playing');

            var userBusy = Date.now() - this.lastUserScroll < FOLLOW_PAUSE_MS;
            if (!this.audio.paused && !userBusy) this.scrollTo(node);
        },

        scrollTo: function (node) {
            var box = this.els.context;
            var b = box.getBoundingClientRect();
            var r = node.getBoundingClientRect();
            var target = box.scrollTop + (r.top - b.top) - (box.clientHeight / 2) + (r.height / 2);
            box.scrollTo({ top: Math.max(0, target), behavior: 'smooth' });
        },

        // ── Câu hỏi ↔ transcript ───────────────────────────────────────
        /** Đoạn đáp án của câu; onlyNumber: chỉ 1 số trong câu nhiều ô (VD 15 trong 14–15). */
        marksOf: function (entry, onlyNumber) {
            var from = onlyNumber || entry.startNumber;
            var to = onlyNumber || entry.endNumber;
            return Array.prototype.filter.call(this.els.context.querySelectorAll('mark.rvl-ans'), function (m) {
                var n = parseInt(m.dataset.q, 10);
                return n >= from && n <= to;
            });
        },

        /** Tô đậm đoạn đáp án của câu (không tua audio). */
        focusEntry: function (entry, opts) {
            if (!this.enabled || !entry) return [];
            var options = opts || {};

            this.els.context.querySelectorAll('mark.rvl-ans.is-focus').forEach(function (m) { m.classList.remove('is-focus'); });
            var marks = this.marksOf(entry, options.number);
            marks.forEach(function (m) { m.classList.add('is-focus'); });

            if (marks.length && options.scroll) {
                this.lastUserScroll = 0;
                this.scrollTo(marks[0]);
            }
            return marks;
        },

        /** Nút ▶: tua tới dòng chứa đáp án, phát, tô đậm đoạn đáp án. */
        playEntry: function (entry, onlyNumber) {
            if (this.onPlayEntry) this.onPlayEntry(entry);

            var marks = this.focusEntry(entry, { scroll: true, number: onlyNumber });
            var label = onlyNumber
                ? String(onlyNumber)
                : (entry.slotCount > 1 ? (entry.startNumber + ' – ' + entry.endNumber) : String(entry.startNumber));

            // Giáo viên đã nhập đoạn nghe -> phát đúng đoạn rồi tự dừng.
            var seg = this.segmentFor(entry, onlyNumber);
            if (seg && this.audioUrl) {
                this.lastUserScroll = Date.now();
                this.playSegment(seg);
                return;
            }
            this.clearSegment();

            if (!marks.length) {
                this.note('Câu ' + label + ' chưa được gắn vị trí trong transcript.');
                return;
            }
            if (!this.audioUrl) {
                this.note('Section này chưa có file audio.');
                return;
            }

            var lineNode = marks[0].closest('.rvl-line');
            var li = lineNode ? parseInt(lineNode.dataset.li, 10) : -1;
            var t = null;
            for (var i = li; i >= 0 && t === null; i--) {
                if (this.lines[i] && this.lines[i].start !== null) t = this.lines[i].start;
            }

            if (t === null) {
                this.note('Đoạn của câu ' + label + ' chưa có mốc thời gian.');
                return;
            }

            this.lastUserScroll = Date.now(); // giữ đoạn đáp án ở giữa, không cuộn theo ngay
            this.seek(t, true);
        },

        /**
         * Khối số câu kiểu mockup Listening: vòng tròn viền đôi + nút ▶ bên dưới.
         * number: số câu hiển thị (câu nhiều ô dựng 1 khối cho mỗi số).
         */
        numberBlock: function (entry, number) {
            var box = el('div', 'rvl-qnum');
            var ring = el('span', 'rvl-ring', String(number));
            ring.setAttribute('aria-hidden', 'true');
            box.appendChild(ring);

            var btn = el('button', 'rvl-qplay-tri rv-allow');
            btn.type = 'button';
            btn.innerHTML = '<i class="fas fa-play" aria-hidden="true"></i>';
            btn.setAttribute('aria-label', 'Nghe đoạn audio của câu ' + number);
            btn.title = 'Nghe đoạn audio của câu ' + number;

            var self = this;
            var only = entry.slotCount > 1 ? number : undefined;
            btn.addEventListener('click', function () { self.playEntry(entry, only); });
            box.appendChild(btn);
            return box;
        },

        /** Gắn nút ▶ vào vòng số câu (review.js gọi cho mỗi thẻ câu hỏi). */
        decorateNumber: function (numEl, entry) {
            if (!this.enabled || !numEl || numEl.parentNode.classList.contains('rvl-numwrap')) return;

            var wrap = el('span', 'rvl-numwrap');
            numEl.replaceWith(wrap);
            wrap.appendChild(numEl);

            var label = entry.slotCount > 1 ? (entry.startNumber + ' – ' + entry.endNumber) : String(entry.startNumber);
            var btn = el('button', 'rvl-qplay rv-allow');
            btn.type = 'button';
            btn.innerHTML = '<i class="fas fa-play" aria-hidden="true"></i>';
            btn.setAttribute('aria-label', 'Nghe đoạn audio của câu ' + label);
            btn.title = 'Nghe đoạn audio của câu ' + label;

            var self = this;
            btn.addEventListener('click', function () { self.playEntry(entry); });
            wrap.appendChild(btn);
        },

        /** Trích đoạn đáp án cho sidebar Answer Help. */
        excerptHtml: function (entry) {
            if (!this.enabled) return '';
            var marks = this.marksOf(entry);
            if (!marks.length) return '';

            var esc = function (s) {
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            };

            var items = marks.map(function (m) {
                var line = m.closest('.rvl-line');
                var turn = m.closest('.rvl-turn');
                var who = turn ? turn.querySelector('.rvl-who').textContent : '';
                var html = esc(line ? line.textContent : m.textContent)
                    .replace(esc(m.textContent), '<mark>' + esc(m.textContent) + '</mark>');
                return '<p><b>' + esc(who) + '</b> ' + html + '</p>';
            }).join('');

            return '<section class="rvl-help-excerpt">'
                + '<h4>Trích transcript</h4>' + items
                + '<button type="button" class="rvl-help-play" data-question-id="' + entry.question.id + '">'
                + '<i class="fas fa-play"></i> Nghe đoạn này</button>'
                + '</section>';
        },
    };

    window.ReviewListening = RL;
})();
