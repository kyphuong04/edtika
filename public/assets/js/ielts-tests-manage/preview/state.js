/**
 * state.js — Preview exam state
 *
 * Chịu trách nhiệm:
 *  - Duyệt cây dữ liệu (sections -> parts -> groups -> questions) thành
 *    danh sách phẳng, đánh số Q1, Q2... THEO ĐÚNG THỨ TỰ backend thật dùng
 *    khi lưu DB (numbering CHẠY LIÊN TỤC qua toàn bộ 6 skill, KHÔNG reset
 *    về 1 ở mỗi skill — khác với cách trang admin hiển thị số tạm khi biên
 *    tập). Điều này quan trọng để Preview phản ánh đúng số thứ tự học viên
 *    thật sẽ thấy.
 *  - Lưu đáp án học viên nhập (theo question.id — id trong DB, duy nhất
 *    toàn bài test).
 */

// const SKILL_ORDER = ['listening', 'reading', 'writing', 'speaking', 'grammar', 'vocabulary'];
// SAU
const SKILL_ORDER = (typeof window !== 'undefined'
    && Array.isArray(window.PREVIEW_SKILL_ORDER)
    && window.PREVIEW_SKILL_ORDER.length)
    ? window.PREVIEW_SKILL_ORDER
    : ['listening', 'reading', 'writing', 'speaking', 'grammar', 'vocabulary'];

const SKILL_LABELS = {
    listening: 'Listening',
    reading: 'Reading',
    writing: 'Writing',
    speaking: 'Speaking',
    grammar: 'Grammar',
    vocabulary: 'Vocabulary',
};

const MOCK_SKILL_DURATIONS_SECONDS = {
    listening: 32 * 60,
    reading: 60 * 60,
    writing: 60 * 60,
};

const MOCK_SPEAKING_PART_DURATION_SECONDS = 5 * 60;

const PreviewState = {
    skills: [],          // danh sách skill có dữ liệu, theo đúng thứ tự chuẩn
    bySkill: {},          // skill -> { parts: [...], entries: [...] }
    allEntries: [],        // toàn bộ entries, theo thứ tự đánh số global
    answers: {},           // questionId -> giá trị đáp án (kiểu tuỳ loại câu hỏi)
    // current: { skill: null, partIndex: 0 },
    current: { skill: null, partIndex: 0, questionId: null },
    submitted: false,
    scoreResult: null,
    lockedSkills: {},
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
        return String(value).trim() !== '';
    },
};

function normalizeCompareText(value) {
    return String(value == null ? '' : value).trim().toLowerCase();
}

function splitAnswerVariants(rawValue) {
    return String(rawValue == null ? '' : rawValue)
        .split('/')
        .map(normalizeCompareText)
        .filter(Boolean);
}

function buildPreviewModel(previewData) {
    const sections = (previewData && previewData.sections) || {};

    SKILL_ORDER.forEach((skill) => {
        const sectionData = sections[skill];
        const parts = (sectionData && Array.isArray(sectionData.parts)) ? sectionData.parts : [];
        if (!parts.length) return;

        const entries = [];
        // Số thứ tự câu hỏi RESET về 1 ở đầu mỗi skill — khác với
        // question_number thật lưu trong DB (chạy liên tục xuyên suốt cả
        // bài, xem buildInlineQuestionData()/createQuestionInPart() ở
        // backend). Đây là lựa chọn hiển thị riêng cho Preview để giáo viên
        // dễ đối chiếu theo từng kỹ năng (Câu 1-40 Listening, rồi lại Câu
        // 1-40 Reading...), không phản ánh số thứ tự học viên thật sẽ thấy
        // khi làm bài live.
        let skillNumber = 1;

        parts.forEach((part, partIndex) => {
            const groups = Array.isArray(part.groups) ? part.groups : [];

            groups.forEach((group) => {
                const questions = Array.isArray(group.questions) ? group.questions : [];

                questions.forEach((question) => {
                    const slotCount = Math.max(1, parseInt(question.slotCount, 10) || 1);
                    const startNumber = skillNumber;
                    const endNumber = skillNumber + slotCount - 1;
                    skillNumber += slotCount;

                    entries.push({
                        skill,
                        partIndex,
                        part,
                        group,
                        question,
                        startNumber,
                        endNumber,
                        slotCount,
                    });
                });
            });
        });

        if (entries.length) {
            PreviewState.skills.push(skill);
            PreviewState.bySkill[skill] = {
                parts,
                entries,
                sectionFiles: (sectionData && sectionData.files) || {},
            };
            PreviewState.allEntries.push(...entries);
        }
    });

    // if (PreviewState.skills.length) {
    //     PreviewState.current.skill = PreviewState.skills[0];
    //     PreviewState.current.partIndex = 0;
    // }
    if (PreviewState.skills.length) {
        PreviewState.current.skill = PreviewState.skills[0];
        PreviewState.current.partIndex = 0;

        const firstEntries = PreviewState.bySkill[PreviewState.current.skill].entries;
        PreviewState.current.questionId = firstEntries.length ? firstEntries[0].question.id : null;
    }
}
