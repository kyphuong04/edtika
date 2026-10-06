/**
 * result-shell.js — phần dùng chung của trang kết quả và trang chữa bài:
 * cửa sổ xác nhận "Làm lại" (partials/retake_modal.blade.php).
 */
(function () {
    function init() {
        var modal = document.getElementById('rsRetakeModal');
        if (!modal) return;

        var opener = document.querySelector('[data-retake-open]');
        var submit = modal.querySelector('[data-retake-submit]');
        var form = modal.querySelector('form');

        function openModal() {
            modal.hidden = false;
            document.body.classList.add('rs-modal-open');
            if (submit) submit.focus();
        }

        function closeModal() {
            modal.hidden = true;
            document.body.classList.remove('rs-modal-open');
            if (opener) opener.focus();
        }

        if (opener) opener.addEventListener('click', openModal);

        modal.querySelectorAll('[data-retake-close]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });

        // Chặn bấm 2 lần -> tạo 2 attempt.
        if (form && submit) {
            form.addEventListener('submit', function () {
                submit.disabled = true;
                submit.textContent = 'Đang chuẩn bị bài thi...';
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
