{{-- GĐ6 - CSS + JS cho khung Pre Lesson / Bài Tập / Post Lesson (lesson_activities) trong trình soạn module --}}
<style>
    .lesson-activities { margin-top: 16px; border: 1px dashed #d9c9ec; border-radius: 14px; padding: 14px 16px; background: #fcfaff; }
    .lesson-activities__head { display: flex; flex-wrap: wrap; align-items: baseline; gap: 8px; margin-bottom: 6px; }
    .lesson-activities__head b { color: #511D99; font-size: 14px; }
    .lesson-activities__head span { color: #8a7d99; font-size: 12px; }
    .lesson-activities__row { display: grid; grid-template-columns: 210px 1fr; gap: 14px; padding: 12px 0; border-top: 1px solid #efe7f8; }
    .lesson-activities__row:first-of-type { border-top: 0; }
    .lesson-activities__label { display: flex; flex-direction: column; gap: 6px; }
    .lesson-activities__badge { align-self: flex-start; background: #efe6fb; color: #511D99; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 999px; }
    .lesson-activities__label small, .lesson-activities__muted { color: #8a7d99; font-size: 12px; line-height: 1.45; }
    .lesson-activities__controls { display: flex; gap: 8px; align-items: center; }
    .lesson-activities__controls .form-control { max-width: 420px; height: 38px; padding: 6px 12px; line-height: 1.4; font-size: 13px; }
    .lesson-activities__controls .btn { white-space: nowrap; }
    .lesson-activities__current { color: #1d7a45; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
    .lesson-activities__warn { color: #b25b00; font-size: 12px; margin-bottom: 8px; }
    .lesson-activities__list { list-style: none; padding: 0; margin: 0 0 8px; display: grid; gap: 6px; }
    .lesson-activities__list li { display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #ece4f6; border-radius: 10px; padding: 7px 10px; font-size: 13px; }
    .lesson-activities__num { color: #511D99; font-weight: 700; white-space: nowrap; }
    .lesson-activities__title { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .lesson-activities__status { font-size: 11px; color: #b25b00; background: #fff4e5; border-radius: 6px; padding: 2px 6px; white-space: nowrap; }
    .lesson-activities__actions { display: flex; gap: 4px; }
    .lesson-activities__actions button, .lesson-activities__actions a { border: 1px solid #e3d8f1; background: #fff; color: #511D99; border-radius: 7px; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; text-decoration: none; }
    .lesson-activities__actions button:disabled { opacity: .35; }
    .lesson-activities.is-busy { opacity: .6; pointer-events: none; }
    @media (max-width: 767px) { .lesson-activities__row { grid-template-columns: 1fr; } .lesson-activities__controls { flex-wrap: wrap; } }
</style>
<script>
    (function () {
        var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';

        function notify(type, message) {
            if (message && typeof window.showToast === 'function') {
                window.showToast(type, '', message);
            }
        }

        function send(box, path, data) {
            var body = new FormData();
            body.append('_token', csrf);
            Object.keys(data || {}).forEach(function (key) {
                body.append(key, data[key]);
            });

            box.classList.add('is-busy');

            return fetch(box.getAttribute('data-url') + path, {
                method: 'POST',
                body: body,
                credentials: 'same-origin',
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            }).then(function (response) {
                return response.json().then(function (json) {
                    return {ok: response.ok, json: json};
                });
            }).then(function (result) {
                box.classList.remove('is-busy');
                if (!result.ok) {
                    notify('error', result.json.message || @json(trans('public.request_failed')));
                    return;
                }
                var wrapper = document.createElement('div');
                wrapper.innerHTML = result.json.html;
                box.replaceWith(wrapper.firstElementChild);
                notify('success', result.json.message);
            }).catch(function () {
                box.classList.remove('is-busy');
                notify('error', @json(trans('public.request_failed')));
            });
        }

        document.addEventListener('click', function (event) {
            var button = event.target.closest('.js-lesson-activities button');
            if (!button) {
                return;
            }
            var box = button.closest('.js-lesson-activities');

            if (button.classList.contains('js-activity-save-pre')) {
                event.preventDefault();
                send(box, '/pre-lesson', {vocabulary_set_id: box.querySelector('.js-activity-word-set').value});
            } else if (button.classList.contains('js-activity-add-test')) {
                event.preventDefault();
                var role = button.getAttribute('data-role');
                var select = box.querySelector('.js-activity-test[data-role="' + role + '"]');
                if (!select.value) {
                    notify('error', @json(trans('bundle_learning.activity_choose_test')));
                    return;
                }
                send(box, '/tests', {role: role, ielts_test_id: select.value});
            } else if (button.classList.contains('js-activity-remove')) {
                event.preventDefault();
                if (confirm(@json(trans('bundle_learning.activity_remove_confirm')))) {
                    send(box, '/tests/' + button.getAttribute('data-id') + '/delete', {});
                }
            } else if (button.classList.contains('js-activity-move')) {
                event.preventDefault();
                send(box, '/tests/' + button.getAttribute('data-id') + '/move', {dir: button.getAttribute('data-dir')});
            }
        });
    })();
</script>
