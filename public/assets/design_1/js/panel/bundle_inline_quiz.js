/**
 * GĐ7 - Làm quiz ngay trên trang học (theo design):
 * - Mini Quiz: video dừng, câu hỏi hiện tại tab Quizzes (trang lesson) hoặc hộp thoại (trang học module cũ)
 * - Mid-term / Final: câu hỏi hiện trong khung player của trang lesson, có đếm giờ
 *
 * window.EdkInlineQuiz.mount(container, {
 *     url,               // GET: câu hỏi; POST: nộp bài (/panel/courses/bundle-learning/quizzes/{id})
 *     mode,              // 'mini' | 'checkpoint'
 *     showHeader,        // hiện tag + tên quiz (mặc định true với mini)
 *     extraButtons,      // [{label, className, onClick}] thêm vào cuối hàng nút
 *     onResult(json)     // sau mỗi lần nộp
 * })
 * Ngôn ngữ: window.edkInlineQuizLang, CSRF: meta[name=csrf-token] hoặc window.bundleLearningPlayer.csrfToken
 */
(function () {
    'use strict';

    function t(key, fallback) {
        var lang = window.edkInlineQuizLang || {};
        return lang[key] || fallback;
    }

    function format(text, params) {
        return String(text).replace(/:(\w+)/g, function (match, name) {
            return Object.prototype.hasOwnProperty.call(params, name) ? params[name] : match;
        });
    }

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.content) {
            return meta.content;
        }
        return (window.bundleLearningPlayer && window.bundleLearningPlayer.csrfToken) || '';
    }

    function escapeHtml(value) {
        var div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function request(method, url, body) {
        var headers = {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken()};

        return fetch(url, {method: method, body: body, credentials: 'same-origin', headers: headers}).then(function (response) {
            return response.json().catch(function () { return {}; }).then(function (json) {
                return {ok: response.ok, json: json};
            });
        });
    }

    function pad(number) {
        return (number < 10 ? '0' : '') + number;
    }

    /* ---------- Câu đã trả lời ---------- */

    function questionAnswered(section) {
        var fields = section.querySelectorAll('input, select, textarea');
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            if (field.type === 'radio' || field.type === 'checkbox') {
                if (field.checked) {
                    return true;
                }
            } else if (String(field.value || '').trim() !== '') {
                return true;
            }
        }
        return false;
    }

    function mount(container, options) {
        options = options || {};
        var mode = options.mode === 'checkpoint' ? 'checkpoint' : 'mini';
        var showHeader = options.showHeader !== undefined ? !!options.showHeader : mode === 'mini';
        var state = {data: null, timer: null, deadline: 0, submitting: false, confirmMissing: false, finished: false};

        container.classList.add('edk-iq');
        container.innerHTML = '<div class="edk-iq-loading">' + escapeHtml(t('loading', 'Đang tải câu hỏi...')) + '</div>';

        function stopTimer() {
            if (state.timer) {
                clearInterval(state.timer);
                state.timer = null;
            }
            window.removeEventListener('beforeunload', beforeUnload);
        }

        function beforeUnload(event) {
            if (!state.finished) {
                event.preventDefault();
                event.returnValue = '';
            }
        }

        function feedback(type, html) {
            var box = container.querySelector('.js-iq-feedback');
            if (!box) {
                return;
            }
            box.className = 'edk-iq-feedback js-iq-feedback ' + type;
            box.innerHTML = html;
            box.hidden = !html;
        }

        function lock(locked) {
            container.classList.toggle('is-locked', locked);
            container.querySelectorAll('.edk-iq-questions input, .edk-iq-questions select, .edk-iq-questions textarea').forEach(function (field) {
                field.disabled = locked;
            });
        }

        function clearMarks() {
            container.querySelectorAll('.js-iq-question').forEach(function (section) {
                section.classList.remove('is-correct', 'is-wrong', 'is-missing');
                var mark = section.querySelector('.js-iq-mark');
                if (mark) {
                    mark.hidden = true;
                    mark.className = 'edk-iq-mark js-iq-mark';
                }
            });
        }

        function showMarks(questions) {
            container.querySelectorAll('.js-iq-question').forEach(function (section) {
                var info = questions[section.getAttribute('data-question-id')];
                var mark = section.querySelector('.js-iq-mark');
                if (!info || !mark) {
                    return;
                }
                mark.hidden = false;
                if (info.manual) {
                    mark.classList.add('wait');
                    mark.textContent = '⏳ ' + t('markWaiting', 'Chờ giáo viên chấm');
                } else if (info.correct) {
                    section.classList.add('is-correct');
                    mark.classList.add('good');
                    mark.textContent = '✓ ' + t('markCorrect', 'Chính xác');
                } else {
                    section.classList.add('is-wrong');
                    mark.classList.add('bad');
                    mark.textContent = '✕ ' + (info.answered ? t('markWrong', 'Chưa đúng') : t('markEmpty', 'Chưa trả lời'));
                }
            });
        }

        function setSubmitVisible(visible) {
            var button = container.querySelector('.js-iq-submit');
            if (button) {
                button.hidden = !visible;
                button.disabled = false;
            }
        }

        function startTimer(minutes) {
            var badge = container.querySelector('.js-iq-timer');
            if (!badge || !(minutes > 0)) {
                return;
            }
            state.deadline = Date.now() + minutes * 60000;

            function render() {
                var left = Math.max(0, Math.round((state.deadline - Date.now()) / 1000));
                badge.textContent = '⏱ ' + pad(Math.floor(left / 60)) + ':' + pad(left % 60);
                badge.classList.toggle('is-low', left <= 60);
                if (left <= 0) {
                    stopTimer();
                    feedback('wait', escapeHtml(t('timeUp', 'Hết giờ - bài làm được nộp tự động.')));
                    submit(true);
                }
            }

            render();
            state.timer = setInterval(render, 1000);
        }

        function submit(force) {
            if (state.submitting || state.finished) {
                return;
            }

            var form = container.querySelector('form');
            var sections = container.querySelectorAll('.js-iq-question');
            var missing = [];
            sections.forEach(function (section) {
                section.classList.remove('is-missing');
                if (!questionAnswered(section)) {
                    missing.push(section);
                }
            });

            if (!force && missing.length) {
                missing.forEach(function (section) { section.classList.add('is-missing'); });

                // Mini Quiz: phải trả lời đủ. Mid-term / Final: cảnh báo, nhấn lần nữa để vẫn nộp
                if (mode === 'mini' || !state.confirmMissing) {
                    state.confirmMissing = mode !== 'mini';
                    feedback('bad', escapeHtml(mode === 'mini'
                        ? t('answerAll', 'Vui lòng trả lời đầy đủ các câu hỏi.')
                        : format(t('missingConfirm', 'Còn :count câu chưa trả lời. Nhấn "Nộp bài" lần nữa để vẫn nộp.'), {count: missing.length})));
                    missing[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
                    return;
                }
            }

            state.submitting = true;
            state.confirmMissing = false;
            var button = container.querySelector('.js-iq-submit');
            if (button) {
                button.disabled = true;
            }

            var body = new FormData(form);
            body.append('_token', csrfToken());

            request('POST', options.url, body).then(function (result) {
                state.submitting = false;
                if (button) {
                    button.disabled = false;
                }

                if (!result.ok) {
                    feedback('bad', escapeHtml(result.json.message || t('failed', 'Không gửi được bài làm. Vui lòng thử lại.')));
                    return;
                }

                handleResult(result.json);
            }).catch(function () {
                state.submitting = false;
                if (button) {
                    button.disabled = false;
                }
                feedback('bad', escapeHtml(t('failed', 'Không gửi được bài làm. Vui lòng thử lại.')));
            });
        }

        function handleResult(json) {
            clearMarks();

            // Mini Quiz: đánh dấu đúng/sai từng câu (không lộ đáp án). Mid-term / Final: chỉ báo điểm
            if (mode === 'mini') {
                showMarks(json.questions || {});
            }

            var score = format(t('score', 'Điểm: :grade/:total · cần :pass để đạt'), {grade: json.grade, total: json.total_grade, pass: json.pass_mark});
            var detailLink = (mode === 'checkpoint' && json.result_url)
                ? ' · <a href="' + escapeHtml(json.result_url) + '" target="_blank">' + escapeHtml(t('viewDetail', 'Xem chi tiết')) + ' ↗</a>'
                : '';
            var type = json.status === 'passed' ? 'good' : (json.status === 'waiting' ? 'wait' : 'bad');
            var icon = json.status === 'passed' ? '✓ ' : (json.status === 'waiting' ? '⏳ ' : '✕ ');
            var attempts = (json.status === 'failed' && json.attempts_left !== null && json.attempts_left !== undefined)
                ? ' · ' + escapeHtml(format(t('attemptsLeft', 'Còn :count lượt làm'), {count: json.attempts_left}))
                : '';

            feedback(type, '<b>' + icon + escapeHtml(json.message) + '</b><br>' + escapeHtml(score) + attempts + detailLink);

            if (json.status === 'failed' && json.can_retry) {
                // Giữ đáp án để học viên sửa câu sai rồi nộp lại
                setSubmitVisible(true);
                var button = container.querySelector('.js-iq-submit');
                if (button) {
                    button.textContent = t('retry', 'Nộp lại');
                }
                // Mỗi lượt làm lại có thời gian mới
                if (mode === 'checkpoint' && state.data && state.data.time > 0) {
                    stopTimer();
                    startTimer(state.data.time);
                    window.addEventListener('beforeunload', beforeUnload);
                }
            } else {
                state.finished = true;
                stopTimer();
                lock(true);
                setSubmitVisible(false);
            }

            if (typeof options.onResult === 'function') {
                options.onResult(json);
            }
        }

        function render(data) {
            state.data = data;
            var meta = [];
            meta.push('<span class="edk-iq-tag outline">' + escapeHtml(format(t('questions', ':count câu hỏi'), {count: data.questions_count})) + '</span>');
            meta.push('<span class="edk-iq-tag outline">' + escapeHtml(format(t('passMark', 'Đạt: :pass/:total điểm'), {pass: data.pass_mark, total: data.total_grade})) + '</span>');
            if (data.attempts_left !== null && data.attempts_left !== undefined) {
                meta.push('<span class="edk-iq-tag outline">' + escapeHtml(format(t('attemptsLeft', 'Còn :count lượt làm'), {count: data.attempts_left})) + '</span>');
            }
            if (mode === 'checkpoint' && data.time > 0) {
                meta.push('<span class="edk-iq-tag outline edk-iq-timer js-iq-timer"></span>');
            }

            container.innerHTML =
                (showHeader ? '<span class="edk-iq-tag">Mini Quiz</span><h3 class="edk-iq-title">' + escapeHtml(data.title) + '</h3>' : '') +
                '<div class="edk-iq-meta">' + meta.join('') + '</div>' +
                '<form novalidate>' + data.html + '</form>' +
                '<div class="edk-iq-feedback js-iq-feedback" hidden></div>' +
                '<div class="edk-iq-footer js-iq-footer">' +
                '<button type="button" class="edk-iq-btn primary js-iq-submit">' + escapeHtml(mode === 'mini' ? t('submitMini', 'Nộp Quiz') : t('submitTest', 'Nộp bài kiểm tra')) + '</button>' +
                '</div>';

            var footer = container.querySelector('.js-iq-footer');
            (options.extraButtons || []).forEach(function (extra) {
                var button = document.createElement(extra.href ? 'a' : 'button');
                if (extra.href) {
                    button.href = extra.href;
                } else {
                    button.type = 'button';
                }
                button.className = 'edk-iq-btn ' + (extra.className || '');
                button.textContent = extra.label;
                if (typeof extra.onClick === 'function') {
                    button.addEventListener('click', extra.onClick);
                }
                footer.appendChild(button);
            });

            container.querySelector('.js-iq-submit').addEventListener('click', function () { submit(false); });
            container.querySelector('form').addEventListener('submit', function (event) {
                event.preventDefault();
                submit(false);
            });
            // Enter trong ô tự luận ngắn không tự nộp bài
            container.querySelectorAll('input[type="text"]').forEach(function (input) {
                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });

            if (!data.can_submit) {
                state.finished = true;
                lock(true);
                setSubmitVisible(false);
                feedback(data.passed ? 'good' : 'bad', escapeHtml(data.passed ? t('alreadyPassed', 'Bạn đã hoàn thành quiz này.') : t('noAttempts', 'Bạn đã dùng hết số lượt làm quiz này.')));
                return;
            }

            if (mode === 'checkpoint') {
                startTimer(data.time);
                window.addEventListener('beforeunload', beforeUnload);
            }
        }

        request('GET', options.url).then(function (result) {
            if (!result.ok) {
                container.innerHTML = '<div class="edk-iq-feedback bad">' + escapeHtml(result.json.message || t('failed', 'Không tải được câu hỏi.')) + '</div>';
                return;
            }
            render(result.json);
            if (typeof options.onReady === 'function') {
                options.onReady(result.json);
            }
        }).catch(function () {
            container.innerHTML = '<div class="edk-iq-feedback bad">' + escapeHtml(t('failed', 'Không tải được câu hỏi.')) + '</div>';
        });

        return {
            destroy: function () {
                state.finished = true;
                stopTimer();
            }
        };
    }

    window.EdkInlineQuiz = {mount: mount};
})();
