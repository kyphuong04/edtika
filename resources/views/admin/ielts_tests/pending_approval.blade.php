@extends('admin.layouts.app')

@section('content')
<section class="section">
    {{-- Modern Header --}}
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24" style="border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-clock text-white" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h1 class="font-20 font-weight-bold text-dark mb-4">
                        {{ trans('update.ielts_question_groups_pending_approval') }}
                    </h1>
                    <p class="text-gray-500 font-13 mb-0">
                        <i class="fas fa-info-circle mr-4" style="font-size: 14px;"></i>
                        {{ trans('update.ielts_review_approve_question_groups_hint') }}
                    </p>
                </div>
            </div>
            <div>
                <span class="badge badge-warning" style="padding: 10px 20px; font-size: 14px; border-radius: 20px; font-weight: 600;">
                    {{ trans('update.pending_count', ['count' => $pendingCount ?? $groups->total()]) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-24">
        @php
            $skillStats = [
                'reading' => ['icon' => 'fa-book', 'color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.1)', 'count' => $groups->where('skill', 'reading')->count() + $tests->where('has_reading', true)->count()],
                'listening' => ['icon' => 'fa-headphones', 'color' => '#1a3a5c', 'bg' => 'rgba(26, 58, 92, 0.1)', 'count' => $groups->where('skill', 'listening')->count() + $tests->where('has_listening', true)->count()],
                'writing' => ['icon' => 'fa-pen', 'color' => '#8b5cf6', 'bg' => 'rgba(139, 92, 246, 0.1)', 'count' => $groups->where('skill', 'writing')->count() + $tests->where('has_writing', true)->count()],
                'speaking' => ['icon' => 'fa-microphone', 'color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.1)', 'count' => $groups->where('skill', 'speaking')->count() + $tests->where('has_speaking', true)->count()]
            ];
        @endphp
        @foreach(['reading', 'listening', 'writing', 'speaking'] as $skill)
            <div class="col-md-3">
                <div class="bg-white rounded-12 p-16 shadow-sm h-100 d-flex align-items-center" style="border-radius: 12px;">
                    <div class="rounded-10 p-12 mr-16" style="background: {{ $skillStats[$skill]['bg'] }}; border-radius: 10px;">
                        <i class="fas {{ $skillStats[$skill]['icon'] }}" style="font-size: 24px; color: {{ $skillStats[$skill]['color'] }}"></i>
                    </div>
                    <div>
                        <div class="font-24 font-weight-bold" style="color: {{ $skillStats[$skill]['color'] }};">
                            {{ $skillStats[$skill]['count'] }}
                        </div>
                        <div class="font-12 text-gray-500 text-uppercase">{{ trans('update.'.$skill) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filters Card --}}
    <div class="bg-white rounded-16 shadow-sm p-20 mb-24" style="border-radius: 12px;">
        <form method="GET" class="m-0">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.skill') }}</label>
                    <select name="skill" class="form-control">
                        <option value="">{{ trans('update.ielts_all_skills') }}</option>
                        <option value="reading" {{ request('skill') == 'reading' ? 'selected' : '' }}>
                            <i class="fas fa-book"></i> {{ trans('update.reading') }}
                        </option>
                        <option value="listening" {{ request('skill') == 'listening' ? 'selected' : '' }}>
                            <i class="fas fa-headphones"></i> {{ trans('update.listening') }}
                        </option>
                        <option value="writing" {{ request('skill') == 'writing' ? 'selected' : '' }}>
                            <i class="fas fa-pen"></i> {{ trans('update.writing') }}
                        </option>
                        <option value="speaking" {{ request('skill') == 'speaking' ? 'selected' : '' }}>
                            <i class="fas fa-microphone"></i> {{ trans('update.speaking') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.type') }}</label>
                    <select name="type" class="form-control">
                        <option value="">{{ trans('update.ielts_all_types') }}</option>
                        <option value="mock" {{ request('type') == 'mock' ? 'selected' : '' }}>{{ trans('update.mock') }}</option>
                        <option value="practice" {{ request('type') == 'practice' ? 'selected' : '' }}>{{ trans('update.practice') }}</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.search') }}</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="{{ trans('update.search_by_title_or_creator') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-filter mr-8"></i>{{ trans('update.filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Pending Tests Table --}}
    <div class="bg-white rounded-16 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="px-20 py-16 border-bottom d-flex align-items-center justify-content-between">
            <div class="font-15 font-weight-bold text-dark">
                <i class="fas fa-file-alt mr-8 text-primary"></i>
                {{ trans('update.ielts_tests') }} - {{ trans('update.pending') }}
            </div>
            <span class="badge badge-info">{{ $tests->total() }}</span>
        </div>

        @if($tests->isEmpty())
            <div class="text-center py-4">
                <div class="text-muted">No pending tests</div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <th class="font-11 text-gray-600 text-uppercase py-16 px-20 font-weight-bold border-0">{{ trans('update.test') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.type') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.skill') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.created_by') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.submitted') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.questions') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center" style="width: 220px;">{{ trans('update.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tests as $test)
                            @php
                                $testQuestionsCount = $test->sections->sum('questions_count');
                                $testSkills = [];
                                if ($test->has_listening) $testSkills[] = trans('update.listening');
                                if ($test->has_reading) $testSkills[] = trans('update.reading');
                                if ($test->has_writing) $testSkills[] = trans('update.writing');
                                if ($test->has_speaking) $testSkills[] = trans('update.speaking');
                            @endphp
                            <tr style="transition: all 0.2s ease; border-bottom: 1px solid #f0f0f0;" class="hover-row">
                                <td class="py-16 px-20">
                                    <div class="font-14 font-weight-bold text-dark">{{ $test->title }}</div>
                                    @if($test->description)
                                        <div class="font-12 text-gray-500 mt-4">{{ Str::limit($test->description, 80) }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge" style="display: inline-block; width: 80px; padding: 5px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; {{ $test->type === 'mock' ? 'background: #e3f2fd; color: #1976d2;' : 'background: #e8f5e9; color: #388e3c;' }}">
                                        {{ $test->type === 'mock' ? trans('update.mock') : trans('update.practice') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="font-12 text-gray-700">{{ implode(', ', $testSkills) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="font-13 text-gray-700">
                                        <i class="fas fa-user-circle mr-1 text-gray-400"></i>
                                        {{ $test->creator->full_name ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="font-13 text-gray-600">
                                        <i class="fas fa-clock mr-1 text-gray-400" style="font-size: 11px;"></i>
                                        {{ dateTimeFormat($test->submitted_for_approval_at ?: $test->updated_at, 'j M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge" style="display: inline-block; width: 40px; background: #f0f9ff; border: 2px solid #bae6fd; color: #0369a1; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                        {{ $testQuestionsCount }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 6px;">
                                        <a href="{{ route('admin.ielts_tests.review', $test->id) }}"
                                           class="btn btn-sm"
                                           style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #f8f9fa; color: #495057; border: 1px solid #dee2e6;">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.ielts_tests.approve', $test->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm"
                                                    style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm"
                                                data-toggle="modal" data-target="#rejectTestModal{{ $test->id }}"
                                                style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="rejectTestModal{{ $test->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Reject test</h5>
                                            <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                                        </div>
                                        <form action="{{ route('admin.ielts_tests.reject', $test->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body" style="padding: 24px;">
                                                <p style="margin-bottom: 8px;"><strong>Test:</strong> {{ $test->title }}</p>
                                                <p style="color: #6c757d; font-size: 14px; margin-bottom: 16px;">{{ trans('update.rejection_reason_desc') }}</p>
                                                <textarea name="rejection_reason" class="form-control" rows="4" required
                                                          placeholder="{{ trans('update.rejection_reason_placeholder') }}"
                                                          style="border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;"></textarea>
                                            </div>
                                            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 16px 24px;">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">{{ trans('admin/main.cancel') }}</button>
                                                <button type="submit" class="btn btn-danger" style="border-radius: 6px;">
                                                    <i class="fas fa-ban mr-1"></i> {{ trans('update.reject') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($tests->hasPages())
                <div class="p-3" style="border-top: 1px solid #f0f0f0;">
                    {{ $tests->appends(request()->except('tests_page'))->links() }}
                </div>
            @endif
        @endif
    </div>

    <div class="mt-24"></div>

    {{-- Groups Table --}}
    <div class="bg-white rounded-16 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="px-20 py-16 border-bottom d-flex align-items-center justify-content-between">
            <div class="font-15 font-weight-bold text-dark">
                <i class="fas fa-layer-group mr-8 text-primary"></i>
                {{ trans('update.group') }} - {{ trans('update.pending') }}
            </div>
            <span class="badge badge-info">{{ $groups->total() }}</span>
        </div>

        @if($groups->isEmpty())
            @if($tests->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5 style="font-weight: 600;">{{ trans('update.all_caught_up') }}</h5>
                <p class="text-muted">{{ trans('update.no_pending_question_groups') }}</p>
            </div>
            @else
            <div class="text-center py-4">
                <div class="text-muted">{{ trans('update.no_pending_question_groups') }}</div>
            </div>
            @endif
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <th class="font-11 text-gray-600 text-uppercase py-16 px-20 font-weight-bold border-0">{{ trans('update.group') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.type') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.skill') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.created_by') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.submitted') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('update.questions') }}</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center" style="width: 250px;">{{ trans('update.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                        <tr style="transition: all 0.2s ease; border-bottom: 1px solid #f0f0f0;" class="hover-row">
                            <td class="py-16 px-20">
                                <div class="d-flex align-items-center">
                                    @php
                                        $skillIcons = [
                                            'reading' => ['icon' => 'fa-book', 'color' => '#3b82f6'],
                                            'listening' => ['icon' => 'fa-headphones', 'color' => '#1a3a5c'],
                                            'writing' => ['icon' => 'fa-pen', 'color' => '#8b5cf6'],
                                            'speaking' => ['icon' => 'fa-microphone', 'color' => '#10b981']
                                        ];
                                        $currentSkill = $skillIcons[$group->skill] ?? ['icon' => 'fa-circle', 'color' => '#6c757d'];
                                    @endphp
                                    <div class="rounded-8 p-10 mr-12" style="background: rgba({{ hexdec(substr($currentSkill['color'], 1, 2)) }}, {{ hexdec(substr($currentSkill['color'], 3, 2)) }}, {{ hexdec(substr($currentSkill['color'], 5, 2)) }}, 0.1);">
                                        <i class="fas {{ $currentSkill['icon'] }}" style="color: {{ $currentSkill['color'] }}; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div class="font-14 font-weight-bold text-dark">{{ $group->title }}</div>
                                        @if($group->description)
                                            <div class="font-12 text-gray-500 mt-4">{{ Str::limit($group->description, 60) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 70px; padding: 5px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; {{ $group->bank_type === 'mock' ? 'background: #e3f2fd; color: #1976d2;' : 'background: #e8f5e9; color: #388e3c;' }}">
                                    {{ $group->bank_type === 'mock' ? trans('update.mock') : trans('update.practice') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 100px; padding: 6px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; 
                                    @if($group->skill === 'reading') background: #dbeafe; color: #1e40af;
                                    @elseif($group->skill === 'listening') background: #e0e7ff; color: #4338ca;
                                    @elseif($group->skill === 'writing') background: #fce7f3; color: #be185d;
                                    @elseif($group->skill === 'speaking') background: #d1fae5; color: #065f46;
                                    @endif">
                                    <i class="fas {{ $currentSkill['icon'] }} mr-1" style="font-size: 10px;"></i>
                                    {{ trans('update.'.$group->skill) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="font-13 text-gray-700">
                                    <i class="fas fa-user-circle mr-1 text-gray-400"></i>
                                    {{ $group->creator->full_name ?? 'Unknown' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="font-13 text-gray-600">
                                    <i class="fas fa-clock mr-1 text-gray-400" style="font-size: 11px;"></i>
                                    {{ dateTimeFormat($group->updated_at, 'j M Y, H:i') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 40px; background: #f0f9ff; border: 2px solid #bae6fd; color: #0369a1; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                    {{ $group->questions_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="{{ route('panel.question-groups.show', $group->id) }}" 
                                       class="btn btn-sm" target="_blank"
                                       style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #f8f9fa; color: #495057; border: 1px solid #dee2e6;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.question_groups.approve', $group->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm"
                                                style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm" 
                                            data-toggle="modal" data-target="#rejectModal{{ $group->id }}"
                                            style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Reject Modal --}}
                        <div class="modal fade" id="rejectModal{{ $group->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>{{ trans('update.reject_question_group') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                                    </div>
                                    <form action="{{ route('admin.question_groups.reject', $group->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body" style="padding: 24px;">
                                            <p style="margin-bottom: 8px;"><strong>{{ trans('update.group') }}:</strong> {{ $group->title }}</p>
                                            <p style="color: #6c757d; font-size: 14px; margin-bottom: 16px;">{{ trans('update.rejection_reason_desc') }}</p>
                                            <textarea name="rejection_reason" class="form-control" rows="4" required 
                                                      placeholder="{{ trans('update.rejection_reason_placeholder') }}" 
                                                      style="border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;"></textarea>
                                        </div>
                                        <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 16px 24px;">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">{{ trans('admin/main.cancel') }}</button>
                                            <button type="submit" class="btn btn-danger" style="border-radius: 6px;">
                                                <i class="fas fa-ban mr-1"></i> {{ trans('update.reject_group') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($groups->hasPages())
                <div class="p-3" style="border-top: 1px solid #f0f0f0;">
                    {{ $groups->appends(request()->except('groups_page'))->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

<style>
.hover-row:hover {
    background: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.text-gray-500 { color: #6c757d; }
.text-gray-600 { color: #4b5563; }
.text-gray-700 { color: #374151; }
.text-gray-400 { color: #9ca3af; }
.rounded-8 { border-radius: 8px; }
.rounded-10 { border-radius: 10px; }
.rounded-12 { border-radius: 12px; }
.rounded-16 { border-radius: 16px; }
</style>

@endsection
