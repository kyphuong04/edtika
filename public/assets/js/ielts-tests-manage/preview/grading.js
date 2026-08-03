/**
 * grading.js — tính đúng/sai THUẦN từ dữ liệu (PreviewState.answers), không
 * đụng DOM. Dùng để tính tổng điểm cho toàn bộ bài test kể cả những câu
 * đang không hiển thị trên màn hình hiện tại (khác skill/part).
 *
 * ExamRenderers.grade() trong renderers.js xử lý phần TÔ MÀU DOM cho câu
 * đang hiển thị; file này chỉ trả về true/false/null (null = không tự
 * chấm được, ví dụ essay).
 */

const ExamGrading = {

    isCorrect(entry) {
        const q = entry.question;
        const fn = this.byType[q.type] || this.byType.short_answer;
        // QUAN TRỌNG: gọi bằng .call(this.byType, ...) chứ không phải fn(entry)
        // trực tiếp — nhiều hàm trong byType uỷ quyền qua this.xxx(entry)
        // (VD true_false_not_given -> this.multiple_choice_single), nên cần
        // giữ đúng `this` trỏ vào object byType khi gọi.
        return fn.call(this.byType, entry);
    },

    /**
     * Chấm toàn bộ bài, trả về { correct, incorrect, ungraded, total, byEntry }.
     * byEntry: Map questionId -> true|false|null
     */
    computeScore() {
        let correct = 0;
        let incorrect = 0;
        let ungraded = 0;
        const byEntry = {};

        PreviewState.allEntries.forEach((entry) => {
            const result = this.isCorrect(entry);
            byEntry[entry.question.id] = result;

            if (result === true) correct++;
            else if (result === false) incorrect++;
            else ungraded++;
        });

        return {
            correct,
            incorrect,
            ungraded,
            total: PreviewState.allEntries.length,
            byEntry,
        };
    },

    byType: {

        multiple_choice_single(entry) {
            const q = entry.question;
            const saved = PreviewState.getAnswer(q.id);
            if (saved === undefined) return false;
            return normalizeCompareText(saved) === normalizeCompareText(q.correctAnswer);
        },

        multiple_choice_multiple(entry) {
            const q = entry.question;
            const saved = PreviewState.getAnswer(q.id) || [];
            const correct = q.correctAnswers || [];
            if (!correct.length) return false;
            if (saved.length !== correct.length) return false;
            const correctSet = new Set(correct.map(normalizeCompareText));
            return saved.every((v) => correctSet.has(normalizeCompareText(v)));
        },

        true_false_not_given(entry) {
            return this.multiple_choice_single(entry);
        },

        yes_no_not_given(entry) {
            return this.multiple_choice_single(entry);
        },

        _matchingLike(entry) {
            return this.multiple_choice_single(entry);
        },

        matching_headings(entry) { return this._matchingLike(entry); },
        matching_information(entry) { return this._matchingLike(entry); },
        matching_features(entry) { return this._matchingLike(entry); },
        matching_sentence_endings(entry) { return this._matchingLike(entry); },

        _completionLike(entry) {
            const q = entry.question;
            const groups = q.correctAnswerGroups || [];
            if (!groups.length) return false;

            const saved = PreviewState.getAnswer(q.id) || [];
            return groups.every((variants, idx) => {
                const accepted = (variants || []).map(normalizeCompareText);
                return accepted.includes(normalizeCompareText(saved[idx]));
            });
        },

        sentence_completion(entry) { return this._completionLike(entry); },
        summary_completion(entry) { return this._completionLike(entry); },
        note_completion(entry) { return this._completionLike(entry); },
        diagram_labeling(entry) { return this._completionLike(entry); },

        table_completion(entry) {
            const q = entry.question;
            const structure = q.table_structure || { rows: [], answers: [] };
            const answerMap = {};
            (structure.answers || []).forEach((a) => {
                answerMap[a.row + '-' + a.col] = a.answers || [];
            });

            const cellKeys = Object.keys(answerMap);
            if (!cellKeys.length) return false;

            const saved = PreviewState.getAnswer(q.id) || {};

            return cellKeys.every((cellKey) => {
                const accepted = answerMap[cellKey].map(normalizeCompareText);
                const savedForCell = saved[cellKey] || [];
                // Mỗi cell có thể có nhiều blank; answers[] lưu theo thứ tự blank
                // trong cell đó (blank_count). Ở đây so khớp toàn bộ mảng.
                if (accepted.length !== savedForCell.length && accepted.length > 0) {
                    // Trường hợp accepted là 1 danh sách các biến thể cho ĐÚNG 1 blank
                    // (khi cell chỉ có 1 blank) -> so sánh phần tử đầu.
                    return accepted.includes(normalizeCompareText(savedForCell[0]));
                }
                return accepted.every((acceptedVal, idx) => normalizeCompareText(savedForCell[idx]) === acceptedVal)
                    || accepted.some((acceptedVal) => savedForCell.some((v) => normalizeCompareText(v) === acceptedVal));
            });
        },

        _dragDropLike(entry) {
            const q = entry.question;
            const correct = q.correctAnswers || [];
            if (!correct.length) return false;

            const saved = PreviewState.getAnswer(q.id) || [];
            return correct.every((val, idx) => normalizeCompareText(saved[idx]) === normalizeCompareText(val));
        },

        drag_drop_disappear(entry) { return this._dragDropLike(entry); },
        drag_drop_reuse(entry) { return this._dragDropLike(entry); },

        short_answer(entry) {
            const q = entry.question;
            const saved = PreviewState.getAnswer(q.id);
            const acceptedVariants = String(q.correctAnswer || '')
                .split('/')
                .map(normalizeCompareText)
                .filter(Boolean);

            if (!acceptedVariants.length) return false;
            return acceptedVariants.includes(normalizeCompareText(saved));
        },

        essay() {
            return null; // không tự chấm được
        },
    },
};
