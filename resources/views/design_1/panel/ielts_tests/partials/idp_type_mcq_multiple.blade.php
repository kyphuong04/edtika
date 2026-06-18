{{-- Multiple Choice (Multiple Answers) - IDP Style --}}
{{-- "Choose TWO/THREE letters" with checkboxes --}}

@php
    $savedAnswers = [];
    foreach($questions as $q) {
        $savedAnswers[$q->id] = is_array($userAnswers[$q->id] ?? null) 
            ? $userAnswers[$q->id] 
            : explode(',', $userAnswers[$q->id] ?? '');
    }
    
    // Get first question for options (shared among group)
    $firstQ = $questions->first();
    $rawOpts = $firstQ->answer_options ?? $firstQ->options ?? null;
    $options = is_array($rawOpts) ? $rawOpts : json_decode($rawOpts ?? '[]', true);
    // Convert numeric-indexed array to letter-keyed (A, B, C...)
    if(!empty($options) && array_keys($options) === range(0, count($options)-1)) {
        $letters = range('A', 'Z');
        $options = array_combine(array_slice($letters, 0, count($options)), array_values($options));
    }
    
    $qNums = $questions->pluck('question_number')->toArray();
    $startQ = min($qNums);
    $endQ = max($qNums);
@endphp

<div class="idp-question" data-q-num="{{ $startQ }}">
    <div class="idp-options">
        @foreach($options as $key => $text)
            <label class="idp-option">
                <input type="checkbox" name="q_multi[]" value="{{ $key }}"
                       data-questions="{{ implode(',', $questions->pluck('id')->toArray()) }}"
                       onchange="saveMultiAnswer(this)">
                <span><strong>{{ $key }}</strong> {!! $text !!}</span>
            </label>
        @endforeach
    </div>
</div>

<script>
function saveMultiAnswer(el) {
    const qIds = el.dataset.questions.split(',');
    const checked = [...document.querySelectorAll('input[name="q_multi[]"]:checked')].map(c => c.value);
    qIds.forEach((qId, idx) => {
        if(checked[idx]) {
            saveAnswer(parseInt(qId), checked[idx]);
        }
    });
}
</script>
