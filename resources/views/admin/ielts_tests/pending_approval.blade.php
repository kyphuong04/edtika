@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pending Approval</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">Pending Approval</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Tests Awaiting Approval</h4>
                <div class="card-header-action">
                    <span class="badge badge-warning">{{ $tests->count() }} Tests</span>
                </div>
            </div>
            <div class="card-body">
                @if($tests->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>All caught up!</h5>
                        <p class="text-gray">No tests pending approval</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Type</th>
                                    <th>Created By</th>
                                    <th>Submitted</th>
                                    <th>Sections</th>
                                    <th>Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tests as $test)
                                <tr>
                                    <td>
                                        <strong>{{ $test->title }}</strong>
                                        <small class="d-block text-gray">{{ Str::limit($test->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $test->type === 'mock' ? 'primary' : 'info' }}">
                                            {{ ucfirst($test->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $test->creator->full_name ?? 'Unknown' }}</td>
                                    <td>{{ dateTimeFormat($test->updated_at, 'j M Y, H:i') }}</td>
                                    <td>{{ $test->sections->count() }} sections</td>
                                    <td>
                                        {{ $test->sections->sum(function($s) { return $s->questions->count(); }) }} questions
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                                    data-target="#reviewModal{{ $test->id }}">
                                                <i class="fas fa-eye mr-1"></i>
                                                Review
                                            </button>
                                            
                                            <form action="{{ route('admin.ielts_tests.approve', $test->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" 
                                                    data-target="#rejectModal{{ $test->id }}">
                                                <i class="fas fa-times mr-1"></i>
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Review Modal --}}
                                <div class="modal fade" id="reviewModal{{ $test->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Review: {{ $test->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Type:</strong> {{ ucfirst($test->type) }}<br>
                                                        <strong>Format:</strong> {{ ucfirst($test->format) }}<br>
                                                        <strong>Duration:</strong> {{ $test->total_duration }} minutes
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Skills:</strong><br>
                                                        @if($test->has_listening) <span class="badge badge-info">Listening</span> @endif
                                                        @if($test->has_reading) <span class="badge badge-success">Reading</span> @endif
                                                        @if($test->has_writing) <span class="badge badge-warning">Writing</span> @endif
                                                        @if($test->has_speaking) <span class="badge badge-danger">Speaking</span> @endif
                                                    </div>
                                                </div>

                                                <h6 class="mt-3">Description:</h6>
                                                <p>{{ $test->description }}</p>

                                                <h6 class="mt-3">Sections ({{ $test->sections->count() }}):</h6>
                                                <ul>
                                                    @foreach($test->sections as $section)
                                                        <li>
                                                            {{ $section->title }} - {{ ucfirst($section->skill) }}
                                                            ({{ $section->questions->count() }} questions)
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                @if($test->isMockTest())
                                                    @php
                                                        $validation = $test->validateMockTestStructure();
                                                    @endphp
                                                    
                                                    @if($validation['valid'])
                                                        <div class="alert alert-success">
                                                            <i class="fas fa-check-circle mr-2"></i>
                                                            Mock test structure is valid
                                                        </div>
                                                    @else
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            <strong>Validation Issues:</strong>
                                                            <ul class="mb-0 mt-2">
                                                                @foreach($validation['errors'] as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Reject Modal --}}
                                <div class="modal fade" id="rejectModal{{ $test->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Test</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{ route('admin.ielts_tests.reject', $test->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Please provide a reason for rejecting this test:</p>
                                                    <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Test</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
