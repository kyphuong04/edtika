{{-- 
    IDP Questions Panel - Renders all question types grouped by question_type
    Each group has its own instructions and question type layout
--}}

@foreach($groupedQuestions as $groupKey => $questions)
    @php
        // Get first question to determine group info
        $firstQ = $questions->first();
        
        // Try to get group from first question's question_group_id
        $group = null;
        if($firstQ && $firstQ->question_group_id) {
            $group = $firstQ->questionGroup ?? \App\Models\IeltsQuestionGroup::find($firstQ->question_group_id);
        }
        
        // Determine question type - priority: question_type field > group's type > default
        $questionType = $firstQ->question_type ?? $group->question_type ?? 'fill_blank';
        
        // Get group instructions if available
        $instructions = $group->instructions ?? $group->instruction ?? '';
        $title = $group->title ?? '';
        $wordBank = $group->word_bank ?? [];
        $matchingOptions = $group->matching_options ?? [];
        
        // Convert to array if JSON string
        if(is_string($wordBank)) {
            $wordBank = json_decode($wordBank, true) ?? [];
        }
        if(is_string($matchingOptions)) {
            $matchingOptions = json_decode($matchingOptions, true) ?? [];
        }
    @endphp

    @if(!$loop->first)
        <hr class="idp-group-separator">
    @endif

    {{-- Group Headers & Instructions --}}
    <div class="idp-questions-header">
        {{ trans('update.ielts_questions') }} {{ $questions->first()->question_number ?? '' }}–{{ $questions->last()->question_number ?? '' }}
    </div>
    <div class="idp-questions-instruction">
        {!! !empty($instructions) ? $instructions : getQuestionInstruction($questionType) !!}
    </div>

    {{-- Render questions based on type --}}
    @switch($questionType)
        @case('true_false_not_given')
        @case('true_false_ng')
        @case('tfng')
            @include('design_1.panel.ielts_tests.partials.idp_type_tfng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('yes_no_not_given')
        @case('yes_no_ng')
        @case('ynng')
            @include('design_1.panel.ielts_tests.partials.idp_type_ynng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('multiple_choice')
        @case('mcq')
        @case('single_choice')
        @case('multiple_choice_single')
            @include('design_1.panel.ielts_tests.partials.idp_type_mcq', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('multiple_choice_multiple')
        @case('multiple_select')
        @case('mcq_multiple')
        @case('choose_two')
        @case('choose_three')
            @include('design_1.panel.ielts_tests.partials.idp_type_mcq_multiple', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('sentence_completion')
        @case('short_answer')
            @include('design_1.panel.ielts_tests.partials.idp_type_sentence', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('summary_completion')
        @case('summary')
            @include('design_1.panel.ielts_tests.partials.idp_type_summary', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ])
            @break
            
        @case('matching')
        @case('matching_features')
        @case('matching_information')
        @case('matching_sentence_endings')
            @include('design_1.panel.ielts_tests.partials.idp_type_matching', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('matching_headings')
            @include('design_1.panel.ielts_tests.partials.idp_type_matching_headings', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('note_completion')
        @case('form_completion')
            @include('design_1.panel.ielts_tests.partials.idp_type_note', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'title' => $title
            ])
            @break
            
        @case('table_completion')
            @include('design_1.panel.ielts_tests.partials.idp_type_table', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'tableData' => $group->table_data ?? null
            ])
            @break
            
        @case('flowchart')
        @case('flow_chart')
            @include('design_1.panel.ielts_tests.partials.idp_type_flowchart', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'flowData' => $group->flow_data ?? null
            ])
            @break
            
        @case('map_labeling')
        @case('diagram_labeling')
        @case('diagram_label')
            @include('design_1.panel.ielts_tests.partials.idp_type_map', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'imageUrl' => $group->image_url ?? null,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('drag_drop')
        @case('dragdrop')
            @include('design_1.panel.ielts_tests.partials.idp_type_dragdrop', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ])
            @break
            
        @default
            {{-- Default: Simple fill in blank --}}
            @include('design_1.panel.ielts_tests.partials.idp_type_fill_blank', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
    @endswitch
@endforeach
