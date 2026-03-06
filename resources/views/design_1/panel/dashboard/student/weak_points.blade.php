@extends('design_1.panel.layouts.panel')

@section('content')
@php
    $skillBands  = $ieltsData['skillBands']  ?? [];
    $weakPoints  = $ieltsData['weakPoints']  ?? [];
    $skillBands  = $ieltsData['skillBands']  ?? [];

    $skillMeta = [
        'listening'  => ['label' => 'Listening',  'icon' => 'headphones', 'color' => '#6366F1', 'url' => '/panel/courses/purchases?skill=listening'],
        'reading'    => ['label' => 'Reading',    'icon' => 'book',       'color' => '#10B981', 'url' => '/panel/courses/purchases?skill=reading'],
        'writing'    => ['label' => 'Writing',    'icon' => 'edit',       'color' => '#F59E0B', 'url' => '/panel/courses/purchases?skill=writing'],
        'speaking'   => ['label' => 'Speaking',   'icon' => 'microphone', 'color' => '#EF4444', 'url' => '/panel/courses/purchases?skill=speaking'],
        'vocabulary' => ['label' => 'Vocabulary', 'icon' => 'translate',  'color' => '#8B5CF6', 'url' => '/panel/dictionary'],
        'grammar'    => ['label' => 'Grammar',    'icon' => 'pen',        'color' => '#06B6D4', 'url' => '/panel/courses/purchases?skill=grammar'],
    ];

    // Split into active weak points (band < 6) and resolved (band >= 6)
    $unresolved = [];
    $resolved   = [];
    foreach ($weakPoints as $skill) {
        $band = $skillBands[$skill] ?? 0;
        if ($band < 6) {
            $unresolved[] = $skill;
        } else {
            $resolved[] = $skill;
        }
    }
@endphp

<div class="d-flex align-items-center gap-12 mb-24">
    <a href="/panel" class="d-flex-center size-36 rounded-12 bg-gray-100 text-dark">
        <x-iconsax-lin-arrow-left class="icons" width="18px" height="18px"/>
    </a>
    <h2 class="font-18 font-weight-bold text-dark mb-0">Weak Points</h2>
</div>

<div class="row gx-20">

    {{-- Left: Unresolved Weak Points --}}
    <div class="col-12 col-lg-6">
        <div class="bg-white rounded-24 p-20">
            <div class="d-flex align-items-center gap-8 mb-16">
                <div class="d-flex-center size-32 rounded-10 bg-danger-40">
                    <x-iconsax-bul-danger class="icons text-danger" width="16px" height="16px"/>
                </div>
                <h4 class="font-15 font-weight-bold text-dark mb-0">Unresolved Weak Points</h4>
                <span class="badge badge-danger-light ml-auto font-11">{{ count($unresolved) }}</span>
            </div>

            @forelse($unresolved as $skill)
                @php
                    $meta = $skillMeta[$skill] ?? ['label' => ucfirst($skill), 'color' => '#9CA3AF', 'url' => '#'];
                    $band = $skillBands[$skill] ?? 0;
                    $pct  = round($band / 9 * 100);
                @endphp
                <div class="rounded-16 border border-gray-200 p-16 mb-12">
                    <div class="d-flex align-items-center gap-12 mb-12">
                        <div class="d-flex-center size-40 rounded-12 flex-shrink-0"
                             style="background:{{ $meta['color'] }}20;">
                            <span class="font-weight-bold" style="color:{{ $meta['color'] }};font-size:16px;">{{ strtoupper(substr($meta['label'],0,1)) }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="font-14 font-weight-bold text-dark mb-0">{{ $meta['label'] }}</p>
                            <p class="font-11 text-gray-400 mb-0">Current band: {{ $band > 0 ? $band : '--' }} / 9</p>
                        </div>
                        <span class="badge" style="background:{{ $meta['color'] }}20;color:{{ $meta['color'] }};font-size:11px;">
                            {{ $pct }}%
                        </span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="rounded-pill bg-gray-100 mb-12" style="height:8px;">
                        <div class="rounded-pill" style="width:{{ $pct }}%;height:8px;background:{{ $meta['color'] }};transition:width .6s ease;"></div>
                    </div>

                    {{-- Recommendation --}}
                    <div class="p-10 rounded-12 bg-warning-40 d-flex align-items-start gap-8 mb-10">
                        <x-iconsax-bul-lamp-on class="icons text-warning flex-shrink-0 mt-2" width="14px" height="14px"/>
                        <span class="font-11 text-dark">
                            Your <strong>{{ $meta['label'] }}</strong> band is below 6.
                            Practice regularly to improve your score.
                        </span>
                    </div>

                    <a href="{{ $meta['url'] }}" class="btn btn-sm rounded-12 font-12 w-100"
                       style="background:{{ $meta['color'] }};color:#fff;">
                        Review {{ $meta['label'] }} lessons →
                    </a>
                </div>
            @empty
                <div class="d-flex-center flex-column text-center p-40 rounded-16 bg-gray-100">
                    <x-iconsax-bul-tick-circle class="icons text-success" width="36px" height="36px"/>
                    <p class="font-13 font-weight-bold text-dark mt-12 mb-0">No unresolved weak points!</p>
                    <p class="font-12 text-gray-500 mt-4 mb-0">Keep up the great work 🎉</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right: Resolved Weak Points --}}
    <div class="col-12 col-lg-6 mt-20 mt-lg-0">
        <div class="bg-white rounded-24 p-20">
            <div class="d-flex align-items-center gap-8 mb-16">
                <div class="d-flex-center size-32 rounded-10 bg-success-40">
                    <x-iconsax-bul-tick-circle class="icons text-success" width="16px" height="16px"/>
                </div>
                <h4 class="font-15 font-weight-bold text-dark mb-0">Resolved</h4>
                <span class="badge badge-success-light ml-auto font-11">{{ count($resolved) }}</span>
            </div>

            @forelse($resolved as $skill)
                @php
                    $meta = $skillMeta[$skill] ?? ['label' => ucfirst($skill), 'color' => '#9CA3AF', 'url' => '#'];
                    $band = $skillBands[$skill] ?? 0;
                    $pct  = round($band / 9 * 100);
                @endphp
                <div class="rounded-16 border border-success-200 p-16 mb-12">
                    <div class="d-flex align-items-center gap-12 mb-10">
                        <div class="d-flex-center size-40 rounded-12 bg-success-40 flex-shrink-0">
                            <x-iconsax-bul-tick-circle class="icons text-success" width="20px" height="20px"/>
                        </div>
                        <div class="flex-grow-1">
                            <p class="font-14 font-weight-bold text-dark mb-0">{{ $meta['label'] }}</p>
                            <p class="font-11 text-gray-400 mb-0">Band: {{ $band }} / 9 &mdash; ✅ Above 6</p>
                        </div>
                        <span class="badge badge-success-light font-11">{{ $pct }}%</span>
                    </div>
                    <div class="rounded-pill bg-gray-100" style="height:8px;">
                        <div class="rounded-pill bg-success" style="width:{{ $pct }}%;height:8px;transition:width .6s ease;"></div>
                    </div>
                </div>
            @empty
                <div class="d-flex-center flex-column text-center p-40 rounded-16 bg-gray-100">
                    <p class="font-12 text-gray-500 mb-0">No resolved skills yet.<br>Complete practice tests to track progress.</p>
                </div>
            @endforelse
        </div>

        {{-- Overall radar preview --}}
        <div class="bg-white rounded-24 p-20 mt-20">
            <h5 class="font-14 font-weight-bold text-dark mb-4">All Skills at a Glance</h5>
            <div id="weakPointsRadarChart"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts_bottom')
<script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>
<script>
(function() {
    "use strict";
    var el = document.querySelector('#weakPointsRadarChart');
    if (!el || typeof ApexCharts === 'undefined') return;

    new ApexCharts(el, {
        chart: {
            type: 'radar',
            height: 240,
            toolbar: { show: false },
            fontFamily: 'Roboto, sans-serif',
            background: 'transparent',
        },
        series: [{ name: 'Band', data: @json(array_values(array_intersect_key($ieltsData['skillBands'] ?? [], array_flip(['listening','reading','writing','speaking'])))) }],
        xaxis: { categories: ['Listening', 'Reading', 'Writing', 'Speaking'] },
        yaxis: { show: false, min: 0, max: 9 },
        fill: { opacity: 0.2, colors: ['#6366F1'] },
        stroke: { width: 2, colors: ['#6366F1'] },
        markers: { size: 4, colors: ['#6366F1'] },
        plotOptions: {
            radar: { polygons: { strokeColors: '#E5E7EB' } }
        },
        dataLabels: {
            enabled: true,
            style: { fontSize: '10px', colors: ['#374151'] },
            formatter: function(v) { return v > 0 ? v : ''; },
        },
    }).render();
})();
</script>
@endpush
