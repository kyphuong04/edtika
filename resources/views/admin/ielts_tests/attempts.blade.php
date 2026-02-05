@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">{{ trans('update.ielts_tests') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.ielts_grading') }}</div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.ielts_total_completed') }}</span>
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
                            <span class="text-gray-500 mt-8">{{ trans('update.ielts_pending_grading') }}</span>
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
                            <span class="text-gray-500 mt-8">{{ trans('update.ielts_writing') }}</span>
                            <div class="d-flex-center size-48 rounded-12" style="background-color: rgba(139, 92, 246, 0.2);">
                                <x-iconsax-bul-edit-2 class="icons" style="color: #8b5cf6;" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ trans('update.ielts_manual_grade') }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.ielts_speaking') }}</span>
                            <div class="d-flex-center size-48 rounded-12" style="background-color: rgba(16, 185, 129, 0.2);">
                                <x-iconsax-bul-microphone class="icons" style="color: #10b981;" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ trans('update.ielts_manual_grade') }}</h5>
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
                                <label class="input-label">{{ trans('admin/main.search_student') }}</label>
                                <input type="text" class="form-control" name="search" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_by_name_email') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.ielts_test') }}</label>
                                <select name="test_id" class="form-control">
                                    <option value="">{{ trans('update.ielts_all_tests') }}</option>
                                    @foreach($tests as $id => $title)
                                        <option value="{{ $id }}" {{ request()->get('test_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.ielts_grading_status') }}</label>
                                <select name="grading_status" class="form-control">
                                    <option value="">{{ trans('update.ielts_all') }}</option>
                                    <option value="pending" {{ request()->get('grading_status') == 'pending' ? 'selected' : '' }}>{{ trans('update.ielts_pending_grading') }}</option>
                                    <option value="graded" {{ request()->get('grading_status') == 'graded' ? 'selected' : '' }}>{{ trans('update.ielts_fully_graded') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mt-1">
                                <label class="input-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <x-iconsax-lin-filter-search width="18" height="18" class="mr-1"/>
                                    {{ trans('admin/main.filter') }}
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
                                    <th>{{ trans('admin/main.student') }}</th>
                                    <th>{{ trans('update.ielts_test') }}</th>
                                    <th>{{ trans('update.completed') }}</th>
                                    <th class="text-center">{{ trans('update.L') }}</th>
                                    <th class="text-center">{{ trans('update.R') }}</th>
                                    <th class="text-center">{{ trans('update.W') }}</th>
                                    <th class="text-center">{{ trans('update.S') }}</th>
                                    <th class="text-center">{{ trans('update.ielts_overall') }}</th>
                                    <th class="text-center">{{ trans('admin/main.status') }}</th>
                                    <th class="text-center">{{ trans('admin/main.actions') }}</th>
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
                                                <span class="badge badge-pill badge-warning">{{ trans('admin/main.pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->speaking_score)
                                                <span class="badge badge-pill" style="background-color: #10b981; color: white;">
                                                    {{ number_format($attempt->speaking_score, 1) }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-warning">{{ trans('admin/main.pending') }}</span>
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
                                                    {{ trans('update.ielts_needs_grading') }}
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <x-iconsax-lin-tick-circle width="14" height="14" class="mr-1"/>
                                                    {{ trans('update.complete') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.ielts_tests.view_attempt', $attempt->id) }}" 
                                               class="btn btn-sm {{ $hasUngradedWS ? 'btn-primary' : 'btn-outline-primary' }}"
                                               title="{{ $hasUngradedWS ? trans('update.grade_now') : trans('update.view_details') }}">
                                                @if($hasUngradedWS)
                                                    <x-iconsax-lin-edit width="16" height="16" class="mr-1"/>
                                                    {{ trans('update.grade') }}
                                                @else
                                                    <x-iconsax-lin-eye width="16" height="16" class="mr-1"/>
                                                    {{ trans('update.view') }}
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <x-iconsax-bul-document-text width="48" height="48" class="text-muted mb-3"/>
                                            <p class="text-muted mb-0">{{ trans('update.no_completed_test_attempts_found') }}</p>
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
