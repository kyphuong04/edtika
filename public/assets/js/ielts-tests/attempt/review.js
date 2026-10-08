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
 *   - Multiple choice 1 đáp án / nhiều đáp án: tô xanh/đỏ phương án đã chọn,
 *     bóng đèn theo câu (1 đáp án) hoặc theo từng phương án (nhiều đáp án)
 *     (ReviewMcq).
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
const RV_MCQ_TYPES = ['multiple_choice_single', 'multiple_choice_multiple'];
const RV_NUMBER_WORDS = ['ZERO', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN'];

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

        // Multiple choice: người ra đề không ghi hướng dẫn -> tự thêm 1 dòng.
        if (ReviewMcq.isMcq(groupEntries[0]) && (!box || box.textContent.trim() === '')) {
            const hint = document.createElement('div');
            hint.className = 'exam-part-instructions rvx-mcq-instr';
            hint.innerHTML = ReviewMcq.defaultInstruction(groupEntries[0]);
            if (box) box.replaceWith(hint);
            else heading.insertAdjacentElement('afterend', hint);
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
   Multiple choice — 1 đáp án (radio) và nhiều đáp án (checkbox).
   Chỉ tô phương án học viên ĐÃ CHỌN: đúng -> xanh + ✔, sai -> đỏ + ✖
   (theo mockup). Đáp án đúng xem trong sidebar Answer Help.
     - 1 đáp án : [số] [đề bài + các phương án] [bóng đèn của câu]
     - nhiều    : mỗi phương án có cột ✔/✖ và bóng đèn riêng
   ===================================================================== */
const ReviewMcq = {
    isMcq(entry) {
        return !!entry && RV_MCQ_TYPES.includes(entry.question.type);
    },

    isMulti(entry) {
        return entry.question.type === 'multiple_choice_multiple';
    },

    letter(index) {
        return String.fromCharCode(65 + index);
    },

    options(entry) {
        const raw = entry.question.options;
        if (Array.isArray(raw)) return raw.map((o) => String(o == null ? '' : o));
        if (raw && typeof raw === 'object') return Object.values(raw).map((o) => String(o == null ? '' : o));
        return [];
    },

    /** Vị trí các phương án là đáp án đúng (đáp án lưu theo chữ hoặc theo chữ cái A/B/C). */
    keyIndexes(entry) {
        const options = this.options(entry).map(rvNorm);
        const out = new Set();

        ReviewData.slotsOf(entry.question.id).forEach((slot) => {
            (slot.accepted || []).forEach((value) => {
                const norm = rvNorm(value);
                let idx = options.indexOf(norm);
                if (idx < 0 && /^[a-z]$/.test(norm)) idx = norm.charCodeAt(0) - 97;
                if (idx >= 0 && idx < options.length) out.add(idx);
            });
        });

        return out;
    },

    /** Vị trí các phương án học viên đã chọn. */
    pickedIndexes(entry) {
        const options = this.options(entry).map(rvNorm);
        const saved = AttemptState.getAnswer(entry.question.id);
        const values = Array.isArray(saved) ? saved : (saved ? [saved] : []);
        const out = new Set();

        values.forEach((value) => {
            const norm = rvNorm(value);
            let idx = options.indexOf(norm);
            if (idx < 0 && /^[a-z]$/.test(norm)) idx = norm.charCodeAt(0) - 97;
            if (idx >= 0 && idx < options.length) out.add(idx);
        });

        return out;
    },

    /** [{ index, letter, text, picked, key, state }] — state: correct | incorrect | none */
    optionStates(entry) {
        const keys = this.keyIndexes(entry);
        const picked = this.pickedIndexes(entry);

        return this.options(entry).map((text, index) => {
            const isPicked = picked.has(index);
            const isKey = keys.has(index);
            return {
                index,
                letter: this.letter(index),
                text,
                picked: isPicked,
                key: isKey,
                state: isPicked ? (isKey ? 'correct' : 'incorrect') : 'none',
            };
        });
    },

    /** "A, D" — dùng cho cửa sổ báo lỗi. */
    keyLetters(entry) {
        return this.optionStates(entry).filter((o) => o.key).map((o) => o.letter).join(', ');
    },

    defaultInstruction(entry) {
        if (!this.isMulti(entry)) return 'Choose the correct answer.';

        const max = parseInt((entry.question.question_data || {}).maxSelect, 10) || entry.slotCount || 2;
        const word = RV_NUMBER_WORDS[max] || String(max);
        return 'Choose <strong>' + word + '</strong> correct answers.';
    },

    statusIcon(state) {
        const label = state === 'correct' ? 'Đúng' : 'Sai';
        const span = document.createElement('span');
        span.className = 'rvx-status is-' + state;
        span.setAttribute('role', 'img');
        span.setAttribute('aria-label', label);
        span.title = label;
        span.innerHTML = '<i class="fas ' + (state === 'correct' ? 'fa-check' : 'fa-times') + '" aria-hidden="true"></i>';
        return span;
    },

    helpButton(entry, optionIndex) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'rvx-help rv-allow';
        btn.dataset.questionId = entry.question.id;

        if (optionIndex !== undefined) {
            btn.dataset.optionIndex = String(optionIndex);
            btn.setAttribute('aria-label', 'Giải thích phương án ' + this.letter(optionIndex) + ' – câu ' + rvNumberLabel(entry));
        } else {
            btn.setAttribute('aria-label', 'Xem đáp án và giải thích câu ' + rvNumberLabel(entry));
        }

        btn.title = 'Answer Help';
        btn.innerHTML = '<i class="far fa-lightbulb" aria-hidden="true"></i>';
        return btn;
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;
        card.dataset.rvxDone = '1';

        const multi = this.isMulti(entry);
        const st = ReviewData.entryStatus(entry);
        const states = this.optionStates(entry);
        const textEl = card.querySelector('.exam-question-text');

        // ── Đầu thẻ: số câu + đề bài (+ bóng đèn cho câu 1 đáp án) ──────
        const num = document.createElement('span');
        num.className = 'rvx-num' + (entry.slotCount > 1 ? ' rvx-num--range' : '');
        num.textContent = rvNumberLabel(entry);

        const body = document.createElement('div');
        body.className = 'rvx-mcq-body';

        if (textEl) {
            textEl.classList.add('rvx-mcq-text');
            body.appendChild(textEl);
        }

        // ── Danh sách phương án ─────────────────────────────────────────
        const list = document.createElement('ul');
        list.className = 'rvx-mcq-options' + (multi ? ' is-multi' : ' is-single');

        // Phương án ngắn (câu 1 đáp án) -> chia 2 cột, đọc dọc A B | C D.
        if (!multi && states.length >= 4 && states.every((o) => o.text.length <= 45)) {
            list.classList.add('is-two-col');
            list.style.gridTemplateRows = 'repeat(' + Math.ceil(states.length / 2) + ', auto)';
        }

        states.forEach((opt) => {
            const li = document.createElement('li');
            li.className = 'rvx-opt' + (opt.state !== 'none' ? ' is-' + opt.state : '');
            li.dataset.optionIndex = String(opt.index);

            const letter = document.createElement('span');
            letter.className = 'rvx-opt-letter';
            letter.textContent = opt.letter;

            const mark = document.createElement('span');
            mark.className = 'rvx-opt-mark ' + (multi ? 'is-check' : 'is-radio') + (opt.picked ? ' is-on' : '');
            mark.setAttribute('aria-hidden', 'true');
            if (multi && opt.picked) mark.innerHTML = '<i class="fas fa-check"></i>';

            const text = document.createElement('span');
            text.className = 'rvx-opt-text';
            const label = document.createElement('span');
            label.className = 'rvx-opt-label';
            label.textContent = opt.text;
            text.appendChild(label);

            if (opt.picked) {
                const sr = document.createElement('span');
                sr.className = 'rvx-sr-only';
                sr.textContent = ' (bạn đã chọn – ' + (opt.state === 'correct' ? 'đúng' : 'sai') + ')';
                label.appendChild(sr);
            }

            li.appendChild(letter);
            li.appendChild(mark);
            li.appendChild(text);

            if (multi) {
                // Cột ✔/✖ luôn chiếm chỗ để các bóng đèn thẳng hàng.
                const slot = document.createElement('span');
                slot.className = 'rvx-opt-status';
                if (opt.picked) slot.appendChild(this.statusIcon(opt.state));
                li.appendChild(slot);
                li.appendChild(this.helpButton(entry, opt.index));
            } else if (opt.picked) {
                const icon = document.createElement('i');
                icon.className = 'rvx-opt-icon fas ' + (opt.state === 'correct' ? 'fa-check' : 'fa-times');
                icon.setAttribute('aria-hidden', 'true');
                text.appendChild(icon);
            }

            list.appendChild(li);
        });

        body.appendChild(list);

        card.innerHTML = '';
        card.classList.add('rvx-mcq-card', multi ? 'rvx-mcq-card--multi' : 'rvx-mcq-card--single', 'rvx-is-' + st);
        card.appendChild(num);
        card.appendChild(body);
        if (!multi) card.appendChild(this.helpButton(entry));
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
            // Đang mở cửa sổ báo lỗi thì Esc chỉ đóng cửa sổ đó.
            if (e.key === 'Escape' && this.isOpen() && !ReportModal.isOpen()) this.close();
        });
    },

    isOpen() {
        return !!(this.root && !this.root.hidden && this.root.classList.contains('is-open'));
    },

    choiceLabel(entry, value) {
        if (value === null || value === undefined || String(value).trim() === '') return '';
        return RV_TFNG_TYPES.includes(entry.question.type) ? String(value).toUpperCase() : String(value);
    },

    /** Đáp án đúng dạng chữ: các ô cách nhau ", ", đáp án thay thế " / ". */
    keyText(entry) {
        if (ReviewMcq.isMcq(entry)) return ReviewMcq.keyLetters(entry);

        return ReviewData.slotsOf(entry.question.id)
            .map((s) => (s.accepted || []).map((a) => this.choiceLabel(entry, a)).join(' / '))
            .filter(Boolean).join(', ');
    },

    /** Ô "Đáp án của bạn" / "Đáp án đúng" cho multiple choice: "A. ..." mỗi dòng. */
    mcqAnswerHtml(entry, which) {
        const rows = ReviewMcq.optionStates(entry).filter((o) => (which === 'picked' ? o.picked : o.key));
        if (!rows.length) return '';
        return rows.map((o) => '<span class="rvx-help-opt' + (which === 'picked' ? ' is-' + o.state : '') + '"><b>'
            + o.letter + '.</b> ' + rvEscape(o.text) + '</span>').join('');
    },

    /** Khối "Phương án X" khi mở từ bóng đèn của 1 phương án (câu nhiều đáp án). */
    mcqFocusHtml(entry, optionIndex) {
        const opt = ReviewMcq.optionStates(entry)[optionIndex];
        if (!opt) return '';

        let verdict;
        let tone;
        if (opt.picked && opt.key) { verdict = 'Bạn đã chọn – đây là đáp án đúng'; tone = 'correct'; }
        else if (opt.picked) { verdict = 'Bạn đã chọn – phương án này không đúng'; tone = 'incorrect'; }
        else if (opt.key) { verdict = 'Đây là đáp án đúng – bạn chưa chọn'; tone = 'missed'; }
        else { verdict = 'Không phải đáp án đúng'; tone = 'none'; }

        return '<div class="rvx-help-focus is-' + tone + '">'
            + '  <div class="rvx-help-focus-head"><span class="rvx-help-focus-letter">' + opt.letter + '</span>'
            + '  <span>' + rvEscape(verdict) + '</span></div>'
            + '  <p>' + rvEscape(opt.text) + '</p>'
            + '</div>';
    },

    render(entry, focus) {
        const q = entry.question;
        const slots = ReviewData.slotsOf(q.id);
        const st = ReviewData.entryStatus(entry);
        const explanation = ReviewData.resultOf(q.id).explanation;

        this.title.textContent = 'Giải thích đáp án – Câu ' + rvNumberLabel(entry);

        const isMcq = ReviewMcq.isMcq(entry);
        const focusIndex = focus && focus.optionIndex !== undefined ? focus.optionIndex : null;

        const yours = isMcq
            ? this.mcqAnswerHtml(entry, 'picked')
            : rvEscape(slots.map((s) => this.choiceLabel(entry, s.submitted)).filter(Boolean).join(', '));
        const keys = isMcq ? this.mcqAnswerHtml(entry, 'key') : rvEscape(this.keyText(entry));

        const statusText = { correct: 'Bạn trả lời đúng', incorrect: 'Bạn trả lời sai', empty: 'Bạn chưa trả lời câu này' }[st];

        this.body.innerHTML = ''
            + '<div class="rvx-help-status is-' + st + '">'
            + '  <i class="fas ' + ({ correct: 'fa-check-circle', incorrect: 'fa-times-circle', empty: 'fa-minus-circle' }[st]) + '" aria-hidden="true"></i>'
            + '  <span>' + statusText + '</span>'
            + '</div>'
            + (q.text ? '<div class="rvx-help-question">' + q.text + '</div>' : '')
            + (isMcq && focusIndex !== null ? this.mcqFocusHtml(entry, focusIndex) : '')
            + '<dl class="rvx-help-answers' + (isMcq ? ' is-mcq' : '') + '">'
            + '  <div><dt>Đáp án của bạn</dt><dd class="is-' + st + '">' + (yours || 'Chưa trả lời') + '</dd></div>'
            + '  <div><dt>Đáp án đúng</dt><dd class="is-key">' + (keys || '—') + '</dd></div>'
            + '</dl>'
            + '<section class="rvx-help-explain">'
            + '  <h4>Giải thích</h4>'
            + (explanation
                ? '<div class="rvx-help-explain-body">' + explanation + '</div>'
                : '<p class="rvx-help-empty">Chưa có giải thích cho câu này.</p>')
            + '</section>';

        this.body.scrollTop = 0;
    },

    open(entry, trigger, focus) {
        if (!this.root) return;
        this.entry = entry;
        this.render(entry, focus);

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
        if (ReportModal.isOpen()) ReportModal.close();
        this.root.classList.remove('is-open');
        document.body.classList.remove('rvx-drawer-open');

        const done = () => { this.root.hidden = true; };
        const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) done(); else window.setTimeout(done, 260);

        if (this.returnFocus && document.body.contains(this.returnFocus)) this.returnFocus.focus();
        this.returnFocus = null;
    },
};

/** "2" hoặc "5 – 7" (câu chiếm nhiều ô). */
function rvNumberLabel(entry) {
    return entry.slotCount > 1
        ? (entry.startNumber + ' – ' + entry.endNumber)
        : String(entry.startNumber);
}

/* =====================================================================
   Cửa sổ "Báo lỗi đáp án" — mở từ nút trên đầu sidebar Answer Help.
   Gửi về panel.ielts_tests.report_answer; manager xem ở Admin > IELTS Tests
   > Thông báo đề thi lỗi.
   ===================================================================== */
const ReportModal = {
    root: null,
    form: null,
    textarea: null,
    count: null,
    submitBtn: null,
    cancelBtn: null,
    msg: null,
    entry: null,
    max: 300,
    sending: false,
    returnFocus: null,
    closeTimer: null,

    init() {
        this.root = document.getElementById('rvxReport');
        const openBtn = document.getElementById('rvxReportOpen');
        if (!this.root || !openBtn) return;

        this.form = document.getElementById('rvxReportForm');
        this.textarea = document.getElementById('rvxReportMessage');
        this.count = document.getElementById('rvxReportCount');
        this.submitBtn = document.getElementById('rvxReportSubmit');
        this.cancelBtn = this.root.querySelector('.rvx-report-cancel');
        this.msg = document.getElementById('rvxReportMsg');
        this.max = parseInt(this.textarea.getAttribute('maxlength'), 10) || 300;

        openBtn.addEventListener('click', () => {
            if (AnswerHelp.entry) this.open(AnswerHelp.entry, openBtn);
        });

        this.root.querySelectorAll('[data-report-close]').forEach((el) => {
            el.addEventListener('click', () => this.close());
        });

        this.textarea.addEventListener('input', () => this.update());
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.send();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                e.stopPropagation();
                this.close();
            }
        });
    },

    isOpen() {
        return !!(this.root && !this.root.hidden);
    },

    open(entry, trigger) {
        window.clearTimeout(this.closeTimer);
        const sameEntry = this.entry && this.entry.question.id === entry.question.id;
        this.entry = entry;
        this.returnFocus = trigger || document.activeElement;

        document.getElementById('rvxReportQuestion').textContent = 'Câu ' + rvNumberLabel(entry);
        document.getElementById('rvxReportAnswer').textContent = AnswerHelp.keyText(entry) || '—';

        // Giữ lại nội dung đang gõ dở nếu mở lại đúng câu đó.
        if (!sameEntry || this.form.classList.contains('is-sent')) this.textarea.value = '';
        this.form.classList.remove('is-sent');
        this.textarea.disabled = false;
        this.cancelBtn.textContent = 'Hủy';
        this.showMessage('', '');
        this.update();

        this.root.hidden = false;
        window.requestAnimationFrame(() => this.root.classList.add('is-open'));
        this.textarea.focus();
    },

    close() {
        if (!this.isOpen()) return;
        window.clearTimeout(this.closeTimer);
        this.root.classList.remove('is-open');
        this.root.hidden = true;
        if (this.returnFocus && document.body.contains(this.returnFocus)) this.returnFocus.focus();
        this.returnFocus = null;
    },

    update() {
        const len = this.textarea.value.length;
        this.count.textContent = len + '/' + this.max;
        this.count.classList.toggle('is-full', len >= this.max);
        this.submitBtn.disabled = this.sending
            || this.form.classList.contains('is-sent')
            || this.textarea.value.trim() === '';
    },

    showMessage(text, type) {
        this.msg.textContent = text;
        this.msg.className = 'rvx-report-msg' + (type ? ' is-' + type : '');
        this.msg.hidden = !text;
    },

    async send() {
        const meta = window.REVIEW_META || {};
        const message = this.textarea.value.trim();
        if (!this.entry || !meta.reportUrl || !message || this.sending) return;

        this.sending = true;
        this.submitBtn.textContent = 'Đang gửi...';
        this.update();
        this.showMessage('', '');

        const tokenEl = document.querySelector('meta[name="csrf-token"]');

        try {
            const res = await fetch(meta.reportUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': tokenEl ? tokenEl.getAttribute('content') : '',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    question_id: this.entry.question.id,
                    question_number: rvNumberLabel(this.entry),
                    message,
                }),
            });

            let data = {};
            try { data = await res.json(); } catch (e) { /* không phải JSON */ }

            if (res.ok && data.status === 'ok') {
                this.form.classList.add('is-sent');
                this.textarea.disabled = true;
                this.cancelBtn.textContent = 'Đóng';
                this.showMessage(data.message || 'Đã gửi báo cáo. Cảm ơn bạn!', 'success');
                this.closeTimer = window.setTimeout(() => this.close(), 2200);
            } else {
                const firstError = data.errors ? Object.values(data.errors)[0] : null;
                const text = (Array.isArray(firstError) ? firstError[0] : firstError)
                    || data.message
                    || (res.status === 419 ? 'Phiên làm việc đã hết hạn, hãy tải lại trang.' : 'Không gửi được báo cáo, hãy thử lại.');
                this.showMessage(text, 'error');
            }
        } catch (e) {
            this.showMessage('Không kết nối được máy chủ, hãy thử lại.', 'error');
        } finally {
            this.sending = false;
            this.submitBtn.textContent = 'Gửi báo cáo';
            this.update();
        }
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
    ReportModal.init();

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
            if (RV_MCQ_TYPES.includes(group.question_type)) holder.classList.add('rvx-group--mcq');
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
            else if (ReviewMcq.isMcq(entry)) ReviewMcq.decorate(entry);
            else ReviewMarker.decorateEntry(entry);
        });
        ReviewMarker.flushLooseExplanations(els.questions);

        els.questions.querySelectorAll('.rvx-group--tfng, .rvx-group--mcq').forEach((holder) => {
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
        if (help) {
            const optionIndex = help.dataset.optionIndex;
            AnswerHelp.open(entry, help, optionIndex !== undefined ? { optionIndex: parseInt(optionIndex, 10) } : null);
        }
    }, true);

    rvBlockInteractions(root);
    rvBindResizer(els.body, root.querySelector('.rvx-left'), document.getElementById('rvxResizer'));

    buildPager();
    renderPart();

    // Mở từ trang kết quả: ?q=N -> nhảy tới câu N.
    const fromQuery = parseInt(new URLSearchParams(window.location.search).get('q'), 10);
    goTo(fromQuery > 0 ? fromQuery : 1, { scroll: fromQuery > 0 });
});