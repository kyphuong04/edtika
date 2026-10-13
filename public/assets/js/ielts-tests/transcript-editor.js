/**
 * transcript-editor.js — cửa sổ nhập Transcript cho Part Listening trong trình
 * tạo đề (create_inline_complete_with_groups.blade.php).
 *
 * Dữ liệu ghi vào part.transcript_data (cùng testData của trình tạo đề, gửi lên
 * khi lưu đề; backend chuẩn hoá bằng App\Support\Ielts\ListeningTranscript):
 *   {
 *     speakers: [{ id: "s1", name: "Jen", narrator: false }],
 *     lines:    [{ speaker: "s1", text: "Hi, [[q1]]I booked a room[[/q1]].", start: 12.5 }]
 *   }
 *   - start : giây tính từ đầu file audio của Part (null = chưa đánh dấu)
 *   - [[qN]]...[[/qN]] : đoạn chứa đáp án câu N (số câu như hiển thị trong đề)
 *
 * Cách dùng cho giáo viên:
 *   1. Khai báo người nói, dán transcript ("Tên: nội dung") -> tự tách dòng.
 *   2. Bấm để đánh dấu: phát audio, đến đầu dòng nào bấm "Đánh dấu" (phím M).
 *   3. Bôi đen đoạn đáp án trong 1 dòng -> chọn số câu -> "Gắn đáp án".
 *
 * Dùng các hàm/biến toàn cục của trình tạo đề: testData, getGroupSlotCount,
 * renderSectionParts, queueAutosaveSnapshot (nếu có).
 */
(function () {
    'use strict';

    var MARK_RE = /\[\[q(\d{1,3})\]\]([\s\S]*?)\[\[\/q\1\]\]/g;
    var NARRATOR_RE = /^(narrator|announcer|người dẫn|dẫn chương trình|mc)$/i;
    var SPEAKER_COLORS = ['#7c3aed', '#0f766e', '#c2410c', '#1d4ed8', '#be185d', '#4d7c0f', '#a16207', '#0e7490'];

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /** 75.3 -> "01:15.3" */
    function formatTime(sec) {
        if (sec === null || sec === undefined || !isFinite(sec)) return '';
        var m = Math.floor(sec / 60);
        var s = sec - m * 60;
        var whole = Math.floor(s);
        var tenth = Math.round((s - whole) * 10);
        if (tenth === 10) { whole += 1; tenth = 0; }
        return (m < 10 ? '0' : '') + m + ':' + (whole < 10 ? '0' : '') + whole + (tenth ? '.' + tenth : '');
    }

    /** "1:15.3" | "75.3" | "0:01:15" -> giây; chuỗi rỗng -> null; sai -> undefined */
    function parseTime(text) {
        var t = String(text || '').trim().replace(',', '.');
        if (t === '') return null;
        if (!/^\d+(:\d{1,2}){0,2}(\.\d+)?$/.test(t)) return undefined;
        var parts = t.split(':').map(parseFloat);
        var sec = 0;
        parts.forEach(function (p) { sec = sec * 60 + p; });
        return Math.round(sec * 100) / 100;
    }

    function uid() {
        return 's' + Math.random().toString(36).slice(2, 8);
    }

    /** Bản nháp transcript trên trình duyệt — giữ lại khi tải lại trang trước khi lưu đề. */
    var Drafts = {
        key: function (skill, partIndex) {
            var testId = (typeof AUTOSAVE_TEST_ID !== 'undefined' && AUTOSAVE_TEST_ID) ? AUTOSAVE_TEST_ID : 'new';
            return 'ielts-tse-draft:' + testId + ':' + skill + ':' + partIndex;
        },
        read: function (skill, partIndex) {
            try {
                var raw = window.localStorage.getItem(this.key(skill, partIndex));
                return raw ? JSON.parse(raw) : null;
            } catch (e) { return null; }
        },
        write: function (skill, partIndex, title, data) {
            try {
                window.localStorage.setItem(this.key(skill, partIndex), JSON.stringify({ savedAt: Date.now(), title: title || '', data: data }));
            } catch (e) { /* hết dung lượng / chế độ ẩn danh */ }
        },
        remove: function (skill, partIndex) {
            try { window.localStorage.removeItem(this.key(skill, partIndex)); } catch (e) { /* bỏ qua */ }
        },
    };

    function sameData(a, b) {
        return JSON.stringify(a || null) === JSON.stringify(b || null);
    }

    var Editor = {
        pendingSubmit: false, // đã "Lưu transcript" nhưng chưa lưu đề
        draft: null,
        modal: null,
        audio: null,
        audioUrl: null,
        ctx: null,          // { sectionEl, skill, part }
        speakers: [],
        lines: [],
        target: 0,          // dòng sẽ được "Đánh dấu"
        selection: null,    // { line, start, end } — đoạn đang bôi đen
        dirty: false,
        range: null,
        segments: {},       // { "5": { start, end } } — đoạn nghe từng câu
        previewEnd: null,   // mốc dừng của "Nghe thử"        // { from, to } số câu của Part

        // ── Khởi tạo khung (1 lần) ─────────────────────────────────────
        build: function () {
            if (this.modal) return;

            var modal = document.createElement('div');
            modal.className = 'tse-modal';
            modal.hidden = true;
            modal.innerHTML = ''
                + '<div class="tse-backdrop"></div>'
                + '<div class="tse-dialog" role="dialog" aria-modal="true" aria-labelledby="tseTitle">'
                + '  <header class="tse-head">'
                + '    <div><h3 id="tseTitle">Transcript</h3><p class="tse-sub"></p></div>'
                + '    <button type="button" class="tse-x" data-act="close" aria-label="Đóng">&times;</button>'
                + '  </header>'
                + '  <div class="tse-toolbar">'
                + '    <div class="tse-player">'
                + '      <audio class="tse-audio" controls preload="metadata"></audio>'
                + '      <select class="tse-rate" title="Tốc độ phát">'
                + '        <option value="0.75">0.75x</option><option value="1" selected>1x</option>'
                + '        <option value="1.25">1.25x</option><option value="1.5">1.5x</option>'
                + '      </select>'
                + '      <button type="button" class="tse-btn tse-btn--primary tse-stamp" data-act="stamp-target">'
                + '        <i class="fas fa-stopwatch"></i> <span>Đánh dấu dòng 1</span></button>'
                + '      <span class="tse-hint">Phím tắt: <kbd>M</kbd> đánh dấu, <kbd>Space</kbd> phát/dừng</span>'
                + '    </div>'
                + '    <p class="tse-noaudio" hidden>Part này chưa có file audio — vẫn nhập được transcript, mốc giờ có thể gõ tay.</p>'
                + '  </div>'
                + '  <div class="tse-draft" hidden>'
                + '    <i class="fas fa-history"></i>'
                + '    <span class="tse-draft-text"></span>'
                + '    <button type="button" class="tse-btn tse-btn--primary" data-act="draft-restore">Khôi phục</button>'
                + '    <button type="button" class="tse-btn" data-act="draft-discard">Bỏ qua</button>'
                + '  </div>'
                + '  <div class="tse-body">'
                + '    <section class="tse-block">'
                + '      <div class="tse-block-head"><h4>Người nói</h4>'
                + '        <button type="button" class="tse-btn" data-act="add-speaker"><i class="fas fa-plus"></i> Thêm người nói</button></div>'
                + '      <div class="tse-speakers"></div>'
                + '    </section>'
                + '    <section class="tse-block">'
                + '      <div class="tse-block-head"><h4>Dán nhanh</h4>'
                + '        <button type="button" class="tse-btn" data-act="toggle-paste">Mở / đóng</button></div>'
                + '      <div class="tse-paste" hidden>'
                + '        <textarea class="tse-paste-text" rows="6" placeholder="Mỗi lượt nói 1 dòng, dạng  Tên: nội dung\nJen: Oh, hi, Jon and Irene. Are you two heading to class?\nBill: Yes. We\'re just walking there now.\n(Có thể kèm mốc giờ đầu dòng: [00:12] Jen: ...)"></textarea>'
                + '        <label class="tse-check"><input type="checkbox" class="tse-split" checked> Tách mỗi câu thành 1 dòng (đồng bộ audio chính xác hơn)</label>'
                + '        <div class="tse-paste-actions">'
                + '          <button type="button" class="tse-btn tse-btn--primary" data-act="paste-replace">Thay toàn bộ</button>'
                + '          <button type="button" class="tse-btn" data-act="paste-append">Thêm vào cuối</button>'
                + '        </div>'
                + '      </div>'
                + '    </section>'
                + '    <section class="tse-block">'
                + '      <div class="tse-block-head"><h4>Gắn đáp án</h4></div>'
                + '      <div class="tse-tagbar">'
                + '        <span class="tse-tag-hint">Bôi đen đoạn chứa đáp án trong 1 dòng, chọn số câu rồi bấm Gắn.</span>'
                + '        <span class="tse-tag-pick"></span>'
                + '        <button type="button" class="tse-btn tse-btn--primary" data-act="tag" disabled><i class="fas fa-highlighter"></i> Gắn đáp án</button>'
                + '      </div>'
                + '      <div class="tse-coverage"></div>'
                + '    </section>'
                + '    <section class="tse-block">'
                + '      <div class="tse-block-head"><h4>Đoạn nghe từng câu <span class="tse-seg-count"></span></h4></div>'
                + '      <p class="tse-tag-hint tse-seg-hint">Mốc bắt đầu – kết thúc của đoạn audio chứa đáp án. Trên trang chữa bài, bấm ▶ ở câu sẽ phát đúng đoạn này rồi tự dừng. '
                + 'Phát audio, đến đầu đoạn bấm ⏱ ô bắt đầu, đến cuối đoạn bấm ⏱ ô kết thúc (hoặc gõ mm:ss). Câu bỏ trống: phát từ dòng chứa đáp án như cũ.</p>'
                + '      <div class="tse-segs"></div>'
                + '    </section>'
                + '    <section class="tse-block tse-block--lines">'
                + '      <div class="tse-block-head"><h4>Các dòng <span class="tse-count"></span></h4>'
                + '        <button type="button" class="tse-btn" data-act="add-line"><i class="fas fa-plus"></i> Thêm dòng</button></div>'
                + '      <div class="tse-lines"></div>'
                + '    </section>'
                + '  </div>'
                + '  <footer class="tse-foot">'
                + '    <span class="tse-foot-info"></span>'
                + '    <span class="tse-foot-note">Sau khi lưu transcript, nhớ bấm <b>Lưu / Cập nhật đề</b> để lưu lên hệ thống.</span>'
                + '    <button type="button" class="tse-btn" data-act="close">Hủy</button>'
                + '    <button type="button" class="tse-btn tse-btn--primary" data-act="save">Lưu transcript</button>'
                + '  </footer>'
                + '</div>';

            document.body.appendChild(modal);
            this.modal = modal;
            this.audio = modal.querySelector('.tse-audio');

            var self = this;
            modal.addEventListener('click', function (e) { self.onClick(e); });
            modal.addEventListener('input', function (e) { self.onInput(e); });
            modal.addEventListener('change', function (e) { self.onChange(e); });

            // Ghi nhớ đoạn đang bôi đen trong ô nội dung.
            ['select', 'keyup', 'mouseup'].forEach(function (type) {
                modal.addEventListener(type, function (e) {
                    if (e.target.classList && e.target.classList.contains('tse-text')) self.captureSelection(e.target);
                });
            });

            document.addEventListener('keydown', function (e) { self.onKey(e); });

            // "Nghe thử" đoạn nghe: tự dừng ở mốc kết thúc.
            this.audio.addEventListener('timeupdate', function () {
                if (self.previewEnd !== null && self.audio.currentTime >= self.previewEnd) {
                    self.previewEnd = null;
                    self.audio.pause();
                }
            });
            this.audio.addEventListener('seeking', function () {
                if (self.previewEnd !== null && (self.audio.currentTime > self.previewEnd + 0.5)) self.previewEnd = null;
            });

            this.audio.addEventListener('ratechange', function () {
                modal.querySelector('.tse-rate').value = String(self.audio.playbackRate);
            });
        },

        // ── Mở / đóng ───────────────────────────────────────────────────
        open: function (button) {
            var partItem = button.closest('.part-item');
            var sectionEl = button.closest('.section-container');
            if (!partItem || !sectionEl || typeof testData === 'undefined') return;

            var skill = sectionEl.getAttribute('data-skill');
            var partId = partItem.getAttribute('data-part-id');
            var parts = (testData.sections[skill] && testData.sections[skill].parts) || [];
            var part = parts.find(function (p) { return String(p.id) === String(partId); });
            if (!part) return;

            this.build();
            this.ctx = { sectionEl: sectionEl, skill: skill, part: part };
            this.range = this.numberRange(parts, part);

            var data = part.transcript_data || {};
            this.speakers = (data.speakers || []).map(function (s) {
                return { id: s.id || uid(), name: s.name || '', narrator: !!s.narrator };
            });
            if (!this.speakers.length) this.speakers = this.defaultSpeakers(parts.indexOf(part));

            this.lines = (data.lines || []).map(function (l) {
                return { speaker: l.speaker || null, text: l.text || '', start: (l.start === 0 || l.start) ? Number(l.start) : null };
            });

            this.segments = {};
            var segSrc = data.segments || {};
            Object.keys(segSrc).forEach(function (n) {
                var sg = segSrc[n] || {};
                this.segments[n] = {
                    start: (sg.start === 0 || sg.start) ? Number(sg.start) : null,
                    end: (sg.end === 0 || sg.end) ? Number(sg.end) : null,
                };
            }, this);

            this.target = this.firstUnstamped();
            this.selection = null;
            this.dirty = false;

            this.modal.querySelector('.tse-sub').textContent = (part.title || 'Part')
                + (this.range ? ' · Câu ' + this.range.from + ' – ' + this.range.to : '');
            this.modal.querySelector('.tse-paste').hidden = this.lines.length > 0;
            this.modal.querySelector('.tse-paste-text').value = '';

            this.loadAudio(part, skill);
            this.renderAll();
            this.checkDraft(parts.indexOf(part));

            this.modal.hidden = false;
            document.body.classList.add('tse-open');
        },

        /** Có bản nháp khác dữ liệu hiện tại (VD tải lại trang trước khi lưu đề) -> hỏi khôi phục. */
        checkDraft: function (partIndex) {
            var box = this.modal.querySelector('.tse-draft');
            var part = this.ctx.part;
            var draft = Drafts.read(this.ctx.skill, partIndex);
            this.ctx.partIndex = partIndex;
            this.draft = null;
            box.hidden = true;

            if (!draft || !draft.data) return;
            if (sameData(draft.data, part.transcript_data) || (draft.title && draft.title !== (part.title || ''))) {
                if (sameData(draft.data, part.transcript_data)) Drafts.remove(this.ctx.skill, partIndex);
                return;
            }

            this.draft = draft;
            var lines = (draft.data.lines || []).length;
            box.querySelector('.tse-draft-text').textContent = 'Có bản transcript chưa được lưu vào đề (' + lines + ' dòng, lúc '
                + new Date(draft.savedAt).toLocaleString() + '). Khôi phục để tiếp tục?';
            box.hidden = false;
        },

        restoreDraft: function () {
            if (!this.draft) return;
            var data = this.draft.data;
            this.speakers = (data.speakers || []).map(function (sp) { return { id: sp.id || uid(), name: sp.name || '', narrator: !!sp.narrator }; });
            this.lines = (data.lines || []).map(function (l) {
                return { speaker: l.speaker || null, text: l.text || '', start: (l.start === 0 || l.start) ? Number(l.start) : null };
            });

            this.segments = {};
            var segSrc = data.segments || {};
            Object.keys(segSrc).forEach(function (n) {
                var sg = segSrc[n] || {};
                this.segments[n] = {
                    start: (sg.start === 0 || sg.start) ? Number(sg.start) : null,
                    end: (sg.end === 0 || sg.end) ? Number(sg.end) : null,
                };
            }, this);
            this.target = this.firstUnstamped();
            this.modal.querySelector('.tse-draft').hidden = true;
            this.modal.querySelector('.tse-paste').hidden = true;
            this.draft = null;
            this.touch();
            this.renderAll();
        },

        discardDraft: function () {
            Drafts.remove(this.ctx.skill, this.ctx.partIndex);
            this.draft = null;
            this.modal.querySelector('.tse-draft').hidden = true;
        },

        close: function (force) {
            if (!this.modal || this.modal.hidden) return;
            if (!force && this.dirty && !window.confirm('Đóng mà không lưu thay đổi transcript?')) return;

            this.audio.pause();
            this.modal.hidden = true;
            document.body.classList.remove('tse-open');
            if (this.audioUrl && this.audioUrl.indexOf('blob:') === 0) URL.revokeObjectURL(this.audioUrl);
            this.audioUrl = null;
        },

        save: function () {
            var part = this.ctx.part;
            var lines = this.lines
                .map(function (l) { return { speaker: l.speaker, text: String(l.text || '').trim(), start: l.start }; })
                .filter(function (l) { return l.text !== ''; });

            var speakers = this.speakers.map(function (s, i) {
                return { id: s.id, name: (s.name || '').trim() || ('Speaker ' + (i + 1)), narrator: !!s.narrator };
            });

            // Đoạn nghe: chỉ lưu đoạn đủ 2 mốc và kết thúc > bắt đầu; đoạn dở dang -> hỏi trước.
            var segments = {};
            var broken = [];
            Object.keys(this.segments).forEach(function (n) {
                var sg = this.segments[n];
                var hasStart = sg.start !== null;
                var hasEnd = sg.end !== null;
                if (!hasStart && !hasEnd) return;
                if (hasStart && hasEnd && sg.end > sg.start) segments[n] = { start: sg.start, end: sg.end };
                else broken.push(n);
            }, this);

            if (broken.length && !window.confirm('Đoạn nghe của câu ' + broken.join(', ') + ' chưa đủ mốc hoặc mốc kết thúc không sau mốc bắt đầu — sẽ bị bỏ qua. Vẫn lưu?')) {
                return;
            }

            var hasSegments = Object.keys(segments).length > 0;
            part.transcript_data = (lines.length || hasSegments)
                ? Object.assign({ speakers: speakers, lines: lines }, hasSegments ? { segments: segments } : {})
                : null;

            // Giữ bản nháp trên trình duyệt tới khi đề được lưu (trang sửa đề không khôi phục auto save).
            Drafts.write(this.ctx.skill, this.ctx.partIndex, part.title, part.transcript_data);
            Editor.pendingSubmit = true;

            this.dirty = false;
            this.close(true);

            if (typeof renderSectionParts === 'function') renderSectionParts(this.ctx.sectionEl, this.ctx.skill);
            if (typeof queueAutosaveSnapshot === 'function') queueAutosaveSnapshot(true);
            if (typeof window.showToast === 'function') {
                window.showToast('success', 'Đã lưu transcript vào bản soạn', 'Bấm Lưu / Cập nhật đề để lưu transcript lên hệ thống.');
            }
        },

        // ── Dữ liệu phụ ────────────────────────────────────────────────
        /** Số câu của Part: đánh số liên tục trong cả section như trang làm bài. */
        numberRange: function (parts, part) {
            if (typeof getGroupSlotCount !== 'function') return null;
            var offset = 0;
            for (var i = 0; i < parts.length; i++) {
                var count = (parts[i].groups || []).reduce(function (sum, g) { return sum + getGroupSlotCount(g); }, 0);
                if (parts[i] === part) return count > 0 ? { from: offset + 1, to: offset + count } : null;
                offset += count;
            }
            return null;
        },

        /** Mặc định theo dạng đề IELTS: Part 1 & 3 hội thoại, Part 2 & 4 độc thoại. */
        defaultSpeakers: function (partIndex) {
            var talkers = [2, 1, 3, 1][partIndex] || 2;
            var list = [{ id: 'narrator', name: 'Narrator', narrator: true }];
            for (var i = 1; i <= talkers; i++) list.push({ id: 's' + i, name: 'Speaker ' + i, narrator: false });
            return list;
        },

        firstUnstamped: function () {
            for (var i = 0; i < this.lines.length; i++) if (this.lines[i].start === null) return i;
            return Math.max(0, this.lines.length - 1);
        },

        speakerColor: function (id) {
            var idx = this.speakers.findIndex(function (s) { return s.id === id; });
            var sp = this.speakers[idx];
            if (!sp) return '#94a3b8';
            return sp.narrator ? '#64748b' : SPEAKER_COLORS[idx % SPEAKER_COLORS.length];
        },

        audioUrlFor: function (part, skill) {
            var input = part.upload_id
                ? document.querySelector('input[name="group_media[' + part.upload_id + '][audio]"]')
                : null;
            if (input && input.files && input.files[0]) return URL.createObjectURL(input.files[0]);

            var path = (part.files && part.files.audio) || part.audio_file || null;
            if (!path) {
                var section = testData.sections[skill] || {};
                path = (section.files && section.files.audio) || null;
            }
            if (!path) return null;
            if (/^([a-z][a-z0-9+.-]*:|\/)/i.test(path)) return path; // URL đầy đủ hoặc đường dẫn tuyệt đối

            // Đường dẫn trong disk "public" (VD ielts/test_parts/audio/x.mp3). URL gốc của
            // disk do Blade truyền vào (config/filesystems.php: public.url, hiện là /store).
            var base = String(window.IELTS_PUBLIC_DISK_URL || '/store').replace(/\/+$/, '');
            return base + '/' + String(path).replace(/^\/+/, '');
        },

        loadAudio: function (part, skill) {
            this.audio.pause();
            this.audioUrl = this.audioUrlFor(part, skill);
            var none = !this.audioUrl;

            this.modal.querySelector('.tse-noaudio').hidden = !none;
            this.modal.querySelector('.tse-player').classList.toggle('is-disabled', none);
            if (none) this.audio.removeAttribute('src');
            else this.audio.src = this.audioUrl;
            this.audio.playbackRate = 1;
        },

        // ── Hiển thị ───────────────────────────────────────────────────
        renderAll: function () {
            this.renderSpeakers();
            this.renderLines();
            this.renderTagPicker();
            this.renderCoverage();
            this.renderSegments();
            this.renderStampButton();
        },

        // ── Đoạn nghe từng câu ─────────────────────────────────────────
        segNumbers: function () {
            var list = [];
            if (this.range) {
                for (var n = this.range.from; n <= this.range.to; n++) list.push(String(n));
            }
            // Đoạn đã lưu cho số câu ngoài phạm vi hiện tại (đề bị đổi số) vẫn hiện để sửa / xoá.
            Object.keys(this.segments).forEach(function (n) { if (list.indexOf(n) < 0) list.push(n); });
            return list.sort(function (a, b) { return a - b; });
        },

        segRowHtml: function (n) {
            var sg = this.segments[n] || { start: null, end: null };
            return '<div class="tse-seg" data-n="' + esc(n) + '">'
                + '<span class="tse-seg-num">Câu ' + esc(n) + '</span>'
                + '<input type="text" class="tse-time-input tse-seg-input" data-edge="start" value="' + esc(formatTime(sg.start)) + '" placeholder="Bắt đầu" aria-label="Bắt đầu đoạn câu ' + esc(n) + '">'
                + '<button type="button" class="tse-icon" data-act="seg-stamp" data-edge="start" title="Lấy thời điểm audio hiện tại làm mốc bắt đầu"><i class="fas fa-stopwatch"></i></button>'
                + '<span class="tse-seg-arrow">→</span>'
                + '<input type="text" class="tse-time-input tse-seg-input" data-edge="end" value="' + esc(formatTime(sg.end)) + '" placeholder="Kết thúc" aria-label="Kết thúc đoạn câu ' + esc(n) + '">'
                + '<button type="button" class="tse-icon" data-act="seg-stamp" data-edge="end" title="Lấy thời điểm audio hiện tại làm mốc kết thúc"><i class="fas fa-stopwatch"></i></button>'
                + '<button type="button" class="tse-btn tse-seg-play" data-act="seg-play"><i class="fas fa-play"></i> Nghe thử</button>'
                + '<span class="tse-seg-len"></span>'
                + '<button type="button" class="tse-icon tse-icon--danger" data-act="seg-clear" title="Xoá đoạn nghe"><i class="fas fa-times"></i></button>'
                + '</div>';
        },

        renderSegments: function () {
            var box = this.modal.querySelector('.tse-segs');
            var nums = this.segNumbers();
            if (!nums.length) {
                box.innerHTML = '<span class="tse-tag-hint">Part chưa có câu hỏi — thêm câu hỏi trước để nhập đoạn nghe.</span>';
                this.modal.querySelector('.tse-seg-count').textContent = '';
                return;
            }
            box.innerHTML = nums.map(this.segRowHtml, this).join('');
            nums.forEach(this.syncSegRow, this);
            this.renderSegCount();
        },

        renderSegCount: function () {
            var nums = this.segNumbers();
            var done = nums.filter(function (n) {
                var sg = this.segments[n];
                return sg && sg.start !== null && sg.end !== null && sg.end > sg.start;
            }, this).length;
            this.modal.querySelector('.tse-seg-count').textContent = nums.length ? '(' + done + '/' + nums.length + ')' : '';
        },

        /** Độ dài đoạn + báo lỗi khi kết thúc <= bắt đầu. */
        syncSegRow: function (n) {
            var row = this.modal.querySelector('.tse-seg[data-n="' + n + '"]');
            if (!row) return;
            var sg = this.segments[n] || { start: null, end: null };
            var bad = sg.start !== null && sg.end !== null && sg.end <= sg.start;
            var ok = sg.start !== null && sg.end !== null && !bad;
            row.classList.toggle('is-done', ok);
            row.classList.toggle('is-bad', bad);
            row.querySelector('.tse-seg-len').textContent = ok
                ? (Math.round((sg.end - sg.start) * 10) / 10) + ' giây'
                : (bad ? 'Kết thúc phải sau bắt đầu' : '');
            row.querySelector('.tse-seg-play').disabled = !ok || !this.audioUrl;
        },

        setSegEdge: function (n, edge, value) {
            if (!this.segments[n]) this.segments[n] = { start: null, end: null };
            this.segments[n][edge] = value;
            this.touch();
            this.syncSegRow(n);
            this.renderSegCount();
        },

        playSegment: function (n) {
            var sg = this.segments[n];
            if (!sg || sg.start === null || sg.end === null || !this.audioUrl) return;
            this.previewEnd = sg.end;
            this.audio.currentTime = sg.start;
            this.audio.play().catch(function () {});
        },

        renderSpeakers: function () {
            var self = this;
            var box = this.modal.querySelector('.tse-speakers');
            box.innerHTML = this.speakers.map(function (s, i) {
                return '<div class="tse-speaker" data-sid="' + esc(s.id) + '">'
                    + '<span class="tse-dot" style="background:' + self.speakerColor(s.id) + '"></span>'
                    + '<input type="text" class="tse-speaker-name" maxlength="40" value="' + esc(s.name) + '" placeholder="Tên người nói">'
                    + '<label class="tse-check" title="Người dẫn: hiển thị nghiêng, mờ"><input type="checkbox" class="tse-speaker-narrator"' + (s.narrator ? ' checked' : '') + '> Người dẫn</label>'
                    + '<button type="button" class="tse-icon" data-act="remove-speaker" aria-label="Xoá người nói">&times;</button>'
                    + '</div>';
            }).join('');
        },

        speakerOptions: function (selected) {
            var html = '<option value="">—</option>';
            this.speakers.forEach(function (s) {
                html += '<option value="' + esc(s.id) + '"' + (s.id === selected ? ' selected' : '') + '>' + esc(s.name || '(chưa đặt tên)') + '</option>';
            });
            return html;
        },

        marksHtml: function (text) {
            var chips = [];
            var m;
            MARK_RE.lastIndex = 0;
            var k = 0;
            while ((m = MARK_RE.exec(text))) {
                var snippet = m[2].length > 40 ? m[2].slice(0, 40) + '…' : m[2];
                chips.push('<span class="tse-mark">Câu ' + m[1] + ': “' + esc(snippet) + '”'
                    + '<button type="button" data-act="untag" data-k="' + (k++) + '" aria-label="Bỏ gắn">&times;</button></span>');
            }
            return chips.join('');
        },

        lineHtml: function (line, i) {
            return '<div class="tse-line' + (i === this.target ? ' is-target' : '') + '" data-i="' + i + '">'
                + '<button type="button" class="tse-idx" data-act="set-target" title="Đặt làm dòng đánh dấu tiếp theo">' + (i + 1) + '</button>'
                + '<div class="tse-time">'
                + '  <input type="text" class="tse-time-input" value="' + esc(formatTime(line.start)) + '" placeholder="--:--" aria-label="Mốc giờ">'
                + '  <button type="button" class="tse-icon" data-act="stamp" title="Gán thời điểm hiện tại của audio"><i class="fas fa-stopwatch"></i></button>'
                + '  <button type="button" class="tse-icon" data-act="play-line" title="Phát từ dòng này"><i class="fas fa-play"></i></button>'
                + '</div>'
                + '<select class="tse-line-speaker" style="border-left-color:' + this.speakerColor(line.speaker) + '">' + this.speakerOptions(line.speaker) + '</select>'
                + '<div class="tse-textwrap">'
                + '  <textarea class="tse-text" rows="1" placeholder="Nội dung">' + esc(line.text) + '</textarea>'
                + '  <div class="tse-marks">' + this.marksHtml(line.text) + '</div>'
                + '</div>'
                + '<div class="tse-line-actions">'
                + '  <button type="button" class="tse-icon" data-act="insert" title="Chèn dòng bên dưới"><i class="fas fa-plus"></i></button>'
                + '  <button type="button" class="tse-icon tse-icon--danger" data-act="remove-line" title="Xoá dòng"><i class="fas fa-trash"></i></button>'
                + '</div>'
                + '</div>';
        },

        renderLines: function () {
            var box = this.modal.querySelector('.tse-lines');
            var self = this;
            box.innerHTML = this.lines.length
                ? this.lines.map(function (l, i) { return self.lineHtml(l, i); }).join('')
                : '<p class="tse-empty">Chưa có dòng nào. Dán transcript ở mục "Dán nhanh" hoặc bấm "Thêm dòng".</p>';
            box.querySelectorAll('.tse-text').forEach(function (t) { self.autosize(t); });
            this.modal.querySelector('.tse-count').textContent = this.lines.length ? '(' + this.lines.length + ')' : '';
            this.renderFootInfo();
        },

        renderFootInfo: function () {
            var stamped = this.lines.filter(function (l) { return l.start !== null; }).length;
            this.modal.querySelector('.tse-foot-info').textContent = this.lines.length
                ? 'Đã đánh dấu mốc giờ ' + stamped + '/' + this.lines.length + ' dòng'
                : '';
        },

        renderTagPicker: function () {
            var pick = this.modal.querySelector('.tse-tag-pick');
            if (this.range) {
                var html = '<select class="tse-tag-number">';
                for (var n = this.range.from; n <= this.range.to; n++) html += '<option value="' + n + '">Câu ' + n + '</option>';
                pick.innerHTML = html + '</select>';
            } else {
                pick.innerHTML = '<input type="number" class="tse-tag-number" min="1" max="999" placeholder="Số câu">';
            }
            this.syncTagButton();
        },

        renderCoverage: function () {
            var box = this.modal.querySelector('.tse-coverage');
            if (!this.range) {
                box.innerHTML = '<span class="tse-tag-hint">Part chưa có câu hỏi — thêm câu hỏi trước để thấy danh sách số câu.</span>';
                return;
            }

            var tagged = {};
            this.lines.forEach(function (l) {
                var m;
                MARK_RE.lastIndex = 0;
                while ((m = MARK_RE.exec(l.text))) tagged[m[1]] = true;
            });

            var html = '';
            for (var n = this.range.from; n <= this.range.to; n++) {
                html += '<span class="tse-cov' + (tagged[n] ? ' is-done' : '') + '" title="' + (tagged[n] ? 'Đã gắn' : 'Chưa gắn') + '">' + n + '</span>';
            }
            box.innerHTML = html;
        },

        renderStampButton: function () {
            var btn = this.modal.querySelector('.tse-stamp');
            var has = this.lines.length > 0;
            btn.disabled = !has || !this.audioUrl;
            btn.querySelector('span').textContent = has ? ('Đánh dấu dòng ' + (this.target + 1)) : 'Đánh dấu';
            this.modal.querySelectorAll('.tse-line').forEach(function (row) {
                row.classList.toggle('is-target', Number(row.dataset.i) === this.target);
            }, this);
        },

        autosize: function (t) {
            t.style.height = 'auto';
            t.style.height = Math.max(34, t.scrollHeight) + 'px';
        },

        // ── Thao tác ───────────────────────────────────────────────────
        touch: function () {
            this.dirty = true;
        },

        stamp: function (index) {
            if (!this.lines[index] || !this.audioUrl) return;
            this.lines[index].start = Math.round(this.audio.currentTime * 100) / 100;
            this.touch();

            var row = this.modal.querySelector('.tse-line[data-i="' + index + '"]');
            if (row) {
                row.querySelector('.tse-time-input').value = formatTime(this.lines[index].start);
                row.classList.add('is-flash');
                window.setTimeout(function () { row.classList.remove('is-flash'); }, 500);
            }
            this.renderFootInfo();
        },

        stampTarget: function () {
            if (!this.lines.length) return;
            this.stamp(this.target);
            this.target = Math.min(this.lines.length - 1, this.target + 1);
            this.renderStampButton();

            var row = this.modal.querySelector('.tse-line[data-i="' + this.target + '"]');
            if (row) row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        },

        playFrom: function (index) {
            var line = this.lines[index];
            if (!line || line.start === null || !this.audioUrl) return;
            this.audio.currentTime = line.start;
            this.audio.play().catch(function () {});
        },

        captureSelection: function (textarea) {
            var row = textarea.closest('.tse-line');
            var s = textarea.selectionStart;
            var e = textarea.selectionEnd;
            this.selection = (row && e > s) ? { line: Number(row.dataset.i), start: s, end: e } : null;
            this.syncTagButton();
        },

        syncTagButton: function () {
            var btn = this.modal.querySelector('[data-act="tag"]');
            if (btn) btn.disabled = !this.selection;
        },

        tag: function () {
            var sel = this.selection;
            if (!sel || !this.lines[sel.line]) return;

            var input = this.modal.querySelector('.tse-tag-number');
            var n = parseInt(input && input.value, 10);
            if (!(n > 0)) { window.alert('Hãy chọn số câu.'); return; }

            var text = this.lines[sel.line].text;
            var chosen = text.slice(sel.start, sel.end);
            if (/\[\[|\]\]/.test(chosen)) {
                window.alert('Đoạn bôi đen đang chứa đoạn đã gắn. Hãy bỏ gắn đoạn cũ trước.');
                return;
            }

            // Bỏ khoảng trắng 2 đầu khỏi đoạn gắn cho gọn.
            var lead = chosen.length - chosen.replace(/^\s+/, '').length;
            var trail = chosen.length - chosen.replace(/\s+$/, '').length;
            var start = sel.start + lead;
            var end = sel.end - trail;
            if (end <= start) return;

            this.lines[sel.line].text = text.slice(0, start) + '[[q' + n + ']]' + text.slice(start, end) + '[[/q' + n + ']]' + text.slice(end);
            this.selection = null;
            this.touch();
            this.refreshLine(sel.line);
            this.renderCoverage();
            this.syncTagButton();

            // Gợi ý câu kế tiếp.
            if (input && input.tagName === 'SELECT' && input.selectedIndex < input.options.length - 1) input.selectedIndex += 1;
        },

        untag: function (lineIndex, k) {
            var line = this.lines[lineIndex];
            if (!line) return;
            var count = -1;
            MARK_RE.lastIndex = 0;
            line.text = line.text.replace(MARK_RE, function (all, n, inner) {
                count += 1;
                return count === k ? inner : all;
            });
            this.touch();
            this.refreshLine(lineIndex);
            this.renderCoverage();
        },

        refreshLine: function (index) {
            var row = this.modal.querySelector('.tse-line[data-i="' + index + '"]');
            if (!row) return;
            var t = row.querySelector('.tse-text');
            t.value = this.lines[index].text;
            this.autosize(t);
            row.querySelector('.tse-marks').innerHTML = this.marksHtml(this.lines[index].text);
        },

        defaultLineSpeaker: function (index) {
            for (var i = index; i >= 0; i--) if (this.lines[i] && this.lines[i].speaker) return this.lines[i].speaker;
            var first = this.speakers.find(function (s) { return !s.narrator; });
            return first ? first.id : null;
        },

        /** Dán "Tên: nội dung" -> dòng; tự thêm người nói mới; nhận mốc giờ đầu dòng. */
        parsePaste: function (raw, split) {
            var self = this;
            var out = [];
            var current = null;

            String(raw || '').split(/\r?\n/).forEach(function (rawLine) {
                var line = rawLine.trim();
                if (!line) return;

                var start = null;
                var tm = line.match(/^\[?(\d{1,2}:\d{2}(?::\d{2})?(?:\.\d+)?)\]?\s+/);
                if (tm) {
                    start = parseTime(tm[1]);
                    line = line.slice(tm[0].length);
                }

                var sm = line.match(/^([^:]{1,40}):\s*(.*)$/);
                if (sm && !/^\d/.test(sm[1]) && sm[1].split(/\s+/).length <= 4) {
                    current = self.ensureSpeaker(sm[1].trim());
                    line = sm[2].trim();
                }
                if (!line) return;

                var pieces = split ? line.split(/(?<=[.!?…])\s+(?=["'“(]?[A-Z0-9])/) : [line];
                pieces.forEach(function (piece, idx) {
                    piece = piece.trim();
                    if (!piece) return;
                    out.push({ speaker: current, text: piece, start: idx === 0 ? (start === undefined ? null : start) : null });
                });
            });

            return out;
        },

        ensureSpeaker: function (name) {
            var found = this.speakers.find(function (s) { return s.name.toLowerCase() === name.toLowerCase(); });
            if (found) return found.id;

            // Thay "Speaker N" mặc định chưa dùng trước khi thêm người mới.
            var used = {};
            this.lines.forEach(function (l) { used[l.speaker] = true; });
            var isNarrator = NARRATOR_RE.test(name);
            var spare = this.speakers.find(function (s) {
                return !used[s.id] && /^(Speaker \d+|Narrator)$/.test(s.name) && s.narrator === isNarrator && !s._claimed;
            });
            if (spare) {
                spare.name = name;
                spare._claimed = true;
                return spare.id;
            }

            var sp = { id: uid(), name: name, narrator: isNarrator, _claimed: true };
            this.speakers.push(sp);
            return sp.id;
        },

        applyPaste: function (mode) {
            var raw = this.modal.querySelector('.tse-paste-text').value;
            var split = this.modal.querySelector('.tse-split').checked;
            if (!raw.trim()) return;

            if (mode === 'replace' && this.lines.length && !window.confirm('Thay toàn bộ ' + this.lines.length + ' dòng hiện có?')) return;
            if (mode === 'replace') this.lines = [];

            this.speakers.forEach(function (s) { delete s._claimed; });
            var parsed = this.parsePaste(raw, split);
            this.speakers.forEach(function (s) { delete s._claimed; });

            this.lines = this.lines.concat(parsed);
            this.target = this.firstUnstamped();
            this.modal.querySelector('.tse-paste-text').value = '';
            this.modal.querySelector('.tse-paste').hidden = true;
            this.touch();
            this.renderAll();
        },

        // ── Sự kiện ────────────────────────────────────────────────────
        onClick: function (e) {
            var el = e.target.closest('[data-act]');
            if (!el || !this.modal.contains(el)) {
                if (e.target.classList.contains('tse-backdrop')) this.close();
                return;
            }

            var act = el.dataset.act;
            var row = el.closest('.tse-line');
            var i = row ? Number(row.dataset.i) : -1;

            switch (act) {
                case 'close': this.close(); break;
                case 'save': this.save(); break;
                case 'stamp-target': this.stampTarget(); break;
                case 'stamp': this.stamp(i); break;
                case 'play-line': this.playFrom(i); break;
                case 'set-target': this.target = i; this.renderStampButton(); break;
                case 'toggle-paste': {
                    var p = this.modal.querySelector('.tse-paste');
                    p.hidden = !p.hidden;
                    if (!p.hidden) this.modal.querySelector('.tse-paste-text').focus();
                    break;
                }
                case 'paste-replace': this.applyPaste('replace'); break;
                case 'paste-append': this.applyPaste('append'); break;
                case 'tag': this.tag(); break;
                case 'draft-restore': this.restoreDraft(); break;
                case 'seg-stamp': {
                    if (!this.audioUrl) break;
                    var segRow = el.closest('.tse-seg');
                    var tnow = Math.round(this.audio.currentTime * 100) / 100;
                    this.setSegEdge(segRow.dataset.n, el.dataset.edge, tnow);
                    segRow.querySelector('.tse-seg-input[data-edge="' + el.dataset.edge + '"]').value = formatTime(tnow);
                    segRow.classList.add('is-flash');
                    window.setTimeout(function () { segRow.classList.remove('is-flash'); }, 500);
                    break;
                }
                case 'seg-play': this.playSegment(el.closest('.tse-seg').dataset.n); break;
                case 'seg-clear': {
                    var clearRow = el.closest('.tse-seg');
                    delete this.segments[clearRow.dataset.n];
                    this.touch();
                    this.renderSegments();
                    break;
                }
                case 'draft-discard': this.discardDraft(); break;
                case 'untag': this.untag(i, Number(el.dataset.k)); break;
                case 'add-line':
                    this.lines.push({ speaker: this.defaultLineSpeaker(this.lines.length - 1), text: '', start: null });
                    this.touch();
                    this.renderLines();
                    this.renderStampButton();
                    var last = this.modal.querySelector('.tse-line:last-child .tse-text');
                    if (last) last.focus();
                    break;
                case 'insert':
                    this.lines.splice(i + 1, 0, { speaker: this.lines[i].speaker, text: '', start: null });
                    this.touch();
                    this.renderLines();
                    this.renderStampButton();
                    var next = this.modal.querySelector('.tse-line[data-i="' + (i + 1) + '"] .tse-text');
                    if (next) next.focus();
                    break;
                case 'remove-line':
                    if (this.lines[i].text.trim() && !window.confirm('Xoá dòng ' + (i + 1) + '?')) return;
                    this.lines.splice(i, 1);
                    this.target = Math.min(this.target, Math.max(0, this.lines.length - 1));
                    this.touch();
                    this.renderLines();
                    this.renderCoverage();
                    this.renderStampButton();
                    break;
                case 'add-speaker':
                    this.speakers.push({ id: uid(), name: 'Speaker ' + (this.speakers.filter(function (s) { return !s.narrator; }).length + 1), narrator: false });
                    this.touch();
                    this.renderSpeakers();
                    this.renderLines();
                    break;
                case 'remove-speaker': {
                    var sid = el.closest('.tse-speaker').dataset.sid;
                    var usedBy = this.lines.filter(function (l) { return l.speaker === sid; }).length;
                    if (usedBy && !window.confirm('Người nói này đang có ' + usedBy + ' dòng. Vẫn xoá?')) return;
                    this.speakers = this.speakers.filter(function (s) { return s.id !== sid; });
                    this.lines.forEach(function (l) { if (l.speaker === sid) l.speaker = null; });
                    this.touch();
                    this.renderSpeakers();
                    this.renderLines();
                    break;
                }
            }
        },

        onInput: function (e) {
            var t = e.target;
            var row = t.closest('.tse-line');

            if (t.classList.contains('tse-text') && row) {
                var i = Number(row.dataset.i);
                this.lines[i].text = t.value;
                this.autosize(t);
                row.querySelector('.tse-marks').innerHTML = this.marksHtml(t.value);
                this.selection = null;
                this.syncTagButton();
                this.touch();
            } else if (t.classList.contains('tse-speaker-name')) {
                var sp = this.speakers.find(function (s) { return s.id === t.closest('.tse-speaker').dataset.sid; });
                if (sp) sp.name = t.value;
                this.touch();
            }
        },

        onChange: function (e) {
            var t = e.target;
            var row = t.closest('.tse-line');

            if (t.classList.contains('tse-seg-input')) {
                var segVal = parseTime(t.value);
                if (segVal === undefined) {
                    t.classList.add('is-invalid');
                    return;
                }
                t.classList.remove('is-invalid');
                t.value = formatTime(segVal);
                this.setSegEdge(t.closest('.tse-seg').dataset.n, t.dataset.edge, segVal);
                return;
            }

            if (t.classList.contains('tse-time-input') && row) {
                var i = Number(row.dataset.i);
                var v = parseTime(t.value);
                if (v === undefined) {
                    t.classList.add('is-invalid');
                    return;
                }
                t.classList.remove('is-invalid');
                this.lines[i].start = v;
                t.value = formatTime(v);
                this.touch();
                this.renderFootInfo();
            } else if (t.classList.contains('tse-line-speaker') && row) {
                this.lines[Number(row.dataset.i)].speaker = t.value || null;
                t.style.borderLeftColor = this.speakerColor(t.value);
                this.touch();
            } else if (t.classList.contains('tse-speaker-narrator')) {
                var sp = this.speakers.find(function (s) { return s.id === t.closest('.tse-speaker').dataset.sid; });
                if (sp) sp.narrator = t.checked;
                this.touch();
                this.renderSpeakers();
                this.renderLines();
            } else if (t.classList.contains('tse-speaker-name')) {
                this.renderLines(); // cập nhật tên trong ô chọn người nói
            } else if (t.classList.contains('tse-rate')) {
                this.audio.playbackRate = parseFloat(t.value) || 1;
            }
        },

        onKey: function (e) {
            if (!this.modal || this.modal.hidden) return;

            if (e.key === 'Escape') {
                this.close();
                return;
            }

            var tag = (e.target && e.target.tagName) || '';
            var typing = /^(INPUT|TEXTAREA|SELECT)$/.test(tag) || (e.target && e.target.isContentEditable);
            if (typing || e.ctrlKey || e.metaKey || e.altKey) return;

            if (e.key === 'm' || e.key === 'M') {
                e.preventDefault();
                this.stampTarget();
            } else if (e.key === ' ' && this.audioUrl) {
                e.preventDefault();
                if (this.audio.paused) this.audio.play().catch(function () {});
                else this.audio.pause();
            }
        },
    };

    // Rời trang khi transcript đã "Lưu" nhưng đề chưa được lưu -> trình duyệt hỏi xác nhận.
    window.addEventListener('beforeunload', function (e) {
        if (!Editor.pendingSubmit && !(Editor.modal && !Editor.modal.hidden && Editor.dirty)) return;
        e.preventDefault();
        e.returnValue = '';
    });

    // Trình tạo đề gửi form bằng testForm.submit() (không phát sự kiện submit) -> bọc lại
    // để bỏ cảnh báo rời trang khi đề đang được lưu.
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('testForm');
        if (!form) return;
        var nativeSubmit = HTMLFormElement.prototype.submit;
        form.submit = function () {
            Editor.pendingSubmit = false;
            return nativeSubmit.call(form);
        };
        form.addEventListener('submit', function () { Editor.pendingSubmit = false; });
    });

    window.IeltsTranscriptEditor = {
        open: function (button) { Editor.open(button); },
        // Cho trang thử / kiểm thử.
        _editor: Editor,
        _parseTime: parseTime,
        _formatTime: formatTime,
    };
})();
