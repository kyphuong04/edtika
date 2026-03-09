@php
    $weakAll       = $ieltsData['weakPoints'] ?? [];
    $weakPointsList = array_slice($weakAll, 0, 3);  // top 3 weakest only
    $skillLabels = [
        'listening'  => 'Listening',  'reading' => 'Reading',
        'writing'    => 'Writing',    'speaking' => 'Speaking',
        'vocabulary' => 'Vocabulary', 'grammar'  => 'Grammar',
    ];
    $skillUrls = [
        'listening'  => '/panel/courses/purchases?skill=listening',
        'reading'    => '/panel/courses/purchases?skill=reading',
        'writing'    => '/panel/courses/purchases?skill=writing',
        'speaking'   => '/panel/courses/purchases?skill=speaking',
        'vocabulary' => '/panel/dictionary',
        'grammar'    => '/panel/courses/purchases?skill=grammar',
    ];
    // Dark → medium → light matching screenshot gradient style
    $bgColors = ['#3D4454', '#6B7280', '#B0B7C3'];
@endphp

<div class="bg-white rounded-24 p-16 h-100 d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h4 class="font-13 font-weight-bold text-dark mb-0" style="letter-spacing:.5px;">WEAK POINT</h4>
        <a href="/panel/dashboard/weak-points" class="font-11 text-primary">See all</a>
    </div>

    <div class="d-flex flex-column flex-grow-1 justify-content-center" style="gap:10px;">
        @for($i = 0; $i < 3; $i++)
            @php
                $skill = $weakPointsList[$i] ?? null;
                $label = $skill ? ($skillLabels[$skill] ?? ucfirst($skill)) : '';
                $url   = $skill ? ($skillUrls[$skill] ?? '#') : '#';
                $bg    = $bgColors[$i];
            @endphp
            <a href="{{ $url }}" class="text-decoration-none d-block">
                <div class="d-flex align-items-center px-14 rounded-pill"
                     style="height:46px;background:{{ $bg }};">
                    <span style="font-size:13px;font-weight:600;color:{{ $i < 2 ? '#fff' : '#374151' }};">{{ $label }}</span>
                </div>
            </a>
        @endfor
    </div>
</div>
