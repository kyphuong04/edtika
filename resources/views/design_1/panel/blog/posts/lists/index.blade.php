@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .blog-post-modal-trigger { cursor: pointer; }
        .blog-post-modal-form {
            max-width: 680px;
            margin: 0 auto;
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
        .blog-post-modal-form .note-editing-area {
            min-height: 220px;
        }
    </style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h2 class="font-20 font-weight-bold">{{ trans('update.articles') }}</h2>
            <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_blog_posts_and_related_statistics') }}</p>
        </div>
        @can('panel_blog_new_article')
            <a href="/panel/blog/new" class="btn btn-primary d-flex align-items-center blog-post-modal-trigger" data-modal-title="{{ trans('update.create_a_post') }}" style="background-color: #511D99; border-color: #511D99;">
                <x-iconsax-lin-add class="icons mr-8" width="16"/>
                {{ trans('update.new_post') }}
            </a>
        @endcan
    </div>

    {{-- Top Stats --}}
    @include('design_1.panel.blog.posts.lists.top_stats')

    {{-- Lists --}}
    @if(!empty($posts) and count($posts))
        <div id="tableListContainer" class="mt-24" data-view-data-path="/panel/blog">

            <div class="row js-table-body-lists">
                @foreach($posts as $postRow)
                    @include('design_1.panel.blog.posts.lists.table_items', ['post' => $postRow])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'blog_posts.svg',
            'title' => trans('update.blog_post_no_result'),
            'hint' => nl2br(trans('update.blog_post_no_result_hint')),
            'btn' => ['url' => '/panel/blog/new','text' => trans('update.create_a_post')]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        (function ($) {
            'use strict';

            const editArticleTitle = @json(trans('public.edit') . ' ' . trans('update.blog_article'));
            const createArticleTitle = @json(trans('update.create_a_post'));
            const requestFailedText = @json(trans('public.request_failed'));

            function initBlogPostEditor($modal) {
                const $editor = $modal.find('.js-blog-post-editor');

                if ($editor.length && $.fn.summernote && !$editor.next('.note-editor').length) {
                    $editor.summernote({
                        dialogsInBody: true,
                        tabsize: 2,
                        height: 280,
                        placeholder: $editor.attr('placeholder'),
                        toolbar: [
                            ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                            ['para', ['ul', 'ol', 'paragraph']],
                        ]
                    });
                }
            }

            function bindImagePreview($scope) {
                $scope.find('.js-blog-post-image-input').off('change.blogPostPreview').on('change.blogPostPreview', function () {
                    const input = this;
                    const $wrapper = $(input).closest('.blog-post-modal-form__upload');
                    const $preview = $wrapper.find('.js-blog-post-image-preview');
                    const file = input.files && input.files[0];

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
            }

            function clearBlogPostErrors($form) {
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.js-blog-post-error').text('');
            }

            function applyBlogPostErrors($form, errors) {
                Object.keys(errors || {}).forEach(function (field) {
                    const $field = $form.find('[name="' + field + '"]');
                    const $error = $form.find('.js-blog-post-error[data-field="' + field + '"]');

                    if ($field.length) {
                        $field.addClass('is-invalid');
                    }

                    if ($error.length) {
                        $error.text(errors[field][0]);
                    }
                });
            }

            $('body').on('click', 'a[href="/panel/blog/new"], a[href^="/panel/blog/"][href$="/edit"]', function (event) {
                event.preventDefault();

                const $trigger = $(this);
                const path = $trigger.attr('href');
                const title = $trigger.data('modal-title') || ($trigger.attr('href').endsWith('/edit') ? editArticleTitle : createArticleTitle);

                handleBasicModal(path, title, function (result, $body, $footer) {
                    $footer.html('&nbsp;');
                    initBlogPostEditor($body);
                    bindImagePreview($body);
                }, '', '48rem');
            });

            $('body').on('submit', '.js-blog-post-modal-form', function (event) {
                event.preventDefault();

                const $form = $(this);
                const $submit = $form.find('.js-blog-post-submit');
                const formData = new FormData(this);

                clearBlogPostErrors($form);

                if ($submit.hasClass('loadingbar')) {
                    return;
                }

                $submit.addClass('loadingbar').prop('disabled', true);

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function () {
                        Swal.close();
                        window.location.reload();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            applyBlogPostErrors($form, xhr.responseJSON.errors);
                            return;
                        }

                        if (typeof notify !== 'undefined') {
                            notify('danger', requestFailedText);
                        }
                    },
                    complete: function () {
                        $submit.removeClass('loadingbar').prop('disabled', false);
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
