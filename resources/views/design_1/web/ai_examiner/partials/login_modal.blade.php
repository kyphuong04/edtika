{{-- B4: Popup yêu cầu đăng nhập cho user anonymous trước khi xem kết quả chấm chữa --}}
<div class="aie-modal" id="aieLoginModal" aria-hidden="true">
    <div class="aie-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="aieLoginModalTitle">
        <button type="button" class="aie-modal__close" data-aie-close aria-label="Đóng">&times;</button>
        <div class="aie-modal__mascot"><img src="/assets/design_1/img/ai-examiner/mascot-bust.png" alt=""></div>
        <h3 id="aieLoginModalTitle">Đăng nhập để tiếp tục chấm chữa</h3>
        <p>Bạn cần đăng nhập để nhận kết quả chấm chữa chi tiết từ AI và lưu bài làm của bạn.</p>
        <div class="aie-modal__actions">
            <button type="button" class="aie-btn aie-btn--soft" data-aie-close>Để sau</button>
            <button type="button" class="aie-btn aie-btn--primary" id="aieLoginNow">Đăng nhập ngay <span aria-hidden="true">→</span></button>
        </div>
    </div>
</div>

{{-- Nút ẩn để mở modal Đăng nhập/Đăng ký có sẵn của layout --}}
<button type="button" id="aieOpenAuthModal" data-open-auth-modal="true" hidden></button>
