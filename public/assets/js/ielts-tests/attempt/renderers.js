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
    let blankIndex = 0;
    const out = (html || '').replace(/(?:_\s*){2,}/g, () => {
        const idx = blankIndex++;
        const val = (savedValues && savedValues[idx]) || '';
        const disabledAttr = disabled ? 'disabled' : '';
        return `<input type="text" class="exam-blank-input" data-blank-index="${idx}" value="${escapeAttr(val)}" ${disabledAttr}>`;
    });
    return { html: out, blankCount: blankIndex };
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

function wireBlankInputs(container, question) {
    const inputs = container.querySelectorAll('.exam-blank-input');
    const existing = AttemptState.getAnswer(question.id) || [];

    inputs.forEach((input, idx) => {
        if (existing[idx]) input.value = existing[idx];

        input.addEventListener('input', () => {
            const current = AttemptState.getAnswer(question.id) || [];
            current[idx] = input.value;
            AttemptAnswers.queueSave(question, current);
        });
    });
}

const ExamRenderers = {
    activeRecorder: null, // { stop: fn } — cho phép app.js ép dừng khi chuyển part

    render(entry) {
        const q = entry.question;
        const handler = this.byType[q.type] || this.byType.short_answer;
        const card = handler.call(this.byType, entry);
        appendSaveIndicator(card, q.id);
        return card;
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

                if (AttemptState.getAnswer(q.id) === opt) row.classList.add('selected');

                row.addEventListener('click', () => {
                    wrap.querySelectorAll('.exam-option-row').forEach((r) => r.classList.remove('selected'));
                    row.classList.add('selected');
                    AttemptAnswers.queueSave(q, opt);
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
            const saved = AttemptState.getAnswer(q.id) || [];

            (q.options || []).forEach((opt) => {
                const row = document.createElement('div');
                row.className = 'exam-option-row';
                row.dataset.value = opt;
                row.textContent = opt;

                if (saved.includes(opt)) row.classList.add('selected');

                row.addEventListener('click', () => {
                    const current = AttemptState.getAnswer(q.id) || [];
                    const idx = current.indexOf(opt);
                    if (idx >= 0) {
                        current.splice(idx, 1);
                        row.classList.remove('selected');
                    } else {
                        current.push(opt);
                        row.classList.add('selected');
                    }
                    AttemptAnswers.queueSave(q, current);
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

                if (AttemptState.getAnswer(q.id) === opt) row.classList.add('selected');

                row.addEventListener('click', () => {
                    wrap.querySelectorAll('.exam-option-row').forEach((r) => r.classList.remove('selected'));
                    row.classList.add('selected');
                    AttemptAnswers.queueSave(q, opt);
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

            const saved = AttemptState.getAnswer(q.id);
            if (saved) select.value = saved;

            select.addEventListener('change', () => {
                AttemptAnswers.queueSave(q, select.value);
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

            const saved = AttemptState.getAnswer(q.id) || [];
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
            const savedByCell = AttemptState.getAnswer(q.id) || {};

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

            const saved = AttemptState.getAnswer(q.id) || [];
            const { html } = renderTextWithBlanks(q.text, [], false);

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
                    const current = AttemptState.getAnswer(q.id) || [];

                    if (slot.classList.contains('filled')) {
                        current[idx] = '';
                        slot.textContent = '\u2026';
                        slot.classList.remove('filled');
                        if (q.type === 'drag_drop_disappear') {
                            bank.querySelectorAll('.exam-dd-chip').forEach((c) => {
                                if (c.textContent === slot.dataset.filledWord) c.classList.remove('used');
                            });
                        }
                        AttemptAnswers.queueSave(q, current);
                        return;
                    }

                    if (!activeChip) return;

                    current[idx] = activeChip;
                    slot.textContent = activeChip;
                    slot.classList.add('filled');
                    slot.dataset.filledWord = activeChip;
                    AttemptAnswers.queueSave(q, current);

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
            input.value = AttemptState.getAnswer(q.id) || '';

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
                questionLabel: 'Question ' + entry.startNumber,
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