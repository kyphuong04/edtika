/**
 * state.js — Attempt exam state (học viên làm bài thật).
 *
 * Khác preview/state.js:
 *  - Chỉ 1 Section (skill đang làm) mỗi lần load trang — kiến trúc
 *    multi-page ở cấp Section, SPA chỉ trong phạm vi các Part của section
 *    đó. Không có SKILL_ORDER/nhiều skill trong 1 lần load.
 *  - Dữ liệu KHÔNG chứa correctAnswer/correctAnswers/correctAnswerGroups/
 *    table_structure.answers (đã strip ở server — BuildsIeltsQuestionPayload
 *    ::stripSectionAnswers()). Không có khái niệm "chấm điểm" ở file này.
 *  - Đáp án không chỉ sống trong bộ nhớ — mọi setAnswer() nên đi kèm gọi
 *    AttemptAnswers.queueSave() (xem answers.js) để lưu lên server.
 *  - Bổ sung current.questionId để FAB tới/lui thống nhất với preview
 *    (preview điều hướng theo questionId; attempt trước đây chỉ theo part).
 */

const SKILL_LABELS = {
    listening: 'Listening',
    reading: 'Reading',
    writing: 'Writing',
    speaking: 'Speaking',
    grammar: 'Grammar',
    vocabulary: 'Vocabulary',
};

const AttemptState = {
    skill: null,
    sectionFiles: {},
    parts: [],
    entries: [],
    answers: {},
    part: { index: 0 },
    current: { questionId: null },
    listeners: [],

    onChange(fn) {
        this.listeners.push(fn);
    },

    emitChange() {
        this.listeners.forEach((fn) => fn());
    },

    setAnswer(questionId, value) {
        this.answers[questionId] = value;
        this.emitChange();
    },

    getAnswer(questionId) {
        return this.answers[questionId];
    },

    isAnswered(questionId) {
        const value = this.answers[questionId];
        if (value === undefined || value === null) return false;
        if (Array.isArray(value)) return value.some((v) => v !== undefined && v !== null && String(v).trim() !== '');
        if (typeof value === 'object') {
            // table_completion lưu dạng { "0-1": [v0, v1], ... } — phải kiểm tra
            // sâu từng blank, không thể chỉ Object.keys().length > 0.
            const vals = Object.values(value);
            if (!vals.length) return false;
            return vals.some((cellVal) => {
                if (Array.isArray(cellVal)) return cellVal.some((v) => v !== undefined && v !== null && String(v).trim() !== '');
                return String(cellVal).trim() !== '';
            });
        }
        return String(value).trim() !== '';
    },

    currentPart() {
        return this.parts[this.part.index] || null;
    },
};

function normalizeCompareText(value) {
    return String(value == null ? '' : value).trim().toLowerCase();
}

/**
 * Chuyển 1 record đã lưu (từ ATTEMPT_SAVED_ANSWERS) sang đúng shape mà
 * renderers.js kỳ vọng trong AttemptState.answers, theo type câu hỏi.
 * Ngược lại với AttemptAnswers.serialize() (answers.js) khi gửi lên.
 */
function hydrateSavedAnswerValue(question, saved) {
    if (!saved) return undefined;

    const type = question.type;

    if (type === 'multiple_choice_multiple') {
        return Array.isArray(saved.answer_options) ? saved.answer_options : [];
    }

    if (
        ['sentence_completion', 'summary_completion', 'note_completion', 'diagram_labeling',
            'drag_drop_disappear', 'drag_drop_reuse'].includes(type)
    ) {
        try {
            const parsed = JSON.parse(saved.answer_text || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    if (type === 'table_completion') {
        try {
            const parsed = JSON.parse(saved.answer_text || '{}');
            const map = {};
            (parsed.answers || []).forEach((cell) => {
                map[cell.row + '-' + cell.col] = cell.answers || [];
            });
            return map;
        } catch (e) {
            return {};
        }
    }

    // multiple_choice_single / tfng / ynng / matching_* / short_answer / essay(Writing)
    return saved.answer_text || '';
}

function buildAttemptModel(sectionData, savedAnswersRaw) {
    const savedAnswers = savedAnswersRaw || {};

    AttemptState.skill = sectionData.skill;
    AttemptState.sectionFiles = sectionData.files || {};
    AttemptState.parts = Array.isArray(sectionData.parts) ? sectionData.parts : [];

    const entries = [];
    let number = 1;

    AttemptState.parts.forEach((part, partIndex) => {
        (part.groups || []).forEach((group) => {
            (group.questions || []).forEach((question) => {
                const slotCount = Math.max(1, parseInt(question.slotCount, 10) || 1);
                const startNumber = number;
                const endNumber = number + slotCount - 1;
                number += slotCount;

                entries.push({
                    partIndex, part, group, question, startNumber, endNumber, slotCount,
                });

                const saved = savedAnswers[question.id];

                if (AttemptState.skill === 'speaking' && question.type === 'essay') {
                    // Speaking: đáp án đã lưu là URL audio thật trên server
                    // (không phải blob cục bộ) — renderers.js phân biệt qua
                    // việc value là URL http(s) hay blob:.
                    if (saved && saved.file_url) {
                        AttemptState.answers[question.id] = saved.file_url;
                    }
                } else {
                    const value = hydrateSavedAnswerValue(question, saved);
                    if (value !== undefined && value !== '' && value !== null) {
                        // Mảng rỗng / object rỗng nghĩa là chưa trả lời — không ghi
                        if (Array.isArray(value) && !value.length) { /* skip */ }
                        else if (typeof value === 'object' && !Array.isArray(value) && !Object.keys(value).length) { /* skip */ }
                        else AttemptState.answers[question.id] = value;
                    }
                }
            });
        });
    });

    AttemptState.entries = entries;

    if (entries.length) {
        AttemptState.current.questionId = entries[0].question.id;
    }
}
