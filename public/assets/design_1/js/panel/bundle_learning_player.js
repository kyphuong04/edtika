/**
 * GĐ3 - Ghi nhận xem video và Mini Quiz trên learning page.
 *
 * - Gửi vị trí xem lên server mỗi 15 giây và khi video kết thúc.
 *   Vị trí chỉ tăng khi video phát bình thường (tua nhanh không được tính).
 * - Tự tiếp tục từ vị trí xem gần nhất.
 * - Mini Quiz: tới giây show_at_second thì dừng video và mời học viên làm quiz.
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

    /* ---------- Mini Quiz modal ---------- */

    function showMiniQuizModal(tracker, quiz) {
        closeModal();

        var overlay = document.createElement('div');
        overlay.className = 'bl-mini-quiz-overlay';
        overlay.innerHTML =
            '<div class="bl-mini-quiz-modal" role="dialog" aria-modal="true">' +
            '<div class="bl-mini-quiz-eyebrow">Mini Quiz</div>' +
            '<h3></h3>' +
            '<p>' + lang('miniQuizText', 'Làm nhanh Mini Quiz để kiểm tra phần vừa học.') + '</p>' +
            '<div class="bl-mini-quiz-actions">' +
            '<button type="button" class="bl-mini-quiz-later">' + lang('later', 'Để sau') + '</button>' +
            '<a class="bl-mini-quiz-start">' + lang('startQuiz', 'Làm Mini Quiz') + ' →</a>' +
            '</div></div>';

        overlay.querySelector('h3').textContent = quiz.title;
        overlay.querySelector('.bl-mini-quiz-start').setAttribute('href', quiz.url);

        overlay.querySelector('.bl-mini-quiz-start').addEventListener('click', function () {
            // Lưu vị trí trước khi rời video
            sendProgress(tracker, true);
        });

        overlay.querySelector('.bl-mini-quiz-later').addEventListener('click', function () {
            closeModal();
            var media = getMedia(tracker.element);
            if (media) {
                media.play();
            }
        });

        document.body.appendChild(overlay);
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
                if (!quiz.passed && !tracker.shownQuizzes[quiz.id] && time >= quiz.show_at_second && time < quiz.show_at_second + 3) {
                    tracker.shownQuizzes[quiz.id] = true;
                    media.pause();
                    showMiniQuizModal(tracker, quiz);
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
            lastSentPosition: 0
        };
        trackers[fileId] = tracker;

        request('GET', config.stateUrl.replace('__ID__', fileId)).then(function (state) {
            tracker.quizzes = state.mini_quizzes || [];
            tracker.completed = !!state.completed;
            tracker.resumeAt = state.completed ? 0 : (state.last_position || 0);
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
