/**
 * review.js — trang CHỮA BÀI (Listening / Reading / Grammar / Vocabulary).
 *
 * Khung trang (navbar, tab Passage, thanh số câu, nút Câu trước / Câu tiếp
 * theo) là khung riêng của trang chữa bài, dựng sẵn bằng Blade
 * (attempt/review.blade.php). Phần THÂN câu hỏi vẫn dùng NGUYÊN bộ máy của
 * trang làm bài (state.js, renderers.js, layout.js) ở chế độ chỉ đọc —
 * không gọi ExamLayout.mount(), chỉ trỏ ExamLayout vào các vùng của khung mới.
 *
 * Dạng đã làm lại giao diện chữa bài:
 *   - True/False/Not Given, Yes/No/Not Given: icon ✔/✖ cạnh đáp án đã chọn,
 *     bóng đèn mở sidebar Answer Help (ReviewTfng + AnswerHelp).
 * Các dạng khác tạm dùng lớp tô đúng/sai cũ (ReviewMarker).
 *
 * Dữ liệu (attempt/review.blade.php):
 *   REVIEW_SECTION_DATA, REVIEW_SAVED_ANSWERS — cùng shape với trang làm bài
 *   REVIEW_RESULTS[questionId] = { slots: [...], explanation }
 *       slots = IeltsTestQuestion::gradeSlots(): mỗi phần tử
 *       { submitted, accepted: [], correct } (+ cell/parts cho table)
 *   ATTEMPT_HIGHLIGHTS — highlight + note học viên tạo lúc làm bài (chỉ xem)
 */

// renderers.js gọi AttemptAnswers.* khi render / khi input đổi. Ở trang chữa
// bài không được gửi gì lên server -> bản rỗng thay cho answers.js.
const AttemptAnswers = {
    onStatusChange() {},
    queueSave() {},
    saveSpeaking() { return Promise.resolve(null); },
    flushAllPending() {},
};

const RV_TFNG_TYPES = ['true_false_not_given', 'yes_no_not_given'];

function rvNorm(value) {
    return String(value == null ? '' : value).trim().toLowerCase();
}

function rvEscape(value) {
    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

const ReviewData = {
    results: window.REVIEW_RESULTS || {},

    resultOf(questionId) {
        return this.results[questionId] || {};
    },

    slotsOf(questionId) {
        const r = this.resultOf(questionId);
        return Array.isArray(r.slots) ? r.slots : [];
    },

    /** 'correct' | 'incorrect' | 'empty' */
    status(slot) {
        if (!slot) return 'empty';
        if (slot.correct) return 'correct';
        const submitted = slot.submitted;
        return (submitted === null || submitted === undefined || String(submitted).trim() === '')
            ? 'empty'
            : 'incorrect';
    },

    /** Trạng thái chung của cả câu (dùng cho câu nhiều ô). */
    entryStatus(entry) {
        const slots = this.slotsOf(entry.question.id);
        if (!slots.length) return 'empty';
        const statuses = slots.map((s) => this.status(s));
        if (statuses.every((s) => s === 'correct')) return 'correct';
        if (statuses.every((s) => s === 'empty')) return 'empty';
        return 'incorrect';
    },
};

/* =====================================================================
   Lớp tô đúng/sai CŨ — cho các dạng chưa làm lại giao diện chữa bài.
   ===================================================================== */
const ReviewMarker = {
    looseExplanations: [],

    /** Tô màu 1 ô + chèn đáp án đúng ngay sau nếu chưa đúng. */
    markField(el, slot, labelFn) {
        if (!el || el.dataset.rvDone) return;
        el.dataset.rvDone = '1';

        const st = ReviewData.status(slot);
        el.classList.add('graded-' + st);

        if (st !== 'correct') {
            const accepted = (slot && Array.isArray(slot.accepted)) ? slot.accepted : [];
            const key = document.createElement('span');
            key.className = 'rv-key';
            key.textContent = accepted.map(labelFn || ((x) => x)).join(' / ') || '—';
            el.insertAdjacentElement('afterend', key);
        }
    },

    markList(nodes, slots) {
        Array.from(nodes).forEach((node, i) => this.markField(node, slots[i]));
    },

    markOptions(scope, slots, question) {
        const accepted = [];
        slots.forEach((s) => (s.accepted || []).forEach((a) => accepted.push(rvNorm(a))));

        const saved = AttemptState.getAnswer(question.id);
        const picked = (Array.isArray(saved) ? saved : (saved ? [saved] : [])).map(rvNorm);

        scope.querySelectorAll('.exam-option-row').forEach((row, idx) => {
            const value = rvNorm(row.dataset.value);
            const letter = String.fromCharCode(97 + idx); // đáp án có thể lưu dạng "A"
            const isKey = accepted.includes(value) || accepted.includes(letter);

            if (isKey) row.classList.add('correct-highlight');
            else if (picked.includes(value)) row.classList.add('incorrect-highlight');
        });

        scope.classList.add('graded-' + (slots.every((s) => s.correct) && slots.length ? 'correct' : 'incorrect'));
    },

    markTable(scope, slots) {
        const byCell = {};
        slots.forEach((s) => { if (s.cell) byCell[s.cell] = s; });

        scope.querySelectorAll('tbody tr').forEach((tr, r) => {
            Array.from(tr.children).forEach((td, c) => {
                const inputs = td.querySelectorAll('.exam-blank-input');
                if (!inputs.length) return;
                const parts = (byCell[r + '-' + c] || {}).parts || [];
                inputs.forEach((input, i) => this.markField(input, parts[i]));
            });
        });
    },

    markMatrixRow(row, slot) {
        const accepted = ((slot && slot.accepted) || []).map(rvNorm);
        const picked = rvNorm(slot && slot.submitted);

        row.querySelectorAll('.exam-matrix-cell').forEach((td) => {
            const input = td.querySelector('input');
            const value = rvNorm(input && input.value);
            if (accepted.includes(value)) td.classList.add('rv-cell-key');
            else if (value && value === picked) td.classList.add('rv-cell-wrong');
        });

        row.classList.add('graded-' + ReviewData.status(slot));
    },

    decorateEntry(entry) {
        const q = entry.question;
        const slots = ReviewData.slotsOf(q.id);
        const scope = document.getElementById('exam-q-' + q.id);
        if (!scope) return;

        const type = q.type || '';

        if (scope.classList.contains('exam-matrix-row')) {
            this.markMatrixRow(scope, slots[0]);
        } else if (type === 'table_completion') {
            this.markTable(scope, slots);
        } else if (type === 'multiple_choice_single' || type === 'multiple_choice_multiple') {
            this.markOptions(scope, slots, q);
        } else if (type.indexOf('matching_') === 0) {
            const box = scope.classList.contains('exam-match-slot') ? scope : scope.querySelector('.exam-match-slot');
            const options = q.options || {};
            this.markField(box, slots[0], (key) => ExamRenderers.matchingLabel(options, key));
        } else if (type === 'drag_drop_disappear' || type === 'drag_drop_reuse') {
            this.markList(scope.querySelectorAll('.exam-dd-slot'), slots);
        } else {
            const inputs = scope.querySelectorAll('.exam-blank-input');
            if (inputs.length) this.markList(inputs, slots);
            else this.markField(scope.querySelector('.exam-short-input'), slots[0]);
        }

        this.appendExplanation(entry, scope);
    },

    appendExplanation(entry, scope) {
        const r = ReviewData.resultOf(entry.question.id);
        if (!r.explanation) return;

        const card = scope.closest('.exam-question-card');
        if (card) {
            if (card.querySelector('.rv-explanation')) return;
            const box = document.createElement('div');
            box.className = 'exam-explanation-box rv-explanation';
            box.innerHTML = '<div class="rv-explain-title">Explanation</div>' + r.explanation;
            card.appendChild(box);
            return;
        }

        // Ma trận / bảng kéo thả / ô trong bài đọc: không có thẻ riêng ->
        // gom xuống cuối cột câu hỏi.
        const label = entry.slotCount > 1
            ? entry.startNumber + '–' + entry.endNumber
            : String(entry.startNumber);
        this.looseExplanations.push({ label, html: r.explanation });
    },

    flushLooseExplanations(container) {
        if (!this.looseExplanations.length) return;

        const box = document.createElement('div');
        box.className = 'exam-explanation-box rv-loose-explanations';
        box.innerHTML = '<div class="rv-explain-title">Explanations</div>';
        this.looseExplanations.forEach((item) => {
            const row = document.createElement('div');
            row.className = 'rv-loose-item';
            row.innerHTML = '<strong>Question ' + item.label + ':</strong> ' + item.html;
            box.appendChild(row);
        });
        container.appendChild(box);
        this.looseExplanations = [];
    },
};

/* =====================================================================
   True/False/Not Given & Yes/No/Not Given
   Hàng: [số câu] [đề bài] [đáp án đã chọn] [✔/✖/—] [bóng đèn]
   ===================================================================== */
const ReviewTfng = {
    isTfng(entry) {
        return RV_TFNG_TYPES.includes(entry.question.type);
    },

    /** "Questions 9 – 13" ở đầu group. */
    decorateGroup(holder, groupEntries) {
        if (!groupEntries.length) return;

        const first = groupEntries[0].startNumber;
        const last = groupEntries[groupEntries.length - 1].endNumber;

        const heading = document.createElement('h3');
        heading.className = 'rvx-group-heading';
        heading.textContent = first === last ? ('Question ' + first) : ('Questions ' + first + ' – ' + last);
        holder.prepend(heading);

        // Tiêu đề group người ra đề gõ dạng "Questions 9–13" trùng với tiêu
        // đề vừa sinh (và có thể lệch số nếu question_number lưu sai) -> bỏ.
        const box = holder.querySelector('.exam-part-instructions');
        const title = box && box.querySelector(':scope > strong:first-child');
        if (title && /^\s*questions?\s*\d/i.test(title.textContent)) {
            const br = title.nextElementSibling;
            if (br && br.tagName === 'BR') br.remove();
            title.remove();
        }
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;
        card.dataset.rvxDone = '1';

        const select = card.querySelector('.exam-tfng-select');
        const text = card.querySelector('.exam-question-text');
        const slot = ReviewData.slotsOf(q.id)[0];
        const st = ReviewData.status(slot);

        if (select && select.options.length) select.options[0].textContent = '—';

        const row = document.createElement('div');
        row.className = 'rvx-tf-row';

        const num = document.createElement('span');
        num.className = 'rvx-num';
        num.textContent = entry.startNumber;

        const textWrap = document.createElement('div');
        textWrap.className = 'rvx-tf-text';
        if (text) textWrap.appendChild(text);

        const answer = document.createElement('div');
        answer.className = 'rvx-tf-answer';
        if (select) answer.appendChild(select);

        const status = document.createElement('span');
        status.className = 'rvx-status is-' + st;
        const statusLabel = { correct: 'Đúng', incorrect: 'Sai', empty: 'Chưa làm' }[st];
        status.setAttribute('role', 'img');
        status.setAttribute('aria-label', statusLabel);
        status.title = statusLabel;
        status.innerHTML = '<i class="fas ' + ({ correct: 'fa-check', incorrect: 'fa-times', empty: 'fa-minus' }[st])
            + '" aria-hidden="true"></i>';

        const help = document.createElement('button');
        help.type = 'button';
        help.className = 'rvx-help rv-allow';
        help.dataset.questionId = q.id;
        help.setAttribute('aria-label', 'Xem đáp án và giải thích câu ' + entry.startNumber);
        help.title = 'Answer Help';
        help.innerHTML = '<i class="far fa-lightbulb" aria-hidden="true"></i>';

        row.appendChild(num);
        row.appendChild(textWrap);
        row.appendChild(answer);
        row.appendChild(status);
        row.appendChild(help);

        card.innerHTML = '';
        card.classList.add('rvx-tf-card', 'rvx-is-' + st);
        card.appendChild(row);
    },
};

/* =====================================================================
   Sidebar Answer Help — trượt từ phải sang, đè lên trang.
   Nội dung hiện tại: đề bài, đáp án của học viên, đáp án đúng, giải thích.
   ===================================================================== */
const AnswerHelp = {
    root: null,
    body: null,
    title: null,
    entry: null,
    returnFocus: null,

    init() {
        this.root = document.getElementById('rvxDrawer');
        if (!this.root) return;
        this.body = document.getElementById('rvxDrawerBody');
        this.title = document.getElementById('rvxDrawerTitle');

        this.root.querySelectorAll('[data-drawer-close]').forEach((el) => {
            el.addEventListener('click', () => this.close());
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) this.close();
        });
    },

    isOpen() {
        return !!(this.root && !this.root.hidden && this.root.classList.contains('is-open'));
    },

    choiceLabel(entry, value) {
        if (value === null || value === undefined || String(value).trim() === '') return '';
        return RV_TFNG_TYPES.includes(entry.question.type) ? String(value).toUpperCase() : String(value);
    },

    render(entry) {
        const q = entry.question;
        const slots = ReviewData.slotsOf(q.id);
        const st = ReviewData.entryStatus(entry);
        const explanation = ReviewData.resultOf(q.id).explanation;

        const numberLabel = entry.slotCount > 1
            ? ('Câu ' + entry.startNumber + ' – ' + entry.endNumber)
            : ('Câu ' + entry.startNumber);

        this.title.textContent = 'Answer Help · ' + numberLabel;

        const yours = slots.map((s) => this.choiceLabel(entry, s.submitted)).filter(Boolean).join(', ');
        const keys = slots.map((s) => (s.accepted || []).map((a) => this.choiceLabel(entry, a)).join(' / '))
            .filter(Boolean).join(', ');

        const statusText = { correct: 'Bạn trả lời đúng', incorrect: 'Bạn trả lời sai', empty: 'Bạn chưa trả lời câu này' }[st];

        this.body.innerHTML = ''
            + '<div class="rvx-help-status is-' + st + '">'
            + '  <i class="fas ' + ({ correct: 'fa-check-circle', incorrect: 'fa-times-circle', empty: 'fa-minus-circle' }[st]) + '" aria-hidden="true"></i>'
            + '  <span>' + statusText + '</span>'
            + '</div>'
            + (q.text ? '<div class="rvx-help-question">' + q.text + '</div>' : '')
            + '<dl class="rvx-help-answers">'
            + '  <div><dt>Đáp án của bạn</dt><dd class="is-' + st + '">' + (yours ? rvEscape(yours) : 'Chưa trả lời') + '</dd></div>'
            + '  <div><dt>Đáp án đúng</dt><dd class="is-key">' + (keys ? rvEscape(keys) : '—') + '</dd></div>'
            + '</dl>'
            + '<section class="rvx-help-explain">'
            + '  <h4>Giải thích</h4>'
            + (explanation
                ? '<div class="rvx-help-explain-body">' + explanation + '</div>'
                : '<p class="rvx-help-empty">Chưa có giải thích cho câu này.</p>')
            + '</section>';

        this.body.scrollTop = 0;
    },

    open(entry, trigger) {
        if (!this.root) return;
        this.entry = entry;
        this.render(entry);

        if (!this.isOpen()) {
            this.returnFocus = trigger || document.activeElement;
            this.root.hidden = false;
            // Kích hoạt transition sau khi bỏ hidden.
            window.requestAnimationFrame(() => this.root.classList.add('is-open'));
            document.body.classList.add('rvx-drawer-open');
            const closeBtn = this.root.querySelector('.rvx-drawer-close');
            if (closeBtn) closeBtn.focus();
        }
    },

    /** Đang mở mà chuyển câu -> đổi nội dung theo câu mới. */
    follow(entry) {
        if (this.isOpen() && entry && (!this.entry || this.entry.question.id !== entry.question.id)) {
            this.entry = entry;
            this.render(entry);
        }
    },

    close() {
        if (!this.root || this.root.hidden) return;
        this.root.classList.remove('is-open');
        document.body.classList.remove('rvx-drawer-open');

        const done = () => { this.root.hidden = true; };
        const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) done(); else window.setTimeout(done, 260);

        if (this.returnFocus && document.body.contains(this.returnFocus)) this.returnFocus.focus();
        this.returnFocus = null;
    },
};

/**
 * Chặn mọi thao tác làm bài trong cột câu hỏi / bài đọc (kéo thả matching,
 * drag&drop, click ô...). Input đã bị disable, đây là lớp chặn thứ 2 cho các
 * thành phần không phải input (chip, ô thả). Vẫn cho audio, link, nút .rv-allow
 * và bôi đen chữ hoạt động bình thường.
 */
function rvBlockInteractions(root) {
    const guard = (e) => {
        const t = e.target;
        if (!t || !t.closest) return;
        if (t.closest('.rv-allow, audio, video, a')) return;
        if (t.closest('.exam-questions, .exam-context')) e.stopPropagation();
    };

    ['pointerdown', 'mousedown', 'touchstart', 'click', 'change', 'input'].forEach((type) => {
        root.addEventListener(type, guard, true);
    });

    root.addEventListener('dragstart', (e) => {
        if (e.target.closest && e.target.closest('.exam-questions, .exam-context')) e.preventDefault();
    }, true);
}

/**
 * Thanh kéo giữa 2 cột (giống trang làm bài): kéo chuột / chạm, hoặc chọn
 * thanh rồi bấm ← → để đổi độ rộng cột trái trong khoảng 25% – 65%.
 */
function rvBindResizer(body, left, handle) {
    if (!body || !left || !handle) return;

    const MIN_PCT = 25;
    const MAX_PCT = 65;
    let dragging = false;

    const setPct = (pct) => {
        pct = Math.min(MAX_PCT, Math.max(MIN_PCT, pct));
        left.style.flexBasis = pct + '%';
        handle.setAttribute('aria-valuenow', String(Math.round(pct)));
    };

    const currentPct = () => (left.getBoundingClientRect().width / body.getBoundingClientRect().width) * 100;

    const onDown = (e) => {
        dragging = true;
        handle.classList.add('is-dragging');
        document.body.classList.add('is-resizing-exam');
        e.preventDefault();
    };

    const onMove = (e) => {
        if (!dragging) return;
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const rect = body.getBoundingClientRect();
        setPct(((clientX - rect.left) / rect.width) * 100);
    };

    const onUp = () => {
        if (!dragging) return;
        dragging = false;
        handle.classList.remove('is-dragging');
        document.body.classList.remove('is-resizing-exam');
    };

    handle.setAttribute('aria-valuemin', String(MIN_PCT));
    handle.setAttribute('aria-valuemax', String(MAX_PCT));

    handle.addEventListener('mousedown', onDown);
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onUp);
    handle.addEventListener('touchstart', onDown, { passive: false });
    document.addEventListener('touchmove', onMove, { passive: false });
    document.addEventListener('touchend', onUp);

    handle.addEventListener('keydown', (e) => {
        if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
        e.preventDefault();
        setPct(currentPct() + (e.key === 'ArrowRight' ? 3 : -3));
    });

    // Bấm đúp -> trả về độ rộng mặc định.
    handle.addEventListener('dblclick', () => {
        left.style.flexBasis = '';
        handle.removeAttribute('aria-valuenow');
    });
}

/** Highlight + note: chỉ hiển thị lại (hover xem note), không thêm/sửa/xoá. */
function rvInitReadOnlyHighlights() {
    if (!window.ExamHighlights) return;
    ExamHighlights.init();
    ExamHighlights.isLocked = () => true;
    ExamHighlights.showToolbarAt = () => {};
}

document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('attemptRoot');
    if (!root) return;

    const els = {
        body: root.querySelector('.rvx-body'),
        context: document.getElementById('rvxContext'),
        questions: document.getElementById('rvxQuestions'),
        partTabs: document.getElementById('rvxPartTabs'),
        pager: document.getElementById('rvxPager'),
        pagerTrack: document.getElementById('rvxPagerTrack'),
        prev: document.getElementById('rvxPrev'),
        next: document.getElementById('rvxNext'),
    };

    buildAttemptModel(window.REVIEW_SECTION_DATA || {}, window.REVIEW_SAVED_ANSWERS || {});

    if (!AttemptState.parts.length || !AttemptState.entries.length) {
        els.questions.innerHTML = '<div class="exam-loading">Phần này chưa có câu hỏi.</div>';
        return;
    }

    // Trỏ ExamLayout vào khung mới thay cho mount(). audioIndicator là phần
    // tử giả: bindListeningAudio() có gọi tới nhưng khung chữa bài không cần.
    ExamLayout.root = root;
    ExamLayout.els = {
        body: els.body,
        context: els.context,
        questions: els.questions,
        resizer: null,
        audioIndicator: document.createElement('div'),
    };

    rvInitReadOnlyHighlights();
    AnswerHelp.init();

    const isListening = AttemptState.skill === 'listening';
    const entries = AttemptState.entries;
    const lastNumber = entries[entries.length - 1].endNumber;
    let currentNumber = 1;
    let transcriptOpen = false;

    function entryOfNumber(n) {
        return entries.find((e) => n >= e.startNumber && n <= e.endNumber) || null;
    }

    // ── Tab Passage / Part ─────────────────────────────────────────────
    function renderPartTabs() {
        els.partTabs.innerHTML = '';
        AttemptState.parts.forEach((part, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'rvx-part-tab' + (idx === AttemptState.part.index ? ' is-active' : '');
            btn.textContent = (isListening ? 'Part ' : 'Passage ') + (idx + 1);
            if (part.title) btn.title = part.title;
            btn.setAttribute('aria-pressed', idx === AttemptState.part.index ? 'true' : 'false');
            btn.addEventListener('click', () => {
                const first = entries.find((e) => e.partIndex === idx);
                if (first) goTo(first.startNumber);
            });
            els.partTabs.appendChild(btn);
        });
    }

    // ── Thanh số câu ───────────────────────────────────────────────────
    function buildPager() {
        els.pagerTrack.innerHTML = '';
        for (let n = 1; n <= lastNumber; n++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'rvx-pager-num';
            btn.textContent = n;
            btn.dataset.n = n;
            btn.setAttribute('role', 'listitem');
            btn.setAttribute('aria-label', 'Câu ' + n);
            btn.addEventListener('click', () => goTo(n));
            els.pagerTrack.appendChild(btn);
        }

        els.pager.querySelectorAll('.rvx-pager-arrow').forEach((arrow) => {
            arrow.addEventListener('click', () => goTo(currentNumber + parseInt(arrow.dataset.step, 10)));
        });
    }

    function syncPager() {
        els.pagerTrack.querySelectorAll('.rvx-pager-num').forEach((btn) => {
            const on = parseInt(btn.dataset.n, 10) === currentNumber;
            btn.classList.toggle('is-active', on);
            if (on) {
                btn.setAttribute('aria-current', 'true');
                const track = els.pagerTrack;
                const target = btn.offsetLeft - (track.clientWidth / 2) + (btn.offsetWidth / 2);
                track.scrollTo({ left: Math.max(0, target), behavior: 'smooth' });
            } else {
                btn.removeAttribute('aria-current');
            }
        });

        const atStart = currentNumber <= 1;
        const atEnd = currentNumber >= lastNumber;
        els.pager.querySelector('[data-step="-1"]').disabled = atStart;
        els.pager.querySelector('[data-step="1"]').disabled = atEnd;
        els.prev.disabled = atStart;
        els.next.disabled = atEnd;
    }

    // ── Dựng 1 Part ────────────────────────────────────────────────────
    /** Mỗi group dựng vào 1 khối riêng để gắn tiêu đề "Questions x – y". */
    function renderGroups(part) {
        const container = els.questions;
        container.innerHTML = '';
        const groups = Array.isArray(part.groups) ? part.groups : [];

        if (!groups.length) {
            ExamLayout.renderQuestions(part);
            return;
        }

        groups.forEach((group) => {
            const holder = document.createElement('div');
            holder.className = 'rvx-group';
            holder.dataset.type = group.question_type || '';
            if (RV_TFNG_TYPES.includes(group.question_type)) holder.classList.add('rvx-group--tfng');
            container.appendChild(holder);

            ExamLayout.els.questions = holder;
            ExamLayout.renderQuestions(Object.assign({}, part, { groups: [group] }));
            // Khối audio Listening chỉ dựng 1 lần ở group đầu.
            ExamLayout._pendingListeningContext = null;
        });

        ExamLayout.els.questions = container;
    }

    /** Listening: đưa audio sang cột trái, kèm nút hiện/ẩn transcript. */
    function placeListeningBlock(part) {
        if (!isListening) return;

        els.context.classList.remove('hidden');
        els.context.innerHTML = '';

        const block = els.questions.querySelector('.exam-listening-block');
        if (block) els.context.appendChild(block);

        if (!part.transcript) return;

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'rv-transcript-toggle rv-allow';

        const box = document.createElement('div');
        box.className = 'rv-transcript';
        box.innerHTML = part.transcript;

        const sync = () => {
            btn.innerHTML = transcriptOpen
                ? '<i class="fas fa-eye-slash"></i> Ẩn transcript'
                : '<i class="fas fa-file-alt"></i> Hiện transcript';
            box.classList.toggle('rv-hidden', !transcriptOpen);
        };

        btn.addEventListener('click', () => { transcriptOpen = !transcriptOpen; sync(); });
        sync();
        els.context.appendChild(btn);
        els.context.appendChild(box);
    }

    function renderPart() {
        const part = AttemptState.currentPart();
        if (!part) return;

        ExamLayout.renderContext(part);
        renderGroups(part);
        placeListeningBlock(part);

        const partIndex = AttemptState.part.index;
        const partEntries = entries.filter((e) => e.partIndex === partIndex);

        partEntries.forEach((entry) => {
            if (ReviewTfng.isTfng(entry)) ReviewTfng.decorate(entry);
            else ReviewMarker.decorateEntry(entry);
        });
        ReviewMarker.flushLooseExplanations(els.questions);

        els.questions.querySelectorAll('.rvx-group--tfng').forEach((holder) => {
            const groupEntries = partEntries.filter((e) => holder.contains(document.getElementById('exam-q-' + e.question.id)));
            ReviewTfng.decorateGroup(holder, groupEntries);
        });

        ExamLayout.lockAllInputs();
        els.context.scrollTop = 0;
        els.questions.scrollTop = 0;
        renderPartTabs();
    }

    function showPart(partIndex) {
        // Phần tử audio bị gỡ khỏi DOM vẫn có thể phát tiếp -> luôn dừng trước.
        root.querySelectorAll('audio').forEach((a) => { if (!a.paused) a.pause(); });
        AttemptState.part.index = partIndex;
        renderPart();
    }

    // ── Câu đang xem ───────────────────────────────────────────────────
    function markCurrent(entry, scroll) {
        root.querySelectorAll('.rvx-current').forEach((el) => el.classList.remove('rvx-current'));
        const card = document.getElementById('exam-q-' + entry.question.id);
        if (!card) return;

        card.classList.add('rvx-current');
        if (scroll) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function goTo(n, options) {
        const opts = options || {};
        n = Math.min(lastNumber, Math.max(1, n));
        const entry = entryOfNumber(n);
        if (!entry) return;

        currentNumber = n;
        AttemptState.current.questionId = entry.question.id;

        if (entry.partIndex !== AttemptState.part.index) showPart(entry.partIndex);

        markCurrent(entry, opts.scroll !== false);
        syncPager();
        AnswerHelp.follow(entry);
    }

    els.prev.addEventListener('click', () => goTo(currentNumber - 1));
    els.next.addEventListener('click', () => goTo(currentNumber + 1));

    // Bấm vào 1 câu -> thành câu đang xem; bấm bóng đèn -> mở Answer Help.
    // Đăng ký TRƯỚC lớp chặn thao tác (cùng pha capture) để luôn nhận được.
    root.addEventListener('click', (e) => {
        const t = e.target;
        if (!t || !t.closest) return;

        const help = t.closest('.rvx-help');
        const card = t.closest('[id^="exam-q-"]');
        if (!help && !card) return;

        const questionId = help ? help.dataset.questionId : card.id.replace('exam-q-', '');
        const entry = entries.find((x) => String(x.question.id) === String(questionId));
        if (!entry) return;

        goTo(entry.startNumber, { scroll: false });
        if (help) AnswerHelp.open(entry, help);
    }, true);

    rvBlockInteractions(root);
    rvBindResizer(els.body, root.querySelector('.rvx-left'), document.getElementById('rvxResizer'));

    buildPager();
    renderPart();

    // Mở từ trang kết quả: ?q=N -> nhảy tới câu N.
    const fromQuery = parseInt(new URLSearchParams(window.location.search).get('q'), 10);
    goTo(fromQuery > 0 ? fromQuery : 1, { scroll: fromQuery > 0 });
});
