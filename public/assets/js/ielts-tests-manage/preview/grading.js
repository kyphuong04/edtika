/**
 * grading.js — tính đúng/sai THUẦN từ dữ liệu (PreviewState.answers), không
 * đụng DOM. Dùng để tính tổng điểm cho toàn bộ bài test kể cả những câu
 * đang không hiển thị trên màn hình hiện tại (khác skill/part).
 *
 * ExamRenderers.grade() trong renderers.js xử lý phần TÔ MÀU DOM cho câu
 * đang hiển thị; file này chỉ trả về true/false/null (null = không tự
 * chấm được, ví dụ essay).
 *
 * QUAN TRỌNG: chấm theo TỪNG SLOT (từng số Q hiển thị trên navigator), không
 * phải theo cả entry/câu hỏi gộp. Một table_completion/note_completion có
 * nhiều blank (VD Q7–Q10) thì mỗi blank được chấm và tô màu ĐỘC LẬP — khớp
 * với cách renderers.js đang tô màu từng .exam-blank-input riêng lẻ. Nếu
 * chấm theo cả entry (all-or-nothing) thì chỉ cần 1 blank sai là toàn bộ
 * dải số đó bị tính sai hết, dù các blank khác đã đúng — đây là lỗi đã xảy
 * ra trước khi có gradeSlots().
 */

const ExamGrading = {

    /**
     * Kết quả ĐÚNG/SAI cho CẢ câu hỏi (all-or-nothing) — giữ lại cho các nhu
     * cầu khác trong tương lai, KHÔNG dùng cho navigator/tổng điểm nữa (xem
     * gradeSlots bên dưới).
     */
    isCorrect(entry) {
        const q = entry.question;
        const fn = this.byType[q.type] || this.byType.short_answer;
        return fn.call(this.byType, entry);
    },

    /**
     * Kết quả ĐÚNG/SAI cho TỪNG SLOT (từng số Q) của 1 entry — mảng có độ
     * dài = entry.slotCount, phần tử idx tương ứng với số
     * (entry.startNumber + idx).
     */
    gradeSlots(entry) {
        const q = entry.question;
        const fn = this.slotsByType[q.type] || this.slotsByType.short_answer;
        return fn.call(this.slotsByType, entry);
    },

    /**
     * Chấm toàn bộ bài theo từng slot, trả về { correct, incorrect,
     * ungraded, total, bySlot }. bySlot: questionId -> mảng true|false|null
     * (dùng lại bởi layout.js để tô màu navigator, tránh tính lại nhiều lần).
     */
    computeScore() {
        let correct = 0;
        let incorrect = 0;
        let ungraded = 0;
        const bySlot = {};

        PreviewState.allEntries.forEach((entry) => {
            const slots = this.gradeSlots(entry);
            bySlot[entry.question.id] = slots;

            slots.forEach((result) => {
                if (result === true) correct++;
                else if (result === false) incorrect++;
                else ungraded++;
            });
        });

        return {
            correct,
            incorrect,
            ungraded,
            total: correct + incorrect + ungraded,
            bySlot,
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
                answerMap[a.row + '-' + a.col] = (a.answers || []).map(splitAnswerVariants);
            });

            const cellKeys = Object.keys(answerMap);
            if (!cellKeys.length) return false;

            const saved = PreviewState.getAnswer(q.id) || {};

            return cellKeys.every((cellKey) => {
                const acceptedPerBlank = answerMap[cellKey];
                const savedForCell = saved[cellKey] || [];

                if (!acceptedPerBlank.length) return false;

                return acceptedPerBlank.every((acceptedVariants, idx) => {
                    return acceptedVariants.includes(normalizeCompareText(savedForCell[idx]));
                });
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

    /**
     * Bản "chấm theo từng slot" — mỗi hàm trả về MẢNG kết quả, độ dài khớp
     * entry.slotCount. Các loại chỉ có 1 slot (multiple choice đơn, TFNG,
     * matching, short_answer, essay) đơn giản là bọc kết quả byType vào
     * mảng 1 phần tử. multiple_choice_multiple giữ nguyên kiểu all-or-
     * nothing (không có cách tách "số nào ứng với lựa chọn nào" một cách tự
     * nhiên) nhưng vẫn lặp lại đúng kết quả cho từng số nó chiếm, để tô màu
     * nhất quán trên toàn dải số của nó.
     */
    slotsByType: {

        _wrapSingle(result) {
            return [result];
        },

        multiple_choice_single(entry) {
            return this._wrapSingle(ExamGrading.byType.multiple_choice_single.call(ExamGrading.byType, entry));
        },
        true_false_not_given(entry) {
            return this._wrapSingle(ExamGrading.byType.true_false_not_given.call(ExamGrading.byType, entry));
        },
        yes_no_not_given(entry) {
            return this._wrapSingle(ExamGrading.byType.yes_no_not_given.call(ExamGrading.byType, entry));
        },
        matching_headings(entry) {
            return this._wrapSingle(ExamGrading.byType.matching_headings.call(ExamGrading.byType, entry));
        },
        matching_information(entry) {
            return this._wrapSingle(ExamGrading.byType.matching_information.call(ExamGrading.byType, entry));
        },
        matching_features(entry) {
            return this._wrapSingle(ExamGrading.byType.matching_features.call(ExamGrading.byType, entry));
        },
        matching_sentence_endings(entry) {
            return this._wrapSingle(ExamGrading.byType.matching_sentence_endings.call(ExamGrading.byType, entry));
        },
        short_answer(entry) {
            return this._wrapSingle(ExamGrading.byType.short_answer.call(ExamGrading.byType, entry));
        },
        essay() {
            return [null];
        },

        multiple_choice_multiple(entry) {
            const whole = ExamGrading.byType.multiple_choice_multiple.call(ExamGrading.byType, entry);
            const count = Math.max(1, entry.slotCount || 1);
            return new Array(count).fill(whole);
        },

        _completionLike(entry) {
            const q = entry.question;
            const groups = q.correctAnswerGroups || [];
            const saved = PreviewState.getAnswer(q.id) || [];
            const count = Math.max(1, entry.slotCount || groups.length || 1);

            return Array.from({ length: count }, (_, idx) => {
                const accepted = (groups[idx] || []).map(normalizeCompareText);
                if (!accepted.length) return null; // chưa cấu hình đáp án cho blank này
                return accepted.includes(normalizeCompareText(saved[idx]));
            });
        },
        sentence_completion(entry) { return this._completionLike(entry); },
        summary_completion(entry) { return this._completionLike(entry); },
        note_completion(entry) { return this._completionLike(entry); },
        diagram_labeling(entry) { return this._completionLike(entry); },

        table_completion(entry) {
            const q = entry.question;
            const structure = q.table_structure || { answers: [] };
            const saved = PreviewState.getAnswer(q.id) || {};

            // Làm phẳng theo đúng thứ tự cell đã lưu (row-major) — khớp thứ
            // tự đánh số Q trên UI. Một cell có thể chứa nhiều blank
            // (blank_count > 1), nên duyệt cả 2 cấp: cell rồi tới blank
            // trong cell.
            const flatResults = [];
            (structure.answers || []).forEach((cellAnswer) => {
                const cellKey = cellAnswer.row + '-' + cellAnswer.col;
                const acceptedPerBlank = (cellAnswer.answers || []).map(splitAnswerVariants);
                const savedForCell = saved[cellKey] || [];

                acceptedPerBlank.forEach((acceptedVariants, blankIdx) => {
                    if (!acceptedVariants.length) {
                        flatResults.push(null);
                        return;
                    }
                    flatResults.push(acceptedVariants.includes(normalizeCompareText(savedForCell[blankIdx])));
                });
            });

            const count = Math.max(1, entry.slotCount || flatResults.length || 1);
            if (flatResults.length === count) return flatResults;

            // Phòng trường hợp slotCount lưu lúc tạo đề lệch với số blank
            // thật flatten được (VD 1 cell có nhiều blank) — chuẩn hoá độ
            // dài để không làm vỡ vòng lặp Q{start}..Q{end} ở navigator.
            return Array.from({ length: count }, (_, idx) => (idx < flatResults.length ? flatResults[idx] : null));
        },

        _dragDropLike(entry) {
            const q = entry.question;
            const correct = q.correctAnswers || [];
            const saved = PreviewState.getAnswer(q.id) || [];
            const count = Math.max(1, entry.slotCount || correct.length || 1);

            return Array.from({ length: count }, (_, idx) => {
                if (correct[idx] === undefined) return null;
                return normalizeCompareText(saved[idx]) === normalizeCompareText(correct[idx]);
            });
        },
        drag_drop_disappear(entry) { return this._dragDropLike(entry); },
        drag_drop_reuse(entry) { return this._dragDropLike(entry); },
    },
};