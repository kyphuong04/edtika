{{-- GĐ2: tóm tắt các trường khóa học theo design mới (chỉ đọc) để admin duyệt --}}
<section class="mt-30">
    <h2 class="section-title after-line">{{ trans('bundle_learning.course_info') }}</h2>

    <div class="row mt-15">
        <div class="col-12 col-md-6">
            <table class="table table-sm table-bordered">
                <tbody>
                <tr>
                    <th class="w-50">{{ trans('bundle_learning.bundle_type') }}</th>
                    <td>{{ !empty($bundle->bundle_type) ? trans('bundle_learning.bundle_type_' . $bundle->bundle_type) : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.exam_type') }}</th>
                    <td>{{ !empty($bundle->exam_type) ? strtoupper($bundle->exam_type) : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.band_from') }} → {{ trans('bundle_learning.band_to') }}</th>
                    <td>{{ $bundle->band_from ?? '-' }} → {{ $bundle->band_to ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.roadmap_months') }}</th>
                    <td>{{ !empty($bundle->roadmap_months) ? $bundle->roadmap_months . ' ' . trans('bundle_learning.months') : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.original_price') }}</th>
                    <td>
                        {{ !empty($bundle->original_price) ? number_format($bundle->original_price) . ' ' . $bundle->getPriceCurrency() : '-' }}
                        @if($bundle->getOriginalPriceDiscountPercent() > 0)
                            <span class="badge badge-success ml-1">-{{ $bundle->getOriginalPriceDiscountPercent() }}%</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.is_combo') }}</th>
                    <td>{{ $bundle->is_combo ? '✓' : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.sequential_modules') }}</th>
                    <td>{{ $bundle->sequential_modules ? '✓' : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('bundle_learning.sequential_lessons') }}</th>
                    <td>{{ (is_null($bundle->sequential_lessons) or $bundle->sequential_lessons) ? '✓' : '-' }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12 col-md-6">
            <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.gifts') }}</h5>
            <ul class="mb-15">
                @forelse($bundle->gifts as $gift)
                    <li>{{ $gift->title }}@if(!empty($gift->description)) – <span class="text-muted">{{ $gift->description }}</span>@endif</li>
                @empty
                    <li class="text-muted">-</li>
                @endforelse
            </ul>

            <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.highlights') }}</h5>
            <ul class="mb-15">
                @forelse($bundle->highlights as $highlight)
                    <li>{{ $highlight->title }}</li>
                @empty
                    <li class="text-muted">-</li>
                @endforelse
            </ul>

            <h5 class="font-14 font-weight-bold">{{ trans('bundle_learning.checkpoints') }}</h5>
            <ul class="mb-0">
                @forelse($bundle->checkpoints as $checkpoint)
                    <li>
                        {{ trans('bundle_learning.checkpoint_' . $checkpoint->type) }}:
                        {{ !empty($checkpoint->quiz) ? $checkpoint->quiz->title : '-' }}
                    </li>
                @empty
                    <li class="text-muted">-</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
