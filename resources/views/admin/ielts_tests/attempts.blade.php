@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
                <div class="breadcrumb-item">Grading</div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Total Completed</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-document-text class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $attempts->total() }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Pending Grading</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <x-iconsax-bul-clock class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $pendingCount }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Writing</span>
                            <div class="d-flex-center size-48 rounded-12" style="background-color: rgba(139, 92, 246, 0.2);">
                                <x-iconsax-bul-edit-2 class="icons" style="color: #8b5cf6;" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">Manual Grade</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Speaking</span>
                            <div class="d-flex-center size-48 rounded-12" style="background-color: rgba(16, 185, 129, 0.2);">
                                <x-iconsax-bul-microphone class="icons" style="color: #10b981;" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">Manual Grade</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    {{-- Filters --}}
                    <form action="{{ route('admin.ielts_tests.attempts') }}" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">Search Student</label>
                                <input type="text" class="form-control" name="search" value="{{ request()->get('search') }}" placeholder="Name or email...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">Test</label>
                                <select name="test_id" class="form-control">
                                    <option value="">All Tests</option>
                                    @foreach($tests as $id => $title)
                                        <option value="{{ $id }}" {{ request()->get('test_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">Grading Status</label>
                                <select name="grading_status" class="form-control">
                                    <option value="">All</option>
                                    <option value="pending" {{ request()->get('grading_status') == 'pending' ? 'selected' : '' }}>Pending Grading</option>
                                    <option value="graded" {{ request()->get('grading_status') == 'graded' ? 'selected' : '' }}>Fully Graded</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mt-1">
                                <label class="input-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <x-iconsax-lin-filter-search width="18" height="18" class="mr-1"/>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            
            {{-- Attempts Table --}}
            <section class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Test</th>
                                    <th>Completed</th>
                                    <th class="text-center">L</th>
                                    <th class="text-center">R</th>
                                    <th class="text-center">W</th>
                                    <th class="text-center">S</th>
                                    <th class="text-center">Overall</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $attempt)
                                    @php
                                        // Check if has ungraded W/S answers
                                        $hasUngradedWS = $attempt->answers->filter(function($a) {
                                            $skill = $a->question->section->skill ?? '';
                                            return in_array($skill, ['writing', 'speaking']) && !$a->graded_at;
                                        })->count() > 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $attempt->user->getAvatar(36) }}" class="rounded-circle mr-2" width="36" height="36" alt="">
                                                <div>
                                                    <div class="font-weight-bold">{{ $attempt->user->full_name }}</div>
                                                    <small class="text-muted">{{ $attempt->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-500">{{ $attempt->test->title }}</span>
                                            <br>
                                            <span class="badge badge-{{ $attempt->test->type == 'mock' ? 'primary' : 'info' }}">
                                                {{ ucfirst($attempt->test->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $attempt->completed_at ? date('d/m/Y H:i', $attempt->completed_at) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->listening_score)
                                                <span class="badge badge-pill" style="background-color: #1a3a5c; color: white;">
                                                    {{ number_format($attempt->listening_score, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->reading_score)
                                                <span class="badge badge-pill" style="background-color: #3b82f6; color: white;">
                                                    {{ number_format($attempt->reading_score, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->writing_score)
                                                <span class="badge badge-pill" style="background-color: #8b5cf6; color: white;">
                                                    {{ number_format($attempt->writing_score, 1) }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->speaking_score)
                                                <span class="badge badge-pill" style="background-color: #10b981; color: white;">
                                                    {{ number_format($attempt->speaking_score, 1) }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->overall_band)
                                                <span class="badge badge-pill badge-dark font-16 px-3 py-2">
                                                    {{ number_format($attempt->overall_band, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($hasUngradedWS)
                                                <span class="badge badge-warning">
                                                    <x-iconsax-lin-clock width="14" height="14" class="mr-1"/>
                                                    Needs Grading
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <x-iconsax-lin-tick-circle width="14" height="14" class="mr-1"/>
                                                    Complete
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.ielts_tests.view_attempt', $attempt->id) }}" 
                                               class="btn btn-sm {{ $hasUngradedWS ? 'btn-primary' : 'btn-outline-primary' }}"
                                               title="{{ $hasUngradedWS ? 'Grade Now' : 'View Details' }}">
                                                @if($hasUngradedWS)
                                                    <x-iconsax-lin-edit width="16" height="16" class="mr-1"/>
                                                    Grade
                                                @else
                                                    <x-iconsax-lin-eye width="16" height="16" class="mr-1"/>
                                                    View
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <x-iconsax-bul-document-text width="48" height="48" class="text-muted mb-3"/>
                                            <p class="text-muted mb-0">No completed test attempts found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            {{-- Pagination --}}
            <div class="mt-3">
                {{ $attempts->appends(request()->query())->links('vendor.pagination.panel') }}
            </div>
        </div>
    </section>
@endsection
