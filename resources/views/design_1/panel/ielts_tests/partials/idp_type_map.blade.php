{{-- Map/Diagram Labeling - IDP Style --}}
{{-- Left: Image/Map, Right: Matching table with radio buttons --}}

@php
    $matchOptions = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);
    $optionKeys = array_keys($matchOptions);
    if(empty($optionKeys)) {
        $optionKeys = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    }
@endphp

<div class="idp-map-layout">
    @if(!empty($imageUrl))
        <div class="idp-map-image">
            <img src="{{ $imageUrl }}" alt="Map/Diagram">
        </div>
    @endif
    
    <div class="idp-map-questions">
        @if(!empty($matchOptions))
            <div class="idp-options" style="margin-bottom: 10px; margin-left: 0;">
                @foreach($matchOptions as $label => $desc)
                    <div style="font-size:14px; margin-bottom:4px;"><strong>{{ $label }}.</strong> {!! $desc !!}</div>
                @endforeach
            </div>
        @endif
        <table class="idp-match-table">
            <thead>
                <tr>
                    <th></th>
                    @foreach($optionKeys as $opt)
                        <th>{{ $opt }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $q)
                    @php
                        $qNum = $q->question_number ?? $loop->iteration;
                        $saved = $userAnswers[$q->id] ?? '';
                    @endphp
                    <tr data-q-num="{{ $qNum }}">
                        <td>
                            <span class="idp-q-num">{{ $qNum }}</span>
                            <span class="idp-q-text">{!! $q->question_text ?? $q->content ?? '' !!}</span>
                        </td>
                        @foreach($optionKeys as $opt)
                            <td>
                                <input type="radio" name="q_{{ $q->id }}" value="{{ $opt }}"
                                       {{ $saved === $opt ? 'checked' : '' }}
                                       onchange="saveAnswer({{ $q->id }}, '{{ $opt }}')">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
