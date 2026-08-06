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

        const skillTabs = document.createElement('div');
        skillTabs.className = 'exam-skill-tabs';

        const timer = document.createElement('div');
        timer.className = 'exam-timer';
        timer.textContent = '00:00';

        header.appendChild(skillTabs);
        header.appendChild(timer);

        const resultBanner = document.createElement('div');
        resultBanner.className = 'exam-result-banner';
        resultBanner.style.display = 'none';

        const partTabs = document.createElement('div');
        partTabs.className = 'exam-part-tabs';

        const body = document.createElement('div');
        body.className = 'exam-body';

        const context = document.createElement('div');
        context.className = 'exam-context';

        const questions = document.createElement('div');
        questions.className = 'exam-questions';

        body.appendChild(context);
        body.appendChild(questions);

        const navigator = document.createElement('div');
        navigator.className = 'exam-navigator';

        const navGrid = document.createElement('div');
        navGrid.className = 'exam-nav-grid';

        const submitBtn = document.createElement('button');
        submitBtn.type = 'button';
        submitBtn.className = 'exam-submit-btn';
        submitBtn.textContent = 'Nộp bài (xem đáp án)';

        navigator.appendChild(navGrid);
        navigator.appendChild(submitBtn);

        rootEl.appendChild(header);
        rootEl.appendChild(resultBanner);
        rootEl.appendChild(partTabs);
        rootEl.appendChild(body);
        rootEl.appendChild(navigator);

        this.els = { header, skillTabs, timer, resultBanner, partTabs, body, context, questions, navigator, navGrid, submitBtn };

        // this.startTimer();
    },

    syncTimerForContext(skill, partIndex, testType) {
        const isMock = testType === 'mock';
        const isSpeaking = skill === 'speaking';

        const scopeKey = (isMock && isSpeaking)
            ? `speaking-part-${partIndex}`
            : skill;

        if (scopeKey === this.timerScopeKey) {
            return; // cùng phạm vi — giữ nguyên đồng hồ đang chạy
        }

        this.timerScopeKey = scopeKey;

        if (isMock) {
            let durationSeconds = MOCK_SKILL_DURATIONS_SECONDS[skill];
            if (isSpeaking) {
                durationSeconds = MOCK_SPEAKING_PART_DURATION_SECONDS;
            }

            if (durationSeconds) {
                this.timerMode = 'down';
                this.timerValue = durationSeconds;
            } else {
                // Skill không có thời lượng quy định (VD Grammar/Vocabulary)
                // -> đếm lên như Practice để không vỡ đồng hồ.
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
        if (this.timerHandle) {
            window.clearInterval(this.timerHandle);
            this.timerHandle = null;
        }

        this.els.timer.classList.remove('is-time-up');

        this.timerHandle = window.setInterval(() => {
            if (this.timerMode === 'down') {
                if (this.timerValue <= 0) {
                    window.clearInterval(this.timerHandle);
                    this.timerHandle = null;
                    return;
                }
                this.timerValue--;
                if (this.timerValue <= 0) {
                    this.els.timer.classList.add('is-time-up');
                }
            } else {
                this.timerValue++;
            }
            this.renderTimerValue();
        }, 1000);
    },

    renderTimerValue() {
        const total = Math.max(0, this.timerValue);
        const m = String(Math.floor(total / 60)).padStart(2, '0');
        const s = String(total % 60).padStart(2, '0');
        this.els.timer.textContent = m + ':' + s;
    },

    renderSkillTabs(onSelect) {
        this.els.skillTabs.innerHTML = '';

        PreviewState.skills.forEach((skill) => {
            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'exam-skill-tab' + (skill === PreviewState.current.skill ? ' active' : '');
            tab.textContent = SKILL_LABELS[skill] || skill;
            tab.addEventListener('click', () => onSelect(skill));
            this.els.skillTabs.appendChild(tab);
        });
    },

    renderPartTabs(onSelect) {
        this.els.partTabs.innerHTML = '';

        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        if (!skillModel) return;

        skillModel.parts.forEach((part, idx) => {
            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'exam-part-tab' + (idx === PreviewState.current.partIndex ? ' active' : '');
            tab.textContent = part.title || ('Part ' + (idx + 1));
            tab.addEventListener('click', () => onSelect(idx));
            this.els.partTabs.appendChild(tab);
        });
    },

    /**
     * Khung ngữ cảnh bên trái: audio/video/image + instructions/passage của Part.
     * Với Writing/Speaking (thường không cần 2 cột), tự ẩn khung này và cho
     * câu hỏi chiếm full width.
     */
    // renderContext(part) {
    //     const el = this.els.context;
    //     el.innerHTML = '';

    //     const files = part.files || {};
    //     const hasMedia = files.audio || files.image || files.video;
    //     const hasText = part.instructions || part.passage;
        renderContext(part, sectionFiles) {
        const el = this.els.context;
        el.innerHTML = '';

        const partFiles = part.files || {};
        const sectionAudio = (sectionFiles && sectionFiles.audio) || null;

        // Audio riêng của Part được ưu tiên nếu có; nếu Part không có audio
        // riêng thì dùng audio chung của cả Section (trường hợp Listening
        // dùng 1 file audio cho toàn bộ 4 part).
        const files = {
            audio: partFiles.audio || sectionAudio,
            image: partFiles.image,
            video: partFiles.video,
        };

        const hasMedia = files.audio || files.image || files.video;
        const hasText = part.instructions || part.passage;

        if (!hasMedia && !hasText) {
            el.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            return;
        }

        el.classList.remove('hidden');
        this.els.questions.classList.remove('full-width');

        this.appendMediaBlock(el, files);

        if (part.instructions) {
            const h = document.createElement('h4');
            h.textContent = 'Hướng dẫn';
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.instructions;
            el.appendChild(div);
        }

        if (part.passage) {
            const h = document.createElement('h4');
            h.textContent = 'Nội dung';
            el.appendChild(h);
            const div = document.createElement('div');
            div.className = 'exam-context-passage';
            div.innerHTML = part.passage;
            el.appendChild(div);
        }
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
    renderQuestions(part) {
        const el = this.els.questions;
        el.innerHTML = '';

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

    appendExplanation(card, entry) {
        const q = entry.question;
        if (!q.explanation) return;

        const box = document.createElement('div');
        box.className = 'exam-explanation-box';
        box.innerHTML = '<strong>Giải thích:</strong> ' + q.explanation;
        card.appendChild(box);
    },

    
    renderNavigator(onJump) {
        const grid = this.els.navGrid;
        grid.innerHTML = '';

        const skillModel = PreviewState.bySkill[PreviewState.current.skill];
        if (!skillModel) return;

        skillModel.entries.forEach((entry) => {
            // Kết quả chấm theo từng slot (đã tính 1 lần khi bấm Nộp bài,
            // xem app.js) — mỗi số Q dùng đúng phần tử tương ứng thay vì
            // dùng chung 1 kết quả cho cả dải số như trước.
            const slots = (PreviewState.submitted && PreviewState.scoreResult)
                ? PreviewState.scoreResult.bySlot[entry.question.id]
                : null;

            for (let n = entry.startNumber; n <= entry.endNumber; n++) {
                const cell = document.createElement('button');
                cell.type = 'button';
                cell.className = 'exam-nav-cell';
                cell.textContent = n;

                if (PreviewState.submitted) {
                    const idx = n - entry.startNumber;
                    const result = slots ? slots[idx] : null;
                    if (result === true) cell.classList.add('correct');
                    else if (result === false) cell.classList.add('incorrect');
                } else if (PreviewState.isAnswered(entry.question.id)) {
                    cell.classList.add('answered');
                }

                if (entry.partIndex === PreviewState.current.partIndex) {
                    cell.classList.add('current');
                }

                cell.addEventListener('click', () => onJump(entry));
                grid.appendChild(cell);
            }
        });
    },
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
