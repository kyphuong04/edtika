{{-- Table Completion - IDP Style --}}
{{-- Table with headers, some cells have input blanks --}}

@php
    $tableStructure = is_array($tableData) ? $tableData : json_decode($tableData ?? '[]', true);
@endphp

@if(!empty($tableStructure))
    <table class="idp-table">
        @if(!empty($tableStructure['headers']))
            <thead>
                <tr>
                    @foreach($tableStructure['headers'] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @foreach($tableStructure['rows'] ?? [] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>
                            @if(isset($cell['question_id']))
                                @php
                                    $q = $questions->firstWhere('id', $cell['question_id']);
                                    $qNum = $q->question_number ?? '';
                                    $saved = $userAnswers[$q->id] ?? '';
                                @endphp
                                <span class="idp-q-num">{{ $qNum }}</span>
                                <input type="text" class="idp-input" value="{{ $saved }}"
                                       oninput="autoSave({{ $q->id }}, this.value)">
                            @else
                                {{ $cell['text'] ?? $cell }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    {{-- Fallback: Simple table from questions --}}
    <table class="idp-table">
        <tbody>
            @foreach($questions as $q)
                @php
                    $qNum = $q->question_number ?? $loop->iteration;
                    $saved = $userAnswers[$q->id] ?? '';
                    $text = $q->question_text ?? $q->content ?? '';
                @endphp
                <tr data-q-num="{{ $qNum }}">
                    <td>
                        <span class="idp-q-num">{{ $qNum }}</span>
                        <span class="idp-q-text">{{ $text }}</span>
                    </td>
                    <td>
                        <input type="text" class="idp-input" value="{{ $saved }}"
                               oninput="autoSave({{ $q->id }}, this.value)">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
