{{-- GĐ3: ghi nhận xem video + Mini Quiz (dùng cho learning page ở panel và ở web). GĐ7: làm quiz ngay trên trang --}}
<link rel="stylesheet" href="/assets/design_1/css/edtika-inline-quiz.css?v=gd7">
<script>
    window.bundleLearningPlayer = {
        stateUrl: '/panel/courses/bundle-learning/files/__ID__/state',
        progressUrl: '/panel/courses/bundle-learning/video-progress',
        csrfToken: '{{ csrf_token() }}',
        lang: {
            miniQuizText: @json(trans('bundle_learning.mini_quiz_popup_text')),
            later: @json(trans('bundle_learning.later')),
            startQuiz: @json(trans('bundle_learning.start_mini_quiz')),
            videoCompleted: @json(trans('bundle_learning.video_completed')),
            close: @json(trans('public.close')),
            continueVideo: @json(trans('bundle_learning.inline_quiz_continue_video')),
            quizRequired: @json(trans('bundle_learning.inline_quiz_required'))
        }
    };

    window.edkInlineQuizLang = {
        loading: @json(trans('bundle_learning.inline_quiz_loading')),
        failed: @json(trans('bundle_learning.inline_quiz_request_failed')),
        questions: @json(trans('bundle_learning.inline_quiz_questions')),
        passMark: @json(trans('bundle_learning.inline_quiz_pass_mark')),
        attemptsLeft: @json(trans('bundle_learning.inline_quiz_attempts_left')),
        submitMini: @json(trans('bundle_learning.inline_quiz_submit_mini')),
        submitTest: @json(trans('bundle_learning.inline_quiz_submit_test')),
        retry: @json(trans('bundle_learning.inline_quiz_retry')),
        answerAll: @json(trans('bundle_learning.inline_quiz_answer_all')),
        missingConfirm: @json(trans('bundle_learning.inline_quiz_missing_confirm')),
        timeUp: @json(trans('bundle_learning.inline_quiz_time_up')),
        score: @json(trans('bundle_learning.inline_quiz_score')),
        viewDetail: @json(trans('bundle_learning.inline_quiz_view_detail')),
        markCorrect: @json(trans('bundle_learning.inline_quiz_mark_correct')),
        markWrong: @json(trans('bundle_learning.inline_quiz_mark_wrong')),
        markEmpty: @json(trans('bundle_learning.inline_quiz_mark_empty')),
        markWaiting: @json(trans('bundle_learning.inline_quiz_mark_waiting')),
        alreadyPassed: @json(trans('bundle_learning.inline_quiz_already_passed')),
        noAttempts: @json(trans('bundle_learning.inline_quiz_no_attempts'))
    };
</script>
<script src="/assets/design_1/js/panel/bundle_inline_quiz.js?v=gd7"></script>
<script src="/assets/design_1/js/panel/bundle_learning_player.js?v=gd7"></script>
