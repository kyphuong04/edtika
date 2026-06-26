@php
    $skillChartSeries = $ieltsData['activityData']['series'] ?? [];
    $skillChartLabels = $ieltsData['activityData']['labels'] ?? [];
    $skillChartHasData = collect($skillChartSeries)->contains(function ($series) {
        return !empty(array_filter($series['data'] ?? [], function ($value) {
            return (float) $value > 0;
        }));
    });
@endphp

@push('scripts_bottom')
<script>
    (function () {
        "use strict";
        var skillChartSeries  = @json($skillChartSeries);
        var skillChartLabels  = @json($skillChartLabels);
        var skillChartHasData = @json($skillChartHasData);
        if (Array.isArray(skillChartSeries)) {
            skillChartHasData = skillChartHasData || skillChartSeries.some(function (series) {
            return Array.isArray(series.data) && series.data.some(function (value) { return Number(value) > 0; });
            });
        }

        if (typeof ApexCharts !== 'undefined' && document.querySelector('#ieltsSkillActivityChart') && skillChartHasData) {
            var options = {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 220,
                    toolbar: { show: false },
                    fontFamily: 'Roboto, sans-serif',
                    background: 'transparent',
                },
                series: skillChartSeries,
                xaxis: {
                    categories: skillChartLabels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9CA3AF', fontSize: '12px' } },
                },
                yaxis: { show: false },
                grid: { show: false },
                legend: { show: false },
                colors: ['#6366F1', '#10B981', '#F59E0B', '#EF4444'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '40%',
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all',
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val > 0 ? val : ''; },
                    style: { fontSize: '10px', colors: ['#374151'] },
                },
                tooltip: {
                    y: { formatter: function(val) { return val + ' activity'; } }
                },
            };
            new ApexCharts(document.querySelector('#ieltsSkillActivityChart'), options).render();
        }
    })();
</script>
@endpush

<div class="bg-white rounded-24 p-16 w-100 mt-0">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h4 class="font-14 font-weight-bold text-dark mb-0">Skill Activity <span class="font-12 text-gray-500 font-weight-normal">(last 7 days)</span></h4>
        {{-- Legend --}}
        <div class="d-flex align-items-center gap-12">
            @php
                $legendItems = [
                    ['Listening', '#6366F1'],
                    ['Reading',   '#10B981'],
                    ['Writing',   '#F59E0B'],
                    ['Speaking',  '#EF4444'],
                ];
            @endphp
            @foreach($legendItems as [$lbl, $color])
                <div class="d-flex align-items-center gap-4">
                    <span class="size-8 rounded-circle d-inline-block" style="background:{{ $color }};min-width:8px;"></span>
                    <span class="font-10 text-gray-500">{{ $lbl }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @if($skillChartHasData)
        <div id="ieltsSkillActivityChart"></div>
    @else
        <div class="d-flex-center flex-column text-center rounded-16 bg-gray-100" style="min-height:220px;">
            <p class="font-12 text-gray-500 mb-0">Chưa có dữ liệu hoạt động trong 7 ngày gần nhất.</p>
        </div>
    @endif
</div>
