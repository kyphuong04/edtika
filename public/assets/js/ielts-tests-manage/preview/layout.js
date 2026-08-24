/**
 * layout.js — dựng khung giao diện tĩnh 1 lần (mount), sau đó chỉ re-render
 * các vùng thay đổi khi người dùng chuyển skill/part hoặc nộp bài.
 */

const ExamLayout = {
    root: null,
    els: {},
    timerHandle: null,
    timerMode: 'up',        // 'up' (Practice, đếm lên) | 'down' (Mock, đếm ngược)
    timerValue: 0,          // giây hiện tại
    timerScopeKey: null,    

   
    mount(rootEl) {
        this.root = rootEl;
        rootEl.innerHTML = '';

        const header = document.createElement('div');
        header.className = 'exam-header';

        const logoWrap = document.createElement('div');
        logoWrap.className = 'exam-header-logo';
        logoWrap.innerHTML = '<span class="exam-header-logo-text">EDTIKA</span>';

        const headerMain = document.createElement('div');
        headerMain.className = 'exam-header-main';

        // Hàng 1: chỉ còn tiêu đề đề thi
        const headerRow1 = document.createElement('div');
        headerRow1.className = 'exam-header-row1';

        const testTitle = document.createElement('div');
        testTitle.className = 'exam-header-title';
        testTitle.textContent = (window.PREVIEW_TEST_META && window.PREVIEW_TEST_META.title) || '';

        headerRow1.appendChild(testTitle);

        const headerRow2 = document.createElement('div');
        headerRow2.className = 'exam-header-row2';

        const audioIndicator = document.createElement('div');
        audioIndicator.className = 'exam-audio-indicator hidden';
        audioIndicator.innerHTML = '<i class="fas fa-volume-up"></i><span>Playing sound</span>';

        const headerRow2Right = document.createElement('div');
        headerRow2Right.className = 'exam-header-row2-right';

        const skillTabs = document.createElement('div');
        skillTabs.className = 'exam-skill-tabs';

        const timer = document.createElement('div');
        timer.className = 'exam-timer';
        timer.textContent = '00:00';

        headerRow2Right.appendChild(skillTabs);
        headerRow2Right.appendChild(timer);

        headerRow2.appendChild(audioIndicator);
        headerRow2.appendChild(headerRow2Right);

        headerMain.appendChild(headerRow1);
        headerMain.appendChild(headerRow2);

        header.appendChild(logoWrap);
        header.appendChild(headerMain);

        const resultBanner = document.createElement('div');
        resultBanner.className = 'exam-result-banner';
        resultBanner.style.display = 'none';

        const body = document.createElement('div');
        body.className = 'exam-body';

        const context = document.createElement('div');
        context.className = 'exam-context';

        const resizer = document.createElement('div');
        resizer.className = 'exam-resizer';

        const questions = document.createElement('div');
        questions.className = 'exam-questions';

        body.appendChild(context);
        body.appendChild(resizer);
        body.appendChild(questions);

        const navigator = document.createElement('div');
        navigator.className = 'exam-navigator';

        const partNavBar = document.createElement('div');
        partNavBar.className = 'exam-part-nav-bar';

        const submitBtn = document.createElement('button');
        submitBtn.type = 'button';
        submitBtn.className = 'exam-submit-check-btn';
        submitBtn.title = 'Nộp bài (xem đáp án)';
        submitBtn.innerHTML = '<i class="fas fa-check"></i>';

        const navRow = document.createElement('div');
        navRow.style.display = 'flex';
        navRow.style.alignItems = 'stretch';
        partNavBar.style.flex = '1';

        navRow.appendChild(partNavBar);
        navRow.appendChild(submitBtn);
        navigator.appendChild(navRow);

        const fabNav = document.createElement('div');
        fabNav.className = 'exam-fab-nav';

        const fabPrev = document.createElement('button');
        fabPrev.type = 'button';
        fabPrev.className = 'exam-fab-btn';
        fabPrev.title = 'Câu trước';
        fabPrev.innerHTML = '<i class="fas fa-chevron-left"></i>';

        const fabNext = document.createElement('button');
        fabNext.type = 'button';
        fabNext.className = 'exam-fab-btn';
        fabNext.title = 'Câu tiếp theo';
        fabNext.innerHTML = '<i class="fas fa-chevron-right"></i>';

        fabNav.appendChild(fabPrev);
        fabNav.appendChild(fabNext);

        rootEl.appendChild(header);
        rootEl.appendChild(resultBanner);
        rootEl.appendChild(body);
        rootEl.appendChild(navigator);
        rootEl.appendChild(fabNav);

        this.els = {
            header, logoWrap, headerMain, headerRow1, headerRow2, testTitle,
            skillTabs, audioIndicator, timer, resultBanner,
            body, context, resizer, questions,
            navigator, partNavBar, submitBtn,
            fabPrev, fabNext,
        };

        this.listeningPhase = 'idle';
        this.bindResizer();
    },


    renderTimerValue() {
        const total = Math.max(0, this.timerValue);
        const m = String(Math.floor(total / 60)).padStart(2, '0');
        const s = String(total % 60).padStart(2, '0');
        this.els.timer.textContent = m + ':' + s;
    },

    stopTimerInterval() {
        if (this.timerHandle) {
            window.clearInterval(this.timerHandle);
            this.timerHandle = null;
        }
    },

/**
 * skill/partIndex/testType quyết định phạm vi (scope) đồng hồ. Riêng
 * Listening (mock): ẩn hẳn đồng hồ, chỉ hiện icon "đang phát âm thanh" —
 * đồng hồ chỉ xuất hiện lại ở 2 phút cuối SAU KHI audio phát xong (xem
 * bindListeningAudio()/startListeningWrapUp()).
 */
syncTimerForContext(skill, partIndex, testType) {
    const isMock = testType === 'mock';
    const isSpeaking = skill === 'speaking';
    const isListening = skill === 'listening';

    const scopeKey = (isMock && isSpeaking) ? `speaking-part-${partIndex}` : skill;

    if (scopeKey === this.timerScopeKey) return;

    this.timerScopeKey = scopeKey;
    this.stopTimerInterval();

    if (isListening && isMock) {
        this.listeningPhase = 'idle';
        this.els.timer.classList.add('hidden');
        this.els.timer.classList.remove('is-time-up', 'is-wrapup-warning');
        return;
    }

    this.els.timer.classList.remove('hidden', 'is-wrapup-warning');
    this.els.audioIndicator.classList.add('hidden');

    if (isMock) {
        let durationSeconds = MOCK_SKILL_DURATIONS_SECONDS[skill];
        if (isSpeaking) durationSeconds = MOCK_SPEAKING_PART_DURATION_SECONDS;

        if (durationSeconds) {
            this.timerMode = 'down';
            this.timerValue = durationSeconds;
        } else {
            this.timerMode = 'up';
            this.timerValue = 0;
        }
    } else {
        this.timerMode = 'up';
        this.timerValue = 0;
    }

    this.renderTimerValue();
    this.restartTimerInterval();
},

restartTimerInterval() {
    this.stopTimerInterval();
    this.els.timer.classList.remove('is-time-up');

    this.timerHandle = window.setInterval(() => {
        if (this.timerMode === 'down') {
            if (this.timerValue <= 0) {
                this.stopTimerInterval();
                this.els.timer.classList.add('is-time-up');
                if (typeof this.onTimeUp === 'function') this.onTimeUp();
                return;
            }
            this.timerValue--;
            if (this.timerValue <= 0) {
                this.els.timer.classList.add('is-time-up');
                if (typeof this.onTimeUp === 'function') this.onTimeUp();
            }
        } else {
            this.timerValue++;
        }
        this.renderTimerValue();
    }, 1000);
},

/** Gắn theo dõi audio Listening: icon "đang phát" khi playing/pause, và
 * khi audio phát xong (ended) mới bắt đầu đếm ngược 2 phút cuối. */
bindListeningAudio(audioEl) {
    if (!audioEl || audioEl.dataset.listeningBound === '1') return;
    audioEl.dataset.listeningBound = '1';

    audioEl.addEventListener('play', () => {
        this.els.audioIndicator.classList.remove('hidden');
    });
    audioEl.addEventListener('pause', () => {
        if (!audioEl.ended) this.els.audioIndicator.classList.add('hidden');
    });
    audioEl.addEventListener('ended', () => {
        this.els.audioIndicator.classList.add('hidden');
        const testType = (window.PREVIEW_TEST_META && window.PREVIEW_TEST_META.type) || 'practice';
        if (testType === 'mock') this.startListeningWrapUp();
    });
},

startListeningWrapUp() {
    if (this.listeningPhase === 'wrapup' || this.listeningPhase === 'locked') return;
    this.listeningPhase = 'wrapup';

    this.timerMode = 'down';
    this.timerValue = 120; // 2 phút cuối

    this.els.timer.classList.remove('hidden');
    this.els.timer.classList.add('is-wrapup-warning');
    this.renderWrapUpMessage();

    this.stopTimerInterval();
    this.timerHandle = window.setInterval(() => {
        if (this.timerValue <= 0) {
            this.stopTimerInterval();
            this.listeningPhase = 'locked';
            this.els.timer.textContent = 'Đã hết giờ';
            this.els.timer.classList.remove('is-wrapup-warning');
            this.els.timer.classList.add('is-time-up');
            if (typeof this.onTimeUp === 'function') this.onTimeUp();
            return;
        }
        this.timerValue--;
        this.renderWrapUpMessage();
    }, 1000);
},

renderWrapUpMessage() {
    const minutesLeft = this.timerValue > 60 ? 2 : 1;
    this.els.timer.textContent = minutesLeft === 2 ? '2 minutes remaining' : '1 minute remaining';
},

lockSkill() {
    this.els.questions.classList.add('is-locked');
    if (!this.els.questions.querySelector('.exam-lock-banner')) {
        const banner = document.createElement('div');
        banner.className = 'exam-lock-banner';
        banner.innerHTML = '<i class="fas fa-lock mr-6"></i> Đã hết giờ — không thể thay đổi đáp án';
        this.els.questions.prepend(banner);
    }
},

unlockSkill() {
    this.els.questions.classList.remove('is-locked');
    const banner = this.els.questions.querySelector('.exam-lock-banner');
    if (banner) banner.remove();
},


    renderSkillTabs() {
        this.els.skillTabs.innerHTML = '';

        const skills = PreviewState.skills;
        const currentIdx = skills.indexOf(PreviewState.current.skill);

        skills.forEach((skill, idx) => {
            const tab = document.createElement('div');
            tab.className = 'exam-skill-tab';
            if (idx === currentIdx) tab.classList.add('active');
            else if (idx < currentIdx) tab.classList.add('completed');
            tab.textContent = SKILL_LABELS[skill] || skill;
            this.els.skillTabs.appendChild(tab);
        });
    },


    renderPartNav(onSelectPart, onJumpQuestion) {
        const bar = this.els.partNavBar;
        bar.innerHTML = '';

        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        if (!skillModel) return;

        skillModel.parts.forEach((part, partIndex) => {
            const partEntries = skillModel.entries.filter((e) => e.partIndex === partIndex);
            const isActive = partIndex === PreviewState.current.partIndex;

            const segment = document.createElement('div');
            segment.className = 'exam-part-nav-segment' + (isActive ? ' active' : '');

            const label = document.createElement('div');
            label.className = 'epn-part-label';
            label.textContent = part.title || ('Part ' + (partIndex + 1));
            segment.appendChild(label);

            if (isActive) {
                const numsWrap = document.createElement('div');
                numsWrap.className = 'epn-question-nums';

                partEntries.forEach((entry) => {
                    const slots = (PreviewState.submitted && PreviewState.scoreResult)
                        ? PreviewState.scoreResult.bySlot[entry.question.id]
                        : null;

                    for (let n = entry.startNumber; n <= entry.endNumber; n++) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'epn-question-num';
                        btn.textContent = n;

                        if (PreviewState.submitted) {
                            const idx = n - entry.startNumber;
                            const result = slots ? slots[idx] : null;
                            if (result === true) btn.classList.add('correct');
                            else if (result === false) btn.classList.add('incorrect');
                        } else if (PreviewState.isAnswered(entry.question.id)) {
                            btn.classList.add('answered');
                        }

                        if (entry.question.id === PreviewState.current.questionId) {
                            btn.classList.add('current');
                        }

                        btn.addEventListener('click', () => onJumpQuestion(entry));
                        numsWrap.appendChild(btn);
                    }
                });

                segment.appendChild(numsWrap);
            } else {
                const total = partEntries.reduce((sum, e) => sum + e.slotCount, 0);
                const answered = partEntries.reduce((sum, e) => sum + (PreviewState.isAnswered(e.question.id) ? e.slotCount : 0), 0);

                const summary = document.createElement('div');
                summary.className = 'epn-part-summary';
                summary.textContent = `${answered}/${total} question`;
                segment.appendChild(summary);

                segment.addEventListener('click', () => onSelectPart(partIndex));
            }

            bar.appendChild(segment);
        });
    },

    // renderContext(part, sectionFiles) {
    //     const el = this.els.context;
    //     el.innerHTML = '';

    //     const partFiles = part.files || {};
    //     const sectionAudio = (sectionFiles && sectionFiles.audio) || null;

    //     const files = {
    //         audio: partFiles.audio || sectionAudio,
    //         image: partFiles.image,
    //         video: partFiles.video,
    //     };

    //     if (PreviewState.current.skill === 'listening') {
    //         el.classList.add('hidden');
    //         this.els.resizer.classList.add('hidden');
    //         this.els.questions.classList.add('full-width');
    //         this._pendingListeningContext = { part, files };
    //         return;
    //     }

    //     this._pendingListeningContext = null;


    //     const hasMedia = files.audio || files.image || files.video;
    //     const hasText = part.instructions || part.passage;

    //     if (!hasMedia && !hasText) {
    //         el.classList.add('hidden');
    //         this.els.resizer.classList.add('hidden');
    //         this.els.questions.classList.add('full-width');
    //         return;
    //     }

    //     el.classList.remove('hidden');
    //     this.els.resizer.classList.remove('hidden');
    //     this.els.questions.classList.remove('full-width');

    //     this.appendMediaBlock(el, files);

    //     if (PreviewState.current.skill === 'listening' && files.audio) {
    //         this.bindListeningAudio(el.querySelector('audio'));
    //     }

    //     if (part.instructions) {
    //         const h = document.createElement('h4');
    //         // h.textContent = 'Hướng dẫn';
    //         el.appendChild(h);
    //         const div = document.createElement('div');
    //         div.className = 'exam-context-passage';
    //         div.innerHTML = part.instructions;
    //         el.appendChild(div);
    //     }

    //     if (part.passage) {
    //         const h = document.createElement('h4');
    //         // h.textContent = 'Nội dung';
    //         el.appendChild(h);
    //         const div = document.createElement('div');
    //         div.className = 'exam-context-passage';
    //         div.innerHTML = part.passage;
    //         el.appendChild(div);
    //     }
    // },
  
    renderContext(part, sectionFiles) {
        const el = this.els.context;
        el.innerHTML = '';
        if (this.els.body) this.els.body.classList.remove('is-speaking', 'is-listening');

        const partFiles = part.files || {};
        const sectionAudio = (sectionFiles && sectionFiles.audio) || null;

        const files = {
            audio: partFiles.audio || sectionAudio,
            image: partFiles.image,
            video: partFiles.video,
        };

        // Listening: luôn gom về 1 cột full-width — audio/instructions được
        // chèn vào ĐẦU cột câu hỏi (renderQuestions) thay vì tách cột riêng.
        if (PreviewState.current.skill === 'listening') {
            el.classList.add('hidden');
            this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            if (this.els.body) this.els.body.classList.add('is-listening');
            this._pendingListeningContext = { part, files };
            return;
        }

        this._pendingListeningContext = null;

        // Speaking: màn hình mới đã tự chia 2 cột bên trong .speaking-stage
        // (trái = audio/timer/mic, phải = note/hint/model answer) nên cột
        // context bên ngoài phải tắt đi, giống trang làm bài của học viên.
        if (PreviewState.current.skill === 'speaking') {
            el.classList.add('hidden');
            this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            if (this.els.body) this.els.body.classList.add('is-speaking');
            this._pendingSpeakingContext = { part, files };
            return;
        }

        this._pendingSpeakingContext = null;

        const hasMedia = files.audio || files.image || files.video;
        const hasText = part.instructions || part.passage;

        if (!hasMedia && !hasText) {
            el.classList.add('hidden');
            this.els.resizer.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            return;
        }

        el.classList.remove('hidden');
        this.els.resizer.classList.remove('hidden');
        this.els.questions.classList.remove('full-width');

        this.appendMediaBlock(el, files);

        if (part.instructions) {
            const h = document.createElement('h4');
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.instructions;
            el.appendChild(div);
        }

        if (part.passage) {
            const h = document.createElement('h4');
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.passage;
            el.appendChild(div);
        }
    },
    bindResizer() {
        const MIN_PCT = 20;
        const MAX_PCT = 75;
        let dragging = false;

        const onPointerDown = (e) => {
            if (this.els.context.classList.contains('hidden')) return; // full-width mode -> không kéo được
            dragging = true;
            this.els.resizer.classList.add('is-dragging');
            document.body.classList.add('is-resizing-exam');
            e.preventDefault();
        };

        const onPointerMove = (e) => {
            if (!dragging) return;

            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const bodyRect = this.els.body.getBoundingClientRect();
            let pct = ((clientX - bodyRect.left) / bodyRect.width) * 100;
            pct = Math.min(MAX_PCT, Math.max(MIN_PCT, pct));

            this.els.context.style.width = pct + '%';
        };

        const onPointerUp = () => {
            if (!dragging) return;
            dragging = false;
            this.els.resizer.classList.remove('is-dragging');
            document.body.classList.remove('is-resizing-exam');
        };

        this.els.resizer.addEventListener('mousedown', onPointerDown);
        document.addEventListener('mousemove', onPointerMove);
        document.addEventListener('mouseup', onPointerUp);

        // Hỗ trợ cảm ứng (tablet).
        this.els.resizer.addEventListener('touchstart', onPointerDown, { passive: false });
        document.addEventListener('touchmove', onPointerMove, { passive: false });
        document.addEventListener('touchend', onPointerUp);
    },

    /** Reset về tỷ lệ mặc định 50/50 — gọi khi cần, không bắt buộc dùng. */
    resetSplitRatio() {
        this.els.context.style.width = '50%';
    },

    /**
     * Chèn <audio>/<video>/<img> nếu có, dùng chung cho khung ngữ cảnh (Part)
     * và khối media riêng của từng Group. `files` có dạng { audio, image, video }
     * — giá trị đã được Controller chuyển thành URL thật (resolvePreviewMediaUrls()),
     * không phải raw storage path.
     */
    appendMediaBlock(container, files) {
        files = files || {};

        if (files.audio) {
            const audio = document.createElement('audio');
            audio.controls = true;
            audio.src = files.audio;
            audio.preload = 'metadata';
            container.appendChild(audio);
        }

        if (files.video) {
            const video = document.createElement('video');
            video.controls = true;
            video.src = files.video;
            container.appendChild(video);
        }

        if (files.image) {
            const img = document.createElement('img');
            img.src = files.image;
            img.alt = '';
            container.appendChild(img);
        }
    },

    /**
     * Render toàn bộ câu hỏi của 1 Part (theo từng group), gọi ExamRenderers
     * cho mỗi câu. Nếu bài đã nộp (PreviewState.submitted), tô luôn màu
     * đúng/sai cho các câu vừa render (vì DOM của chúng chỉ tồn tại từ giờ).
     */
    // renderQuestions(part) {
    //     const el = this.els.questions;
    //     el.innerHTML = '';

    //     const groups = Array.isArray(part.groups) ? part.groups : [];

    //     groups.forEach((group) => {
    //         if (group.title || group.passage) {
    //             const groupBox = document.createElement('div');
    //             groupBox.className = 'exam-part-instructions';
    //             groupBox.innerHTML = (group.title ? '<strong>' + group.title + '</strong><br>' : '') + (group.passage || '');
    //             el.appendChild(groupBox);
    //         }

    //         this.appendMediaBlock(el, group.files || {});

    //         (group.questions || []).forEach((question) => {
    //             const entry = PreviewState.allEntries.find((e) => e.question.id === question.id);
    //             if (!entry) return;

    //             const card = ExamRenderers.render(entry);
    //             el.appendChild(card);

    //             if (PreviewState.submitted) {
    //                 ExamRenderers.grade(entry);
    //                 this.appendExplanation(card, entry);
    //             }
    //         });
    //     });
    // },

    renderQuestions(part) {
        const el = this.els.questions;
        el.innerHTML = '';

        // Nếu đang ở Listening (xem renderContext ở trên), chèn audio +
        // instructions vào đầu cột câu hỏi thay vì cột ngữ cảnh riêng.
        if (this._pendingListeningContext) {
            const { files } = this._pendingListeningContext;
            const listeningBlock = document.createElement('div');
            listeningBlock.className = 'exam-listening-block';

            this.appendMediaBlock(listeningBlock, files);
            this.bindListeningAudio(listeningBlock.querySelector('audio'));

            if (part.instructions) {
                const div = document.createElement('div');
                div.className = 'exam-part-instructions';
                div.innerHTML = part.instructions;
                listeningBlock.appendChild(div);
            }

            el.appendChild(listeningBlock);
        }

        if (PreviewState.current.skill === 'speaking') {
            this.renderSpeakingQuestions(part);
            return;
        }

        const groups = Array.isArray(part.groups) ? part.groups : [];

        groups.forEach((group) => {
            if (group.title || group.passage) {
                const groupBox = document.createElement('div');
                groupBox.className = 'exam-part-instructions';
                groupBox.innerHTML = (group.title ? '<strong>' + group.title + '</strong><br>' : '') + (group.passage || '');
                el.appendChild(groupBox);
            }

            this.appendMediaBlock(el, group.files || {});

            (group.questions || []).forEach((question) => {
                const entry = PreviewState.allEntries.find((e) => e.question.id === question.id);
                if (!entry) return;

                const card = ExamRenderers.render(entry);
                el.appendChild(card);

                if (PreviewState.submitted) {
                    ExamRenderers.grade(entry);
                    this.appendExplanation(card, entry);
                }
            });
        });
    },
    /**
     * Speaking: mỗi lần chỉ hiện 1 câu (câu đang làm) — xong câu này mới mở
     * câu sau (yêu cầu Part 1 & Part 3). Các câu đã xong vẫn cho quay lại
     * xem. Thứ tự do IeltsSpeakingFlow quyết định, dùng chung với trang làm
     * bài của học viên.
     */
    renderSpeakingQuestions(part) {
        const el = this.els.questions;
        const flow = window.IeltsSpeakingFlow;

        if (window.IeltsSpeakingStage) window.IeltsSpeakingStage.disposeAll();

        const ctx = this._pendingSpeakingContext;
        const ctxPart = (ctx && ctx.part) || part || {};
        if (ctxPart.instructions || ctxPart.passage) {
            const note = document.createElement('div');
            note.className = 'exam-speaking-part-note';
            note.innerHTML = (ctxPart.instructions || '') + (ctxPart.passage || '');
            el.appendChild(note);
        }
        if (ctx) this.appendMediaBlock(el, ctx.files || {});

        const skillModel = PreviewState.bySkill[PreviewState.current.skill] || { entries: [] };
        const partEntries = (skillModel.entries || []).filter((e) => e.partIndex === PreviewState.current.partIndex);
        if (!partEntries.length) return;

        let index = partEntries.findIndex((e) => e.question.id === PreviewState.current.questionId);
        if (index === -1) index = 0;

        // Không cho nhảy vượt quá câu đang được mở.
        if (flow) {
            const maxIndex = flow.firstOpenIndex(partEntries);
            if (index > maxIndex) index = maxIndex;
            PreviewState.current.questionId = partEntries[index].question.id;
        }

        if (partEntries.length > 1) {
            const progress = document.createElement('div');
            progress.className = 'exam-speaking-progress';
            progress.textContent = 'Question ' + (index + 1) + ' / ' + partEntries.length;
            el.appendChild(progress);
        }

        const entry = partEntries[index];
        const card = ExamRenderers.render(entry);
        el.appendChild(card);

        if (PreviewState.submitted) {
            this.appendExplanation(card, entry);
        }
    },

    appendExplanation(card, entry) {
        const q = entry.question;
        if (!q.explanation) return;

        const box = document.createElement('div');
        box.className = 'exam-explanation-box';
        box.innerHTML = '<strong>Giải thích:</strong> ' + q.explanation;
        card.appendChild(box);
    },

    
    // renderNavigator(onJump) {
    //     const grid = this.els.navGrid;
    //     grid.innerHTML = '';

    //     const skillModel = PreviewState.bySkill[PreviewState.current.skill];
    //     if (!skillModel) return;

    //     skillModel.entries.forEach((entry) => {
    //         // Kết quả chấm theo từng slot (đã tính 1 lần khi bấm Nộp bài,
    //         // xem app.js) — mỗi số Q dùng đúng phần tử tương ứng thay vì
    //         // dùng chung 1 kết quả cho cả dải số như trước.
    //         const slots = (PreviewState.submitted && PreviewState.scoreResult)
    //             ? PreviewState.scoreResult.bySlot[entry.question.id]
    //             : null;

    //         for (let n = entry.startNumber; n <= entry.endNumber; n++) {
    //             const cell = document.createElement('button');
    //             cell.type = 'button';
    //             cell.className = 'exam-nav-cell';
    //             cell.textContent = n;

    //             if (PreviewState.submitted) {
    //                 const idx = n - entry.startNumber;
    //                 const result = slots ? slots[idx] : null;
    //                 if (result === true) cell.classList.add('correct');
    //                 else if (result === false) cell.classList.add('incorrect');
    //             } else if (PreviewState.isAnswered(entry.question.id)) {
    //                 cell.classList.add('answered');
    //             }

    //             if (entry.partIndex === PreviewState.current.partIndex) {
    //                 cell.classList.add('current');
    //             }

    //             cell.addEventListener('click', () => onJump(entry));
    //             grid.appendChild(cell);
    //         }
    //     });
    // },
    showResultBanner(score) {
        const el = this.els.resultBanner;
        el.style.display = 'block';

        const graded = score.correct + score.incorrect;
        const parts = [`Kết quả tự chấm: ${score.correct}/${graded} câu đúng`];
        if (score.ungraded > 0) {
            parts.push(`${score.ungraded} câu tự luận cần Teacher tự đọc & chấm`);
        }

        el.textContent = parts.join(' · ');
    },

    scrollToQuestion(questionId) {
        const card = document.getElementById('exam-q-' + questionId);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            card.style.outline = '2px solid #511D99';
            window.setTimeout(() => { card.style.outline = ''; }, 1200);
        }
    },
};
