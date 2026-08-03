/**
 * renderers.js — render + chấm điểm từng loại câu hỏi.
 *
 * Mỗi renderer nhận `entry` (từ state.js: { question, startNumber, endNumber, slotCount, ... })
 * và trả về 1 <div class="exam-question-card"> đã gắn sẵn event lưu đáp án
 * vào PreviewState.answers[question.id].
 *
 * ExamRenderers.grade(entry) được gọi khi bấm "Nộp bài" — tô màu đúng/sai
 * dựa trên correctAnswer/correctAnswers/correctAnswerGroups đã có sẵn
 * trong payload (do buildInlineQuestionData() ở backend chuẩn hoá).
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

/**
 * Chèn <input> vào mọi vị trí ___ trong 1 đoạn HTML. Trả về HTML mới +
 * số lượng blank đã chèn.
 */
function renderTextWithBlanks(html, savedValues, disabled) {
    let blankIndex = 0;
    const out = (html || '').replace(/(?:_\s*){2,}/g, () => {
        const idx = blankIndex++;
        const val = (savedValues && savedValues[idx]) || '';
        const disabledAttr = disabled ? 'disabled' : '';
        return `<input type="text" class="exam-blank-input" data-blank-index="${idx}" value="${escapeAttr(val)}" ${disabledAttr}>`;
    });
    return { html: out, blankCount: blankIndex };
}

function wireBlankInputs(container, question) {
    const inputs = container.querySelectorAll('.exam-blank-input');
    const existing = PreviewState.getAnswer(question.id) || [];

    inputs.forEach((input, idx) => {
        if (existing[idx]) input.value = existing[idx];

        input.addEventListener('input', () => {
            const current = PreviewState.getAnswer(question.id) || [];
            current[idx] = input.value;
            PreviewState.setAnswer(question.id, current);
        });
    });
}

const ExamRenderers = {

    render(entry) {
        const q = entry.question;
        const handler = this.byType[q.type] || this.byType.short_answer;
        return handler.call(this.byType, entry);
    },

    grade(entry) {
        const q = entry.question;
        const handler = this.gradeByType[q.type] || this.gradeByType.short_answer;
        // QUAN TRỌNG (giống fix trong grading.js): nhiều hàm trong gradeByType
        // uỷ quyền qua this._optionBased(...)/this._matchingGrade(...)/...
        // nên PHẢI gọi bằng .call(this.gradeByType, ...), không phải .call(this, ...)
        // (this ở đây là ExamRenderers, không có các hàm _xxx đó).
        return handler.call(this.gradeByType, entry);
    },

    byType: {

        multiple_choice_single(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const wrap = document.createElement('div');
            wrap.className = 'exam-options';

            (q.options || []).forEach((opt) => {
                const row = document.createElement('div');
                row.className = 'exam-option-row';
                row.dataset.value = opt;
                row.textContent = opt;

                if (PreviewState.getAnswer(q.id) === opt) row.classList.add('selected');

                row.addEventListener('click', () => {
                    wrap.querySelectorAll('.exam-option-row').forEach((r) => r.classList.remove('selected'));
                    row.classList.add('selected');
                    PreviewState.setAnswer(q.id, opt);
                });

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
            const saved = PreviewState.getAnswer(q.id) || [];

            (q.options || []).forEach((opt) => {
                const row = document.createElement('div');
                row.className = 'exam-option-row';
                row.dataset.value = opt;
                row.textContent = opt;

                if (saved.includes(opt)) row.classList.add('selected');

                row.addEventListener('click', () => {
                    const current = PreviewState.getAnswer(q.id) || [];
                    const idx = current.indexOf(opt);
                    if (idx >= 0) {
                        current.splice(idx, 1);
                        row.classList.remove('selected');
                    } else {
                        current.push(opt);
                        row.classList.add('selected');
                    }
                    PreviewState.setAnswer(q.id, current);
                });

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
            appendTextBlock(card, q.text);

            const wrap = document.createElement('div');
            wrap.className = 'exam-options';

            choices.forEach((opt) => {
                const row = document.createElement('div');
                row.className = 'exam-option-row';
                row.dataset.value = opt;
                row.textContent = opt;

                if (PreviewState.getAnswer(q.id) === opt) row.classList.add('selected');

                row.addEventListener('click', () => {
                    wrap.querySelectorAll('.exam-option-row').forEach((r) => r.classList.remove('selected'));
                    row.classList.add('selected');
                    PreviewState.setAnswer(q.id, opt);
                });

                wrap.appendChild(row);
            });

            card.appendChild(wrap);
            return card;
        },

        _matchingLike(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const select = document.createElement('select');
            select.className = 'form-control';
            select.style.maxWidth = '160px';

            const blank = document.createElement('option');
            blank.value = '';
            blank.textContent = '-- Chọn --';
            select.appendChild(blank);

            Object.keys(q.options || {}).sort().forEach((key) => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = key;
                select.appendChild(option);
            });

            const saved = PreviewState.getAnswer(q.id);
            if (saved) select.value = saved;

            select.addEventListener('change', () => {
                PreviewState.setAnswer(q.id, select.value);
            });

            card.appendChild(select);
            return card;
        },

        matching_headings(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_information(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_features(entry) { return ExamRenderers.byType._matchingLike(entry); },
        matching_sentence_endings(entry) { return ExamRenderers.byType._matchingLike(entry); },

        _completionLike(entry) {
            const q = entry.question;
            const card = makeCard(entry);

            const saved = PreviewState.getAnswer(q.id) || [];
            const { html } = renderTextWithBlanks(q.text, saved, false);
            appendTextBlock(card, html);
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

            const structure = q.table_structure || { headers: [], rows: [] };
            const headers = structure.headers || [];
            const rows = structure.rows || [];

            const wrap = document.createElement('div');
            wrap.className = 'exam-table-wrap';

            const table = document.createElement('table');
            const thead = document.createElement('thead');
            const headRow = document.createElement('tr');
            headers.forEach((h) => {
                const th = document.createElement('th');
                th.textContent = h || '';
                headRow.appendChild(th);
            });
            thead.appendChild(headRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            const savedByCell = PreviewState.getAnswer(q.id) || {};

            rows.forEach((row, rIdx) => {
                const cells = Array.isArray(row) ? row : (row.cells || []);
                const tr = document.createElement('tr');

                cells.forEach((cellText, cIdx) => {
                    const td = document.createElement('td');
                    const cellKey = rIdx + '-' + cIdx;
                    const savedForCell = savedByCell[cellKey] || [];
                    const { html, blankCount } = renderTextWithBlanks(cellText, savedForCell, false);
                    td.innerHTML = html;

                    if (blankCount > 0) {
                        td.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
                            input.addEventListener('input', () => {
                                const current = PreviewState.getAnswer(q.id) || {};
                                current[cellKey] = current[cellKey] || [];
                                current[cellKey][idx] = input.value;
                                PreviewState.setAnswer(q.id, current);
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

            const saved = PreviewState.getAnswer(q.id) || [];
            const { html, blankCount } = renderTextWithBlanks(q.text, [], false);

            // Thay input bằng slot span (kéo-thả bằng click-chọn, không cần
            // HTML5 drag API để đảm bảo hoạt động ổn định trên mọi thiết bị).
            let slotIndex = 0;
            const slotHtml = html.replace(/<input[^>]*class="exam-blank-input"[^>]*>/g, () => {
                const idx = slotIndex++;
                const val = saved[idx] || '';
                return `<span class="exam-blank-slot ${val ? 'filled' : ''}" data-blank-index="${idx}">${escapeAttr(val) || '&hellip;'}</span>`;
            });

            appendTextBlock(card, slotHtml);

            const bank = document.createElement('div');
            bank.className = 'exam-dd-bank';

            let activeChip = null;

            (q.options || []).forEach((word) => {
                const chip = document.createElement('span');
                chip.className = 'exam-dd-chip';
                chip.textContent = word;
                chip.dataset.word = word;

                chip.addEventListener('click', () => {
                    if (chip.classList.contains('used') && q.type === 'drag_drop_disappear') return;
                    bank.querySelectorAll('.exam-dd-chip').forEach((c) => c.classList.remove('active-pick'));
                    chip.classList.add('active-pick');
                    activeChip = word;
                });

                bank.appendChild(chip);
            });

            card.insertBefore(bank, card.querySelector('.exam-question-text').nextSibling);

            card.querySelectorAll('.exam-blank-slot').forEach((slot) => {
                slot.addEventListener('click', () => {
                    const idx = parseInt(slot.dataset.blankIndex, 10);
                    const current = PreviewState.getAnswer(q.id) || [];

                    if (slot.classList.contains('filled')) {
                        // Bấm lại slot đã điền -> gỡ ra, trả từ về ngân hàng.
                        current[idx] = '';
                        slot.textContent = '\u2026';
                        slot.classList.remove('filled');
                        if (q.type === 'drag_drop_disappear') {
                            bank.querySelectorAll('.exam-dd-chip').forEach((c) => {
                                if (c.textContent === slot.dataset.filledWord) c.classList.remove('used');
                            });
                        }
                        PreviewState.setAnswer(q.id, current);
                        return;
                    }

                    if (!activeChip) return;

                    current[idx] = activeChip;
                    slot.textContent = activeChip;
                    slot.classList.add('filled');
                    slot.dataset.filledWord = activeChip;
                    PreviewState.setAnswer(q.id, current);

                    if (q.type === 'drag_drop_disappear') {
                        bank.querySelectorAll('.exam-dd-chip').forEach((c) => {
                            if (c.dataset.word === activeChip) c.classList.add('used');
                        });
                    }

                    bank.querySelectorAll('.exam-dd-chip').forEach((c) => c.classList.remove('active-pick'));
                    activeChip = null;
                });
            });

            return card;
        },

        drag_drop_disappear(entry) { return ExamRenderers.byType._dragDropLike(entry); },
        drag_drop_reuse(entry) { return ExamRenderers.byType._dragDropLike(entry); },

        short_answer(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.style.maxWidth = '320px';
            input.value = PreviewState.getAnswer(q.id) || '';

            input.addEventListener('input', () => {
                PreviewState.setAnswer(q.id, input.value);
            });

            card.appendChild(input);
            return card;
        },

        essay(entry) {
            const q = entry.question;
            const card = makeCard(entry);
            appendTextBlock(card, q.text);

            const textarea = document.createElement('textarea');
            textarea.className = 'exam-essay-textarea';
            textarea.value = PreviewState.getAnswer(q.id) || '';
            textarea.placeholder = 'Nhập bài làm của bạn...';

            const counter = document.createElement('div');
            counter.className = 'exam-word-count';

            const updateCount = () => {
                const words = textarea.value.trim() ? textarea.value.trim().split(/\s+/).length : 0;
                counter.textContent = words + ' từ';
            };
            updateCount();

            textarea.addEventListener('input', () => {
                PreviewState.setAnswer(q.id, textarea.value);
                updateCount();
            });

            card.appendChild(textarea);
            card.appendChild(counter);
            return card;
        },
    },

    gradeByType: {

        _optionBased(entry, correctSet) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const saved = PreviewState.getAnswer(q.id);
            const savedArr = Array.isArray(saved) ? saved : (saved ? [saved] : []);
            const isCorrect = savedArr.length === correctSet.size
                && savedArr.every((v) => correctSet.has(v));

            card.querySelectorAll('.exam-option-row').forEach((row) => {
                const val = row.dataset.value;
                if (correctSet.has(val)) {
                    row.classList.add('correct-highlight');
                } else if (savedArr.includes(val)) {
                    row.classList.add('incorrect-highlight');
                }
            });

            card.classList.add(isCorrect ? 'graded-correct' : 'graded-incorrect');
            return isCorrect;
        },

        multiple_choice_single(entry) {
            return this._optionBased(entry, new Set([entry.question.correctAnswer]));
        },

        multiple_choice_multiple(entry) {
            const correct = entry.question.correctAnswers || [];
            return this._optionBased(entry, new Set(correct));
        },

        true_false_not_given(entry) {
            return this._optionBased(entry, new Set([entry.question.correctAnswer]));
        },

        yes_no_not_given(entry) {
            return this._optionBased(entry, new Set([entry.question.correctAnswer]));
        },

        _matchingGrade(entry) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const saved = PreviewState.getAnswer(q.id);
            const isCorrect = normalizeCompareText(saved) === normalizeCompareText(q.correctAnswer);
            card.classList.add(isCorrect ? 'graded-correct' : 'graded-incorrect');
            return isCorrect;
        },

        matching_headings(entry) { return this._matchingGrade(entry); },
        matching_information(entry) { return this._matchingGrade(entry); },
        matching_features(entry) { return this._matchingGrade(entry); },
        matching_sentence_endings(entry) { return this._matchingGrade(entry); },

        _completionGrade(entry) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const groups = q.correctAnswerGroups || [];
            const saved = PreviewState.getAnswer(q.id) || [];
            let allCorrect = groups.length > 0;

            card.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
                const accepted = (groups[idx] || []).map(normalizeCompareText);
                const userVal = normalizeCompareText(saved[idx]);
                const ok = accepted.includes(userVal);
                input.classList.add(ok ? 'graded-correct' : 'graded-incorrect');
                input.disabled = true;
                if (!ok) allCorrect = false;
            });

            card.classList.add(allCorrect ? 'graded-correct' : 'graded-incorrect');
            return allCorrect;
        },

        sentence_completion(entry) { return this._completionGrade(entry); },
        summary_completion(entry) { return this._completionGrade(entry); },
        note_completion(entry) { return this._completionGrade(entry); },
        diagram_labeling(entry) { return this._completionGrade(entry); },

        table_completion(entry) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const structure = q.table_structure || { answers: [] };
            const answerMap = {};
            (structure.answers || []).forEach((a) => {
                answerMap[a.row + '-' + a.col] = a.answers || [];
            });

            const saved = PreviewState.getAnswer(q.id) || {};
            let allCorrect = true;
            let hasBlank = false;

            card.querySelectorAll('td').forEach((td) => {
                const inputs = td.querySelectorAll('.exam-blank-input');
                if (!inputs.length) return;
            });

            // Duyệt lại theo cấu trúc bảng để biết chính xác row/col của từng ô.
            const rows = structure.rows || [];
            const tds = card.querySelectorAll('tbody td');
            let tdCursor = 0;
            rows.forEach((row, rIdx) => {
                const cells = Array.isArray(row) ? row : (row.cells || []);
                cells.forEach((cellText, cIdx) => {
                    const td = tds[tdCursor++];
                    if (!td) return;
                    const cellKey = rIdx + '-' + cIdx;
                    const accepted = (answerMap[cellKey] || []).map(normalizeCompareText);
                    const savedForCell = saved[cellKey] || [];

                    td.querySelectorAll('.exam-blank-input').forEach((input, idx) => {
                        hasBlank = true;
                        const ok = accepted.includes(normalizeCompareText(savedForCell[idx]));
                        input.classList.add(ok ? 'graded-correct' : 'graded-incorrect');
                        input.disabled = true;
                        if (!ok) allCorrect = false;
                    });
                });
            });

            allCorrect = hasBlank && allCorrect;
            card.classList.add(allCorrect ? 'graded-correct' : 'graded-incorrect');
            return allCorrect;
        },

        _dragDropGrade(entry) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const correct = q.correctAnswers || [];
            const saved = PreviewState.getAnswer(q.id) || [];
            let allCorrect = correct.length > 0;

            card.querySelectorAll('.exam-blank-slot').forEach((slot, idx) => {
                const ok = normalizeCompareText(saved[idx]) === normalizeCompareText(correct[idx]);
                slot.style.borderColor = ok ? '#22c55e' : '#ef4444';
                slot.style.background = ok ? '#dcfce7' : '#fee2e2';
                if (!ok) allCorrect = false;
            });

            card.classList.add(allCorrect ? 'graded-correct' : 'graded-incorrect');
            return allCorrect;
        },

        drag_drop_disappear(entry) { return this._dragDropGrade(entry); },
        drag_drop_reuse(entry) { return this._dragDropGrade(entry); },

        short_answer(entry) {
            const q = entry.question;
            const card = document.getElementById('exam-q-' + q.id);
            if (!card) return null;

            const saved = PreviewState.getAnswer(q.id);
            const acceptedVariants = String(q.correctAnswer || '')
                .split('/')
                .map(normalizeCompareText)
                .filter(Boolean);

            const isCorrect = acceptedVariants.includes(normalizeCompareText(saved));
            card.classList.add(isCorrect ? 'graded-correct' : 'graded-incorrect');
            return isCorrect;
        },

        essay() {
            // Tự luận không tự chấm được -> không tô đúng/sai, chỉ tính là "đã nộp".
            return null;
        },
    },
};
