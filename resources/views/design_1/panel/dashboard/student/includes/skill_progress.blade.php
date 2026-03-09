@php
    $skillMeta = [
        'listening'  => ['label' => 'L', 'name' => 'Listening',  'color' => '#6366F1', 'url' => '/panel/courses/purchases?skill=listening'],
        'writing'    => ['label' => 'W', 'name' => 'Writing',    'color' => '#F59E0B', 'url' => '/panel/courses/purchases?skill=writing'],
        'speaking'   => ['label' => 'S', 'name' => 'Speaking',   'color' => '#EF4444', 'url' => '/panel/courses/purchases?skill=speaking'],
        'reading'    => ['label' => 'R', 'name' => 'Reading',    'color' => '#10B981', 'url' => '/panel/courses/purchases?skill=reading'],
        'vocabulary' => ['label' => 'V', 'name' => 'Vocabulary', 'color' => '#8B5CF6', 'url' => '/panel/dictionary'],
        'grammar'    => ['label' => 'G', 'name' => 'Grammar',    'color' => '#06B6D4', 'url' => '/panel/courses/purchases?skill=grammar'],
    ];
    $skillProgress = $ieltsData['skillProgress'] ?? [];
    $skillBands    = $ieltsData['skillBands']    ?? [];
@endphp

<div class="bg-white rounded-24 p-16 h-100">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 8px;">
        @foreach($skillMeta as $key => $meta)
            @php
                $pct  = $skillProgress[$key] ?? 0;
                $band = $skillBands[$key] ?? 0;
                $size = 76;
                $r    = 32;
                $circ = round(2 * M_PI * $r, 2);
                $dash = round(($pct / 100) * $circ, 2);
                $gap  = round($circ - $dash, 2);
                $offset = round($circ / 4, 2);
            @endphp
            <a href="{{ $meta['url'] }}" class="d-flex flex-column align-items-center text-decoration-none">
                {{-- Circle --}}
                <div style="position:relative;width:{{ $size }}px;height:{{ $size }}px;">
                    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}" style="position:absolute;top:0;left:0;">
                        <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $r }}" fill="white" stroke="#F3F4F6" stroke-width="5"/>
                        <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $r }}"
                                fill="none"
                                stroke="{{ $meta['color'] }}"
                                stroke-width="5"
                                stroke-dasharray="{{ $dash }} {{ $gap }}"
                                stroke-dashoffset="{{ $offset }}"
                                stroke-linecap="round"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <span style="font-size:20px;font-weight:700;color:#1F2937;line-height:1;">{{ $meta['label'] }}</span>
                        <span style="font-size:10px;color:#9CA3AF;margin-top:2px;">{{ $band > 0 ? $band : '-' }}</span>
                    </div>
                </div>
                {{-- Skill name --}}
                <span style="font-size:11px;color:#9CA3AF;margin-top:6px;text-align:center;">{{ $meta['name'] }}</span>
            </a>
        @endforeach
    </div>
</div>
