@extends('design_1.panel.layouts.panel')

@section('content')
<section>
    {{-- Modern Header with Gradient --}}
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="d-flex align-items-center mb-8">
                    <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%);">
                        <x-iconsax-bul-note-2 class="icons text-white" width="24px" height="24px"/>
                    </div>
                    <div>
                        <h1 class="font-20 font-weight-bold text-dark mb-4">
                            {{ ucfirst($type) }} Question Groups
                        </h1>
                        <p class="text-gray-500 font-13 mb-0">
                            <x-iconsax-lin-info-circle class="icons mr-4" width="14px" height="14px"/>
                            Manage question groups for {{ $type }} tests
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-12">
                {{-- Type Toggle Pills --}}
                <div class="btn-group">
                    <a href="{{ route('panel.question-groups.index', ['type' => 'mock']) }}" 
                       class="btn {{ $type === 'mock' ? 'btn-primary' : 'btn-outline-secondary' }}"
                       style="{{ $type === 'mock' ? 'background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); border: none;' : '' }}">
                        <x-iconsax-bul-document-text class="icons mr-8" width="16px" height="16px"/>Mock
                    </a>
                    <a href="{{ route('panel.question-groups.index', ['type' => 'practice']) }}" 
                       class="btn {{ $type === 'practice' ? 'btn-primary' : 'btn-outline-secondary' }}"
                       style="{{ $type === 'practice' ? 'background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); border: none;' : '' }}">
                        <x-iconsax-bul-edit class="icons mr-8" width="16px" height="16px"/>Practice
                    </a>
                </div>
                
                <a href="{{ route('panel.question-groups.create', ['type' => $type]) }}" 
                   class="btn btn-success"
                   style="background: linear-gradient(135deg, #28c76f 0%, #48da89 100%); border: none;">
                    <x-iconsax-bul-add class="icons mr-8" width="16px" height="16px"/>New Group
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-24">
        @php
            $skillStats = [
                'reading' => ['color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.1)'],
                'listening' => ['color' => '#1a3a5c', 'bg' => 'rgba(26, 58, 92, 0.1)'],
                'writing' => ['color' => '#8b5cf6', 'bg' => 'rgba(139, 92, 246, 0.1)'],
                'speaking' => ['color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.1)']
            ];
        @endphp
        @foreach(['reading', 'listening', 'writing', 'speaking'] as $skill)
            @php
                $count = $groups->where('skill', $skill)->count();
            @endphp
            <div class="col-md-3">
                <div class="bg-white rounded-12 p-16 shadow-sm h-100 d-flex align-items-center">
                    <div class="rounded-10 p-12 mr-16" style="background: {{ $skillStats[$skill]['bg'] }};">
                        @if($skill === 'reading')
                            <x-iconsax-bul-book class="icons" width="24px" height="24px" style="color: {{ $skillStats[$skill]['color'] }}"/>
                        @elseif($skill === 'listening')
                            <x-iconsax-bul-headphone class="icons" width="24px" height="24px" style="color: {{ $skillStats[$skill]['color'] }}"/>
                        @elseif($skill === 'writing')
                            <x-iconsax-bul-edit-2 class="icons" width="24px" height="24px" style="color: {{ $skillStats[$skill]['color'] }}"/>
                        @elseif($skill === 'speaking')
                            <x-iconsax-bul-microphone-2 class="icons" width="24px" height="24px" style="color: {{ $skillStats[$skill]['color'] }}"/>
                        @endif
                    </div>
                    <div>
                        <div class="font-24 font-weight-bold" style="color: {{ $skillStats[$skill]['color'] }};">{{ $count }}</div>
                        <div class="font-12 text-gray-500 text-uppercase">{{ ucfirst($skill) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filters Card --}}
    <div class="bg-white rounded-16 shadow-sm p-20 mb-24">
        <form method="GET" class="m-0">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Skill</label>
                    <select name="skill" class="form-control">
                        <option value="">All Skills</option>
                        <option value="reading" {{ request('skill') == 'reading' ? 'selected' : '' }}>📖 Reading</option>
                        <option value="listening" {{ request('skill') == 'listening' ? 'selected' : '' }}>🎧 Listening</option>
                        <option value="writing" {{ request('skill') == 'writing' ? 'selected' : '' }}>✍️ Writing</option>
                        <option value="speaking" {{ request('skill') == 'speaking' ? 'selected' : '' }}>🗣️ Speaking</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Band</label>
                    <select name="band" class="form-control">
                        <option value="">All Bands</option>
                        @foreach([3.0, 3.5, 4.0, 4.5, 5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                            <option value="{{ $band }}" {{ request('band') == $band ? 'selected' : '' }}>{{ $band }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Search</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <!-- <span class="input-group-text bg-white border-right-0">
                                <x-iconsax-lin-search-normal-1 class="icons text-gray-400" width="16px" height="16px"/>
                            </span> -->
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="Search groups..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" 
                            style="background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); border: none;">
                        <x-iconsax-lin-filter class="icons mr-8" width="16px" height="16px"/>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Groups Table --}}
    <div class="bg-white rounded-16 shadow-sm overflow-hidden">
        @if($groups->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <th class="font-11 text-gray-600 text-uppercase py-16 px-20 font-weight-bold border-0">Group</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Skill</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Band</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Questions</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Status</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center" style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                            <tr class="group-row" style="transition: all 0.2s ease;">
                                <td class="py-16 px-20 border-top-0">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $skillColors = [
                                                'reading' => '#3b82f6',
                                                'listening' => '#1a3a5c',
                                                'writing' => '#8b5cf6',
                                                'speaking' => '#10b981'
                                            ];
                                        @endphp
                                        <div class="rounded-8 d-flex align-items-center justify-content-center mr-12" 
                                             style="width: 36px; height: 36px; background: {{ $skillColors[$group->skill] }}15;">
                                            @if($group->skill === 'reading')
                                                <x-iconsax-bul-book class="icons" width="18px" height="18px" style="color: {{ $skillColors[$group->skill] }}"/>
                                            @elseif($group->skill === 'listening')
                                                <x-iconsax-bul-headphone class="icons" width="18px" height="18px" style="color: {{ $skillColors[$group->skill] }}"/>
                                            @elseif($group->skill === 'writing')
                                                <x-iconsax-bul-edit-2 class="icons" width="18px" height="18px" style="color: {{ $skillColors[$group->skill] }}"/>
                                            @elseif($group->skill === 'speaking')
                                                <x-iconsax-bul-microphone-2 class="icons" width="18px" height="18px" style="color: {{ $skillColors[$group->skill] }}"/>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('panel.question-groups.show', $group->id) }}" 
                                               class="font-14 font-weight-bold text-dark d-block hover-primary"
                                               style="transition: color 0.2s;">
                                                {{ $group->title }}
                                            </a>
                                            @if($group->instructions)
                                                <p class="font-12 text-gray-500 mb-0 mt-4" style="line-height: 1.4;">
                                                    {{ Str::limit(strip_tags($group->instructions), 50) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-16 text-center border-top-0">
                                    @php
                                        $skillBadges = [
                                            'reading' => ['bg' => '#3b82f6', 'text' => 'Reading'],
                                            'listening' => ['bg' => '#1a3a5c', 'text' => 'Listening'],
                                            'writing' => ['bg' => '#8b5cf6', 'text' => 'Writing'],
                                            'speaking' => ['bg' => '#10b981', 'text' => 'Speaking']
                                        ];
                                    @endphp
                                    <span class="d-inline-flex align-items-center px-10 py-4 font-11 font-weight-bold text-center" 
                                          style="background: {{ $skillBadges[$group->skill]['bg'] }}; color: white; border-radius: 6px; white-space: nowrap;">
                                        @if($group->skill === 'reading')
                                            <x-iconsax-bul-book class="icons mr-4" width="12px" height="12px"/>
                                        @elseif($group->skill === 'listening')
                                            <x-iconsax-bul-headphone class="icons mr-4" width="12px" height="12px"/>
                                        @elseif($group->skill === 'writing')
                                            <x-iconsax-bul-edit-2 class="icons mr-4" width="12px" height="12px"/>
                                        @elseif($group->skill === 'speaking')
                                            <x-iconsax-bul-microphone-2 class="icons mr-4" width="12px" height="12px"/>
                                        @endif
                                        {{ $skillBadges[$group->skill]['text'] }}
                                    </span>
                                </td>
                                <td class="py-16 text-center border-top-0">
                                    @if($group->target_band)
                                        <span class="font-16 font-weight-bold" style="color: #f59e0b;">{{ $group->target_band }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="py-16 text-center border-top-0">
                                    <span class="d-inline-block px-8 py-4 font-13 font-weight-bold" 
                                          style="background: {{ $group->questions_count > 0 ? '#e0f2fe' : '#f1f5f9' }}; 
                                                 color: {{ $group->questions_count > 0 ? '#0284c7' : '#94a3b8' }}; 
                                                 border-radius: 6px; min-width: 32px;">
                                        {{ $group->questions_count }}
                                    </span>
                                </td>
                                <td class="py-16 text-center border-top-0">
                                    @php
                                        $statusStyles = [
                                            'draft' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'text' => 'Draft'],
                                            'pending' => ['bg' => '#fef3c7', 'color' => '#d97706', 'text' => 'Pending'],
                                            'approved' => ['bg' => '#d1fae5', 'color' => '#059669', 'text' => 'Approved']
                                        ];
                                        $status = $statusStyles[$group->status] ?? $statusStyles['draft'];
                                    @endphp
                                    <span class="d-inline-block px-10 py-4 font-11" 
                                          style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; border-radius: 6px; white-space: nowrap;">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="py-16 text-center border-top-0">
                                    <div class="d-flex gap-8 justify-content-center">
                                        @if($group->status === 'draft')
                                            <form action="{{ route('panel.question-groups.submit-approval', $group->id) }}" 
                                                  method="POST" class="d-inline submit-approval-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #fef3c7; color: #d97706; border: none;"
                                                        title="Submit for Approval">
                                                    <x-iconsax-lin-send class="icons" width="16px" height="16px"/>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('panel.question-groups.show', $group->id) }}" 
                                           class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center" 
                                           style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #e0f2fe; color: #0284c7; border: none;"
                                           title="View">
                                            <x-iconsax-lin-eye class="icons" width="16px" height="16px"/>
                                        </a>
                                        @if(in_array($group->status, ['draft', 'rejected']))
                                            <a href="{{ route('panel.question-groups.edit', $group->id) }}" 
                                               class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center"
                                               style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #f1f5f9; color: #64748b; border: none;"
                                               title="Edit">
                                                <x-iconsax-lin-edit class="icons" width="16px" height="16px"/>
                                            </a>
                                        @endif
                                        @if($group->status !== 'approved' || $group->questions_count == 0)
                                            <form action="{{ route('panel.question-groups.destroy', $group->id) }}" 
                                                  method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #fee2e2; color: #dc2626; border: none;"
                                                        title="Delete">
                                                    <x-iconsax-lin-trash class="icons" width="16px" height="16px"/>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($groups->hasPages())
                <div class="p-20 border-top d-flex justify-content-between align-items-center">
                    <div class="font-13 text-gray-500">
                        Showing {{ $groups->firstItem() }} - {{ $groups->lastItem() }} of {{ $groups->total() }} groups
                    </div>
                    <div>
                        {{ $groups->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-80">
                <div class="d-inline-flex align-items-center justify-content-center rounded-16 mb-20" 
                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                    <x-iconsax-bul-folder-open class="icons text-gray-300" width="48px" height="48px"/>
                </div>
                <h4 class="font-18 font-weight-bold text-dark mb-8">No Question Groups Yet</h4>
                <p class="text-gray-500 mb-24 font-14">
                    Create your first {{ $type }} question group to get started
                </p>
                <a href="{{ route('panel.question-groups.create', ['type' => $type]) }}" 
                   class="btn btn-success btn-lg px-32"
                   style="background: linear-gradient(135deg, #28c76f 0%, #48da89 100%); border: none; border-radius: 12px;">
                    <x-iconsax-bul-add class="icons mr-8" width="18px" height="18px"/>Create First Group
                </a>
            </div>
        @endif
    </div>
</section>

@push('scripts_bottom')
<script>
$(document).ready(function() {
    // Submit for Approval confirmation
    $('.submit-approval-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: 'Submit for Approval?',
            text: 'This will send the question group to managers for review.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-paper-plane mr-8"></i>Yes, Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Delete confirmation with SweetAlert
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: 'Delete Group?',
            text: 'This will permanently delete this group and all its questions!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash mr-8"></i>Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Hover effect for rows
    $('.group-row').hover(
        function() { $(this).css('background-color', '#f8fafc'); },
        function() { $(this).css('background-color', ''); }
    );

    // Action button hover
    $('.action-btn').hover(
        function() { $(this).css('transform', 'scale(1.1)'); },
        function() { $(this).css('transform', 'scale(1)'); }
    );
});
</script>

<style>
.hover-primary:hover { color: #2e5a8a !important; }
.action-btn { transition: all 0.2s ease; }
.gap-8 { gap: 8px; }
.gap-12 { gap: 12px; }
.rounded-10 { border-radius: 10px; }
.rounded-12 { border-radius: 12px; }
.rounded-16 { border-radius: 16px; }

/* Table styling */
.table td { vertical-align: middle; }
.table tbody tr { border-bottom: 1px solid #f1f5f9; }
.table tbody tr:last-child { border-bottom: none; }

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar { height: 6px; }
.table-responsive::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
.table-responsive::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.table-responsive::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush
@endsection
