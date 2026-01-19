@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .flashcard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }
    
    .flashcard {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
        min-height: 200px;
    }
    
    .flashcard:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    
    .flashcard.flipped .flashcard-front {
        transform: rotateY(180deg);
    }
    
    .flashcard.flipped .flashcard-back {
        transform: rotateY(0deg);
    }
    
    .flashcard-front, .flashcard-back {
        backface-visibility: hidden;
        transition: transform 0.6s;
    }
    
    .flashcard-back {
        transform: rotateY(180deg);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 24px;
    }
    
    .flashcard-word {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    
    .flashcard-pronunciation {
        font-size: 16px;
        color: #666;
        font-family: 'Courier New', monospace;
        margin-bottom: 16px;
    }
    
    .flashcard-definition {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
    }
    
    .delete-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #ff5252;
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
    }
    
    .delete-btn:hover {
        background: #ff1744;
        transform: scale(1.1);
    }
    
    .flip-hint {
        position: absolute;
        bottom: 16px;
        right: 16px;
        font-size: 12px;
        color: #999;
        font-style: italic;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 16px;
    }
    
    .empty-state-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="font-20 font-weight-bold">{{ trans('panel.my_flashcards') }}</h2>
                    <a href="{{ url('/panel/dictionary') }}" class="btn btn-primary btn-sm">
                        <x-iconsax-lin-book class="mr-1" width="16px" height="16px"/>
                        {{ trans('panel.back_to_dictionary') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-12">
            @if($flashcards->count() > 0)
                <div class="flashcard-grid">
                    @foreach($flashcards as $flashcard)
                        <div class="flashcard" data-id="{{ $flashcard->id }}" onclick="flipCard(this)">
                            <button class="delete-btn" onclick="deleteFlashcard(event, {{ $flashcard->id }})">
                                <x-iconsax-lin-trash class="icons" width="18px" height="18px"/>
                            </button>
                            
                            <div class="flashcard-front">
                                <div class="flashcard-word">{{ $flashcard->word }}</div>
                                @if($flashcard->pronunciation)
                                    <div class="flashcard-pronunciation">{{ $flashcard->pronunciation }}</div>
                                @endif
                                <div class="flip-hint">Click to see definition</div>
                            </div>
                            
                            <div class="flashcard-back">
                                <div class="flashcard-word mb-3">{{ trans('panel.definition') }}</div>
                                <div class="flashcard-definition">{{ $flashcard->definition }}</div>
                                
                                @if($flashcard->example)
                                    <div class="mt-3">
                                        <strong>{{ trans('panel.example') }}:</strong>
                                        <div class="text-muted mt-1" style="font-style: italic;">{{ $flashcard->example }}</div>
                                    </div>
                                @endif
                                
                                @if($flashcard->translation)
                                    <div class="mt-3">
                                        <strong>{{ trans('panel.translation') }}:</strong>
                                        <div class="mt-1">{{ $flashcard->translation }}</div>
                                    </div>
                                @endif
                                
                                <div class="flip-hint">Click to see word</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $flashcards->links() }}
                </div>
            @else
                <div class="panel-section-card py-40 px-25">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <x-iconsax-bul-bookmark-2 width="80px" height="80px" class="text-gray-300"/>
                        </div>
                        <div class="empty-state-text">{{ trans('panel.no_flashcards_yet') }}</div>
                        <a href="{{ url('/panel/dictionary') }}" class="btn btn-primary">
                            <x-iconsax-lin-book class="mr-1" width="18px" height="18px"/>
                            {{ trans('panel.start_learning') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
    function flipCard(card) {
        // Don't flip if clicking delete button
        if (event.target.closest('.delete-btn')) {
            return;
        }
        
        card.classList.toggle('flipped');
    }

    function deleteFlashcard(event, id) {
        event.stopPropagation();
        
        Swal.fire({
            title: '{{ trans("panel.are_you_sure") }}',
            text: '{{ trans("panel.flashcard_delete_confirm") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ trans("panel.yes_delete") }}',
            cancelButtonText: '{{ trans("panel.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/panel/dictionary/flashcards/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ trans("panel.deleted") }}',
                            text: '{{ trans("panel.flashcard_deleted_successfully") }}',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans("panel.error") }}',
                        text: '{{ trans("panel.something_went_wrong") }}'
                    });
                });
            }
        });
    }
</script>
@endpush
