/**
 * highlights.js — bôi highlight + ghi chú trong bài đọc.
 *
 * Neo vị trí bằng offset TEXT THUẦN của passage thuộc 1 part, kèm chuỗi
 * đối chiếu. Chỉ hoạt động trong cột ngữ cảnh (.exam-context-passage) —
 * cột câu hỏi có input/kéo thả, chèn <mark> vào đó sẽ làm hỏng bài làm.
 */
const ExamHighlights = {
    byPart: {},
    root: null,
    partId: null,
    toolbar: null,
    modal: null,
    activeId: null,
    tip: null,
    tipTimer: null,
    tipHover: false,
    pendingOffsets: null,

    init() {
        const data = window.ATTEMPT_HIGHLIGHTS || {};
        this.byPart = {};
        Object.keys(data).forEach((k) => { this.byPart[String(k)] = (data[k] || []).slice(); });

        this.ensureToolbar();
        this.ensureModal();
        this.bindGlobal();
    },

    isLocked() {
        return !!document.querySelector('.exam-questions.is-locked');
    },

    /** layout.renderContext() gọi sau khi vẽ xong passage của part. */
    mountRoot(rootEl, partId) {
        this.root = rootEl;
        this.partId = String(partId || '');
        rootEl.setAttribute('data-hl-root', '1');
        this.hideToolbar();
        this.renderAll();
    },

    list() {
        return this.byPart[this.partId] || (this.byPart[this.partId] = []);
    },

    // ── Text offsets ────────────────────────────────────────────────
    textNodes(root) {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        const out = [];
        while (walker.nextNode()) out.push(walker.currentNode);
        return out;
    },

    offsetsFromRange(root, range) {
        const pre = range.cloneRange();
        pre.selectNodeContents(root);
        pre.setEnd(range.startContainer, range.startOffset);
        const start = pre.toString().length;
        const end = start + range.toString().length;
        return end > start ? { start: start, end: end } : null;
    },

    rangeFromOffsets(root, start, end) {
        const nodes = this.textNodes(root);
        const range = document.createRange();
        let pos = 0;
        let started = false;

        for (let i = 0; i < nodes.length; i++) {
            const node = nodes[i];
            const len = node.nodeValue.length;

            if (!started && start < pos + len) {
                range.setStart(node, start - pos);
                started = true;
            }
            if (started && end <= pos + len) {
                range.setEnd(node, end - pos);
                return range;
            }
            pos += len;
        }
        return null;
    },

    // ── Vẽ / gỡ ─────────────────────────────────────────────────────
    renderAll() {
        if (!this.root) return;
        this.list()
            .slice()
            .sort((a, b) => a.start - b.start)
            .forEach((item) => this.wrap(item));
    },

    wrap(item) {
        const range = this.rangeFromOffsets(this.root, item.start, item.end);
        if (!range) return;

        // Passage đã bị sửa -> bỏ qua, không tô nhầm chỗ.
        if (range.toString().trim() !== String(item.text || '').trim()) return;

        const nodes = this.textNodes(this.root).filter((n) => range.intersectsNode(n));

        nodes.forEach((node) => {
            let s = 0;
            let e = node.nodeValue.length;
            if (node === range.startContainer) s = range.startOffset;
            if (node === range.endContainer) e = range.endOffset;
            if (e <= s) return;

            const target = s > 0 ? node.splitText(s) : node;
            if (e - s < target.nodeValue.length) target.splitText(e - s);

            const mark = document.createElement('mark');
            mark.className = 'exam-hl' + (item.note ? ' has-note' : '');
            mark.dataset.hlId = item.id;
            target.parentNode.insertBefore(mark, target);
            mark.appendChild(target);
        });
    },

    unwrap(id) {
        if (!this.root) return;
        this.root.querySelectorAll('mark.exam-hl[data-hl-id="' + id + '"]').forEach((mark) => {
            const parent = mark.parentNode;
            while (mark.firstChild) parent.insertBefore(mark.firstChild, mark);
            parent.removeChild(mark);
            parent.normalize();
        });
    },

    refreshMarkClass(item) {
        if (!this.root) return;
        this.root.querySelectorAll('mark.exam-hl[data-hl-id="' + item.id + '"]').forEach((mark) => {
            mark.classList.toggle('has-note', !!item.note);
        });
    },

    find(id) {
        return this.list().find((h) => String(h.id) === String(id)) || null;
    },

    // ── Toolbar ─────────────────────────────────────────────────────
    ensureToolbar() {
        if (this.toolbar) return this.toolbar;

        const bar = document.createElement('div');
        bar.className = 'exam-hl-toolbar hidden';
        bar.innerHTML = ''
            + '<button type="button" data-act="highlight"><i class="fas fa-highlighter"></i> Highlight</button>'
            + '<button type="button" data-act="note"><i class="fas fa-sticky-note"></i> Note</button>'
            + '<button type="button" data-act="delete"><i class="fas fa-trash-alt"></i> Xóa</button>';
        document.body.appendChild(bar);

        bar.addEventListener('mousedown', (e) => e.preventDefault()); // giữ selection
        bar.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;
            this.onAction(btn.dataset.act);
        });

        this.toolbar = bar;
        return bar;
    },

    showToolbarAt(rect, mode) {
        const bar = this.ensureToolbar();
        bar.classList.remove('hidden');
        bar.dataset.mode = mode;

        const top = window.scrollY + rect.top - bar.offsetHeight - 10;
        const left = window.scrollX + rect.left + (rect.width / 2) - (bar.offsetWidth / 2);
        bar.style.top = Math.max(8, top) + 'px';
        bar.style.left = Math.max(8, left) + 'px';
    },

    hideToolbar() {
        if (this.toolbar) this.toolbar.classList.add('hidden');
        this.activeId = null;
        this.pendingOffsets = null;
    },

    bindGlobal() {
        // Bôi xong -> hiện toolbar.
        document.addEventListener('mouseup', (e) => this.onSelectEnd(e));
        document.addEventListener('touchend', (e) => this.onSelectEnd(e));

        // Bấm vào vùng đã bôi -> toolbar cho chính highlight đó.
        document.addEventListener('click', (e) => {
            const mark = e.target.closest('mark.exam-hl');
            if (mark && this.root && this.root.contains(mark)) {
                this.activeId = mark.dataset.hlId;
                this.pendingOffsets = null;
                this.showToolbarAt(mark.getBoundingClientRect(), 'existing');
                return;
            }
            if (!e.target.closest('.exam-hl-toolbar') && !e.target.closest('.exam-hl-modal')) {
                this.hideToolbar();
            }
        });

                // Hover vào vùng đã bôi có ghi chú -> hiện tooltip.
        document.addEventListener('mouseover', (e) => {
            const mark = e.target.closest && e.target.closest('mark.exam-hl.has-note');
            if (!mark || !this.root || !this.root.contains(mark)) return;

            const item = this.find(mark.dataset.hlId);
            if (!item || !item.note) return;

            window.clearTimeout(this.tipTimer);
            this.showTip(mark, item);
        });

        document.addEventListener('mouseout', (e) => {
            const mark = e.target.closest && e.target.closest('mark.exam-hl');
            if (mark) this.hideTip();
        });

        // Cuộn trang thì ẩn đi, tránh tooltip đứng sai chỗ.
        window.addEventListener('scroll', () => {
            this.tipHover = false;
            if (this.tip) this.tip.classList.add('hidden');
        }, true);
    },

    onSelectEnd(e) {
        if (e.target && e.target.closest && e.target.closest('.exam-hl-toolbar')) return;
        if (this.isLocked() || !this.root) return;

        window.setTimeout(() => {
            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || sel.rangeCount === 0) return;

            const range = sel.getRangeAt(0);
            if (!this.root.contains(range.commonAncestorContainer)) return;

            const offsets = this.offsetsFromRange(this.root, range);
            if (!offsets) return;

            this.activeId = null;
            this.pendingOffsets = {
                start: offsets.start,
                end: offsets.end,
                text: range.toString(),
            };
            this.showToolbarAt(range.getBoundingClientRect(), 'selection');
        }, 0);
    },

    // ── Hành động ───────────────────────────────────────────────────
    onAction(act) {
        if (this.isLocked()) return;

        if (act === 'delete') return this.doDelete();
        if (act === 'highlight') return this.doHighlight(false);
        if (act === 'note') return this.doNote();
    },

    doHighlight(silent) {
        if (this.activeId) { this.hideToolbar(); return Promise.resolve(this.find(this.activeId)); }
        if (!this.pendingOffsets) return Promise.resolve(null);

        const payload = {
            part_id: this.partId,
            start: this.pendingOffsets.start,
            end: this.pendingOffsets.end,
            text: this.pendingOffsets.text,
        };

        return this.post(window.ATTEMPT_HL_STORE_URL, payload).then((data) => {
            const item = {
                id: data.id,
                start: payload.start,
                end: payload.end,
                text: payload.text,
                note: null,
            };
            this.list().push(item);

            window.getSelection().removeAllRanges();
            this.wrap(item);
            if (!silent) this.hideToolbar();
            return item;
        });
    },

    doNote() {
        const existing = this.activeId ? this.find(this.activeId) : null;

        if (existing) {
            this.hideToolbar();
            this.openModal(existing);
            return;
        }

        this.doHighlight(true).then((item) => {
            this.hideToolbar();
            if (item) this.openModal(item);
        });
    },

    doDelete() {
        const ids = [];

        if (this.activeId) {
            ids.push(this.activeId);
        } else if (this.pendingOffsets) {
            // Xoá mọi highlight giao với vùng đang bôi.
            this.list().forEach((h) => {
                if (h.start < this.pendingOffsets.end && h.end > this.pendingOffsets.start) ids.push(h.id);
            });
        }

        this.hideToolbar();
        window.getSelection().removeAllRanges();

        ids.forEach((id) => {
            this.del(window.ATTEMPT_HL_BASE_URL + '/' + id).then(() => {
                this.unwrap(id);
                const list = this.list();
                const idx = list.findIndex((h) => String(h.id) === String(id));
                if (idx >= 0) list.splice(idx, 1);
            });
        });
    },

    // ── Modal ghi chú ───────────────────────────────────────────────
    ensureModal() {
        if (this.modal) return this.modal;

        const overlay = document.createElement('div');
        overlay.className = 'exam-hl-modal hidden';
        overlay.innerHTML = ''
            + '<div class="exam-hl-modal-card">'
            + '  <div class="exam-hl-modal-head">'
            + '    <span><i class="fas fa-sticky-note"></i> Thêm ghi chú</span>'
            + '    <button type="button" class="exam-hl-modal-close">&times;</button>'
            + '  </div>'
            + '  <div class="exam-hl-modal-quote"></div>'
            + '  <textarea class="exam-hl-modal-text" maxlength="2000" placeholder="Nhập ghi chú của bạn tại đây..."></textarea>'
            + '  <div class="exam-hl-modal-actions">'
            + '    <button type="button" class="exam-hl-modal-cancel">Cancel</button>'
            + '    <button type="button" class="exam-hl-modal-save">Save</button>'
            + '  </div>'
            + '</div>';
        document.body.appendChild(overlay);

        const close = () => overlay.classList.add('hidden');
        overlay.querySelector('.exam-hl-modal-close').addEventListener('click', close);
        overlay.querySelector('.exam-hl-modal-cancel').addEventListener('click', close);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });

        overlay.querySelector('.exam-hl-modal-save').addEventListener('click', () => {
            const id = overlay.dataset.hlId;
            const item = this.find(id);
            if (!item) return close();

            const note = overlay.querySelector('.exam-hl-modal-text').value.trim();
            this.post(window.ATTEMPT_HL_BASE_URL + '/' + id, { note: note }).then(() => {
                item.note = note || null;
                this.refreshMarkClass(item);
                close();
            });
        });

        this.modal = overlay;
        return overlay;
    },

    openModal(item) {
        const overlay = this.ensureModal();
        overlay.dataset.hlId = item.id;
        overlay.querySelector('.exam-hl-modal-quote').textContent = item.text || '';
        overlay.querySelector('.exam-hl-modal-text').value = item.note || '';
        overlay.classList.remove('hidden');
        overlay.querySelector('.exam-hl-modal-text').focus();
    },

        // ── Tooltip ghi chú (hover) ─────────────────────────────────────
    ensureTip() {
        if (this.tip) return this.tip;

        const tip = document.createElement('div');
        tip.className = 'exam-hl-tip hidden';
        document.body.appendChild(tip);

        // Rê chuột vào chính tooltip thì giữ nguyên, để đọc/cuộn ghi chú dài.
        tip.addEventListener('mouseenter', () => { this.tipHover = true; });
        tip.addEventListener('mouseleave', () => { this.tipHover = false; this.hideTip(); });

        this.tip = tip;
        return tip;
    },

    showTip(mark, item) {
        const tip = this.ensureTip();
        tip.textContent = item.note || '';
        tip.classList.remove('hidden');

        const rect = mark.getBoundingClientRect();
        const top = window.scrollY + rect.top - tip.offsetHeight - 8;
        const left = window.scrollX + rect.left + (rect.width / 2) - (tip.offsetWidth / 2);

        // Không đủ chỗ phía trên -> lật xuống dưới.
        tip.classList.toggle('is-below', top < window.scrollY + 8);
        tip.style.top = (top < window.scrollY + 8 ? window.scrollY + rect.bottom + 8 : top) + 'px';
        tip.style.left = Math.max(8, Math.min(left, window.innerWidth - tip.offsetWidth - 8)) + 'px';
    },

    hideTip() {
        if (this.tipHover) return;
        window.clearTimeout(this.tipTimer);
        this.tipTimer = window.setTimeout(() => {
            if (!this.tipHover && this.tip) this.tip.classList.add('hidden');
        }, 120);
    },

    // ── HTTP ────────────────────────────────────────────────────────
    headers() {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.ATTEMPT_CSRF
                || document.querySelector('meta[name="csrf-token"]')?.content || '',
        };
    },

    post(url, body) {
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: this.headers(),
            body: JSON.stringify(body),
        }).then((r) => r.ok ? r.json() : Promise.reject(r));
    },

    del(url) {
        return fetch(url, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: this.headers(),
        }).then((r) => r.ok ? r.json() : Promise.reject(r));
    },
};

window.ExamHighlights = ExamHighlights;