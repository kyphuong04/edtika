@php
    /* ── Aggregate lesson count across all courses in the bundle ── */
    $totalLessons = 0;
    foreach ($bundle->bundleWebinars as $bw) {
        $webinar = $bw->webinar;
        if ($webinar) {
            $totalLessons += $webinar->sessions->count()
                + $webinar->files->count()
                + $webinar->textLessons->count();
        }
    }
    $totalCourses  = count($bundle->bundleWebinars);
    $totalStudents = count($bundle->sales);
    $totalSales    = handlePrice($bundle->sales->sum('amount'));
    $totalHours    = convertMinutesToHourAndMinute($bundle->getBundleDuration());
@endphp

<div class="materials-bundle-card" style="cursor:pointer;"
     onclick="window.location='/panel/bundles/{{ $bundle->id }}/modules'">

    {{-- Actions Dropdown (stop propagation so card click doesn't fire) --}}
    <div class="materials-bundle-card__actions actions-dropdown" onclick="event.stopPropagation()">
        <div class="webinar-card-actions-btn d-flex-center size-36 rounded-8 cursor-pointer"
             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </div>

        <div class="actions-dropdown__dropdown-menu dropdown-menu-width-200 dropdown-menu-top-28">
            <ul class="my-8">
                @can('panel_bundles_create')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/bundles/{{ $bundle->id }}/edit">{{ trans('public.edit') }}</a>
                    </li>
                @endcan

                @can('panel_bundles_courses')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/bundles/{{ $bundle->id }}/courses">{{ trans('product.courses') }}</a>
                    </li>
                @endcan

                @if($authUser->id == $bundle->teacher_id or $authUser->id == $bundle->creator_id)
                    @can('panel_bundles_export_students_list')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/bundles/{{ $bundle->id }}/export-students-list">
                                {{ trans('public.export_list') }}
                            </a>
                        </li>
                    @endcan
                @endif

                @if($bundle->creator_id == $authUser->id)
                    @can('panel_bundles_delete')
                        <li class="actions-dropdown__dropdown-menu-item">
                            @include('design_1.panel.includes.content_delete_btn', [
                                'deleteContentUrl'       => "/panel/bundles/{$bundle->id}/delete",
                                'deleteContentClassName' => ' text-danger',
                                'deleteContentItem'      => $bundle,
                                'deleteContentItemType'  => 'bundle',
                            ])
                        </li>
                    @endcan
                @endif
            </ul>
        </div>
    </div>

    {{-- Title (links to modules detail page) --}}
    <a href="/panel/bundles/{{ $bundle->id }}/modules" class="d-block">
        <h3 class="materials-bundle-card__title">{{ $bundle->title }}</h3>
    </a>

    {{-- Total lessons subtitle --}}
    <p class="materials-bundle-card__subtitle">
        Tổng số lượng bài học:
        <span class="font-weight-bold text-dark">{{ $totalLessons }} bài</span>
    </p>

    {{-- Stats Grid: 3 rows × 2 cols --}}
    <div class="materials-bundle-card__stats">

        {{-- Students --}}
        <div class="materials-bundle-card__stat">
            <x-iconsax-lin-profile-2user class="icons text-gray-400 flex-shrink-0" width="18px" height="18px"/>
            <span>
                <span class="stat-val">{{ $totalStudents }}</span>
                {{ trans('public.students') }}
            </span>
        </div>

        {{-- Lessons --}}
        <div class="materials-bundle-card__stat">
            <x-iconsax-lin-note-2 class="icons text-gray-400 flex-shrink-0" width="18px" height="18px"/>
            <span>
                <span class="stat-val">{{ $totalLessons }}</span>
                {{ trans('update.lessons') ?? 'bài học' }}
            </span>
        </div>

        {{-- Sales amount --}}
        <div class="materials-bundle-card__stat">
            <x-iconsax-lin-moneys class="icons text-gray-400 flex-shrink-0" width="18px" height="18px"/>
            <span>
                <span class="stat-val">{{ $totalSales }}</span>
                {{ trans('panel.sales') }}
            </span>
        </div>

        {{-- Hours --}}
        <div class="materials-bundle-card__stat">
            <x-iconsax-lin-clock-1 class="icons text-gray-400 flex-shrink-0" width="18px" height="18px"/>
            <span>
                <span class="stat-val">{{ $totalHours }}</span>
                {{ trans('home.hours') }}
            </span>
        </div>

        {{-- Courses count --}}
        <div class="materials-bundle-card__stat">
            <x-iconsax-lin-video-play class="icons text-gray-400 flex-shrink-0" width="18px" height="18px"/>
            <span>
                <span class="stat-val">{{ $totalCourses }}</span>
                {{ trans('product.courses') }}
            </span>
        </div>

        {{-- Price --}}
        <div class="materials-bundle-card__stat materials-bundle-card__price">
            @if($bundle->price > 0)
                @if($bundle->bestTicket() < $bundle->price)
                    <span class="text-primary">
                        {{ handlePrice($bundle->bestTicket(), true, true, false, null, true) }}
                    </span>
                @else
                    <span class="text-primary">
                        {{ handlePrice($bundle->price, true, true, false, null, true) }}
                    </span>
                @endif
            @else
                <span class="text-success">{{ trans('public.free') }}</span>
            @endif
        </div>

    </div>

</div>
