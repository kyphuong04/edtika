<div class="js-bundle-repeater-row d-flex align-items-start p-12 rounded-12 border-gray-200 mt-8">
    <div class="row flex-grow-1">
        <div class="col-12 col-md-5">
            <input type="text" name="gifts[{{ $index }}][title]" class="form-control" value="{{ $gift['title'] ?? '' }}" placeholder="{{ trans('bundle_learning.gift_title') }}" maxlength="255"/>
        </div>
        <div class="col-12 col-md-3 mt-8 mt-md-0">
            <select name="gifts[{{ $index }}][icon]" class="form-control">
                @foreach($giftIcons as $giftIcon)
                    <option value="{{ $giftIcon }}" {{ ($gift['icon'] ?? 'gift') === $giftIcon ? 'selected' : '' }}>{{ trans('bundle_learning.icon_' . $giftIcon) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4 mt-8 mt-md-0">
            <input type="text" name="gifts[{{ $index }}][description]" class="form-control" value="{{ $gift['description'] ?? '' }}" placeholder="{{ trans('bundle_learning.gift_description') }}"/>
        </div>
    </div>
    <button type="button" class="js-bundle-repeater-remove btn btn-sm btn-transparent text-danger ml-8" aria-label="{{ trans('public.delete') }}">&times;</button>
</div>
