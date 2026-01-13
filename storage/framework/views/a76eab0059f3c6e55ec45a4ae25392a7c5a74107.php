

<?php $__currentLoopData = $groupedQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $questions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
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
    ?>

    <?php if(!$loop->first): ?>
        <hr class="idp-group-separator">
    <?php endif; ?>

    
    <div class="idp-questions-header">
        Questions <?php echo e($questions->first()->question_number ?? ''); ?>–<?php echo e($questions->last()->question_number ?? ''); ?>

        <a href="#" class="idp-help-link">📧 Help</a>
    </div>
    <div class="idp-questions-instruction">
        <?php echo !empty($instructions) ? $instructions : getQuestionInstruction($questionType); ?>

    </div>

    
    <?php switch($questionType):
        case ('true_false_not_given'): ?>
        <?php case ('tfng'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_tfng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('yes_no_not_given'): ?>
        <?php case ('ynng'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_ynng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('multiple_choice'): ?>
        <?php case ('mcq'): ?>
        <?php case ('single_choice'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_mcq', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('multiple_choice_multiple'): ?>
        <?php case ('mcq_multiple'): ?>
        <?php case ('choose_two'): ?>
        <?php case ('choose_three'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_mcq_multiple', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('sentence_completion'): ?>
        <?php case ('short_answer'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_sentence', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('summary_completion'): ?>
        <?php case ('summary'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_summary', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('matching'): ?>
        <?php case ('matching_features'): ?>
        <?php case ('matching_information'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_matching', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('matching_headings'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_matching_headings', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('note_completion'): ?>
        <?php case ('form_completion'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_note', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'title' => $title
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('table_completion'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_table', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'tableData' => $group->table_data ?? null
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('flowchart'): ?>
        <?php case ('flow_chart'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_flowchart', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'flowData' => $group->flow_data ?? null
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('map_labeling'): ?>
        <?php case ('diagram_labeling'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_map', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'imageUrl' => $group->image_url ?? null,
                'matchingOptions' => $matchingOptions
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php case ('drag_drop'): ?>
        <?php case ('dragdrop'): ?>
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_dragdrop', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
            
        <?php default: ?>
            
            <?php echo $__env->make('design_1.panel.ielts_tests.partials.idp_type_fill_blank', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endswitch; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_questions_panel.blade.php ENDPATH**/ ?>