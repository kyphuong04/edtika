@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }

    $isEnabled = !isset($itemValue['enabled']) || !empty($itemValue['enabled']);
@endphp

<div class="tab-pane mt-3 fade" id="floating_support_cta" role="tabpanel" aria-labelledby="floating_support_cta-tab">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-7">
            <form action="{{ getAdminPanelUrl() }}/settings/floating_support_cta" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="general">

                <div class="form-group custom-switches-stacked mb-4">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[enabled]" value="0">
                        <input type="checkbox" name="value[enabled]" id="floatingSupportCtaEnabledSwitch" value="1" {{ $isEnabled ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="floatingSupportCtaEnabledSwitch">Bật cụm CTA hỗ trợ nổi</label>
                    </label>
                    <p class="font-12 text-gray-500 mb-0">Hiển thị nút "Hỗ trợ" ở góc phải bên dưới trên các trang frontend.</p>
                </div>

                <div class="form-group">
                    <label>Nhãn nút chính</label>
                    <input type="text" name="value[support_label]" class="form-control" value="{{ $itemValue['support_label'] ?? 'Hỗ trợ' }}" maxlength="30">
                </div>

                <div class="form-group">
                    <label>Số điện thoại liên hệ</label>
                    <input type="text" name="value[contact_phone]" class="form-control" value="{{ $itemValue['contact_phone'] ?? '' }}" placeholder="Ví dụ: 0901234567 hoặc +84901234567">
                    <p class="font-12 text-gray-500 mt-1 mb-0">Nút Liên hệ sẽ hiển thị số này và bấm để gọi trực tiếp bằng tel:.</p>
                </div>

                <div class="form-group">
                    <label>Nhãn nút Zalo</label>
                    <input type="text" name="value[zalo_label]" class="form-control" value="{{ $itemValue['zalo_label'] ?? 'Liên hệ qua Zalo' }}" maxlength="50">
                </div>

                <div class="form-group">
                    <label>Liên kết Zalo</label>
                    <input type="text" name="value[zalo_url]" class="form-control" value="{{ $itemValue['zalo_url'] ?? 'https://zalo.me/' }}" placeholder="Ví dụ: https://zalo.me/your-id">
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>
