@extends('design_1.panel.layouts.panel', ['panelContentFull' => true, 'hidePanelTitleBar' => true])

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page_noticeboards") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page") }}">
    <style>
        /* Make learning page fill the panel content area */
        .panel-content {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .learning-page {
            flex: 1;
            min-height: 0;
            height: calc(100vh - 70px);
        }
        .learning-page__main {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex: 1;
            min-width: 0;
        }
        /* Override course link back to panel course detail */
        .learning-page__top-header .js-course-back-link {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="learning-page d-flex">
        <div class="learning-page__main">
            {{-- Top Header --}}
            @include('design_1.web.courses.learning_page.includes.top_header')

            {{-- Page Content --}}
            @include('design_1.web.courses.learning_page.includes.main_content')
        </div>

        {{-- Curriculum Sidebar --}}
        @include('design_1.web.courses.learning_page.includes.sidebar.index')
    </div>

    {{-- Noticeboards Drawer --}}
    @include('design_1.web.courses.learning_page.noticeboards.index')
@endsection

@push('scripts_bottom')
    <script>
        {{-- Learning page required globals --}}
        var courseUrl           = '{{ $panelCourseDetailUrl }}';
        var courseSlug          = '{{ $course->slug }}';
        {{-- Keep original courseLearningUrl for AJAX calls (track-time, personal-note/*) --}}
        var courseLearningUrl   = '{{ $course->getLearningPageUrl() }}';

        var defaultItemType = '{{ !empty(request()->get('type')) ? request()->get('type') : (!empty($userLearningLastView) ? $userLearningLastView->item_type : '') }}';
        var defaultItemId   = '{{ !empty(request()->get('item')) ? request()->get('item') : (!empty($userLearningLastView) ? $userLearningLastView->item_id : '') }}';
        var loadFirstContent = {{ (!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true' }};

        {{-- Lang strings required by learning_page.js --}}
        var learningPageEmptyContentTitleLang    = '{{ trans('update.learning_page_empty_content_title') }}';
        var learningPageEmptyContentHintLang     = '{{ trans('update.learning_page_empty_content_hint') }}';
        var pleaseWaitLang                       = '{{ trans('update.please_wait') }}';
        var pleaseWaitForTheContentLang          = '{{ trans('update.please_wait_for_the_content_to_load') }}';
        var newCourseNoteLang                    = '{{ trans('update.new_course_note') }}';
        var editCourseNoteLang                   = '{{ trans('update.edit_course_note') }}';
        var courseNoteLang                       = '{{ trans('update.course_note') }}';
        var saveNoteLang                         = '{{ trans('update.save_note') }}';
        var deleteNoteLang                       = '{{ trans('update.delete_note') }}';
        var submittedOnLang                      = '{{ trans('update.submitted_on') }}';
        var editLang                             = '{{ trans('public.edit') }}';
        var accessDeniedLang                     = '{{ trans('update.access_denied') }}';
        var noteLang                             = '{{ trans('update.note') }}';
        var accessDeniedModalFooterHintLang      = '{{ trans('update.your_access_will_be_delegated_automatically') }}';
        var rateAssignmentLang                   = '{{ trans('update.rate_assignment') }}';
        var passGradeLang                        = '{{ trans('update.pass_grade') }}';
        var submitGradeLang                      = '{{ trans('update.submit_grade') }}';
        var submitQuestionLang                   = '{{ trans('update.submit_question') }}';
        var courseCompletedLang                  = '{{ trans('update.course_completed') }}';
    </script>

    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>
    <script src="{{ getDesign1ScriptPath("learning_page_noticeboards") }}"></script>
    <script src="{{ getDesign1ScriptPath("learning_page") }}"></script>

    <script>
        // Rewrite any "Go to course detail" link to use panel URL
        (function () {
            var originalCourseUrl = '{{ $course->getUrl() }}';
            var panelCourseUrl    = '{{ $panelCourseDetailUrl }}';
            document.querySelectorAll('a[href="' + originalCourseUrl + '"]').forEach(function (el) {
                el.href = panelCourseUrl;
            });
        })();
    </script>
@endpush
