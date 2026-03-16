<div class="col-12 col-md-6 col-lg-4 mb-24">
    <div class="bg-white rounded-16 border-gray-200 h-100 position-relative overflow-hidden" style="box-shadow: 0 1px 4px rgba(0,0,0,.06);">

        {{-- Thumbnail --}}
        <div class="position-relative w-100" style="height: 190px; background: #e8e8e8;">
            @if(!empty($post->image))
                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-cover w-100 h-100">
            @else
                <div class="w-100 h-100 d-flex-center bg-gray-100">
                    <x-iconsax-lin-gallery class="icons text-gray-300" width="48"/>
                </div>
            @endif

            {{-- ... Dropdown placed on the thumbnail --}}
            <div class="actions-dropdown position-absolute" style="top: 10px; right: 10px;">
                <button type="button" class="d-flex-center size-32 bg-white rounded-8" style="box-shadow: 0 1px 4px rgba(0,0,0,.15);">
                    <x-iconsax-lin-more class="icons text-gray-600" width="16"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-160 dropdown-menu-top-40">
                    <ul class="my-8">
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/blog/{{ $post->id }}/edit" class="d-flex align-items-center gap-8 px-16 py-8 text-dark">
                                <x-iconsax-lin-edit-2 class="icons text-primary" width="16"/>
                                {{ trans('public.edit') }}
                            </a>
                        </li>

                        @can('panel_blog_delete_article')
                            <li class="actions-dropdown__dropdown-menu-item">
                                @include('design_1.panel.includes.content_delete_btn', [
                                    'deleteContentUrl' => "/panel/blog/{$post->id}/delete",
                                    'deleteContentClassName' => 'd-flex align-items-center gap-8 w-100 px-16 py-8 btn-transparent text-danger',
                                    'deleteContentItem' => $post,
                                    'deleteContentItemType' => "post",
                                ])
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-16">

            {{-- Title + Status --}}
            <div class="d-flex align-items-start justify-content-between gap-8">
                <h3 class="font-15 font-weight-bold text-dark" style="line-height: 1.4; flex: 1; min-width: 0; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <a href="{{ $post->getUrl() }}" target="_blank" class="text-dark">{{ $post->title }}</a>
                </h3>

                <span class="flex-shrink-0 d-inline-flex-center px-8 py-4 rounded-8 font-12
                    @if($post->status == 'publish') bg-success-20 text-success
                    @else bg-warning-20 text-warning
                    @endif">
                    @if($post->status == 'publish')
                        {{ trans('public.published') }}
                    @else
                        {{ trans('public.pending') }}
                    @endif
                </span>
            </div>

            {{-- Author + Date --}}
            <div class="d-flex align-items-center mt-10 gap-4">
                @if(!empty($post->author))
                    <span class="font-13 font-weight-bold text-dark">{{ $post->author->full_name }}</span>
                    <span class="text-gray-300 font-12 mx-4">·</span>
                @endif
                <span class="font-12 text-gray-500">{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
            </div>

            {{-- Description --}}
            @if(!empty($post->description))
                <p class="font-13 text-gray-500 mt-8" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.5;">
                    {{ Str::limit(strip_tags($post->description), 110) }}
                </p>
            @endif

        </div>
    </div>
</div>
