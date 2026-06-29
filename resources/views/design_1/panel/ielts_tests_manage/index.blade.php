@extends('design_1.panel.layouts.panel')

<style>
.btn-1 {
    border-radius: 12px;
    background: #511D99;
    color: #fff;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}
.btn-1:hover {
    background: #fff;
    color: #511D99;
    border-color: #511D99;
}
</style>

@section('content')
<section class="mt-30">
    {{-- IELTS Standard Warning --}}
    @if(session('mock_parts_warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-20" role="alert">
        <i class="fas fa-exclamation-triangle mr-10"></i>
        <strong>{{ trans('update.test_created') }}</strong> {{ session('mock_parts_warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-25">
        <div>
            <h1 class="section-title font-24 text-dark-blue">{{ trans('update.ielts_tests_manage_title') }}</h1>
            <p class="text-black font-14">{{ trans('update.ielts_tests_manage_hint') }}</p>
        </div>
        <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn-1 btn-lg rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
            <i class="fas fa-plus-circle mr-8"></i>
            <span>{{ trans('update.create_new_test') }}</span>
        </a>
    </div>

    {{-- Stats Cards --}}
   <div class="row mt-20">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-20">
        <div class="bg-white rounded-16 p-20 d-flex align-items-center justify-content-between border">
            <div>
                <span class="d-block text-gray-500 font-12 mb-5">{{ trans('update.total_tests') }}</span>
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
                <span class="d-block text-gray-500 font-12 mb-5">{{ trans('update.mock_tests') }}</span>
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
                <span class="d-block text-gray-500 font-12 mb-5">{{ trans('update.practice_tests') }}</span>
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
                <span class="d-block text-gray-500 font-12 mb-5">{{ trans('update.published') }}</span>
                <h3 class="font-30 font-weight-bold text-dark-blue">{{ $stats['published'] }}</h3>
            </div>
            <div class="d-flex-center size-48 bg-success-light rounded-circle">
                <x-iconsax-bul-tick-circle class="size-icon-20 text-success"/>
            </div>
        </div>
    </div>
 </div>

    {{-- Filters Card --}}
    <div class="row" style="margin-top: 30px; align-items: center;">
        <div class="col-12">
            <div class="bg-white rounded-16 p-20" style="border-radius: 12px;">
                <form method="GET" class="m-0">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.type') }}</label>
                            <select name="type" class="form-control">
                                <option value="">{{ trans('update.all_types') }}</option>
                                <option value="mock" {{ request('type') == 'mock' ? 'selected' : '' }}>{{ trans('update.mock_test') }}</option>
                                <option value="practice" {{ request('type') == 'practice' ? 'selected' : '' }}>{{ trans('update.practice') }}</option>
                                <option value="diagnostic" {{ request('type') == 'diagnostic' ? 'selected' : '' }}>{{ trans('update.diagnostic') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('public.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ trans('update.all_status') }}</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ trans('update.draft') }}</option>
                                <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>{{ trans('update.pending_approval') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ trans('update.approved') }}</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ trans('update.published') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ trans('update.rejected') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.search') }}</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="{{ trans('update.search_by_title') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-1 btn-lg rounded-12 d-inline-flex align-items-center justify-content-center" style="height:38px;gap:6px;white-space:nowrap;width:100%;">
                                <i class="fas fa-filter mr-8"></i>{{ trans('update.filter') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tests List --}}
    <div class="row" style="margin-top: 30px;">
        <div class="col-12">
            @if($tests->isEmpty())
                <div class="bg-white shadow-sm rounded-16 p-30" style="border-radius: 12px;">
                    @include('design_1.panel.includes.no-result',[
                        'file_name' => 'support.svg',
                        'title' => trans('update.no_tests_yet'),
                        'hint' => trans('update.create_your_first_ielts_test_hint'),
                        'btn' => ['url' => route('panel.my_ielts_tests.create'),'text' => trans('update.create_test')]
                    ])
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pl-25" width="30%">{{ trans('update.test_information') }}</th>
                                        <th class="text-center" width="13%">{{ trans('update.type') }}</th>
                                        <th class="text-center" width="13%">{{ trans('update.duration') }}</th>
                                        <th class="text-center" width="10%">{{ trans('update.attempts') }}</th>
                                        <th class="text-center" width="13%">{{ trans('public.status') }}</th>
                                        <th class="text-center pr-25" width="16%">{{ trans('update.actions') }}</th>
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
                                                    <span class="badge text-black badge-soft-primary" style="min-width: 90px; display: inline-block;">{{ trans('update.mock_test') }}</span>
                                                @elseif($test->type === 'practice')
                                                    <span class="badge text-black badge-soft-info" style="min-width: 90px; display: inline-block;">{{ trans('update.practice') }}</span>
                                                @else
                                                    <span class="badge text-black badge-soft-secondary" style="min-width: 90px; display: inline-block;">{{ trans('update.diagnostic') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="text-dark-blue font-weight-500">{{ $test->total_duration }} {{ trans('update.ielts_min') }}</div>
                                                <small class="text-gray-500">{{ trans('update.ielts_sections_count', ['count' => $test->sections->count()]) }}</small>
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
                                                        'published' => trans('update.published'),
                                                        'pending_approval' => trans('update.pending_approval'),
                                                        'approved' => trans('update.approved'),
                                                        'rejected' => trans('update.rejected'),
                                                        'draft' => trans('update.draft')
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
                                                        <a href="{{ route('panel.my_ielts_tests.edit_inline', $test->id) }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Xem/Chỉnh sửa</span>
                                                        </a>

                                                        @if($test->canBeEdited())
                                                            @if($test->status === 'draft')
                                                                <a href="#"
                                                                   class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4 delete-test-btn"
                                                                   data-delete-url="{{ route('panel.my_ielts_tests.delete', $test->id) }}"
                                                                   data-delete-mode="direct"
                                                                   data-test-id="{{ $test->id }}"
                                                                   data-test-title="{{ $test->title }}">
                                                                    <x-iconsax-lin-trash class="icons text-danger mr-2" width="18px" height="18px"/>
                                                                    <span class="text-danger font-14">Xóa bản nháp</span>
                                                                </a>
                                                            @else
                                                                <a href="#"
                                                                   class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4 delete-test-btn"
                                                                   data-request-url="{{ url('/panel/content-delete-request') }}"
                                                                   data-delete-mode="request"
                                                                   data-test-id="{{ $test->id }}"
                                                                   data-test-title="{{ $test->title }}">
                                                                    <x-iconsax-lin-trash class="icons text-danger mr-2" width="18px" height="18px"/>
                                                                    <span class="text-danger font-14">Xóa</span>
                                                                </a>
                                                            @endif
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
                <p id="modalMessage" class="text-gray-500 font-14 mb-20"></p>
                <div class="d-flex justify-content-center gap-10">
                    <button type="button" class="btn btn-light px-30 mr-10" data-dismiss="modal">{{ trans('update.cancel') }}</button>
                    <button type="button" id="modalConfirmBtn" class="btn px-30"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let confirmCallback = null;
let confirmModalElement = null;
let confirmModalInstance = null;

function getConfirmModalInstance() {
    if (!confirmModalElement) {
        confirmModalElement = document.getElementById('confirmModal');
    }

    if (!confirmModalElement || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return null;
    }

    if (!confirmModalInstance) {
        confirmModalInstance = new bootstrap.Modal(confirmModalElement);
    }

    return confirmModalInstance;
}

function showConfirmModal(options) {
    const modalElement = document.getElementById('confirmModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalIconElement = document.getElementById('modalIconElement');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    if (!modalElement || !modalIcon || !modalIconElement || !modalTitle || !modalMessage || !modalConfirmBtn) {
        const confirmed = window.confirm(options.message || 'Are you sure?');
        if (confirmed && typeof options.onConfirm === 'function') {
            options.onConfirm();
        }
        return;
    }

    const iconBg = options.type === 'danger' ? '#fee2e2' : '#dcfce7';
    const iconColor = options.type === 'danger' ? '#dc2626' : '#16a34a';
    const iconClass = options.type === 'danger' ? 'fa fa-exclamation-triangle' : 'fa fa-paper-plane';

    modalIcon.style.backgroundColor = iconBg;
    modalIconElement.className = iconClass + ' font-30';
    modalIconElement.style.color = iconColor;
    modalTitle.textContent = options.title || '';
    modalMessage.textContent = options.message || '';
    modalConfirmBtn.textContent = options.confirmText || 'Confirm';
    modalConfirmBtn.className = 'btn px-30 ' + (options.type === 'danger' ? 'btn-danger' : 'btn-success');

    confirmCallback = options.onConfirm;
    const modalInstance = getConfirmModalInstance();

    if (modalInstance) {
        modalInstance.show();
        return;
    }

    modalElement.style.display = 'block';
    modalElement.classList.add('show');
    modalElement.setAttribute('aria-modal', 'true');
    modalElement.removeAttribute('aria-hidden');
    document.body.classList.add('modal-open');
}

function hideConfirmModal() {
    const modalElement = document.getElementById('confirmModal');
    const modalInstance = getConfirmModalInstance();

    if (modalInstance) {
        modalInstance.hide();
        return;
    }

    if (!modalElement) {
        return;
    }

    modalElement.classList.remove('show');
    modalElement.style.display = 'none';
    modalElement.setAttribute('aria-hidden', 'true');
    modalElement.removeAttribute('aria-modal');
    document.body.classList.remove('modal-open');
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmButton = document.getElementById('modalConfirmBtn');
    const cancelButtons = document.querySelectorAll('#confirmModal [data-dismiss="modal"]');

    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            hideConfirmModal();
            if (confirmCallback) {
                confirmCallback();
                confirmCallback = null;
            }
        });
    }

    cancelButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            hideConfirmModal();
            confirmCallback = null;
        });
    });

    document.querySelectorAll('.delete-test-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const deleteMode = button.dataset.deleteMode;
            const deleteUrl = button.dataset.deleteUrl;
            const requestUrl = button.dataset.requestUrl;
            const testId = button.dataset.testId;
            const testTitle = button.dataset.testTitle;

            if (deleteMode === 'direct' && deleteUrl) {
                showConfirmModal({
                    type: 'danger',
                    title: 'Xóa bản nháp',
                    message: 'Bạn có chắc muốn xóa vĩnh viễn bản nháp "' + testTitle + '"?',
                    confirmText: 'Xóa ngay',
                    onConfirm: function() {
                        window.location.href = deleteUrl;
                    }
                });
                return;
            }

            showConfirmModal({
                type: 'danger',
                title: 'Gửi yêu cầu xóa',
                message: 'Yêu cầu xóa "' + testTitle + '" sẽ cần manager/CEO duyệt trước khi hệ thống thực sự xóa đề này.',
                confirmText: 'Gửi yêu cầu',
                onConfirm: function() {
                    fetch(requestUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            item_id: testId,
                            item_type: 'ielts_test',
                            description: 'Request delete IELTS test: ' + testTitle
                        })
                    })
                        .then(async function(response) {
                            if (response.ok) {
                                return response;
                            }

                            let payload = null;
                            try {
                                payload = await response.json();
                            } catch (error) {
                                payload = null;
                            }

                            const message = payload && payload.errors
                                ? Object.values(payload.errors).flat().join('\n')
                                : 'Không thể gửi yêu cầu xóa.';

                            throw new Error(message);
                        })
                        .then(function() {
                            alert('Yêu cầu xóa đã được gửi để manager/CEO duyệt.');
                            window.location.reload();
                        })
                        .catch(function(error) {
                            alert(error.message || 'Không thể gửi yêu cầu xóa.');
                        });
                }
            });
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
    /* Make table rows look like inner-rounded cards and ensure badge labels are visible */
    .custom-table {
        border-spacing: 0 12px;
        width: 100%;
    }

    .custom-table thead th {
        background: transparent !important;
        border: none;
        padding: 12px 10px;
    }

    .custom-table tbody tr {
        background: transparent;
    }

    .custom-table tbody td {
        background: #ffffff !important;
        border: none !important;
        padding: 18px 12px !important;
        vertical-align: middle !important;
        overflow: visible !important;
        -webkit-background-clip: padding-box !important;
        background-clip: padding-box !important;
    }

    /* Force rounded corners on the row by rounding first/last cells and adding subtle border/shadow */
    .custom-table tbody tr td:first-child {
        border-top-left-radius: 12px !important;
        border-bottom-left-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(14,30,37,0.04) !important;
        border-left: 1px solid rgba(16,24,40,0.03) !important;
    }
    .custom-table tbody tr td:last-child {
        border-top-right-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(14,30,37,0.04) !important;
        border-right: 1px solid rgba(16,24,40,0.03) !important;
    }

    /* Ensure inner cells don't show square borders that break rounding */
    .custom-table tbody tr td:not(:first-child):not(:last-child) {
        border-left: 1px solid rgba(16,24,40,0.03) !important;
        border-right: 1px solid rgba(16,24,40,0.03) !important;
        box-shadow: none !important;
    }

    .custom-table .badge {
        color: #0b1220 !important;
        padding: 6px 10px !important;
        font-weight: 600 !important;
        display: inline-block !important;
        min-width: 85px !important;
        text-align: center !important;
        border-radius: 10px !important;
        background-clip: padding-box !important;
    }

    /* Make sure the responsive wrapper doesn't clip rounded corners */
    .table-responsive { overflow: visible !important; }

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
        top: calc(100% + 6px) !important;
        right: 0 !important;
        left: auto !important;
        z-index: 1050 !important;
        transform: none !important;
        min-width: 220px;
        max-width: calc(100vw - 24px);
        white-space: normal;
        overflow-wrap: anywhere;
        will-change: auto;
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