<div class="learning-page__main-content" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

    {{-- Exit / Back / Next nav row inside the content area --}}
    <div class="learning-page__content-nav">
        {{-- Exit button: returns to the course detail page --}}
        <a href="{{ isset($panelCourseDetailUrl) ? $panelCourseDetailUrl : $course->getUrl() }}"
           class="learning-page__nav-btn d-flex align-items-center gap-8 rounded-pill text-decoration-none">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ trans('panel.exit') }}</span>
        </a>

        {{-- Back / Next navigation --}}
        <div class="d-flex align-items-center gap-8">
            <button type="button" class="js-learning-page-prev-item learning-page__nav-btn d-flex align-items-center gap-8 rounded-pill">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ trans('update.back') }}</span>
            </button>

            <button type="button" class="js-learning-page-next-item learning-page__nav-btn d-flex align-items-center gap-8 rounded-pill">
                <span>{{ trans('update.next') }}</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mainContent" class="w-100">
        @if(!empty($isForumPage))
            @include('design_1.web.courses.learning_page.includes.contents.forum.index')
        @elseif(!empty($isForumAnswersPage))
            @include('design_1.web.courses.learning_page.includes.contents.forum.answers')
        @endif
    </div>
</div>
