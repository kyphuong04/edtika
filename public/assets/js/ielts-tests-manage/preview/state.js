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

const SKILL_ORDER = ['listening', 'reading', 'writing', 'speaking', 'grammar', 'vocabulary'];

const SKILL_LABELS = {
    listening: 'Listening',
    reading: 'Reading',
    writing: 'Writing',
    speaking: 'Speaking',
    grammar: 'Grammar',
    vocabulary: 'Vocabulary',
};

const PreviewState = {
    skills: [],          // danh sách skill có dữ liệu, theo đúng thứ tự chuẩn
    bySkill: {},          // skill -> { parts: [...], entries: [...] }
    allEntries: [],        // toàn bộ entries, theo thứ tự đánh số global
    answers: {},           // questionId -> giá trị đáp án (kiểu tuỳ loại câu hỏi)
    current: { skill: null, partIndex: 0 },
    submitted: false,
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

/**
 * So sánh 2 đáp án dạng text, không phân biệt hoa/thường và khoảng trắng
 * thừa. Dùng chung bởi renderers.js (tô màu DOM) và grading.js (tính điểm
 * thuần) — đặt ở đây (file nạp đầu tiên) để cả 2 nơi đều chắc chắn có sẵn,
 * không phụ thuộc vào thứ tự nạp <script> giữa chúng.
 */
function normalizeCompareText(value) {
    return String(value == null ? '' : value).trim().toLowerCase();
}

/**
 * Xây dựng model phẳng từ window.PREVIEW_DATA.
 */
function buildPreviewModel(previewData) {
    const sections = (previewData && previewData.sections) || {};
    let globalNumber = 1;

    SKILL_ORDER.forEach((skill) => {
        const sectionData = sections[skill];
        const parts = (sectionData && Array.isArray(sectionData.parts)) ? sectionData.parts : [];
        if (!parts.length) return;

        const entries = [];

        parts.forEach((part, partIndex) => {
            const groups = Array.isArray(part.groups) ? part.groups : [];

            groups.forEach((group) => {
                const questions = Array.isArray(group.questions) ? group.questions : [];

                questions.forEach((question) => {
                    const slotCount = Math.max(1, parseInt(question.slotCount, 10) || 1);
                    const startNumber = globalNumber;
                    const endNumber = globalNumber + slotCount - 1;
                    globalNumber += slotCount;

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
            PreviewState.bySkill[skill] = { parts, entries };
            PreviewState.allEntries.push(...entries);
        }
    });

    if (PreviewState.skills.length) {
        PreviewState.current.skill = PreviewState.skills[0];
        PreviewState.current.partIndex = 0;
    }
}
