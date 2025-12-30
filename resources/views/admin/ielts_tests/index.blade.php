@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>IELTS Tests</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">IELTS Tests</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Total Tests</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-clipboard-text class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $tests->total() }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Mock Tests</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-document-text class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $tests->where('type', 'mock')->count() }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Practice Tests</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-note-text class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $tests->where('type', 'practice')->count() }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Pending Approval</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <x-iconsax-bul-clock class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $tests->where('status', 'pending_approval')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form action="{{ route('admin.ielts_tests.index') }}" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input type="text" class="form-control" name="search" value="{{ request()->get('search') }}" placeholder="Search test title...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="mock" {{ request()->get('type') == 'mock' ? 'selected' : '' }}>Mock Test</option>
                                    <option value="practice" {{ request()->get('type') == 'practice' ? 'selected' : '' }}>Practice Test</option>
                                    <option value="diagnostic" {{ request()->get('type') == 'diagnostic' ? 'selected' : '' }}>Diagnostic Test</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request()->get('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_approval" {{ request()->get('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ request()->get('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('admin/main.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">Manage all IELTS tests in a single place</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                <a href="{{ route('admin.ielts_tests.create') }}" class="btn btn-primary">
                                    <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                    <span class="ml-4 font-12">Create New Test</span>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Title</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Format</th>
                                            <th class="text-center">Duration</th>
                                            <th class="text-center">Attempts</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Created By</th>
                                            <th>{{ trans('admin/main.actions') }}</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        @foreach($tests as $test)
                                            <tr>
                                                <td>
                                                    <span class="text-black font-weight-bold">{{ $test->title }}</span>
                                                    @if($test->target_band_min && $test->target_band_max)
                                                        <small class="d-block text-gray-700">Target: Band {{ $test->target_band_min }} - {{ $test->target_band_max }}</small>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if($test->type === 'mock')
                                                        <span class="badge-status  text-black bg-primary-30" style="min-width: 90px; display: inline-block;">Mock</span>
                                                    @elseif($test->type === 'practice')
                                                        <span class="badge-status text-black bg-info-30" style="min-width: 90px; display: inline-block;">Practice</span>
                                                    @else
                                                        <span class="badge-status text-black bg-gray-200" style="min-width: 90px; display: inline-block;">Diagnostic</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge-status text-dark bg-gray-300" style="min-width: 90px; display: inline-block;">{{ ucfirst($test->format) }}</span>
                                                </td>

                                                <td class="text-center">
                                                    {{ $test->total_duration }} min
                                                    <small class="d-block text-gray-500">
                                                        @if($test->has_listening) L:{{ $test->listening_duration }} @endif
                                                        @if($test->has_reading) R:{{ $test->reading_duration }} @endif
                                                        @if($test->has_writing) W:{{ $test->writing_duration }} @endif
                                                        @if($test->has_speaking) S:{{ $test->speaking_duration }} @endif
                                                    </small>
                                                </td>

                                                <td class="text-center">
                                                    {{ $test->attempts->count() }}
                                                </td>

                                                <td class="text-center">
                                                    @if($test->status === 'published')
                                                        <span class="badge-status text-black bg-success-30" style="min-width: 90px; display: inline-block;">Published</span>
                                                    @elseif($test->status === 'pending_approval')
                                                        <span class="badge-status text-black bg-warning-30" style="min-width: 90px; display: inline-block;">Pending</span>
                                                    @elseif($test->status === 'approved')
                                                        <span class="badge-status text-black bg-info-30" style="min-width: 90px; display: inline-block;">Approved</span>
                                                    @elseif($test->status === 'rejected')
                                                        <span class="badge-status text-black bg-danger-30" style="min-width: 90px; display: inline-block;">Rejected</span>
                                                    @else
                                                        <span class="badge-status text-black bg-gray-200" style="min-width: 90px; display: inline-block;">Draft</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    {{ $test->creator->full_name ?? 'Unknown' }}
                                                </td>

                                                <td>
                                                    <div class="btn-group dropdown table-actions position-relative">
                                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="{{ route('admin.ielts_tests.sections', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-hierarchy-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">Manage Sections</span>
                                                            </a>

                                                            <a href="{{ route('admin.ielts_tests.edit', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>

                                                            @if($test->canBeEdited())
                                                                @include('admin.includes.delete_button',[
                                                                    'url' => route('admin.ielts_tests.destroy', $test->id),
                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                    'btnText' => trans("admin/main.delete"),
                                                                    'btnIcon' => 'trash',
                                                                    'iconType' => 'lin',
                                                                    'iconClass' => 'text-danger mr-2',
                                                                ])
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $tests->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
