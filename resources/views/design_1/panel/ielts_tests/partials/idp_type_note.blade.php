{{-- Note/Form Completion - IDP Style --}}
{{-- Structured list with bullets/dashes and input blanks --}}

@if(!empty($title))
    <div class="idp-note-title">{{ $title }}</div>
@endif

<ul class="idp-note-bullet" style="list-style: none; padding: 0;">
    @foreach($questions as $q)
        @php
            $qNum = $q->question_number ?? $loop->iteration;
            $saved = $userAnswers[$q->id] ?? '';
            $text = $q->question_text ?? $q->content ?? '';
            
            // Detect if it's a section header (no blank)
            $isHeader = !preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text) && empty($text);
        @endphp
        
        @if($isHeader && !empty($q->section_title))
            <li style="font-weight: bold; margin-top: 12px; margin-bottom: 6px;">
                {{ $q->section_title }}
            </li>
        @else
            <li class="idp-question" data-q-num="{{ $qNum }}" style="margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                <span style="margin-right: 4px;">•</span>
                @if(preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text))
                    <span>
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
                    </span>
                @else
                    <span class="idp-q-text">{!! $text !!}</span>
                    <span class="idp-q-num">{{ $qNum }}</span>
                    <input type="text" class="idp-input" value="{{ $saved }}" 
                           oninput="autoSave({{ $q->id }}, this.value)">
                @endif
            </li>
        @endif
    @endforeach
</ul>
