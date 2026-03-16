{{-- Grading Activity Chart (Stacked Bar – Writing & Speaking graded per day) --}}
<div class="bg-white p-16 rounded-24 mt-24">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="font-14 font-weight-bold text-dark">Bài chấm trong tuần</h4>
        <div class="d-flex align-items-center gap-16">
            <div class="d-flex align-items-center gap-6">
                <span class="d-inline-block rounded-circle" style="width:10px;height:10px;background:#6366f1;"></span>
                <span class="font-12 text-gray-500">Writing</span>
            </div>
            <div class="d-flex align-items-center gap-6">
                <span class="d-inline-block rounded-circle" style="width:10px;height:10px;background:#22c55e;"></span>
                <span class="font-12 text-gray-500">Speaking</span>
            </div>
        </div>
    </div>

    <div id="teacherGradingChart" class="mt-16" style="min-height:220px;"></div>
</div>

@push('scripts_bottom2')
<script>
(function () {
    "use strict";

    var gradingLabels       = @json($teacherGradingChart['labels']);
    var gradingWritingData  = @json($teacherGradingChart['writingData']);
    var gradingSpeakingData = @json($teacherGradingChart['speakingData']);

    var options = {
        series: [
            { name: 'Writing',  data: gradingWritingData  },
            { name: 'Speaking', data: gradingSpeakingData },
        ],
        chart: {
            type: 'bar',
            height: 220,
            stacked: true,
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        colors: ['#6366f1', '#22c55e'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '48%',
                borderRadius: 6,
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'last',
            },
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: gradingLabels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { fontSize: '12px', colors: '#111', fontWeight: '600' } },
        },
        yaxis: {
            labels: {
                style: { fontSize: '12px', colors: '#111', fontWeight: '600' },
                formatter: function (val) { return Math.round(val); },
            },
            tickAmount: 4,
            min: 0,
        },
        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        legend: { show: false },
        tooltip: {
            shared: true,
            intersect: false,
            y: { formatter: function (val) { return val + ' bài'; } },
        },
    };

    var chart = new ApexCharts(document.querySelector('#teacherGradingChart'), options);
    chart.render();
})(jQuery);
</script>
@endpush
