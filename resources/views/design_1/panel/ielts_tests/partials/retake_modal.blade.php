{{--
    Cửa sổ xác nhận "Làm lại" — dùng chung cho trang kết quả và trang chữa bài.
    Cần $retakeUrl. Mở bằng nút có thuộc tính data-retake-open (navbar),
    xử lý đóng/mở trong public/assets/js/ielts-tests/result-shell.js.
--}}
<div class="rs-modal" id="rsRetakeModal" role="dialog" aria-modal="true" aria-labelledby="rsRetakeTitle" hidden>
    <div class="rs-modal-backdrop" data-retake-close></div>
    <div class="rs-modal-card">
        <span class="rs-modal-icon"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i></span>
        <h3 id="rsRetakeTitle">Xác nhận làm lại bài thi</h3>
        <p>Kết quả hiện tại của bạn sẽ bị hủy và không thể khôi phục.<br>Bạn có chắc chắn muốn làm lại bài thi này không?</p>
        <form action="{{ $retakeUrl }}" method="POST" class="rs-modal-actions">
            @csrf
            <button type="button" class="rs-btn rs-btn--outline" data-retake-close>Hủy</button>
            <button type="submit" class="rs-btn rs-btn--primary" data-retake-submit>Xác nhận làm lại</button>
        </form>
    </div>
</div>
