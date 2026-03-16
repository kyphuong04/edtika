@extends('design_1.panel.layouts.panel')

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">{{ ucfirst($bankType) }} Question Groups</h1>
            <p class="text-gray-500 font-14 mt-4">Manage groups of questions with shared passages/audio</p>
        </div>
        <div class="d-flex gap-8">
            <a href="{{ route('panel.question_bank.' . $bankType . '.list') }}" class="btn btn-outline-secondary">
                <x-iconsax-lin-document class="icons mr-8" width="16px" height="16px"/>
                Standalone Questions
            </a>
            <a href="{{ route('panel.question_bank.groups.create', $bankType) }}" class="btn btn-primary">
                <x-iconsax-bul-add-circle class="icons mr-8" width="16px" height="16px"/>
                Create Group
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-16 rounded-24 mb-24">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3 mb-8">
                <label class="font-12 text-gray-500 mb-8">Skill</label>
                <select name="skill" class="form-control">
                    <option value="">All Skills</option>
                    <option value="listening" {{ request('skill') == 'listening' ? 'selected' : '' }}>🎧 Listening</option>
                    <option value="reading" {{ request('skill') == 'reading' ? 'selected' : '' }}>📖 Reading</option>
                    <option value="writing" {{ request('skill') == 'writing' ? 'selected' : '' }}>✍️ Writing</option>
                    <option value="speaking" {{ request('skill') == 'speaking' ? 'selected' : '' }}>🗣️ Speaking</option>
                </select>
            </div>

            <div class="col-md-3 mb-8">
                <label class="font-12 text-gray-500 mb-8">Difficulty</label>
                <select name="difficulty" class="form-control">
                    <option value="">All Levels</option>
                    <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>

            <div class="col-md-4 mb-8">
                <label class="font-12 text-gray-500 mb-8">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search group title..." value="{{ request('search') }}">
            </div>

            <div class="col-md-2 mb-8">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    {{-- Groups Grid --}}
    @if($groups->count() > 0)
        <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-20 mb-24">
            @foreach($groups as $group)
                <div class="bg-white p-20 rounded-24 hover-shadow" style="transition: all 0.3s;">
                    {{-- Header --}}
                    <div class="d-flex align-items-start justify-content-between mb-16">
                        <div class="flex-1">
                            <h4 class="font-16 font-weight-bold text-dark mb-8">{{ $group->title }}</h4>
                            <div class="d-flex align-items-center gap-8 flex-wrap">
                                {{-- Skill Badge --}}
                                <span class="badge badge-{{ $group->skill === 'listening' ? 'primary' : ($group->skill === 'reading' ? 'info' : 'primary') }}">
                                    {{ $group->skill_label }}
                                </span>
                                
                                {{-- Difficulty --}}
                                <span class="badge badge-{{ $group->difficulty_badge }}">
                                    {{ ucfirst($group->difficulty_level) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($group->description)
                        <p class="text-gray-600 font-14 mb-12" style="line-height: 1.5;">
                            {{ Str::limit($group->description, 120) }}
                        </p>
                    @endif

                    {{-- Stats --}}
                    <div class="d-flex align-items-center gap-16 mb-16 pb-16" style="border-bottom: 1px solid #f3f4f6;">
                        <div class="d-flex align-items-center gap-6">
                            <x-iconsax-bul-message-question class="icons text-primary" width="16px" height="16px"/>
                            <span class="font-14 text-gray-600">{{ $group->question_count }} questions</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-6">
                            <x-iconsax-bul-chart class="icons text-success" width="16px" height="16px"/>
                            <span class="font-14 text-gray-600">Used {{ $group->usage_count }}x</span>
                        </div>
                    </div>

                    {{-- Audio Player for Listening Groups --}}
                    @if($group->skill === 'listening' && ($group->audio_file || $group->audio_path))
                        <div class="audio-player mb-16 p-12 bg-gray-100 rounded-12">
                            <div class="d-flex align-items-center gap-8 mb-8">
                                <x-iconsax-bul-music-play class="icons text-primary" width="16px" height="16px"/>
                                <span class="font-12 font-weight-500 text-dark">Audio Preview</span>
                            </div>
                            <audio controls preload="metadata" style="width: 100%; height: 40px;">
                                <source src="{{ $group->audio_url }}" type="audio/mpeg">
                                Your browser does not support audio.
                            </audio>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if($group->tags && is_array($group->tags))
                        <div class="d-flex flex-wrap gap-6 mb-16">
                            @foreach(array_slice($group->tags, 0, 3) as $tag)
                                <span class="badge badge-light font-12">{{ $tag }}</span>
                            @endforeach
                            @if(count($group->tags) > 3)
                                <span class="badge badge-light font-12">+{{ count($group->tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-flex gap-8">
                        <a href="{{ route('panel.question_bank.groups.edit', [$bankType, $group->id]) }}" class="btn btn-sm btn-outline-primary flex-1">
                            <x-iconsax-lin-edit class="icons mr-4" width="14px" height="14px"/>
                            Edit
                        </a>
                        <a href="{{ route('panel.question_bank.groups.delete', [$bankType, $group->id]) }}" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this group and {{ $group->question_count }} questions?')">
                            <x-iconsax-bul-trash class="icons" width="14px" height="14px"/>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex align-items-center justify-content-between mt-8 px-4">
            <p class="font-14 text-gray-500 mb-0">
                Showing {{ $groups->firstItem() }} - {{ $groups->lastItem() }} of {{ $groups->total() }} groups
            </p>
            {{ $groups->appends(request()->query())->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white p-40 rounded-24 text-center">
            <x-iconsax-bul-folder-open class="icons text-gray-400 mb-16" width="64px" height="64px"/>
            <h3 class="font-18 font-weight-bold text-dark mb-8">No Question Groups Yet</h3>
            <p class="text-gray-500 font-14 mb-24">Create your first group to organize questions under one passage or audio</p>
            <a href="{{ route('panel.question_bank.groups.create', $bankType) }}" class="btn btn-primary">
                <x-iconsax-bul-add-circle class="icons mr-8" width="16px" height="16px"/>
                Create First Group
            </a>
        </div>
    @endif
</section>

<style>
.hover-shadow {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.hover-shadow:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}
.pagination { margin-bottom: 0; }
.pagination .page-item .page-link { border-radius: 8px; margin: 0 2px; color: #1a3a5c; border-color: #e5e7eb; }
.pagination .page-item.active .page-link { background-color: #1a3a5c; border-color: #1a3a5c; color: #fff; }
.pagination .page-item.disabled .page-link { color: #adb5bd; }
</style>
@endsection
