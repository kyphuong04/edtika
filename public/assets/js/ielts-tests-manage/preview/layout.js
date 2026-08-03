/**
 * layout.js — dựng khung giao diện tĩnh 1 lần (mount), sau đó chỉ re-render
 * các vùng thay đổi khi người dùng chuyển skill/part hoặc nộp bài.
 */

const ExamLayout = {
    root: null,
    els: {},
    timerSeconds: 0,
    timerHandle: null,

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

        this.startTimer();
    },

    startTimer() {
        this.timerHandle = window.setInterval(() => {
            this.timerSeconds++;
            const m = String(Math.floor(this.timerSeconds / 60)).padStart(2, '0');
            const s = String(this.timerSeconds % 60).padStart(2, '0');
            this.els.timer.textContent = m + ':' + s;
        }, 1000);
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
    renderContext(part) {
        const el = this.els.context;
        el.innerHTML = '';

        const files = part.files || {};
        const hasMedia = files.audio || files.image || files.video;
        const hasText = part.instructions || part.passage;

        if (!hasMedia && !hasText) {
            el.classList.add('hidden');
            this.els.questions.classList.add('full-width');
            return;
        }

        el.classList.remove('hidden');
        this.els.questions.classList.remove('full-width');

        if (files.audio) {
            const audio = document.createElement('audio');
            audio.controls = true;
            audio.src = files.audio;
            el.appendChild(audio);
        }

        if (files.video) {
            const video = document.createElement('video');
            video.controls = true;
            video.src = files.video;
            el.appendChild(video);
        }

        if (files.image) {
            const img = document.createElement('img');
            img.src = files.image;
            el.appendChild(img);
        }

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
            for (let n = entry.startNumber; n <= entry.endNumber; n++) {
                const cell = document.createElement('button');
                cell.type = 'button';
                cell.className = 'exam-nav-cell';
                cell.textContent = n;

                if (PreviewState.submitted) {
                    const result = ExamGrading.isCorrect(entry);
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
