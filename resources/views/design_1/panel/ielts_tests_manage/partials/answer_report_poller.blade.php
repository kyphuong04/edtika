{{--
    Thông báo đề lỗi — tự cập nhật không cần tải lại trang (chỉ giáo viên).
    Cứ 20 giây hỏi server 1 lần (tạm dừng khi tab bị ẩn, hỏi ngay khi quay lại):
      - Sidebar: đổi số "Thông báo đề lỗi (N)".
      - Có báo cáo mới: hiện toast; nếu đang ở trang danh sách thì tải lại
        phần thống kê + bảng (giữ nguyên bộ lọc đang chọn), tô sáng dòng mới.
--}}
<script>
    (function () {
        "use strict";

        var SUMMARY_URL = @json(route('panel.ielts_answer_reports.summary'));
        var PAGE_PATH = '/panel/noticeboard/answer-reports';
        var LABEL = 'Thông báo đề lỗi';
        var INTERVAL = 20000;

        var live = document.getElementById('arLive');
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
            window.showToast('success', LABEL, 'Có báo cáo đề lỗi mới từ học viên.');
        }

        /** Đang ở trang danh sách: tải lại thống kê + bảng theo đúng URL (giữ bộ lọc, trang). */
        function refreshList(previousLatest) {
            return fetch(window.location.href, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (res) { return res.ok ? res.text() : null; })
                .then(function (html) {
                    if (!html) return;
                    var doc = new DOMParser().parseFromString(html, 'text/html');

                    ['arStats', 'arTable'].forEach(function (id) {
                        var fresh = doc.getElementById(id);
                        var current = document.getElementById(id);
                        if (fresh && current) current.innerHTML = fresh.innerHTML;
                    });

                    document.querySelectorAll('#arTable tr[data-report-id]').forEach(function (tr) {
                        if ((parseInt(tr.dataset.reportId, 10) || 0) > previousLatest) tr.classList.add('ar-row-new');
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
