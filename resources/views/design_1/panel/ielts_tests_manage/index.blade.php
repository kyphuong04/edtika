@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-25">
        <div>
            <h1 class="section-title font-24 text-dark-blue">My IELTS Tests</h1>
            <p class="text-black font-14">Manage and monitor your IELTS practice and mock exams.</p>
        </div>
        <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn btn-primary shadow-primary d-flex align-items-center">
            <i class="fas fa-plus-circle mr-5"></i>
            <span>Create New Test</span>
        </a>
    </div>

    {{-- Stats Cards --}}
   <div class="row mt-20">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-20">
        <div class="bg-white rounded-16 p-20 d-flex align-items-center justify-content-between border">
            <div>
                <span class="d-block text-gray-500 font-12 mb-5">Total Tests</span>
                <h3 class="font-30 font-weight-bold text-dark-blue">{{ $stats['total'] }}</h3>
            </div>
            <div class="d-flex-center size-48 bg-info-light rounded-circle">
                <x-iconsax-bul-clipboard-text class="size-icon-20 text-info"/>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-20">
        <div class="bg-white rounded-16 p-20 d-flex align-items-center justify-content-between border">
            <div>
                <span class="d-block text-gray-500 font-12 mb-5">Mock Tests</span>
                <h3 class="font-30 font-weight-bold text-dark-blue">{{ $tests->where('type', 'mock')->count() }}</h3>
           </div>
            <div class="d-flex-center size-48 bg-success-light rounded-circle">
                <x-iconsax-bul-document-text class="size-icon-20 text-success"/>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-20">
        <div class="bg-white rounded-16 p-20 d-flex align-items-center justify-content-between border">
            <div>
                <span class="d-block text-gray-500 font-12 mb-5">Practice Tests</span>
                <h3 class="font-30 font-weight-bold text-dark-blue">{{ $tests->where('type', 'practice')->count() }}</h3>
            </div>
            <div class="d-flex-center size-48 bg-danger-light rounded-circle">
                <x-iconsax-bul-note-text class="size-icon-20 text-danger"/>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-20">
        <div class="bg-white rounded-16 p-20 d-flex align-items-center justify-content-between border">
            <div>
                <span class="d-block text-gray-500 font-12 mb-5">Pending Approval</span>
                <h3 class="font-30 font-weight-bold text-dark-blue">{{ $stats['pending'] }}</h3>
            </div>
            <div class="d-flex-center size-48 bg-warning-light rounded-circle">
                <x-iconsax-bul-clock class="size-icon-20 text-warning"/>
            </div>
        </div>
    </div>
</div>

    {{-- Tests List --}}
    <div class="row" style="margin-top: 60px;">
        <div class="col-12">
            @if($tests->isEmpty())
                <div class="bg-white shadow-sm rounded-16 p-30">
                    @include('design_1.panel.includes.no-result',[
                        'file_name' => 'support.svg',
                        'title' => 'No tests yet!',
                        'hint' => 'Create your first IELTS test to get started.',
                        'btn' => ['url' => route('panel.my_ielts_tests.create'),'text' => 'Create Test']
                    ])
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-16">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0" style="min-width: 900px;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pl-25" width="30%">Test Information</th>
                                        <th class="text-center" width="13%">Type</th>
                                        <th class="text-center" width="13%">Duration</th>
                                        <th class="text-center" width="10%">Attempts</th>
                                        <th class="text-center" width="13%">Status</th>
                                        <th class="text-center pr-25" width="16%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tests as $test)
                                        <tr>
                                           <td class="pl-25 py-20 text-center">
                                            <div>
                                                {{-- Test Title --}}
                                                <div class="font-weight-bold text-dark-blue mb-2" style="font-size: 15px;">
                                                    {{ $test->title }}
                                                </div>
                                                
                                                {{-- Format & Band Info --}}
                                                <div class="text-gray-700" style="font-size: 12px;">
                                                    <span class="mr-3">
                                                        <i class="fa fa-file-alt mr-1"></i>
                                                        {{ ucfirst($test->format ?? 'Both') }}
                                                    </span>
                                                    @if($test->target_band_min && $test->target_band_max)
                                                        <span class="text-primary font-weight-500">
                                                            <i class="fa fa-bullseye mr-1"></i>
                                                            Band {{ number_format($test->target_band_min, 1) }}-{{ number_format($test->target_band_max, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                            <td class="text-center">
                                                @if($test->type === 'mock')
                                                    <span class="badge text-black badge-soft-primary" style="min-width: 90px; display: inline-block;">Mock Test</span>
                                                @elseif($test->type === 'practice')
                                                    <span class="badge text-black badge-soft-info" style="min-width: 90px; display: inline-block;">Practice</span>
                                                @else
                                                    <span class="badge text-black badge-soft-secondary" style="min-width: 90px; display: inline-block;">Diagnostic</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="text-dark-blue font-weight-500">{{ $test->total_duration }} min</div>
                                                <small class="text-gray">{{ $test->sections->count() }} sections</small>
                                            </td>

                                            <td class="text-center">
                                                <span class="font-weight-500">{{ $test->attempts->count() }}</span>
                                            </td>

                                            <td class="text-center text-black">
                                                @php
                                                    $statusClasses = [
                                                        'published' => 'badge-soft-success',
                                                        'pending_approval' => 'badge-soft-warning',
                                                        'approved' => 'badge-soft-info',
                                                        'rejected' => 'badge-soft-danger',
                                                        'draft' => 'badge-soft-secondary'
                                                    ];
                                                    $statusLabel = [
                                                        'published' => 'Published',
                                                        'pending_approval' => 'Pending',
                                                        'approved' => 'Approved',
                                                        'rejected' => 'Rejected',
                                                        'draft' => 'Draft'
                                                    ];
                                                    $class = $statusClasses[$test->status] ?? 'badge-soft-secondary';
                                                    $label = $statusLabel[$test->status] ?? ucfirst($test->status);
                                                @endphp
                                                <span class="badge text-black {{ $class }}" style="min-width: 85px; display: inline-block;">{{ $label }}</span>
                                            </td>
                                            <td class="text-center p-25">
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-hierarchy-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Manage Sections</span>
                                                        </a>

                                                        <a href="{{ route('panel.my_ielts_tests.edit', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Edit</span>
                                                        </a>
                                                        
                                                        @if(($test->status === 'draft' || $test->status === 'rejected') && $test->sections->count() > 0)
                                                            <a href="{{ route('panel.my_ielts_tests.submit_approval', $test->id) }}" 
                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 submit-approval-btn"
                                                               data-test-title="{{ $test->title }}">
                                                                <x-iconsax-lin-send-2 class="icons text-success mr-2" width="18px" height="18px"/>
                                                                <span class="text-success font-14 font-weight-bold">Submit for Approval</span>
                                                            </a>
                                                        @endif

                                                        <!-- <a href="{{ route('panel.my_ielts_tests.duplicate', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-copy class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Duplicate</span>
                                                        </a> -->

                                                        @if($test->canBeEdited())
                                                            <a href="{{ route('panel.my_ielts_tests.delete', $test->id) }}" 
                                                               class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4 delete-test-btn"
                                                               data-test-title="{{ $test->title }}">
                                                                <x-iconsax-lin-trash class="icons text-danger mr-2" width="18px" height="18px"/>
                                                                <span class="text-danger font-14">Delete</span>
                                                            </a>
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
                </div>
                {{-- Add Pagination if needed --}}
                <div class="mt-20">
                    {{-- $tests->links() --}}
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Custom Confirmation Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-30">
                <div class="mb-20">
                    <div id="modalIcon" class="d-inline-flex align-items-center justify-content-center rounded-circle mb-15" style="width: 70px; height: 70px;">
                        <i id="modalIconElement" class="font-30"></i>
                    </div>
                </div>
                <h4 id="modalTitle" class="font-20 font-weight-bold text-dark-blue mb-10"></h4>
                <p id="modalMessage" class="text-gray font-14 mb-20"></p>
                <div class="d-flex justify-content-center gap-10">
                    <button type="button" class="btn btn-light px-30" data-dismiss="modal">Cancel</button>
                    <button type="button" id="modalConfirmBtn" class="btn px-30"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let confirmCallback = null;

function showConfirmModal(options) {
    const modal = $('#confirmModal');
    const iconBg = options.type === 'danger' ? '#fee2e2' : '#dcfce7';
    const iconColor = options.type === 'danger' ? '#dc2626' : '#16a34a';
    const iconClass = options.type === 'danger' ? 'fa fa-exclamation-triangle' : 'fa fa-paper-plane';
    
    $('#modalIcon').css('background-color', iconBg);
    $('#modalIconElement').attr('class', iconClass + ' font-30').css('color', iconColor);
    $('#modalTitle').text(options.title);
    $('#modalMessage').text(options.message);
    $('#modalConfirmBtn')
        .text(options.confirmText)
        .attr('class', 'btn px-30 ' + (options.type === 'danger' ? 'btn-danger' : 'btn-success'));
    
    confirmCallback = options.onConfirm;
    modal.modal('show');
}

$(document).ready(function() {
    $('#modalConfirmBtn').on('click', function() {
        $('#confirmModal').modal('hide');
        if (confirmCallback) {
            confirmCallback();
            confirmCallback = null;
        }
    });
    
    // Submit for Approval
    $('.submit-approval-btn').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const testTitle = $(this).data('test-title');
        
        showConfirmModal({
            type: 'success',
            title: 'Submit for Approval?',
            message: `Are you sure you want to submit "${testTitle}" for approval? You won't be able to edit it until it's reviewed.`,
            confirmText: 'Yes, Submit',
            onConfirm: function() {
                window.location.href = url;
            }
        });
    });
    
    // Delete Test
    $('.delete-test-btn').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const testTitle = $(this).data('test-title');
        
        showConfirmModal({
            type: 'danger',
            title: 'Delete Test?',
            message: `Are you sure you want to delete "${testTitle}"? This action cannot be undone.`,
            confirmText: 'Yes, Delete',
            onConfirm: function() {
                window.location.href = url;
            }
        });
    });
});
</script>

<style>
    .bg-blue-100 { background-color: #e7f0fd; }
    .bg-green-100 { background-color: #eaf8f0; }
    .bg-red-100 { background-color: #fcecef; }
    .bg-orange-100 { background-color: #fff4e6; }
    
    .badge-soft-primary { background: #e7f0fd; color: #438eff; }
    .badge-soft-success { background: #eaf8f0; color: #17bb66; }
    .badge-soft-danger { background: #fcecef; color: #f63c3c; }
    .badge-soft-warning { background: #fff4e6; color: #ff9100; }
    .badge-soft-info { background: #e5f7f8; color: #00b8d9; }
    .badge-soft-secondary { background: #f2f4f7; color: #667085; }

    .custom-table thead th {
        border-top: none;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #6a7c92;
        padding: 15px 10px;
    }

    .custom-table tbody tr {
        transition: all 0.2s;
    }

    .custom-table tbody tr:hover {
        background-color: #f8faff;
    }

    .shadow-primary {
        box-shadow: 0 4px 12px rgba(67, 142, 255, 0.3);
    }

    .btn-transparent {
        background: transparent;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    .stats-card {
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-3px);
    }

    /* Fix Dropdown Overlay */
    .table-actions {
        position: relative !important;
    }
    
    .table-actions .dropdown-menu {
        position: absolute !important;
        z-index: 1050 !important;
        will-change: transform;
    }
    
    .table-responsive {
        overflow: visible !important;
    }
    
    .card-body {
        overflow: visible !important;
    }
</style><style>
    /* Stats Cards */
    .stats-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f1f1 !important;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }

    /* Table Styles */
    .custom-table thead th {
        background-color: #f8f9fb;
        border: none;
        color: #7a869a;
        font-size: 11px;
        letter-spacing: 0.8px;
        font-weight: 700;
        padding: 15px 10px;
    }
    .custom-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }
    .custom-table tbody tr:hover {
        background-color: #fbfcfe;
    }

    /* Badge Soft Style */
    .badge-soft {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        min-width: 85px;
        text-align: center;
    }
    .badge-soft-primary { background: #eef4ff; color: #3b82f6; }
    .badge-soft-info { background: #e0f7fa; color: #00acc1; }
    .badge-soft-success { background: #e8f5e9; color: #2e7d32; }
    .badge-soft-warning { background: #fff8e1; color: #f57c00; }
    .badge-soft-danger { background: #ffebee; color: #d32f2f; }
    .badge-soft-secondary { background: #f3f4f6; color: #6b7280; }
    
    .badge-pill {
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Table Cell Padding */
    .custom-table tbody td {
        padding: 18px 10px;
        vertical-align: middle;
    }

    /* Action Button Custom */
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        margin: 0 auto;
    }
    .btn-action:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    /* Fix Dropdown Menu */
    .dropdown-item {
        padding: 10px 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }
    .dropdown-item i {
        width: 20px;
    }

    .shadow-primary {
        box-shadow: 0 4px 14px 0 rgba(0, 118, 255, 0.39);
    }
</style>
@endsection