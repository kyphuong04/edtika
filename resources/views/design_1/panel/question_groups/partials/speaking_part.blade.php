{{-- Speaking Part View - Premium & Modern UI --}}
@php
    $partType = 'Part 1'; 
    if ($group->question_type) {
        // Support both old format (part1, part2, part3) and new format (part1_questions, part2_cue_card, part3_discussion)
        if ($group->question_type === 'part1' || $group->question_type === 'part1_questions') { 
            $partType = 'Part 1'; 
        }
        elseif ($group->question_type === 'part2' || $group->question_type === 'part2_cue_card') { 
            $partType = 'Part 2'; 
        }
        elseif ($group->question_type === 'part3' || $group->question_type === 'part3_discussion') { 
            $partType = 'Part 3'; 
        }
    } elseif (strpos($group->title ?? '', 'Part 2') !== false || strpos($group->title ?? '', 'Part2') !== false) {
        $partType = 'Part 2';
    } elseif (strpos($group->title ?? '', 'Part 3') !== false || strpos($group->title ?? '', 'Part3') !== false) {
        $partType = 'Part 3';
    }
    
    $partInfo = [
        'Part 1' => ['duration' => '4-5 mins', 'icon' => 'fa-user-tie', 'color' => '#3b82f6'],
        'Part 2' => ['duration' => '3-4 mins', 'icon' => 'fa-id-card', 'color' => '#f59e0b'],
        'Part 3' => ['duration' => '4-5 mins', 'icon' => 'fa-comments', 'color' => '#8b5cf6']
    ];
    $current = $partInfo[$partType];
@endphp

<div class="row">
    {{-- LEFT: Speaking Task Info --}}
    <div class="col-lg-5">
        <div class="card speaking-main-card shadow-sm border-0 mb-24">
            <div class="card-header speaking-premium-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <h5 class="text-white font-18 font-weight-bold mb-0">
                        <i class="fas fa-microphone-alt mr-10"></i>{{ $group->title ?? 'Speaking Unit' }}
                    </h5>
                    <span class="badge badge-part-tag">{{ $partType }}</span>
                </div>
            </div>
            
            <div class="card-body p-24">
                {{-- Task Meta Info --}}
                <div class="d-flex gap-20 mb-20 border-bottom pb-16">
                    <div class="meta-pill">
                        <i class="fas fa-history text-primary mr-8"></i>
                        <span>{{ $current['duration'] }}</span>
                    </div>
                    <div class="meta-pill">
                        <i class="fas fa-layer-group text-success mr-8"></i>
                        <span>{{ $group->questions()->count() }} Questions</span>
                    </div>
                </div>

                {{-- Instructions --}}
                @if($group->instructions)
                    <div class="instruction-box mb-20">
                        <div class="font-12 font-weight-bold text-uppercase text-gray-400 mb-8 tracking-wider">Instructions</div>
                        <div class="instruction-text">{!! nl2br(e($group->instructions)) !!}</div>
                    </div>
                @endif
                
                {{-- The Cue Card / Topic Section --}}
                @if($group->passage)
                    <div class="cue-card-container">
                        <div class="cue-card-header">
                            <i class="fas {{ $current['icon'] }} mr-8"></i>
                            {{ $partType === 'Part 2' ? 'Candidate Task Card' : 'Discussion Topic' }}
                        </div>
                        <div class="cue-card-body">
                            {!! $group->passage !!}
                        </div>
                    </div>
                @else
                    <div class="empty-placeholder py-40">
                        <i class="fas fa-plus-circle fa-2x text-gray-200 mb-12"></i>
                        <p class="text-gray-400 font-13">Add cue card content in group settings</p>
                    </div>
                @endif

                <div class="mt-24">
                    <a href="{{ route('panel.question-groups.edit', $group->id) }}" class="btn btn-edit-group w-100">
                        <i class="fas fa-cog mr-8"></i>Configure This Part
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- RIGHT: Questions Management --}}
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-16 overflow-hidden">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between p-20">
                <h5 class="font-16 font-weight-bold text-dark mb-0">
                    <i class="fas fa-list-ul text-primary mr-10"></i>Question Navigator
                </h5>
                <button type="button" class="btn btn-add-modern" 
                        data-toggle="modal" data-target="#addQuestionModal" data-backdrop="false">
                    <i class="fas fa-plus-circle mr-8"></i>Add Question
                </button>
            </div>
            
            <div class="card-body p-0">
                @php $questions = $group->questions()->orderBy('id')->get(); @endphp
                @if($questions->isEmpty())
                    <div class="text-center py-60 bg-light-gray">
                        <div class="empty-illu mb-16">
                            <i class="fas fa-comment-slash"></i>
                        </div>
                        <p class="text-gray-500 font-14">Ready to start? Add your first question.</p>
                    </div>
                @else
                    <div class="speaking-list-wrapper">
                        @foreach($questions as $index => $question)
                            <div class="speaking-item">
                                <div class="item-number">{{ $index + 1 }}</div>
                                <div class="item-main">
                                    <div class="item-text font-15">{{ $question->question_text }}</div>
                                    @if($question->explanation)
                                        <div class="item-sample-tag">
                                            <i class="fas fa-lightbulb mr-4"></i>Model answer included
                                        </div>
                                    @endif
                                </div>
                                <div class="item-actions">
                                    <a href="{{ route('panel.questions.edit', $question->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('panel.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Delete?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL FIXED - PREMIUM STYLE --}}
<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-premium">
            <div class="modal-header border-0 position-relative">
                <h5 class="modal-title font-16 font-weight-bold text-white">
                    <i class="fas fa-microphone mr-10"></i>New Speaking Question
                </h5>
                <button type="button" class="close-custom-red" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('panel.questions.store', $group->id) }}" method="POST">
                @csrf
                <input type="hidden" name="question_type" value="speaking">
                <input type="hidden" name="speaking_part" value="{{ $partType }}">
                <div class="modal-body p-30">
                    <div class="row mb-20">
                        <div class="col-md-4">
                            <label class="font-weight-bold text-dark font-13">Display Order</label>
                            <input type="number" name="question_number" class="form-control-modern" value="{{ $questions->count() + 1 }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="font-weight-bold text-dark font-13">Context Type</label>
                            <select name="speaking_type" class="form-control-modern">
                                <option value="general">Standard Question</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="abstract">Abstract/Discussion</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-20">
                        <label class="font-weight-bold text-dark font-13">Question Sentence *</label>
                        <textarea name="question_text" class="form-control-modern" rows="3" placeholder="What is your favorite...?" required></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark font-13">Sample Model Answer</label>
                        <textarea name="explanation" class="form-control-modern" rows="5" placeholder="Provide a band 8.0+ sample..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-24 bg-light">
                    <button type="button" class="btn btn-link text-gray-500 font-weight-bold mr-16" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save-question">Create Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* CSS Reset & Premium Variables */
:root {
    --speaking-primary: #4f46e5;
    --speaking-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    --bg-light: #f8fafc;
}

/* Modal Z-index Fix */
.modal-backdrop { display: none !important; }
#addQuestionModal { background: rgba(15, 23, 42, 0.6) !important; z-index: 9999 !important; }

/* Close Button Red */
.close-custom-red {
    position: absolute; right: 20px; top: 18px;
    background: #fff; border: none; color: #ef4444; width: 32px; height: 32px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; cursor: pointer; z-index: 10;
}
.close-custom-red:hover { background: #ef4444; color: #fff; transform: rotate(90deg); }

/* Left Card Styling */
.speaking-premium-header { background: var(--speaking-gradient); padding: 24px; border: none; }
.badge-part-tag { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.4); padding: 6px 14px; border-radius: 30px; font-weight: 700; text-transform: uppercase; font-size: 11px; }

.meta-pill { display: flex; align-items: center; background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; }

.instruction-box { background: #f0f9ff; border-radius: 12px; padding: 16px; border-left: 4px solid #3b82f6; }
.instruction-text { font-size: 13px; color: #1e40af; line-height: 1.6; }

.cue-card-container {
    background: #fff; border: 2px dashed #e2e8f0; border-radius: 16px; overflow: hidden;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}
.cue-card-header { background: #f8fafc; padding: 12px 20px; border-bottom: 2px dashed #e2e8f0; font-weight: 700; color: #1e293b; font-size: 13px; text-transform: uppercase; }
.cue-card-body { padding: 20px; font-size: 15px; line-height: 1.8; color: #334155; }

.btn-edit-group { background: #f1f5f9; color: #475569; border-radius: 10px; padding: 12px; font-weight: 700; font-size: 13px; border: none; transition: 0.3s; }
.btn-edit-group:hover { background: #e2e8f0; color: #1e293b; }

/* Right Card & Questions List */
.btn-add-modern { background: #1e293b; color: white; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 700; border: none; transition: 0.3s; }
.btn-add-modern:hover { background: #0f172a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

.speaking-list-wrapper { background: white; }
.speaking-item {
    display: flex; align-items: center; gap: 16px; padding: 20px;
    border-bottom: 1px solid #f1f5f9; transition: 0.2s;
}
.speaking-item:hover { background: #f8fafc; }
.item-number {
    width: 32px; height: 32px; background: #e0e7ff; color: #4f46e5;
    border-radius: 8px; display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 14px; flex-shrink: 0;
}
.item-main { flex-grow: 1; }
.item-text { font-weight: 600; color: #1e293b; margin-bottom: 4px; }
.item-sample-tag { display: inline-flex; align-items: center; font-size: 11px; color: #10b981; font-weight: 700; background: #ecfdf5; padding: 2px 8px; border-radius: 4px; }

.btn-action {
    width: 32px; height: 32px; border-radius: 6px; display: inline-flex;
    align-items: center; justify-content: center; border: none; font-size: 12px; transition: 0.2s;
}
.btn-action.edit { background: #fffbeb; color: #d97706; }
.btn-action.delete { background: #fef2f2; color: #dc2626; }
.btn-action:hover { transform: scale(1.1); }

/* Modal Premium Styling */
.modal-premium .modal-header { background: var(--speaking-gradient); padding: 20px 30px; }
.form-control-modern {
    width: 100%; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 10px; font-size: 14px; transition: 0.3s;
}
.form-control-modern:focus { border-color: #4f46e5; background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
.btn-save-question { background: #4f46e5; color: white; border-radius: 10px; padding: 12px 30px; font-weight: 700; border: none; }

.empty-illu { font-size: 40px; color: #e2e8f0; }
.rounded-16 { border-radius: 16px; }
</style>