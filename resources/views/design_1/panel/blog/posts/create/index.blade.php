@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .blog-post-create-page {
            max-width: 760px;
            margin: 0 auto;
        }
        .blog-post-create-card {
            background: #fff;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, .08);
        }
        .blog-post-modal-form__upload {
            border: 1.5px dashed #4b5563;
            border-radius: 14px;
            padding: 24px;
            min-height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: #fff;
            position: relative;
            overflow: hidden;
        }
        .blog-post-modal-form__upload.has-preview {
            border-style: solid;
            padding: 0;
        }
        .blog-post-modal-form__upload-preview {
            width: 100%;
            height: 100%;
            min-height: 170px;
            object-fit: cover;
        }
        .blog-post-modal-form__upload-placeholder {
            text-align: center;
            color: #6b7280;
        }
        .blog-post-modal-form__upload-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }
        .blog-post-modal-form__upload-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 108px;
            height: 38px;
            border-radius: 999px;
            background: #6b7280;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            margin-top: 16px;
        }
        .blog-post-modal-form .note-editor.note-frame {
            border-radius: 16px;
            border-color: #1f2937;
        }
        .blog-post-modal-form .note-toolbar {
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="blog-post-create-page">
        <div class="blog-post-create-card">
            @include('design_1.panel.blog.posts.modals.form', ['isStandalonePage' => true])
        </div>
    </div>
@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        (function ($) {
            'use strict';

            const $editor = $('.js-blog-post-editor');

            if ($editor.length) {
                $editor.summernote({
                    dialogsInBody: true,
                    tabsize: 2,
                    height: 320,
                    placeholder: $editor.attr('placeholder'),
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]
                });
            }

            $('.js-blog-post-image-input').on('change', function () {
                const file = this.files && this.files[0];
                const $wrapper = $(this).closest('.blog-post-modal-form__upload');
                const $preview = $wrapper.find('.js-blog-post-image-preview');

                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    $preview.attr('src', event.target.result).removeClass('d-none');
                    $wrapper.addClass('has-preview');
                    $wrapper.find('.js-blog-post-upload-placeholder').addClass('d-none');
                };
                reader.readAsDataURL(file);
            });
        })(jQuery);
    </script>
@endpush
