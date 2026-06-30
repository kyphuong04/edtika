@php
    $saleItem = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

    $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
    $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
    $isProgressing = false;

    if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing = true;
    }

    // Use panel detail pages for both webinars and bundles
    $panelDetailUrl   = !empty($sale->webinar)
        ? url('/panel/courses/purchases/' . $saleItem->slug)
        : url('/panel/courses/purchases/' . $saleItem->slug);
    $panelDetailTarget = '';
    $panelLearningUrl  = !empty($sale->webinar)
        ? url('/panel/courses/purchases/learning/' . $saleItem->slug)
        : url('/panel/courses/purchases/' . $saleItem->slug);
@endphp

@if(!empty($saleItem))
    <div class="panel-course-card-1 position-relative rounded-24">
        <div class="position-relative d-flex flex-column flex-lg-row  gap-12 z-index-2 bg-white p-12 rounded-24">
            {{-- Image (only for webinars, not bundles) --}}
            @if(!empty($sale->webinar))
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <a href="{{ $panelDetailUrl }}" {{ $panelDetailTarget }}>
                    <img src="{{ $saleItem->getImage() }}" alt="" class="img-cover rounded-16">
                </a>
            </div>
            @endif

            {{-- Content --}}
            <div class="panel-course-card-1__content flex-1 d-flex flex-column rounded-16">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">
                                <a href="{{ $panelDetailUrl }}" {{ $panelDetailTarget }} class="text-decoration-none text-dark">
                                    {{ truncate($saleItem->title, 46) }}
                                </a>
                            </h3>
                        </div>

                        {{-- actions dropdown removed per user preference --}}
                    </div>
                    {{-- Stats --}}
                    <a href="{{ $panelDetailUrl }}" {{ $panelDetailTarget }} class="text-decoration-none">
                        @include("design_1.panel.webinars.my_purchases.item_card.stats")
                    </a>
                </div>

                {{-- Progress & Price --}}
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-7">
                        @include("design_1.panel.webinars.my_purchases.item_card.progress_and_chart")
                    </div>

                    {{-- Continue Learning Button --}}
                    @if(!empty($sale->webinar))
                        <div class="col-5 d-flex align-items-center justify-content-end">
                            {{-- link now points to the course detail page instead of learning page --}}
                            <a href="{{ $panelDetailUrl }}" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 mr-4" style="color: #511D99">{{ trans('update.continue_learning') }}</span>
                                <x-iconsax-lin-arrow-right class="icons mt-2" width="16px" height="16px" style="color: #511D99"/>
                            </a>
                        </div>
                    @elseif(!empty($sale->bundle))
                        <div class="col-5 d-flex align-items-center justify-content-end">
                            <a href="{{ $panelDetailUrl }}" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 mr-4" style="color: #511D99">{{ trans('update.view_details') }}</span>
                                <x-iconsax-lin-arrow-right class="icons mt-2" width="16px" height="16px" style="color: #511D99"/>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

