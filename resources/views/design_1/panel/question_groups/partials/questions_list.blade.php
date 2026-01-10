{{-- Questions List for Reading/Listening --}}
<div class="card">
    <div class="card-header">
        <h5 class="font-16 font-weight-bold mb-0">
            <x-iconsax-bul-clipboard-text class="icons text-primary mr-8" width="20px" height="20px"/>
            Questions ({{ $group->questions()->count() }})
        </h5>
    </div>
    <div class="card-body p-0">
        @php
            $questions = $group->questions()->get();
        @endphp
        
        @if($questions->isEmpty())
            <div class="text-center py-32">
                <x-iconsax-bul-note-2 class="icons text-gray-400 mb-12" width="48px" height="48px"/>
                <p class="text-gray-500 mb-0">No questions added yet</p>
                <p class="text-gray-400 font-12">Add your first question using the form above</p>
            </div>
        @else
            <div class="questions-list">
                @foreach($questions as $index => $question)
                    <div class="question-item border-bottom p-16">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-8">
                                    <span class="badge badge-dark font-12 mr-8">Q{{ $question->question_number }}</span>
                                    <span class="badge badge-{{ 
                                        $question->question_type === 'multiple_choice' ? 'primary' : 
                                        ($question->question_type === 'fill_blank' ? 'success' : 
                                        ($question->question_type === 'true_false_not_given' ? 'info' : 'secondary'))
                                    }} font-12 d-flex align-items-center">
                                        @if($question->question_type === 'multiple_choice')
                                            <i class="fas fa-list-ul mr-4" style="font-size: 11px;"></i>
                                        @elseif($question->question_type === 'fill_blank')
                                            <i class="fas fa-edit mr-4" style="font-size: 11px;"></i>
                                        @elseif($question->question_type === 'true_false_not_given')
                                            <i class="fas fa-check-double mr-4" style="font-size: 11px;"></i>
                                        @elseif($question->question_type === 'matching_sentence_ending')
                                            <i class="fas fa-link mr-4" style="font-size: 11px;"></i>
                                        @else
                                            <i class="fas fa-file-alt mr-4" style="font-size: 11px;"></i>
                                        @endif
                                        {{ ucwords(str_replace('_', ' ', $question->question_type)) }}
                                    </span>
                                    @if($question->auto_gradable)
                                        <span class="badge badge-success font-12 ml-4">Auto</span>
                                    @endif
                                    <span class="text-gray-500 font-12 ml-8">{{ $question->points }} pt</span>
                                </div>
                                
                                <p class="font-14 text-dark mb-8">{{ Str::limit($question->question_text, 120) }}</p>
                                
                                @if($question->correct_answer)
                                    <div class="d-flex align-items-center">
                                        <x-iconsax-bul-tick-circle class="icons text-success mr-4" width="16px" height="16px"/>
                                        <span class="text-success font-13">Answer: {{ $question->correct_answer }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            
                            <div class="d-flex gap-8">
                                <button type="button" class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center" 
                                        data-toggle="collapse" data-target="#question-{{ $question->id }}"
                                        style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #e0f2fe; color: #0284c7; border: none;"
                                        title="View Details">
                                    <x-iconsax-lin-eye class="icons" width="16px" height="16px"/>
                                </button>
                                <a href="{{ route('panel.questions.edit', $question->id) }}" 
                                   class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center"
                                   style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #f1f5f9; color: #64748b; border: none;"
                                   title="Edit">
                                    <x-iconsax-lin-edit class="icons" width="16px" height="16px"/>
                                </a>
                                <form action="{{ route('panel.questions.destroy', $question->id) }}" method="POST" 
                                      class="d-inline delete-question-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm action-btn d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; padding: 0; border-radius: 8px; background: #fee2e2; color: #dc2626; border: none;"
                                            title="Delete">
                                        <x-iconsax-lin-trash class="icons" width="16px" height="16px"/>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        {{-- Collapsible Details --}}
                        <div id="question-{{ $question->id }}" class="collapse mt-12">
                            <div class="bg-gray-50 rounded-12 p-12">
                                @if($question->instruction)
                                    <p class="font-13 text-gray-600 mb-8"><strong>Instruction:</strong> {{ $question->instruction }}</p>
                                @endif
                                
                                @if($question->answer_options)
                                    <div class="mb-8">
                                        <strong class="font-13 text-gray-700">Options:</strong>
                                        <ul class="mb-0 mt-4">
                                            @foreach(json_decode($question->answer_options, true) as $key => $option)
                                                <li class="font-13 {{ $option === $question->correct_answer ? 'text-success font-weight-bold' : 'text-gray-600' }}">
                                                    {{ $key }}) {{ $option }}
                                                    @if($option === $question->correct_answer)
                                                        <x-iconsax-bul-tick-circle class="icons text-success ml-4" width="14px" height="14px"/>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @if($question->explanation)
                                    <div class="mt-8">
                                        <strong class="font-13 text-gray-700">Explanation:</strong>
                                        <div class="font-13 text-gray-600 mt-4">{!! $question->explanation !!}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
