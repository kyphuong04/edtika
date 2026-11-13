{{-- GĐ2: thông tin khóa học theo design mới (loại khóa, kỳ thi, band, lộ trình) --}}
@php
    $courseInfoBundle = $bundle ?? null;
    $courseInfoValue = function ($field, $default = null) use ($courseInfoBundle) {
        $old = old($field);
        if (!is_null($old)) {
            return $old;
        }

        return (!empty($courseInfoBundle) and !is_null($courseInfoBundle->{$field})) ? $courseInfoBundle->{$field} : $default;
    };
    $bandOptions = ['0.0', '1.0', '2.0', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0', '6.5', '7.0', '7.5', '8.0', '8.5', '9.0'];
    $selectedBandFrom = is_null($courseInfoValue('band_from')) ? '' : number_format((float)$courseInfoValue('band_from'), 1);
    $selectedBandTo = is_null($courseInfoValue('band_to')) ? '' : number_format((float)$courseInfoValue('band_to'), 1);
@endphp

<h3 class="font-14 font-weight-bold my-24">{{ trans('bundle_learning.course_info') }}</h3>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.bundle_type') }}</label>
            <select name="bundle_type" class="form-control @error('bundle_type') is-invalid @enderror">
                <option value="">{{ trans('bundle_learning.choose') }}</option>
                @foreach(\App\Models\Bundle::TYPES as $bundleType)
                    <option value="{{ $bundleType }}" {{ $courseInfoValue('bundle_type') === $bundleType ? 'selected' : '' }}>{{ trans('bundle_learning.bundle_type_' . $bundleType) }}</option>
                @endforeach
            </select>
            @error('bundle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.exam_type') }}</label>
            <select name="exam_type" class="form-control @error('exam_type') is-invalid @enderror">
                @foreach(\App\Models\Bundle::EXAM_TYPES as $examType)
                    <option value="{{ $examType }}" {{ $courseInfoValue('exam_type', 'ielts') === $examType ? 'selected' : '' }}>{{ strtoupper($examType) }}</option>
                @endforeach
            </select>
            @error('exam_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.band_from') }}</label>
            <select name="band_from" class="form-control @error('band_from') is-invalid @enderror">
                <option value="">{{ trans('bundle_learning.choose') }}</option>
                @foreach($bandOptions as $band)
                    <option value="{{ $band }}" {{ $selectedBandFrom === $band ? 'selected' : '' }}>{{ $band }}</option>
                @endforeach
            </select>
            @error('band_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.band_to') }}</label>
            <select name="band_to" class="form-control @error('band_to') is-invalid @enderror">
                <option value="">{{ trans('bundle_learning.choose') }}</option>
                @foreach($bandOptions as $band)
                    <option value="{{ $band }}" {{ $selectedBandTo === $band ? 'selected' : '' }}>{{ $band }}</option>
                @endforeach
            </select>
            @error('band_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.roadmap_months') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500 w-auto px-8">{{ trans('bundle_learning.months') }}</span>
            <input type="number" min="1" max="36" name="roadmap_months" class="form-control @error('roadmap_months') is-invalid @enderror" value="{{ $courseInfoValue('roadmap_months') }}"/>
            @error('roadmap_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="form-group-label">{{ trans('bundle_learning.demo_video_duration') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500 w-auto px-8">{{ trans('bundle_learning.seconds') }}</span>
            <input type="number" min="0" name="demo_video_duration" class="form-control @error('demo_video_duration') is-invalid @enderror" value="{{ $courseInfoValue('demo_video_duration') }}"/>
            @error('demo_video_duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
