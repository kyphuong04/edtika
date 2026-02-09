{{-- YES / NO / NOT GIVEN - IDP Style --}}
{{-- Same layout as TFNG but with YES/NO --}}

@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}">
        <div style="margin-bottom: 6px;">
            <span class="idp-q-num">{{ $qNum }}</span>
            <span class="idp-q-text">{{ $q->question_text ?? $q->content ?? '' }}</span>
        </div>
        <div class="idp-options">
            <label class="idp-option">
                <input type="radio" name="q_{{ $q->id }}" value="YES" 
                       {{ $saved === 'YES' ? 'checked' : '' }}
                       onchange="saveAnswer({{ $q->id }}, 'YES')">
                <span>{{ trans('update.ielts_yes') }}</span>
            </label>
            <label class="idp-option">
                <input type="radio" name="q_{{ $q->id }}" value="NO"
                       {{ $saved === 'NO' ? 'checked' : '' }}
                       onchange="saveAnswer({{ $q->id }}, 'NO')">
                <span>{{ trans('update.ielts_no') }}</span>
            </label>
            <label class="idp-option">
                <input type="radio" name="q_{{ $q->id }}" value="NOT GIVEN"
                       {{ $saved === 'NOT GIVEN' ? 'checked' : '' }}
                       onchange="saveAnswer({{ $q->id }}, 'NOT GIVEN')">
                <span>{{ trans('update.ielts_not_given') }}</span>
            </label>
        </div>
    </div>
@endforeach
