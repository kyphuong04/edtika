{{-- Icon thẻ folder bộ từ vựng. Tham số: $icon (key trong VocabularyFolder::ICONS), $size (px) --}}
@php
    $iconKey = \App\Models\VocabularyFolder::iconKey($icon ?? null);
    $iconSize = (int) ($size ?? 40);
@endphp
<svg width="{{ $iconSize }}" height="{{ $iconSize }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($iconKey)
        @case('folder')
            <path d="M3.5 7.5a2 2 0 0 1 2-2h4l2 2h7a2 2 0 0 1 2 2v7.5a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9.5z"/><path d="M3.5 10h17"/>
            @break
        @case('target')
            <circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.5"/>
            @break
        @case('chart')
            <path d="M4 20h16"/><path d="M7 16V10"/><path d="M12 16V6"/><path d="M17 16v-4"/>
            @break
        @case('globe')
            <circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17"/><path d="M12 3.5c2.4 2.3 3.6 5.1 3.6 8.5s-1.2 6.2-3.6 8.5c-2.4-2.3-3.6-5.1-3.6-8.5s1.2-6.2 3.6-8.5z"/>
            @break
        @case('chat')
            <path d="M4.5 6.5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H10l-4 3.5V15.5h.5a2 2 0 0 1-2-2v-7z"/><path d="M8.5 9h7"/><path d="M8.5 12h4.5"/>
            @break
        @case('bulb')
            <path d="M9 17.5h6"/><path d="M10 20.5h4"/><path d="M12 3.5a6 6 0 0 0-3.6 10.8c.6.5 1.1 1.3 1.1 2.2v1h5v-1c0-.9.5-1.7 1.1-2.2A6 6 0 0 0 12 3.5z"/>
            @break
        @case('star')
            <path d="M12 3.8l2.5 5.2 5.7.8-4.1 4 1 5.6-5.1-2.7-5.1 2.7 1-5.6-4.1-4 5.7-.8L12 3.8z"/>
            @break
        @default
            <path d="M12 6.5c-1.8-1.3-4.4-2-7.5-2v13c3.1 0 5.7.7 7.5 2 1.8-1.3 4.4-2 7.5-2v-13c-3.1 0-5.7.7-7.5 2z"/><path d="M12 6.5v13"/>
    @endswitch
</svg>
