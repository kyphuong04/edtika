/**
 * app.js — khởi động ứng dụng Preview.
 * Đọc window.PREVIEW_DATA (từ config.blade.php), dựng model, mount layout,
 * gắn sự kiện điều hướng + nộp bài.
 */

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

    ExamLayout.onTimeUp = function () {
        const skill = PreviewState.current.skill;
        if (PreviewState.lockedSkills[skill]) return; // đã khoá rồi, tránh gọi lặp
        PreviewState.lockedSkills[skill] = true;
        ExamLayout.lockSkill();
    };

    // Speaking đi tuần tự: xong câu này mới mở câu sau (Part 1 & Part 3).
    // Dùng chung IeltsSpeakingFlow với trang làm bài của học viên.
    if (window.IeltsSpeakingFlow) {
        window.IeltsSpeakingFlow.reset();
        window.IeltsSpeakingFlow.hydrate(
            PreviewState.allEntries.filter((e) => e.skill === 'speaking'),
            (questionId) => !!PreviewState.getAnswer(questionId)
        );
        ExamRenderers.speakingNav = {
            next: () => goToQuestionOffset(1),
            onQuestionDone: () => {
                updateFabState();
                ExamLayout.renderPartNav(switchPart, jumpToEntry);
            },
        };
    }

    function currentEntries() {
        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        return skillModel ? skillModel.entries : [];
    }

    // Câu speaking đang mở đã có bản thu chưa — quyết định cho đi tiếp hay không.
    function speakingCanAdvance() {
        const questionId = PreviewState.current.questionId;
        if (!questionId) return true;
        if (window.IeltsSpeakingFlow && window.IeltsSpeakingFlow.isDone(questionId)) return true;
        return !!PreviewState.getAnswer(questionId);
    }

    function currentPart() {
        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        return skillModel ? skillModel.parts[PreviewState.current.partIndex] : null;
    }

    // Sau khi đổi part/skill, đảm bảo con trỏ questionId luôn trỏ vào 1 câu
    // thuộc part đang hiển thị (để 2 nút prev/next luôn hoạt động đúng).
    function ensureCurrentQuestionValid() {
        const entries = currentEntries();
        if (!entries.length) {
            PreviewState.current.questionId = null;
            return;
        }
        const stillValid = entries.some((e) => e.partIndex === PreviewState.current.partIndex
            && e.question.id === PreviewState.current.questionId);
        if (!stillValid) {
            const partEntries = entries.filter((e) => e.partIndex === PreviewState.current.partIndex);

            if (PreviewState.current.skill === 'speaking' && window.IeltsSpeakingFlow && partEntries.length) {
                const openIndex = window.IeltsSpeakingFlow.firstOpenIndex(partEntries);
                PreviewState.current.questionId = partEntries[openIndex].question.id;
                return;
            }

            PreviewState.current.questionId = partEntries.length ? partEntries[0].question.id : null;
        }
    }

    // function refreshView() {
    //     ensureCurrentQuestionValid();

    //     ExamLayout.renderSkillTabs();
    //     ExamLayout.renderPartNav(switchPart, jumpToEntry);

    //     const part = currentPart();
    //     if (part) {
    //         const skillModel = PreviewState.bySkill[PreviewState.current.skill];
    //         ExamLayout.renderContext(part, skillModel ? skillModel.sectionFiles : null);
    //         ExamLayout.renderQuestions(part);
    //     }

    //     ExamLayout.syncTimerForContext(
    //         PreviewState.current.skill,
    //         PreviewState.current.partIndex,
    //         previewTestType
    //     );

    //     updateNextSkillButton();
    //     updateFabState();
    // }
    function refreshView() {
        ensureCurrentQuestionValid();

        ExamLayout.renderSkillTabs();
        ExamLayout.renderPartNav(switchPart, jumpToEntry);

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

        if (PreviewState.lockedSkills[PreviewState.current.skill]) {
            ExamLayout.lockSkill();
        } else {
            ExamLayout.unlockSkill();
        }

        // updateNextSkillButton();
        updateFabState();
    }

    function switchPart(partIndex) {
        if (PreviewState.current.partIndex === partIndex) return;
        PreviewState.current.partIndex = partIndex;
        PreviewState.current.questionId = null; // để ensureCurrentQuestionValid() tự chọn câu đầu part
        refreshView();
    }

    // Bắt buộc đi tuần tự — chỉ được tiến sang skill NGAY SAU trong PreviewState.skills.
    function goToNextSkill(silent) {
        const skills = PreviewState.skills;
        const idx = skills.indexOf(PreviewState.current.skill);
        if (idx === -1 || idx >= skills.length - 1) return false;

        if (!silent) {
            const remainingHere = currentEntries().filter((e) => !PreviewState.isAnswered(e.question.id));
            if (remainingHere.length > 0) {
                const proceed = window.confirm(
                    `Còn ${remainingHere.length} câu chưa trả lời ở kỹ năng này. Vẫn muốn chuyển sang kỹ năng tiếp theo?`
                );
                if (!proceed) return false;
            }
        }

        PreviewState.current.skill = skills[idx + 1];
        PreviewState.current.partIndex = 0;
        PreviewState.current.questionId = null;
        refreshView();
        return true;
    }

    function goToPrevSkill() {
        const skills = PreviewState.skills;
        const idx = skills.indexOf(PreviewState.current.skill);
        if (idx <= 0) return false;

        const prevSkill = skills[idx - 1];
        const prevEntries = PreviewState.bySkill[prevSkill].entries;
        const lastEntry = prevEntries[prevEntries.length - 1];

        PreviewState.current.skill = prevSkill;
        PreviewState.current.partIndex = lastEntry.partIndex;
        PreviewState.current.questionId = lastEntry.question.id;
        refreshView();
        return true;
    }

    function jumpToEntry(entry) {
        const needsPartSwitch = PreviewState.current.partIndex !== entry.partIndex;

        PreviewState.current.skill = entry.skill;
        PreviewState.current.questionId = entry.question.id;

        if (needsPartSwitch) {
            PreviewState.current.partIndex = entry.partIndex;
            refreshView();
        } else if (PreviewState.current.skill === 'speaking') {
            // Speaking chỉ hiện câu đang làm nên phải vẽ lại cột câu hỏi.
            ExamRenderers.stopAnyActiveRecording();
            const part = currentPart();
            if (part) ExamLayout.renderQuestions(part);
            ExamLayout.renderPartNav(switchPart, jumpToEntry);
            updateFabState();
        } else {
            ExamLayout.renderPartNav(switchPart, jumpToEntry);
            updateFabState();
        }

        window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
    }

    // Nút tròn tới/lui: di chuyển theo thứ tự câu hỏi trong SKILL HIỆN TẠI
    // (không tự nhảy sang skill khác — muốn sang skill khác phải bấm nút
    // "Chuyển kỹ năng tiếp theo" ở trên).
    function goToQuestionOffset(offset) {
        const entries = currentEntries();
        if (!entries.length) return;

        // Speaking: chưa thu xong câu hiện tại thì không cho đi tiếp.
        if (offset > 0 && PreviewState.current.skill === 'speaking' && !speakingCanAdvance()) return;

        let idx = entries.findIndex((e) => e.question.id === PreviewState.current.questionId);
        if (idx === -1) idx = 0;

        idx += offset;

        if (idx > entries.length - 1) {
            // Đã ở câu cuối cùng của skill hiện tại -> nhảy sang skill kế tiếp.
            const moved = goToNextSkill(false);
            if (moved) {
                window.setTimeout(() => {
                    const first = currentEntries()[0];
                    if (first) ExamLayout.scrollToQuestion(first.question.id);
                }, 30);
            }
            return;
        }

        if (idx < 0) {
            // Đã ở câu đầu tiên của skill hiện tại -> quay lại skill trước đó.
            const moved = goToPrevSkill();
            if (moved) {
                window.setTimeout(() => {
                    if (PreviewState.current.questionId) ExamLayout.scrollToQuestion(PreviewState.current.questionId);
                }, 30);
            }
            return;
        }

        const targetEntry = entries[idx];

        if (targetEntry.partIndex !== PreviewState.current.partIndex) {
            PreviewState.current.partIndex = targetEntry.partIndex;
            PreviewState.current.questionId = targetEntry.question.id;
            refreshView();
            window.setTimeout(() => ExamLayout.scrollToQuestion(targetEntry.question.id), 30);
            return;
        }

        jumpToEntry(targetEntry);
    }
    
    function updateFabState() {
        const skills = PreviewState.skills;
        const skillIdx = skills.indexOf(PreviewState.current.skill);
        const entries = currentEntries();
        const idx = entries.findIndex((e) => e.question.id === PreviewState.current.questionId);

        // Chỉ thực sự disable khi đã ở biên đầu/cuối của TOÀN BỘ đề thi
        // (skill đầu tiên + câu đầu tiên / skill cuối cùng + câu cuối cùng).
        ExamLayout.els.fabPrev.disabled = skillIdx <= 0 && idx <= 0;

        let nextDisabled = skillIdx >= skills.length - 1 && (idx === -1 || idx >= entries.length - 1);
        // Speaking: xong câu này mới mở câu sau.
        if (!nextDisabled && PreviewState.current.skill === 'speaking' && !speakingCanAdvance()) {
            nextDisabled = true;
        }
        ExamLayout.els.fabNext.disabled = nextDisabled;
    }

    PreviewState.onChange(() => {
        if (!PreviewState.submitted) {
            ExamLayout.renderPartNav(switchPart, jumpToEntry);
        }
    });

    // ExamLayout.els.nextSkillBtn.addEventListener('click', goToNextSkill);
    ExamLayout.els.fabPrev.addEventListener('click', () => goToQuestionOffset(-1));
    ExamLayout.els.fabNext.addEventListener('click', () => goToQuestionOffset(1));

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
        PreviewState.scoreResult = score;
        ExamLayout.showResultBanner(score);
        ExamLayout.els.submitBtn.disabled = true;
        ExamLayout.els.submitBtn.title = 'Đã nộp bài';
        ExamLayout.els.fabPrev.disabled = true;
        ExamLayout.els.fabNext.disabled = true;
        // ExamLayout.els.nextSkillBtn.disabled = true;

        refreshView();
    });

    refreshView();
});
