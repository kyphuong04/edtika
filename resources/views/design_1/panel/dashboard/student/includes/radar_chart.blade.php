@php
    $radarDataValues  = $ieltsData['radarData']['data']   ?? [0, 0, 0, 0, 0];
    $radarDataLabels  = $ieltsData['radarData']['labels'] ?? ['Listening', 'Reading', 'Writing', 'Speaking', 'Overall'];
@endphp
@push('scripts_bottom')
<script>
(function() {
    "use strict";
    var radarData   = @json($radarDataValues);
    var radarLabels = @json($radarDataLabels);
    var retriesLeft = 20;

    function renderRadarWhenReady() {
        var radarEl = document.querySelector('#ieltsRadarChart');

        if (!radarEl) {
            return;
        }

        if (radarEl.dataset.rendered === '1') {
            return;
        }

        if (typeof ApexCharts === 'undefined') {
            if (retriesLeft > 0) {
                retriesLeft -= 1;
                setTimeout(renderRadarWhenReady, 120);
            }
            return;
        }

        var normalizedData = Array.isArray(radarData) ? radarData.slice(0, 5) : [0, 0, 0, 0, 0];
        while (normalizedData.length < 5) {
            normalizedData.push(0);
        }

        var normalizedLabels = Array.isArray(radarLabels) ? radarLabels.slice(0, 5) : ['Listening', 'Reading', 'Writing', 'Speaking', 'Overall'];
        while (normalizedLabels.length < 5) {
            normalizedLabels.push(['Listening', 'Reading', 'Writing', 'Speaking', 'Overall'][normalizedLabels.length]);
        }

        radarEl.dataset.rendered = '1';

        new ApexCharts(radarEl, {
            chart: {
                type: 'radar',
                height: 250,
                toolbar: { show: false },
                fontFamily: 'Roboto, sans-serif',
                background: 'transparent',
            },
            series: [{ name: 'Band', data: normalizedData }],
            xaxis: { categories: normalizedLabels },
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
    }

    renderRadarWhenReady();
})();
</script>
@endpush

<div class="bg-white rounded-24 p-16 mt-16">
        <h5 class="font-13 font-weight-bold text-dark mb-4">Skills Radar</h5>
    <div id="ieltsRadarChart" style="min-height:250px;"></div>
</div>
