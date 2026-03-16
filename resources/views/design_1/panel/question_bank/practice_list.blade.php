@extends('design_1.panel.layouts.panel')

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Practice Question Bank</h1>
            <p class="text-gray-500 font-14 mt-4">Browse by topic • {{ $questions->total() }} questions</p>
        </div>
        <div class="d-flex gap-8">
            {{-- View Toggle --}}
            <div class="btn-group">
                <a href="?{{ http_build_query(array_merge(request()->except('view'), ['view' => 'table'])) }}" 
                   class="btn btn-sm btn-{{ $viewMode === 'table' ? 'success' : 'outline-secondary' }}">
                    <x-iconsax-bul-menu class="icons" width="16px" height="16px"/>
                </a>
                <a href="?{{ http_build_query(array_merge(request()->except('view'), ['view' => 'card'])) }}" 
                   class="btn btn-sm btn-{{ $viewMode === 'card' ? 'success' : 'outline-secondary' }}">
                    <x-iconsax-bul-element-3 class="icons" width="16px" height="16px"/>
                </a>
            </div>
            <a href="{{ route('panel.question_bank.create', ['bank_type' => 'practice']) }}" class="btn btn-success btn-sm">
                <x-iconsax-bul-add class="icons mr-8" width="16px" height="16px"/>Add
            </a>
        </div>
    </div>

    {{-- Compact Filters --}}
    <form method="GET" class="bg-white p-16 rounded-24 mb-16">
        <div class="row align-items-center">
            <div class="col-md-2">
                <select name="skill" class="form-control form-control-sm">
                    <option value="">All Skills</option>
                    <option value="listening" {{ request('skill') == 'listening' ? 'selected' : '' }}>🎧 Listening</option>
                    <option value="reading" {{ request('skill') == 'reading' ? 'selected' : '' }}>📖 Reading</option>
                    <option value="writing" {{ request('skill') == 'writing' ? 'selected' : '' }}>✍️ Writing</option>
                    <option value="speaking" {{ request('skill') == 'speaking' ? 'selected' : '' }}>🗣️ Speaking</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="difficulty" class="form-control form-control-sm">
                    <option value="">All Levels</option>
                    <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>⚡ Beginner</option>
                    <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>⚡ Intermediate</option>
                    <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>⚡ Advanced</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="target_band" class="form-control form-control-sm">
                    <option value="">🎯 All Bands</option>
                    <option value="5.0" {{ request('target_band') == '5.0' ? 'selected' : '' }}>5.0</option>
                    <option value="5.5" {{ request('target_band') == '5.5' ? 'selected' : '' }}>5.5</option>
                    <option value="6.0" {{ request('target_band') == '6.0' ? 'selected' : '' }}>6.0</option>
                    <option value="6.5" {{ request('target_band') == '6.5' ? 'selected' : '' }}>6.5</option>
                    <option value="7.0" {{ request('target_band') == '7.0' ? 'selected' : '' }}>7.0</option>
                    <option value="7.5" {{ request('target_band') == '7.5' ? 'selected' : '' }}>7.5</option>
                    <option value="8.0" {{ request('target_band') == '8.0' ? 'selected' : '' }}>8.0+</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="🔍 Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-sm w-100">Filter</button>
            </div>
        </div>
        <input type="hidden" name="view" value="{{ $viewMode }}">
        <input type="hidden" name="topic" value="{{ request('topic') }}">
    </form>

    <div class="row">
        {{-- Topics Sidebar --}}
        <div class="col-lg-3">
            <div class="bg-white rounded-24 p-16 sticky-sidebar">
                <h4 class="font-12 font-weight-bold text-gray-500 mb-12 text-uppercase">Topics</h4>
                
                <a href="?" class="topic-item d-flex align-items-center justify-content-between p-12 rounded-12 mb-4 {{ !request('topic') ? 'active success' : '' }}">
                    <span class="font-14">All Topics</span>
                    <span class="badge badge-light">{{ $questions->total() }}</span>
                </a>

                @if($topics && count($topics) > 0)
                    @foreach(array_slice($topics, 0, 15) as $topic => $count)
                        <a href="?topic={{ urlencode($topic) }}&view={{ $viewMode }}" 
                           class="topic-item d-flex align-items-center justify-content-between p-12 rounded-12 mb-4 {{ request('topic') === $topic ? 'active success' : '' }}">
                            <span class="font-14">{{ ucfirst($topic) }}</span>
                            <span class="badge">{{ $count }}</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Questions Area --}}
        <div class="col-lg-9">
            @if($questions->count() > 0)
                @if($viewMode === 'card')
                    {{-- Card View --}}
                    <div class="question-grid">
                        @foreach($questions as $question)
                            <div class="question-card">
                                @php
                                    $skillInfo = [
                                        'listening' => ['icon' => '🎧', 'color' => 'skill-listening', 'label' => 'Listening'],
                                        'reading' => ['icon' => '📖', 'color' => 'skill-reading', 'label' => 'Reading'],
                                        'writing' => ['icon' => '✍️', 'color' => 'skill-writing', 'label' => 'Writing'],
                                        'speaking' => ['icon' => '🗣️', 'color' => 'skill-speaking', 'label' => 'Speaking'],
                                    ];
                                    $skill = $skillInfo[$question->skill] ?? ['icon' => '📝', 'color' => 'skill-default', 'label' => ucfirst($question->skill)];
                                @endphp
                                
                                <div class="d-flex justify-content-between align-items-start mb-12">
                                    <div class="skill-indicator {{ $skill['color'] }}">
                                        <span class="skill-icon">{{ $skill['icon'] }}</span>
                                        <span class="skill-label">{{ $skill['label'] }}</span>
                                    </div>
                                    @if($question->target_band)
                                        <div class="band-indicator">
                                            <span class="band-label">Band {{ $question->target_band }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Audio Player for Listening --}}
                                @if($question->skill === 'listening' && $question->audio_file)
                                    <div class="audio-player-mini mb-12">
                                        <audio controls preload="metadata" style="width: 100%; height: 36px;">
                                            <source src="{{ Storage::disk('public')->url($question->audio_file) }}" type="audio/mpeg">
                                            <source src="{{ Storage::disk('public')->url($question->audio_file) }}" type="audio/wav">
                                            Your browser does not support audio.
                                        </audio>
                                    </div>
                                @endif

                                <h5 class="question-text">{{ \Illuminate\Support\Str::limit($question->question_text, 120) }}</h5>

                                <div class="question-meta">
                                    <div class="meta-badges">
                                        <span class="badge badge-{{ $question->difficulty_badge }}">{{ ucfirst($question->difficulty_level) }}</span>
                                        @if($question->practice_focus)
                                            <span class="badge badge-warning">{{ $question->practice_focus }}</span>
                                        @endif
                                    </div>
                                    <div class="usage-count">
                                        <x-iconsax-bul-chart-2 class="icons" width="14px" height="14px"/>
                                        <span>{{ $question->usage_count }}</span>
                                    </div>
                                </div>

                                @if($question->tags && count($question->tags) > 0)
                                    <div class="question-tags">
                                        @foreach(array_slice($question->tags, 0, 3) as $tag)
                                            <a href="?topic={{ urlencode($tag) }}&view=card" class="tag-link">{{ $tag }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="question-actions">
                                    <a href="{{ route('panel.question_bank.edit', ['practice', $question->id]) }}" class="btn-action btn-edit">
                                        <x-iconsax-bul-edit class="icons" width="16px" height="16px"/>
                                    </a>
                                    <a href="{{ route('panel.question_bank.delete', ['practice', $question->id]) }}" 
                                       class="btn-action btn-delete" onclick="return confirm('Delete?')">
                                        <x-iconsax-bul-trash class="icons" width="16px" height="16px"/>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Table View --}}
                    <div class="question-table-container">
                        <table class="question-table">
                            <thead>
                                <tr>
                                    <th width="50">Skill</th>
                                    <th>Question</th>
                                    <th width="80">Band</th>
                                    <th width="120">Level</th>
                                    <th width="80">Used</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                    @php
                                        $skillInfo = [
                                            'listening' => ['icon' => '🎧', 'color' => 'skill-listening'],
                                            'reading' => ['icon' => '📖', 'color' => 'skill-reading'],
                                            'writing' => ['icon' => '✍️', 'color' => 'skill-writing'],
                                            'speaking' => ['icon' => '🗣️', 'color' => 'skill-speaking'],
                                        ];
                                        $skill = $skillInfo[$question->skill] ?? ['icon' => '📝', 'color' => ''];
                                    @endphp
                                    <tr>
                                        <td class="skill-cell">
                                            <div class="skill-badge {{ $skill['color'] }}">
                                                <span class="skill-icon-sm">{{ $skill['icon'] }}</span>
                                            </div>
                                        </td>
                                        <td class="question-cell">
                                            <div class="question-title">{{ \Illuminate\Support\Str::limit($question->question_text, 100) }}</div>
                                            <div class="question-info">
                                                @if($question->practice_focus)
                                                    <span class="focus-badge">{{ $question->practice_focus }}</span>
                                                @endif
                                                @if($question->tags)
                                                    @foreach(array_slice($question->tags, 0, 2) as $tag)
                                                        <a href="?topic={{ urlencode($tag) }}&view=table" class="tag-link-sm">{{ $tag }}</a>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="band-cell">
                                            @if($question->target_band)
                                                <span class="badge badge-info">{{ $question->target_band }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="level-cell">
                                            <span class="badge badge-{{ $question->difficulty_badge }}">{{ ucfirst($question->difficulty_level) }}</span>
                                        </td>
                                        <td class="usage-cell">
                                            <div class="usage-indicator">
                                                <x-iconsax-bul-chart-2 class="icons" width="14px" height="14px"/>
                                                <span>{{ $question->usage_count }}</span>
                                            </div>
                                        </td>
                                        <td class="actions-cell">
                                            <div class="table-actions">
                                                <a href="{{ route('panel.question_bank.edit', ['practice', $question->id]) }}" class="btn-action-sm">
                                                    <x-iconsax-bul-edit class="icons" width="14px" height="14px"/>
                                                </a>
                                                <a href="{{ route('panel.question_bank.delete', ['practice', $question->id]) }}" 
                                                   class="btn-action-sm btn-delete" onclick="return confirm('Delete?')">
                                                    <x-iconsax-bul-trash class="icons" width="14px" height="14px"/>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="table-pagination d-flex align-items-center justify-content-between">
                            <p class="font-14 text-gray-500 mb-0">
                                Showing {{ $questions->firstItem() }} - {{ $questions->lastItem() }} of {{ $questions->total() }} questions
                            </p>
                            {{ $questions->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <x-iconsax-bul-search-normal class="icons" width="48px" height="48px"/>
                    </div>
                    <h4>{{ request('topic') ? 'No "'.request('topic').'" questions' : 'No questions found' }}</h4>
                    <p>Try adjusting filters or add new questions</p>
                    <a href="{{ route('panel.question_bank.create', ['bank_type' => 'practice']) }}" class="btn btn-success">
                        <x-iconsax-bul-add class="icons mr-8" width="16px" height="16px"/>Add Practice Question
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
/* Sidebar Topics */
.sticky-sidebar { position: sticky; top: 20px; }
.topic-item { text-decoration: none; color: #374151; transition: all 0.2s; }
.topic-item:hover { background-color: #f3f4f6; transform: translateX(4px); }
.topic-item.active.success { background-color: rgba(40, 199, 111, 0.1); color: #28c76f; font-weight: 600; }
.topic-item.active.success .badge { background-color: #28c76f; color: white; }

/* Card View */
.question-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
.question-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.3s; position: relative; overflow: hidden; }
.question-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.12); transform: translateY(-2px); }

.skill-indicator { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; }
.skill-listening { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.skill-reading { background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%); color: white; }
.skill-writing { background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); color: white; }
.skill-speaking { background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%); color: white; }

.band-indicator { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); padding: 6px 12px; border-radius: 8px; color: white; font-size: 12px; font-weight: 600; }

.question-text { font-size: 15px; line-height: 1.6; color: #1f2937; margin-bottom: 16px; min-height: 48px; }
.question-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.meta-badges { display: flex; gap: 6px; }
.usage-count { display: flex; align-items: center; gap: 4px; color: #6b7280; font-size: 13px; }
.question-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px; }
.tag-link { font-size: 12px; padding: 4px 10px; background: #f3f4f6; border-radius: 6px; color: #6b7280; text-decoration: none; transition: all 0.2s; }
.tag-link:hover { background: #e5e7eb; color: #374151; }
.question-actions { display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #f3f4f6; }
.btn-action { flex: 1; padding: 8px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1px solid #e5e7eb; }
.btn-edit:hover { background: #f3f4f6; border-color: #28c76f; color: #28c76f; }
.btn-delete:hover { background: #fee2e2; border-color: #ef4444; color: #ef4444; }

/* Table View */
.question-table-container { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.question-table { width: 100%; border-collapse: collapse; }
.question-table thead { background: #f9fafb; }
.question-table th { padding: 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #e5e7eb; }
.question-table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f3f4f6; }
.question-table tbody tr:hover { background: #f9fafb; }
.question-table td { padding: 16px; vertical-align: middle; }

.skill-cell { text-align: center; }
.skill-badge { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; font-size: 20px; }
.skill-badge.skill-listening { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.skill-badge.skill-reading { background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%); }
.skill-badge.skill-writing { background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); }
.skill-badge.skill-speaking { background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%); }

.question-cell { max-width: 500px; }
.question-title { font-size: 14px; color: #1f2937; margin-bottom: 6px; line-height: 1.5; }
.question-info { display: flex; gap: 6px; flex-wrap: wrap; }
.focus-badge { font-size: 11px; padding: 2px 8px; background: #fff3cd; border-radius: 4px; color: #856404; }
.tag-link-sm { font-size: 11px; padding: 2px 8px; background: #eff6ff; border-radius: 4px; color: #3b82f6; text-decoration: none; }
.tag-link-sm:hover { background: #dbeafe; }

.band-cell, .level-cell, .usage-cell, .actions-cell { text-align: center; }
.usage-indicator { display: inline-flex; align-items: center; gap: 4px; color: #6b7280; }
.table-actions { display: flex; gap: 6px; justify-content: center; }
.btn-action-sm { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #e5e7eb; transition: all 0.2s; }
.btn-action-sm:hover { background: #f3f4f6; border-color: #28c76f; }
.btn-action-sm.btn-delete:hover { background: #fee2e2; border-color: #ef4444; }
.table-pagination { padding: 20px; border-top: 1px solid #f3f4f6; }
.table-pagination .pagination { margin-bottom: 0; }
.table-pagination .pagination .page-item .page-link { border-radius: 8px; margin: 0 2px; color: #1a3a5c; border-color: #e5e7eb; }
.table-pagination .pagination .page-item.active .page-link { background-color: #1a3a5c; border-color: #1a3a5c; color: #fff; }
.table-pagination .pagination .page-item.disabled .page-link { color: #adb5bd; }

/* Empty State */
.empty-state { background: white; border-radius: 16px; padding: 80px 40px; text-align: center; }
.empty-icon { display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 16px; background: #f3f4f6; color: #9ca3af; margin-bottom: 20px; }
.empty-state h4 { font-size: 18px; color: #1f2937; margin-bottom: 8px; }
.empty-state p { color: #6b7280; margin-bottom: 24px; }
</style>
@endsection
