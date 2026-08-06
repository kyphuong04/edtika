/**
 * app.js — khởi động ứng dụng Preview.
 * Đọc window.PREVIEW_DATA (từ config.blade.php), dựng model, mount layout,
 * gắn sự kiện điều hướng + nộp bài.
 */

// document.addEventListener('DOMContentLoaded', function () {
//     const root = document.getElementById('examRoot');
//     if (!root) return;

//     buildPreviewModel(window.PREVIEW_DATA || {});

//     if (!PreviewState.skills.length) {
//         root.innerHTML = '<div class="exam-loading">Đề thi chưa có nội dung nào để xem trước. '
//             + 'Quay lại trang chỉnh sửa để thêm Part và câu hỏi.</div>';
//         return;
//     }

//     ExamLayout.mount(root);

//     function currentPart() {
//         const skillModel = PreviewState.bySkill[PreviewState.current.skill];
//         return skillModel ? skillModel.parts[PreviewState.current.partIndex] : null;
//     }

//     function refreshView() {
//         ExamLayout.renderSkillTabs(switchSkill);
//         ExamLayout.renderPartTabs(switchPart);

//         const part = currentPart();
//         if (part) {
//             const skillModel = PreviewState.bySkill[PreviewState.current.skill];
//             ExamLayout.renderContext(part, skillModel ? skillModel.sectionFiles : null);
//             ExamLayout.renderQuestions(part);
//         }

//         ExamLayout.renderNavigator(jumpToEntry);
//     }

// SAU
document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('examRoot');
    if (!root) return;

    buildPreviewModel(window.PREVIEW_DATA || {});

    if (!PreviewState.skills.length) {
        root.innerHTML = '<div class="exam-loading">Đề thi chưa có nội dung nào để xem trước. '
            + 'Quay lại trang chỉnh sửa để thêm Part và câu hỏi.</div>';
        return;
    }

    const previewTestType = (window.PREVIEW_TEST_META && window.PREVIEW_TEST_META.type) || 'practice';

    ExamLayout.mount(root);

    function currentPart() {
        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        return skillModel ? skillModel.parts[PreviewState.current.partIndex] : null;
    }

    function refreshView() {
        ExamLayout.renderSkillTabs(switchSkill);
        ExamLayout.renderPartTabs(switchPart);

        const part = currentPart();
        if (part) {
            const skillModel = PreviewState.bySkill[PreviewState.current.skill];
            ExamLayout.renderContext(part, skillModel ? skillModel.sectionFiles : null);
            ExamLayout.renderQuestions(part);
        }

        ExamLayout.syncTimerForContext(
            PreviewState.current.skill,
            PreviewState.current.partIndex,
            previewTestType
        );

        ExamLayout.renderNavigator(jumpToEntry);
    }

    function switchSkill(skill) {
        if (PreviewState.current.skill === skill) return;
        PreviewState.current.skill = skill;
        PreviewState.current.partIndex = 0;
        refreshView();
    }

    function switchPart(partIndex) {
        if (PreviewState.current.partIndex === partIndex) return;
        PreviewState.current.partIndex = partIndex;
        refreshView();
    }

    function jumpToEntry(entry) {
        const needsSkillSwitch = PreviewState.current.skill !== entry.skill;
        const needsPartSwitch = PreviewState.current.partIndex !== entry.partIndex;

        if (needsSkillSwitch || needsPartSwitch) {
            PreviewState.current.skill = entry.skill;
            PreviewState.current.partIndex = entry.partIndex;
            refreshView();
        }

        // Đợi 1 tick để DOM của part vừa chuyển sang kịp render xong.
        window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
    }

    PreviewState.onChange(() => {
        if (!PreviewState.submitted) {
            ExamLayout.renderNavigator(jumpToEntry);
        }
    });

   
    ExamLayout.els.submitBtn.addEventListener('click', () => {
        if (PreviewState.submitted) return;

        const unanswered = PreviewState.allEntries.filter((e) => !PreviewState.isAnswered(e.question.id));
        if (unanswered.length > 0) {
            const proceed = window.confirm(
                `Còn ${unanswered.length} câu chưa trả lời. Vẫn muốn nộp bài để xem đáp án?`
            );
            if (!proceed) return;
        }

        PreviewState.submitted = true;

        const score = ExamGrading.computeScore();
        // Lưu lại để renderNavigator() tái sử dụng thay vì tính lại mỗi lần
        // render (mỗi lần chuyển part/skill sau khi đã nộp bài).
        PreviewState.scoreResult = score;
        ExamLayout.showResultBanner(score);
        ExamLayout.els.submitBtn.disabled = true;
        ExamLayout.els.submitBtn.textContent = 'Đã nộp bài';

        refreshView();
    });

    refreshView();
});
