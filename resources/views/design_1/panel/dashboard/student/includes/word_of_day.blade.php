@php
    /** @var \App\Models\AcademicWordListWord|null $wordOfDay */
    $word = $wordOfDay ?? null;
@endphp

<div class="bg-white rounded-24 p-20 h-100 d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h4 class="font-14 font-weight-bold text-dark mb-0">Word of the Day</h4>
        <span class="font-11 text-gray-400">{{ now()->format('d M Y') }}</span>
    </div>

    @if($word)
        <div class="flex-grow-1">
            <p class="font-18 font-weight-bold text-dark mb-4">{{ $word->word }}</p>

            @if($word->pronunciation)
                <p class="font-12 text-gray-500 mb-8">{{ $word->pronunciation }}</p>
            @endif

            @if($word->translation)
                <p class="font-12 text-primary mb-8 font-weight-bold">{{ $word->translation }}</p>
            @endif

            @if($word->definition)
                <p class="font-12 text-dark mb-8">{{ $word->definition }}</p>
            @endif

            @if($word->example)
                <div class="p-12 rounded-12 bg-gray-100 border-left-primary" style="border-left:3px solid var(--primary);">
                    <p class="font-12 text-gray-500 mb-0 font-italic">{{ $word->example }}</p>
                </div>
            @endif
        </div>

        <a href="/panel/dictionary" class="btn btn-primary btn-sm rounded-pill mt-12 w-100">
            Open Dictionary
        </a>
    @else
        <div class="d-flex-center flex-column text-center p-20 rounded-16 bg-gray-100 flex-grow-1">
            <x-iconsax-bul-book-1 class="icons text-primary" width="32px" height="32px"/>
            <p class="font-12 text-gray-500 mt-8 mb-0">No word available yet.<br>Add words via the Dictionary.</p>
            <a href="/panel/dictionary" class="btn btn-primary btn-sm rounded-pill mt-12">Go to Dictionary</a>
        </div>
    @endif
</div>
