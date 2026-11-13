{{-- GĐ2: Quà tặng, slider "Chi tiết và nội dung", cấu hình học tập, Mid-term/Final --}}
@php
    $extrasBundle = $bundle ?? null;
    $giftRows = old('gifts', !empty($extrasBundle) ? $extrasBundle->gifts->map(function ($gift) {
        return ['title' => $gift->title, 'icon' => $gift->icon, 'description' => $gift->description];
    })->all() : []);
    $highlightRows = old('highlights', !empty($extrasBundle) ? $extrasBundle->highlights->map(function ($highlight) {
        return ['title' => $highlight->title, 'description' => $highlight->description];
    })->all() : []);
    $giftIcons = config('bundle_learning.gift_icons', ['gift']);

    $checkpointsByType = !empty($extrasBundle) ? $extrasBundle->checkpoints->keyBy('type') : collect();

    // Quiz có thể dùng làm Mid-term/Final: quiz thường (không phải Mini Quiz) trong các module của khóa
    $checkpointQuizzes = collect();
    if (!empty($extrasBundle)) {
        $extrasWebinarIds = $extrasBundle->bundleWebinars()->pluck('webinar_id')->all();
        if (!empty($extrasWebinarIds)) {
            $checkpointQuizzes = \App\Models\Quiz::query()
                ->whereIn('webinar_id', $extrasWebinarIds)
                ->whereNull('file_id')
                ->get();
        }
    }

    $sequentialModules = old('learning_config_submitted') ? !empty(old('sequential_modules')) : (!empty($extrasBundle) and $extrasBundle->sequential_modules);
    $sequentialLessons = old('learning_config_submitted') ? !empty(old('sequential_lessons')) : (empty($extrasBundle) or is_null($extrasBundle->sequential_lessons) or $extrasBundle->sequential_lessons);
@endphp

<input type="hidden" name="bundle_extras_submitted" value="1">
<input type="hidden" name="learning_config_submitted" value="1">

{{-- ===== Quà tặng ===== --}}
<div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
    <div>
        <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.gifts') }}</h5>
        <p class="mt-4 font-12 text-gray-500">{{ trans('bundle_learning.gifts_hint') }}</p>
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary js-bundle-repeater-add" data-target="#bundleGiftRows" data-template="#bundleGiftTemplate">{{ trans('bundle_learning.add') }}</button>
</div>
@error('gifts') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

<div id="bundleGiftRows" class="mt-12" data-next-index="{{ count($giftRows) }}">
    @foreach($giftRows as $index => $gift)
        @include('design_1.panel.bundles.create.includes.extras.gift_row', ['index' => $index, 'gift' => $gift, 'giftIcons' => $giftIcons])
    @endforeach
</div>

<template id="bundleGiftTemplate">
    @include('design_1.panel.bundles.create.includes.extras.gift_row', ['index' => '__INDEX__', 'gift' => [], 'giftIcons' => $giftIcons])
</template>

{{-- ===== Slider "Chi tiết và nội dung" ===== --}}
<div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
    <div>
        <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.highlights') }}</h5>
        <p class="mt-4 font-12 text-gray-500">{{ trans('bundle_learning.highlights_hint') }}</p>
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary js-bundle-repeater-add" data-target="#bundleHighlightRows" data-template="#bundleHighlightTemplate">{{ trans('bundle_learning.add') }}</button>
</div>
@error('highlights') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

<div id="bundleHighlightRows" class="mt-12" data-next-index="{{ count($highlightRows) }}">
    @foreach($highlightRows as $index => $highlight)
        @include('design_1.panel.bundles.create.includes.extras.highlight_row', ['index' => $index, 'highlight' => $highlight])
    @endforeach
</div>

<template id="bundleHighlightTemplate">
    @include('design_1.panel.bundles.create.includes.extras.highlight_row', ['index' => '__INDEX__', 'highlight' => []])
</template>

{{-- ===== Cấu hình học tập ===== --}}
<div class="p-12 rounded-16 border-gray-300 border-dashed mt-32">
    <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.learning_config') }}</h5>

    <div class="d-flex align-items-center mt-12">
        <div class="custom-switch mr-8">
            <input id="sequentialModulesSwitch" type="checkbox" name="sequential_modules" class="custom-control-input" {{ $sequentialModules ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="sequentialModulesSwitch"></label>
        </div>
        <label class="cursor-pointer mb-0" for="sequentialModulesSwitch">{{ trans('bundle_learning.sequential_modules') }}</label>
    </div>
    <p class="font-12 text-gray-500 mt-4">- {{ trans('bundle_learning.sequential_modules_hint') }}</p>

    <div class="d-flex align-items-center mt-12">
        <div class="custom-switch mr-8">
            <input id="sequentialLessonsSwitch" type="checkbox" name="sequential_lessons" class="custom-control-input" {{ $sequentialLessons ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="sequentialLessonsSwitch"></label>
        </div>
        <label class="cursor-pointer mb-0" for="sequentialLessonsSwitch">{{ trans('bundle_learning.sequential_lessons') }}</label>
    </div>
    <p class="font-12 text-gray-500 mt-4">- {{ trans('bundle_learning.sequential_lessons_hint') }}</p>
</div>

{{-- ===== Mid-term / Final ===== --}}
<div class="p-12 rounded-16 border-gray-300 border-dashed mt-32">
    <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.checkpoints') }}</h5>
    <p class="mt-4 font-12 text-gray-500">{{ trans('bundle_learning.checkpoints_hint') }}</p>

    @if(empty($extrasBundle) or $checkpointQuizzes->isEmpty())
        <p class="mt-8 font-12 text-warning">{{ trans('bundle_learning.checkpoints_no_quiz') }}</p>
    @endif

    <div class="row mt-12">
        @foreach(\App\Models\BundleCheckpoint::TYPES as $checkpointType)
            @php
                $checkpoint = $checkpointsByType->get($checkpointType);
                $oldCheckpoint = old('checkpoints.' . $checkpointType);
                $checkpointEnabled = !is_null($oldCheckpoint) ? !empty($oldCheckpoint['enabled']) : !empty($checkpoint);
                $checkpointTitle = $oldCheckpoint['title'] ?? ($checkpoint->title ?? '');
                $checkpointQuizId = $oldCheckpoint['quiz_id'] ?? ($checkpoint->quiz_id ?? '');
                $checkpointUnlock = $oldCheckpoint['unlock_after_modules'] ?? ($checkpoint->unlock_after_modules ?? '');
            @endphp

            <div class="col-12 col-lg-6">
                <div class="p-12 rounded-12 border-gray-200 h-100">
                    <div class="d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkpoint{{ $checkpointType }}Switch" type="checkbox" name="checkpoints[{{ $checkpointType }}][enabled]" class="custom-control-input" {{ $checkpointEnabled ? 'checked' : '' }}>
                            <label class="custom-control-label cursor-pointer" for="checkpoint{{ $checkpointType }}Switch"></label>
                        </div>
                        <label class="cursor-pointer mb-0 font-weight-bold" for="checkpoint{{ $checkpointType }}Switch">{{ trans('bundle_learning.checkpoint_' . $checkpointType) }}</label>
                    </div>

                    <div class="form-group mt-12">
                        <label class="form-group-label">{{ trans('public.title') }}</label>
                        <input type="text" name="checkpoints[{{ $checkpointType }}][title]" class="form-control" value="{{ $checkpointTitle }}" maxlength="255"/>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('bundle_learning.checkpoint_quiz') }}</label>
                        <select name="checkpoints[{{ $checkpointType }}][quiz_id]" class="form-control @error('checkpoints.' . $checkpointType . '.quiz_id') is-invalid @enderror">
                            <option value="">{{ trans('bundle_learning.choose') }}</option>
                            @foreach($checkpointQuizzes as $checkpointQuiz)
                                <option value="{{ $checkpointQuiz->id }}" {{ (string)$checkpointQuizId === (string)$checkpointQuiz->id ? 'selected' : '' }}>{{ $checkpointQuiz->title }}</option>
                            @endforeach
                        </select>
                        @error('checkpoints.' . $checkpointType . '.quiz_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if($checkpointType === \App\Models\BundleCheckpoint::TYPE_MID)
                        <div class="form-group mb-0">
                            <label class="form-group-label">{{ trans('bundle_learning.unlock_after_modules') }}</label>
                            <input type="number" min="1" name="checkpoints[{{ $checkpointType }}][unlock_after_modules]" class="form-control" value="{{ $checkpointUnlock }}" placeholder="{{ trans('bundle_learning.unlock_after_modules_hint') }}"/>
                        </div>
                    @else
                        <p class="font-12 text-gray-500 mb-0">- {{ trans('bundle_learning.final_unlock_hint') }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts_bottom')
    <script>
        (function () {
            document.addEventListener('click', function (event) {
                var addButton = event.target.closest('.js-bundle-repeater-add');
                if (addButton) {
                    var container = document.querySelector(addButton.getAttribute('data-target'));
                    var template = document.querySelector(addButton.getAttribute('data-template'));
                    if (!container || !template) {
                        return;
                    }
                    var index = parseInt(container.getAttribute('data-next-index') || '0', 10);
                    container.insertAdjacentHTML('beforeend', template.innerHTML.replace(/__INDEX__/g, index));
                    container.setAttribute('data-next-index', index + 1);
                    return;
                }

                var removeButton = event.target.closest('.js-bundle-repeater-remove');
                if (removeButton) {
                    var row = removeButton.closest('.js-bundle-repeater-row');
                    if (row) {
                        row.remove();
                    }
                }
            });
        })();
    </script>
@endpush
