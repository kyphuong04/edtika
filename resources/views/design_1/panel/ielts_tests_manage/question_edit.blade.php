@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    #multipleChoiceOptions {
        animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h1 class="section-title">Edit Question</h1>
            <p class="text-gray">{{ $section->title }} (Q{{ $section->question_start }} - Q{{ $section->question_end }})</p>
        </div>
        <a href="{{ route('panel.my_ielts_tests.questions', [$test->id, $section->id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-5"></i>Back to Questions
        </a>
    </div>

    <form action="{{ route('panel.my_ielts_tests.questions.update', [$test->id, $section->id, $question->id]) }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                <h5 class="mb-20">Question Information</h5>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Question Number *</label>
                            <input type="number" name="question_number" class="form-control @error('question_number') is-invalid @enderror" 
                                   value="{{ old('question_number', $question->question_number) }}" required 
                                   min="{{ $section->question_start }}" max="{{ $section->question_end }}">
                            <small class="text-muted">Between {{ $section->question_start }} and {{ $section->question_end }}</small>
                            @error('question_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Question Type *</label>
                            @if(in_array($section->skill, ['writing','speaking']))
                                <input type="text" class="form-control" value="{{ ucfirst($section->skill) }}" disabled>
                                <input type="hidden" name="question_type" value="essay">
                            @else
                                <select name="question_type" class="form-control" id="questionType" required>
                                    <option value="">Select type...</option>
                                    <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                    <option value="fill_blank" {{ old('question_type', $question->question_type) === 'fill_blank' ? 'selected' : '' }}>Fill in the Blank</option>
                                    <option value="true_false" {{ old('question_type', $question->question_type) === 'true_false' ? 'selected' : '' }}>True/False</option>
                                    <option value="matching" {{ old('question_type', $question->question_type) === 'matching' ? 'selected' : '' }}>Matching</option>
                                    <option value="short_answer" {{ old('question_type', $question->question_type) === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                    <option value="essay" {{ old('question_type', $question->question_type) === 'essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="table_completion" {{ old('question_type', $question->question_type) === 'table_completion' ? 'selected' : '' }}>Table Completion</option>
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Points *</label>
                            <input type="number" name="points" class="form-control" value="{{ old('points', $question->points ?? 0) }}" required min="0" step="0.025">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Question Text *</label>
                    <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror" 
                              rows="4" required placeholder="Enter the question...">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Instructions (Optional)</label>
                    <textarea name="instruction" class="form-control" rows="2" 
                              placeholder="Special instructions for this question...">{{ old('instruction', $question->instruction) }}</textarea>
                </div>

                @if(in_array($section->skill, ['writing','speaking']))
                    <div class="form-group">
                        <label>Task Image URL (optional)</label>
                        <input type="text" name="task_image_url" class="form-control" value="{{ old('task_image_url', optional($question->question_data)['task_image'] ?? '') }}" placeholder="https://example.com/image.jpg">
                        <small class="text-muted">Optional image to appear with the task prompt.</small>
                    </div>

                    <div class="form-group">
                        <label>Model Answer (visible to students after review)</label>
                        <textarea name="explanation" class="form-control" rows="6" placeholder="Enter model answer or sample response...">{{ old('explanation', $question->explanation) }}</textarea>
                    </div>
                @endif

                <div id="multipleChoiceOptions" style="display: none;">
                    <h6 class="mb-15">Answer Options</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option A</label>
                                <input type="text" name="option_a" class="form-control" value="{{ old('option_a') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option B</label>
                                <input type="text" name="option_b" class="form-control" value="{{ old('option_b') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option C</label>
                                <input type="text" name="option_c" class="form-control" value="{{ old('option_c') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option D</label>
                                <input type="text" name="option_d" class="form-control" value="{{ old('option_d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Correct Answer</label>
                            <input type="text" name="correct_answer" class="form-control" value="{{ old('correct_answer', $question->correct_answer) }}">
                            <small class="text-muted">For auto-gradable questions only</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Accept Synonyms (comma-separated)</label>
                            <input type="text" name="accept_synonyms" class="form-control" value="{{ old('accept_synonyms', $question->accept_synonyms) }}" 
                                   placeholder="e.g., happy, glad, joyful">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox mt-15">
                            <input type="checkbox" name="auto_gradable" class="custom-control-input" id="autoGrade" value="1" {{ old('auto_gradable', $question->auto_gradable) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="autoGrade">Auto-gradable</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox mt-15">
                            <input type="checkbox" name="case_sensitive" class="custom-control-input" id="caseSensitive" value="1" {{ old('case_sensitive', $question->case_sensitive) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="caseSensitive">Case sensitive</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Max Words (for text answers)</label>
                            <input type="number" name="max_words" class="form-control" value="{{ old('max_words', $question->max_words) }}" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Explanation (for practice tests)</label>
                    <textarea name="explanation" class="form-control" rows="3" 
                              placeholder="Explain the correct answer...">{{ old('explanation', $question->explanation) }}</textarea>
                </div>

                <input type="hidden" name="table_structure" id="table_structure_input" value='{{ old('table_structure', json_encode($question->table_structure ?? null)) }}'>

                <div id="tableBuilderWrapper" style="display: none;" class="mt-3">
                    <label>Table Builder</label>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="openTableBuilder">Open Table Builder</button>
                        <small class="text-muted ml-2">Use '___' in a cell to mark it as a blank input for students.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end mt-20">
            <a href="{{ route('panel.my_ielts_tests.questions', [$test->id, $section->id]) }}" class="btn btn-secondary mr-10">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-5"></i>Update Question
            </button>
        </div>
    </form>
</section>
@endsection

@push('scripts_bottom')
<script>
var qTypeEl = document.getElementById('questionType');
if (qTypeEl) {
    qTypeEl.addEventListener('change', function() {
        const optionsDiv = document.getElementById('multipleChoiceOptions');
        if (this.value === 'multiple_choice') {
            optionsDiv.style.display = 'block';
        } else {
            optionsDiv.style.display = 'none';
        }
    });

    // On load
    if (qTypeEl.value === 'multiple_choice') {
        const optionsDiv = document.getElementById('multipleChoiceOptions');
        if (optionsDiv) optionsDiv.style.display = 'block';
    }
}
</script>
<div class="modal fade" id="tableBuilderModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Table Builder</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addRow">Add Row</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addCol">Add Column</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clearTable">Clear</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="builderTable">
                        <tbody></tbody>
                    </table>
                </div>
                <p class="mt-2 text-muted">Mark a cell as blank by typing <strong>___</strong> into it. Cells without ___ are static content.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTable">Save Table</button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle table builder wrapper when type changes
if (qTypeEl) {
    qTypeEl.addEventListener('change', function() {
        const wrapper = document.getElementById('tableBuilderWrapper');
        if (this.value === 'table_completion') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    });
}

// Open builder and populate if existing
document.getElementById('openTableBuilder').addEventListener('click', function() {
    const raw = document.getElementById('table_structure_input').value;
    let data = null;
    try { data = raw ? JSON.parse(raw) : null; } catch(e) { data = null; }
    renderBuilderTable(data);
    $('#tableBuilderModal').modal('show');
});

function renderBuilderTable(data) {
    const tbody = document.querySelector('#builderTable tbody');
    tbody.innerHTML = '';
    if (!data || !Array.isArray(data.rows) || data.rows.length === 0) {
        // default 3x2
        data = { cols: ['Col 1','Col 2'], rows: [['',''],['',''],['','']] };
    }
    const answersMap = {};
    if (data.answers && Array.isArray(data.answers)) {
        data.answers.forEach(a => answersMap[`${a.row}-${a.col}`] = a.answer);
    }

    data.rows.forEach((row, rIdx) => {
        const tr = document.createElement('tr');
        row.forEach((cell, cIdx) => {
            const td = document.createElement('td');
            td.contentEditable = true;
            td.innerText = cell ?? '';
            // attach expected answer if exists
            const key = `${rIdx}-${cIdx}`;
            if (answersMap[key]) {
                td.dataset.expected = answersMap[key];
                td.title = 'Expected: ' + answersMap[key];
            }

            // double click to set expected answer for blank cells
            td.addEventListener('dblclick', function() {
                if (td.innerText.indexOf('___') === -1) {
                    if (!confirm('This cell is not marked as blank (___). Convert it to blank and set expected answer?')) return;
                    td.innerText = '___';
                }
                const a = prompt('Expected answer for this blank (leave empty to unset):', td.dataset.expected || '');
                if (a === null) return; // canceled
                if (a.trim() === '') {
                    delete td.dataset.expected;
                    td.title = '';
                } else {
                    td.dataset.expected = a.trim();
                    td.title = 'Expected: ' + a.trim();
                }
            });

            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });
}

document.getElementById('addRow').addEventListener('click', function(){
    const tbody = document.querySelector('#builderTable tbody');
    const cols = tbody.querySelector('tr') ? tbody.querySelectorAll('tr:first-child td').length : 2;
    const tr = document.createElement('tr');
    for (let i=0;i<cols;i++){ const td = document.createElement('td'); td.contentEditable = true; td.innerText=''; tr.appendChild(td); }
    tbody.appendChild(tr);
});

document.getElementById('addCol').addEventListener('click', function(){
    const rows = document.querySelectorAll('#builderTable tbody tr');
    rows.forEach(r=>{ const td = document.createElement('td'); td.contentEditable = true; td.innerText=''; r.appendChild(td); });
});

document.getElementById('clearTable').addEventListener('click', function(){
    document.querySelector('#builderTable tbody').innerHTML = '';
});

document.getElementById('saveTable').addEventListener('click', function(){
    const rows = [];
    const answers = [];
    document.querySelectorAll('#builderTable tbody tr').forEach((tr, rIdx)=>{
        const cells = [];
        tr.querySelectorAll('td').forEach((td, cIdx)=>{
            const text = td.innerText.trim();
            cells.push(text);
            if (text.indexOf('___') !== -1 && td.dataset.expected) {
                answers.push({ row: rIdx, col: cIdx, answer: td.dataset.expected });
            }
        });
        rows.push(cells);
    });
    const payload = { rows, answers };
    document.getElementById('table_structure_input').value = JSON.stringify(payload);
    $('#tableBuilderModal').modal('hide');
});

// initialize visibility on load
if (qTypeEl && qTypeEl.value === 'table_completion') {
    const wrapper = document.getElementById('tableBuilderWrapper');
    if (wrapper) wrapper.style.display = 'block';
}
</script>
@endpush
