/**
 * answers.js — lưu đáp án lên server (autosave), debounce theo từng câu hỏi
 * độc lập.
 *
 * HỢP ĐỒNG WIRE FORMAT (khớp IeltsTestQuestion::checkAnswer(), đã patch
 * cùng Lớp 4):
 *  - multiple_choice_single / true_false_not_given / yes_no_not_given /
 *    matching_* / short_answer      -> answer_text = string thô
 *  - multiple_choice_multiple        -> answer_options = mảng string
 *  - completion (sentence/summary/note/diagram) / drag_drop_*
 *                                     -> answer_text = JSON mảng string theo
 *                                        thứ tự blank
 *  - table_completion                -> answer_text = JSON
 *                                        { answers: [{row,col,answers:[v0,v1,...]}] }
 *                                        answers[i].answers = mảng THEO THỨ
 *                                        TỰ BLANK trong cell đó (hỗ trợ
 *                                        nhiều blank/ô)
 *  - essay (Writing)                 -> answer_text = nội dung bài viết
 *  - essay (Speaking)                -> KHÔNG qua đường này — dùng
 *                                        saveSpeaking() (multipart + file)
 */

const AttemptAnswers = {
    timers: {},
    pending: {},
    DEBOUNCE_MS: 600,
    statusListeners: [],

    onStatusChange(fn) {
        this.statusListeners.push(fn);
    },

    emitStatus(questionId, status) {
        this.statusListeners.forEach((fn) => fn(questionId, status));
    },

    queueSave(question, value) {
        AttemptState.setAnswer(question.id, value);

        const questionId = question.id;
        this.pending[questionId] = { question, value };

        if (this.timers[questionId]) {
            window.clearTimeout(this.timers[questionId]);
        }

        this.emitStatus(questionId, 'pending');

        this.timers[questionId] = window.setTimeout(() => {
            this.flush(question, value);
            delete this.pending[questionId];
        }, this.DEBOUNCE_MS);
    },

    flush(question, value) {
        const payload = this.serialize(question, value);

        fetch(window.ATTEMPT_SAVE_URL, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then((r) => {
                if (!r.ok) throw new Error('save_failed');
                return r.json();
            })
            .then(() => this.emitStatus(question.id, 'saved'))
            .catch(() => this.emitStatus(question.id, 'error'));
    },

    serialize(question, value) {
        const type = question.type;

        if (type === 'multiple_choice_multiple') {
            return { question_id: question.id, answer_options: Array.isArray(value) ? value : [] };
        }

        if (
            ['sentence_completion', 'summary_completion', 'note_completion', 'diagram_labeling',
                'drag_drop_disappear', 'drag_drop_reuse'].includes(type)
        ) {
            return { question_id: question.id, answer_text: JSON.stringify(Array.isArray(value) ? value : []) };
        }

        if (type === 'table_completion') {
            const cells = Object.keys(value || {}).map((cellKey) => {
                const [row, col] = cellKey.split('-').map(Number);
                return { row, col, answers: value[cellKey] || [] };
            });
            return { question_id: question.id, answer_text: JSON.stringify({ answers: cells }) };
        }

        return { question_id: question.id, answer_text: String(value ?? '') };
    },

    /**
     * Speaking — FormData thật kèm file audio (saveAnswer() cần
     * $request->file('audio')). Không debounce vì đây là action rời rạc.
     */
    saveSpeaking(questionId, blob) {
        const formData = new FormData();
        formData.append('question_id', questionId);
        formData.append('audio', blob, 'speaking_' + questionId + '.webm');

        this.emitStatus(questionId, 'pending');

        return fetch(window.ATTEMPT_SAVE_URL, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: formData,
        })
            .then((r) => {
                if (!r.ok) throw new Error('save_failed');
                return r.json();
            })
            .then((result) => {
                this.emitStatus(questionId, 'saved');
                return result.audio_url || null;
            })
            .catch((err) => {
                this.emitStatus(questionId, 'error');
                throw err;
            });
    },

    /** Ép lưu ngay mọi debounce đang chờ — gọi trước khi rời trang/section. */
    flushAllPending() {
        Object.keys(this.pending).forEach((questionId) => {
            window.clearTimeout(this.timers[questionId]);
            const { question, value } = this.pending[questionId];
            this.flush(question, value);
        });
        this.pending = {};
    },
};