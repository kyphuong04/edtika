{{-- GĐ5: nút "+ Giỏ hàng" / "+ Thêm vào giỏ" dùng route /cart/store có sẵn, cập nhật số trên nút giỏ nổi --}}
<script>
    (function () {
        var token = @json(csrf_token());

        document.addEventListener('click', function (event) {
            var button = event.target.closest('.js-edk-add-cart');
            if (!button) {
                return;
            }

            event.preventDefault();
            button.disabled = true;

            fetch('/cart/store', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({item_id: button.getAttribute('data-bundle-id'), item_name: 'bundle_id'})
            }).then(function (response) {
                return response.json().then(function (data) {
                    return {ok: response.ok, data: data};
                });
            }).then(function (result) {
                var data = result.data || {};

                if (!result.ok && typeof window.showToast === 'function') {
                    window.showToast('error', data.title || '', data.msg || '');
                }

                if (result.ok) {
                    // Panel giỏ hàng (cart_panel) mở ra và tự cập nhật số lượng từ server
                    document.dispatchEvent(new CustomEvent('edk:cart-added', {detail: {bundleId: button.getAttribute('data-bundle-id')}}));
                }
            }).catch(function () {
                // Server trả về trang (ví dụ khóa đã có / đã sở hữu) thay vì JSON: tải lại để hiện thông báo
                window.location.reload();
            }).finally(function () {
                button.disabled = false;
            });
        });
    })();
</script>
