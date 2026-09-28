/**
 * renderers.js — render UI từng loại câu hỏi cho bài thi thật.
 *
 * Khác preview/renderers.js:
 *  - Không có gradeByType — bài thi thật KHÔNG BAO GIỜ tự chấm hiển thị
 *    ngay cho học viên.
 *  - Mọi thay đổi đáp án gọi AttemptAnswers.queueSave() (tự lưu server),
 *    thay vì chỉ PreviewState.setAnswer() (chỉ ở bộ nhớ).
 *  - Speaking ghi âm thật -> upload qua AttemptAnswers.saveSpeaking().
 */

function escapeAttr(value) {
    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function questionLabel(entry) {
    return entry.slotCount > 1
        ? `Câu ${entry.startNumber}–${entry.endNumber}`
        : `Câu ${entry.startNumber}`;
}

function makeCard(entry) {
    const card = document.createElement('div');
    card.className = 'exam-question-card';
    card.id = 'exam-q-' + entry.question.id;
    card.dataset.questionId = entry.question.id;

    const badge = document.createElement('div');
    badge.className = 'exam-question-number';
    badge.textContent = questionLabel(entry);
    card.appendChild(badge);

    return card;
}

function appendTextBlock(card, html, className) {
    if (!html) return;
    const div = document.createElement('div');
    div.className = className || 'exam-question-text';
    div.innerHTML = html;
    card.appendChild(div);
}

function renderTextWithBlanks(html, savedValues, disabled) {
    return window.IeltsBlanks.render(html, savedValues, disabled);
}

/** Trạng thái lưu (pending/saved/error) hiển thị nhỏ cạnh mỗi câu hỏi. */
function appendSaveIndicator(card, questionId) {
    const el = document.createElement('span');
    el.className = 'attempt-save-indicator';
    el.dataset.questionId = questionId;
    card.querySelector('.exam-question-number')?.after(el);

    AttemptAnswers.onStatusChange((qId, status) => {
        if (String(qId) !== String(questionId)) return;
        el.className = 'attempt-save-indicator status-' + status;
        el.textContent = status === 'pending' ? 'Đang lưu...' : status === 'saved' ? 'Đã lưu' : 'Lỗi lưu';
    });
}

/** A, B ... Z, AA, AB — sắp theo độ dài trước, tránh A, AA, AB, B. */
function matchingKeyCompare(a, b) {
    return a.length === b.length ? a.localeCompare(b) : a.length - b.length;
}

/** Nhãn hiển thị: "B – Julie Mattison", hoặc chỉ "B" nếu chưa nhập nội dung. */
function matchingOptionLabel(options, key) {
    const text = options && options[key];
    return (text && String(text) !== key) ? (key + ' – ' + text) : key;
}

function wireBlankInputs(container, question) {
    const inputs = container.querySelectorAll('.exam-blank-input');

    // Đáp án cũ có thể là chuỗi (Short Answer trước khi đổi) -> ép về mảng.
    const toList = (value) => Array.isArray(value)
        ? value.slice()
        : (value ? [String(value)] : []);

    const existing = toList(AttemptState.getAnswer(question.id));

    inputs.forEach((input, idx) => {
        if (existing[idx]) input.value = existing[idx];

        input.addEventListener('input', () => {
            const current = toList(AttemptState.getAnswer(question.id));
            current[idx] = input.value;

            // Giữ đủ độ dài để không lệch vị trí khi chấm.
            for (let i = 0; i < inputs.length; i++) {
                if (current[i] == null) current[i] = '';
            }

            AttemptAnswers.queueSave(question, current);
        });
    });
}

/**
 * Gắn số câu ngay trước mỗi ô trống (kiểu IELTS trên máy).
 * entry.startNumber là số câu đầu tiên của question này.
 */
function decorateBlankNumbers(container, entry) {
    container.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
        const badge = document.createElement('span');
        badge.className = 'exam-blank-number';
        badge.textContent = entry.startNumber + idx;
        input.parentNode.insertBefore(badge, input);
    });
}

function decorateBlankNumbers(container, entry) {
    container.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
        const badge = document.createElement('span');
        badge.className = 'exam-blank-number';
        badge.textContent = entry.startNumber + idx;
        input.parentNode.insertBefore(badge, input);
    });
}

/**
 * Kéo thả cho Matching. Kho lựa chọn nằm ở cấp GROUP, ô thả nằm trong từng
 * thẻ câu hỏi, nên phải điều phối ở cấp document thay vì trong 1 thẻ.
 * Giá trị lưu là key đáp án ("A", "ii"...) — giống hệt bản dropdown cũ.
 */
const ExamMatchingDnD = {
    bound: false,
    picked: null,

    ensureBound() {
        if (this.bound) return;
        this.bound = true;
        document.addEventListener('pointerdown', (ev) => this.onPointerDown(ev));
    },

    paint(slot) {
        const value = slot.dataset.value || '';
        const label = slot.dataset.label || value;

        slot.textContent = '';

        if (slot.dataset.number) {
            const num = document.createElement('span');
            num.className = 'exam-match-slot-num';
            num.textContent = slot.dataset.number;
            slot.appendChild(num);
        }

        const text = document.createElement('span');
        text.className = 'exam-match-slot-text';
        text.textContent = value ? label : (slot.dataset.number ? '' : '–');
        slot.appendChild(text);

        slot.classList.toggle('filled', !!value);
    },


    chips(groupId) {
        return Array.from(document.querySelectorAll('.exam-match-chip[data-group-id="' + groupId + '"]'));
    },

    slots(groupId) {
        return Array.from(document.querySelectorAll('.exam-match-slot[data-group-id="' + groupId + '"]'));
    },

    isSingleUse(groupId) {
        const pool = document.querySelector('.exam-match-pool[data-group-id="' + groupId + '"]');
        return !!pool && pool.dataset.singleUse === '1';
    },

    syncChips(groupId) {
        if (!this.isSingleUse(groupId)) return;
        const used = this.slots(groupId).map((s) => s.dataset.value || '').filter(Boolean);
        this.chips(groupId).forEach((chip) => {
            chip.classList.toggle('used', used.includes(chip.dataset.value));
        });
    },

    fill(slot, value, label) {
        const question = slot.__question;
        const groupId = slot.dataset.groupId;
        if (!question) return;

        // Matching Headings: mỗi lựa chọn chỉ dùng 1 lần -> gỡ khỏi ô cũ.
        if (value && this.isSingleUse(groupId)) {
            this.slots(groupId).forEach((other) => {
                if (other !== slot && other.dataset.value === value) this.clear(other, true);
            });
        }

        slot.dataset.value = value || '';
        slot.dataset.label = value ? (label || value) : '';
        this.paint(slot);
        AttemptAnswers.queueSave(question, value || '');
        this.syncChips(groupId);
    },

    clear(slot, skipSync) {
        slot.dataset.value = '';
        slot.dataset.label = '';
        this.paint(slot);
        if (slot.__question) AttemptAnswers.queueSave(slot.__question, '');
        if (!skipSync) this.syncChips(slot.dataset.groupId);
    },

    clearPick() {
        document.querySelectorAll('.exam-match-chip.picked').forEach((c) => c.classList.remove('picked'));
        this.picked = null;
    },

    slotFromPoint(x, y, groupId) {
        const el = document.elementFromPoint(x, y);
        const slot = el ? el.closest('.exam-match-slot') : null;
        return slot && slot.dataset.groupId === groupId ? slot : null;
    },

    overPool(x, y, groupId) {
        const el = document.elementFromPoint(x, y);
        const pool = el ? el.closest('.exam-match-pool') : null;
        return !!pool && pool.dataset.groupId === groupId;
    },

    onPointerDown(ev) {
        const chip = ev.target.closest('.exam-match-chip');
        const slot = ev.target.closest('.exam-match-slot');
        if (!chip && !slot) return;

        const el = chip || slot;
        if (el.closest('.is-locked') || document.querySelector('.exam-questions.is-locked')) return;
        if (ev.pointerType === 'mouse' && ev.button !== 0) return;

        if (chip) {
            if (chip.classList.contains('used')) return;
            this.startDrag(ev, chip, chip.dataset.groupId, chip.dataset.value, chip.dataset.label, null);
            return;
        }

        if (slot.classList.contains('filled')) {
            this.startDrag(ev, slot, slot.dataset.groupId, slot.dataset.value, slot.dataset.label, slot);
            return;
        }

        // Ô trống: đặt lựa chọn đang chọn sẵn (cách bấm 2 lần).
        ev.preventDefault();
        if (this.picked && this.picked.groupId === slot.dataset.groupId) {
            this.fill(slot, this.picked.value, this.picked.label);
            this.clearPick();
        }
    },

    startDrag(ev, el, groupId, value, label, sourceSlot) {
        ev.preventDefault();

        const self = this;
        const start = { x: ev.clientX, y: ev.clientY };
        let ghost = null;
        let moved = false;
        let hovered = null;

        const onMove = (e) => {
            if (!moved && Math.hypot(e.clientX - start.x, e.clientY - start.y) < 5) return;

            if (!moved) {
                moved = true;
                el.classList.add('is-dragging');
                ghost = document.createElement('div');
                ghost.className = 'exam-match-ghost';
                ghost.textContent = label || value;
                document.body.appendChild(ghost);
            }

            ghost.style.left = e.clientX + 'px';
            ghost.style.top = e.clientY + 'px';

            const under = self.slotFromPoint(e.clientX, e.clientY, groupId);
            if (hovered && hovered !== under) hovered.classList.remove('drag-over');
            if (under) under.classList.add('drag-over');
            hovered = under;
        };

        const onUp = (e) => {
            document.removeEventListener('pointermove', onMove);
            document.removeEventListener('pointerup', onUp);
            document.removeEventListener('pointercancel', onUp);

            el.classList.remove('is-dragging');
            if (ghost) ghost.remove();
            if (hovered) hovered.classList.remove('drag-over');

            if (!moved) {
                if (sourceSlot) {
                    self.clear(sourceSlot);
                } else {
                    const wasPicked = el.classList.contains('picked');
                    self.clearPick();
                    if (!wasPicked) {
                        el.classList.add('picked');
                        self.picked = { groupId: groupId, value: value, label: label };
                    }
                }
                return;
            }

            const target = self.slotFromPoint(e.clientX, e.clientY, groupId);
            if (target) {
                if (sourceSlot && target !== sourceSlot) self.clear(sourceSlot, true);
                self.fill(target, value, label);
                self.clearPick();
            } else if (sourceSlot && self.overPool(e.clientX, e.clientY, groupId)) {
                self.clear(sourceSlot);
            }
        };

        document.addEventListener('pointermove', onMove);
        document.addEventListener('pointerup', onUp);
        document.addEventListener('pointercancel', onUp);
    },
};

const ExamRenderers = {
    activeRecorder: null, // { stop: fn } — cho phép app.js ép dừng khi chuyển part

    render(entry) {
        const q = entry.question;
        const handler = this.byType[q.type] || this.byType.short_answer;
        const card = handler.call(this.byType, entry);
        appendSaveIndicator(card, q.id);
        return card;
    },

        /** layout.js dùng khi dựng ô thả trong bài đọc. */
    matchingLabel(options, key) {
        return matchingOptionLabel(options, key);
    },

        /** Kho lựa chọn dùng chung cho cả group Matching. layout.js gọi hàm này. */
    buildMatchingPool(group, groupId) {
        const first = (group.questions || [])[0];
        const options = (first && first.options) || {};
        const keys = Object.keys(options).sort(matchingKeyCompare);
        if (!keys.length) return null;

        const pool = document.createElement('div');
        pool.className = 'exam-match-pool';
        pool.dataset.groupId = groupId;
        // Headings: mỗi heading chỉ dùng cho 1 đoạn (theo chuẩn IELTS).
        pool.dataset.singleUse = group.question_type === 'matching_headings' ? '1' : '0';

        const label = document.createElement('div');
        label.className = 'exam-match-pool-label';
        label.textContent = pool.dataset.singleUse === '1'
            ? 'Kéo mỗi lựa chọn vào một câu — mỗi lựa chọn chỉ dùng một lần'
            : 'Kéo lựa chọn vào ô trống — có thể dùng lại nhiều lần';
        pool.appendChild(label);

        const chips = document.createElement('div');
        chips.className = 'exam-match-chips';

        keys.forEach((key) => {
            const chip = document.createElement('span');
            chip.className = 'exam-match-chip';
            chip.dataset.groupId = groupId;
            chip.dataset.value = key;
            chip.dataset.label = matchingOptionLabel(options, key);
            chip.textContent = chip.dataset.label;
            chips.appendChild(chip);
        });

        pool.appendChild(chips);
        ExamMatchingDnD.ensureBound();
        return pool;
    },


    buildMatchingMatrix(group) {
        // Chỉ lấy câu Matching (phòng dữ liệu cũ lẫn loại khác trong group).
        const entries = (group.questions || [])
            .map((q) => AttemptState.entries.find((e) => e.question.id === q.id))
            .filter((e) => e && String(e.question.type || '').indexOf('matching_') === 0);
        if (!entries.length) return null;

        // Gộp cột của MỌI statement — giáo viên có thể thêm statement nhiều lần
        // với số cột khác nhau.
        const options = {};
        entries.forEach((e) => Object.assign(options, e.question.options || {}));
        const keys = Object.keys(options).sort(matchingKeyCompare);
        if (!keys.length) return null;

        const wrap = document.createElement('div');
        wrap.className = 'exam-matrix-wrap';

        // Chú thích nội dung từng lựa chọn, nếu giáo viên có nhập (khác chữ cái).
        const described = keys.filter((k) => options[k] && String(options[k]) !== k);
        if (described.length) {
            const legend = document.createElement('div');
            legend.className = 'exam-matrix-legend';
            described.forEach((k) => {
                const item = document.createElement('div');
                const strong = document.createElement('strong');
                strong.textContent = k;
                item.appendChild(strong);
                item.appendChild(document.createTextNode(' ' + options[k]));
                legend.appendChild(item);
            });
            wrap.appendChild(legend);
        }

        const scroller = document.createElement('div');
        scroller.className = 'exam-matrix-scroll';

        const table = document.createElement('table');
        table.className = 'exam-matrix';

        // Header
        const thead = document.createElement('thead');
        const headRow = document.createElement('tr');
        const headStatement = document.createElement('th');
        headStatement.className = 'exam-matrix-statement-head';
        headStatement.textContent = 'Statements';
        headRow.appendChild(headStatement);

        keys.forEach((k) => {
            const th = document.createElement('th');
            th.className = 'exam-matrix-col';
            th.textContent = k;
            if (options[k] && String(options[k]) !== k) th.title = options[k];
            headRow.appendChild(th);
        });
        thead.appendChild(headRow);
        table.appendChild(thead);

        // Body
        const tbody = document.createElement('tbody');

        entries.forEach((entry) => {
            const q = entry.question;
            const saved = String(AttemptState.getAnswer(q.id) || '').trim().toUpperCase();

            const tr = document.createElement('tr');
            tr.className = 'exam-matrix-row' + (saved ? ' answered' : '');
            tr.id = 'exam-q-' + q.id;   // để thanh điều hướng cuộn tới đúng hàng

            // Cột statement
            const tdStatement = document.createElement('td');
            tdStatement.className = 'exam-matrix-statement';

            const inner = document.createElement('div');
            inner.className = 'exam-matrix-statement-inner';

            const num = document.createElement('span');
            num.className = 'exam-matrix-num';
            num.textContent = entry.startNumber;

            const text = document.createElement('div');
            text.className = 'exam-matrix-text';
            text.innerHTML = q.text || '';

            const indicator = document.createElement('span');
            indicator.className = 'attempt-save-indicator';
            AttemptAnswers.onStatusChange((qId, status) => {
                if (String(qId) !== String(q.id)) return;
                indicator.className = 'attempt-save-indicator status-' + status;
                indicator.textContent = status === 'pending' ? 'Đang lưu...' : status === 'saved' ? 'Đã lưu' : 'Lỗi lưu';
            });
            text.appendChild(indicator);

            inner.appendChild(num);
            inner.appendChild(text);
            tdStatement.appendChild(inner);
            tr.appendChild(tdStatement);

            // Các ô chọn
            keys.forEach((k) => {
                const td = document.createElement('td');
                td.className = 'exam-matrix-cell';

                const label = document.createElement('label');
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'exam-mx-' + q.id;
                radio.value = k;
                radio.checked = saved === k.toUpperCase();
                radio.setAttribute('aria-label', 'Câu ' + entry.startNumber + ' – ' + k);

                if (radio.checked) td.classList.add('selected');

                // Bấm lại ô đang chọn -> bỏ chọn (radio mặc định không bỏ được).
                let wasChecked = false;
                label.addEventListener('pointerdown', () => { wasChecked = radio.checked; });

                radio.addEventListener('click', () => {
                    if (wasChecked) {
                        radio.checked = false;
                        wasChecked = false;
                        td.classList.remove('selected');
                        tr.classList.remove('answered');
                        AttemptAnswers.queueSave(q, '');
                    }
                });

                radio.addEventListener('change', () => {
                    if (!radio.checked) return;
                    tr.querySelectorAll('.exam-matrix-cell').forEach((c) => c.classList.remove('selected'));
                    td.classList.add('selected');
                    tr.classList.add('answered');
                    AttemptAnswers.queueSave(q, k);
                });

                label.appendChild(radio);
                td.appendChild(label);
                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        scroller.appendChild(table);
        wrap.appendChild(scroller);
        return wrap;
    },

    /** Gắn group id cho ô thả trong thẻ câu hỏi vừa render. */
    tagMatchingCard(card, groupId) {
        card.querySelectorAll('.exam-match-slot').forEach((slot) => {
            slot.dataset.groupId = groupId;
        });
    },

    byType: {

        multiple_choice_single(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const wrap = document.createElement('div');
            wrap.className = 'exam-options';

            // name chung trong 1 câu -> trình duyệt tự bỏ chọn lựa chọn cũ.
            const groupName = 'exam-q-' + q.id;
            const saved = AttemptState.getAnswer(q.id);

            (q.options || []).forEach((opt, index) => {
                const row = document.createElement('label');
                row.className = 'exam-option-row exam-option-check';
                row.dataset.value = opt;

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.className = 'exam-option-checkbox';
                radio.name = groupName;
                radio.checked = saved === opt;

                const letter = document.createElement('span');
                letter.className = 'exam-option-letter';
                letter.textContent = String.fromCharCode(65 + index);

                const text = document.createElement('span');
                text.className = 'exam-option-text';
                text.textContent = opt;

                if (radio.checked) row.classList.add('selected');

                radio.addEventListener('change', () => {
                    if (!radio.checked) return;

                    wrap.querySelectorAll('.exam-option-row').forEach((r) => r.classList.remove('selected'));
                    row.classList.add('selected');
                    AttemptAnswers.queueSave(q, opt);
                });

                row.appendChild(radio);
                row.appendChild(letter);
                row.appendChild(text);
                wrap.appendChild(row);
            });

            card.appendChild(wrap);
            return card;
        },

        multiple_choice_multiple(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const wrap = document.createElement('div');
            wrap.className = 'exam-options';

            // Đáp án cũ có thể là string (dữ liệu trước khi đổi loại câu hỏi).
            const savedRaw = AttemptState.getAnswer(q.id);
            const saved = Array.isArray(savedRaw) ? savedRaw : (savedRaw ? [savedRaw] : []);

            (q.options || []).forEach((opt, index) => {
                // <label> để bấm vào cả dòng đều tick được; input thật để
                // lockAllInputs() khoá được khi hết giờ.
                const row = document.createElement('label');
                row.className = 'exam-option-row exam-option-check';
                row.dataset.value = opt;

                const box = document.createElement('input');
                box.type = 'checkbox';
                box.className = 'exam-option-checkbox';
                box.checked = saved.includes(opt);

                const letter = document.createElement('span');
                letter.className = 'exam-option-letter';
                letter.textContent = String.fromCharCode(65 + index);

                const text = document.createElement('span');
                text.className = 'exam-option-text';
                text.textContent = opt;

                if (box.checked) row.classList.add('selected');

                box.addEventListener('change', () => {
                    const prev = AttemptState.getAnswer(q.id);
                    const current = Array.isArray(prev) ? prev.slice() : (prev ? [prev] : []);
                    const idx = current.indexOf(opt);

                    if (box.checked && idx < 0) current.push(opt);
                    if (!box.checked && idx >= 0) current.splice(idx, 1);

                    row.classList.toggle('selected', box.checked);
                    AttemptAnswers.queueSave(q, current);
                });

                row.appendChild(box);
                row.appendChild(letter);
                row.appendChild(text);
                wrap.appendChild(row);
            });

            card.appendChild(wrap);
            return card;
        },

        true_false_not_given(entry) {
            return ExamRenderers.byType._tfngLike(entry, ['True', 'False', 'Not Given']);
        },
        yes_no_not_given(entry) {
            return ExamRenderers.byType._tfngLike(entry, ['Yes', 'No', 'Not Given']);
        },
        _tfngLike(entry, choices) {
            const q = entry.question;
            const card = makeCard(entry);

            // Dropdown và đề bài nằm chung một hàng, dropdown đứng trước.
            const row = document.createElement('div');
            row.className = 'exam-inline-row';

            const select = document.createElement('select');
            select.className = 'form-control exam-tfng-select';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Chọn --';
            select.appendChild(placeholder);

            choices.forEach((choice) => {
                const option = document.createElement('option');
                option.value = choice;                     // 'True' / 'Not Given' — khớp đáp án editor lưu
                option.textContent = choice.toUpperCase(); // hiển thị TRUE / NOT GIVEN
                select.appendChild(option);
            });

            // Đáp án đã lưu có thể khác hoa/thường (dữ liệu cũ) -> so không phân biệt.
            const saved = AttemptState.getAnswer(q.id);
            if (saved) {
                const matched = choices.find((c) => c.toLowerCase() === String(saved).trim().toLowerCase());
                select.value = matched || '';
            }

            select.addEventListener('change', () => {
                AttemptAnswers.queueSave(q, select.value);
            });

            const text = document.createElement('div');
            text.className = 'exam-question-text';
            text.innerHTML = q.text || '';

            row.appendChild(select);
            row.appendChild(text);
            card.appendChild(row);

            return card;
        },

        _matchingLike(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            const options = q.options || {};

            const row = document.createElement('div');
            row.className = 'exam-match-row';

            const text = document.createElement('div');
            text.className = 'exam-question-text';
            text.innerHTML = q.text || '';

            const slot = document.createElement('div');
            slot.className = 'exam-match-slot';
            slot.dataset.questionId = q.id;
            slot.__question = q;

            const saved = String(AttemptState.getAnswer(q.id) || '').trim();
            slot.dataset.value = saved;
            slot.dataset.label = saved ? matchingOptionLabel(options, saved) : '';
            ExamMatchingDnD.paint(slot);

            row.appendChild(text);
            row.appendChild(slot);
            card.appendChild(row);

            ExamMatchingDnD.ensureBound();
            return card;
        },
        
        matching_headings(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_information(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_features(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_sentence_endings(entry) { return ExamRenderers.byType._matchingLike(entry); },

        _completionLike(entry) {
            const q = entry.question;
            const card = makeCard(entry);

            const saved = AttemptState.getAnswer(q.id) || [];
            const { html } = renderTextWithBlanks(q.text, saved, false);
            appendTextBlock(card, html);
            decorateBlankNumbers(card, entry);
            card.classList.add('has-inline-blanks');
            wireBlankInputs(card, q);

            return card;
        },
        sentence_completion(entry) { return ExamRenderers.byType._completionLike(entry); },
        summary_completion(entry) { return ExamRenderers.byType._completionLike(entry); },
        note_completion(entry) { return ExamRenderers.byType._completionLike(entry); },
        diagram_labeling(entry) { return ExamRenderers.byType._completionLike(entry); },

        table_completion(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            // Số câu đã hiện ngay trong bảng -> ẩn badge "Câu N–M" trên đầu thẻ.
            card.classList.add('has-inline-blanks');

            const structure = q.table_structure || { headers: [], rows: [] };
            const headers = structure.headers || [];
            const rows = structure.rows || [];

            const wrap = document.createElement('div');
            wrap.className = 'exam-table-wrap';

            const table = document.createElement('table');

            if (headers.some((h) => String(h || '').trim() !== '')) {
                const thead = document.createElement('thead');
                const headRow = document.createElement('tr');
                headers.forEach((h) => {
                    const th = document.createElement('th');
                    th.textContent = h || '';
                    headRow.appendChild(th);
                });
                thead.appendChild(headRow);
                table.appendChild(thead);
            }

            const tbody = document.createElement('tbody');
            const savedByCell = AttemptState.getAnswer(q.id) || {};

            // Mỗi cell có blank = 1 câu, đếm từ trái sang phải, trên xuống dưới —
            // khớp slotCount của editor/payload và cách checkAnswer() chấm theo cell.
            let cellNumber = entry.startNumber;

            rows.forEach((row, rIdx) => {
                const cells = Array.isArray(row) ? row : (row.cells || []);
                const tr = document.createElement('tr');

                cells.forEach((cellText, cIdx) => {
                    const td = document.createElement('td');
                    const cellKey = rIdx + '-' + cIdx;
                    const savedForCell = savedByCell[cellKey] || [];
                    const { html, blankCount } = renderTextWithBlanks(cellText, savedForCell, false);

                    const inner = document.createElement('div');
                    inner.className = 'exam-table-cell';
                    inner.innerHTML = html;
                    td.appendChild(inner);

                    if (blankCount > 0) {
                        td.classList.add('has-blank');

                        const firstInput = inner.querySelector('.exam-blank-input');
                        const badge = document.createElement('span');
                        badge.className = 'exam-table-number';
                        badge.textContent = cellNumber++;
                        firstInput.parentNode.insertBefore(badge, firstInput);

                        inner.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
                            input.setAttribute('aria-label', 'Câu ' + badge.textContent);
                            input.addEventListener('input', () => {
                                const current = AttemptState.getAnswer(q.id) || {};
                                current[cellKey] = current[cellKey] || [];
                                current[cellKey][idx] = input.value;
                                AttemptAnswers.queueSave(q, current);
                            });
                        });
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            wrap.appendChild(table);
            card.appendChild(wrap);
            return card;
        },

        _dragDropLike(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            const isReuse = q.type === 'drag_drop_reuse';

            const savedInit = AttemptState.getAnswer(q.id) || [];
            const { html } = renderTextWithBlanks(q.text, [], false);

            // Đổi <input> của blank-utils thành ô thả.
            let slotIndex = 0;
            const slotHtml = html.replace(/<input[^>]*class="exam-blank-input"[^>]*>/g, () => {
                const idx = slotIndex++;
                const val = savedInit[idx] || '';
                return `<span class="exam-dd-slot${val ? ' filled' : ''}" data-blank-index="${idx}">${val ? escapeAttr(val) : '–'}</span>`;
            });

            appendTextBlock(card, slotHtml);

            // ── Kho đáp án ────────────────────────────────────────────────
            const bankWrap = document.createElement('div');
            bankWrap.className = 'exam-dd-bank-wrap';

            const bankLabel = document.createElement('div');
            bankLabel.className = 'exam-dd-bank-label';
            // bankLabel.textContent = 'Kéo đáp án vào ô trống';

            const bank = document.createElement('div');
            bank.className = 'exam-dd-bank';

            bankWrap.appendChild(bankLabel);
            bankWrap.appendChild(bank);
            card.appendChild(bankWrap);

            // ── Helpers ───────────────────────────────────────────────────
            const slots = () => Array.from(card.querySelectorAll('.exam-dd-slot'));
            const isLocked = () => !!card.closest('.is-locked');

            const getAnswers = () => {
                const current = AttemptState.getAnswer(q.id);
                return Array.isArray(current) ? current.slice() : [];
            };

            function syncChips() {
                if (isReuse) return; // kiểu "reuse": chip luôn dùng lại được
                const used = getAnswers().filter(Boolean);
                bank.querySelectorAll('.exam-dd-chip').forEach((chip) => {
                    chip.classList.toggle('used', used.includes(chip.dataset.word));
                });
            }

            function applyAnswers(answers) {
                const list = slots();
                const normalized = list.map((_, i) => answers[i] || '');

                list.forEach((slot, i) => {
                    slot.textContent = normalized[i] || '–';
                    slot.classList.toggle('filled', !!normalized[i]);
                });

                AttemptAnswers.queueSave(q, normalized);
                syncChips();
            }

            function placeWord(word, slot) {
                const answers = getAnswers();
                const idx = parseInt(slot.dataset.blankIndex, 10);

                // Kiểu "disappear": mỗi đáp án chỉ nằm ở 1 ô -> gỡ khỏi ô cũ.
                if (!isReuse) {
                    answers.forEach((value, i) => {
                        if (value === word && i !== idx) answers[i] = '';
                    });
                }

                answers[idx] = word;
                applyAnswers(answers);
            }

            function clearSlot(slot) {
                const answers = getAnswers();
                answers[parseInt(slot.dataset.blankIndex, 10)] = '';
                applyAnswers(answers);
            }

            function slotFromPoint(x, y) {
                const el = document.elementFromPoint(x, y);
                const slot = el ? el.closest('.exam-dd-slot') : null;
                return slot && card.contains(slot) ? slot : null;
            }

            function overBank(x, y) {
                const el = document.elementFromPoint(x, y);
                return !!(el && el.closest('.exam-dd-bank-wrap') && card.contains(el));
            }

            // ── Kéo thả bằng Pointer Events (chạy cả chuột lẫn cảm ứng) ────
            let pickedWord = null; // dự phòng: chạm chip rồi chạm ô trống

            function clearPick() {
                pickedWord = null;
                bank.querySelectorAll('.exam-dd-chip').forEach((c) => c.classList.remove('active-pick'));
            }

            function beginDrag(ev, word, sourceSlot, el) {
                if (isLocked()) return;
                if (ev.pointerType === 'mouse' && ev.button !== 0) return;
                if (!isReuse && !sourceSlot && el.classList.contains('used')) return;

                ev.preventDefault();

                const start = { x: ev.clientX, y: ev.clientY };
                let ghost = null;
                let moved = false;
                let hovered = null;

                const onMove = (e) => {
                    if (!moved && Math.hypot(e.clientX - start.x, e.clientY - start.y) < 5) return;

                    if (!moved) {
                        moved = true;
                        el.classList.add('is-dragging');
                        ghost = document.createElement('div');
                        ghost.className = 'exam-dd-ghost';
                        ghost.textContent = word;
                        document.body.appendChild(ghost);
                    }

                    ghost.style.left = e.clientX + 'px';
                    ghost.style.top = e.clientY + 'px';

                    const under = slotFromPoint(e.clientX, e.clientY);
                    if (hovered && hovered !== under) hovered.classList.remove('drag-over');
                    if (under) under.classList.add('drag-over');
                    hovered = under;
                };

                const onUp = (e) => {
                    document.removeEventListener('pointermove', onMove);
                    document.removeEventListener('pointerup', onUp);
                    document.removeEventListener('pointercancel', onUp);

                    el.classList.remove('is-dragging');
                    if (ghost) ghost.remove();
                    if (hovered) hovered.classList.remove('drag-over');

                    // Không di chuyển -> coi như bấm chọn.
                    if (!moved) {
                        if (sourceSlot) {
                            clearSlot(sourceSlot);
                        } else {
                            const wasActive = el.classList.contains('active-pick');
                            clearPick();
                            if (!wasActive) {
                                pickedWord = word;
                                el.classList.add('active-pick');
                            }
                        }
                        return;
                    }

                    const target = slotFromPoint(e.clientX, e.clientY);
                    if (target) {
                        placeWord(word, target);
                        clearPick();
                    } else if (sourceSlot && overBank(e.clientX, e.clientY)) {
                        clearSlot(sourceSlot); // kéo ngược về kho = gỡ đáp án
                    }
                };

                document.addEventListener('pointermove', onMove);
                document.addEventListener('pointerup', onUp);
                document.addEventListener('pointercancel', onUp);
            }

            (q.options || []).forEach((word) => {
                const chip = document.createElement('span');
                chip.className = 'exam-dd-chip';
                chip.textContent = word;
                chip.dataset.word = word;
                chip.addEventListener('pointerdown', (ev) => beginDrag(ev, word, null, chip));
                bank.appendChild(chip);
            });

            slots().forEach((slot) => {
                slot.addEventListener('pointerdown', (ev) => {
                    if (isLocked()) return;

                    // Ô đã điền: kéo sang ô khác, hoặc kéo về kho để gỡ.
                    if (slot.classList.contains('filled')) {
                        beginDrag(ev, slot.textContent, slot, slot);
                        return;
                    }

                    ev.preventDefault();
                    if (pickedWord) {
                        placeWord(pickedWord, slot);
                        clearPick();
                    }
                });
            });

            syncChips();
            return card;
        },

        drag_drop_disappear(entry) { return ExamRenderers.byType._dragDropLike(entry); },
        drag_drop_reuse(entry) { return ExamRenderers.byType._dragDropLike(entry); },

        short_answer(entry) {
            const q = entry.question;
            const card = makeCard(entry);

            const saved = AttemptState.getAnswer(q.id);
            const savedList = Array.isArray(saved) ? saved : (saved ? [String(saved)] : []);
            const { html, blankCount } = renderTextWithBlanks(q.text, savedList, false);

            // Đề có ___ -> ô nhập nằm ngay tại chỗ trống.
            if (blankCount > 0) {
                appendTextBlock(card, html);
                decorateBlankNumbers(card, entry);
                wireBlankInputs(card, q);
                card.classList.add('has-inline-blanks');
                return card;
            }

            // Đề không có ___ -> ô nhập đặt bên dưới, như cũ.
            appendTextBlock(card, q.text);

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control exam-short-input';
            input.value = typeof saved === 'string' ? saved : (savedList[0] || '');

            input.addEventListener('input', () => {
                AttemptAnswers.queueSave(q, input.value);
            });

            card.appendChild(input);
            return card;
        },

        essay(entry) {
            if (AttemptState.skill === 'speaking') {
                return ExamRenderers.byType._speakingRecorder(entry);
            }

            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const taskImage = q.question_data && q.question_data.task_image;
            if (taskImage) {
                const img = document.createElement('img');
                img.src = taskImage;
                img.alt = '';
                img.style.maxWidth = '100%';
                img.style.borderRadius = '8px';
                img.style.marginBottom = '12px';
                card.appendChild(img);
            }

            const textarea = document.createElement('textarea');
            textarea.className = 'exam-essay-textarea';
            textarea.value = AttemptState.getAnswer(q.id) || '';
            textarea.placeholder = 'Enter your work...';

            const counter = document.createElement('div');
            counter.className = 'exam-word-count';

            const updateCount = () => {
                const words = textarea.value.trim() ? textarea.value.trim().split(/\s+/).length : 0;
                counter.textContent = words + ' từ';
            };
            updateCount();

            textarea.addEventListener('input', () => {
                AttemptAnswers.queueSave(q, textarea.value);
                updateCount();
            });

            card.appendChild(textarea);
            card.appendChild(counter);
            return card;
        },

        /**
         * Speaking — màn hình 2 cột dùng chung với preview của giáo viên
         * (public/assets/js/ielts-shared/speaking-stage.js). Ở đây chỉ nối
         * các "cửa" dữ liệu riêng của trang làm bài: lưu file thu qua
         * AttemptAnswers.saveSpeaking(), và với mock test thì lấy Model
         * Answer qua endpoint riêng sau khi đã trả lời.
         */
        _speakingRecorder(entry) {
            const q = entry.question;

            if (!window.IeltsSpeakingStage) {
                const fallback = makeCard(entry);
                appendTextBlock(fallback, q.text);
                return fallback;
            }

            const meta = window.ATTEMPT_META || {};
            const isMock = !!meta.isMockTest;
            const partEntries = AttemptState.entries.filter((e) => e.partIndex === entry.partIndex);
            const position = partEntries.findIndex((e) => e.question.id === q.id);
            const part = entry.part || {};

            return window.IeltsSpeakingStage.render(entry, {
                isMock,
                partNumber: part.part_number || (entry.partIndex + 1),
                questionLabel: (q.title && String(q.title).trim()) || ('Question ' + entry.startNumber),
                stepLabel: partEntries.length > 1
                    ? `${part.title || 'Part ' + (entry.partIndex + 1)} — question ${position + 1} of ${partEntries.length}`
                    : (part.title || ''),
                storageKey: 'attempt-' + (meta.attemptId || 0),
                locked: ExamRenderers.isSpeakingLocked(),
                hasNext: position >= 0 && position < partEntries.length - 1,

                getRecordingUrl: (questionId) => AttemptState.getAnswer(questionId) || null,

                // Lưu ngay object URL vào state để quay lại câu này vẫn nghe
                // được bản thu dù upload chưa xong, rồi thay bằng URL server.
                saveRecording: (questionId, blob) => {
                    AttemptState.setAnswer(questionId, URL.createObjectURL(blob));
                    return AttemptAnswers.saveSpeaking(questionId, blob).then((serverUrl) => {
                        if (serverUrl) AttemptState.setAnswer(questionId, serverUrl);
                        return serverUrl;
                    });
                },

                fetchModelAnswer: isMock ? ExamRenderers.fetchSpeakingModelAnswer : null,

                onDone: () => {
                    if (ExamRenderers.speakingNav && ExamRenderers.speakingNav.onQuestionDone) {
                        ExamRenderers.speakingNav.onQuestionDone();
                    }
                },

                onNext: () => {
                    if (ExamRenderers.speakingNav && ExamRenderers.speakingNav.next) {
                        ExamRenderers.speakingNav.next();
                    }
                },
            });
        },
    },

    /** Ép dừng ghi âm đang chạy (nếu có) — gọi trước khi chuyển Part Speaking. */
    stopAnyActiveRecording() {
        if (this.activeRecorder) {
            this.activeRecorder.stop();
            this.activeRecorder = null;
        }
        if (window.IeltsSpeakingStage) window.IeltsSpeakingStage.stopActiveRecording();
    },

    /** Hết giờ / đã nộp -> cột câu hỏi mang class is-locked (xem layout.js). */
    isSpeakingLocked() {
        const questions = document.querySelector('.exam-questions');
        return !!(questions && questions.classList.contains('is-locked'));
    },

    /**
     * Mock test: Model Answer không đi kèm payload, phải gọi server và chỉ
     * được trả về sau khi câu đó đã có bản thu (xem speakingModelAnswer()).
     */
    fetchSpeakingModelAnswer(questionId) {
        const template = window.ATTEMPT_SPEAKING_MODEL_ANSWER_URL_TEMPLATE;
        if (!template) return Promise.resolve(null);

        return fetch(template.replace('__QUESTION_ID__', questionId), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
        })
            .then((r) => (r.ok ? r.json() : null))
            .then((data) => (data && data.model_answer) || null)
            .catch(() => null);
    },
};