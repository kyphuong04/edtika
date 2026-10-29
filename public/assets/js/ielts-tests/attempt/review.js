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
 *   - Sentence / Note completion (Reading): mỗi ô trống thay bằng "viên"
 *     đáp án ngay trong câu: đúng -> xanh ✔; sai/bỏ trống -> đỏ ✖ → đáp án
 *     đúng (xanh), kèm bóng đèn theo từng ô (ReviewCompletion).
 *   - Table completion (Reading): như trên, trong từng ô bảng.
 *   - Matching headings (Reading): tại chỗ [[Q]] trong bài đọc (hoặc thẻ câu
 *     hỏi): [số + bóng đèn] [đáp án đúng ✔] (+ [heading đã chọn ✖] nếu sai)
 *     (ReviewMatching).
 *   - Matching sentence endings (Reading): thẻ [số] [câu + viên đáp án] [💡]
 *     (ReviewEndings).
 *   - Matching information (Reading): bảng ma trận, ô đáp án đúng xanh, ô
 *     chọn sai đỏ, cột bóng đèn cuối mỗi hàng (ReviewMatrix).
 *   - Flow chart (Reading): sơ đồ căn giữa, mỗi ô trống = [số] [viên đáp án]
 *     [💡]; Diagram Labeling điền từ (Listening): mỗi bước 1 thẻ, cột trái là
 *     vòng số + nút ▶ nghe lại đoạn (ReviewFlow).
 *   - Drag & drop (giữ / bỏ khỏi danh sách): mỗi chỗ trống 1 hàng [câu] [số]
 *     [đáp án đã chọn / đáp án đúng] (ReviewDragDrop).
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
const RV_COMPLETION_TYPES = ['sentence_completion', 'note_completion', 'table_completion'];
// Sơ đồ điền từ: Flow chart (Reading) + Diagram Labeling điền từ (Listening).
const RV_FLOW_TYPES = ['flow_chart_completion', 'diagram_completion'];

// Listening: các dạng gộp vào 1 khối (blank sheet) ở trang làm bài.
const RV_LISTEN_SHEET_TYPES = ['sentence_completion', 'summary_completion', 'note_completion', 'short_answer'];

function rvListening() {
    const RLw = window.ReviewListening;
    return !!(RLw && RLw.enabled);
}
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
                // Hiển thị trên trang chữa bài: phương án đã chọn + đáp án đúng (kể cả khi
                // học viên không chọn) -> câu sai thấy cùng lúc đáp án mình chọn và đáp án đúng.
                shown: isPicked || isKey,
                tone: isKey ? 'correct' : 'incorrect',
                missed: isKey && !isPicked,
            };
        });
    },

    /** Lớp màu của 1 phương án: is-correct / is-incorrect (+ is-missed: đáp án đúng chưa chọn). */
    rowClass(opt) {
        if (!opt.shown) return '';
        return ' is-' + opt.tone + (opt.missed ? ' is-missed' : '');
    },

    srText(opt) {
        if (opt.missed) return ' (đáp án đúng)';
        if (opt.picked) return ' (bạn đã chọn – ' + (opt.state === 'correct' ? 'đúng' : 'sai') + ')';
        return '';
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

        // Listening: giao diện riêng theo mockup (vòng số + ▶, không bóng đèn từng câu).
        if (window.ReviewListening && window.ReviewListening.enabled) {
            this.decorateListening(entry, card, { multi, st, states, textEl });
            return;
        }

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
            li.className = 'rvx-opt' + this.rowClass(opt);
            li.dataset.optionIndex = String(opt.index);

            const letter = document.createElement('span');
            letter.className = 'rvx-opt-letter';
            letter.textContent = opt.letter;

            const mark = document.createElement('span');
            mark.className = 'rvx-opt-mark ' + (multi ? 'is-check' : 'is-radio') + ((opt.picked || (!multi && opt.key)) ? ' is-on' : '');
            mark.setAttribute('aria-hidden', 'true');
            if (multi && opt.picked) mark.innerHTML = '<i class="fas fa-check"></i>';

            const text = document.createElement('span');
            text.className = 'rvx-opt-text';
            const label = document.createElement('span');
            label.className = 'rvx-opt-label';
            label.textContent = opt.text;
            text.appendChild(label);

            if (opt.shown) {
                const sr = document.createElement('span');
                sr.className = 'rvx-sr-only';
                sr.textContent = this.srText(opt);
                label.appendChild(sr);
            }

            li.appendChild(letter);
            li.appendChild(mark);
            li.appendChild(text);

            if (multi) {
                // Cột ✔/✖ luôn chiếm chỗ để các bóng đèn thẳng hàng.
                const slot = document.createElement('span');
                slot.className = 'rvx-opt-status';
                if (opt.shown) slot.appendChild(this.statusIcon(opt.tone));
                li.appendChild(slot);
                li.appendChild(this.helpButton(entry, opt.index));
            } else if (opt.shown) {
                const icon = document.createElement('i');
                icon.className = 'rvx-opt-icon fas ' + (opt.tone === 'correct' ? 'fa-check' : 'fa-times');
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

    /**
     * Listening (mockup):
     *  - 1 đáp án : [vòng số + ▶] | đề bài đậm + hàng A–E (radio), hàng đã chọn tô cả dòng, ✔/✖ cuối dòng
     *  - nhiều    : đề bài như dòng hướng dẫn + mỗi phương án là 1 ô viền (checkbox bên ngoài)
     *               | cột phải: mỗi số câu 1 vòng số + ▶ riêng (VD 14, 15)
     * Không có bóng đèn từng câu — sidebar Answer Help mở bằng tab ở mép phải.
     */
    decorateListening(entry, card, ctx) {
        const RL = window.ReviewListening;
        const { multi, st, states, textEl } = ctx;

        const body = document.createElement('div');
        body.className = 'rvl-qbody';

        if (textEl) {
            textEl.className = multi ? 'rvl-qtext rvl-qtext--plain' : 'rvl-qtext';
            body.appendChild(textEl);
        }

        const list = document.createElement('ul');
        list.className = 'rvl-opts' + (multi ? ' is-boxed' : '');

        states.forEach((opt) => {
            const li = document.createElement('li');
            li.className = 'rvl-opt' + this.rowClass(opt);

            const mark = document.createElement('span');
            mark.className = 'rvl-opt-mark ' + (multi ? 'is-check' : 'is-radio') + ((opt.picked || (!multi && opt.key)) ? ' is-on' : '');
            mark.setAttribute('aria-hidden', 'true');
            if (multi && opt.picked) mark.innerHTML = '<i class="fas fa-check"></i>';

            const letter = document.createElement('span');
            letter.className = 'rvl-opt-letter';
            letter.textContent = opt.letter + (multi ? '.' : '');

            const text = document.createElement('span');
            text.className = 'rvl-opt-text';
            text.textContent = opt.text;
            if (opt.shown) {
                const sr = document.createElement('span');
                sr.className = 'rvx-sr-only';
                sr.textContent = this.srText(opt);
                text.appendChild(sr);
            }

            const icon = document.createElement('i');
            icon.className = 'rvl-opt-icon' + (opt.shown ? ' fas ' + (opt.tone === 'correct' ? 'fa-check' : 'fa-times') : '');
            icon.setAttribute('aria-hidden', 'true');

            if (multi) {
                // [checkbox] [ô viền: "A." nội dung ... ✔/✖]
                const box = document.createElement('span');
                box.className = 'rvl-opt-box';
                box.appendChild(letter);
                box.appendChild(text);
                box.appendChild(icon);
                li.appendChild(mark);
                li.appendChild(box);
            } else {
                // [A] [radio] nội dung ... ✔/✖  (cả dòng tô màu)
                li.appendChild(letter);
                li.appendChild(mark);
                li.appendChild(text);
                li.appendChild(icon);
            }
            list.appendChild(li);
        });
        body.appendChild(list);

        const nums = document.createElement('div');
        nums.className = 'rvl-qnums' + (multi ? ' is-right' : '');
        for (let n = entry.startNumber; n <= entry.endNumber; n++) nums.appendChild(RL.numberBlock(entry, n));

        card.innerHTML = '';
        card.classList.add('rvl-mcq', multi ? 'rvl-mcq--multi' : 'rvl-mcq--single', 'rvx-is-' + st);
        if (multi) {
            card.appendChild(body);
            card.appendChild(nums);
        } else {
            card.appendChild(nums);
            card.appendChild(body);
        }
    },
};

/* =====================================================================
   Sidebar Answer Help — trượt từ phải sang, đè lên trang.
   Nội dung hiện tại: đề bài, đáp án của học viên, đáp án đúng, giải thích.
   ===================================================================== */
/* =====================================================================
   Sentence completion & Note completion (Reading)
     - Sentence: mỗi câu là 1 thẻ viền: [số] câu văn, ô trống thay bằng
       viên đáp án ngay trong câu.
     - Note    : cả group nằm trong 1 khung ghi chú (tiêu đề giữa, mục,
       gạch đầu dòng do người ra đề soạn); mỗi ô trống = [số] + viên đáp án.
   Viên đáp án: đúng  -> [answer ✔]
                sai   -> [bài làm ✖] → [đáp án ✔]
                trống -> [ ✖ ]       → [đáp án ✔]
   Bóng đèn cạnh từng ô mở Answer Help của đúng ô đó.
   ===================================================================== */
const ReviewCompletion = {
    applies(entry) {
        if (!entry || !RV_COMPLETION_TYPES.includes(entry.question.type)) return false;
        // Listening: chỉ Table dùng chung lớp này (các dạng khác xem ReviewListenSheet).
        return AttemptState.skill !== 'listening' || entry.question.type === 'table_completion';
    },

    isNote(entry) {
        return entry.question.type === 'note_completion';
    },

    /** Đáp án chấp nhận của 1 ô (bỏ phần tử rỗng). */
    acceptedOf(slot) {
        return ((slot && Array.isArray(slot.accepted)) ? slot.accepted : [])
            .map((a) => String(a == null ? '' : a).trim())
            .filter(Boolean);
    },

    submittedOf(slot) {
        return slot && slot.submitted != null ? String(slot.submitted).trim() : '';
    },

    pill(text, tone) {
        const el = document.createElement('span');
        el.className = 'rvx-pill is-' + tone + (text ? '' : ' is-blank');
        if (text) {
            const t = document.createElement('span');
            t.className = 'rvx-pill-text';
            t.textContent = text;
            el.appendChild(t);
        }
        el.insertAdjacentHTML('beforeend', '<i class="fas ' + (tone === 'correct' ? 'fa-check' : 'fa-times')
            + '" aria-hidden="true"></i>');
        return el;
    },

    /** Khối thay cho 1 ô trống: [số] [viên đáp án] (→ [đáp án đúng]) [bóng đèn]. */
    buildBlank(entry, index, withNumber) {
        return this.blankEl({
            entry,
            slot: ReviewData.slotsOf(entry.question.id)[index],
            number: entry.startNumber + index,
            slotIndex: index,
            withNumber,
            withHelp: true,
        });
    },

    /** opts: { entry, slot, number, slotIndex, withNumber, withHelp } */
    blankEl(opts) {
        const { entry, slot, number, withNumber } = opts;
        const index = opts.slotIndex;
        const st = ReviewData.status(slot);
        const submitted = this.submittedOf(slot);
        const accepted = this.acceptedOf(slot);

        const wrap = document.createElement('span');
        wrap.className = 'rvx-blank is-' + st;
        wrap.dataset.number = String(number);

        if (opts.numberEl) {
            wrap.appendChild(opts.numberEl);   // Listening: vòng số + ▶
        } else if (withNumber) {
            const num = document.createElement('span');
            num.className = 'rvx-num rvx-num--inline';
            num.textContent = number;
            wrap.appendChild(num);
        }

        const answers = document.createElement('span');
        answers.className = 'rvx-blank-answers';
        answers.setAttribute('aria-hidden', 'true');

        if (st === 'correct') {
            answers.appendChild(this.pill(submitted || accepted[0] || '', 'correct'));
        } else {
            answers.appendChild(this.pill(submitted, 'incorrect'));
            const arrow = document.createElement('span');
            arrow.className = 'rvx-arrow';
            arrow.textContent = '→';
            answers.appendChild(arrow);
            const key = this.pill(accepted[0] || '—', 'correct');
            key.classList.add('is-key');
            if (accepted.length > 1) key.title = 'Đáp án chấp nhận: ' + accepted.join(' / ');
            answers.appendChild(key);
        }
        wrap.appendChild(answers);

        const sr = document.createElement('span');
        sr.className = 'rvx-sr-only';
        sr.textContent = 'Câu ' + number + ': '
            + ({ correct: 'đúng', incorrect: 'sai', empty: 'chưa trả lời' }[st])
            + (submitted ? ', bạn điền "' + submitted + '"' : '')
            + (st !== 'correct' && accepted.length ? ', đáp án đúng "' + accepted.join(' / ') + '"' : '')
            + '.';
        wrap.appendChild(sr);

        if (opts.withHelp) wrap.appendChild(this.helpButton(entry, index, number));

        return wrap;
    },

    helpButton(entry, slotIndex, number) {
        const help = document.createElement('button');
        help.type = 'button';
        help.className = 'rvx-help rvx-help--sm rv-allow';
        help.dataset.questionId = entry.question.id;
        help.dataset.slotIndex = String(slotIndex);
        help.setAttribute('aria-label', 'Xem đáp án và giải thích câu ' + number);
        help.title = 'Answer Help';
        help.innerHTML = '<i class="far fa-lightbulb" aria-hidden="true"></i>';
        return help;
    },

    /**
     * Table completion: mỗi ô bảng có chỗ trống = 1 câu (slot có cell "r-c"
     * và parts = từng chỗ trống trong ô). Ô: [số] [viên đáp án…] [bóng đèn].
     */
    decorateTable(entry, card) {
        const slots = ReviewData.slotsOf(entry.question.id);
        const byCell = {};
        slots.forEach((slot, i) => { if (slot && slot.cell) byCell[slot.cell] = { slot, index: i }; });

        let fallbackIndex = 0;
        card.querySelectorAll('tbody tr').forEach((tr, r) => {
            Array.from(tr.children).forEach((td, c) => {
                const inputs = Array.from(td.querySelectorAll('.exam-blank-input'));
                if (!inputs.length) return;

                const found = byCell[r + '-' + c] || { slot: slots[fallbackIndex], index: fallbackIndex };
                fallbackIndex = found.index + 1;
                const slot = found.slot || {};
                const parts = Array.isArray(slot.parts) && slot.parts.length ? slot.parts : [slot];
                const number = entry.startNumber + found.index;

                const badge = td.querySelector('.exam-table-number');
                if (badge) badge.remove();

                const listening = rvListening();
                inputs.forEach((input, i) => {
                    input.replaceWith(this.blankEl({
                        entry,
                        slot: parts[i],
                        number,
                        slotIndex: found.index,
                        withNumber: i === 0,
                        // Listening: vòng số + ▶ nghe đoạn của câu; Answer Help ở tab mép phải.
                        numberEl: listening && i === 0 ? window.ReviewListening.numberBlock(entry, number) : null,
                        withHelp: !listening && i === inputs.length - 1,
                    }));
                });

                td.classList.add('rvx-is-' + ReviewData.status(slot));
            });
        });
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;

        if (q.type === 'table_completion') {
            if (!card.querySelector('.exam-table-wrap')) {
                ReviewMarker.decorateEntry(entry);
                return;
            }
            card.dataset.rvxDone = '1';
            this.decorateTable(entry, card);
            const tableBadge = card.querySelector('.exam-question-number');
            if (tableBadge) tableBadge.remove();
            card.querySelectorAll('.attempt-save-indicator').forEach((el) => el.remove());
            card.classList.add('rvx-table-card');
            return;
        }

        const text = card.querySelector('.exam-question-text');
        const inputs = Array.from(card.querySelectorAll('.exam-blank-input'));
        // Đề cũ không có ___ trong câu -> giữ cách tô cũ.
        if (!text || !inputs.length) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        card.dataset.rvxDone = '1';

        const note = this.isNote(entry);
        const single = !note && inputs.length === 1;

        card.querySelectorAll('.exam-blank-number').forEach((b) => b.remove());
        inputs.forEach((input, i) => input.replaceWith(this.buildBlank(entry, i, !single)));

        const badge = card.querySelector('.exam-question-number');
        if (badge) badge.remove();
        card.querySelectorAll('.attempt-save-indicator').forEach((el) => el.remove());

        card.classList.add(note ? 'rvx-note-card' : 'rvx-sc-card', 'rvx-is-' + ReviewData.entryStatus(entry));

        if (note) return;

        if (single) {
            card.classList.add('rvx-sc-item');
            const holder = document.createElement('span');
            text.replaceWith(holder);
            holder.replaceWith(this.sentenceRow(text, entry.startNumber));
            return;
        }

        // Nhiều câu trong 1 question (mỗi câu 1 đoạn / 1 dòng): tách mỗi câu
        // có ô trống thành 1 thẻ riêng, số câu đứng thành cột bên trái.
        card.classList.add('rvx-sc-split');
        const frag = document.createDocumentFragment();
        this.splitLines(text).forEach((nodes) => {
            const box = document.createElement('div');
            box.className = 'exam-question-text';
            nodes.forEach((n) => box.appendChild(n));

            const first = box.querySelector('.rvx-blank');
            if (!first) {
                if (box.textContent.trim() || box.querySelector('img, table')) {
                    box.classList.add('rvx-sc-intro');
                    frag.appendChild(box);
                }
                return;
            }

            const inlineNum = first.querySelector('.rvx-num--inline');
            if (inlineNum) inlineNum.remove();

            const item = document.createElement('div');
            item.className = 'rvx-sc-item';
            item.appendChild(this.sentenceRow(box, first.dataset.number));
            frag.appendChild(item);
        });
        text.replaceWith(frag);
    },

    /** [số câu] [câu văn] */
    sentenceRow(textEl, number) {
        const row = document.createElement('div');
        row.className = 'rvx-sc-row';
        const num = document.createElement('span');
        num.className = 'rvx-num rvx-num--sc';
        num.textContent = number;
        row.appendChild(num);
        row.appendChild(textEl);
        return row;
    },

    /**
     * Chia nội dung câu hỏi thành từng dòng: mỗi khối (p, div, li, h*) là 1
     * dòng, trong khối lại tách tiếp theo <br>. Trả về mảng các mảng node.
     */
    splitLines(container) {
        const BLOCKS = /^(P|DIV|LI|H[1-6]|BLOCKQUOTE)$/;
        const lines = [];
        let current = [];
        const flush = () => { if (current.length) lines.push(current); current = []; };

        const walk = (parent) => {
            Array.from(parent.childNodes).forEach((node) => {
                if (node.nodeName === 'BR') { flush(); return; }
                if (node.nodeName === 'UL' || node.nodeName === 'OL') { flush(); walk(node); flush(); return; }
                if (node.nodeType === 1 && BLOCKS.test(node.nodeName)) {
                    flush();
                    if (node.querySelector('br, p, div, li')) walk(node);
                    else current.push(node);
                    flush();
                    return;
                }
                current.push(node);
            });
        };

        walk(container);
        flush();
        return lines;
    },

    /** Note completion: gom tiêu đề + các thẻ của group vào 1 khung ghi chú. */
    decorateGroup(holder) {
        if (holder.dataset.type !== 'note_completion') return;
        if (holder.querySelector('.rvx-notes')) return;

        const items = Array.from(holder.children).filter((el) => el.classList.contains('rvx-note-card')
            || el.classList.contains('exam-question-title'));
        if (!items.some((el) => el.classList.contains('rvx-note-card'))) return;

        const panel = document.createElement('div');
        panel.className = 'rvx-notes';
        items[0].insertAdjacentElement('beforebegin', panel);
        items.forEach((el) => panel.appendChild(el));
    },
};

/* =====================================================================
   Flow chart Completion (Reading) & Diagram Labeling điền từ (Listening)
   Sơ đồ đã được renderers.js dựng (ielts-shared/flowchart.js); ở đây chỉ
   thay từng ô nhập bằng viên đáp án của ReviewCompletion.
     - Reading : [số đổi màu theo đúng/sai] [viên đáp án] [💡] ngay trong dòng,
                 toàn bộ sơ đồ căn giữa.
     - Listening: viên đáp án không kèm số; số câu chuyển sang cột trái của
                 thẻ bước (vòng số + nút ▶ như các dạng Listening khác).
   ===================================================================== */
const ReviewFlow = {
    applies(entry) {
        return !!entry && RV_FLOW_TYPES.includes(entry.question.type);
    },

    isSteps(entry) {
        return entry.question.type === 'diagram_completion';
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;

        const inputs = Array.from(card.querySelectorAll('.exam-blank-input'));
        if (!inputs.length) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        card.dataset.rvxDone = '1';

        const steps = this.isSteps(entry);
        const slots = ReviewData.slotsOf(q.id);

        card.querySelectorAll('.exam-blank-number').forEach((b) => b.remove());
        inputs.forEach((input, i) => {
            input.replaceWith(ReviewCompletion.blankEl({
                entry,
                slot: slots[i],
                number: entry.startNumber + i,
                slotIndex: i,
                withNumber: !steps,
                withHelp: !steps,
            }));
        });

        const badge = card.querySelector('.exam-question-number');
        if (badge) badge.remove();
        card.querySelectorAll('.attempt-save-indicator').forEach((el) => el.remove());

        card.classList.add('rvx-flow-card', steps ? 'rvx-flow-card--steps' : 'rvx-flow-card--chart',
            'rvx-is-' + ReviewData.entryStatus(entry));

        if (steps) this.decorateSteps(entry, card);
    },

    /** Listening: mỗi thẻ bước có cột vòng số (+ ▶ nghe lại) cho các ô trống trong bước. */
    decorateSteps(entry, card) {
        const RL = window.ReviewListening;
        const canPlay = !!(RL && RL.enabled);

        card.querySelectorAll('.ifc-step').forEach((step) => {
            const blanks = Array.from(step.querySelectorAll('.rvx-blank'));
            if (!blanks.length) {
                step.classList.add('is-plain');
                return;
            }

            const nums = document.createElement('div');
            nums.className = 'rvl-qnums ifc-step-nums';
            blanks.forEach((blank) => {
                const number = Number(blank.dataset.number);
                nums.appendChild(canPlay ? RL.numberBlock(entry, number) : this.ring(number));
            });
            step.prepend(nums);
        });
    },

    /** Vòng số không có nút ▶ (section chưa có audio). */
    ring(number) {
        const box = document.createElement('div');
        box.className = 'rvl-qnum';
        const ring = document.createElement('span');
        ring.className = 'rvl-ring';
        ring.textContent = number;
        box.appendChild(ring);
        return box;
    },
};

/* =====================================================================
   Matching headings (Reading)
   Ô [[Q]] trong bài đọc (hoặc thẻ câu hỏi bên phải nếu đề không gắn [[Q]])
   đổi thành:  [số]   [B  How small changes ...   ✔]   <- đáp án đúng
               [💡]   [E  Why traffic jams ...    ✖]   <- học viên chọn sai
   Đúng -> chỉ 1 hàng xanh. Bỏ trống -> hàng xanh + hàng "Chưa trả lời".
   ===================================================================== */
const ReviewMatching = {
    applies(entry) {
        return !!entry
            && entry.question.type === 'matching_headings'
            && AttemptState.skill !== 'listening';
    },

    row(key, text, tone) {
        const row = document.createElement('span');
        row.className = 'rvx-mh-row is-' + tone;

        const k = document.createElement('span');
        k.className = 'rvx-mh-key';
        k.textContent = key;

        const t = document.createElement('span');
        t.className = 'rvx-mh-text';
        t.textContent = text;

        const icon = document.createElement('span');
        icon.className = 'rvx-mh-icon';
        icon.innerHTML = '<i class="fas ' + ({ correct: 'fa-check', incorrect: 'fa-times', empty: 'fa-minus' }[tone])
            + '" aria-hidden="true"></i>';

        row.appendChild(k);
        row.appendChild(t);
        row.appendChild(icon);
        return row;
    },

    optionText(options, key) {
        const text = options && options[key];
        return text && String(text) !== key ? String(text) : '';
    },

    /** Khối [số + bóng đèn] [các hàng đáp án]. */
    build(entry, questionHtml) {
        const q = entry.question;
        const options = q.options || {};
        const slot = ReviewData.slotsOf(q.id)[0];
        const st = ReviewData.status(slot);
        const submitted = slot && slot.submitted != null ? String(slot.submitted).trim() : '';
        const accepted = ((slot && slot.accepted) || []).map((a) => String(a).trim()).filter(Boolean);

        const box = document.createElement('span');
        box.className = 'rvx-mh is-' + st;

        const side = document.createElement('span');
        side.className = 'rvx-mh-side';
        const num = document.createElement('span');
        num.className = 'rvx-num';
        num.textContent = entry.startNumber;
        side.appendChild(num);

        const help = document.createElement('button');
        help.type = 'button';
        help.className = 'rvx-help rvx-help--sm rv-allow';
        help.dataset.questionId = q.id;
        help.setAttribute('aria-label', 'Xem đáp án và giải thích câu ' + entry.startNumber);
        help.title = 'Answer Help';
        help.innerHTML = '<i class="far fa-lightbulb" aria-hidden="true"></i>';
        side.appendChild(help);

        const rows = document.createElement('span');
        rows.className = 'rvx-mh-rows';

        if (questionHtml) {
            const prompt = document.createElement('span');
            prompt.className = 'rvx-mh-prompt';
            prompt.innerHTML = questionHtml;
            rows.appendChild(prompt);
        }

        // Đáp án đúng (thường 1; đề cho phép nhiều đáp án -> mỗi đáp án 1 hàng).
        const keys = st === 'correct' ? [this.matchKey(options, submitted) || submitted] : accepted;
        keys.forEach((key) => rows.appendChild(this.row(key, this.optionText(options, key), 'correct')));

        if (st === 'incorrect') {
            rows.appendChild(this.row(submitted, this.optionText(options, this.matchKey(options, submitted) || submitted), 'incorrect'));
        } else if (st === 'empty') {
            rows.appendChild(this.row('—', 'Chưa trả lời', 'empty'));
        }

        const sr = document.createElement('span');
        sr.className = 'rvx-sr-only';
        sr.textContent = 'Câu ' + entry.startNumber + ': '
            + ({ correct: 'đúng', incorrect: 'sai', empty: 'chưa trả lời' }[st]) + '.';
        rows.appendChild(sr);

        box.appendChild(side);
        box.appendChild(rows);
        return box;
    },

    /** Key đúng chữ hoa/thường như trong danh sách lựa chọn ("ii" vs "II"). */
    matchKey(options, value) {
        const v = rvNorm(value);
        return Object.keys(options || {}).find((k) => rvNorm(k) === v) || '';
    },

    decorate(entry) {
        const q = entry.question;
        const el = document.getElementById('exam-q-' + q.id);
        if (!el || el.dataset.rvxDone) return;

        const inPassage = el.classList.contains('exam-match-slot');
        if (!inPassage && !el.querySelector('.exam-match-slot')) {
            ReviewMarker.decorateEntry(entry);
            return;
        }

        if (inPassage) {
            const box = this.build(entry, '');
            box.id = el.id;
            box.dataset.rvxDone = '1';
            box.classList.add('rvx-mh--passage');
            el.replaceWith(box);
            return;
        }

        // Thẻ câu hỏi ở cột phải: [số + 💡] [Paragraph B / các hàng đáp án]
        const text = el.querySelector('.exam-question-text');
        const box = this.build(entry, text ? text.innerHTML : '');
        el.dataset.rvxDone = '1';
        el.innerHTML = '';
        el.classList.add('rvx-mh-card', 'rvx-is-' + ReviewData.status(ReviewData.slotsOf(q.id)[0]));
        el.appendChild(box);
    },
};

/* =====================================================================
   Matching sentence endings (Reading)
   Mỗi câu 1 thẻ:  [số]  Câu văn … [currents ✔] … tiếp       | [💡]
                   sai -> [carbodioxide ✖] → [carbon ✔]
                   trống -> [Chưa trả lời] → [đáp án ✔]
   Viên đáp án đặt đúng chỗ ___ nếu câu có, không thì ở cuối câu. Ô [[Q]]
   trong bài đọc (nếu đề gắn) đổi thành [số] [viên đáp án] [💡] ngay tại chỗ.
   ===================================================================== */
const ReviewEndings = {
    applies(entry) {
        return !!entry
            && entry.question.type === 'matching_sentence_endings'
            && AttemptState.skill !== 'listening';
    },

    label(options, key) {
        const k = ReviewMatching.matchKey(options, key) || key;
        return ReviewMatching.optionText(options, k) || k;
    },

    /** [bài làm] (→ [đáp án]) */
    answers(entry) {
        const q = entry.question;
        const options = q.options || {};
        const slot = ReviewData.slotsOf(q.id)[0];
        const st = ReviewData.status(slot);
        const submitted = slot && slot.submitted != null ? String(slot.submitted).trim() : '';
        const accepted = ((slot && slot.accepted) || []).map((a) => String(a).trim()).filter(Boolean);

        const wrap = document.createElement('span');
        wrap.className = 'rvx-se-answers is-' + st;
        wrap.dataset.number = String(entry.startNumber);

        if (st === 'correct') {
            wrap.appendChild(this.pill(options, submitted, 'correct'));
        } else {
            if (st === 'empty') {
                const empty = document.createElement('span');
                empty.className = 'rvx-pill is-empty';
                empty.textContent = 'Chưa trả lời';
                wrap.appendChild(empty);
            } else {
                wrap.appendChild(this.pill(options, submitted, 'incorrect'));
            }
            const arrow = document.createElement('span');
            arrow.className = 'rvx-arrow';
            arrow.setAttribute('aria-hidden', 'true');
            arrow.textContent = '→';
            wrap.appendChild(arrow);
            const key = this.pill(options, accepted[0] || '—', 'correct');
            key.classList.add('is-key');
            wrap.appendChild(key);
        }

        const sr = document.createElement('span');
        sr.className = 'rvx-sr-only';
        sr.textContent = 'Câu ' + entry.startNumber + ': '
            + ({ correct: 'đúng', incorrect: 'sai', empty: 'chưa trả lời' }[st]) + '.';
        wrap.appendChild(sr);
        return wrap;
    },

    pill(options, key, tone) {
        const el = ReviewCompletion.pill(this.label(options, key), tone);
        const k = ReviewMatching.matchKey(options, key);
        if (k) el.title = k + ' – ' + this.label(options, k);
        return el;
    },

    help(entry) {
        return ReviewCompletion.helpButton(entry, 0, entry.startNumber);
    },

    decorate(entry) {
        const q = entry.question;
        const el = document.getElementById('exam-q-' + q.id);
        if (!el || el.dataset.rvxDone) return;

        // Ô [[Q]] trong bài đọc
        if (el.classList.contains('exam-match-slot')) {
            const box = document.createElement('span');
            box.className = 'rvx-se-inline';
            box.id = el.id;
            box.dataset.rvxDone = '1';
            const num = document.createElement('span');
            num.className = 'rvx-num rvx-num--inline';
            num.textContent = entry.startNumber;
            box.appendChild(num);
            box.appendChild(this.answers(entry));
            box.appendChild(this.help(entry));
            el.replaceWith(box);
            return;
        }

        if (!el.querySelector('.exam-match-slot')) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        el.dataset.rvxDone = '1';

        // Câu văn: có ___ thì đặt viên đáp án vào đó, không thì nối cuối câu.
        const text = document.createElement('div');
        text.className = 'exam-question-text rvx-se-text';
        const html = q.text || '';
        if (window.IeltsBlanks && window.IeltsBlanks.count(html) > 0) {
            text.innerHTML = window.IeltsBlanks.render(html, [], true).html;
            const inputs = text.querySelectorAll('.exam-blank-input');
            inputs.forEach((input, i) => {
                if (i === 0) input.replaceWith(this.answers(entry));
                else input.replaceWith(document.createTextNode('…'));
            });
        } else {
            text.innerHTML = html;
            // Gắn vào cuối đoạn văn cuối cùng để không bị xuống dòng thừa.
            const blocks = text.querySelectorAll('p, div, li');
            const target = blocks.length ? blocks[blocks.length - 1] : text;
            target.appendChild(document.createTextNode(' '));
            target.appendChild(this.answers(entry));
        }

        const row = document.createElement('div');
        row.className = 'rvx-se-row';
        const num = document.createElement('span');
        num.className = 'rvx-num rvx-num--sc';
        num.textContent = entry.startNumber;
        const side = document.createElement('div');
        side.className = 'rvx-se-help';
        side.appendChild(this.help(entry));

        row.appendChild(num);
        row.appendChild(text);
        row.appendChild(side);

        el.innerHTML = '';
        el.classList.add('rvx-se-card', 'rvx-is-' + ReviewData.status(ReviewData.slotsOf(q.id)[0]));
        el.appendChild(row);
    },
};

/* =====================================================================
   Matching information (Reading) — bảng ma trận
     [số] Statement | A | B | C … | 💡
   Ô đáp án đúng: nền xanh + chấm xanh; ô học viên chọn sai: nền đỏ + chấm đỏ.
   ===================================================================== */
const RV_LABELING_TYPES = ['diagram_labeling', 'diagram_label', 'map_labeling'];

const ReviewMatrix = {
    /** Matching information (Reading) + Diagram / Map Labeling dạng A–K (mọi skill). */
    applies(entry) {
        if (!entry) return false;
        const type = entry.question.type;
        if (RV_LABELING_TYPES.includes(type)) return true;
        return type === 'matching_information' && AttemptState.skill !== 'listening';
    },

    decorate(entry) {
        const q = entry.question;
        const row = document.getElementById('exam-q-' + q.id);
        if (!row || row.dataset.rvxDone) return;
        if (!row.classList.contains('exam-matrix-row')) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        row.dataset.rvxDone = '1';

        const slot = ReviewData.slotsOf(q.id)[0];
        const st = ReviewData.status(slot);
        const accepted = ((slot && slot.accepted) || []).map(rvNorm);
        const picked = rvNorm(slot && slot.submitted);

        row.classList.add('rvx-mx-row', 'rvx-is-' + st);

        const num = row.querySelector('.exam-matrix-num');
        const RLw = window.ReviewListening;
        const listening = !!(RLw && RLw.enabled);
        if (num && listening) {
            // Listening: vòng số + ▶ nghe đoạn audio của câu (như các dạng khác).
            num.replaceWith(RLw.numberBlock(entry, entry.startNumber));
        } else if (num) {
            num.className = 'rvx-num rvx-mx-num';
        }
        row.querySelectorAll('.attempt-save-indicator').forEach((el) => el.remove());

        row.querySelectorAll('.exam-matrix-cell').forEach((td) => {
            const input = td.querySelector('input');
            const value = rvNorm(input && input.value);
            const isKey = accepted.includes(value);
            const isPicked = !!value && value === picked;

            td.classList.remove('selected');
            let tone = '';
            if (isKey) tone = 'correct';
            else if (isPicked) tone = 'incorrect';

            const dot = document.createElement('span');
            dot.className = 'rvx-mx-dot' + (tone ? ' is-' + tone : '');
            dot.setAttribute('aria-hidden', 'true');

            const label = td.querySelector('label') || td;
            if (input) input.classList.add('rvx-sr-only');
            label.appendChild(dot);

            if (tone) td.classList.add('rvx-mx-' + tone);
            if (isPicked) td.classList.add('rvx-mx-picked');
            if (input) {
                input.setAttribute('aria-label', 'Câu ' + entry.startNumber + ' – ' + input.value
                    + (isKey ? ' (đáp án đúng)' : '') + (isPicked ? ' (bạn chọn)' : ''));
            }
        });

        const table = row.closest('table');
        if (table) table.classList.add('rvx-mx');

        // Listening đã có tab "Answer Help" ở mép phải -> không thêm cột bóng đèn.
        if (listening) {
            if (table) table.classList.add('rvx-mx--listening');
            const head = table && table.querySelector('.exam-matrix-statement-head');
            if (head) head.textContent = '';
            return;
        }

        const helpTd = document.createElement('td');
        helpTd.className = 'rvx-mx-help';
        helpTd.appendChild(ReviewCompletion.helpButton(entry, 0, entry.startNumber));
        row.appendChild(helpTd);

        // Cột bóng đèn trên tiêu đề bảng (1 lần).
        const headRow = table && table.querySelector('thead tr');
        if (headRow && !headRow.querySelector('.rvx-mx-help-head')) {
            const th = document.createElement('th');
            th.className = 'rvx-mx-help-head';
            th.innerHTML = '<span class="rvx-sr-only">Answer Help</span>';
            headRow.appendChild(th);
            const stHead = headRow.querySelector('.exam-matrix-statement-head');
            if (stHead) stHead.textContent = 'Statement';
            table.classList.add('rvx-mx');
        }
    },
};

/* =====================================================================
   Listening — Sentence / Summary / Note completion, Short answer
   (trang làm bài gộp cả group vào 1 khối "blank sheet")
     [Public Service broadcast]                   <- khung tiêu đề (Group Title)
     [số ▶]  • Volunteer workers must be at least [18 ✔] years old.
     [số ▶]  • Job applicants should not have [criminal records ✖] → [police records ✔]
   Mỗi câu 1 thẻ viền; Answer Help mở bằng tab mép phải.
   ===================================================================== */
const ReviewListenSheet = {
    applies(entry) {
        return !!entry
            && AttemptState.skill === 'listening'
            && RV_LISTEN_SHEET_TYPES.includes(entry.question.type);
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;

        const text = card.querySelector('.exam-question-text');
        const inputs = Array.from(card.querySelectorAll('.exam-blank-input'));
        if (!text || !inputs.length || !card.closest('.exam-blank-sheet')) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        card.dataset.rvxDone = '1';

        const slots = ReviewData.slotsOf(q.id);
        card.querySelectorAll('.exam-blank-number, .exam-question-number, .attempt-save-indicator').forEach((el) => el.remove());

        inputs.forEach((input, i) => {
            input.replaceWith(ReviewCompletion.blankEl({
                entry,
                slot: slots[i],
                number: entry.startNumber + i,
                slotIndex: i,
                withNumber: i > 0,      // ô đầu: số ở cột trái
                withHelp: false,
            }));
        });

        const row = document.createElement('div');
        row.className = 'rvx-ls-row';
        let num;
        if (rvListening()) {
            num = window.ReviewListening.numberBlock(entry, entry.startNumber);
        } else {
            num = document.createElement('span');
            num.className = 'rvx-num';
            num.textContent = entry.startNumber;
        }

        const holder = document.createElement('span');
        text.replaceWith(holder);
        row.appendChild(num);
        row.appendChild(text);
        holder.replaceWith(row);

        card.classList.add('rvx-ls-card', 'rvx-is-' + ReviewData.entryStatus(entry));
        // Ghi chú nhiều dòng (tiêu đề mục, gạch đầu dòng riêng) -> không thêm chấm đầu dòng.
        if (text.querySelector('ul, ol, h1, h2, h3, h4, h5') || text.querySelectorAll('p').length > 1) {
            card.classList.add('is-rich');
        }
    },

    /** Khung tiêu đề: bỏ nếu Group Title chỉ là "Questions 1–5" (đã có tiêu đề nhóm). */
    decorateGroup(holder) {
        const sheet = holder.querySelector('.exam-blank-sheet');
        if (!sheet) return;
        sheet.classList.add('rvx-ls-sheet');

        const title = sheet.querySelector('.exam-blank-sheet-title');
        if (title && /^\s*questions?\s*\d/i.test(title.textContent)) title.remove();

        // Hướng dẫn của giáo viên đứng trước dòng giới hạn số từ, ngoài khung.
        const intro = sheet.querySelector('.exam-blank-sheet-intro');
        if (intro) {
            const limit = holder.querySelector('.exam-blank-sheet-instruction');
            sheet.parentNode.insertBefore(intro, limit || sheet);
        }
    },
};

/* =====================================================================
   Drag & Drop — Remove from list (disappear) / Keep in list (reuse)
   Mỗi chỗ trống thành 1 hàng:
     [câu / nhãn]          [số ▶]   [original, new ideas        ✔]
     [câu / nhãn]          [số ▶]   [retail experience          ✖]   <- học viên chọn
                                    [an understanding of food   ✔]   <- đáp án đúng
   Dòng không có chỗ trống (VD "Area of voluntary work") làm tiêu đề cột.
   Kho đáp án giữ ở dưới, hiện đủ (không làm mờ đáp án đã dùng).
   Reading: số là vòng tròn thường + bóng đèn theo từng ô.
   ===================================================================== */
const ReviewDragDrop = {
    applies(entry) {
        return !!entry && (entry.question.type === 'drag_drop_disappear' || entry.question.type === 'drag_drop_reuse');
    },

    answerRow(text, tone) {
        const row = document.createElement('span');
        row.className = 'rvx-dd-answer is-' + tone;
        const t = document.createElement('span');
        t.className = 'rvx-dd-answer-text';
        t.textContent = text;
        const icon = document.createElement('span');
        icon.className = 'rvx-dd-answer-icon';
        icon.innerHTML = '<i class="fas ' + (tone === 'correct' ? 'fa-check' : 'fa-times') + '" aria-hidden="true"></i>';
        row.appendChild(t);
        row.appendChild(icon);
        return row;
    },

    answersFor(slot) {
        const st = ReviewData.status(slot);
        const submitted = ReviewCompletion.submittedOf(slot);
        const accepted = ReviewCompletion.acceptedOf(slot);

        const box = document.createElement('div');
        box.className = 'rvx-dd-answers';
        if (st === 'correct') {
            box.appendChild(this.answerRow(submitted || accepted[0] || '', 'correct'));
        } else {
            const mine = this.answerRow(submitted, 'incorrect');
            if (!submitted) {
                mine.classList.add('is-blank');
                mine.querySelector('.rvx-dd-answer-text').textContent = 'Chưa trả lời';
            }
            box.appendChild(mine);
            const key = this.answerRow(accepted.join(' / ') || '—', 'correct');
            key.classList.add('is-key');
            box.appendChild(key);
        }
        return box;
    },

    /** Câu văn của 1 dòng, bỏ chỗ trống ở cuối; chỗ trống giữa câu -> "……". */
    statementOf(nodes, slotEl) {
        const box = document.createElement('div');
        box.className = 'rvx-dd-statement';
        nodes.forEach((n) => box.appendChild(n));
        const slots = Array.from(box.querySelectorAll('.exam-dd-slot'));
        slots.forEach((s) => {
            const gap = document.createElement('span');
            gap.className = 'rvx-dd-gap' + (s === slotEl ? ' is-self' : '');
            gap.textContent = '……';
            s.replaceWith(gap);
        });
        // Chỗ trống đứng cuối câu: bỏ luôn dấu "……" cho gọn.
        const gaps = box.querySelectorAll('.rvx-dd-gap');
        const last = gaps[gaps.length - 1];
        if (last && box.lastChild) {
            const range = document.createRange();
            range.setStartAfter(last);
            range.setEndAfter(box.lastChild);
            if (range.toString().replace(/[\s.:;,!?…]/g, '') === '') last.remove();
        }
        return box;
    },

    decorate(entry) {
        const q = entry.question;
        const card = document.getElementById('exam-q-' + q.id);
        if (!card || card.dataset.rvxDone) return;

        const text = card.querySelector('.exam-question-text');
        if (!text || !text.querySelector('.exam-dd-slot')) {
            ReviewMarker.decorateEntry(entry);
            return;
        }
        card.dataset.rvxDone = '1';

        const listening = rvListening();
        const slots = ReviewData.slotsOf(q.id);
        let slotIndex = 0;

        const list = document.createElement('div');
        list.className = 'rvx-dd-list' + (listening ? ' is-listening' : '');

        ReviewCompletion.splitLines(text).forEach((nodes) => {
            const holder = document.createElement('div');
            nodes.forEach((n) => holder.appendChild(n));
            const ddSlots = Array.from(holder.querySelectorAll('.exam-dd-slot'));

            if (!ddSlots.length) {
                if (!holder.textContent.trim() && !holder.querySelector('img, table')) return;
                holder.className = 'rvx-dd-heading';
                list.appendChild(holder);
                return;
            }

            ddSlots.forEach((slotEl, k) => {
                const index = slotIndex++;
                const number = entry.startNumber + index;
                const slot = slots[index];

                const row = document.createElement('div');
                row.className = 'rvx-dd-row rvx-is-' + ReviewData.status(slot);
                row.dataset.number = String(number);

                // Nhiều chỗ trống cùng 1 dòng: câu văn chỉ in ở hàng đầu.
                const statement = k === 0
                    ? this.statementOf(Array.from(holder.childNodes).map((n) => n.cloneNode(true)), null)
                    : Object.assign(document.createElement('div'), { className: 'rvx-dd-statement' });
                if (k === 0 && ddSlots.length > 1) {
                    // Đánh dấu chỗ trống của từng hàng bằng số câu.
                    statement.querySelectorAll('.rvx-dd-gap').forEach((g, gi) => { g.textContent = '(' + (number + gi) + ')'; });
                }

                let num;
                if (listening) {
                    num = window.ReviewListening.numberBlock(entry, number);
                } else {
                    num = document.createElement('span');
                    num.className = 'rvx-num';
                    num.textContent = number;
                }
                const numCol = document.createElement('div');
                numCol.className = 'rvx-dd-num';
                numCol.appendChild(num);

                row.appendChild(statement);
                row.appendChild(numCol);
                row.appendChild(this.answersFor(slot));
                if (!listening) {
                    const helpCol = document.createElement('div');
                    helpCol.className = 'rvx-dd-help';
                    helpCol.appendChild(ReviewCompletion.helpButton(entry, index, number));
                    row.appendChild(helpCol);
                }
                list.appendChild(row);
            });
        });

        text.replaceWith(list);
        card.querySelectorAll('.exam-question-number, .attempt-save-indicator').forEach((el) => el.remove());
        card.querySelectorAll('.exam-dd-chip').forEach((chip) => chip.classList.remove('used', 'active-pick'));
        card.classList.add('rvx-dd-card', 'rvx-is-' + ReviewData.entryStatus(entry));
    },
};

/* =====================================================================
   Sidebar "Giải thích đáp án" — theo TỪNG SỐ CÂU, mọi dạng bài.

   Thứ tự (mục nào không có dữ liệu thì ẩn):
     Đáp án đúng / đáp án của bạn
     1. Phân tích câu hỏi   (câu hỏi + từ khoá tô vàng + bản dịch, phương án,
                              phân tích của giáo viên, từ vựng quan trọng)
     2. Đối chiếu paraphrase
     3. Trích dẫn trong bài  (Reading: đoạn trích + "Xem vị trí trong bài";
                              Listening: trích transcript + "Nghe đoạn này")
     4. Vì sao các đáp án khác không đúng (theo dạng bài + tự phân tích bài điền từ)
     5. Vì sao bạn làm sai câu này? (câu sai / bỏ trống — lưu DB)
     6. Tài liệu IELTS liên quan  (giáo viên nhập theo đề)

   Dữ liệu: REVIEW_RESULTS[qid].explain.slots[i] (App\Support\Ielts\ReviewExplanation).
   Câu chưa có -> dùng REVIEW_RESULTS[qid].explanation (ô Answer help cũ).
   ===================================================================== */
const RV_BLANK_RE = /[_＿‗](?:[\s ]*[_＿‗])+/g;

// Dạng có danh sách phương án (giáo viên chọn phương án gây nhiễu để giải thích).
const RV_OPTION_BANK_TYPES = [
    'matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings',
    'diagram_labeling', 'diagram_label', 'map_labeling', 'drag_drop_disappear', 'drag_drop_reuse',
];

const RV_TFNG_LABELS = {
    true_false_not_given: ['TRUE', 'FALSE', 'NOT GIVEN'],
    yes_no_not_given: ['YES', 'NO', 'NOT GIVEN'],
};

const ReviewExplain = {
    /** Dữ liệu giải thích của 1 ô (MCQ nhiều đáp án dùng chung ô 0). */
    slotData(entry, slotIndex) {
        const ex = ReviewData.resultOf(entry.question.id).explain;
        const slots = ex && ex.slots;
        if (!slots) return null;
        const idx = ReviewMcq.isMcq(entry) ? 0 : (slotIndex || 0);
        return slots[idx] || slots[String(idx)] || null;
    },

    /** HTML -> chữ thuần, xuống dòng theo khối. */
    toLines(html) {
        const box = document.createElement('div');
        box.innerHTML = String(html || '')
            .replace(/<br\s*\/?>/gi, '\n')
            .replace(/<\/(p|div|li|h\d|tr)>/gi, '\n')
            .replace(/<\/t[dh]>/gi, ' \t ');
        return (box.textContent || '').replace(/ /g, ' ').split(/\n+/)
            .map((l) => l.replace(/\s*\t\s*/g, ' | ').replace(/\s+/g, ' ').replace(/^\|\s*|\s*\|$/g, '').trim())
            .filter(Boolean);
    },

    /** Câu chứa chỗ trống thứ slotIndex (đếm theo thứ tự đọc). Chỗ trống đó -> "____". */
    blankLine(lines, slotIndex) {
        let seen = 0;
        for (let i = 0; i < lines.length; i++) {
            const count = (lines[i].match(RV_BLANK_RE) || []).length;
            if (slotIndex < seen + count) {
                let k = 0;
                const target = slotIndex - seen;
                return lines[i].replace(RV_BLANK_RE, () => (k++ === target ? '____' : '…'));
            }
            seen += count;
        }
        return '';
    },

    /** Nội dung câu hỏi của đúng số câu đang xem (chữ thuần). */
    questionText(entry, slotIndex) {
        const q = entry.question;
        const multi = entry.slotCount > 1 && !ReviewMcq.isMcq(entry);

        if (!multi) return this.toLines(q.text).join(' ').replace(RV_BLANK_RE, '____');

        if (q.type === 'table_completion' && q.table_structure) {
            const t = q.table_structure;
            const headers = Array.isArray(t.headers) ? t.headers : [];
            let seen = 0;
            const rows = Array.isArray(t.rows) ? t.rows : [];
            for (let r = 0; r < rows.length; r++) {
                const cells = Array.isArray(rows[r]) ? rows[r] : [];
                for (let c = 0; c < cells.length; c++) {
                    const text = this.toLines(cells[c]).join(' ');
                    const count = (text.match(RV_BLANK_RE) || []).length;
                    if (slotIndex < seen + count) {
                        let k = 0;
                        const target = slotIndex - seen;
                        const cell = text.replace(RV_BLANK_RE, () => (k++ === target ? '____' : '…'));
                        const head = this.toLines(headers[c]).join(' ');
                        const rowHead = c > 0 ? this.toLines(cells[0]).join(' ') : '';
                        return (rowHead ? rowHead + ' – ' : '') + (head ? head + ': ' : '') + cell;
                    }
                    seen += count;
                }
            }
            return '';
        }

        let html = q.text;
        if (window.IeltsFlowchart && window.IeltsFlowchart.isFlowType(q.type)) {
            const flow = window.IeltsFlowchart.fromQuestion(q);
            if (flow) html = window.IeltsFlowchart.toPlainHtml(flow);
        }
        return this.blankLine(this.toLines(html), slotIndex);
    },

    /** Tô vàng từ khoá (cột trái bảng paraphrase + từ vựng) trong câu hỏi. */
    highlight(text, data) {
        let html = rvEscape(text).replace(/____/g, '<span class="rvh-gap">____</span>');
        if (!data) return html;

        const terms = [];
        ((data.paraphrase && data.paraphrase.rows) || []).forEach((r) => { if (r.question) terms.push(r.question); });
        (data.vocab || []).forEach((v) => { if (v.term) terms.push(v.term); });

        terms
            .map((t) => String(t).trim())
            .filter((t) => t.length > 1)
            .sort((a, b) => b.length - a.length)
            .forEach((term) => {
                const safe = rvEscape(term).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                // Không tô lại bên trong thẻ đã chèn.
                const re = new RegExp('(^|[^\\p{L}\\p{N}>])(' + safe + ')(?=$|[^\\p{L}\\p{N}])(?![^<]*>)', 'giu');
                html = html.replace(re, '$1<mark class="rvh-kw">$2</mark>');
            });
        return html;
    },

    // ── Tự phân tích đáp án điền từ của học viên ─────────────────────
    words(s) {
        return rvNorm(s).replace(/[.,;:!?"'()]/g, ' ').split(/\s+/).filter(Boolean);
    },

    distance(a, b) {
        if (a === b) return 0;
        if (Math.abs(a.length - b.length) > 3) return 99;
        const prev = [];
        for (let j = 0; j <= b.length; j++) prev[j] = j;
        for (let i = 1; i <= a.length; i++) {
            let diag = prev[0];
            prev[0] = i;
            for (let j = 1; j <= b.length; j++) {
                const tmp = prev[j];
                prev[j] = Math.min(prev[j] + 1, prev[j - 1] + 1, diag + (a[i - 1] === b[j - 1] ? 0 : 1));
                diag = tmp;
            }
        }
        return prev[b.length];
    },

    plural(a, b) {
        const forms = (w) => [w + 's', w + 'es', w.replace(/y$/, 'ies')];
        return forms(a).includes(b) || forms(b).includes(a);
    },

    /** Mảng chuỗi lý do tự phát hiện cho 1 ô điền từ sai. */
    autoReasons(entry, slot) {
        const out = [];
        const submitted = String(slot && slot.submitted != null ? slot.submitted : '').trim();
        if (!submitted || slot.correct) return out;

        const accepted = ((slot.parts && slot.parts.length ? slot.parts.flatMap((p) => p.accepted || []) : slot.accepted) || [])
            .map((a) => String(a).trim()).filter(Boolean);
        const subWords = this.words(submitted);
        const max = parseInt(entry.group && entry.group.max_words, 10) || 0;

        if (max > 0 && subWords.length > max) {
            out.push('Vượt giới hạn số từ: đề yêu cầu tối đa <b>' + max + ' từ</b>, bạn viết <b>' + subWords.length + ' từ</b>.');
        }

        const sub = subWords.join(' ');
        let best = null;
        accepted.forEach((a) => {
            const key = this.words(a).join(' ');
            if (!key) return;
            const d = this.distance(sub, key);
            if (!best || d < best.d) best = { a, key, d };
        });
        if (!best) return out;

        const key = best.key;
        const keyWords = key.split(' ');
        if (subWords.length === keyWords.length && subWords.every((w, i) => w === keyWords[i] || this.plural(w, keyWords[i]))
            && sub !== key) {
            out.push('Sai dạng <b>số ít / số nhiều</b>: bạn viết “' + rvEscape(submitted) + '”, đáp án là “' + rvEscape(best.a) + '”.');
        } else if (best.d > 0 && best.d <= (key.length >= 6 ? 2 : 1)) {
            out.push('Gần đúng nhưng <b>sai chính tả</b>: bạn viết “' + rvEscape(submitted) + '”, đáp án là “' + rvEscape(best.a) + '”.');
        } else if ((' ' + sub + ' ').includes(' ' + key + ' ') && sub !== key) {
            out.push('<b>Thừa từ</b>: bạn viết “' + rvEscape(submitted) + '”, đáp án chỉ cần “' + rvEscape(best.a) + '”.');
        } else if ((' ' + key + ' ').includes(' ' + sub + ' ') && sub !== key) {
            out.push('<b>Thiếu từ</b>: bạn viết “' + rvEscape(submitted) + '”, đáp án đầy đủ là “' + rvEscape(best.a) + '”.');
        }
        return out;
    },

    /** Tìm khoá giải thích (không phân biệt hoa thường). */
    reasonFor(options, key) {
        if (!options || key === null || key === undefined) return null;
        const k = rvNorm(key);
        const hit = Object.keys(options).find((x) => rvNorm(x) === k);
        return hit ? options[hit] : null;
    },
};

const AnswerHelp = {
    root: null,
    body: null,
    title: null,
    entry: null,
    slotIndex: null,
    returnFocus: null,
    collapsed: {},     // mục đang thu gọn (giữ khi chuyển câu)

    init() {
        this.root = document.getElementById('rvxDrawer');
        if (!this.root) return;
        this.body = document.getElementById('rvxDrawerBody');
        this.title = document.getElementById('rvxDrawerTitle');
        DrawerResize.init(this.root.querySelector('.rvx-drawer-panel'));

        this.root.querySelectorAll('[data-drawer-close]').forEach((el) => {
            el.addEventListener('click', () => this.close());
        });

        this.body.addEventListener('click', (e) => {
            const t = e.target;
            if (!t.closest) return;

            // Listening: "Nghe đoạn này" trong mục Trích transcript.
            const play = t.closest('.rvl-help-play');
            if (play && this.entry && window.ReviewListening) {
                const entry = this.entry;
                this.close();
                window.ReviewListening.playEntry(entry);
                return;
            }

            // Reading: "Xem vị trí trong bài".
            const locate = t.closest('[data-rvh-locate]');
            if (locate) {
                this.locateEvidence(locate);
                return;
            }

            // Mở / thu gọn 1 mục.
            const head = t.closest('.rvh-sec-head');
            if (head) {
                const sec = head.closest('.rvh-sec');
                const open = sec.classList.toggle('is-collapsed') === false;
                head.setAttribute('aria-expanded', open ? 'true' : 'false');
                this.collapsed[sec.dataset.sec] = !open;
                return;
            }

            MistakeReason.onClick(e);
        });

        this.body.addEventListener('input', (e) => MistakeReason.onInput(e));

        document.addEventListener('keydown', (e) => {
            // Đang mở cửa sổ báo lỗi thì Esc chỉ đóng cửa sổ đó.
            if (e.key === 'Escape' && this.isOpen() && !ReportModal.isOpen()) {
                if (MistakeReason.closeMenu()) return;
                this.close();
            }
        });
    },

    isOpen() {
        return !!(this.root && !this.root.hidden && this.root.classList.contains('is-open'));
    },

    choiceLabel(entry, value) {
        if (value === null || value === undefined || String(value).trim() === '') return '';
        if (ReviewMatching.applies(entry) || ReviewEndings.applies(entry) || ReviewMatrix.applies(entry)) {
            const options = entry.question.options || {};
            const key = ReviewMatching.matchKey(options, value) || String(value);
            return ExamRenderers.matchingLabel(options, key);
        }
        return RV_TFNG_TYPES.includes(entry.question.type) ? String(value).toUpperCase() : String(value);
    },

    /** Đáp án đúng dạng chữ: các ô cách nhau ", ", đáp án thay thế " / ". */
    keyText(entry) {
        if (ReviewMcq.isMcq(entry)) return ReviewMcq.keyLetters(entry);

        return ReviewData.slotsOf(entry.question.id)
            .map((s) => (s.accepted || []).map((a) => this.choiceLabel(entry, a)).join(' / '))
            .filter(Boolean).join(', ');
    },

    /** Đáp án đúng của 1 slot; ô bảng nhiều chỗ trống: các chỗ trống cách nhau ", ". */
    slotKeyText(slot) {
        if (Array.isArray(slot.parts) && slot.parts.length > 1) {
            return slot.parts.map((p) => (p.accepted || []).join(' / ')).join(', ');
        }
        return (slot.accepted || []).join(' / ');
    },

    // ── Khung từng mục ───────────────────────────────────────────────
    section(key, icon, title, inner, extraClass) {
        if (!inner) return '';
        const collapsed = !!this.collapsed[key];
        return '<section class="rvh-sec' + (extraClass ? ' ' + extraClass : '') + (collapsed ? ' is-collapsed' : '') + '" data-sec="' + key + '">'
            + '  <span class="rvh-sec-icon" aria-hidden="true">' + icon + '</span>'
            + '  <button type="button" class="rvh-sec-head" aria-expanded="' + (collapsed ? 'false' : 'true') + '">'
            + '    <span>' + title + '</span><i class="fas fa-chevron-down" aria-hidden="true"></i>'
            + '  </button>'
            + '  <div class="rvh-sec-body">' + inner + '</div>'
            + '</section>';
    },

    /** Ô "Đáp án đúng" (+ "Đáp án của bạn" khi sai / chưa trả lời). */
    answersHtml(entry, slot, st) {
        const isMcq = ReviewMcq.isMcq(entry);
        let keys;
        let yours;

        if (isMcq) {
            const states = ReviewMcq.optionStates(entry);
            keys = states.filter((o) => o.key).map((o) => o.letter).join(', ');
            yours = states.filter((o) => o.picked).map((o) => o.letter).join(', ');
        } else {
            keys = slot ? (this.slotKeyText(slot).split(' / ').map((a) => this.choiceLabel(entry, a)).join(' / ')) : this.keyText(entry);
            yours = slot ? this.choiceLabel(entry, slot.submitted) : '';
        }

        const keyBox = '<div class="rvh-ans is-key"><span>Đáp án đúng</span><b>' + rvEscape(keys || '—') + '</b></div>';
        if (st === 'correct') {
            return '<div class="rvh-answers">' + keyBox + '<div class="rvh-ans-ok"><i class="fas fa-check-circle" aria-hidden="true"></i> Bạn trả lời đúng</div></div>';
        }
        const yourBox = st === 'empty'
            ? '<div class="rvh-ans is-empty"><span>Đáp án của bạn</span><b>Chưa trả lời</b></div>'
            : '<div class="rvh-ans is-wrong"><span>Đáp án của bạn</span><b>' + rvEscape(yours || '—') + '</b></div>';
        return '<div class="rvh-answers">' + keyBox + yourBox + '</div>';
    },

    /** 1. Phân tích câu hỏi */
    analysisHtml(entry, slotIndex, data, legacy) {
        const q = entry.question;
        let html = '';

        const text = ReviewExplain.questionText(entry, slotIndex);
        if (text || (data && data.translation)) {
            html += '<div class="rvh-question">'
                + (text ? '<p class="rvh-q-text">' + ReviewExplain.highlight(text, data) + '</p>' : '')
                + (data && data.translation ? '<p class="rvh-q-trans">(' + rvEscape(data.translation) + ')</p>' : '')
                + '</div>';
        }

        // Phương án (MCQ): tô đáp án đúng / phương án đã chọn sai.
        if (ReviewMcq.isMcq(entry)) {
            html += '<ul class="rvh-options">' + ReviewMcq.optionStates(entry).map((o) => {
                const tone = o.key ? 'is-key' : (o.picked ? 'is-wrong' : '');
                return '<li class="' + tone + '"><span class="rvh-opt-letter">' + o.letter + '</span><span>' + rvEscape(o.text) + '</span></li>';
            }).join('') + '</ul>';
        }

        // TFNG: nhãn đúng
        if (RV_TFNG_TYPES.includes(q.type)) {
            const labels = RV_TFNG_LABELS[q.type] || [];
            const slot = ReviewData.slotsOf(q.id)[0] || {};
            const key = rvNorm((slot.accepted || [])[0]);
            const picked = rvNorm(slot.submitted);
            html += '<ul class="rvh-options rvh-options--inline">' + labels.map((l) => {
                const n = rvNorm(l);
                const tone = n === key ? 'is-key' : (n === picked ? 'is-wrong' : '');
                return '<li class="' + tone + '"><span>' + l + '</span></li>';
            }).join('') + '</ul>';
        }

        const body = (data && data.analysis) || legacy;
        if (body) html += '<div class="rvh-rich">' + body + '</div>';

        if (data && data.vocab && data.vocab.length) {
            html += '<div class="rvh-vocab"><h5>Từ vựng quan trọng:</h5><ul>'
                + data.vocab.map((v) => '<li><b>' + rvEscape(v.term) + '</b>' + (v.meaning ? ': ' + rvEscape(v.meaning) : '') + '</li>').join('')
                + '</ul></div>';
        }

        return html || '<p class="rvh-empty">Chưa có giải thích cho câu này.</p>';
    },

    /** 2. Đối chiếu paraphrase */
    paraphraseHtml(data) {
        const p = data && data.paraphrase;
        if (!p || (!(p.rows && p.rows.length) && !p.note)) return '';
        let html = '';
        if (p.rows && p.rows.length) {
            html += '<table class="rvh-para"><thead><tr><th>Keywords trong câu hỏi / đáp án</th><th>Cụm tương ứng trong bài</th></tr></thead><tbody>'
                + p.rows.map((r) => '<tr><td>' + rvEscape(r.question || '') + '</td><td>' + rvEscape(r.passage || '') + '</td></tr>').join('')
                + '</tbody></table>';
        }
        if (p.note) html += '<div class="rvh-rich rvh-para-note">' + p.note + '</div>';
        return html;
    },

    /** 3. Trích dẫn trong bài */
    evidenceHtml(entry, data) {
        if (window.ReviewListening && window.ReviewListening.enabled) {
            const ex = window.ReviewListening.excerptHtml(entry);
            // Bỏ tiêu đề riêng của khối transcript (đã có tiêu đề mục).
            return ex ? ex.replace(/<h4>[\s\S]*?<\/h4>/, '') : '';
        }
        if (!data || !data.evidence) return '';
        return '<blockquote class="rvh-quote">“' + rvEscape(data.evidence) + '”</blockquote>'
            + '<button type="button" class="rvh-locate" data-rvh-locate="' + rvEscape(data.evidence) + '">'
            + '<i class="fas fa-search-location" aria-hidden="true"></i> Xem vị trí trong bài</button>'
            + '<span class="rvh-locate-msg" role="status" aria-live="polite"></span>';
    },

    /** 4. Vì sao các đáp án khác không đúng — theo dạng bài. */
    wrongHtml(entry, slot, data) {
        const q = entry.question;
        const options = data && data.wrong && data.wrong.options;
        const item = (label, reason, mine, tag) => '<div class="rvh-wrong' + (mine ? ' is-mine' : '') + '">'
            + '<p class="rvh-wrong-head">' + label + (mine ? ' <span class="rvh-tag">' + (tag || 'Bạn đã chọn') + '</span>' : '') + '</p>'
            + (reason ? '<div class="rvh-rich">' + reason + '</div>' : '')
            + '</div>';

        // Multiple choice: từng phương án sai.
        if (ReviewMcq.isMcq(entry)) {
            const rows = ReviewMcq.optionStates(entry).filter((o) => !o.key).map((o) => ({
                o, reason: ReviewExplain.reasonFor(options, o.letter) || ReviewExplain.reasonFor(options, o.text),
            })).filter((r) => r.reason || r.o.picked);
            if (!rows.some((r) => r.reason)) return '';
            rows.sort((a, b) => (b.o.picked ? 1 : 0) - (a.o.picked ? 1 : 0));
            return rows.map((r) => item('<b>Đáp án ' + r.o.letter + ' không đúng</b>', r.reason, r.o.picked)).join('');
        }

        // TRUE / FALSE / NOT GIVEN: 2 nhãn còn lại.
        if (RV_TFNG_TYPES.includes(q.type)) {
            const key = rvNorm((slot && slot.accepted || [])[0]);
            const picked = rvNorm(slot && slot.submitted);
            const rows = (RV_TFNG_LABELS[q.type] || []).filter((l) => rvNorm(l) !== key).map((l) => ({
                l, reason: ReviewExplain.reasonFor(options, l), mine: rvNorm(l) === picked,
            })).filter((r) => r.reason);
            rows.sort((a, b) => (b.mine ? 1 : 0) - (a.mine ? 1 : 0));
            return rows.map((r) => item('<b>Không phải ' + r.l + '</b>', r.reason, r.mine)).join('');
        }

        // Có danh sách phương án (Matching, Labeling A–K, Drag & drop): phương án gây nhiễu.
        const hasOptionBank = RV_OPTION_BANK_TYPES.includes(q.type);
        if (hasOptionBank) {
            if (!options) return '';
            const accepted = ((slot && slot.accepted) || []).map(rvNorm);
            const picked = rvNorm(slot && slot.submitted);
            const rows = Object.keys(options).filter((k) => !accepted.includes(rvNorm(k))).map((k) => ({
                k, reason: options[k], mine: rvNorm(k) === picked,
            }));
            rows.sort((a, b) => (b.mine ? 1 : 0) - (a.mine ? 1 : 0));
            return rows.map((r) => item('<b>' + rvEscape(this.choiceLabel(entry, r.k) || r.k) + '</b> không đúng', r.reason, r.mine)).join('');
        }

        // Dạng điền từ: tự phân tích bài làm + đáp án sai thường gặp.
        let html = '';
        const auto = ReviewExplain.autoReasons(entry, slot);
        if (auto.length) {
            html += '<div class="rvh-auto"><p class="rvh-wrong-head"><b>Phân tích đáp án của bạn</b></p><ul>'
                + auto.map((a) => '<li>' + a + '</li>').join('') + '</ul></div>';
        }
        // Bỏ dòng trùng (cùng đáp án sai + cùng lý do).
        const seen = {};
        const common = ((data && data.wrong && data.wrong.common) || []).filter((c) => {
            const k = rvNorm(c.answer) + '|' + rvNorm(String(c.reason || '').replace(/<[^>]*>/g, ''));
            if (seen[k]) return false;
            seen[k] = true;
            return true;
        });
        const mine = rvNorm(slot && slot.submitted);
        html += common.map((c) => item('Viết “<b>' + rvEscape(c.answer) + '</b>” là sai', c.reason, mine && rvNorm(c.answer) === mine, 'Lỗi của bạn')).join('');
        return html;
    },

    render(entry, focus) {
        const q = entry.question;
        const slots = ReviewData.slotsOf(q.id);
        const isMcq = ReviewMcq.isMcq(entry);

        let slotIndex = focus && focus.slotIndex !== undefined && focus.slotIndex !== null ? focus.slotIndex : 0;
        if (isMcq || slotIndex >= slots.length) slotIndex = 0;
        this.slotIndex = slotIndex;

        const multi = !isMcq && slots.length > 1;
        const slot = isMcq ? null : slots[slotIndex];
        const st = multi ? ReviewData.status(slot) : ReviewData.entryStatus(entry);
        const number = multi ? String(entry.startNumber + slotIndex) : rvNumberLabel(entry);
        this.focusNumber = multi ? number : null;
        this.title.textContent = 'Giải thích đáp án – Câu ' + number;

        const data = ReviewExplain.slotData(entry, slotIndex);
        const legacy = ReviewData.resultOf(q.id).explanation || '';
        const focusIndex = focus && focus.optionIndex !== undefined ? focus.optionIndex : null;
        const isListening = !!(window.ReviewListening && window.ReviewListening.enabled);

        this.body.innerHTML = ''
            + this.answersHtml(entry, multi ? slot : (isMcq ? null : slots[0]), st)
            + (isMcq && focusIndex !== null ? this.mcqFocusHtml(entry, focusIndex) : '')
            + '<div class="rvh-flow">'
            + this.section('analysis', '<i class="far fa-lightbulb"></i>', 'Phân tích câu hỏi', this.analysisHtml(entry, slotIndex, data, legacy))
            + this.section('paraphrase', '<i class="fas fa-link"></i>', 'Đối chiếu paraphrase', this.paraphraseHtml(data))
            + this.section('evidence', '<i class="fas fa-quote-left"></i>', isListening ? 'Trích transcript' : 'Trích dẫn trong bài', this.evidenceHtml(entry, data))
            + this.section('wrong', '<i class="fas fa-times"></i>', 'Vì sao các đáp án khác không đúng', this.wrongHtml(entry, slot || slots[0], data))
            + this.section('mistake', '<i class="fas fa-question"></i>', 'Vì sao bạn làm sai câu này?', st === 'correct' ? '' : MistakeReason.html(entry, slotIndex, number), 'rvh-sec--mistake')
            + this.section('resources', '<i class="fas fa-book-open"></i>', 'Tài liệu IELTS liên quan', this.resourcesHtml())
            + '</div>';

        this.body.scrollTop = 0;
    },

    /** 6. Tài liệu IELTS liên quan (giáo viên nhập theo đề). */
    resourcesHtml() {
        const list = (window.REVIEW_META && window.REVIEW_META.resources) || [];
        if (!list.length) return '';
        return list.map((r) => '<div class="rvh-res">'
            + (r.url
                ? '<a href="' + rvEscape(r.url) + '" target="_blank" rel="noopener">' + rvEscape(r.title) + ' <i class="fas fa-arrow-right" aria-hidden="true"></i></a>'
                : '<b>' + rvEscape(r.title) + '</b>')
            + (r.description ? '<p>' + rvEscape(r.description) + '</p>' : '')
            + '</div>').join('');
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

    /** Reading: tìm đoạn trích trong bài đọc, tô và cuộn tới. */
    locateEvidence(btn) {
        const quote = btn.getAttribute('data-rvh-locate') || '';
        const msg = btn.parentNode.querySelector('.rvh-locate-msg');
        const roots = Array.from(document.querySelectorAll('#rvxContext .exam-context-passage, #rvxContext .exam-part-instructions'));
        const norm = (s) => s.replace(/\s+/g, ' ').toLowerCase();
        const needle = norm(quote.replace(/^[“"']|[”"']$/g, '').trim());

        document.querySelectorAll('mark.rvh-evidence').forEach((m) => {
            const p = m.parentNode;
            while (m.firstChild) p.insertBefore(m.firstChild, m);
            p.removeChild(m);
            p.normalize();
        });

        for (const root of roots) {
            const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
            const nodes = [];
            let text = '';
            while (walker.nextNode()) {
                const n = walker.currentNode;
                // Ánh xạ từng ký tự của chuỗi đã gộp khoảng trắng về node gốc.
                for (let i = 0; i < n.nodeValue.length; i++) {
                    const ch = n.nodeValue[i];
                    if (/\s/.test(ch)) {
                        if (text.endsWith(' ')) continue;
                        text += ' ';
                    } else {
                        text += ch.toLowerCase();
                    }
                    nodes.push([n, i]);
                }
            }
            const at = text.indexOf(needle);
            if (at < 0) continue;

            const start = nodes[at];
            const end = nodes[at + needle.length - 1];
            const range = document.createRange();
            range.setStart(start[0], start[1]);
            range.setEnd(end[0], end[1] + 1);

            const hits = [];
            const w2 = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
            while (w2.nextNode()) if (range.intersectsNode(w2.currentNode)) hits.push(w2.currentNode);
            let first = null;
            hits.forEach((node) => {
                let s = node === range.startContainer ? range.startOffset : 0;
                let e = node === range.endContainer ? range.endOffset : node.nodeValue.length;
                if (e <= s) return;
                const target = s > 0 ? node.splitText(s) : node;
                if (e - s < target.nodeValue.length) target.splitText(e - s);
                const mark = document.createElement('mark');
                mark.className = 'rvh-evidence';
                target.parentNode.insertBefore(mark, target);
                mark.appendChild(target);
                if (!first) first = mark;
            });

            this.close();
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (msg) msg.textContent = 'Không tìm thấy đoạn trích trong bài đọc.';
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

    /** Đang mở mà chuyển câu (hoặc sang ô khác của cùng câu) -> đổi nội dung. */
    follow(entry, number) {
        if (!this.isOpen() || !entry) return;
        const slotIndex = number ? Math.max(0, number - entry.startNumber) : 0;
        if (!this.entry || this.entry.question.id !== entry.question.id || (entry.slotCount > 1 && slotIndex !== this.slotIndex)) {
            this.entry = entry;
            this.render(entry, { slotIndex });
        }
    },

    close() {
        if (!this.root || this.root.hidden) return;
        if (ReportModal.isOpen()) ReportModal.close();
        MistakeReason.flush();
        this.root.classList.remove('is-open');
        document.body.classList.remove('rvx-drawer-open');

        const done = () => { this.root.hidden = true; };
        const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) done(); else window.setTimeout(done, 260);

        if (this.returnFocus && document.body.contains(this.returnFocus)) this.returnFocus.focus();
        this.returnFocus = null;
    },
};

/* ── Kéo giãn chiều ngang sidebar (mép trái) ─────────────────────────────
   Kéo bằng chuột / cảm ứng, hoặc focus vào tay nắm rồi dùng phím ← →.
   Nhấp đúp = về độ rộng mặc định. Độ rộng được nhớ cho lần sau (localStorage).
   Màn hình hẹp (< 768px) sidebar luôn full màn hình, không kéo được. */
const DrawerResize = {
    KEY: 'ielts-review-drawer-width',
    MIN: 420,
    DEFAULT: 640,
    panel: null,
    handle: null,

    max() {
        return Math.max(this.MIN, Math.round(window.innerWidth * 0.92));
    },

    enabled() {
        return window.innerWidth >= 768;
    },

    apply(width, save) {
        if (!this.panel) return;
        if (!this.enabled() || !width) {
            this.panel.style.width = '';
            return;
        }
        const w = Math.round(Math.min(this.max(), Math.max(this.MIN, width)));
        this.panel.style.width = w + 'px';
        if (this.handle) this.handle.setAttribute('aria-valuenow', String(w));
        if (save) {
            try { window.localStorage.setItem(this.KEY, String(w)); } catch (e) { /* bỏ qua */ }
        }
    },

    saved() {
        try { return parseInt(window.localStorage.getItem(this.KEY), 10) || 0; } catch (e) { return 0; }
    },

    init(panel) {
        if (!panel || this.panel) return;
        this.panel = panel;

        const handle = document.createElement('div');
        handle.className = 'rvx-drawer-resize';
        handle.setAttribute('role', 'separator');
        handle.setAttribute('aria-orientation', 'vertical');
        handle.setAttribute('aria-label', 'Kéo để đổi độ rộng khung giải thích');
        handle.setAttribute('aria-valuemin', String(this.MIN));
        handle.setAttribute('tabindex', '0');
        handle.title = 'Kéo để đổi độ rộng · nhấp đúp để về mặc định';
        panel.appendChild(handle);
        this.handle = handle;

        this.apply(this.saved(), false);

        handle.addEventListener('pointerdown', (e) => {
            if (!this.enabled() || e.button !== 0) return;
            e.preventDefault();
            handle.setPointerCapture(e.pointerId);
            document.body.classList.add('rvx-drawer-resizing');

            const move = (ev) => this.apply(window.innerWidth - ev.clientX, false);
            const up = (ev) => {
                handle.removeEventListener('pointermove', move);
                handle.removeEventListener('pointerup', up);
                handle.removeEventListener('pointercancel', up);
                document.body.classList.remove('rvx-drawer-resizing');
                this.apply(window.innerWidth - ev.clientX, true);
            };
            handle.addEventListener('pointermove', move);
            handle.addEventListener('pointerup', up);
            handle.addEventListener('pointercancel', up);
        });

        handle.addEventListener('dblclick', () => {
            try { window.localStorage.removeItem(this.KEY); } catch (e) { /* bỏ qua */ }
            this.apply(0, false);
        });

        handle.addEventListener('keydown', (e) => {
            if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
            e.preventDefault();
            const current = panel.getBoundingClientRect().width;
            this.apply(current + (e.key === 'ArrowLeft' ? 40 : -40), true);
        });

        // Thu nhỏ cửa sổ trình duyệt -> không để sidebar rộng hơn màn hình.
        window.addEventListener('resize', () => this.apply(this.saved() || 0, false));
    },
};

/* ── "Vì sao bạn làm sai câu này?" ──────────────────────────────────── */
const MistakeReason = {
    timer: null,
    pending: null,

    meta() {
        return (window.REVIEW_META && window.REVIEW_META.mistake) || null;
    },

    key(entry, slotIndex) {
        return entry.question.id + ':' + (slotIndex || 0);
    },

    saved(entry, slotIndex) {
        const m = this.meta();
        return (m && m.saved && m.saved[this.key(entry, slotIndex)]) || null;
    },

    html(entry, slotIndex, number) {
        const m = this.meta();
        if (!m || !m.reasons || !m.reasons.length) return '';
        const saved = this.saved(entry, slotIndex);
        const label = saved ? ((m.reasons.find((r) => r.code === saved.code) || {}).label || '') : '';
        const isOther = saved && saved.code === m.other;

        // Người xem không phải học viên làm bài (giáo viên…): chỉ xem.
        if (!m.url) {
            return saved
                ? '<p class="rvh-mistake-ro">' + rvEscape(label) + (isOther && saved.text ? ': ' + rvEscape(saved.text) : '') + '</p>'
                : '<p class="rvh-empty">Học viên chưa chọn lý do.</p>';
        }

        const text = isOther && saved.text ? saved.text : '';
        return '<div class="rvh-mistake" data-question-id="' + entry.question.id + '" data-slot="' + (slotIndex || 0) + '" data-number="' + rvEscape(number) + '">'
            + '  <button type="button" class="rvh-select' + (saved ? ' has-value' : '') + '" aria-haspopup="listbox" aria-expanded="false">'
            + '    <span>' + rvEscape(label || 'Chọn lý do') + '</span><i class="fas fa-chevron-down" aria-hidden="true"></i>'
            + '  </button>'
            + '  <ul class="rvh-menu" role="listbox" hidden>'
            + m.reasons.map((r) => '<li role="option" data-code="' + rvEscape(r.code) + '" aria-selected="' + (saved && saved.code === r.code ? 'true' : 'false') + '">' + rvEscape(r.label) + '</li>').join('')
            + '  </ul>'
            + '  <div class="rvh-other"' + (isOther ? '' : ' hidden') + '>'
            + '    <textarea maxlength="' + m.max + '" rows="4" placeholder="Nhập lý do của bạn...">' + rvEscape(text) + '</textarea>'
            + '    <span class="rvh-count">' + text.length + '/' + m.max + '</span>'
            + '  </div>'
            + '  <p class="rvh-saved" role="status" aria-live="polite"></p>'
            + '</div>';
    },

    closeMenu() {
        const open = document.querySelector('.rvh-mistake .rvh-menu:not([hidden])');
        if (!open) return false;
        open.hidden = true;
        open.parentNode.querySelector('.rvh-select').setAttribute('aria-expanded', 'false');
        return true;
    },

    onClick(e) {
        const box = e.target.closest('.rvh-mistake');
        if (!box) { this.closeMenu(); return; }

        const select = e.target.closest('.rvh-select');
        if (select) {
            const menu = box.querySelector('.rvh-menu');
            menu.hidden = !menu.hidden;
            select.setAttribute('aria-expanded', menu.hidden ? 'false' : 'true');
            return;
        }

        const li = e.target.closest('.rvh-menu li');
        if (!li) return;
        const m = this.meta();
        const code = li.dataset.code;
        box.querySelector('.rvh-select span').textContent = li.textContent;
        box.querySelector('.rvh-select').classList.add('has-value');
        box.querySelectorAll('.rvh-menu li').forEach((x) => x.setAttribute('aria-selected', x === li ? 'true' : 'false'));
        this.closeMenu();

        const other = box.querySelector('.rvh-other');
        other.hidden = code !== m.other;
        if (code === m.other) {
            const ta = other.querySelector('textarea');
            ta.focus();
            this.save(box, code, ta.value.trim());
        } else {
            this.save(box, code, '');
        }
    },

    onInput(e) {
        const ta = e.target.closest('.rvh-other textarea');
        if (!ta) return;
        const box = ta.closest('.rvh-mistake');
        const m = this.meta();
        box.querySelector('.rvh-count').textContent = ta.value.length + '/' + m.max;
        window.clearTimeout(this.timer);
        this.pending = { box, text: ta.value.trim() };
        this.timer = window.setTimeout(() => this.flush(), 700);
    },

    /** Lưu nốt nội dung "Lý do khác" đang gõ dở (đóng sidebar / chuyển câu). */
    flush() {
        window.clearTimeout(this.timer);
        if (!this.pending) return;
        const p = this.pending;
        this.pending = null;
        this.save(p.box, this.meta().other, p.text);
    },

    async save(box, code, text) {
        const m = this.meta();
        if (!m || !m.url) return;
        const status = box.querySelector('.rvh-saved');
        const questionId = parseInt(box.dataset.questionId, 10);
        const slotIndex = parseInt(box.dataset.slot, 10) || 0;
        const tokenEl = document.querySelector('meta[name="csrf-token"]');

        if (status) { status.textContent = 'Đang lưu...'; status.className = 'rvh-saved'; }
        try {
            const res = await fetch(m.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': tokenEl ? tokenEl.getAttribute('content') : '',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    question_id: questionId,
                    slot_index: slotIndex,
                    question_number: box.dataset.number || null,
                    reason_code: code,
                    reason_text: text || null,
                }),
            });
            const json = await res.json().catch(() => ({}));
            if (!res.ok || json.status !== 'ok') throw new Error(json.message || 'save_failed');

            m.saved = m.saved || {};
            m.saved[questionId + ':' + slotIndex] = json.reason;
            if (status) { status.textContent = 'Đã lưu lý do.'; status.className = 'rvh-saved is-ok'; }
        } catch (err) {
            if (status) {
                status.textContent = err && err.message && err.message !== 'save_failed' ? err.message : 'Chưa lưu được, hãy thử lại.';
                status.className = 'rvh-saved is-error';
            }
        }
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

        this.number = (AnswerHelp.entry === entry && AnswerHelp.focusNumber) || rvNumberLabel(entry);
        document.getElementById('rvxReportQuestion').textContent = 'Câu ' + this.number;
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
                    question_number: this.number || rvNumberLabel(this.entry),
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

    // Listening: transcript + thanh audio + nút ▶ từng câu (review-listening.js).
    const RL = (isListening && window.ReviewListening && document.getElementById('rvlPlayer'))
        ? window.ReviewListening
        : null;
    if (RL) {
        RL.init({
            context: els.context,
            player: document.getElementById('rvlPlayer'),
            entries,
            sectionFiles: (window.REVIEW_SECTION_DATA || {}).files || {},
            onPlayEntry: (entry) => goTo(entry.startNumber, { scroll: true, fromPlay: true }),
        });
        root.classList.add('rvl-root');

        // Tab ở mép phải (mockup): mở sidebar giải thích của câu đang xem.
        const helpTab = document.createElement('button');
        helpTab.type = 'button';
        helpTab.className = 'rvl-help-tab';
        helpTab.title = 'Giải thích đáp án câu đang xem';
        helpTab.setAttribute('aria-label', 'Mở giải thích đáp án câu đang xem');
        helpTab.innerHTML = '<i class="fas fa-chevron-left" aria-hidden="true"></i>';
        helpTab.addEventListener('click', () => {
            const entry = entryOfNumber(currentNumber);
            if (entry) AnswerHelp.open(entry, helpTab, { slotIndex: currentNumber - entry.startNumber });
        });
        document.querySelector('.rvx-right').appendChild(helpTab);
    }

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
            btn.textContent = (isListening ? 'Section ' : 'Passage ') + (idx + 1);
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
            if (RV_COMPLETION_TYPES.includes(group.question_type)
                && (AttemptState.skill !== 'listening' || group.question_type === 'table_completion')) {
                holder.classList.add('rvx-group--completion');
            }
            if (RV_FLOW_TYPES.includes(group.question_type)) holder.classList.add('rvx-group--flow');
            if (group.question_type === 'drag_drop_disappear' || group.question_type === 'drag_drop_reuse') {
                holder.classList.add('rvx-group--mh', 'rvx-group--dd');
            }
            if (AttemptState.skill === 'listening' && RV_LISTEN_SHEET_TYPES.includes(group.question_type)) {
                holder.classList.add('rvx-group--lsheet');
            }
            if (group.question_type === 'matching_headings' && AttemptState.skill !== 'listening') {
                holder.classList.add('rvx-group--mh');
            }
            if (RV_LABELING_TYPES.includes(group.question_type)) {
                holder.classList.add('rvx-group--mh', 'rvx-group--mx', 'rvx-group--map');
            }
            if (group.question_type === 'matching_information' && AttemptState.skill !== 'listening') {
                holder.classList.add('rvx-group--mh', 'rvx-group--mx');
            }
            if (group.question_type === 'matching_sentence_endings' && AttemptState.skill !== 'listening') {
                holder.classList.add('rvx-group--mh', 'rvx-group--se');
            }
            holder.__questionIds = (group.questions || []).map((q) => String(q.id));
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

        const partIndex = AttemptState.part.index;
        const partEntries = entries.filter((e) => e.partIndex === partIndex);

        if (RL) {
            // Khối audio mặc định của layout.js không dùng -> thanh audio riêng ở cột trái.
            els.questions.querySelectorAll('.exam-listening-block').forEach((b) => b.remove());
            RL.renderPart(part, partEntries);
        } else {
            placeListeningBlock(part);
        }

        partEntries.forEach((entry) => {
            if (ReviewTfng.isTfng(entry)) ReviewTfng.decorate(entry);
            else if (ReviewMcq.isMcq(entry)) ReviewMcq.decorate(entry);
            else if (ReviewFlow.applies(entry)) ReviewFlow.decorate(entry);
            else if (ReviewCompletion.applies(entry)) ReviewCompletion.decorate(entry);
            else if (ReviewListenSheet.applies(entry)) ReviewListenSheet.decorate(entry);
            else if (ReviewDragDrop.applies(entry)) ReviewDragDrop.decorate(entry);
            else if (ReviewMatching.applies(entry)) ReviewMatching.decorate(entry);
            else if (ReviewEndings.applies(entry)) ReviewEndings.decorate(entry);
            else if (ReviewMatrix.applies(entry)) ReviewMatrix.decorate(entry);
            else ReviewMarker.decorateEntry(entry);
        });
        ReviewMarker.flushLooseExplanations(els.questions);

        els.questions.querySelectorAll('.rvx-group--tfng, .rvx-group--mcq, .rvx-group--completion, .rvx-group--mh, .rvx-group--flow, .rvx-group--lsheet').forEach((holder) => {
            // Theo danh sách câu của group (ô Matching có thể nằm trong bài đọc, ngoài holder).
            const ids = holder.__questionIds;
            const groupEntries = partEntries.filter((e) => (ids
                ? ids.includes(String(e.question.id))
                : holder.contains(document.getElementById('exam-q-' + e.question.id))));
            ReviewTfng.decorateGroup(holder, groupEntries);
            if (holder.classList.contains('rvx-group--completion')) ReviewCompletion.decorateGroup(holder);
            if (holder.classList.contains('rvx-group--lsheet')) ReviewListenSheet.decorateGroup(holder);
        });

        // Nút ▶ cạnh vòng số câu (dạng đã làm lại giao diện chữa bài).
        if (RL) {
            partEntries.forEach((entry) => {
                const card = document.getElementById('exam-q-' + entry.question.id);
                RL.decorateNumber(card && card.querySelector('.rvx-num:not(.rvx-num--inline)'), entry);
            });
        }

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
    function markCurrent(entry, scroll, number) {
        root.querySelectorAll('.rvx-current').forEach((el) => el.classList.remove('rvx-current'));
        const card = document.getElementById('exam-q-' + entry.question.id);
        if (!card) return;

        card.classList.add('rvx-current');

        // Câu nhiều ô điền ngay trong câu: tô + cuộn tới đúng ô đang xem.
        const blank = number
            ? card.querySelector('.rvx-blank[data-number="' + number + '"], .rvx-dd-row[data-number="' + number + '"]')
            : null;
        if (blank) blank.classList.add('rvx-current');

        if (scroll) (blank || card).scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function goTo(n, options) {
        const opts = options || {};
        n = Math.min(lastNumber, Math.max(1, n));
        const entry = entryOfNumber(n);
        if (!entry) return;

        currentNumber = n;
        AttemptState.current.questionId = entry.question.id;

        if (entry.partIndex !== AttemptState.part.index) showPart(entry.partIndex);

        markCurrent(entry, opts.scroll !== false, n);
        syncPager();
        AnswerHelp.follow(entry, n);

        // Tô đậm đoạn đáp án trong transcript; chỉ cuộn transcript khi audio đang dừng
        // (đang phát thì để transcript chạy theo audio). Nút ▶ tự xử lý phần tua.
        if (RL && !opts.fromPlay) RL.focusEntry(entry, { scroll: RL.audio.paused });
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

        // Bấm vào 1 ô trống (hoặc bóng đèn của ô) -> đúng số câu của ô đó.
        const blank = t.closest('.rvx-blank, .rvx-dd-row');
        const blankNumber = blank ? parseInt(blank.dataset.number, 10) : NaN;
        goTo(blankNumber > 0 ? blankNumber : entry.startNumber, { scroll: false });

        if (help) {
            const optionIndex = help.dataset.optionIndex;
            const slotIndex = help.dataset.slotIndex;
            let focus = null;
            if (optionIndex !== undefined) focus = { optionIndex: parseInt(optionIndex, 10) };
            else if (slotIndex !== undefined) focus = { slotIndex: parseInt(slotIndex, 10) };
            else if (blankNumber > 0) focus = { slotIndex: blankNumber - entry.startNumber };
            AnswerHelp.open(entry, help, focus);
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