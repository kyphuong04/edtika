<div class="d-flex align-items-center justify-content-between bg-gray-100 p-12 rounded-12">
    <div class="">
        <h2 class="font-24">{{ $item->title }}</h2>

        <div class="d-flex flex-wrap align-items-center gap-16 gap-lg-24 mt-12">

            @if($itemType == "file")
                <div class="d-flex align-items-center">
                    @php
                        $itemIcon = !empty($item) ? $item->getIconXByType() : 'document';
                    @endphp

                    @svg("iconsax-lin-{$itemIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])

                    <span class="ml-4 text-gray-500">{{ trans('update.file_type_' . $item->file_type) }}</span>
                </div>
            @endif

            @if($itemType == "session")
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-video class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 text-gray-500">{{ trans('update.live_session') }}</span>
                </div>
            @endif

            @if($itemType == "quiz")
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 text-gray-500">{{ trans('quiz.quiz') }}</span>
                </div>
            @endif

            @if($itemType == "quiz")
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 text-gray-500">{{ trans('quiz.quiz') }}</span>
                </div>
            @endif

            @if($itemType == "text_lesson")
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-note-1 class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 text-gray-500">{{ trans('webinars.text_lesson') }}</span>
                </div>
            @endif

            @php
                $itemDuration = null;

                if (!empty($item->duration)) {
                    $itemDuration = $item->duration;
                }

                if (!empty($item->study_time)) {
                    $itemDuration = $item->study_time;
                }
            @endphp
            @if(!empty($itemDuration))
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 text-gray-500">{{ convertMinutesToHourAndMinute($itemDuration) }} {{ trans('public.minutes') }}</span>
                </div>
            @endif

        </div>
    </div>

    <div class="d-flex align-items-center gap-16">
        @if($itemType == "file" and $item->downloadable)
            <a href="{{ $course->getUrl() }}/file/{{ $item->id }}/download" class="d-flex-center size-48 rounded-circle bg-white" data-tippy-content="{{ trans('home.download') }}">
                <x-iconsax-lin-import-2 class="icons text-gray-500" width="24px" height="24px"/>
            </a>
        @endif

        @if(!empty(getFeaturesSettings('course_notes_status')))
            <div class="position-relative d-flex-center size-48 rounded-circle bg-white cursor-pointer {{ $itemHasPersonalNote ? 'js-edit-personal-note' : 'js-add-personal-note' }}"
                 data-item-id="{{ $item->id }}"
                 data-item-type="{{ $item->getMorphClass() }}"
                 data-tippy-content="{{ trans('update.personal_note') }}"
            >
                <x-iconsax-lin-document-text class="icons text-gray-500" width="24px" height="24px"/>

                @if($itemHasPersonalNote)
                    <div class="has-personal-note-beep"></div>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="learning-page__content-tabs mt-16">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="lp-quiz-tab-{{ $item->id }}" data-toggle="tab" href="#lp-quiz-{{ $item->id }}" role="tab" aria-controls="lp-quiz-{{ $item->id }}" aria-selected="true">Interactive Quizz</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lp-desc-tab-{{ $item->id }}" data-toggle="tab" href="#lp-desc-{{ $item->id }}" role="tab" aria-controls="lp-desc-{{ $item->id }}" aria-selected="false">Description</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lp-notes-tab-{{ $item->id }}" data-toggle="tab" href="#lp-notes-{{ $item->id }}" role="tab" aria-controls="lp-notes-{{ $item->id }}" aria-selected="false">Lecture Notes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lp-dict-tab-{{ $item->id }}" data-toggle="tab" href="#lp-dict-{{ $item->id }}" role="tab" aria-controls="lp-dict-{{ $item->id }}" aria-selected="false">Dictionary</a>
        </li>
    </ul>

    <div class="tab-content mt-12">
        <div class="tab-pane fade show active" id="lp-quiz-{{ $item->id }}" role="tabpanel" aria-labelledby="lp-quiz-tab-{{ $item->id }}">
            <div class="learning-page__empty-tab">
                Chưa có nội dung Interactive Quizz cho bài học này.
            </div>
        </div>

        <div class="tab-pane fade" id="lp-desc-{{ $item->id }}" role="tabpanel" aria-labelledby="lp-desc-tab-{{ $item->id }}">
            @if(!empty($itemType) and $itemType == 'text_lesson')
                @if(!empty($item->summary))
                    <div class="mt-12 text-gray-500">{!! nl2br($item->summary) !!}</div>
                @endif

                @if(!empty($item->content))
                    <div class="mt-12 text-gray-500">{!! nl2br($item->content) !!}</div>
                @endif
            @else
                @if(!empty($item->description))
                    <div class="mt-12 text-gray-500">{!! nl2br($item->description) !!}</div>
                @endif
            @endif

            @if(!empty($item->attachments) and count($item->attachments))
                <div class="bg-gray-100 p-12 rounded-16 mt-16">
                    <h4 class="font-14 text-dark">{{ trans('update.attachments') }}</h4>

                    <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-12 mt-12">
                        @foreach($item->attachments as $itemAttachment)
                            @if(!empty($itemAttachment->file))
                                <a href="{{ $courseUrl }}/file/{{ $itemAttachment->file->id }}/download" target="_blank" class="d-flex align-items-center p-16 rounded-16 bg-white text-dark">
                                    <div class="d-flex-center size-56 bg-gray-100 rounded-circle">
                                        <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                                            <x-iconsax-bul-document-download class="icons text-primary" width="24px" height="24px"/>
                                        </div>
                                    </div>
                                    <div class="ml-8">
                                        <h5 class="font-14 text-dark">{{ $itemAttachment->file->title }}</h5>
                                        <div class="d-flex align-items-center gap-4 font-12 text-gray-500 mt-4">
                                            <span class="">{{ trans("update.file_type_{$itemAttachment->file->file_type}") }}</span>

                                            @if(!empty($itemAttachment->file->volume))
                                                <span class="">| {{ $itemAttachment->file->getVolume() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="tab-pane fade" id="lp-notes-{{ $item->id }}" role="tabpanel" aria-labelledby="lp-notes-tab-{{ $item->id }}">
            <div class="learning-page__empty-tab">
                Chưa có Lecture Notes cho bài học này.
            </div>
        </div>

        <div class="tab-pane fade" id="lp-dict-{{ $item->id }}" role="tabpanel" aria-labelledby="lp-dict-tab-{{ $item->id }}">
            <div class="learning-page__dictionary">
                <div class="learning-page__dictionary-search bg-gray-100 rounded-16 p-16">
                    <h4 class="font-16 text-dark">Search English</h4>

                    <div class="d-flex flex-column flex-md-row gap-12 mt-12">
                        <input type="text" class="form-control" placeholder="Oxford">
                        <button type="button" class="btn btn-dark">Search</button>
                    </div>

                    <div class="learning-page__dictionary-results bg-white rounded-16 p-16 mt-16">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-16">
                                    <h3 class="font-24">Oxford</h3>
                                    <div class="text-gray-500 font-12">
                                        UK /ˈɒks.fəd/ <br>
                                        US /ˈɑːks.fɚd/
                                    </div>
                                </div>
                                <div class="text-gray-500 font-12 mt-4">noun</div>
                            </div>
                        </div>

                        <div class="mt-12 border-top pt-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-gray-700">a city in south east England, famous for its university</div>
                                <button type="button" class="btn btn-sm btn-dark">Save</button>
                            </div>
                        </div>

                        <div class="mt-12 border-top pt-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-gray-700">(also Oxford shoe) a type of fairly formal man's shoe, usually made of leather, that fastens with laces</div>
                                <button type="button" class="btn btn-sm btn-dark">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 pt-16 border-top-gray-100">
    @if(!in_array($itemType, ['quiz']))
        <div class="d-flex align-items-center form-group mb-0">
            <div class="custom-switch mr-8">
                <input type="checkbox"
                       name="passed_section_toggle[]"
                       id="fileReadToggle{{ $item->id }}"
                       data-item-name="{{ $itemType }}_id"
                       data-course-slug="{{ $courseSlug }}"
                       value="{{ $item->id }}"
                       class="js-passed-item-toggle custom-control-input"
                    {{ (!empty($item->checkPassedItem())) ? 'checked' : '' }}
                >
                <label class="custom-control-label cursor-pointer" for="fileReadToggle{{ $item->id }}"></label>
            </div>

            <div class="">
                <label class="cursor-pointer text-gray-500" for="fileReadToggle{{ $item->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
            </div>
        </div>
    @else
        <div class=""></div>
    @endif

    {{--@if(!empty($item->chapter))
        @php
            $previousItemUrl = $item->chapter->getPreviousItem($itemType, $item->id)
        @endphp

        <div class="d-flex align-items-center justify-content-between gap-16 mt-16 mt-lg-0">
            <a href="{{ !empty($previousItemUrl) ? $previousItemUrl : '#!' }}" class="d-flex-center gap-8 bg-white border-gray-200 rounded-24 px-12 py-8 bg-hover-gray-100">
                <x-iconsax-lin-arrow-left class="icons text-gray-500" width="24px" height="24px"/>
                <span class="font-12 text-gray-500">{{ trans('update.previous_lesson') }}</span>
            </a>

            <a href="" class="d-flex-center gap-8 bg-white border-gray-200 rounded-24 px-12 py-8 bg-hover-gray-100">
                <span class="font-12 text-gray-500">{{ trans('update.next_lesson') }}</span>
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="24px" height="24px"/>
            </a>
        </div>
    @endif--}}
</div>
