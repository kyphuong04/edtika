<div id="learningPageSidebar" class="learning-page__sidebar">

    {{-- Mobile Header --}}
    <div class="learning-page__sidebar-header px-16 border-bottom-gray-200">
        <div class="js-toggle-show-learning-page-sidebar-drawer cursor-pointer">
            <x-iconsax-lin-add class="icons close-icon text-gray-500" width="28px" height="28px"/>
        </div>
    </div>

    <div class="learning-page__sidebar-content" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
        <div class="px-16">

            @php
                $overallPercent    = $course->getProgress(true);
                $hasChapters       = !empty($course->chapters) && count($course->chapters);
                $filterChapterId   = request()->get('chapter'); // filter to single chapter when set

                $hasItemsWithoutChapter = (!empty($sessionsWithoutChapter) && count($sessionsWithoutChapter))
                    || (!empty($textLessonsWithoutChapter) && count($textLessonsWithoutChapter))
                    || (!empty($filesWithoutChapter) && count($filesWithoutChapter));

                // When a chapter filter is active, hide items-without-chapter row
                if (!empty($filterChapterId)) {
                    $hasItemsWithoutChapter = false;
                }
            @endphp

            {{-- Course Expire Alert --}}
            @if(!empty($course->access_days) and !empty($saleItem))
                @php
                    $courseExpired = $course->getExpiredAccessDays($saleItem->created_at, $saleItem->gift_id)
                @endphp
                <div class="d-flex align-items-center bg-warning-10 border-warning rounded-12 p-12 mb-12">
                    <x-iconsax-bul-danger class="icons text-warning" width="20px" height="20px"/>
                    <span class="font-12 text-warning ml-8">{!! trans('update.course_expires_on_date', ['date' => dateTimeFormat($courseExpired, 'j M Y')]) !!}</span>
                </div>
            @endif

            {{-- Sections with Timeline --}}
            @if($hasChapters)
                @foreach($course->chapters as $chapter)
                    {{-- Skip chapters that don't match the active filter --}}
                    @if(!empty($filterChapterId) && $chapter->id != $filterChapterId)
                        @continue
                    @endif
                    {{-- Card 1: Section title + count + progress --}}
                    <div class="learning-section-info-card mb-16">
                        <h4 class="learning-section-title">{{ mb_strtoupper($chapter->title) }}</h4>
                        <p class="learning-section-count">{{ $chapter->getTopicsCount(true) }} {{ trans('public.lessons') }}</p>
                        <div class="learning-section-progress-track mt-10">
                            <div class="js-course-learning-progress-bar-percent learning-section-progress-fill" style="width: {{ $overallPercent }}%"></div>
                        </div>
                    </div>

                    {{-- Card 2: Lesson timeline --}}
                    <div class="learning-timeline-card mb-16">
                        <div class="learning-timeline">
                            @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                                @foreach($chapter->chapterItems as $chapterItem)
                                    @php
                                        $tlItem   = null;
                                        $tlType   = null;
                                        $tlTitle  = null;
                                        $tlSub    = null;
                                        $tlId     = null;
                                        $tlPassed = false;
                                        $tlLocked = false;
                                        $tlPErr   = '';
                                        $tlDErr   = '';
                                        $isLast   = $loop->last;

                                        if ($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession && !empty($chapterItem->session) && $chapterItem->session->status == 'active') {
                                            $s = $chapterItem->session;
                                            $tlItem = $s; $tlType = \App\Models\WebinarChapter::$chapterSession;
                                            $tlTitle = $s->title; $tlSub = trans('update.live'); $tlId = $s->id;
                                            $tlPassed = !empty($s->checkPassedItem());
                                            $seq = $s->checkSequenceContent();
                                            $tlLocked = !empty($seq) && (!empty($seq['all_passed_items_error']) || !empty($seq['access_after_day_error']));
                                            $tlPErr = $seq['all_passed_items_error'] ?? ''; $tlDErr = $seq['access_after_day_error'] ?? '';
                                        } elseif ($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile && !empty($chapterItem->file) && $chapterItem->file->status == 'active') {
                                            $f = $chapterItem->file;
                                            $tlItem = $f; $tlType = \App\Models\WebinarChapter::$chapterFile;
                                            $tlTitle = $f->title; $tlSub = trans('update.file_type_' . $f->file_type); $tlId = $f->id;
                                            $tlPassed = !empty($f->checkPassedItem());
                                            $seq = $f->checkSequenceContent();
                                            $tlLocked = !empty($seq) && (!empty($seq['all_passed_items_error']) || !empty($seq['access_after_day_error']));
                                            $tlPErr = $seq['all_passed_items_error'] ?? ''; $tlDErr = $seq['access_after_day_error'] ?? '';
                                        } elseif ($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson && !empty($chapterItem->textLesson) && $chapterItem->textLesson->status == 'active') {
                                            $tl = $chapterItem->textLesson;
                                            $tlItem = $tl; $tlType = \App\Models\WebinarChapter::$chapterTextLesson;
                                            $tlTitle = $tl->title; $tlSub = trans('webinars.text_lesson'); $tlId = $tl->id;
                                            $tlPassed = !empty($tl->checkPassedItem());
                                            $seq = $tl->checkSequenceContent();
                                            $tlLocked = !empty($seq) && (!empty($seq['all_passed_items_error']) || !empty($seq['access_after_day_error']));
                                            $tlPErr = $seq['all_passed_items_error'] ?? ''; $tlDErr = $seq['access_after_day_error'] ?? '';
                                        } elseif ($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment && !empty($chapterItem->assignment) && $chapterItem->assignment->status == 'active') {
                                            $a = $chapterItem->assignment;
                                            $tlItem = $a; $tlType = 'assignment';
                                            $tlTitle = $a->title; $tlSub = trans('quiz.assignment'); $tlId = $a->id;
                                            $tlPassed = false;
                                            $seq = $a->checkSequenceContent();
                                            $tlLocked = !empty($seq) && (!empty($seq['all_passed_items_error']) || !empty($seq['access_after_day_error']));
                                            $tlPErr = $seq['all_passed_items_error'] ?? ''; $tlDErr = $seq['access_after_day_error'] ?? '';
                                        } elseif ($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz && !empty($chapterItem->quiz) && $chapterItem->quiz->status == 'active') {
                                            $q = $chapterItem->quiz;
                                            $tlItem = $q; $tlType = 'quiz';
                                            $tlTitle = $q->title; $tlSub = trans('quiz.quizzes'); $tlId = $q->id;
                                            $tlPassed = false;
                                        }
                                    @endphp

                                    @if(!empty($tlItem))
                                        <div class="learning-timeline__item js-content-tab-item {{ $tlLocked ? 'js-sequence-content-error-modal' : '' }}"
                                             data-type="{{ $tlType }}"
                                             data-id="{{ $tlId }}"
                                             data-passed-error="{{ $tlPErr }}"
                                             data-access-days-error="{{ $tlDErr }}">
                                            <div class="learning-timeline__track">
                                                <div class="learning-timeline__circle {{ $tlPassed ? 'is-passed' : '' }}">
                                                    @if($tlPassed)
                                                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none"><path d="M2 6L5 9L10 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    @endif
                                                </div>
                                                @if(!$isLast)
                                                    <div class="learning-timeline__line"></div>
                                                @endif
                                            </div>
                                            <div class="learning-timeline__body cursor-pointer">
                                                <div class="learning-timeline__title">{{ $tlTitle }}</div>
                                                <div class="learning-timeline__sub">
                                                    @if($tlLocked)
                                                        <div class="tl-sub-row tl-sub-row--locked">
                                                            <x-iconsax-bol-lock-circle class="icons text-danger flex-shrink-0" width="12px" height="12px"/>
                                                            <span>{{ $tlSub }}</span>
                                                        </div>
                                                    @else
                                                        <div class="tl-sub-row">{{ $tlSub }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>{{-- /.learning-timeline-card --}}
                @endforeach
            @endif

            {{-- Items without a chapter --}}
            @if($hasItemsWithoutChapter)
                <div class="learning-section-info-card mb-10">
                    <h4 class="learning-section-title">{{ mb_strtoupper($course->title) }}</h4>
                    <div class="learning-section-progress-track mt-8">
                        <div class="js-course-learning-progress-bar-percent learning-section-progress-fill" style="width: {{ $overallPercent }}%"></div>
                    </div>
                </div>
                <div class="learning-timeline-card mb-16">
                    <div class="learning-timeline">
                        @php
                            $allFlat = collect();
                            if (!empty($sessionsWithoutChapter))   $allFlat = $allFlat->merge($sessionsWithoutChapter->map(fn($s) => ['obj'=>$s,'kind'=>'session']));
                            if (!empty($textLessonsWithoutChapter)) $allFlat = $allFlat->merge($textLessonsWithoutChapter->map(fn($t) => ['obj'=>$t,'kind'=>'text_lesson']));
                            if (!empty($filesWithoutChapter))       $allFlat = $allFlat->merge($filesWithoutChapter->map(fn($f) => ['obj'=>$f,'kind'=>'file']));
                            $flatTotal = $allFlat->count();
                        @endphp

                        @foreach($allFlat as $fi => $flatRow)
                            @php
                                $obj    = $flatRow['obj'];
                                $kind   = $flatRow['kind'];
                                $isLast = ($fi === $flatTotal - 1);

                                if ($kind === 'session') {
                                    $fType = \App\Models\WebinarChapter::$chapterSession;
                                    $fSub  = trans('update.live');
                                } elseif ($kind === 'text_lesson') {
                                    $fType = \App\Models\WebinarChapter::$chapterTextLesson;
                                    $fSub  = trans('webinars.text_lesson');
                                } else {
                                    $fType = \App\Models\WebinarChapter::$chapterFile;
                                    $fSub  = trans('update.file_type_' . $obj->file_type);
                                }

                                $fPassed = !empty($obj->checkPassedItem());
                                $seqF    = $obj->checkSequenceContent();
                                $fLocked = !empty($seqF) && (!empty($seqF['all_passed_items_error']) || !empty($seqF['access_after_day_error']));
                                $fPErr   = $seqF['all_passed_items_error'] ?? '';
                                $fDErr   = $seqF['access_after_day_error'] ?? '';
                            @endphp

                            <div class="learning-timeline__item js-content-tab-item {{ $fLocked ? 'js-sequence-content-error-modal' : '' }}"
                                 data-type="{{ $fType }}"
                                 data-id="{{ $obj->id }}"
                                 data-passed-error="{{ $fPErr }}"
                                 data-access-days-error="{{ $fDErr }}">
                                <div class="learning-timeline__track">
                                    <div class="learning-timeline__circle {{ $fPassed ? 'is-passed' : '' }}">
                                        @if($fPassed)
                                            <svg width="10" height="10" viewBox="0 0 12 12" fill="none"><path d="M2 6L5 9L10 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        @endif
                                    </div>
                                    @if(!$isLast)
                                        <div class="learning-timeline__line"></div>
                                    @endif
                                </div>
                                <div class="learning-timeline__body cursor-pointer">
                                    <div class="learning-timeline__title">{{ $obj->title }}</div>
                                    <div class="learning-timeline__sub">
                                        @if($fLocked)
                                            <div class="tl-sub-row tl-sub-row--locked">
                                                <x-iconsax-bol-lock-circle class="icons text-danger flex-shrink-0" width="12px" height="12px"/>
                                                <span>{{ $fSub }}</span>
                                            </div>
                                        @else
                                            <div class="tl-sub-row">{{ $fSub }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>{{-- /.learning-timeline-card --}}
            @endif

            {{-- Empty state --}}
            @if(!$hasChapters and !$hasItemsWithoutChapter)
                <div class="text-center py-40">
                    <img src="/assets/design_1/img/courses/learning_page/empty_state.svg" alt="" width="160px" class="img-fluid mb-12">
                    <h4 class="font-14 text-dark">{{ trans('update.learning_page_empty_content_title') }}</h4>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.learning_page_empty_content_hint') }}</p>
                </div>
            @endif

            {{-- Your Notes --}}
            <div class="learning-notes-panel" id="sidebarNotePanel">
                <h4 class="learning-notes-panel__title">{{ trans('update.your_notes') }}</h4>

                <div class="js-sidebar-note-placeholder text-center py-20">
                    <x-iconsax-lin-note-1 class="icons text-gray-300 mb-8" width="28px" height="28px"/>
                    <p class="font-12 text-gray-400">{{ trans('update.no_course_notes') }}</p>
                </div>

                <div class="js-sidebar-note-editor d-none">
                    <input type="hidden" id="sidebarNoteItemId" value="">
                    <input type="hidden" id="sidebarNoteItemType" value="">

                    <input type="text"
                           id="sidebarNoteTitleInput"
                           class="notes-title-input mb-12"
                           placeholder="{{ trans('update.note_title') ?? 'Note Title' }}">

                    <textarea id="sidebarNoteEditor" class="w-100"></textarea>

                    <div class="d-flex justify-content-center mt-16">
                        <button type="button" class="js-sidebar-save-note notes-save-btn">
                            Save
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
