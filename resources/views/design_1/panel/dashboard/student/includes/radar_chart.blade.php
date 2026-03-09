@php
    $radarDataValues  = $ieltsData['radarData']['data']   ?? [0, 0, 0, 0];
    $radarDataLabels  = $ieltsData['radarData']['labels'] ?? ['Listening', 'Reading', 'Writing', 'Speaking'];
@endphp
@push('scripts_bottom')
<script>
(function() {
    "use strict";
    var radarEl = document.querySelector('#ieltsRadarChart');
    if (!radarEl || typeof ApexCharts === 'undefined') return;

    var radarData   = @json($radarDataValues);
    var radarLabels = @json($radarDataLabels);

    new ApexCharts(radarEl, {
        chart: {
            type: 'radar',
            height: 220,
            toolbar: { show: false },
            fontFamily: 'Roboto, sans-serif',
            background: 'transparent',
        },
        series: [{ name: 'Band', data: radarData }],
        xaxis: { categories: radarLabels },
        yaxis: { show: false, min: 0, max: 9 },
        fill: { opacity: 0.25, colors: ['#6366F1'] },
        stroke: { width: 2, colors: ['#6366F1'] },
        markers: { size: 4, colors: ['#6366F1'] },
        plotOptions: {
            radar: {
                polygons: {
                    strokeColors: '#E5E7EB',
                    fill: { colors: ['#F9FAFB', '#FFFFFF'] },
                }
            }
        },
        dataLabels: {
            enabled: true,
            background: { enabled: false },
            style: { fontSize: '10px', colors: ['#374151'] },
            formatter: function(val) { return val > 0 ? val : ''; },
        },
        tooltip: { y: { formatter: function(val) { return val + ' / 9'; } } },
    }).render();
})();
</script>
@endpush

<div class="bg-white rounded-24 p-16 mt-16">
    <h5 class="font-13 font-weight-bold text-dark mb-4">Skills Radar</h5>
    <div id="ieltsRadarChart"></div>
</div>
