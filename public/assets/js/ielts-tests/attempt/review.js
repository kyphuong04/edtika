/**
 * review.js — trang CHỮA BÀI (Listening / Reading / Grammar / Vocabulary).
 *
 * Dùng lại NGUYÊN bộ máy UI của trang làm bài (state.js, renderers.js,
 * layout.js) ở chế độ CHỈ ĐỌC, thay thế app.js + answers.js + highlights.js.
 * Không sửa file nào của trang làm bài -> giao diện 2 trang luôn y hệt nhau.
 *
 * Dữ liệu (attempt/review.blade.php):
 *   REVIEW_SECTION_DATA, REVIEW_SAVED_ANSWERS — cùng shape với trang làm bài
 *   REVIEW_RESULTS[questionId] = { slots: [...], explanation }
 *       slots = IeltsTestQuestion::gradeSlots(): mỗi phần tử
 *       { submitted, accepted: [], correct } (+ cell/parts cho table)
 *   REVIEW_META = { score, total, band, resultsUrl, sections: [...], studentName }
 */

// renderers.js gọi AttemptAnswers.* khi render / khi input đổi. Ở trang chữa
// bài không được gửi gì lên server -> bản rỗng thay cho answers.js.
const AttemptAnswers = {
    onStatusChange() {},
    queueSave() {},
    saveSpeaking() { return Promise.resolve(null); },
    flushAllPending() {},
};

function rvNorm(value) {
    return String(value == null ? '' : value).trim().toLowerCase();
}

const ReviewMarker = {
    results: window.REVIEW_RESULTS || {},
    meta: window.REVIEW_META || {},
    transcriptOpen: false,
    looseExplanations: [],

    slotsOf(questionId) {
        const r = this.results[questionId];
        return r && Array.isArray(r.slots) ? r.slots : [];
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

    /** Tô màu 1 ô + chèn đáp án đúng ngay sau nếu chưa đúng. */
    markField(el, slot, labelFn) {
        if (!el || el.dataset.rvDone) return;
        el.dataset.rvDone = '1';

        const st = this.status(slot);
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

        row.classList.add('graded-' + this.status(slot));
    },

    decorateEntry(entry) {
        const q = entry.question;
        const slots = this.slotsOf(q.id);
        const scope = document.getElementById('exam-q-' + q.id);
        if (!scope) return;

        const type = q.type || '';

        if (scope.classList.contains('exam-matrix-row')) {
            this.markMatrixRow(scope, slots[0]);
        } else if (type === 'table_completion') {
            this.markTable(scope, slots);
        } else if (type === 'multiple_choice_single' || type === 'multiple_choice_multiple') {
            this.markOptions(scope, slots, q);
        } else if (type === 'true_false_not_given' || type === 'yes_no_not_given') {
            this.markField(scope.querySelector('.exam-tfng-select'), slots[0]);
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
        const r = this.results[entry.question.id] || {};
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

    decoratePart(partIndex) {
        this.looseExplanations = [];

        AttemptState.entries
            .filter((e) => e.partIndex === partIndex)
            .forEach((e) => this.decorateEntry(e));

        if (this.looseExplanations.length) {
            const box = document.createElement('div');
            box.className = 'exam-explanation-box rv-loose-explanations';
            box.innerHTML = '<div class="rv-explain-title">Explanations</div>';
            this.looseExplanations.forEach((item) => {
                const row = document.createElement('div');
                row.className = 'rv-loose-item';
                row.innerHTML = '<strong>Question ' + item.label + ':</strong> ' + item.html;
                box.appendChild(row);
            });
            ExamLayout.els.questions.appendChild(box);
        }
    },

    /** Thanh điều hướng: đổi tím "đã trả lời" thành xanh/đỏ/xám theo kết quả. */
    paintNav() {
        const segments = ExamLayout.els.partNavBar.querySelectorAll('.exam-part-nav-segment');

        AttemptState.parts.forEach((part, partIndex) => {
            const segment = segments[partIndex];
            if (!segment) return;

            const statuses = [];
            AttemptState.entries
                .filter((e) => e.partIndex === partIndex)
                .forEach((e) => {
                    const slots = this.slotsOf(e.question.id);
                    for (let i = 0; i < e.slotCount; i++) statuses.push(this.status(slots[i]));
                });

            segment.querySelectorAll('.epn-question-num').forEach((btn, i) => {
                btn.classList.remove('answered');
                btn.classList.add(statuses[i] === 'empty' ? 'skipped' : statuses[i]);
            });

            const summary = segment.querySelector('.epn-part-summary');
            if (summary) {
                summary.textContent = statuses.filter((s) => s === 'correct').length
                    + '/' + statuses.length + ' correct';
            }
        });
    },

    setupHeader() {
        const els = ExamLayout.els;
        const meta = this.meta;

        // Đồng hồ -> điểm + band
        els.timer.className = 'exam-timer rv-score';
        els.timer.innerHTML = '';
        const score = document.createElement('span');
        score.textContent = (meta.score || 0) + '/' + (meta.total || 0) + ' correct';
        els.timer.appendChild(score);
        if (meta.band !== null && meta.band !== undefined) {
            const band = document.createElement('span');
            band.className = 'rv-band';
            band.textContent = 'Band ' + Number(meta.band).toFixed(1);
            els.timer.appendChild(band);
        }

        if (meta.studentName) {
            const who = document.createElement('div');
            who.className = 'rv-student';
            who.textContent = meta.studentName;
            els.testTitle.insertAdjacentElement('afterend', who);
        }

        // Nút nộp bài -> quay lại trang kết quả
        const back = els.submitBtn;
        back.classList.add('rv-back-btn');
        back.title = 'Back to results';
        back.innerHTML = '<i class="fas fa-arrow-left"></i><span>Results</span>';
        back.disabled = false;
        back.addEventListener('click', () => { window.location.href = meta.resultsUrl; });
    },

    /** Tab chuyển phần — link sang section khác (mỗi section 1 lần tải trang). */
    renderTabs() {
        const wrap = ExamLayout.els.skillTabs;
        if (!wrap) return;
        wrap.innerHTML = '';

        const sections = Array.isArray(this.meta.sections) ? this.meta.sections : [];
        if (sections.length <= 1) {
            const tab = document.createElement('div');
            tab.className = 'exam-skill-tab active';
            tab.textContent = SKILL_LABELS[AttemptState.skill] || String(AttemptState.skill || '').toUpperCase();
            wrap.appendChild(tab);
            return;
        }

        sections.forEach((s) => {
            const a = document.createElement('a');
            a.className = 'exam-skill-tab' + (s.active ? ' active' : '');
            a.href = s.url;
            a.textContent = s.label;
            wrap.appendChild(a);
        });
    },

    /** Listening: nút hiện/ẩn transcript ngay dưới audio của Part. */
    addTranscript(part) {
        if (AttemptState.skill !== 'listening' || !part || !part.transcript) return;

        const block = ExamLayout.els.questions.querySelector('.exam-listening-block');
        if (!block || block.querySelector('.rv-transcript-toggle')) return;

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'rv-transcript-toggle rv-allow';

        const box = document.createElement('div');
        box.className = 'rv-transcript' + (this.transcriptOpen ? '' : ' rv-hidden');
        box.innerHTML = part.transcript;

        const sync = () => {
            btn.innerHTML = this.transcriptOpen
                ? '<i class="fas fa-eye-slash"></i> Hide transcript'
                : '<i class="fas fa-file-alt"></i> Show transcript';
            box.classList.toggle('rv-hidden', !this.transcriptOpen);
        };

        btn.addEventListener('click', () => {
            this.transcriptOpen = !this.transcriptOpen;
            sync();
        });

        sync();
        block.appendChild(btn);
        block.appendChild(box);
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

document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('attemptRoot');
    if (!root) return;

    buildAttemptModel(window.REVIEW_SECTION_DATA || {}, window.REVIEW_SAVED_ANSWERS || {});

    if (!AttemptState.parts.length) {
        root.innerHTML = '<div class="exam-loading">This section has no content.</div>';
        return;
    }

    ExamLayout.mount(root);
    root.classList.add('is-review');
    ReviewMarker.setupHeader();
    rvBlockInteractions(root);

    function partAudioUrl(partIndex) {
        const part = AttemptState.parts[partIndex];
        if (!part) return null;
        return (part.files || {}).audio || (AttemptState.sectionFiles || {}).audio || null;
    }

    function ensureCurrent() {
        const partEntries = AttemptState.entries.filter((e) => e.partIndex === AttemptState.part.index);
        const valid = partEntries.some((e) => e.question.id === AttemptState.current.questionId);
        if (!valid) AttemptState.current.questionId = partEntries.length ? partEntries[0].question.id : null;
    }

    function renderNav() {
        ExamLayout.renderPartNav(switchPart, jumpToEntry);
        ReviewMarker.paintNav();
    }

    function refreshView() {
        ensureCurrent();
        ReviewMarker.renderTabs();
        renderNav();

        const part = AttemptState.currentPart();
        if (part) {
            ExamLayout.renderContext(part);
            ExamLayout.renderQuestions(part);
            ReviewMarker.decoratePart(AttemptState.part.index);
            ReviewMarker.addTranscript(part);
            ExamLayout.lockAllInputs();
        }

        updateFab();
    }

    function changePart(partIndex) {
        // Khác file audio -> dừng audio cũ (phần tử bị gỡ khỏi DOM vẫn có thể phát tiếp).
        if (partAudioUrl(partIndex) !== partAudioUrl(AttemptState.part.index)) {
            const audio = document.querySelector('audio[data-listening-audio="1"]');
            if (audio && !audio.paused) audio.pause();
        }
        AttemptState.part.index = partIndex;
    }

    function switchPart(partIndex) {
        if (AttemptState.part.index === partIndex) return;
        changePart(partIndex);
        AttemptState.current.questionId = null;
        refreshView();
    }

    function jumpToEntry(entry) {
        AttemptState.current.questionId = entry.question.id;

        if (AttemptState.part.index !== entry.partIndex) {
            changePart(entry.partIndex);
            refreshView();
        } else {
            renderNav();
            updateFab();
        }

        window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
    }

    function goToQuestionOffset(offset) {
        const entries = AttemptState.entries;
        let idx = entries.findIndex((e) => e.question.id === AttemptState.current.questionId);
        if (idx === -1) idx = 0;
        const target = entries[idx + offset];
        if (target) jumpToEntry(target);
    }

    function updateFab() {
        const entries = AttemptState.entries;
        const idx = entries.findIndex((e) => e.question.id === AttemptState.current.questionId);
        if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.disabled = idx <= 0;
        if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.disabled = idx === -1 || idx >= entries.length - 1;
    }

    if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.addEventListener('click', () => goToQuestionOffset(-1));
    if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.addEventListener('click', () => goToQuestionOffset(1));

    refreshView();

    const jumpTo = parseInt(new URLSearchParams(window.location.search).get('q'), 10);
    if (jumpTo > 0) {
        const target = AttemptState.entries.find((e) => jumpTo >= e.startNumber && jumpTo <= e.endNumber);
        if (target) jumpToEntry(target);
    }
});