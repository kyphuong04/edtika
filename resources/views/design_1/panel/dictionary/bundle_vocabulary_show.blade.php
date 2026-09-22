@extends('design_1.panel.layouts.panel')

@php
    $bvwFields = ['id', 'sort_order', 'word', 'part_of_speech', 'pronunciation', 'definition', 'translation_vi', 'collocation', 'example', 'audio_url', 'image_url'];
@endphp

@push('styles_top')
<style>
    .bundle-vocab-scroll {
        max-height: min(62vh, 620px);
        overflow-y: auto;
        overflow-x: auto;
    }

    .bundle-vocab-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb {
        background: rgba(81, 29, 153, 0.35);
        border-radius: 10px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(81, 29, 153, 0.5);
    }

    .bundle-vocab-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #fff;
    }

    .bundle-vocab-pagination .pagination {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .bundle-vocab-pagination .page-item {
        display: inline-flex;
        margin: 0;
    }

    .bundle-vocab-pagination .page-link {
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(81, 29, 153, 0.18);
        color: #511D99;
        background: #fff;
        text-decoration: none;
    }

    .bundle-vocab-pagination .page-item.active .page-link {
        background: #511D99;
        border-color: #511D99;
        color: #fff;
    }

    .bundle-vocab-pagination .page-item.disabled .page-link {
        opacity: 0.45;
        pointer-events: none;
    }

    /* ── Sửa từ trực tiếp ───────────────────────────────────── */
    .bvw-actions {
        white-space: nowrap;
    }

    .bvw-actions .btn + .btn {
        margin-left: 4px;
    }

    .bvw-row-updated {
        animation: bvwRowFlash 1.4s ease;
    }

    @keyframes bvwRowFlash {
        from { background: rgba(22, 163, 74, 0.16); }
        to   { background: transparent; }
    }

    @media (prefers-reduced-motion: reduce) {
        .bvw-row-updated { animation: none; }
    }

    .bvw-alert {
        display: none;
    }

    /* ── Modal ──────────────────────────────────────────────── */
    .bvw-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 99999;
    }

    .bvw-modal.is-open {
        display: block;
    }

    .bvw-modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(2, 6, 23, 0.5);
    }

    .bvw-modal__dialog {
        position: relative;
        width: min(720px, calc(100vw - 24px));
        margin: 40px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.3);
        max-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .dark-mode .bvw-modal__dialog {
        background: #1e293b;
    }

    .bvw-modal__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 16px 22px;
        border-bottom: 1px solid #eceef4;
    }

    .bvw-modal__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .dark-mode .bvw-modal__title {
        color: #f1f5f9;
    }

    .bvw-modal__close {
        border: none;
        background: transparent;
        font-size: 28px;
        line-height: 1;
        color: #475569;
        cursor: pointer;
        padding: 0;
    }

    #bvwForm {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
    }

    .bvw-modal__body {
        padding: 18px 22px;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }

    .bvw-modal__footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 14px 22px;
        border-top: 1px solid #eceef4;
    }

    .bvw-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 16px;
    }

    .bvw-grid .bvw-full {
        grid-column: 1 / -1;
    }

    .bvw-field-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
    }

    .bvw-field-hint {
        color: #64748b;
        font-size: 12px;
        margin-top: 4px;
    }

    @media (max-width: 575px) {
        .bvw-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="btn btn-sm btn-outline-secondary mb-16">{{ trans('panel.bundle_vocabulary_back_to_list') }}</a>
    </div>

    <div class="col-12 col-lg-5">
        <div class="bg-white rounded-12 p-16 mb-20">
            <h4 class="font-16 mb-12">{{ trans('panel.bundle_vocabulary_set_detail') }} #{{ $set->id }}</h4>

            <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/update') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_bundle') }}</label>
                    <input type="text" class="form-control" value="{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}" readonly>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_set_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $set->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('public.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $set->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_intro_content') }}</label>
                    <textarea name="intro_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_intro_content_placeholder') }}">{{ old('intro_content', $set->intro_content) }}</textarea>
                    @error('intro_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_feature_content') }}</label>
                    <textarea name="feature_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_feature_content_placeholder') }}">{{ old('feature_content', $set->feature_content) }}</textarea>
                    @error('feature_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_source_file') }}</label>
                    <input type="file" name="source_file" class="form-control" accept=".csv,.txt,.xls,.xlsx">
                    <p class="font-12 text-gray-500 mt-8 mb-0">{{ trans('panel.bundle_vocabulary_file_template_hint') }}</p>
                    @error('source_file')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('panel.bundle_vocabulary_update') }}</button>
            </form>

            <hr>

            <div class="d-flex align-items-center flex-wrap gap-8">
                @if($set->status !== 'approved')
                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/submit') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-warning">{{ trans('panel.bundle_vocabulary_submit_for_approval') }}</button>
                    </form>
                @endif

                @if($canApprove)
                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/approve') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">{{ trans('panel.bundle_vocabulary_approve') }}</button>
                    </form>

                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/reject') }}" class="w-100 mt-8">
                        {{ csrf_field() }}
                        <label class="input-label">{{ trans('panel.bundle_vocabulary_rejection_note') }}</label>
                        <textarea name="rejection_note" class="form-control" rows="2" required>{{ old('rejection_note') }}</textarea>
                        <button type="submit" class="btn btn-danger mt-8">{{ trans('panel.bundle_vocabulary_reject') }}</button>
                    </form>
                @endif
            </div>

            @if(!empty($set->rejection_note))
                <div class="alert alert-danger mt-16 mb-0">{{ $set->rejection_note }}</div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="bg-white rounded-12 p-16">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-8 mb-8">
                <h4 class="font-16 mb-0">{{ trans('panel.bundle_vocabulary_words_count') }}: <span id="bvwWordsCount">{{ $set->words_count }}</span></h4>
                <button type="button" class="btn btn-sm btn-primary" id="bvwAddBtn">Thêm từ</button>
            </div>
            <p class="font-12 text-gray-500 mb-12">Thay đổi ở từng từ được áp dụng ngay cho học viên, không cần gửi duyệt lại.</p>

            <div class="alert bvw-alert" id="bvwPageAlert" role="status"></div>

            <div class="table-responsive bundle-vocab-scroll">
                <table class="table custom-table" id="bvwTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('panel.bundle_vocabulary_actions') }}</th>
                            <th>Word</th>
                            <th>POS</th>
                            <th>IPA</th>
                            <th>{{ trans('panel.definition') }}</th>
                            <th>{{ trans('panel.translation') }}</th>
                            <th>Audio</th>
                            <th>Collocation</th>
                            <th>Example</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($words as $word)
                            <tr data-word-id="{{ $word->id }}" data-word="{{ json_encode($word->only($bvwFields)) }}">
                                <td data-field="sort_order">{{ $word->sort_order }}</td>
                                <td class="bvw-actions">
                                    <button type="button" class="btn btn-sm btn-outline-primary js-bvw-edit">Sửa</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger js-bvw-delete">Xoá</button>
                                </td>
                                <td data-field="word">{{ $word->word }}</td>
                                <td data-field="part_of_speech">{{ $word->part_of_speech }}</td>
                                <td data-field="pronunciation">{{ $word->pronunciation }}</td>
                                <td data-field="definition">{{ $word->definition }}</td>
                                <td data-field="translation_vi">{{ $word->translation_vi }}</td>
                                <td data-field="audio_url">
                                    @if(!empty($word->audio_url))
                                        <a href="{{ $word->audio_url }}" target="_blank" rel="noopener noreferrer">Audio link</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td data-field="collocation">{{ $word->collocation }}</td>
                                <td data-field="example">{{ $word->example }}</td>
                                <td data-field="image_url">
                                    @if(!empty($word->image_url))
                                        <a href="{{ $word->image_url }}" target="_blank" rel="noopener noreferrer">Image link</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-gray-500">{{ trans('public.no_result') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-16 bundle-vocab-pagination">{{ $words->appends(request()->all())->links() }}</div>
        </div>
    </div>
</div>

{{-- ── Modal thêm / sửa từ ─────────────────────────────────────── --}}
<div class="bvw-modal" id="bvwModal" aria-hidden="true">
    <div class="bvw-modal__backdrop js-bvw-close"></div>
    <div class="bvw-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bvwModalTitle">
        <div class="bvw-modal__header">
            <h5 class="bvw-modal__title" id="bvwModalTitle">Sửa từ</h5>
            <button type="button" class="bvw-modal__close js-bvw-close" aria-label="Đóng">&times;</button>
        </div>

        <form id="bvwForm" novalidate>
            <div class="bvw-modal__body">
                <div class="alert alert-danger bvw-alert" id="bvwFormAlert"></div>

                <div class="bvw-grid">
                    <div class="form-group">
                        <label class="input-label" for="bvwWord">Từ vựng <span class="text-danger">*</span></label>
                        <input type="text" id="bvwWord" name="word" class="form-control" maxlength="255" required>
                        <div class="bvw-field-error" data-error-for="word"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="bvwPos">Loại từ</label>
                        <input type="text" id="bvwPos" name="part_of_speech" class="form-control" maxlength="50" placeholder="noun, verb, adj...">
                        <div class="bvw-field-error" data-error-for="part_of_speech"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="bvwIpa">Phiên âm (IPA)</label>
                        <input type="text" id="bvwIpa" name="pronunciation" class="form-control" maxlength="255">
                        <div class="bvw-field-error" data-error-for="pronunciation"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="bvwTranslation">Nghĩa tiếng Việt</label>
                        <input type="text" id="bvwTranslation" name="translation_vi" class="form-control">
                        <div class="bvw-field-hint">Bỏ trống cả nghĩa và định nghĩa thì hệ thống tự dịch.</div>
                        <div class="bvw-field-error" data-error-for="translation_vi"></div>
                    </div>

                    <div class="form-group bvw-full">
                        <label class="input-label" for="bvwDefinition">Định nghĩa</label>
                        <textarea id="bvwDefinition" name="definition" class="form-control" rows="2"></textarea>
                        <div class="bvw-field-error" data-error-for="definition"></div>
                    </div>

                    <div class="form-group bvw-full">
                        <label class="input-label" for="bvwCollocation">Collocation</label>
                        <textarea id="bvwCollocation" name="collocation" class="form-control" rows="2"></textarea>
                        <div class="bvw-field-hint">Mỗi cụm một dòng, hoặc ngăn cách bằng dấu phẩy.</div>
                        <div class="bvw-field-error" data-error-for="collocation"></div>
                    </div>

                    <div class="form-group bvw-full">
                        <label class="input-label" for="bvwExample">Ví dụ</label>
                        <textarea id="bvwExample" name="example" class="form-control" rows="2"></textarea>
                        <div class="bvw-field-error" data-error-for="example"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="bvwAudio">Link audio</label>
                        <input type="url" id="bvwAudio" name="audio_url" class="form-control" placeholder="https://...">
                        <div class="bvw-field-error" data-error-for="audio_url"></div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="bvwImage">Link hình ảnh</label>
                        <input type="url" id="bvwImage" name="image_url" class="form-control" placeholder="https://...">
                        <div class="bvw-field-error" data-error-for="image_url"></div>
                    </div>
                </div>
            </div>

            <div class="bvw-modal__footer">
                <button type="button" class="btn btn-outline-secondary js-bvw-close">Huỷ</button>
                <button type="submit" class="btn btn-primary" id="bvwSubmitBtn">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function ($) {
    "use strict";

    const BASE_URL   = @json(url('/panel/dictionary/bundle-vocabulary/' . $set->id . '/words'));
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content') || @json(csrf_token());
    const FLASH_KEY  = 'bvwFlashMessage';
    const FORM_FIELDS = ['word', 'part_of_speech', 'pronunciation', 'definition', 'translation_vi', 'collocation', 'example', 'audio_url', 'image_url'];
    const TEXT_FIELDS = ['sort_order', 'word', 'part_of_speech', 'pronunciation', 'definition', 'translation_vi', 'collocation', 'example'];

    const $modal = $('#bvwModal').appendTo('body');
    const $form = $('#bvwForm');
    const $submitBtn = $('#bvwSubmitBtn');
    let editingWordId = null;

    // ── Thông báo ────────────────────────────────────────────────
    let pageAlertTimer = null;

    function showPageAlert(type, message) {
        const $alert = $('#bvwPageAlert');
        clearTimeout(pageAlertTimer);
        $alert.removeClass('alert-success alert-danger')
              .addClass('alert-' + type)
              .text(message)
              .stop(true, true)
              .show();
        pageAlertTimer = setTimeout(function () { $alert.fadeOut(200); }, 5000);
    }

    function extractErrorMessage(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }
        if (xhr.status === 403) {
            return 'Bạn không có quyền chỉnh sửa bộ từ vựng này.';
        }
        if (xhr.status === 419) {
            return 'Phiên làm việc đã hết hạn. Tải lại trang rồi thử lại.';
        }
        return 'Không lưu được thay đổi. Kiểm tra kết nối rồi thử lại.';
    }

    // Hiện thông báo sau khi thêm từ và chuyển trang
    try {
        const flash = sessionStorage.getItem(FLASH_KEY);
        if (flash) {
            sessionStorage.removeItem(FLASH_KEY);
            showPageAlert('success', flash);
        }
    } catch (e) {}

    // ── Modal ────────────────────────────────────────────────────
    function clearFormErrors() {
        $form.find('.bvw-field-error').text('');
        $form.find('.is-invalid').removeClass('is-invalid');
        $('#bvwFormAlert').hide().text('');
    }

    function openModal(mode, word) {
        editingWordId = mode === 'edit' ? word.id : null;
        clearFormErrors();

        FORM_FIELDS.forEach(function (field) {
            const value = word && word[field] !== null && word[field] !== undefined ? word[field] : '';
            $form.find('[name="' + field + '"]').val(value);
        });

        $('#bvwModalTitle').text(mode === 'edit' ? 'Sửa từ "' + (word.word || '') + '"' : 'Thêm từ mới');
        $submitBtn.text(mode === 'edit' ? 'Lưu thay đổi' : 'Thêm từ');

        $modal.addClass('is-open').attr('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        setTimeout(function () { $('#bvwWord').trigger('focus'); }, 50);
    }

    function closeModal() {
        $modal.removeClass('is-open').attr('aria-hidden', 'true');
        document.body.style.overflow = '';
        editingWordId = null;
    }

    $(document).on('click', '.js-bvw-close', closeModal);

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $modal.hasClass('is-open')) {
            closeModal();
        }
    });

    $('#bvwAddBtn').on('click', function () {
        openModal('add', null);
    });

    $(document).on('click', '.js-bvw-edit', function () {
        const word = $(this).closest('tr').data('word');
        if (word) {
            openModal('edit', word);
        }
    });

    // ── Cập nhật dòng trong bảng ─────────────────────────────────
    function renderLinkCell($td, url, label) {
        $td.empty();
        if (url) {
            $td.append($('<a>', { href: url, target: '_blank', rel: 'noopener noreferrer', text: label }));
        } else {
            $td.text('-');
        }
    }

    function renderRow($row, word) {
        $row.data('word', word);

        TEXT_FIELDS.forEach(function (field) {
            const value = word[field] !== null && word[field] !== undefined ? word[field] : '';
            $row.find('[data-field="' + field + '"]').text(value);
        });

        renderLinkCell($row.find('[data-field="audio_url"]'), word.audio_url, 'Audio link');
        renderLinkCell($row.find('[data-field="image_url"]'), word.image_url, 'Image link');

        $row.removeClass('bvw-row-updated');
        void $row[0].offsetWidth; // restart animation
        $row.addClass('bvw-row-updated');
    }

    // ── Lưu (thêm / sửa) ─────────────────────────────────────────
    $form.on('submit', function (e) {
        e.preventDefault();
        clearFormErrors();

        const isEdit = editingWordId !== null;
        const url = isEdit
            ? BASE_URL + '/' + editingWordId + '/update'
            : BASE_URL + '/store';
        const originalLabel = $submitBtn.text();

        $submitBtn.prop('disabled', true).text('Đang lưu...');

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: $form.serialize() + '&_token=' + encodeURIComponent(CSRF_TOKEN)
        })
        .done(function (res) {
            if (!res.success) {
                $('#bvwFormAlert').text(res.message || 'Không lưu được thay đổi.').show();
                return;
            }

            if (isEdit) {
                const $row = $('#bvwTable tr[data-word-id="' + editingWordId + '"]');
                if ($row.length) {
                    renderRow($row, res.data);
                }
                closeModal();
                showPageAlert('success', res.message);
                return;
            }

            // Từ mới nằm cuối bộ → chuyển tới trang cuối để thấy từ vừa thêm
            try { sessionStorage.setItem(FLASH_KEY, res.message); } catch (err) {}
            const target = new URL(window.location.href);
            target.searchParams.set('page', res.last_page || 1);
            window.location.href = target.toString();
        })
        .fail(function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function (field, messages) {
                    const $input = $form.find('[name="' + field + '"]');
                    if ($input.length) {
                        $input.addClass('is-invalid');
                        $form.find('[data-error-for="' + field + '"]').text(messages[0]);
                    } else {
                        $('#bvwFormAlert').text(messages[0]).show();
                    }
                });
                return;
            }

            $('#bvwFormAlert').text(extractErrorMessage(xhr)).show();
        })
        .always(function () {
            $submitBtn.prop('disabled', false).text(originalLabel);
        });
    });

    // ── Xoá ──────────────────────────────────────────────────────
    $(document).on('click', '.js-bvw-delete', function () {
        const $btn = $(this);
        const $row = $btn.closest('tr');
        const word = $row.data('word');

        if (!word) return;

        const confirmed = confirm(
            'Xoá từ "' + word.word + '" khỏi bộ từ vựng?\n' +
            'Tiến độ học của học viên với từ này cũng bị xoá và không khôi phục được.'
        );
        if (!confirmed) return;

        $btn.prop('disabled', true);

        $.ajax({
            url: BASE_URL + '/' + word.id + '/delete',
            method: 'POST',
            dataType: 'json',
            data: { _token: CSRF_TOKEN }
        })
        .done(function (res) {
            if (!res.success) {
                $btn.prop('disabled', false);
                showPageAlert('danger', res.message || 'Không xoá được từ này.');
                return;
            }

            $row.fadeOut(200, function () { $row.remove(); });
            $('#bvwWordsCount').text(res.words_count);
            showPageAlert('success', res.message);
        })
        .fail(function (xhr) {
            $btn.prop('disabled', false);
            showPageAlert('danger', extractErrorMessage(xhr));
        });
    });

})(jQuery);
</script>
@endpush