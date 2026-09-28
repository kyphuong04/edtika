{{-- Nơi DUY NHẤT chứa Blade logic của trang attempt. Mọi .js khác là JS thuần. --}}
@php
    $attemptMeta = [
        'attemptId' => $attempt->id,
        'testId' => $test->id,
        'testTitle' => $test->title,
        'testType' => $testType,
        'isMockTest' => $test->isMockTest(),
        'skill' => $currentSection->skill,
    ];
@endphp
<script>
    window.ATTEMPT_SECTION_DATA = @json($sectionData);
    window.ATTEMPT_SAVED_ANSWERS = @json($savedAnswers);
    window.ATTEMPT_META = @json($attemptMeta);
    window.ATTEMPT_INITIAL_REMAINING_SECONDS = @json($initialRemainingSeconds);
    window.ATTEMPT_SAVE_URL = @json(route('panel.ielts_tests.save_answer', $attempt->id));
    window.ATTEMPT_FINISH_SECTION_URL = @json(route('panel.ielts_tests.finish_section', $attempt->id));
    window.ATTEMPT_SCOPE_STATUS_URL = @json(route('panel.ielts_tests.scope_status', $attempt->id));
    window.ATTEMPT_SPEAKING_START_PART_URL_TEMPLATE = @json(
        route('panel.ielts_tests.speaking_start_part', ['attemptId' => $attempt->id, 'partId' => '__PART_ID__'])
    );
    window.ATTEMPT_SPEAKING_MODEL_ANSWER_URL_TEMPLATE = @json(
        route('panel.ielts_tests.speaking_model_answer', ['attemptId' => $attempt->id, 'questionId' => '__QUESTION_ID__'])
    );
    window.ATTEMPT_NEEDS_DURATION = @json($needsDuration ?? false);
    window.ATTEMPT_DURATION_OPTIONS = @json($durationOptions ?? []);
    window.ATTEMPT_SET_DURATION_URL = @json(route('panel.ielts_tests.practice_duration', $attempt->id));
    window.ATTEMPT_CSRF = @json(csrf_token());
    window.ATTEMPT_HIGHLIGHTS = @json($highlights ?? (object) []);
    window.ATTEMPT_HL_STORE_URL = @json(route('panel.ielts_tests.highlights.store', $attempt->id));
    window.ATTEMPT_HL_BASE_URL = @json(url('panel/ielts-tests/attempt/' . $attempt->id . '/highlights'));
</script>
