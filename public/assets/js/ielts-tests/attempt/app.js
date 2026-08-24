/**
 * app.js — orchestrator. Sở hữu mọi quyết định business (mock/practice,
 * khi nào poll, khi nào hết giờ tự nộp, khi nào cần gọi speakingStartPart).
 *
 * Đồng nhất UI với preview/app.js: FAB tới/lui, part navigator segment,
 * ensureCurrentQuestionValid, updateFabState. Giữ nguyên logic server-
 * authoritative (poll, speakingStartPart, finishSection).
 *
 * Yêu cầu Listening mới:
 *  - Practice Listening: audio cho phép tua/dừng (controls đầy đủ).
 *  - Mock Listening: khi bước vào skill lần đầu hiện gate "Phát" 1 lần,
 *    bấm Play mới vào làm bài; khi chuyển Part trong cùng skill thì auto-play
 *    audio Part đó (không hiện lại gate).
 *  - Cả practice/mock: khi nhấn vào làm bài hiện overlay kiểm tra mic+loa
 *    trước; có thể Bỏ qua để vào thi ngay.
 */

document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('attemptRoot');
    if (!root) return;

    buildAttemptModel(window.ATTEMPT_SECTION_DATA || {}, window.ATTEMPT_SAVED_ANSWERS || {});

    if (!AttemptState.parts.length) {
        root.innerHTML = '<div class="exam-loading">Phần thi này chưa có nội dung. Vui lòng liên hệ giáo viên.</div>';
        return;
    }

    const meta = window.ATTEMPT_META || {};
    const isMock = !!meta.isMockTest;
    const skill = meta.skill;

    let pollHandle = null;
    let currentScopeKey = null;
    let finished = false;
    let deviceChecked = false;

    ExamLayout.mount(root);
    ExamLayout.onTimerZero = handleTimeUp;

    // Speaking: nạp sẵn các câu đã có bản thu (làm dở rồi quay lại) để luồng
    // "xong câu này mới mở câu sau" không bắt học viên thu lại từ đầu.
    if (skill === 'speaking' && window.IeltsSpeakingFlow) {
        window.IeltsSpeakingFlow.reset();
        window.IeltsSpeakingFlow.hydrate(
            AttemptState.entries,
            (questionId) => !!AttemptState.getAnswer(questionId)
        );

        // Nút "Next question" trong stage điều hướng giống FAB tới.
        ExamRenderers.speakingNav = {
            next: () => goToQuestionOffset(1),
            onQuestionDone: () => {
                updateFabState();
                ExamLayout.renderPartNav(switchPart, jumpToEntry);
            },
        };
    }

    function currentEntries() {
        return AttemptState.entries;
    }

    function currentPart() {
        return AttemptState.currentPart();
    }

    function getPartAudioUrl(partIndex) {
        const part = AttemptState.parts[partIndex];
        if (!part) return null;
        const partAudio = (part.files || {}).audio;
        return partAudio || (AttemptState.sectionFiles || {}).audio || null;
    }

    function confirmListeningPartChange(targetPartIndex) {
        if (skill !== 'listening') return true;

        const oldUrl = getPartAudioUrl(AttemptState.part.index);
        const newUrl = getPartAudioUrl(targetPartIndex);

        if (!oldUrl || !newUrl || oldUrl === newUrl) {
            return true; // không có audio, hoặc dùng chung 1 file -> không cần hỏi
        }

        const oldAudio = document.querySelector('audio[data-listening-audio="1"]');
        const oldIsPlaying = oldAudio && !oldAudio.paused;

        if (oldIsPlaying) {
            const proceed = window.confirm(
                'Part bạn sắp chuyển tới dùng một file âm thanh KHÁC với Part hiện tại. '
                + 'Nếu tiếp tục, âm thanh đang phát sẽ dừng lại và audio của Part mới sẽ bắt đầu. '
                + 'Bạn có chắc chắn muốn chuyển Part không?'
            );
            if (!proceed) return false;

            oldAudio.dataset.allowPause = '1';
            oldAudio.pause();
            delete oldAudio.dataset.allowPause;
        }

        return true;
    }

    function ensureCurrentQuestionValid() {
        const entries = currentEntries();
        if (!entries.length) {
            AttemptState.current.questionId = null;
            return;
        }
        const stillValid = entries.some((e) => e.partIndex === AttemptState.part.index
            && e.question.id === AttemptState.current.questionId);
        if (!stillValid) {
            const partEntries = entries.filter((e) => e.partIndex === AttemptState.part.index);

            // Speaking: vào Part thì đứng ở câu đầu tiên chưa trả lời.
            if (skill === 'speaking' && window.IeltsSpeakingFlow && partEntries.length) {
                const openIndex = window.IeltsSpeakingFlow.firstOpenIndex(partEntries);
                AttemptState.current.questionId = partEntries[openIndex].question.id;
                return;
            }

            AttemptState.current.questionId = partEntries.length ? partEntries[0].question.id : null;
        }
    }

    function refreshView() {
        ensureCurrentQuestionValid();

        ExamLayout.renderSkillTabs();
        ExamLayout.renderPartNav(switchPart, jumpToEntry);

        const part = currentPart();
        if (part) {
            ExamLayout.renderContext(part);
            ExamLayout.renderQuestions(part);
        }

        updateFabState();
    }

    // function switchPart(partIndex) {
    //     if (AttemptState.part.index === partIndex) return;

    //     ExamRenderers.stopAnyActiveRecording();

    //     const oldAudio = document.querySelector('audio[data-listening-audio="1"]');
    //     if (oldAudio && !oldAudio.paused) {
    //         oldAudio.dataset.allowPause = '1';
    //         oldAudio.pause();
    //         delete oldAudio.dataset.allowPause;
    //     }

    //     if (skill === 'speaking' && isMock) {
    //         const targetPart = AttemptState.parts[partIndex];
    //         activateSpeakingPart(targetPart.id).then(() => {
    //             AttemptState.part.index = partIndex;
    //             AttemptState.current.questionId = null;
    //             refreshView();
    //         });
    //         return;
    //     }

    //     AttemptState.part.index = partIndex;
    //     AttemptState.current.questionId = null;
    //     refreshView();
    // }

    // SAU
    function switchPart(partIndex) {
        if (AttemptState.part.index === partIndex) return;

        if (!confirmListeningPartChange(partIndex)) return;

        ExamRenderers.stopAnyActiveRecording();

        if (skill === 'speaking' && isMock) {
            const targetPart = AttemptState.parts[partIndex];
            activateSpeakingPart(targetPart.id).then(() => {
                AttemptState.part.index = partIndex;
                AttemptState.current.questionId = null;
                refreshView();
            });
            return;
        }

        AttemptState.part.index = partIndex;
        AttemptState.current.questionId = null;
        refreshView();
        // Mock listening: renderQuestions tự quyết định auto-play/tái sử
        // dụng audio dựa theo cùng-file hay khác-file (xem layout.js).
    }

    // function jumpToEntry(entry) {
    //     const needsPartSwitch = AttemptState.part.index !== entry.partIndex;
    //     AttemptState.current.questionId = entry.question.id;

    //     if (needsPartSwitch) {
    //         if (skill === 'speaking' && isMock) {
    //             const targetPart = AttemptState.parts[entry.partIndex];
    //             ExamRenderers.stopAnyActiveRecording();
    //             activateSpeakingPart(targetPart.id).then(() => {
    //                 AttemptState.part.index = entry.partIndex;
    //                 refreshView();
    //                 window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
    //             });
    //             return;
    //         }
    //         AttemptState.part.index = entry.partIndex;
    //         refreshView();
    //     } else {
    //         ExamLayout.renderPartNav(switchPart, jumpToEntry);
    //         updateFabState();
    //     }

    //     window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
    // }

    // SAU
    function jumpToEntry(entry) {
        const needsPartSwitch = AttemptState.part.index !== entry.partIndex;

        if (needsPartSwitch && !confirmListeningPartChange(entry.partIndex)) {
            return; // người dùng huỷ -> giữ nguyên Part/câu hiện tại
        }

        AttemptState.current.questionId = entry.question.id;

        if (needsPartSwitch) {
            if (skill === 'speaking' && isMock) {
                const targetPart = AttemptState.parts[entry.partIndex];
                ExamRenderers.stopAnyActiveRecording();
                activateSpeakingPart(targetPart.id).then(() => {
                    AttemptState.part.index = entry.partIndex;
                    refreshView();
                    window.setTimeout(() => ExamLayout.scrollToQuestion(entry.question.id), 30);
                });
                return;
            }
            AttemptState.part.index = entry.partIndex;
            refreshView();
        } else if (skill === 'speaking') {
            // Speaking chỉ render câu đang mở nên phải vẽ lại cột câu hỏi.
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

    // function goToQuestionOffset(offset) {
    //     const entries = currentEntries();
    //     if (!entries.length) return;

    //     let idx = entries.findIndex((e) => e.question.id === AttemptState.current.questionId);
    //     if (idx === -1) idx = 0;
    //     idx += offset;

    //     if (idx < 0 || idx >= entries.length) return;

    //     const targetEntry = entries[idx];

    //     if (targetEntry.partIndex !== AttemptState.part.index) {
    //         if (skill === 'speaking' && isMock) {
    //             ExamRenderers.stopAnyActiveRecording();
    //             const targetPart = AttemptState.parts[targetEntry.partIndex];
    //             activateSpeakingPart(targetPart.id).then(() => {
    //                 AttemptState.part.index = targetEntry.partIndex;
    //                 AttemptState.current.questionId = targetEntry.question.id;
    //                 refreshView();
    //                 window.setTimeout(() => ExamLayout.scrollToQuestion(targetEntry.question.id), 30);
    //             });
    //             return;
    //         }
    //         AttemptState.part.index = targetEntry.partIndex;
    //         AttemptState.current.questionId = targetEntry.question.id;
    //         refreshView();
    //         window.setTimeout(() => ExamLayout.scrollToQuestion(targetEntry.question.id), 30);
    //         return;
    //     }

    //     jumpToEntry(targetEntry);
    // }

    // SAU
    function goToQuestionOffset(offset) {
        const entries = currentEntries();
        if (!entries.length) return;

        // Speaking: không cho vượt câu khi chưa thu xong câu đang mở.
        if (offset > 0 && skill === 'speaking' && !speakingCanAdvance()) return;

        let idx = entries.findIndex((e) => e.question.id === AttemptState.current.questionId);
        if (idx === -1) idx = 0;
        idx += offset;

        if (idx < 0 || idx >= entries.length) return;

        const targetEntry = entries[idx];

        if (targetEntry.partIndex !== AttemptState.part.index) {
            if (!confirmListeningPartChange(targetEntry.partIndex)) {
                return; // người dùng huỷ -> không nhảy câu/Part
            }

            if (skill === 'speaking' && isMock) {
                ExamRenderers.stopAnyActiveRecording();
                const targetPart = AttemptState.parts[targetEntry.partIndex];
                activateSpeakingPart(targetPart.id).then(() => {
                    AttemptState.part.index = targetEntry.partIndex;
                    AttemptState.current.questionId = targetEntry.question.id;
                    refreshView();
                    window.setTimeout(() => ExamLayout.scrollToQuestion(targetEntry.question.id), 30);
                });
                return;
            }
            AttemptState.part.index = targetEntry.partIndex;
            AttemptState.current.questionId = targetEntry.question.id;
            refreshView();
            window.setTimeout(() => ExamLayout.scrollToQuestion(targetEntry.question.id), 30);
            return;
        }

        jumpToEntry(targetEntry);
    }

    function updateFabState() {
        const entries = currentEntries();
        const idx = entries.findIndex((e) => e.question.id === AttemptState.current.questionId);
        const atFirst = idx <= 0;
        let atLast = idx === -1 || idx >= entries.length - 1;

        // Speaking: chỉ mở câu sau khi câu hiện tại đã thu xong.
        if (skill === 'speaking' && !atLast && !speakingCanAdvance()) {
            atLast = true;
        }

        if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.disabled = atFirst;
        if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.disabled = atLast;
    }

    // Câu speaking hiện tại đã hoàn tất (có bản thu) hay chưa.
    function speakingCanAdvance() {
        const questionId = AttemptState.current.questionId;
        if (!questionId) return true;
        if (window.IeltsSpeakingFlow && window.IeltsSpeakingFlow.isDone(questionId)) return true;
        return !!AttemptState.getAnswer(questionId);
    }

    AttemptState.onChange(() => {
        if (!finished) {
            ExamLayout.renderPartNav(switchPart, jumpToEntry);
            updateFabState();
        }
    });

    function activateSpeakingPart(partId) {
        const url = window.ATTEMPT_SPEAKING_START_PART_URL_TEMPLATE.replace('__PART_ID__', partId);

        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
        })
            .then((r) => r.json())
            .then((data) => {
                currentScopeKey = 'speaking-part-' + partId;

                if (data.status === 'expired') {
                    ExamLayout.setTimerMode('down');
                    ExamLayout.setTimerSeconds(0);
                    ExamLayout.stopTimerTick();
                    ExamLayout.els.timer.classList.add('is-time-up');
                    ExamLayout.lockSkill();
                    return;
                }

                ExamLayout.setTimerMode('down');
                ExamLayout.setTimerSeconds(data.remaining_seconds || 0);
                ExamLayout.startTimerTick();
            });
    }

    function startPolling() {
        if (!isMock) return;

        pollHandle = window.setInterval(() => {
            const params = new URLSearchParams({ skill });
            if (skill === 'speaking') {
                const part = currentPart();
                if (part) params.set('part_id', part.id);
            }

            fetch(window.ATTEMPT_SCOPE_STATUS_URL + '?' + params.toString(), {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' },
            })
                .then((r) => r.json())
                .then((data) => {
                    if (data.remaining_seconds !== null) {
                        ExamLayout.setTimerSeconds(data.remaining_seconds);
                    }
                    if (data.expired) {
                        handleTimeUp();
                    }
                })
                .catch(() => {});
        }, 20000);
    }

    function stopPolling() {
        if (pollHandle) window.clearInterval(pollHandle);
    }

    function handleTimeUp() {
        if (finished) return;
        finished = true;

        stopPolling();
        ExamRenderers.stopAnyActiveRecording();
        AttemptAnswers.flushAllPending();
        ExamLayout.lockSkill();
        if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.disabled = true;
        if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.disabled = true;
        ExamLayout.els.submitBtn.disabled = true;
        ExamLayout.els.submitBtn.title = 'Đã hết giờ — đang chuyển...';

        window.setTimeout(() => callFinishSection(true), 400);
    }

    function callFinishSection(auto) {
        return fetch(window.ATTEMPT_FINISH_SECTION_URL, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
        })
            .then((r) => r.json())
            .then((data) => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(() => {
                if (auto) {
                    window.setTimeout(() => callFinishSection(true), 3000);
                }
            });
    }

    if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.addEventListener('click', () => goToQuestionOffset(-1));
    if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.addEventListener('click', () => goToQuestionOffset(1));

    ExamLayout.els.submitBtn.addEventListener('click', () => {
        if (finished) return;

        const unanswered = AttemptState.entries.filter((e) => !AttemptState.isAnswered(e.question.id));
        if (unanswered.length > 0) {
            const proceed = window.confirm(
                `Còn ${unanswered.length} câu chưa trả lời. Vẫn muốn nộp phần thi này?`
            );
            if (!proceed) return;
        }

        finished = true;
        stopPolling();
        AttemptAnswers.flushAllPending();
        ExamLayout.lockAllInputs();
        ExamLayout.els.submitBtn.disabled = true;
        ExamLayout.els.submitBtn.title = 'Đang nộp...';
        if (ExamLayout.els.fabPrev) ExamLayout.els.fabPrev.disabled = true;
        if (ExamLayout.els.fabNext) ExamLayout.els.fabNext.disabled = true;

        window.setTimeout(() => callFinishSection(false), 200);
    });

    window.addEventListener('beforeunload', () => {
        AttemptAnswers.flushAllPending();
    });

    // ── Khởi động ────────────────────────────────────────────────
    function bootExam() {
        if (skill === 'speaking' && isMock) {
            const firstPart = currentPart();
            if (firstPart) {
                activateSpeakingPart(firstPart.id).then(startPolling);
            }
        } else if (isMock) {
            ExamLayout.setTimerMode('down');
            ExamLayout.setTimerSeconds(window.ATTEMPT_INITIAL_REMAINING_SECONDS || 0);
            ExamLayout.startTimerTick();
            startPolling();
        } else {
            ExamLayout.setTimerMode('up');
            ExamLayout.setTimerSeconds(0);
            ExamLayout.startTimerTick();
        }
        refreshView();
    }

    // Hiển thị overlay kiểm tra mic+loa trước khi vào bài (cả practice & mock)
    // Key per attempt để không hiện lại nếu reload sau khi đã qua
    const deviceKey = 'ielts-device-checked:' + (meta.attemptId || '0');
    try {
        deviceChecked = sessionStorage.getItem(deviceKey) === '1';
    } catch(e) {}

    if (!deviceChecked) {
        ExamLayout.showDeviceOverlay(() => {
            try { sessionStorage.setItem(deviceKey, '1'); } catch(e) {}
            bootExam();
        });
    } else {
        bootExam();
    }
});
