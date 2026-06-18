@php
    $osd = $orgSalesDashboard ?? [];
@endphp

<div class="row gx-16">

    {{-- ═══════════════════════════════════════════════════
         LEFT COLUMN  (2/3)
    ═══════════════════════════════════════════════════════ --}}
    <div class="col-12 col-xl-8">

        {{-- ── Row 1: 4 Stat cards ──────────────────────────── --}}
        <div class="row g-12 mb-16 organ-stats-row">
            {{-- Marketing Lead / Form tư vấn --}}
            <div class="col-6 col-md-3 organ-stat-col">
                <div class="bg-white rounded-16 p-16 h-100 shadow-sm">
                    <div class="d-flex-center size-36 bg-warning-30 rounded-10 mb-10">
                        <x-iconsax-bul-user-add class="icons text-warning" width="18px" height="18px"/>
                    </div>
                    <span class="d-block font-20 font-weight-bold text-dark">{{ $osd['marketingLeads'] ?? 0 }}</span>
                    <span class="d-block font-11 text-gray-500 mt-4 lh-15">Marketing Lead<br>/ Form tư vấn</span>
                </div>
            </div>

            {{-- Đã tư vấn --}}
            <div class="col-6 col-md-3 organ-stat-col">
                <div class="bg-white rounded-16 p-16 h-100 shadow-sm">
                    <div class="d-flex-center size-36 bg-primary-40 rounded-10 mb-10">
                        <x-iconsax-bul-message-text class="icons text-primary" width="18px" height="18px"/>
                    </div>
                    <span class="d-block font-20 font-weight-bold text-dark">{{ $osd['leadsConsulted'] ?? 0 }}</span>
                    <span class="d-block font-11 text-gray-500 mt-4">Đã tư vấn</span>
                </div>
            </div>

            {{-- Đã trả phí --}}
            <div class="col-6 col-md-3 organ-stat-col">
                <div class="bg-white rounded-16 p-16 h-100 shadow-sm">
                    <div class="d-flex-center size-36 bg-success-30 rounded-10 mb-10">
                        <x-iconsax-bul-wallet-money class="icons text-success" width="18px" height="18px"/>
                    </div>
                    <span class="d-block font-20 font-weight-bold text-dark">{{ $osd['leadsPaid'] ?? 0 }}</span>
                    <span class="d-block font-11 text-gray-500 mt-4">Đã trả phí</span>
                </div>
            </div>

            {{-- Đã từ chối (clickable → modal) --}}
            <div class="col-6 col-md-3 organ-stat-col">
                <div class="bg-white rounded-16 p-16 h-100 shadow-sm" style="cursor:pointer"
                     data-toggle="modal" data-target="#rejectedLeadsModal">
                    <div class="d-flex-center size-36 bg-danger-30 rounded-10 mb-10">
                        <x-iconsax-bul-close-circle class="icons text-danger" width="18px" height="18px"/>
                    </div>
                    <span class="d-block font-20 font-weight-bold text-dark">{{ $osd['leadsRejected'] ?? 0 }}</span>
                    <span class="d-block font-11 text-gray-500 mt-4">Đã từ chối</span>
                </div>
            </div>
        </div>

        {{-- ── Row 2: Weekly double bar chart ───────────────── --}}
        <div class="bg-white rounded-16 p-16 mb-16 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-16">
                <h5 class="font-14 font-weight-bold text-dark mb-0">Performance – Chốt đơn trong 7 ngày gần nhất</h5>
                <div class="d-flex align-items-center gap-12 font-11 text-gray-500">
                    <span class="d-flex align-items-center gap-6">
                        <span class="d-inline-block rounded-4 flex-shrink-0" style="width:10px;height:10px;background:#ffffff;border:1px solid #9ca3af;"></span> Cá nhân
                    </span>
                    <span class="d-flex align-items-center gap-6">
                        <span class="d-inline-block rounded-4 flex-shrink-0" style="width:10px;height:10px;background:#111111;"></span> Team
                    </span>
                </div>
            </div>
            <div id="orgWeeklyChart"></div>
        </div>

        {{-- ── Row 3: Active / Non-Active / Mentor KPI ─────── --}}
        <div class="row g-12 mb-16 organ-kpi-row">
            <div class="col-12 col-md-4 organ-kpi-col">
                <div class="bg-white rounded-16 p-16 shadow-sm">
                    <span class="d-block font-22 font-weight-bold text-dark">{{ $osd['activeStudents'] ?? 0 }}</span>
                    <span class="d-block font-12 text-gray-500 mt-6">Active student</span>
                </div>
            </div>
            <div class="col-12 col-md-4 organ-kpi-col">
                <div class="bg-white rounded-16 p-16 shadow-sm">
                    <span class="d-block font-22 font-weight-bold text-dark">{{ $osd['nonActiveStudents'] ?? 0 }}</span>
                    <span class="d-block font-12 text-gray-500 mt-6">Non – Active student</span>
                </div>
            </div>
            <div class="col-12 col-md-4 organ-kpi-col">
                <div class="bg-white rounded-16 p-16 shadow-sm">
                    <span class="d-block font-22 font-weight-bold text-dark">{{ $osd['mentorsUnderKpi'] ?? 0 }}</span>
                    <span class="d-block font-12 text-gray-500 mt-6">Mentor under KPI Daily</span>
                </div>
            </div>
        </div>

        {{-- ── Row 4: Two lists side by side ────────────────── --}}
        <div class="row g-12 organ-lists-row">

            {{-- Left list: Form đăng ký --}}
            <div class="col-12 col-md-6 organ-lists-col">
                <div class="bg-white rounded-16 p-16 shadow-sm">
                    <h5 class="font-13 font-weight-bold text-dark mb-14">List học viên để lại<br>form đăng ký</h5>
                    <div class="organ-reg-list">
                        @php $regs = $osd['formRegistrations'] ?? []; @endphp
                        @forelse($regs as $i => $submission)
                            <div class="organ-list-item rounded-12 bg-gray-100 p-12 mb-8 d-flex align-items-center justify-content-between {{ $i >= 3 ? 'organ-item-hidden' : '' }}"
                                 data-reg-index="{{ $i }}">
                                <div class="d-flex align-items-center gap-10 overflow-hidden">
                                    @if(!empty($submission->user))
                                        <div class="size-32 rounded-circle bg-white flex-shrink-0">
                                            <img src="{{ $submission->user->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="font-12 font-weight-bold text-dark text-ellipsis">{{ $submission->user->full_name }}</div>
                                            <div class="font-11 text-gray-500 text-ellipsis">{{ $submission->user->email }}</div>
                                        </div>
                                    @else
                                        <div class="size-32 rounded-circle bg-white flex-shrink-0 d-flex-center">
                                            <x-iconsax-bul-user class="icons text-gray-400" width="16px" height="16px"/>
                                        </div>
                                        <div class="font-12 text-gray-500">Ẩn danh</div>
                                    @endif
                                </div>
                                <button type="button" class="organ-check-btn d-flex-center size-24 rounded-circle bg-white border-0 flex-shrink-0 ml-8"
                                        onclick="orgListCheck(this, 'reg')" title="Đã xử lý">
                                    <x-iconsax-lin-tick-circle class="icons text-gray-400" width="16px" height="16px"/>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-20 text-gray-400 font-12">Chưa có form đăng ký.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right list: Sắp hoàn thành --}}
            <div class="col-12 col-md-6 organ-lists-col">
                <div class="bg-white rounded-16 p-16 shadow-sm">
                    <h5 class="font-13 font-weight-bold text-dark mb-14">List học viên sắp hoàn<br>thành khóa học</h5>
                    <div class="organ-complete-list">
                        @php $completings = $osd['completingStudents'] ?? []; @endphp
                        @forelse($completings as $i => $item)
                            <div class="organ-list-item rounded-12 bg-gray-100 p-12 mb-8 d-flex align-items-center justify-content-between {{ $i >= 3 ? 'organ-item-hidden' : '' }}"
                                 data-cmp-index="{{ $i }}">
                                <div class="d-flex align-items-center gap-10 overflow-hidden">
                                    <div class="size-32 rounded-circle bg-white flex-shrink-0">
                                        <img src="{{ $item['user']->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="font-12 font-weight-bold text-dark text-ellipsis">{{ $item['user']->full_name }}</div>
                                        <div class="font-11 text-gray-500 text-ellipsis">{{ truncate($item['webinar']->title ?? '', 28) }}</div>
                                        <div class="d-flex align-items-center gap-6 mt-4">
                                            <div class="progress-card d-flex bg-white flex-grow-1" style="height:4px;width:70px;">
                                                <div class="progress-bar bg-primary" style="width:{{ $item['progress'] }}%;"></div>
                                            </div>
                                            <span class="font-10 text-gray-500">{{ $item['progress'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="organ-check-btn d-flex-center size-24 rounded-circle bg-white border-0 flex-shrink-0 ml-8"
                                        onclick="orgListCheck(this, 'cmp')" title="Đã xử lý">
                                    <x-iconsax-lin-tick-circle class="icons text-gray-400" width="16px" height="16px"/>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-20 text-gray-400 font-12">Chưa có học viên sắp hoàn thành.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        {{-- end row 4 --}}

    </div>
    {{-- end left col --}}


    {{-- ═══════════════════════════════════════════════════
         RIGHT COLUMN (1/3)
    ═══════════════════════════════════════════════════════ --}}
    <div class="col-12 col-xl-4 mt-16 mt-xl-0">

        {{-- Admin profile card --}}
        <div class="bg-white rounded-16 p-20 shadow-sm text-center mb-12">
            <div class="d-flex-center mx-auto rounded-circle bg-gray-100"
                 style="width:72px;height:72px;overflow:hidden;">
                <img src="{{ $authUser->getAvatar(72) }}" alt=""
                     class="img-cover rounded-circle" style="width:72px;height:72px;">
            </div>
            <h5 class="font-15 font-weight-bold text-dark mt-12 mb-0">{{ strtoupper($authUser->full_name) }}</h5>
            <div class="mt-10 font-12 font-weight-bold text-dark">
                THÀNH TÍCH CHỐT SALE: {{ $osd['saleAchievementPct'] ?? 0 }}%
            </div>
            <div class="bg-gray-200 rounded-pill mt-8 mx-auto overflow-hidden" style="height:8px;max-width:180px;">
                <div class="bg-dark rounded-pill h-100" style="width:{{ $osd['saleAchievementPct'] ?? 0 }}%;"></div>
            </div>
        </div>

        {{-- 4 Personal metric cards – stacked full-width --}}
        <div class="mb-12">
            <div class="bg-white rounded-16 p-14 shadow-sm mb-8 text-center">
                <span class="d-block font-18 font-weight-bold text-dark">{{ $osd['leadsPerDay'] ?? 0 }}</span>
                <span class="d-block font-11 text-gray-500 mt-4">Số lượt lead chăm sóc/<br>ngày (TB tháng)</span>
            </div>
            <div class="bg-white rounded-16 p-14 shadow-sm mb-8 text-center">
                <span class="d-block font-18 font-weight-bold text-dark">{{ $osd['personalConversionRate'] ?? 0 }}%</span>
                <span class="d-block font-11 text-gray-500 mt-4">Tỷ lệ chuyển đổi cá<br>nhân (theo tháng)</span>
            </div>
            <div class="bg-white rounded-16 p-14 shadow-sm mb-8 text-center">
                <span class="d-block font-18 font-weight-bold text-dark">{{ $osd['avgDealDays'] ?? 0 }} ngày</span>
                <span class="d-block font-11 text-gray-500 mt-4">Thời gian trung bình lead<br>→ chốt (theo tháng)</span>
            </div>
            <div class="bg-white rounded-16 p-14 shadow-sm mb-0 text-center">
                <span class="d-block font-18 font-weight-bold text-dark">{{ handlePrice($osd['contractValue'] ?? 0) }}</span>
                <span class="d-block font-11 text-gray-500 mt-4">Giá trị hợp đồng<br>(Revenue/Sale)</span>
            </div>
        </div>

        {{-- Top Seller list --}}
        <div class="bg-white rounded-16 p-16 shadow-sm">
            <h5 class="font-14 font-weight-bold text-dark text-center mb-16">TOP SELLER</h5>
            @forelse($osd['topSellers'] ?? [] as $rank => $seller)
                <div class="rounded-12 bg-gray-100 p-12 mb-8 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-10 overflow-hidden">
                        <span class="font-11 font-weight-bold text-gray-500 flex-shrink-0" style="min-width:16px;">{{ $rank + 1 }}</span>
                        <div class="size-32 rounded-circle bg-white flex-shrink-0">
                            <img src="{{ $seller['user']->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                        </div>
                        <div class="overflow-hidden">
                            <div class="font-12 font-weight-bold text-dark text-ellipsis">{{ $seller['user']->full_name }}</div>
                            <div class="font-11 text-gray-500">{{ $seller['sales'] }} deals &bull; {{ $seller['rate'] }}%</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400 font-12">Chưa có dữ liệu.</div>
            @endforelse
        </div>

        {{-- Events Calendar --}}
        <div class="mt-12">
            @include('design_1.panel.dashboard.instructor.includes.events_calendar')
        </div>

    </div>
    {{-- end right col --}}

</div>

{{-- Rejected Leads Modal --}}
<div class="modal fade" id="rejectedLeadsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-16">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-14 font-weight-bold">Danh sách Deal bị từ chối</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-12">
                <p class="font-13 text-gray-500 mb-4">Tổng số deal bị từ chối (hoàn tiền):
                    <strong class="text-dark">{{ $osd['leadsRejected'] ?? 0 }}</strong>
                </p>

                @php $rejectedLeads = $osd['rejectedLeadsList'] ?? collect(); @endphp
                @if($rejectedLeads->count() > 0)
                    <div style="max-height:320px; overflow-y:auto;">
                        @foreach($rejectedLeads as $rejectedSale)
                            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-12 p-10 mb-8">
                                <div class="overflow-hidden pr-8">
                                    <div class="font-12 font-weight-bold text-dark text-ellipsis">
                                        {{ $rejectedSale->buyer->full_name ?? 'Lead ẩn danh' }}
                                    </div>
                                    <div class="font-11 text-gray-500 text-ellipsis">
                                        {{ $rejectedSale->buyer->email ?? 'Không có email' }}
                                    </div>
                                    <div class="font-11 text-gray-500 text-ellipsis mt-4">
                                        {{ !empty($rejectedSale->webinar) ? $rejectedSale->webinar->title : 'Deal không thuộc khóa học' }}
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <div class="font-11 font-weight-bold text-danger">Từ chối</div>
                                    <div class="font-10 text-gray-400 mt-4">
                                        {{ !empty($rejectedSale->refund_at) ? dateTimeFormat($rejectedSale->refund_at, 'j M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="font-12 text-gray-400">Chưa có deal bị từ chối trong năm.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.organ-item-hidden { display: none !important; }
.lh-15 { line-height: 1.5; }

.organ-stats-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-left: 0;
    margin-right: 0;
}

.organ-stats-row > .organ-stat-col {
    width: 100%;
    max-width: 100%;
    flex: initial;
    padding-left: 0;
    padding-right: 0;
}

@media (min-width: 768px) {
    .organ-stats-row {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .organ-stats-row > .organ-stat-col {
        width: 100%;
        max-width: 100%;
    }
}

.organ-kpi-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 12px;
    margin-left: 0;
    margin-right: 0;
}

.organ-kpi-row > .organ-kpi-col {
    width: 100%;
    max-width: 100%;
    flex: initial;
    padding-left: 0;
    padding-right: 0;
}

@media (min-width: 768px) {
    .organ-kpi-row {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

.organ-lists-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 12px;
    margin-left: 0;
    margin-right: 0;
}

.organ-lists-row > .organ-lists-col {
    width: 100%;
    max-width: 100%;
    flex: initial;
    padding-left: 0;
    padding-right: 0;
}

@media (min-width: 576px) {
    .organ-lists-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>

<script>
(function () {
    "use strict";

    /**
     * Dismiss current item and reveal the next hidden one in the same list.
     * @param {HTMLElement} btn  - the clicked check button
     * @param {string}      type - 'reg' | 'cmp'
     */
    window.orgListCheck = function (btn, type) {
        var item = btn.closest('.organ-list-item');
        if (!item) return;

        var list = type === 'reg'
            ? document.querySelector('.organ-reg-list')
            : document.querySelector('.organ-complete-list');

        // Fade out current
        item.style.transition = 'opacity .25s';
        item.style.opacity    = '0';
        setTimeout(function () {
            item.remove();
            // Reveal first still-hidden item
            if (list) {
                var next = list.querySelector('.organ-item-hidden');
                if (next) {
                    next.classList.remove('organ-item-hidden');
                    next.style.opacity = '0';
                    next.style.transition = 'opacity .25s';
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () { next.style.opacity = '1'; });
                    });
                }
            }
        }, 260);
    };

    // ── Weekly double bar chart (ApexCharts) ──────────────────
    var weeklyEl = document.getElementById('orgWeeklyChart');
    if (weeklyEl && typeof ApexCharts !== 'undefined') {
        new ApexCharts(weeklyEl, {
            series: [
                { name: 'Cá nhân', data: @json($osd['weeklyPersonal'] ?? []) },
                { name: 'Team',    data: @json($osd['weeklyTeam']     ?? []) }
            ],
            chart: {
                type: 'bar',
                height: 230,
                toolbar: { show: false },
                animations: { enabled: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadius: 4,
                    grouped: true
                }
            },
            colors: ['#ffffff', '#111111'],
            stroke: { show: true, width: 1, colors: ['#9ca3af'] },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($osd['weeklyLabels'] ?? []),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: '11px', colors: '#9ca3af' } }
            },
            yaxis: {
                min: 0,
                tickAmount: 4,
                labels: {
                    formatter: function (v) { return Math.round(v); },
                    style: { fontSize: '11px', colors: '#9ca3af' }
                }
            },
            grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
            legend: { show: false },
            tooltip: { theme: 'light' }
        }).render();
    }
}());
</script>

