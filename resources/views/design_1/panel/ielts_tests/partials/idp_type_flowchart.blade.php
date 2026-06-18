{{-- Flow Chart Completion - IDP Style --}}
{{-- Boxes connected by arrows (↓) with input blanks inside --}}

@php
    $flowStructure = is_array($flowData) ? $flowData : json_decode($flowData ?? '[]', true);
@endphp

<div class="idp-flowchart">
    @if(!empty($flowStructure))
        @foreach($flowStructure as $idx => $step)
            <div class="idp-flow-box {{ isset($step['question_id']) ? 'highlight' : '' }}">
                @if(isset($step['question_id']))
                    @php
                        $q = $questions->firstWhere('id', $step['question_id']);
                        $qNum = $q->question_number ?? '';
                        $saved = $userAnswers[$q->id] ?? '';
                    @endphp
                    {!! preg_replace_callback(
                        '/_{2,}|\[\s*\d*\s*\]|____/',
                        function($m) use ($q, $qNum, $saved) {
                            return '<span class="idp-q-num">' . $qNum . '</span> ' .
                                   '<input type="text" class="idp-input" value="' . e($saved) . '" 
                                           oninput="autoSave(' . $q->id . ', this.value)">';
                        },
                        ($step['text'] ?? ''),
                        1
                    ) !!}
                @else
                    {!! $step['text'] ?? '' !!}
                @endif
            </div>
            @if(!$loop->last)
                <div class="idp-flow-arrow">↓</div>
            @endif
        @endforeach
    @else
        {{-- Fallback: Generate from questions --}}
        @foreach($questions as $q)
            @php
                $qNum = $q->question_number ?? $loop->iteration;
                $saved = $userAnswers[$q->id] ?? '';
                $text = $q->question_text ?? $q->content ?? '';
            @endphp
            <div class="idp-flow-box highlight" data-q-num="{{ $qNum }}">
                @if(preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text))
                    {!! preg_replace_callback(
                        '/_{2,}|\[\s*\d*\s*\]|____/',
                        function($m) use ($q, $qNum, $saved) {
                            return '<span class="idp-q-num">' . $qNum . '</span> ' .
                                   '<input type="text" class="idp-input" value="' . e($saved) . '" 
                                           oninput="autoSave(' . $q->id . ', this.value)">';
                        },
                        $text,
                        1
                    ) !!}
                @else
                    <span class="idp-q-num">{{ $qNum }}</span>
                    <input type="text" class="idp-input" value="{{ $saved }}" 
                           oninput="autoSave({{ $q->id }}, this.value)">
                    {!! $text !!}
                @endif
            </div>
            @if(!$loop->last)
                <div class="idp-flow-arrow">↓</div>
            @endif
        @endforeach
    @endif
</div>
