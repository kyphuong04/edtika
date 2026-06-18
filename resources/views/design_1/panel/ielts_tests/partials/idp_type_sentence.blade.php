{{-- Sentence Completion / Short Answer - IDP Style --}}
{{-- Question number + sentence with blank OR just input after question --}}

@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
            $text = $q->question_text ?? $q->content ?? '';
        
        // Check if text has blank placeholder
        $hasBlank = preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text);
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}">
        @if($hasBlank)
            {{-- Inline input in sentence --}}
            <span class="idp-q-num">{{ $qNum }}</span>
            <span class="idp-q-text">
                    {!! preg_replace('/_{2,}/', '<input type="text" class="idp-input idp-input-lg" data-qid="'.$q->id.'" value="'.e($saved).'" oninput="autoSave('.$q->id.', this.value)">', $text, 1) !!}
            </span>
        @else
            {{-- Question then input --}}
            <div style="margin-bottom: 6px;">
                <span class="idp-q-num">{{ $qNum }}</span>
                <span class="idp-q-text">{!! $text !!}</span>
            </div>
            <div style="margin-left: 28px;">
                <input type="text" class="idp-input idp-input-lg" 
                       value="{{ $saved }}"
                       data-qid="{{ $q->id }}"
                       oninput="autoSave({{ $q->id }}, this.value)">
            </div>
        @endif
    </div>
@endforeach
