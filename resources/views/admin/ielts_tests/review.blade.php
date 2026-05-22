@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Review Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.pending_approval') }}">Pending Approval</a></div>
            <div class="breadcrumb-item">Review</div>
        </div>
    </div>

    <div class="section-body">

        {{-- Top action bar --}}
        <div class="card mb-20" style="border-left: 4px solid #f59e0b;">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                <div>
                    <h4 class="mb-4">{{ $test->title }}</h4>
                    <div class="d-flex align-items-center" style="gap:8px;flex-wrap:wrap;">
                        <span class="badge badge-warning" style="font-size:13px;padding:6px 12px;">
                            {{ ucwords(str_replace('_', ' ', $test->status)) }}
                        </span>
                        <span class="badge badge-{{ $test->type === 'mock' ? 'primary' : 'success' }}" style="font-size:13px;padding:6px 12px;">
                            {{ ucfirst($test->type) }}
                        </span>
                        <span class="badge badge-secondary" style="font-size:13px;padding:6px 12px;">
                            {{ ucfirst($test->format) }}
                        </span>
                        <span class="font-13 font-weight-600" style="color:#1e293b;">
                            <i class="fas fa-user mr-4" style="color:#64748b;"></i>{{ $test->creator->full_name ?? 'Unknown' }}
                        </span>
                        @if($test->submitted_for_approval_at)
                        <span class="font-13 font-weight-600" style="color:#1e293b;">
                            <i class="fas fa-clock mr-4" style="color:#64748b;"></i>Submitted: {{ dateTimeFormat($test->submitted_for_approval_at, 'j M Y, H:i') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="d-flex" style="gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('admin.ielts_tests.sections', $test->id) }}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit mr-4"></i>Manage Sections
                    </a>
                    @if($test->status === 'pending_approval')
                    <form action="{{ route('admin.ielts_tests.approve', $test->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Approve and publish this test?')">
                            <i class="fas fa-check mr-4"></i>Approve & Publish
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm"
                            data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-times mr-4"></i>Reject
                    </button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#managerFeedbackModal">
                        <i class="fas fa-comment-dots mr-4"></i>Send Feedback
                    </button>
                    @endif
                    <a href="{{ route('admin.ielts_tests.pending_approval') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-4"></i>Back
                    </a>
                </div>
            </div>
        </div>

        {{-- Test Metadata --}}
        <div class="row mb-20">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header"><h4>Test Information</h4></div>
                    <div class="card-body">
                        <form action="{{ route('admin.ielts_tests.update', $test->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_force_update" value="1">
                            <div class="form-group">
                                <label class="input-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $test->title }}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $test->description }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">Target Band Min</label>
                                        <select name="target_band_min" class="form-control">
                                            <option value="">—</option>
                                            @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                                <option value="{{ $i }}" {{ $test->target_band_min == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">Target Band Max</label>
                                        <select name="target_band_max" class="form-control">
                                            <option value="">—</option>
                                            @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                                <option value="{{ $i }}" {{ $test->target_band_max == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($test->isPracticeTest())
                            <div class="form-group">
                                <label class="input-label">Practice Category</label>
                                <select name="practice_category_id" class="form-control">
                                    <option value="">Select category...</option>
                                    @foreach($practiceCategories as $skill => $categories)
                                        <optgroup label="{{ ucfirst($skill) }}">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $test->practice_category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save mr-4"></i>Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header"><h4>Summary</h4></div>
                    <div class="card-body">
                        @php
                            $totalSections = $test->sections->count();
                            $totalQuestions = $test->sections->sum(function($s) {
                                $fromParts = $s->parts->sum(function($p) { return $p->questions->count(); });
                                return $fromParts > 0 ? $fromParts : $s->questions->count();
                            });
                            $skills = [];
                            if ($test->has_listening) $skills[] = 'Listening';
                            if ($test->has_reading) $skills[] = 'Reading';
                            if ($test->has_writing) $skills[] = 'Writing';
                            if ($test->has_speaking) $skills[] = 'Speaking';
                        @endphp
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Type</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ ucfirst($test->type) }}</td>
                            </tr>
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Format</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ ucfirst($test->format) }}</td>
                            </tr>
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Skills</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ implode(', ', $skills) }}</td>
                            </tr>
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Sections</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ $totalSections }}</td>
                            </tr>
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Total Questions</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ $totalQuestions }}</td>
                            </tr>
                            @if($test->target_band_min || $test->target_band_max)
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Band Range</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ $test->target_band_min ?? '?' }} – {{ $test->target_band_max ?? '?' }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="font-13 font-weight-600" style="color:#475569;">Creator</td>
                                <td class="font-weight-bold" style="color:#0f172a;">{{ $test->creator->full_name ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($test->feedbacks && $test->feedbacks->isNotEmpty())
        <div class="col-md-12">
            <div class="card mt-20">
                <div class="card-header"><h4>Feedback History</h4></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($test->feedbacks as $fb)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $fb->sender ? $fb->sender->full_name : 'System' }}</strong>
                                    <div class="text-muted small">{{ date('j M Y, H:i', $fb->created_at) }}</div>
                                </div>
                                <div>
                                    <!-- placeholder for actions -->
                                </div>
                            </div>
                            <div class="mt-2">{{ nl2br(e($fb->message)) }}</div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Sections & Questions --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Sections &amp; Questions</h4>
                <a href="{{ route('admin.ielts_tests.sections', $test->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-cog mr-4"></i>Manage Sections
                </a>
            </div>
            <div class="card-body p-0">
                @if($test->sections->isEmpty())
                    <div class="text-center py-5 text-gray">
                        <i class="fas fa-folder-open fa-3x mb-12"></i>
                        <p>No sections added yet.</p>
                    </div>
                @else
                    @foreach($test->sections->sortBy('sort_order') as $section)
                    @php
                        $skillColors = [
                            'listening' => '#1a3a5c',
                            'reading'   => '#3b82f6',
                            'writing'   => '#8b5cf6',
                            'speaking'  => '#10b981',
                        ];
                        $sectionColor = $skillColors[$section->skill] ?? '#6b7280';

                        $hasParts = $section->parts->isNotEmpty();
                        $directQuestions = $section->questions;
                    @endphp
                    <div style="border-top: 1px solid #f0f0f0;">
                        {{-- Section header --}}
                        <div class="d-flex align-items-center justify-content-between px-20 py-14"
                             style="background: #f8fafc; border-left: 4px solid {{ $sectionColor }};">
                            <div class="d-flex align-items-center" style="gap:12px;">
                                <span class="badge" style="background:{{ $sectionColor }};color:#fff;font-size:12px;padding:5px 10px;">
                                    {{ ucfirst($section->skill) }}
                                </span>
                                <div>
                                    <strong class="font-15">{{ $section->title }}</strong>
                                    @if($section->question_start && $section->question_end)
                                        <small class="ml-8 font-weight-600" style="color:#374151;">Q{{ $section->question_start }}–Q{{ $section->question_end }}</small>
                                    @endif
                                    @if($section->duration_minutes)
                                        <small class="ml-8 font-weight-600" style="color:#374151;"><i class="fas fa-clock"></i> {{ $section->duration_minutes }} min</small>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex" style="gap:6px;">
                                <a href="{{ route('admin.ielts_tests.questions', $section->id) }}"
                                   class="btn btn-xs btn-outline-primary">
                                    <i class="fas fa-edit mr-4"></i>Edit Questions
                                </a>
                                <a href="{{ route('admin.ielts_tests.question_groups', $section->id) }}"
                                   class="btn btn-xs btn-outline-secondary">
                                    <i class="fas fa-layer-group mr-4"></i>Question Groups
                                </a>
                            </div>
                        </div>

                        {{-- Section content --}}
                        <div class="px-20 py-16">

                            {{-- Audio / Passage info --}}
                            @if($section->hasAudio() || $section->hasPassage() || $section->hasImage())
                            <div class="mb-12 d-flex" style="gap:8px;flex-wrap:wrap;">
                                @if($section->hasAudio())
                                    <span class="badge badge-info"><i class="fas fa-volume-up mr-4"></i>Audio</span>
                                @endif
                                @if($section->hasImage())
                                    <span class="badge badge-secondary"><i class="fas fa-image mr-4"></i>Image</span>
                                @endif
                                @if($section->hasPassage())
                                    <span class="badge badge-light border"><i class="fas fa-file-alt mr-4"></i>Passage</span>
                                @endif
                            </div>
                            @endif

                            @if($section->instructions)
                                <p class="font-13 mb-12" style="color:#374151;"><em>{{ Str::limit($section->instructions, 200) }}</em></p>
                            @endif

                            {{-- Parts → Groups → Questions --}}
                            @if($hasParts)
                                @foreach($section->parts->sortBy('sort_order') as $part)
                                @php
                                    $partGroupIds = $part->questionGroups->pluck('id');
                                    $partQuestionsCount = $section->questions->filter(fn($q) =>
                                        $q->question_group_id && $partGroupIds->contains($q->question_group_id)
                                        || (!$q->question_group_id && $part->questionGroups->filter(fn($g) =>
                                            $q->question_number >= $g->question_start && $q->question_number <= $g->question_end
                                        )->isNotEmpty())
                                    )->count();
                                @endphp
                                <div class="mb-16" style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                                    <div class="px-16 py-10 font-weight-bold font-14"
                                         style="background:#f0fdf4;border-bottom:1px solid #e5e7eb;">
                                        {{ $part->title ?? 'Part ' . $loop->iteration }}
                                        <small class="ml-8 font-weight-normal" style="color:#475569;">
                                            {{ $part->questionGroups->count() }} group(s) · {{ $partQuestionsCount }} question(s)
                                        </small>
                                    </div>
                                    @foreach($part->questionGroups as $group)
                                    @php
                                        $groupQuestions = $section->questions->filter(function($q) use ($group) {
                                            if ($q->question_group_id) {
                                                return $q->question_group_id === $group->id;
                                            }
                                            return $q->question_number >= $group->question_start && $q->question_number <= $group->question_end;
                                        })->sortBy('question_number');
                                    @endphp
                                    <div class="px-16 py-12" style="border-bottom:1px solid #f3f4f6;">
                                        <div class="d-flex align-items-center justify-content-between mb-8">
                                            <div>
                                                <span class="badge badge-light border font-11" style="margin-right:6px;">
                                                    {{ ucwords(str_replace('_', ' ', $group->question_type ?? 'unknown')) }}
                                                </span>
                                                <strong class="font-13">{{ $group->title }}</strong>
                                            </div>
                                            <small class="font-weight-600" style="color:#475569;">{{ $groupQuestions->count() }} questions</small>
                                        </div>
                                        @if($groupQuestions->isNotEmpty())
                                        <div style="padding-left:12px;">
                                            @foreach($groupQuestions as $q)
                                            <div class="py-6" style="border-bottom:1px dashed #f3f4f6;">
                                                <span class="badge badge-light" style="font-size:10px;">Q{{ $q->question_number ?? $loop->iteration }}</span>
                                                <span class="font-13 ml-6" style="color:#1e293b;">{{ Str::limit($q->question_text, 120) }}</span>
                                                <a href="#" class="btn btn-xs btn-outline-secondary ml-3 edit-question-btn" data-question='@json($q)'>
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                @if($q->correct_answer)
                                                    <span class="badge badge-success ml-8" style="font-size:10px;">
                                                        ✓ {{ Str::limit(is_array($q->correct_answer) ? implode(', ', $q->correct_answer) : $q->correct_answer, 40) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                            <p class="font-12 mb-0 pl-12" style="color:#475569;">No questions added yet. <a href="{{ route('admin.ielts_tests.question_groups.questions', $group->id) }}">Add questions</a></p>
                                        @endif
                                    </div>
                                    @endforeach

                                    {{-- Direct part questions (not in groups) --}}
                                    @if($part->questions->isNotEmpty() && $part->questionGroups->isEmpty())
                                    <div class="px-16 py-12">
                                        @foreach($part->questions->sortBy('sort_order') as $q)
                                        <div class="py-6" style="border-bottom:1px dashed #f3f4f6;">
                                            <span class="badge badge-light" style="font-size:10px;">Q{{ $q->question_number ?? $loop->iteration }}</span>
                                            <span class="font-13 ml-6" style="color:#1e293b;">{{ Str::limit($q->question_text, 120) }}</span>
                                            <a href="#" class="btn btn-xs btn-outline-secondary ml-3 edit-question-btn" data-question='@json($q)'>
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if($q->correct_answer)
                                                <span class="badge badge-success ml-8" style="font-size:10px;">
                                                    ✓ {{ Str::limit(is_array($q->correct_answer) ? implode(', ', $q->correct_answer) : $q->correct_answer, 40) }}
                                                </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach

                            {{-- Direct section questions (no parts) --}}
                            @elseif($directQuestions->isNotEmpty())
                                <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                                    @foreach($directQuestions->sortBy('sort_order') as $q)
                                    <div class="px-16 py-8" style="border-bottom:1px dashed #f3f4f6;">
                                        <span class="badge badge-light" style="font-size:10px;">Q{{ $q->question_number ?? $loop->iteration }}</span>
                                        <span class="badge badge-light border font-10 ml-6">
                                            {{ ucwords(str_replace('_', ' ', $q->question_type)) }}
                                        </span>
                                        <span class="font-13 ml-6" style="color:#1e293b;">{{ Str::limit($q->question_text, 120) }}</span>
                                        <a href="#" class="btn btn-xs btn-outline-secondary ml-3 edit-question-btn" data-question='@json($q)'>
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($q->correct_answer)
                                            <span class="badge badge-success ml-8" style="font-size:10px;">
                                                ✓ {{ Str::limit(is_array($q->correct_answer) ? implode(', ', $q->correct_answer) : $q->correct_answer, 40) }}
                                            </span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="font-13 mb-0" style="color:#475569;">No questions added to this section yet.</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Bottom approve/reject --}}
        @if($test->status === 'pending_approval')
        <div class="card mt-20" style="border-left: 4px solid #10b981;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-4">Ready to make a decision?</h5>
                    <p class="mb-0 font-13 font-weight-500" style="color:#374151;">Approving will immediately publish the test for students.</p>
                </div>
                <div class="d-flex" style="gap:10px;">
                    <form action="{{ route('admin.ielts_tests.approve', $test->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Approve and publish this test?')">
                            <i class="fas fa-check mr-6"></i>Approve &amp; Publish
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-times mr-6"></i>Reject
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Manager Feedback Modal --}}
<div class="modal fade" id="managerFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Feedback to Creator</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.ielts_tests.manager_feedback', $test->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Feedback</label>
                        <textarea name="feedback" class="form-control" rows="6" required placeholder="Give constructive feedback for the creator..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Send Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Question Modal (reused for all questions) --}}
<div class="modal fade" id="editQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Question</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editQuestionForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Q#</label>
                            <input type="number" name="question_number" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Type</label>
                            <input type="text" name="question_type" class="form-control" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Points</label>
                            <input type="number" step="0.1" name="points" class="form-control">
                        </div>
                        <div class="form-group col-md-4 form-check pt-4">
                            <input type="checkbox" name="auto_gradable" class="form-check-input" id="auto_gradable_chk">
                            <label class="form-check-label" for="auto_gradable_chk">Auto gradable</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea name="question_text" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Instruction</label>
                        <input type="text" name="instruction" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Correct Answer</label>
                        <input type="text" name="correct_answer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Hint</label>
                        <input type="text" name="hint" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Explanation</label>
                        <textarea name="explanation" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts_bottom')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-question-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var q = JSON.parse(this.getAttribute('data-question'));
                var form = document.getElementById('editQuestionForm');
                // set action URL
                form.action = '/admin/ielts-tests/questions/' + q.id + '/update';
                form.querySelector('[name=question_number]').value = q.question_number || '';
                form.querySelector('[name=question_type]').value = q.question_type || '';
                form.querySelector('[name=points]').value = q.points || '';
                form.querySelector('[name=question_text]').value = q.question_text || '';
                form.querySelector('[name=instruction]').value = q.instruction || '';
                form.querySelector('[name=correct_answer]').value = (Array.isArray(q.correct_answer) ? q.correct_answer.join(',') : (q.correct_answer || ''));
                form.querySelector('[name=hint]').value = q.hint || '';
                form.querySelector('[name=explanation]').value = q.explanation || '';
                form.querySelector('[name=auto_gradable]').checked = q.auto_gradable == 1;
                $('#editQuestionModal').modal('show');
            });
        });
    });
</script>
@endpush

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#dc3545 0%,#fd7e14 100%);color:white;border:none;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-8"></i>Reject Test</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:white;opacity:1;">&times;</button>
            </div>
            <form action="{{ route('admin.ielts_tests.reject', $test->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:24px;">
                    <p class="mb-4"><strong>Test:</strong> {{ $test->title }}</p>
                    <p class="text-gray font-13 mb-12">Please provide a clear reason so the creator can revise and resubmit.</p>
                    <textarea name="rejection_reason" class="form-control" rows="4" required
                              placeholder="Describe what needs to be fixed..."></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-6"></i>Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
