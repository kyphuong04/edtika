@php
    $postTitle = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->title : old('title');
    $postSubtitle = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? ($post->translate($locale ?? app()->getLocale())->subtitle ?? '') : old('subtitle');
    $postContent = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->content : old('content');
    $postDescription = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->description : old('description');
    $postStudyTime = !empty($post) ? $post->study_time : old('study_time');
    $selectedCategoryId = !empty($post) ? $post->category_id : old('category_id');
    $selectedRelatedPostIds = !empty($post)
        ? $post->relatedPosts()->pluck('post_id')->toArray()
        : (old('related_post_ids', []));
    $buttonLabel = !empty($post) ? trans('public.save') : trans('update.create_a_post');
@endphp

<form action="/panel/blog/{{ !empty($post) ? $post->id.'/update' : 'store' }}" method="post" enctype="multipart/form-data" class="blog-post-modal-form js-blog-post-modal-form">
    {{ csrf_field() }}

    <input type="hidden" name="locale" value="{{ $locale ?? mb_strtolower(app()->getLocale()) }}">

    <div class="form-group">
        <label class="form-group-label">{{ trans('admin/main.category') }}</label>
        <select name="category_id" class="form-control form-control-lg rounded-16 @error('category_id') is-invalid @enderror">
            <option value="">{{ trans('admin/main.choose_category') }}</option>

            @foreach(($blogCategories ?? []) as $category)
                <option value="{{ $category->id }}" {{ (string)$selectedCategoryId === (string)$category->id ? 'selected' : '' }}>{{ $category->title }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="category_id">@error('category_id') {{ $message }} @enderror</div>
    </div>

    <div class="form-group">
        <label class="form-group-label sr-only">{{ trans('admin/main.title') }}</label>
        <input type="text" name="title" class="form-control form-control-lg rounded-16 @error('title') is-invalid @enderror" value="{{ $postTitle }}" placeholder="Article Title">
        <div class="invalid-feedback js-blog-post-error" data-field="title">@error('title') {{ $message }} @enderror</div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('admin/main.subtitle') ?? 'Phụ đề' }}</label>
        <input type="text" name="subtitle" class="form-control form-control-lg rounded-16 @error('subtitle') is-invalid @enderror" value="{{ $postSubtitle }}" placeholder="Article Subtitle">
        <div class="invalid-feedback js-blog-post-error" data-field="subtitle">@error('subtitle') {{ $message }} @enderror</div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.study_time') ?? 'Thời gian đọc (phút)' }}</label>
        <input type="number" name="study_time" class="form-control form-control-lg rounded-16 @error('study_time') is-invalid @enderror" value="{{ $postStudyTime }}" placeholder="e.g., 5" min="0">
        <div class="invalid-feedback js-blog-post-error" data-field="study_time">@error('study_time') {{ $message }} @enderror</div>
    </div>

    <div class="form-group bg-white-editor mt-24">
        <label class="form-group-label">{{ trans('admin/main.description') ?? 'Mô tả' }}</label>
        <textarea name="description" class="form-control js-blog-post-editor-description @error('description') is-invalid @enderror" placeholder="Article Description">{!! $postDescription !!}</textarea>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="description">@error('description') {{ $message }} @enderror</div>
    </div>

    <div class="form-group mt-24">
        <label class="form-group-label">{{ trans('update.related_posts') }}</label>
        @php
            $availableRelatedPosts = collect($availableRelatedPosts ?? [])->filter(function ($item) {
                return !empty($item) && !empty($item->id);
            });
        @endphp
        <select name="related_post_ids[]" class="form-control form-control-lg rounded-16 js-blog-related-posts-select @error('related_post_ids') is-invalid @enderror" multiple data-placeholder="{{ trans('update.select_related_post') }}">
            @forelse($availableRelatedPosts as $relatedPostOption)
                <option value="{{ $relatedPostOption->id }}" {{ in_array($relatedPostOption->id, $selectedRelatedPostIds) ? 'selected' : '' }}>
                    {{ $relatedPostOption->title }}{{ !empty($relatedPostOption->author) ? ' - '.$relatedPostOption->author->full_name : '' }}
                </option>
            @empty
                <option value="" disabled>{{ trans('update.blog_post_no_result') }}</option>
            @endforelse
        </select>
        <small class="text-gray-500 d-block mt-8">{{ trans('update.assign_related_posts_to_the_this_article') }}</small>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="related_post_ids">@error('related_post_ids') {{ $message }} @enderror</div>
    </div>

    <div class="form-group mt-24">
        <label class="d-block form-group-label text-center mb-12">{{ trans('update.article_cover') }}</label>
        <div class="blog-post-modal-form__upload {{ (!empty($post) && !empty($post->image)) ? 'has-preview' : '' }}">
            <img src="{{ (!empty($post) && !empty($post->image)) ? $post->image : '' }}" alt="{{ $postTitle }}" class="blog-post-modal-form__upload-preview js-blog-post-image-preview {{ (!empty($post) && !empty($post->image)) ? '' : 'd-none' }}">
            <div class="blog-post-modal-form__upload-placeholder js-blog-post-upload-placeholder {{ (!empty($post) && !empty($post->image)) ? 'd-none' : '' }}">
                <div class="d-flex-center">
                    <x-iconsax-lin-export-1 class="icons text-gray-500" width="34" height="34"/>
                </div>
                <span class="blog-post-modal-form__upload-btn">Upload Image</span>
            </div>
            <input type="file" name="image" class="blog-post-modal-form__upload-input js-blog-post-image-input @error('image') is-invalid @enderror" accept="image/*">
        </div>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="image">@error('image') {{ $message }} @enderror</div>
    </div>

    <div class="form-group bg-white-editor mt-24">
        <label class="form-group-label">{{ trans('public.content') ?? 'Nội dung' }}</label>
        <textarea name="content" class="form-control js-blog-post-editor @error('content') is-invalid @enderror" placeholder="Type your content">{!! $postContent !!}</textarea>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="content">@error('content') {{ $message }} @enderror</div>
    </div>

    <div class="d-flex justify-content-center mt-28">
        <button type="submit" class="btn rounded-50 px-32 js-blog-post-submit">{{ $buttonLabel }}</button>
    </div>
</form>