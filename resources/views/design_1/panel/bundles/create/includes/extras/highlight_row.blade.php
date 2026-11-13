<div class="js-bundle-repeater-row d-flex align-items-start p-12 rounded-12 border-gray-200 mt-8">
    <div class="flex-grow-1">
        <input type="text" name="highlights[{{ $index }}][title]" class="form-control" value="{{ $highlight['title'] ?? '' }}" placeholder="{{ trans('bundle_learning.highlight_title') }}" maxlength="255"/>
        <textarea name="highlights[{{ $index }}][description]" rows="2" class="form-control mt-8" placeholder="{{ trans('bundle_learning.highlight_description') }}">{{ $highlight['description'] ?? '' }}</textarea>
    </div>
    <button type="button" class="js-bundle-repeater-remove btn btn-sm btn-transparent text-danger ml-8" aria-label="{{ trans('public.delete') }}">&times;</button>
</div>
