{{--
    Thông báo đề lỗi / Thông báo từ vựng lỗi — tự cập nhật không cần tải lại trang (chỉ giáo viên).
    Dùng chung cho nhiều trang báo cáo, truyền tham số khi @include (mặc định = Thông báo đề lỗi):
      pollSummaryUrl, pollPagePath, pollLabel, pollToast, pollPrefix (tiền tố id: {prefix}Live / Stats / Table)
    Cứ 20 giây hỏi server 1 lần (tạm dừng khi tab bị ẩn, hỏi ngay khi quay lại):
      - Sidebar: đổi số "Thông báo đề lỗi (N)".
      - Có báo cáo mới: hiện toast; nếu đang ở trang danh sách thì tải lại
        phần thống kê + bảng (giữ nguyên bộ lọc đang chọn), tô sáng dòng mới.
--}}
<script>
    (function () {
        "use strict";

        var SUMMARY_URL = @json($pollSummaryUrl ?? route('panel.ielts_answer_reports.summary'));
        var PAGE_PATH = @json($pollPagePath ?? '/panel/noticeboard/answer-reports');
        var LABEL = @json($pollLabel ?? 'Thông báo đề lỗi');
        var TOAST = @json($pollToast ?? 'Có báo cáo đề lỗi mới từ học viên.');
        var PREFIX = @json($pollPrefix ?? 'ar');
        var INTERVAL = 20000;

        var live = document.getElementById(PREFIX + 'Live');
        var latestId = live ? parseInt(live.dataset.latestId, 10) || 0 : null;
        var timer = null;
        var busy = false;

        function sidebarLinks() {
            return Array.prototype.filter.call(document.querySelectorAll('a[href]'), function (a) {
                try {
                    return new URL(a.getAttribute('href'), window.location.origin).pathname === PAGE_PATH;
                } catch (e) {
                    return false;
                }
            });
        }

        function updateSidebar(count) {
            sidebarLinks().forEach(function (a) {
                var text = a.querySelector('.sidebar-text') || a;
                text.textContent = LABEL + (count > 0 ? ' (' + count + ')' : '');
            });
        }

        function notify() {
            if (typeof window.showToast !== 'function') return;
            window.showToast('success', LABEL, TOAST);
        }

        /** Đang ở trang danh sách: tải lại thống kê + bảng theo đúng URL (giữ bộ lọc, trang). */
        function refreshList(previousLatest) {
            return fetch(window.location.href, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (res) { return res.ok ? res.text() : null; })
                .then(function (html) {
                    if (!html) return;
                    var doc = new DOMParser().parseFromString(html, 'text/html');

                    [PREFIX + 'Stats', PREFIX + 'Table'].forEach(function (id) {
                        var fresh = doc.getElementById(id);
                        var current = document.getElementById(id);
                        if (fresh && current) current.innerHTML = fresh.innerHTML;
                    });

                    document.querySelectorAll('#' + PREFIX + 'Table tr[data-report-id]').forEach(function (tr) {
                        if ((parseInt(tr.dataset.reportId, 10) || 0) > previousLatest) tr.classList.add(PREFIX + '-row-new');
                    });
                });
        }

        function tick() {
            if (busy || document.hidden) return schedule();
            busy = true;

            fetch(SUMMARY_URL, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (res) { return res.ok ? res.json() : null; })
                .then(function (data) {
                    if (!data) return;

                    updateSidebar(parseInt(data.new_count, 10) || 0);

                    var newest = parseInt(data.latest_id, 10) || 0;

                    // Lần đầu ở trang khác: chỉ ghi mốc, không báo.
                    if (latestId === null) {
                        latestId = newest;
                        return;
                    }

                    if (newest > latestId) {
                        var previous = latestId;
                        latestId = newest;
                        notify(); // số chính xác hiện trên sidebar
                        if (live) return refreshList(previous);
                    }
                })
                .catch(function () { /* mất mạng tạm thời -> thử lại lần sau */ })
                .then(function () {
                    busy = false;
                    schedule();
                });
        }

        function schedule() {
            window.clearTimeout(timer);
            timer = window.setTimeout(tick, INTERVAL);
        }

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                window.clearTimeout(timer);
                tick();
            }
        });

        // Trang danh sách đã có số mới nhất từ server -> chờ 1 chu kỳ; trang khác hỏi ngay để lấy mốc.
        if (live) schedule();
        else tick();
    })();
</script>
