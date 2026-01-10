@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Questions</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.sections', $section->test_id) }}">Sections</a></div>
            <div class="breadcrumb-item">Questions</div>
        </div>
    </div>

    <div class="section-body">
        {{-- Instructions Alert --}}
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>IELTS Question Structure:</strong> Each section contains multiple <strong>Question Groups</strong>. 
            Each group has a specific question type (e.g., Multiple Choice, True/False/Not Given) and shares common content (passage, audio, instructions).
        </div>

        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h4 class="mb-0">{{ $section->title }}</h4>
                    <p class="text-gray mb-0 mt-1">
                        <span class="badge badge-{{
                            $section->skill === 'listening' ? 'info' :
                            ($section->skill === 'reading' ? 'success' :
                            ($section->skill === 'writing' ? 'warning' : 'danger'))
                        }}">{{ ucfirst($section->skill) }}</span>
                        <span class="ml-2">Questions {{ $section->question_start }} - {{ $section->question_end }}</span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.ielts_tests.sections', $section->test_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Sections
                    </a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionGroupModal">
                        <i class="fas fa-layer-group mr-2"></i>
                        Add Question Group
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($section->questions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-3x text-gray mb-3"></i>
                        <h5>No questions yet</h5>
                        <p class="text-gray">Add your first question to this section</p>
                    </div>
                @else
                    @foreach($section->questions->sortBy('question_number') as $question)
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="mb-0 mr-3">Question {{ $question->question_number }}</h5>
                                        <span class="badge badge-{{ 
                                            $question->question_type === 'multiple_choice' ? 'primary' : 
                                            ($question->question_type === 'fill_blank' ? 'success' : 
                                            ($question->question_type === 'essay' ? 'warning' : 'info')) 
                                        }}">
                                            {{ ucwords(str_replace('_', ' ', $question->question_type)) }}
                                        </span>
                                        @if($question->auto_gradable)
                                            <span class="badge badge-success ml-2">Auto-Grade</span>
                                        @else
                                            <span class="badge badge-warning ml-2">Manual</span>
                                        @endif
                                        <span class="badge badge-light ml-2">{{ $question->points }} pts</span>
                                    </div>
                                    
                                    <p class="mb-2">{{ Str::limit($question->question_text, 150) }}</p>
                                    
                                    @if($question->instruction)
                                        <small class="text-gray d-block mb-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            {{ Str::limit($question->instruction, 100) }}
                                        </small>
                                    @endif

                                    @if($question->question_type === 'multiple_choice' && $question->answer_options)
                                        <div class="mt-2">
                                            <small class="text-gray">Options:</small>
                                            <ul class="mb-0 mt-1">
                                                @foreach(json_decode($question->answer_options) as $option)
                                                    <li class="small {{ $option == $question->correct_answer ? 'text-success font-weight-bold' : '' }}">
                                                        {{ $option }}
                                                        @if($option == $question->correct_answer)
                                                            <i class="fas fa-check text-success ml-1"></i>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @elseif($question->auto_gradable)
                                        <div class="mt-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Correct: <strong>{{ $question->correct_answer }}</strong>
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="btn-group ml-3">
                                    <button class="btn btn-sm btn-warning" onclick="editQuestion({{ $question->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.ielts_tests.questions.delete', $question->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this question?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Add Question Modal --}}
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Question</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.ielts_tests.questions.store', $section->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question Number *</label>
                                <input type="number" name="question_number" class="form-control" required 
                                       min="{{ $section->question_start }}" max="{{ $section->question_end }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question Type *</label>
                                <select name="question_type" class="form-control" id="questionType" required>
                                    <option value="">Select type...</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="fill_blank">Fill in the Blank</option>
                                    <option value="true_false">True/False</option>
                                    <option value="matching">Matching</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Points *</label>
                                <input type="number" name="points" class="form-control" value="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Question Text *</label>
                        <textarea name="question_text" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Instructions (Optional)</label>
                        <textarea name="instruction" class="form-control" rows="2"></textarea>
                    </div>

                    <div id="multipleChoiceOptions" style="display: none;">
                        <h6 class="mb-3">Answer Options</h6>
                        <div class="form-group">
                            <label>Option A</label>
                            <input type="text" name="option_a" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Option B</label>
                            <input type="text" name="option_b" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Option C</label>
                            <input type="text" name="option_c" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Option D</label>
                            <input type="text" name="option_d" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correct Answer</label>
                                <input type="text" name="correct_answer" class="form-control">
                                <small class="text-gray">For auto-gradable questions only</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Accept Synonyms (comma-separated)</label>
                                <input type="text" name="accept_synonyms" class="form-control">
                                <small class="text-gray">e.g., happy, glad, joyful</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox mt-3">
                                <input type="checkbox" name="auto_gradable" class="custom-control-input" id="autoGrade" value="1">
                                <label class="custom-control-label" for="autoGrade">Auto-gradable</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox mt-3">
                                <input type="checkbox" name="case_sensitive" class="custom-control-input" id="caseSensitive" value="1">
                                <label class="custom-control-label" for="caseSensitive">Case sensitive</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Explanation (for practice tests)</label>
                        <textarea name="explanation" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Max Words (for text answers)</label>
                        <input type="number" name="max_words" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
document.getElementById('questionType').addEventListener('change', function() {
    const optionsDiv = document.getElementById('multipleChoiceOptions');
    if (this.value === 'multiple_choice') {
        optionsDiv.style.display = 'block';
    } else {
        optionsDiv.style.display = 'none';
    }
});

function editQuestion(questionId) {
    alert('Edit functionality coming soon. Use delete and re-create for now.');
}
</script>
@endpush
