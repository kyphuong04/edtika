{{-- Question Form for Reading/Listening - Improved UI --}}

@php
    // Initial question number is always 1 for new groups (local-first approach)
    // The actual numbering is managed by JavaScript from the local questionsArray
    $nextQuestionNumber = 1;
@endphp

{{-- Compact Add Question Form --}}
<div class="bg-white rounded-16 shadow-sm mb-20">
    <div class="p-16 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="font-14 font-weight-bold text-dark mb-0">
            <x-iconsax-lin-add-circle class="text-primary mr-8" width="18" height="18"/>Add Question
            <span class="badge badge-primary ml-8" id="localQuestionCount">0 in list</span>
        </h5>
        <button type="button" id="resetFormBtn" class="btn btn-sm btn-outline-secondary">
            <x-iconsax-lin-refresh class="mr-4" width="14" height="14"/>Reset
        </button>
    </div>
    
    <div class="p-16">
        <form id="questionForm">
            @csrf
            
            {{-- Question Number & Type in one row --}}
            <div class="d-flex gap-12 mb-16">
                <div style="width: 100px;">
                    <label class="font-12 font-weight-bold text-gray-600 mb-6">Q. No.</label>
                    <input type="number" name="question_number" id="question_number" 
                           class="form-control form-control-sm text-center" 
                           value="{{ $nextQuestionNumber }}" min="1" required style="font-weight: 600;">
                </div>
                <div class="flex-fill">
                    <label class="font-12 font-weight-bold text-gray-600 mb-6">Question Type</label>
                    <select name="question_type" id="questionType" class="form-control form-control-sm" required>
                        <option value="">-- Select Type --</option>
                        @if($group->skill === 'reading')
                            <optgroup label="Multiple Choice">
                                <option value="multiple_choice_single">Single Answer</option>
                                <option value="multiple_choice_multiple">Multiple Answers</option>
                            </optgroup>
                            <optgroup label="True/False/Not Given">
                                <option value="true_false_not_given">True / False / Not Given</option>
                                <option value="yes_no_not_given">Yes / No / Not Given</option>
                            </optgroup>
                            <optgroup label="Matching">
                                <option value="matching_headings">Matching Headings</option>
                                <option value="matching_information">Matching Information</option>
                                <option value="matching_features">Matching Features</option>
                                <option value="matching_sentence_endings">Matching Sentence Endings</option>
                            </optgroup>
                            <optgroup label="Completion">
                                <option value="sentence_completion">Sentence Completion</option>
                                <option value="summary_completion">Summary Completion</option>
                                <option value="note_completion">Note Completion</option>
                                <option value="table_completion">Table Completion</option>
                                <option value="diagram_labeling">Diagram Labeling</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="short_answer">Short Answer</option>
                            </optgroup>
                        @elseif($group->skill === 'listening')
                            <optgroup label="Multiple Choice">
                                <option value="multiple_choice_single">Single Answer</option>
                                <option value="multiple_choice_multiple">Multiple Answers</option>
                            </optgroup>
                            <optgroup label="Completion">
                                <option value="form_completion">Form Completion</option>
                                <option value="note_completion">Note Completion</option>
                                <option value="table_completion">Table Completion</option>
                                <option value="sentence_completion">Sentence Completion</option>
                            </optgroup>
                            <optgroup label="Labeling">
                                <option value="flow_chart">Flow Chart</option>
                                <option value="map_labeling">Map Labeling</option>
                                <option value="diagram_labeling">Diagram Labeling</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="short_answer">Short Answer</option>
                            </optgroup>
                        @endif
                    </select>
                </div>
            </div>
            
            {{-- Type-specific Fields Container --}}
            <div id="typeSpecificFields">
                {{-- Empty state --}}
                <div id="selectTypePrompt" class="text-center py-24 bg-gray-50 rounded-12">
                    <x-iconsax-lin-mouse-circle class="text-gray-400 mb-12" width="48" height="48"/>
                    <p class="text-gray-500 mb-0 font-14">Select a question type to continue</p>
                </div>
                
                {{-- All forms pre-rendered, show/hide based on selection --}}
                @if($group->skill === 'reading')
                    <div class="question-type-form" data-type="multiple_choice_single" style="display:none;">
                        @include('design_1.panel.questions.types.multiple_choice_single')
                    </div>
                    <div class="question-type-form" data-type="multiple_choice_multiple" style="display:none;">
                        @include('design_1.panel.questions.types.multiple_choice_multiple')
                    </div>
                    <div class="question-type-form" data-type="true_false_not_given" style="display:none;">
                        @include('design_1.panel.questions.types.true_false_not_given')
                    </div>
                    <div class="question-type-form" data-type="yes_no_not_given" style="display:none;">
                        @include('design_1.panel.questions.types.yes_no_not_given')
                    </div>
                    <div class="question-type-form" data-type="matching_headings" style="display:none;">
                        @include('design_1.panel.questions.types.matching_headings')
                    </div>
                    <div class="question-type-form" data-type="matching_information" style="display:none;">
                        @include('design_1.panel.questions.types.matching_information')
                    </div>
                    <div class="question-type-form" data-type="matching_features" style="display:none;">
                        @include('design_1.panel.questions.types.matching_features')
                    </div>
                    <div class="question-type-form" data-type="matching_sentence_endings" style="display:none;">
                        @include('design_1.panel.questions.types.matching_sentence_endings')
                    </div>
                    <div class="question-type-form" data-type="sentence_completion" style="display:none;">
                        @include('design_1.panel.questions.types.sentence_completion')
                    </div>
                    <div class="question-type-form" data-type="summary_completion" style="display:none;">
                        @include('design_1.panel.questions.types.summary_completion')
                    </div>
                    <div class="question-type-form" data-type="note_completion" style="display:none;">
                        @include('design_1.panel.questions.types.note_completion')
                    </div>
                    <div class="question-type-form" data-type="table_completion" style="display:none;">
                        @include('design_1.panel.questions.types.table_completion')
                    </div>
                    <div class="question-type-form" data-type="diagram_labeling" style="display:none;">
                        @include('design_1.panel.questions.types.diagram_labeling')
                    </div>
                    <div class="question-type-form" data-type="short_answer" style="display:none;">
                        @include('design_1.panel.questions.types.short_answer')
                    </div>
                @elseif($group->skill === 'listening')
                    <div class="question-type-form" data-type="multiple_choice_single" style="display:none;">
                        @include('design_1.panel.questions.types.multiple_choice_single')
                    </div>
                    <div class="question-type-form" data-type="multiple_choice_multiple" style="display:none;">
                        @include('design_1.panel.questions.types.multiple_choice_multiple')
                    </div>
                    <div class="question-type-form" data-type="form_completion" style="display:none;">
                        @include('design_1.panel.questions.types.form_completion')
                    </div>
                    <div class="question-type-form" data-type="note_completion" style="display:none;">
                        @include('design_1.panel.questions.types.note_completion')
                    </div>
                    <div class="question-type-form" data-type="table_completion" style="display:none;">
                        @include('design_1.panel.questions.types.table_completion')
                    </div>
                    <div class="question-type-form" data-type="flow_chart" style="display:none;">
                        @include('design_1.panel.questions.types.flow_chart')
                    </div>
                    <div class="question-type-form" data-type="map_labeling" style="display:none;">
                        @include('design_1.panel.questions.types.map_labeling')
                    </div>
                    <div class="question-type-form" data-type="diagram_labeling" style="display:none;">
                        @include('design_1.panel.questions.types.diagram_labeling')
                    </div>
                    <div class="question-type-form" data-type="sentence_completion" style="display:none;">
                        @include('design_1.panel.questions.types.sentence_completion')
                    </div>
                    <div class="question-type-form" data-type="short_answer" style="display:none;">
                        @include('design_1.panel.questions.types.short_answer')
                    </div>
                @endif
            </div>
            
            {{-- Add Button --}}
            <div class="mt-16 pt-16 border-top">
                <button type="button" id="addQuestionToListBtn" class="btn btn-primary w-100">
                    <x-iconsax-lin-add-circle class="icons mr-8" width="18" height="18"/>Add to List
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Questions Preview List --}}
<div class="bg-white rounded-16 shadow-sm" id="questionsListCard" style="display: none;">
    <div class="p-16 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="font-14 font-weight-bold text-dark mb-0">
            <x-iconsax-bul-tick-circle class="icons text-success mr-8" width="18" height="18"/>
            <span id="questionCount">0</span> Question(s) Ready
        </h5>
        <button type="button" id="saveAllQuestionsBtn" class="btn btn-success btn-sm">
            <x-iconsax-lin-cloud class="icons mr-8" width="16" height="16"/>Save All
        </button>
    </div>
    <div class="p-16">
        <div id="questionsPreviewList">
            {{-- Questions will be added here dynamically --}}
        </div>
    </div>
</div>

{{-- Existing Questions Section --}}
<div class="mt-20">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h6 class="font-12 font-weight-bold text-gray-600 text-uppercase mb-0">
            <x-iconsax-lin-archive class="icons mr-8" width="16" height="16"/>Saved Questions ({{ $group->questions->count() }})
        </h6>
    </div>
    
    @if($group->questions->count() > 0)
        <div class="questions-list">
            @foreach($group->questions as $question)
                @php
                    $questionData = json_decode($question->question_data ?? '{}', true) ?? [];
                    $correctAnswer = $question->correct_answer ?? '-';
                    $questionText = $question->question_text ?? $questionData['question_text'] ?? $questionData['statement'] ?? '';
                    $questionTypeLabel = ucwords(str_replace('_', ' ', $question->question_type ?? 'Unknown'));
                    
                    // Format answer based on type
                    $answerDisplay = $correctAnswer;
                    if (in_array($correctAnswer, ['true', 'yes'])) {
                        $answerDisplay = '<span class="text-success font-weight-bold">' . strtoupper($correctAnswer) . '</span>';
                    } elseif (in_array($correctAnswer, ['false', 'no'])) {
                        $answerDisplay = '<span class="text-danger font-weight-bold">' . strtoupper($correctAnswer) . '</span>';
                    } elseif ($correctAnswer === 'not_given') {
                        $answerDisplay = '<span class="text-warning font-weight-bold">NOT GIVEN</span>';
                    }
                @endphp
                <div class="question-item p-12 bg-gray-50 rounded-8 mb-8" style="border-left: 3px solid #3b82f6;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-fill">
                            <div class="d-flex align-items-center mb-8">
                                <span class="badge badge-primary mr-12" style="min-width: 32px;">Q{{ $question->question_order ?? $question->question_number ?? $loop->iteration }}</span>
                                <span class="badge badge-light text-primary font-11">{{ $questionTypeLabel }}</span>
                            </div>
                            @if($questionText)
                                <p class="font-13 text-dark mb-8" style="line-height: 1.5;">{{ Str::limit($questionText, 150) }}</p>
                            @endif
                            <div class="d-flex align-items-center flex-wrap gap-8">
                                <div class="font-12">
                                    <x-iconsax-bul-tick-circle class="icons text-success mr-4" width="14" height="14"/>
                                    <strong>Answer:</strong> {!! $answerDisplay !!}
                                </div>
                                @if($question->word_limit)
                                    <span class="badge badge-secondary font-10">Max {{ $question->word_limit }} word(s)</span>
                                @endif
                                @if($question->alternative_answers)
                                    <span class="font-11 text-muted">Alt: {{ is_array(json_decode($question->alternative_answers)) ? implode(', ', json_decode($question->alternative_answers)) : $question->alternative_answers }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-8 ml-12">
                            <a href="{{ route('panel.questions.edit', $question->id) }}" class="btn btn-sm btn-light" title="Edit">
                                <x-iconsax-lin-edit-2 class="icons text-primary" width="16" height="16"/>
                            </a>
                            <form action="{{ route('panel.questions.destroy', $question->id) }}" method="POST" 
                                  onsubmit="return confirm('Delete this question?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                    <x-iconsax-lin-trash class="icons text-danger" width="16" height="16"/>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 rounded-12">
            <x-iconsax-bul-box class="icons text-gray-300 mb-8" width="48" height="48"/>
            <p class="text-gray-400 mb-0 font-13">No questions saved yet</p>
        </div>
    @endif
</div>

@push('scripts_bottom')
<script>
$(document).ready(function() {
    let questionsArray = [];
    let editingIndex = -1;
    
    // Helper function for notifications using SweetAlert2
    function notify(type, message) {
        if (typeof Swal !== 'undefined') {
            const icons = {
                'success': 'success',
                'error': 'error', 
                'warning': 'warning',
                'info': 'info'
            };
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icons[type] || 'info',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else if (typeof $.toast !== 'undefined') {
            $.toast({
                heading: type.charAt(0).toUpperCase() + type.slice(1),
                text: message,
                position: 'top-right',
                icon: type === 'warning' ? 'warning' : (type === 'error' ? 'error' : (type === 'info' ? 'info' : 'success')),
                showHideTransition: 'slide',
                hideAfter: 3000
            });
        } else {
            console.log(`[${type}] ${message}`);
        }
    }

    // Show/hide form based on question type selection
    // Use multiple event handlers for better compatibility
    $(document).on('change', '#questionType', function() {
        const type = $(this).val();
        console.log('Question type changed to:', type);
        
        // Hide prompt and all forms
        $('#selectTypePrompt').hide();
        $('.question-type-form').hide();
        
        if (type) {
            // Show selected form
            const $targetForm = $(`.question-type-form[data-type="${type}"]`);
            if ($targetForm.length) {
                $targetForm.show();
                console.log('Showing form for type:', type);
            } else {
                console.warn('No form found for type:', type);
                notify('warning', `Form template for "${type}" not found`);
                $('#selectTypePrompt').show();
            }
        } else {
            $('#selectTypePrompt').show();
        }
    });
    
    // Also bind with native event for extra safety
    const questionTypeSelect = document.getElementById('questionType');
    if (questionTypeSelect) {
        questionTypeSelect.addEventListener('change', function() {
            $(this).trigger('change');
        });
    }

    // Add question to list
    $('#addQuestionToListBtn').on('click', function() {
        const type = $('#questionType').val();
        
        if (!type) {
            notify('warning', 'Please select a question type');
            return;
        }
        
        const qNum = $('#question_number').val();
        if (!qNum) {
            notify('warning', 'Please enter a question number');
            return;
        }
        
        // Get only the visible form's data
        const $visibleForm = $(`.question-type-form[data-type="${type}"]`);
        
        // Check if batch mode
        const isBatchMode = $visibleForm.find('input[name="batch_mode"]').val() === '1';
        
        if (isBatchMode) {
            // BATCH MODE: Add multiple questions at once
            // Find batch data from any batch input (different types use different IDs)
            let batchData = '';
            const $batchInput = $visibleForm.find('input[name="batch_questions"]');
            if ($batchInput.length) {
                batchData = $batchInput.val();
            }
            
            let batchQuestions = [];
            
            try {
                batchQuestions = JSON.parse(batchData);
            } catch (e) {
                notify('error', 'Invalid batch data');
                return;
            }
            
            if (!batchQuestions || batchQuestions.length === 0) {
                notify('warning', 'Please fill in at least one question with answer');
                return;
            }
            
            // Add each question from batch
            batchQuestions.forEach(function(q) {
                const questionData = {
                    question_number: q.question_number,
                    question_type: q.question_type || type,
                    question_type_label: q.question_type_label || $('#questionType option:selected').text().trim(),
                    question_text: q.question_text || '',
                    correct_answer: q.correct_answer || ''
                };
                
                // Copy all other properties
                for (let key in q) {
                    if (key.startsWith('options[')) {
                        questionData[key] = q[key];
                    } else if (!['question_number', 'question_type', 'question_type_label', 'question_text', 'correct_answer'].includes(key)) {
                        questionData['question_data[' + key + ']'] = q[key];
                    }
                }
                
                questionsArray.push(questionData);
            });
            
            notify('success', `Added ${batchQuestions.length} questions to list`);
            
            // Update question number for next batch
            const maxQNum = Math.max(...batchQuestions.map(q => parseInt(q.question_number)));
            $('#question_number').val(maxQNum + 1);
            
            updateQuestionsList();
            return;
        }
        
        // SINGLE MODE: Original logic
        // Check required fields in visible form only
        let valid = true;
        $visibleForm.find('[required]').each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).focus();
                return false;
            }
        });
        
        if (!valid) {
            notify('warning', 'Please fill in all required fields');
            return;
        }

        // Build question data
        const questionData = {
            question_number: qNum,
            question_type: type,
            question_type_label: $('#questionType option:selected').text().trim()
        };
        
        // Get data from visible form only
        $visibleForm.find('input, select, textarea').each(function() {
            const name = $(this).attr('name');
            if (name && name !== '_token') {
                const value = $(this).val();
                if ($(this).is(':checkbox') || $(this).is(':radio')) {
                    if ($(this).is(':checked')) {
                        questionData[name] = value;
                    }
                } else {
                    questionData[name] = value;
                }
            }
        });

        // Calculate next question number BEFORE reset
        const nextQuestionNumber = parseInt(qNum) + 1;
        
        if (editingIndex >= 0) {
            questionsArray[editingIndex] = questionData;
            editingIndex = -1;
            $('#addQuestionToListBtn').html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>Add to List');
            notify('success', 'Question updated');
        } else {
            questionsArray.push(questionData);
            notify('success', 'Question added to list');
        }

        updateQuestionsList();
        resetForm();
        
        // Auto-increment question number (use saved value)
        $('#question_number').val(nextQuestionNumber);
    });

    // Reset form
    $('#resetFormBtn').on('click', function() {
        resetForm();
        notify('info', 'Form reset');
    });

    function resetForm() {
        // Calculate next question number from local array only
        let nextNum = 1;
        if (questionsArray.length > 0) {
            const maxLocalNum = Math.max(...questionsArray.map(q => parseInt(q.question_number) || 0));
            nextNum = maxLocalNum + 1;
        }
        
        $('#questionForm')[0].reset();
        $('#question_number').val(nextNum);
        $('.question-type-form').hide();
        $('#selectTypePrompt').show();
        editingIndex = -1;
        $('#addQuestionToListBtn').html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>Add to List');
        
        // Update local count badge
        $('#localQuestionCount').text(questionsArray.length + ' in list');
    }

    // Update questions preview list
    function updateQuestionsList() {
        const listHtml = questionsArray.map((q, index) => {
            const correctAnswer = q.correct_answer || '-';
            const questionText = q.question_text || q.statement || '';
            const reference = q.question_data?.reference || q['question_data[reference]'] || '';
            const questionType = q.question_type || '';
            
            // Truncate long text
            const truncatedText = questionText.length > 100 
                ? questionText.substring(0, 100) + '...' 
                : questionText;
            
            // Get additional details based on question type
            let extraDetails = '';
            let answerDisplay = correctAnswer;
            
            // True/False/Not Given formatting
            if (correctAnswer === 'true') answerDisplay = '<span class="text-success font-weight-bold">TRUE</span>';
            else if (correctAnswer === 'false') answerDisplay = '<span class="text-danger font-weight-bold">FALSE</span>';
            else if (correctAnswer === 'not_given') answerDisplay = '<span class="text-warning font-weight-bold">NOT GIVEN</span>';
            
            // Matching Headings - show paragraph and heading
            if (questionType.includes('matching_headings')) {
                const paragraph = q['question_data[paragraph]'] || q.question_data?.paragraph || '';
                const headingText = q['options[' + correctAnswer + ']'] || '';
                if (paragraph) {
                    extraDetails += `<div class="font-12 mb-4"><span class="badge badge-info mr-8">Đoạn ${paragraph}</span></div>`;
                }
                if (headingText) {
                    extraDetails += `<div class="font-12 text-gray-600 mb-4"><em>"${headingText}"</em></div>`;
                }
            }
            
            // Matching Information - show paragraph
            if (questionType.includes('matching_information')) {
                extraDetails += `<div class="font-12 mb-4"><span class="badge badge-info">→ Đoạn ${correctAnswer}</span></div>`;
            }
            
            // Matching Features - show feature name if available
            if (questionType.includes('matching_features')) {
                const featureName = q['options[' + correctAnswer + ']'] || '';
                if (featureName) {
                    extraDetails += `<div class="font-12 text-gray-600 mb-4">→ <strong>${correctAnswer}:</strong> ${featureName}</div>`;
                }
            }
            
            // Matching Sentence Endings - show ending text
            if (questionType.includes('matching_sentence_endings')) {
                const endingText = q['options[' + correctAnswer + ']'] || '';
                if (endingText) {
                    extraDetails += `<div class="font-12 text-gray-600 mb-4">→ "...${endingText}"</div>`;
                }
            }
            
            // Multiple choice - show options
            if (questionType.includes('multiple_choice')) {
                let optionsHtml = '';
                ['A', 'B', 'C', 'D'].forEach(opt => {
                    const optText = q['options[' + opt + ']'] || q.options?.[opt] || '';
                    if (optText) {
                        const isCorrect = correctAnswer === opt;
                        optionsHtml += `<span class="font-11 mr-12 ${isCorrect ? 'text-success font-weight-bold' : 'text-muted'}">${opt}: ${optText.substring(0, 30)}${optText.length > 30 ? '...' : ''}</span>`;
                    }
                });
                if (optionsHtml) {
                    extraDetails += `<div class="font-12 mb-4">${optionsHtml}</div>`;
                }
            }
            
            // Yes/No/Not Given formatting
            if (questionType.includes('yes_no_not_given')) {
                if (correctAnswer === 'yes') answerDisplay = '<span class="text-success font-weight-bold">YES</span>';
                else if (correctAnswer === 'no') answerDisplay = '<span class="text-danger font-weight-bold">NO</span>';
                else if (correctAnswer === 'not_given') answerDisplay = '<span class="text-warning font-weight-bold">NOT GIVEN</span>';
            }
            
            // Completion types (sentence, summary, note, table, form, diagram, flow_chart, map)
            if (questionType.includes('completion') || questionType.includes('labeling') || 
                questionType.includes('flow_chart') || questionType.includes('map')) {
                const wordLimit = q.word_limit || q['question_data[word_limit]'] || '';
                const altAnswers = q.alternative_answers || '';
                
                if (wordLimit) {
                    extraDetails += `<div class="font-12 mb-4"><span class="badge badge-secondary">Max ${wordLimit} word(s)</span></div>`;
                }
                if (altAnswers && altAnswers.length > 0) {
                    extraDetails += `<div class="font-11 text-muted mb-4">Alt: ${altAnswers}</div>`;
                }
            }
            
            // Short answer
            if (questionType.includes('short_answer')) {
                const wordLimit = q.word_limit || q['question_data[word_limit]'] || '3';
                extraDetails += `<div class="font-12 mb-4"><span class="badge badge-secondary">Max ${wordLimit} word(s)</span></div>`;
            }
            
            return `
                <div class="question-preview-item mb-12 p-16 bg-white rounded-12 shadow-sm" style="border-left: 4px solid #28c76f;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-fill">
                            <div class="d-flex align-items-center mb-8">
                                <span class="badge badge-success mr-12" style="min-width: 36px;">Q${q.question_number}</span>
                                <span class="badge badge-light text-primary">${q.question_type_label}</span>
                            </div>
                            ${truncatedText ? `
                                <p class="font-13 text-dark mb-8" style="line-height: 1.5;">"${truncatedText}"</p>
                            ` : ''}
                            ${extraDetails}
                            <div class="d-flex align-items-center flex-wrap gap-12">
                                <div class="font-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success mr-4" style="vertical-align: middle;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    <strong>Answer:</strong> ${answerDisplay}
                                </div>
                                ${reference ? `
                                    <div class="font-12 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-4" style="vertical-align: middle;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        ${reference}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-8 ml-12">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-question-btn" data-index="${index}" title="Edit" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-question-btn" data-index="${index}" title="Remove" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        $('#questionsPreviewList').html(listHtml || '<p class="text-gray-500 text-center py-16 mb-0">No questions added yet</p>');
        $('#questionCount').text(questionsArray.length);
        $('#localQuestionCount').text(questionsArray.length + ' in list');
        
        if (questionsArray.length > 0) {
            $('#questionsListCard').slideDown(200);
        } else {
            $('#questionsListCard').slideUp(200);
        }
    }

    // Edit question
    $(document).on('click', '.edit-question-btn', function() {
        const index = $(this).data('index');
        const question = questionsArray[index];
        
        editingIndex = index;
        
        $('#question_number').val(question.question_number);
        $('#questionType').val(question.question_type).trigger('change');
        
        setTimeout(() => {
            Object.keys(question).forEach(key => {
                const input = $(`[name="${key}"]`);
                if (input.length) {
                    if (input.attr('type') === 'checkbox' || input.attr('type') === 'radio') {
                        input.filter(`[value="${question[key]}"]`).prop('checked', true);
                    } else {
                        input.val(question[key]);
                    }
                }
            });
        }, 300);
        
        $('#addQuestionToListBtn').html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8"><polyline points="20 6 9 17 4 12"></polyline></svg>Update Question');
        
        $('html, body').animate({
            scrollTop: $('#questionForm').offset().top - 100
        }, 300);
    });

    // Delete question
    $(document).on('click', '.delete-question-btn', function() {
        const index = $(this).data('index');
        questionsArray.splice(index, 1);
        updateQuestionsList();
        notify('info', 'Question removed');
    });

    // Save all questions
    $('#saveAllQuestionsBtn').on('click', function() {
        if (questionsArray.length === 0) {
            notify('warning', 'Please add at least one question');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-8"></span>Saving...');

        $.ajax({
            url: '{{ route("panel.questions.store", $group->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                questions: questionsArray
            },
            success: function(response) {
                if (response.success) {
                    notify('success', 'All questions saved successfully!');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    notify('error', response.message || 'Error saving questions');
                    btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"></path><path d="M12 13v4"></path><path d="m10 15 2-2 2 2"></path></svg>Save All');
                }
            },
            error: function(xhr) {
                notify('error', xhr.responseJSON?.message || 'Error saving questions');
                btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"></path><path d="M12 13v4"></path><path d="m10 15 2-2 2 2"></path></svg>Save All');
            }
        });
    });
});
</script>

<style>
.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
}
.gap-4 { gap: 4px; }
.gap-8 { gap: 8px; }
.gap-12 { gap: 12px; }
.bg-gray-50 { background-color: #f9fafb; }
.question-type-form { animation: fadeIn 0.2s ease; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush