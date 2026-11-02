{{--
    Cửa sổ "Chia sẻ bài làm" — trang kết quả + trang chữa bài của chính học viên.
    Mở bằng nút data-share-open (result_topbar). Mở lần đầu -> POST storeUrl tạo link
    công khai /ielts-share/{token} và chọn ảnh 1:1 theo mức điểm.
    Xử lý trong public/assets/js/ielts-tests/result-shell.js (ShareModal).

    $shareMeta = IeltsAttemptShareController::modalMeta($attempt)
--}}
<div class="rs-modal rs-share" id="rsShareModal" role="dialog" aria-modal="true" aria-labelledby="rsShareTitle" hidden
     data-store-url="{{ $shareMeta['storeUrl'] }}"
     data-shuffle-url="{{ $shareMeta['shuffleUrl'] }}"
     data-disable-url="{{ $shareMeta['disableUrl'] }}">
    <div class="rs-modal-backdrop" data-share-close></div>

    <div class="rs-share-card">
        <button type="button" class="rs-share-x" data-share-close aria-label="Đóng">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>

        <header class="rs-share-head">
            <h3 id="rsShareTitle">Chia sẻ bài làm</h3>
            <p>Bạn bè mở link sẽ xem được kết quả và bài chữa Listening / Reading của bạn, không cần đăng nhập.</p>
        </header>

        {{-- Đang tạo link --}}
        <div class="rs-share-state" data-share-state="loading">
            <span class="rs-share-spinner" aria-hidden="true"></span>
            <span>Đang tạo link chia sẻ...</span>
        </div>

        {{-- Lỗi --}}
        <div class="rs-share-state is-error" data-share-state="error" hidden>
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <span data-share-error>Không tạo được link chia sẻ. Vui lòng thử lại.</span>
            <button type="button" class="rs-btn rs-btn--outline" data-share-retry>Thử lại</button>
        </div>

        {{-- Link đã tắt --}}
        <div class="rs-share-state is-off" data-share-state="off" hidden>
            <i class="fas fa-link-slash" aria-hidden="true"></i>
            <span>Đã tắt link chia sẻ — người có link sẽ không xem được bài làm nữa.</span>
            <button type="button" class="rs-btn rs-btn--primary" data-share-retry>Bật lại link</button>
        </div>

        <div class="rs-share-body" data-share-state="ready" hidden>
            {{-- Ảnh 1:1 theo mức điểm --}}
            <div class="rs-share-visual">
                <figure class="rs-share-image">
                    <img src="" alt="" data-share-img width="1080" height="1080">
                </figure>
                <div class="rs-share-visual-actions">
                    <button type="button" class="rs-share-chip" data-share-shuffle hidden>
                        <i class="fas fa-shuffle" aria-hidden="true"></i> Đổi ảnh khác
                    </button>
                    <a href="#" class="rs-share-chip" data-share-download download>
                        <i class="fas fa-download" aria-hidden="true"></i> Tải ảnh
                    </a>
                </div>
            </div>

            <div class="rs-share-side">
                {{-- Link Card: gần giống khung xem trước trên Facebook / LinkedIn --}}
                <div class="rs-share-linkcard" aria-label="Xem trước khi đăng">
                    <img src="" alt="" data-share-card-img>
                    <div>
                        <span class="rs-share-domain" data-share-domain></span>
                        <strong data-share-card-title></strong>
                        <span data-share-card-desc></span>
                    </div>
                </div>

                <label class="rs-share-label" for="rsShareCaption">Lời dẫn</label>
                <div class="rs-share-field">
                    <textarea id="rsShareCaption" rows="2" maxlength="300" data-share-caption></textarea>
                    <button type="button" class="rs-share-copy" data-share-copy="caption">Sao chép</button>
                </div>

                <label class="rs-share-label" for="rsShareLink">Link bài làm</label>
                <div class="rs-share-field">
                    <input id="rsShareLink" type="text" readonly data-share-link>
                    <button type="button" class="rs-share-copy" data-share-copy="link">Sao chép</button>
                </div>

                <div class="rs-share-label">Chia sẻ lên</div>
                <div class="rs-share-networks">
                    <button type="button" class="rs-share-net is-facebook" data-share-net="facebook">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i><span>Facebook</span>
                    </button>
                    <button type="button" class="rs-share-net is-threads" data-share-net="threads">
                        <i class="fas fa-at" aria-hidden="true"></i><span>Threads</span>
                    </button>
                    <button type="button" class="rs-share-net is-linkedin" data-share-net="linkedin">
                        <i class="fab fa-linkedin-in" aria-hidden="true"></i><span>LinkedIn</span>
                    </button>
                    <button type="button" class="rs-share-net is-native" data-share-net="native" hidden>
                        <i class="fas fa-ellipsis" aria-hidden="true"></i><span>Khác</span>
                    </button>
                </div>

                <p class="rs-share-msg" data-share-msg role="status" aria-live="polite"></p>
            </div>

            <footer class="rs-share-foot">
                <span class="rs-share-views"><i class="far fa-eye" aria-hidden="true"></i> <span data-share-views>0</span> lượt xem</span>
                <button type="button" class="rs-share-off" data-share-disable>
                    <i class="fas fa-link-slash" aria-hidden="true"></i> Tắt link chia sẻ
                </button>
            </footer>
        </div>
    </div>
</div>
