{{-- GĐ3: ghi nhận xem video + Mini Quiz (dùng cho learning page ở panel và ở web) --}}
<style>
    .bl-mini-quiz-overlay { position: fixed; inset: 0; z-index: 2000; background: rgba(33, 24, 46, .45); display: flex; align-items: center; justify-content: center; padding: 16px; }
    .bl-mini-quiz-modal { background: #fff; border-radius: 20px; padding: 24px; max-width: 420px; width: 100%; box-shadow: 0 20px 60px rgba(33, 24, 46, .25); }
    .bl-mini-quiz-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #5A2B81; }
    .bl-mini-quiz-modal h3 { font-size: 18px; font-weight: 700; margin: 8px 0; }
    .bl-mini-quiz-modal p { color: #756a82; font-size: 14px; }
    .bl-mini-quiz-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }
    .bl-mini-quiz-actions button, .bl-mini-quiz-actions a { padding: 9px 16px; border-radius: 10px; font-weight: 600; font-size: 14px; border: 1px solid #eee8f4; background: #fff; color: #21182e; }
    .bl-mini-quiz-actions a { background: #5A2B81; border-color: #5A2B81; color: #fff; }
</style>
<script>
    window.bundleLearningPlayer = {
        stateUrl: '/panel/courses/bundle-learning/files/__ID__/state',
        progressUrl: '/panel/courses/bundle-learning/video-progress',
        csrfToken: '{{ csrf_token() }}',
        lang: {
            miniQuizText: @json(trans('bundle_learning.mini_quiz_popup_text')),
            later: @json(trans('bundle_learning.later')),
            startQuiz: @json(trans('bundle_learning.start_mini_quiz')),
            videoCompleted: @json(trans('bundle_learning.video_completed'))
        }
    };
</script>
<script src="/assets/design_1/js/panel/bundle_learning_player.js?v=gd3"></script>
