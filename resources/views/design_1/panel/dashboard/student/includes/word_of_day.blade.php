@php
    /** @var \App\Models\AcademicWordListWord|null $wordOfDay */
    $word = $wordOfDay ?? null;
    $fallbackWord = is_array($word) ? $word : null;
@endphp

<div class="bg-white rounded-24 p-20 h-100 d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h4 class="font-14 font-weight-bold text-dark mb-0">Word of the Day</h4>
        <span class="font-11 text-gray-400">{{ now()->format('d M Y') }}</span>
    </div>

    @if(is_object($word))
        <div class="flex-grow-1 rounded-20 p-16" style="background:linear-gradient(180deg,#ffffff 0%,#f8f6ff 100%);border:1px solid #efe8ff;">
            <div class="d-flex align-items-center justify-content-between mb-10">
                <span class="badge badge-light font-11 px-10 py-6" style="background:#efe8ff;color:#5b21b6;">Word of the day</span>
                @if(!empty($word->word_type))
                    <span class="font-11 text-gray-400">{{ $word->word_type }}</span>
                @endif
            </div>

            <p class="font-22 font-weight-bold text-dark mb-2">{{ $word->word }}</p>

            @if(!empty($word->pronunciation))
                <p class="font-12 text-gray-500 mb-8">/{{ $word->pronunciation }}/</p>
            @endif

            @if(!empty($word->translation))
                <p class="font-13 text-primary mb-10 font-weight-bold">{{ $word->translation }}</p>
            @endif

            @if(!empty($word->definition))
                <p class="font-12 text-dark mb-10">{{ $word->definition }}</p>
            @endif

            @if(!empty($word->example))
                <div class="p-12 rounded-12 bg-white border-left-primary" style="border-left:3px solid var(--primary);">
                    <p class="font-12 text-gray-500 mb-0 font-italic">{{ $word->example }}</p>
                </div>
            @endif
        </div>
    @elseif(!empty($fallbackWord))
        <div class="flex-grow-1 rounded-20 p-16" style="background:linear-gradient(180deg,#ffffff 0%,#f8f6ff 100%);border:1px solid #efe8ff;">
            <div class="d-flex align-items-center justify-content-between mb-10">
                <span class="badge badge-light font-11 px-10 py-6" style="background:#efe8ff;color:#5b21b6;">Word of the day</span>
                <span class="font-11 text-gray-400">Daily fallback</span>
            </div>

            <p class="font-22 font-weight-bold text-dark mb-2">{{ $fallbackWord['word'] }}</p>

            @if(!empty($fallbackWord['pronunciation']))
                <p class="font-12 text-gray-500 mb-8">/{{ $fallbackWord['pronunciation'] }}/</p>
            @endif

            @if(!empty($fallbackWord['translation']))
                <p class="font-13 text-primary mb-10 font-weight-bold">{{ $fallbackWord['translation'] }}</p>
            @endif

            @if(!empty($fallbackWord['definition']))
                <p class="font-12 text-dark mb-10">{{ $fallbackWord['definition'] }}</p>
            @endif

            @if(!empty($fallbackWord['example']))
                <div class="p-12 rounded-12 bg-white border-left-primary" style="border-left:3px solid var(--primary);">
                    <p class="font-12 text-gray-500 mb-0 font-italic">{{ $fallbackWord['example'] }}</p>
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
