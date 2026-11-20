/**
 * GĐ3 - Ghi nhận xem video và Mini Quiz trên learning page.
 *
 * - Gửi vị trí xem lên server mỗi 15 giây và khi video kết thúc.
 *   Vị trí chỉ tăng khi video phát bình thường (tua nhanh không được tính).
 * - Tự tiếp tục từ vị trí xem gần nhất.
 * - Mini Quiz: tới giây show_at_second thì dừng video và hiện câu hỏi ngay trên trang (GĐ7):
 *   trang lesson bắt sự kiện "edk:mini-quiz" để hiện ở tab Quizzes; trang khác hiện hộp thoại.
 *   Quiz "blocking" (chưa đạt): không xem tiếp / tua qua mốc quiz cho tới khi làm đạt.
 *
 * Cấu hình: window.bundleLearningPlayer = { stateUrl, progressUrl, csrfToken, lang: {...} }
 */
(function () {
    'use strict';

    var config = window.bundleLearningPlayer;
    if (!config) {
        return;
    }

    var SEND_INTERVAL_MS = 15000;
    var MAX_NORMAL_STEP_SECONDS = 3; // 1 giây thực tế ở tốc độ tối đa 2x + độ trễ
    var trackers = {};

    function lang(key, fallback) {
        return (config.lang && config.lang[key]) || fallback;
    }

    function request(method, url, data) {
        var options = {
            method: method,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': config.csrfToken
            }
        };

        if (data) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }

        return fetch(url, options).then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        });
    }

    /* ---------- Truy cập player (video thường hoặc Plyr cho YouTube/Vimeo) ---------- */

    function getMedia(element) {
        if (element.tagName === 'VIDEO') {
            return {
                time: function () { return element.currentTime || 0; },
                duration: function () { return isFinite(element.duration) ? element.duration : 0; },
                playing: function () { return !element.paused && !element.ended; },
                pause: function () { element.pause(); },
                play: function () { var p = element.play(); if (p && p.catch) { p.catch(function () {}); } },
                seek: function (t) { element.currentTime = t; }
            };
        }

        var plyr = element.plyr || (element.closest('.plyr') && element.closest('.plyr').plyr);
        if (!plyr) {
            return null;
        }

        return {
            time: function () { return plyr.currentTime || 0; },
            duration: function () { return isFinite(plyr.duration) ? plyr.duration : 0; },
            playing: function () { return !!plyr.playing; },
            pause: function () { plyr.pause(); },
            play: function () { plyr.play(); },
            seek: function (t) { plyr.currentTime = t; }
        };
    }

    /* ---------- Mini Quiz (GĐ7: làm ngay trên trang) ---------- */

    function findQuiz(quizId) {
        var found = null;
        Object.keys(trackers).forEach(function (fileId) {
            trackers[fileId].quizzes.forEach(function (quiz) {
                if (String(quiz.id) === String(quizId)) {
                    found = {tracker: trackers[fileId], quiz: quiz};
                }
            });
        });
        return found;
    }

    function markQuizPassed(quizId, status) {
        var found = findQuiz(quizId);
        if (!found) {
            return;
        }
        if (status === 'waiting') {
            found.quiz.waiting = true;
        } else {
            found.quiz.passed = true;
        }
        found.quiz.blocking = false;
    }

    function resume(tracker) {
        closeModal();
        tracker.activeQuizId = null;
        var media = getMedia(tracker.element);
        if (media) {
            media.play();
        }
    }

    function openMiniQuiz(tracker, quiz) {
        tracker.activeQuizId = quiz.id;

        var detail = {
            quiz: quiz,
            fileId: tracker.fileId,
            markPassed: function (status) { markQuizPassed(quiz.id, status); },
            resume: function () { resume(tracker); },
            close: function () { tracker.activeQuizId = null; }
        };

        // Trang lesson mới: hiện tại tab Quizzes (không bật đè lên video)
        var event = new CustomEvent('edk:mini-quiz', {detail: detail, cancelable: true});
        if (!document.dispatchEvent(event)) {
            return;
        }

        showMiniQuizModal(tracker, quiz, detail);
    }

    function showMiniQuizModal(tracker, quiz, detail) {
        closeModal();

        var overlay = document.createElement('div');
        overlay.className = 'bl-mini-quiz-overlay';
        overlay.innerHTML = '<div class="bl-mini-quiz-modal" role="dialog" aria-modal="true">' +
            '<button type="button" class="edk-iq-close" aria-label="' + lang('close', 'Đóng') + '">×</button>' +
            '<div class="js-bl-mini-quiz-body"></div></div>';
        document.body.appendChild(overlay);

        function close() {
            closeModal();
            tracker.activeQuizId = null;
            if (!quiz.blocking) {
                // Không bắt buộc: "Để sau" thì xem tiếp
                var media = getMedia(tracker.element);
                if (media) {
                    media.play();
                }
            }
        }

        overlay.querySelector('.edk-iq-close').addEventListener('click', close);

        var body = overlay.querySelector('.js-bl-mini-quiz-body');

        if (!quiz.inline_url || !window.EdkInlineQuiz) {
            // Dự phòng: mở trang quiz của module
            body.innerHTML = '<div class="edk-iq"><span class="edk-iq-tag">Mini Quiz</span><h3 class="edk-iq-title"></h3>' +
                '<p>' + lang('miniQuizText', 'Làm nhanh Mini Quiz để kiểm tra phần vừa học.') + '</p>' +
                '<div class="edk-iq-footer"><a class="edk-iq-btn primary">' + lang('startQuiz', 'Làm Mini Quiz') + ' →</a></div></div>';
            body.querySelector('h3').textContent = quiz.title;
            body.querySelector('a').setAttribute('href', quiz.url);
            body.querySelector('a').addEventListener('click', function () { sendProgress(tracker, true); });
            return;
        }

        var extraButtons = quiz.blocking ? [] : [{label: lang('later', 'Để sau'), onClick: close}];

        window.EdkInlineQuiz.mount(body, {
            url: quiz.inline_url,
            mode: 'mini',
            extraButtons: extraButtons,
            onResult: function (json) {
                if (json.status !== 'passed' && json.status !== 'waiting') {
                    return;
                }
                detail.markPassed(json.status);
                var footer = body.querySelector('.js-iq-footer');
                if (footer) {
                    footer.innerHTML = '';
                    var next = document.createElement('button');
                    next.type = 'button';
                    next.className = 'edk-iq-btn primary';
                    next.textContent = lang('continueVideo', 'Tiếp tục video') + ' →';
                    next.addEventListener('click', function () { resume(tracker); });
                    footer.appendChild(next);
                }
            }
        });
    }

    function closeModal() {
        var existing = document.querySelector('.bl-mini-quiz-overlay');
        if (existing) {
            existing.remove();
        }
    }

    /* ---------- Theo dõi từng video ---------- */

    function sendProgress(tracker, force) {
        if (tracker.completed && !force) {
            return;
        }

        var media = getMedia(tracker.element);
        var duration = media ? media.duration() : 0;
        var now = Date.now();

        if (!force && now - tracker.lastSentAt < SEND_INTERVAL_MS) {
            return;
        }

        if (tracker.watchedMax <= tracker.lastSentPosition && !force) {
            return;
        }

        tracker.lastSentAt = now;
        tracker.lastSentPosition = tracker.watchedMax;

        request('POST', config.progressUrl, {
            file_id: tracker.fileId,
            position: Math.floor(tracker.watchedMax),
            duration: duration > 0 ? Math.round(duration) : null
        }).then(function (result) {
            if (result && result.completed && !tracker.completed) {
                tracker.completed = true;
                if (typeof window.showToast === 'function') {
                    window.showToast('success', '', lang('videoCompleted', 'Đã ghi nhận bạn xem xong video này.'));
                }
            }
        }).catch(function () {
            // Lỗi mạng: lần gửi sau sẽ gửi lại vị trí mới nhất
            tracker.lastSentPosition = 0;
        });
    }

    function tick(tracker) {
        var media = getMedia(tracker.element);
        if (!media) {
            return;
        }

        var duration = media.duration();
        var time = media.time();

        // Tiếp tục từ vị trí xem gần nhất (một lần, khi player đã biết thời lượng)
        if (!tracker.resumed && duration > 0) {
            tracker.resumed = true;
            if (tracker.resumeAt > 5 && tracker.resumeAt < duration - 5) {
                media.seek(tracker.resumeAt);
                tracker.lastTime = tracker.resumeAt;
                return;
            }
        }

        if (media.playing()) {
            var step = time - tracker.lastTime;
            // Chỉ cộng khi phát bình thường; tua nhanh thì không tính phần bị bỏ qua
            if (step > 0 && step <= MAX_NORMAL_STEP_SECONDS && time > tracker.watchedMax) {
                tracker.watchedMax = time;
            }

            // Mini Quiz tới mốc thời gian
            for (var i = 0; i < tracker.quizzes.length; i++) {
                var quiz = tracker.quizzes[i];
                if (quiz.passed || time < quiz.show_at_second) {
                    continue;
                }

                if (quiz.blocking) {
                    // Chưa làm đạt: giữ video ở mốc quiz (kể cả khi tua qua)
                    media.pause();
                    if (time > quiz.show_at_second + 1) {
                        media.seek(quiz.show_at_second);
                        time = quiz.show_at_second;
                    }
                    if (tracker.activeQuizId !== quiz.id) {
                        tracker.shownQuizzes[quiz.id] = true;
                        openMiniQuiz(tracker, quiz);
                    } else if (typeof window.showToast === 'function') {
                        window.showToast('error', '', lang('quizRequired', 'Hoàn thành Mini Quiz để xem tiếp video.'));
                    }
                    break;
                }

                if (!tracker.shownQuizzes[quiz.id] && time < quiz.show_at_second + 3) {
                    tracker.shownQuizzes[quiz.id] = true;
                    media.pause();
                    openMiniQuiz(tracker, quiz);
                    break;
                }
            }

            sendProgress(tracker, false);
        }

        tracker.lastTime = time;
    }

    function startTracker(element, fileId) {
        var tracker = {
            fileId: fileId,
            element: element,
            quizzes: [],
            shownQuizzes: {},
            resumeAt: 0,
            resumed: false,
            completed: false,
            watchedMax: 0,
            lastTime: 0,
            lastSentAt: 0,
            lastSentPosition: 0,
            activeQuizId: null
        };
        trackers[fileId] = tracker;

        request('GET', config.stateUrl.replace('__ID__', fileId)).then(function (state) {
            tracker.quizzes = state.mini_quizzes || [];
            tracker.completed = !!state.completed;
            tracker.resumeAt = state.completed ? 0 : (state.last_position || 0);
            // Không tự tiếp tục vượt qua Mini Quiz chưa làm
            tracker.quizzes.forEach(function (quiz) {
                if (quiz.blocking && tracker.resumeAt > quiz.show_at_second) {
                    tracker.resumeAt = Math.max(0, quiz.show_at_second - 1);
                }
            });
            tracker.watchedMax = state.last_position || 0;
            tracker.lastSentPosition = tracker.watchedMax;
        }).catch(function () {
            // Không có quyền (module/lesson đang khóa) hoặc lỗi mạng: không theo dõi video này
            delete trackers[fileId];
            tracker.disabled = true;
        });

        if (element.tagName === 'VIDEO') {
            element.addEventListener('ended', function () {
                // Không nâng watchedMax lên hết video: nếu tua tới cuối thì phần bỏ qua không được tính
                sendProgress(tracker, true);
            });
            element.addEventListener('pause', function () {
                sendProgress(tracker, true);
            });
        }

        tracker.timer = setInterval(function () {
            if (tracker.disabled) {
                clearInterval(tracker.timer);
                return;
            }

            // Video bị thay khi chuyển sang item khác (nội dung nạp bằng ajax)
            if (!document.body.contains(element)) {
                clearInterval(tracker.timer);
                sendProgress(tracker, true);
                delete trackers[fileId];
                return;
            }

            tick(tracker);
        }, 1000);
    }

    function scan() {
        document.querySelectorAll('[id^="fileVideo"]').forEach(function (element) {
            var fileId = parseInt(element.id.replace('fileVideo', ''), 10);
            if (!fileId || trackers[fileId]) {
                return;
            }

            // Đợi Plyr khởi tạo xong với video nhúng
            if (element.tagName !== 'VIDEO' && !getMedia(element)) {
                return;
            }

            startTracker(element, fileId);
        });
    }

    // Trang có tab Quizzes gọi khi học viên làm đạt Mini Quiz ngoài lúc video dừng
    window.bundleLearningPlayerApi = {
        markQuizPassed: markQuizPassed
    };

    setInterval(scan, 1500);
    scan();

    window.addEventListener('beforeunload', function () {
        Object.keys(trackers).forEach(function (fileId) {
            var tracker = trackers[fileId];
            if (tracker.completed || tracker.watchedMax <= tracker.lastSentPosition) {
                return;
            }
            // Gửi lần cuối khi rời trang
            var payload = new FormData();
            payload.append('_token', config.csrfToken);
            payload.append('file_id', tracker.fileId);
            payload.append('position', Math.floor(tracker.watchedMax));
            if (navigator.sendBeacon) {
                navigator.sendBeacon(config.progressUrl, payload);
            }
        });
    });
})();
