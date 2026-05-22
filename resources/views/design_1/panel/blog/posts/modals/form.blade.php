@php
    $postTitle = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->title : old('title');
    $postContent = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->content : old('content');
    $postDescription = (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? $post->translate($locale ?? app()->getLocale())->description : old('description');
    $buttonLabel = !empty($post) ? trans('public.save') : trans('update.create_a_post');
@endphp

<form action="/panel/blog/{{ !empty($post) ? $post->id.'/update' : 'store' }}" method="post" enctype="multipart/form-data" class="blog-post-modal-form js-blog-post-modal-form">
    {{ csrf_field() }}

    <input type="hidden" name="locale" value="{{ $locale ?? mb_strtolower(app()->getLocale()) }}">
    <input type="hidden" name="subtitle" value="{{ (!empty($post) && !empty($post->translate($locale ?? app()->getLocale()))) ? ($post->translate($locale ?? app()->getLocale())->subtitle ?? '') : old('subtitle') }}">
    <input type="hidden" name="description" value="{{ $postDescription }}">
    <input type="hidden" name="study_time" value="{{ !empty($post) ? $post->study_time : old('study_time') }}">

    <div class="form-group">
        <label class="form-group-label sr-only">{{ trans('admin/main.title') }}</label>
        <input type="text" name="title" class="form-control form-control-lg rounded-16 @error('title') is-invalid @enderror" value="{{ $postTitle }}" placeholder="Article Title">
        <div class="invalid-feedback js-blog-post-error" data-field="title">@error('title') {{ $message }} @enderror</div>
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
        <label class="form-group-label sr-only">{{ trans('public.content') }}</label>
        <textarea name="content" class="form-control js-blog-post-editor @error('content') is-invalid @enderror" placeholder="Type your content">{!! $postContent !!}</textarea>
        <div class="invalid-feedback d-block js-blog-post-error" data-field="content">@error('content') {{ $message }} @enderror</div>
    </div>

    <div class="d-flex justify-content-center mt-28">
        <button type="submit" class="btn btn-primary rounded-50 px-32 js-blog-post-submit" style="background-color: #511D99; border-color: #511D99;">{{ $buttonLabel }}</button>
    </div>
</form>