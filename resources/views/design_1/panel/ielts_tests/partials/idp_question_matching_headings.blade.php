{{-- Matching Headings Question Type --}}
@php
    $headings = $question->headings ?? [];
    $paragraphs = $question->paragraphs ?? [];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    {{-- List of Headings --}}
    <div style="margin-bottom: 20px; padding: 16px; background: #f9f9f9; border: 1px solid #e0e0e0;">
        <div style="font-weight: bold; margin-bottom: 12px;">List of Headings</div>
        @foreach($headings as $index => $heading)
            @php $romanNumeral = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x'][$index] ?? ($index + 1); @endphp
            <div style="margin-bottom: 8px;">
                <strong>{{ $romanNumeral }}</strong> &nbsp; {{ $heading }}
            </div>
        @endforeach
    </div>
    
    {{-- Paragraphs to Match --}}
    @foreach($paragraphs as $index => $paragraph)
        @php
            $qNum = $paragraph['number'] ?? ($question->start_number ?? 1) + $index;
            $paragraphId = $paragraph['id'] ?? $question->id . '_para_' . $index;
            $paragraphLabel = $paragraph['label'] ?? chr(65 + $index); // A, B, C...
            $userAnswer = $userAnswers[$paragraphId] ?? '';
        @endphp
        
        <div class="idp-question" style="margin-bottom: 16px; display: flex; align-items: flex-start; gap: 12px;">
            <span class="idp-question-number">{{ $qNum }}</span>
            
            <div style="flex: 1;">
                <div style="font-weight: bold; margin-bottom: 4px;">
                    Paragraph {{ $paragraphLabel }}
                </div>
                
                <select class="idp-select-heading"
                        id="answer_{{ $paragraphId }}"
                        name="question_{{ $paragraphId }}"
                        onchange="saveAnswer('{{ $paragraphId }}', this.value)"
                        style="padding: 8px 12px; font-size: 14px; border: 1px solid #d9d9d9; min-width: 200px;">
                    <option value="">-- Select heading --</option>
                    @foreach($headings as $hIndex => $heading)
                        @php $romanNumeral = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x'][$hIndex] ?? ($hIndex + 1); @endphp
                        <option value="{{ $romanNumeral }}" {{ $userAnswer === $romanNumeral ? 'selected' : '' }}>
                            {{ $romanNumeral }} - {{ Str::limit($heading, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endforeach
</div>
