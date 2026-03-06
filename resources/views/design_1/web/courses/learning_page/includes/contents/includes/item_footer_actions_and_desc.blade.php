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

        {{-- Personal Note icon (hidden) --}}
        {{-- @if(!empty(getFeaturesSettings('course_notes_status')))
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
        @endif --}}
    </div>
</div>

<div class="learning-page__content-tabs mt-16">
    <ul class="nav nav-tabs" role="tablist" style="display:flex!important;justify-content:space-around!important;width:100%!important;">
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

            {{-- ── Inline styles (output once per page via @once) ── --}}
            @once
            <style>
                .lp-dict-wrap { font-family: inherit; }
                /* Search card */
                .lp-dict-search-card {
                    background: #fff;
                    border-radius: 12px;
                    padding: 24px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.08);
                    margin-bottom: 20px;
                }
                .lp-dict-search-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 14px;
                }
                .lp-dict-search-row {
                    display: flex;
                    gap: 10px;
                }
                .lp-dict-search-input {
                    flex: 1;
                    padding: 11px 18px;
                    border: 2px solid #e2e8f0;
                    border-radius: 8px;
                    font-size: 15px;
                    outline: none;
                    transition: border-color .2s;
                }
                .lp-dict-search-input:focus { border-color: #3b82f6; }
                .lp-dict-search-btn {
                    padding: 11px 28px;
                    background: #3b82f6;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: background .2s;
                    white-space: nowrap;
                }
                .lp-dict-search-btn:hover { background: #2563eb; }
                .lp-dict-search-btn:disabled { background: #94a3b8; cursor: not-allowed; }

                /* Result card */
                .lp-dict-result-card {
                    background: #fff;
                    border-radius: 12px;
                    padding: 28px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.08);
                }
                .lp-dict-result-card.d-none { display: none !important; }
                .lp-dict-result-word {
                    font-size: 30px;
                    font-weight: 700;
                    font-style: italic;
                    color: #1e293b;
                    margin: 0 0 16px;
                }
                /* Pronunciation row */
                .lp-dict-pron-row {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    margin-bottom: 20px;
                    padding-bottom: 16px;
                    border-bottom: 2px solid #e5e7eb;
                }
                .lp-dict-pron-item {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .lp-dict-pron-label {
                    font-size: 12px;
                    font-weight: 700;
                    color: #fff;
                    background: #3b82f6;
                    border-radius: 4px;
                    padding: 1px 6px;
                }
                .lp-dict-pron-text {
                    font-size: 15px;
                    color: #374151;
                }
                .lp-dict-audio-btn {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    background: #3b82f6;
                    border: none;
                    color: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: background .2s, transform .15s;
                    padding: 0;
                }
                .lp-dict-audio-btn:hover:not(:disabled) { background: #2563eb; transform: scale(1.1); }
                .lp-dict-audio-btn:disabled { background: #d1d5db; cursor: not-allowed; opacity: .6; }
                /* POS sections */
                .lp-dict-pos-section {
                    margin-bottom: 24px;
                    padding-bottom: 16px;
                    border-bottom: 1px solid #e5e7eb;
                }
                .lp-dict-pos-section:last-child { border-bottom: none; margin-bottom: 0; }
                .lp-dict-pos-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 12px;
                }
                .lp-dict-pos-title {
                    font-size: 17px;
                    font-weight: 600;
                    font-style: italic;
                    color: #374151;
                }
                .lp-dict-save-btn {
                    padding: 6px 20px;
                    background: #6b7280;
                    color: #fff;
                    border: none;
                    border-radius: 20px;
                    font-size: 13px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background .2s;
                }
                .lp-dict-save-btn:hover { background: #4b5563; }
                .lp-dict-save-btn.saved { background: #10b981; }
                .lp-dict-save-btn:disabled { opacity: .6; cursor: not-allowed; }
                .lp-dict-def-item { padding-left: 16px; margin-bottom: 10px; }
                .lp-dict-def-text { color: #374151; font-size: 14px; line-height: 1.6; }
                .lp-dict-def-example {
                    color: #6b7280;
                    font-size: 13px;
                    font-style: italic;
                    padding-left: 12px;
                    border-left: 3px solid #e2e8f0;
                    margin-top: 4px;
                }
                /* Back button — same look as panel dictionary: auto-width, centered */
                .lp-dict-back-wrap {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                }
                .lp-dict-back-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 10px 30px;
                    background: #fff;
                    border: 2px solid #d1d5db;
                    border-radius: 10px;
                    color: #374151;
                    font-size: 15px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background .2s, border-color .2s;
                    white-space: nowrap;
                }
                .lp-dict-back-btn:hover { background: #f9fafb; border-color: #9ca3af; }
            </style>
            @endonce

            <div class="lp-dict-wrap">
                {{-- Search card --}}
                <div class="lp-dict-search-card">
                    <h3 class="lp-dict-search-title">Search English</h3>
                    <div class="lp-dict-search-row">
                        <input type="text"
                               class="lp-dict-search-input"
                               id="lpDictInput{{ $item->id }}"
                               placeholder="Search the word...">
                        <button class="lp-dict-search-btn" id="lpDictBtn{{ $item->id }}">Search</button>
                    </div>
                </div>

                {{-- Result card (hidden initially) --}}
                <div class="lp-dict-result-card d-none" id="lpDictResult{{ $item->id }}">
                    <h2 class="lp-dict-result-word" id="lpDictWord{{ $item->id }}"></h2>

                    <div class="lp-dict-pron-row">
                        <div class="lp-dict-pron-item">
                            <span class="lp-dict-pron-label">UK</span>
                            <span class="lp-dict-pron-text" id="lpDictUK{{ $item->id }}">/--/</span>
                            <button class="lp-dict-audio-btn" id="lpDictAudioUK{{ $item->id }}" disabled title="Play UK pronunciation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M11 5L6 9H2v6h4l5 4V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                        <div class="lp-dict-pron-item">
                            <span class="lp-dict-pron-label">US</span>
                            <span class="lp-dict-pron-text" id="lpDictUS{{ $item->id }}">/--/</span>
                            <button class="lp-dict-audio-btn" id="lpDictAudioUS{{ $item->id }}" disabled title="Play US pronunciation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M11 5L6 9H2v6h4l5 4V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>

                    <div id="lpDictDefs{{ $item->id }}"></div>

                    <div class="lp-dict-back-wrap">
                        <button class="lp-dict-back-btn" id="lpDictBack{{ $item->id }}">
                            ← Back
                        </button>
                    </div>
                </div>
            </div>

            <script>
            (function ($) {
                var itemId   = '{{ $item->id }}';
                var audioUK  = null;
                var audioUS  = null;

                var $input   = $('#lpDictInput'   + itemId);
                var $btn     = $('#lpDictBtn'      + itemId);
                var $result  = $('#lpDictResult'   + itemId);
                var $word    = $('#lpDictWord'     + itemId);
                var $ukText  = $('#lpDictUK'       + itemId);
                var $usText  = $('#lpDictUS'       + itemId);
                var $ukAudio = $('#lpDictAudioUK'  + itemId);
                var $usAudio = $('#lpDictAudioUS'  + itemId);
                var $defs    = $('#lpDictDefs'     + itemId);
                var $back    = $('#lpDictBack'     + itemId);

                /* ── Search click / Enter ── */
                $btn.on('click', function () {
                    var term = $input.val().trim();
                    if (term) searchDictionary(term);
                });

                $input.on('keypress', function (e) {
                    if (e.which === 13) $btn.trigger('click');
                });

                /* ── AJAX search (same endpoint as panel dictionary) ── */
                function searchDictionary(word) {
                    $.ajax({
                        url: '/panel/dictionary/search-first',
                        method: 'POST',
                        data: {
                            word: word,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $btn.prop('disabled', true).text('Searching...');
                        },
                        success: function (response) {
                            if (response.success && response.data) {
                                displayResult(response.data);
                            } else {
                                alert('Word not found. Please try another word.');
                            }
                        },
                        error: function () {
                            alert('Search failed. Please try again.');
                        },
                        complete: function () {
                            $btn.prop('disabled', false).text('Search');
                        }
                    });
                }

                /* ── Display result
                 * Controller transformDictionaryData returns:
                 *   data.headword          — word string
                 *   data.pronunciations[]  — [{label:'UK'|'US', ipa:'...', audio:'...'}]
                 *   data.meanings[]        — [{partOfSpeech, definitions:[{definition, example}]}]
                 * Mirrors the panel's displayDictionaryResult exactly.
                 */
                function displayResult(data) {
                    var wordText = data.headword || data.word || '';
                    $word.text(wordText);

                    /* ── Pronunciations ── */
                    var ukIpa = '/--/', usIpa = '/--/';
                    var ukUrl = '',    usUrl = '';

                    if (data.pronunciations && data.pronunciations.length) {
                        data.pronunciations.forEach(function (p) {
                            var lbl = (p.label || '').toUpperCase();
                            if (lbl === 'UK') {
                                ukIpa = p.ipa || '/--/';
                                ukUrl = p.audio || '';
                            } else if (lbl === 'US') {
                                usIpa = p.ipa || '/--/';
                                usUrl = p.audio || '';
                            } else if (ukIpa === '/--/') {
                                ukIpa = p.ipa || '/--/';
                                ukUrl = p.audio || '';
                            }
                        });
                    }

                    $ukText.text(ukIpa);
                    $usText.text(usIpa);

                    if (audioUK) { audioUK.pause(); audioUK = null; }
                    if (audioUS) { audioUS.pause(); audioUS = null; }
                    audioUK = ukUrl ? new Audio(ukUrl) : null;
                    audioUS = usUrl ? new Audio(usUrl) : null;
                    $ukAudio.prop('disabled', !audioUK);
                    $usAudio.prop('disabled', !audioUS);

                    /* ── Definitions — grouped by part of speech (same as panel) ── */
                    var html = '';
                    if (data.meanings && data.meanings.length) {
                        data.meanings.forEach(function (meaning) {
                            var pos = meaning.partOfSpeech || '';
                            html += '<div class="lp-dict-pos-section">';
                            html += '<div class="lp-dict-pos-header">';
                            html += '<span class="lp-dict-pos-title">' + pos + '</span>';
                            html += '<button class="lp-dict-save-btn" data-pos="' + pos + '" data-word="' + wordText + '">Save</button>';
                            html += '</div>';
                            if (meaning.definitions && meaning.definitions.length) {
                                meaning.definitions.forEach(function (def, i) {
                                    html += '<div class="lp-dict-def-item">';
                                    html += '<div class="lp-dict-def-text">' + (i + 1) + '. ' + def.definition + '</div>';
                                    if (def.example) {
                                        html += '<div class="lp-dict-def-example">• ' + def.example + '</div>';
                                    }
                                    html += '</div>';
                                });
                            }
                            html += '</div>';
                        });
                    } else {
                        html = '<p style="color:#6b7280;">No definitions found.</p>';
                    }
                    $defs.html(html);

                    /* ── Bind Save buttons — same endpoint as panel's index_new.blade.php ── */
                    $defs.find('.lp-dict-save-btn').on('click', function (e) {
                        e.stopPropagation();
                        var $saveBtn = $(this);
                        var pos      = $saveBtn.data('pos');
                        var w        = $saveBtn.data('word');
                        var meaning  = (data.meanings || []).find(function (m) { return m.partOfSpeech === pos; });
                        var definition = '', example = '', pronunciation = '';
                        if (meaning && meaning.definitions && meaning.definitions.length) {
                            definition = meaning.definitions[0].definition || '';
                            example    = meaning.definitions[0].example    || '';
                        }
                        /* Get IPA pronunciation for the flashcard record */
                        if (data.pronunciations && data.pronunciations.length) {
                            pronunciation = data.pronunciations[0].ipa || data.pronunciations[0].text || '';
                        }
                        $.ajax({
                            url: '/panel/dictionary/my-word-list/add-word',
                            method: 'POST',
                            data: {
                                word: w,
                                part_of_speech: pos,
                                definition: definition,
                                example: example,
                                pronunciation: pronunciation,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () { $saveBtn.prop('disabled', true).text('Adding...'); },
                            success: function (r) {
                                if (r.success) {
                                    $saveBtn.addClass('saved').text('Added ✓').css({'background': '#22c55e', 'color': '#fff'});
                                } else {
                                    /* Already in list */
                                    if (r.message && r.message.toLowerCase().indexOf('already') !== -1) {
                                        $saveBtn.text('Already added').css({'background': '#94a3b8', 'color': '#fff'}).prop('disabled', true);
                                    } else {
                                        $saveBtn.prop('disabled', false).text('Save').removeAttr('style');
                                    }
                                }
                            },
                            error: function (xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '';
                                if (msg.toLowerCase().indexOf('already') !== -1) {
                                    $saveBtn.text('Already added').css({'background': '#94a3b8', 'color': '#fff'}).prop('disabled', true);
                                } else {
                                    $saveBtn.prop('disabled', false).text('Save').removeAttr('style');
                                }
                            }
                        });
                    });

                    $result.removeClass('d-none');
                }

                /* ── Back button ── */
                $back.on('click', function () {
                    $result.addClass('d-none');
                    if (audioUK) { audioUK.pause(); audioUK = null; }
                    if (audioUS) { audioUS.pause(); audioUS = null; }
                });

                /* ── Audio buttons ── */
                $ukAudio.on('click', function () { if (audioUK) audioUK.play(); });
                $usAudio.on('click', function () { if (audioUS) audioUS.play(); });

            }(jQuery));
            </script>

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
