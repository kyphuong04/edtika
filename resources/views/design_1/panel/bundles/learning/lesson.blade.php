@extends('design_1.panel.layouts.panel')

{{--
    GĐ6 - Trang học một lesson theo màn #player trong bản demo của khách:
    khung nội dung (Pre Lesson / Video Learning / Bài Tập / Post Lesson) + tab Giới thiệu, Ghi chú, Quizzes
    + cột "Nội dung bài học" (Module → Lesson → Pre Lesson / Video Learning / Post Lesson, Mid-term / Final).
--}}

@push('styles_top')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-storefront.css?v=gd5-4">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-learning.css?v=1">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-lesson.css?v=2">
@endpush

@section('content')
    @php
        $icon = function ($name) {
            $paths = [
                'book' => '<path d="M12 7c-2-1.4-5-1.5-9-1v14c4-.4 7-.4 9 1 2-1.4 5-1.4 9-1V6c-4-.5-7-.4-9 1Z"></path><path d="M12 7v14"></path>',
                'play' => '<circle cx="12" cy="12" r="9"></circle><path d="m10 8 6 4-6 4Z"></path>',
                'target' => '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1"></circle>',
                'file' => '<path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v5h5M9 13h6M9 17h6"></path>',
                'dot' => '<circle cx="12" cy="12" r="4"></circle>',
                'lock' => '<rect width="14" height="11" x="5" y="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path>',
                'up' => '<path d="m6 15 6-6 6 6"></path>',
                'down' => '<path d="m6 9 6 6 6-6"></path>',
            ];
            return '<svg class="v9-svg" viewBox="0 0 24 24" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
        };
        $doneMark = '<span class="done-mark">✓</span>';
        $lockMark = '<span class="v10-lock-line">' . $icon('lock') . '</span>';

        $moduleNumber = $module['order_index'] + 1;
        $lessonNumber = $lesson['order_index'] + 1;
        $skillLabel = !empty($module['skill']) ? trans('bundle_learning.skill_' . $module['skill']) : null;
        $viewLabels = [
            'pre' => 'Pre Lesson',
            'video' => 'Video Learning',
            'exercise' => trans('bundle_learning.activity_exercise'),
            'post' => 'Post Lesson',
            'checkpoint' => !empty($currentCheckpoint) ? ($currentCheckpoint['title'] ?: trans('bundle_learning.checkpoint_' . $currentCheckpoint['type'])) : 'Test',
        ];

        $videoIndex = !empty($currentVideo) ? $videos->search(function ($video) use ($currentVideo) { return $video['id'] === $currentVideo['id']; }) : false;
        $subParts = ['Module ' . $moduleNumber, 'Lesson ' . $lessonNumber];
        if ($view === 'video' and $videoIndex !== false) {
            $subParts[] = 'Video ' . ($videoIndex + 1) . '/' . $videos->count();
            $videoSeconds = (int)($currentVideo['model']->duration_seconds ?? 0);
            if ($videoSeconds > 0) {
                $subParts[] = max(1, (int)round($videoSeconds / 60)) . ' ' . trans('bundle_learning.minutes_short');
            }
        }

        // GĐ7: Mid-term / Final đang mở trong khung player
        $totalModules = count($progress['modules']);
        if ($view === 'checkpoint' and !empty($currentCheckpoint)) {
            $subParts = [
                trans('bundle_learning.checkpoint_label_' . ($currentCheckpoint['type'] === 'final' ? 'final' : 'mid')),
                trans('bundle_learning.checkpoint_modules', ['required' => $currentCheckpoint['required_modules'], 'total' => $totalModules]),
            ];
        }
        $checkpointUrl = function ($quizId) use ($courseUrl) {
            return $courseUrl . '/checkpoints/' . $quizId;
        };

        $lessonPageUrl = function ($chapterId, $query = '') use ($courseUrl) {
            return $courseUrl . '/lessons/' . $chapterId . ($query !== '' ? '?' . $query : '');
        };
        $itemLearningUrl = function ($type, $id) use ($learningUrl) {
            return $learningUrl . '?type=' . $type . '&item=' . $id;
        };

        // Mid-term / Final hiện sau module thứ "required_modules"
        $checkpointsAfter = [];
        foreach ($progress['checkpoints'] as $checkpoint) {
            $after = max(1, min(count($progress['modules']), (int)$checkpoint['required_modules']));
            $checkpointsAfter[$after][] = $checkpoint;
        }
    @endphp

    <div class="edk edk-learning">
        <section id="player">
            <div class="crumb">
                <a href="/panel/courses/purchases">{{ trans('panel.my_courses') }}</a><span>›</span>
                <a href="{{ $courseUrl }}">{{ trans('bundle_learning.overview_crumb') }}</a><span>›</span>
                <b>{{ $viewLabels[$view] ?? 'Video Learning' }}</b>
            </div>

            <div class="player-layout">
                <div class="player-stack">
                    {{-- ================= Khung nội dung ================= --}}
                    <div class="shell player-shell">
                        <div class="player-head">
                            <div>
                                <span class="tag outline">{{ $bundle->title }}{{ $skillLabel ? ' · ' . $skillLabel : '' }}</span>
                                <h1>{{ $view === 'checkpoint' ? $viewLabels['checkpoint'] : $chapter->title }}</h1>
                                <span class="muted tiny">{{ implode(' · ', $subParts) }}</span>
                            </div>
                            <a class="action sm" href="{{ $courseUrl }}">Course Syllabus ↗</a>
                        </div>

                        <div id="player-window">
                            @if($view === 'pre' and !empty($pre))
                                {{-- ----- Pre Lesson: đánh dấu từ đã thuộc ----- --}}
                                <div class="player-switch js-pre-lesson"
                                     data-word-url="{{ $lessonUrl }}/pre-lesson/word"
                                     data-complete-url="{{ $lessonUrl }}/pre-lesson/complete"
                                     data-required="{{ $pre['required'] }}"
                                     data-total="{{ $preWords->count() }}">
                                    <span class="tag">Pre Lesson · My Word List</span>
                                    <h3>{{ trans('bundle_learning.pre_lesson_title') }}</h3>
                                    <p class="ed19-inline-note">{{ trans('bundle_learning.pre_lesson_intro', ['required' => $pre['required'], 'total' => $preWords->count()]) }}</p>

                                    @php $knownCount = count(array_intersect($preWords->pluck('id')->all(), $knownWordIds)); @endphp
                                    <div class="flex between">
                                        <b style="color:#5A2B81">{{ trans('bundle_learning.pre_lesson_known') }} <span class="js-pre-known">{{ $knownCount }}</span>/{{ $preWords->count() }} {{ trans('bundle_learning.words') }}</b>
                                        <span class="tag js-pre-target {{ $knownCount >= $pre['required'] ? 'success' : '' }}">{{ trans('bundle_learning.pre_lesson_target', ['count' => $pre['required']]) }}</span>
                                    </div>
                                    <div class="ed19-progress"><span class="js-pre-bar" style="width:{{ $preWords->count() > 0 ? round($knownCount / $preWords->count() * 100) : 0 }}%"></span></div>

                                    <div class="ed19-vocab-grid">
                                        @foreach($preWords as $word)
                                            @php $isKnown = in_array((int)$word->id, $knownWordIds, true); @endphp
                                            <button type="button" class="js-pre-word {{ $isKnown ? 'done' : '' }}" data-word-id="{{ $word->id }}" aria-pressed="{{ $isKnown ? 'true' : 'false' }}">
                                                <span><span class="js-pre-check">{{ $isKnown ? '✓ ' : '' }}</span>{{ $word->word }}</span>
                                                @if(!empty($word->translation_vi) or !empty($word->definition))
                                                    <small>{{ \Illuminate\Support\Str::limit($word->translation_vi ?: $word->definition, 60) }}</small>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>

                                    <p class="ed19-inline-note">{{ trans('bundle_learning.pre_lesson_sync_note') }}</p>

                                    <div class="flex" style="flex-wrap:wrap">
                                        @if($pre['done'])
                                            <a href="{{ $lessonPageUrl($chapter->id, 'view=video') }}" class="action sm primary">{{ trans('bundle_learning.pre_lesson_go_video') }} →</a>
                                        @else
                                            <button type="button" class="action sm primary js-pre-complete" {{ $knownCount < $pre['required'] ? 'disabled' : '' }}>{{ trans('bundle_learning.pre_lesson_confirm') }} →</button>
                                        @endif
                                        <a href="/panel/dictionary" class="action sm">{{ trans('bundle_learning.pre_lesson_open_flashcard') }} ↗</a>
                                    </div>
                                </div>

                            @elseif($view === 'video' and !empty($currentVideo))
                                {{-- ----- Video Learning ----- --}}
                                @php $file = $currentVideo['model']; @endphp
                                <div class="edk-video-frame">
                                    @if($file->storage == 'youtube')
                                        <div class="js-lesson-plyr plyr__video-embed" id="fileVideo{{ $file->id }}">
                                            <iframe src="{{ $file->file }}?origin={{ url('/') }}&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                                        </div>
                                    @elseif($file->storage == 'vimeo')
                                        <div class="js-lesson-plyr plyr__video-embed" id="fileVideo{{ $file->id }}">
                                            <iframe src="{{ $file->getVimeoPath() }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                                        </div>
                                    @elseif(in_array($file->storage, ['upload', 'external_link', 's3']))
                                        <video id="fileVideo{{ $file->id }}" class="js-lesson-plyr" controls preload="metadata" playsinline>
                                            <source src="{{ $file->file }}" type="video/mp4"/>
                                        </video>
                                    @else
                                        {{-- Nguồn video đặc biệt (secure host, Google Drive, iframe): mở bằng trình học của module --}}
                                        <div class="edk-video-empty">
                                            <span class="big-play" aria-hidden="true">▶</span>
                                            <p>{{ trans('bundle_learning.video_open_in_player') }}</p>
                                            <a href="{{ $itemLearningUrl('file', $file->id) }}" class="action sm primary">{{ trans('bundle_learning.open') }} →</a>
                                        </div>
                                    @endif
                                </div>

                                @if($currentVideo['done'])
                                    <div class="edk-video-done">✓ {{ trans('bundle_learning.video_completed') }}</div>
                                @endif

                                <div class="flex" style="flex-wrap:wrap;margin-top:12px">
                                    @php $nextVideo = $videoIndex !== false ? $videos->get($videoIndex + 1) : null; @endphp
                                    @if(!empty($nextVideo))
                                        <a href="{{ $lessonPageUrl($chapter->id, 'view=video&item=' . $nextVideo['id']) }}" class="action sm">Video {{ str_pad($videoIndex + 2, 2, '0', STR_PAD_LEFT) }} →</a>
                                    @elseif(!empty($lesson['post']))
                                        <a href="{{ $lessonPageUrl($chapter->id, 'view=post') }}" class="action sm primary">Post Lesson →</a>
                                    @endif
                                </div>

                            @elseif(in_array($view, ['exercise', 'post']) and !empty($currentTest))
                                {{-- ----- Bài Tập / Post Lesson: đề "Bài tập khóa học" chấm tự động ----- --}}
                                @php
                                    $roleTests = collect($lesson[$view]);
                                    $testIndex = $roleTests->search(function ($test) use ($currentTest) { return $test['link_id'] === $currentTest['link_id']; });
                                    $nextTest = $roleTests->get($testIndex + 1);
                                @endphp
                                <div class="player-switch">
                                    <span class="tag">{{ $viewLabels[$view] }} · Lesson {{ $lessonNumber }}</span>
                                    <h3>{{ $view === 'post' ? trans('bundle_learning.post_item', ['number' => str_pad($testIndex + 1, 2, '0', STR_PAD_LEFT)]) . ' · ' : '' }}{{ $currentTest['title'] }}</h3>

                                    @if($view === 'post' and $roleTests->count() > 1)
                                        <div class="assignment-progress">
                                            @foreach($roleTests as $test)
                                                <span class="{{ $test['done'] ? 'done' : '' }}"></span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <p class="muted">{{ $view === 'exercise' ? trans('bundle_learning.exercise_intro') : trans('bundle_learning.post_intro') }}</p>

                                    <div class="edk-test-meta">
                                        <span class="tag outline">Listening / Reading</span>
                                        @if($currentTest['duration'] > 0)
                                            <span class="tag outline">{{ $currentTest['duration'] }} {{ trans('bundle_learning.minutes_short') }}</span>
                                        @endif
                                        <span class="tag outline">{{ trans('bundle_learning.auto_graded') }}</span>
                                    </div>

                                    @if($currentTest['done'])
                                        <div class="edk-test-result">
                                            ✓ {{ trans('bundle_learning.test_done') }}
                                            @if(!is_null($currentTest['band']))
                                                · Band <b>{{ rtrim(rtrim(number_format($currentTest['band'], 1), '0'), '.') }}</b>
                                            @endif
                                            @if(!is_null($currentTest['correct']) and !empty($currentTest['total_questions']))
                                                · {{ trans('bundle_learning.test_correct', ['correct' => $currentTest['correct'], 'total' => $currentTest['total_questions']]) }}
                                            @endif
                                        </div>
                                    @endif

                                    <div class="edk-test-actions">
                                        @if($contentUnlocked)
                                            <form action="/panel/ielts-tests/{{ $currentTest['test_id'] }}/start" method="post">
                                                {{ csrf_field() }}
                                                <button type="submit" class="action sm primary">{{ $currentTest['done'] ? trans('bundle_learning.test_retake') : trans('bundle_learning.test_start') }} →</button>
                                            </form>
                                        @else
                                            <span class="tag">{{ trans('bundle_learning.pre_lesson_locked_short') }}</span>
                                        @endif

                                        @if($currentTest['done'] and !empty($currentTest['attempt_id']))
                                            <a href="/panel/ielts-tests/attempt/{{ $currentTest['attempt_id'] }}/results" class="action sm">{{ trans('bundle_learning.test_view_result') }}</a>
                                        @endif

                                        @if(!empty($nextTest))
                                            <a href="{{ $lessonPageUrl($chapter->id, 'view=' . $view . '&test=' . $nextTest['link_id']) }}" class="action sm">{{ trans('bundle_learning.test_next') }} →</a>
                                        @endif

                                        @if($videos->isNotEmpty())
                                            <a href="{{ $lessonPageUrl($chapter->id, 'view=video') }}" class="action sm">{{ trans('bundle_learning.back_to_video') }}</a>
                                        @endif
                                    </div>
                                </div>

                            @elseif($view === 'checkpoint' and !empty($checkpointInfo))
                                {{-- ----- GĐ7: Mid-term / Final làm ngay trong khung player ----- --}}
                                @php
                                    $cpQuiz = $checkpointInfo['quiz'];
                                    $cpLast = $checkpointInfo['last_result'];
                                    $cpName = $viewLabels['checkpoint'];
                                @endphp
                                <div class="player-switch js-checkpoint" data-url="{{ $checkpointInfo['inline_url'] }}">
                                    <span class="tag">{{ trans('bundle_learning.checkpoint_label_' . ($currentCheckpoint['type'] === 'final' ? 'final' : 'mid')) }}</span>
                                    <h3>{{ $cpName }}</h3>
                                    <p class="muted tiny">{{ trans('bundle_learning.checkpoint_intro', ['required' => $currentCheckpoint['required_modules'], 'total' => $totalModules]) }}</p>

                                    <div class="js-checkpoint-intro">
                                        <div class="edk-test-meta">
                                            <span class="tag outline">{{ trans('bundle_learning.inline_quiz_questions', ['count' => $checkpointInfo['questions_count']]) }}</span>
                                            <span class="tag outline">{{ trans('bundle_learning.inline_quiz_pass_mark', ['pass' => (int)$cpQuiz->pass_mark, 'total' => $checkpointInfo['total_grade']]) }}</span>
                                            <span class="tag outline">{{ !empty($cpQuiz->time) ? trans('bundle_learning.checkpoint_minutes', ['minutes' => (int)$cpQuiz->time]) : trans('bundle_learning.checkpoint_no_limit') }}</span>
                                            @if(!is_null($checkpointInfo['attempts_left']))
                                                <span class="tag outline">{{ trans('bundle_learning.inline_quiz_attempts_left', ['count' => $checkpointInfo['attempts_left']]) }}</span>
                                            @endif
                                        </div>

                                        @if($currentCheckpoint['passed'])
                                            <div class="feedback good">✓ {{ trans('bundle_learning.checkpoint_passed', ['title' => $cpName]) }}@if(!empty($cpLast) and $cpLast->status === \App\Models\QuizzesResult::$passed) · {{ (int)$cpLast->user_grade }}/{{ $checkpointInfo['total_grade'] }}@endif</div>
                                        @elseif(!empty($cpLast) and $cpLast->status === \App\Models\QuizzesResult::$waiting)
                                            <div class="feedback" style="background:#fff8e1;color:#7a5a00">⏳ {{ trans('bundle_learning.checkpoint_waiting') }}</div>
                                        @elseif(!empty($cpLast) and $cpLast->status === \App\Models\QuizzesResult::$failed)
                                            <div class="feedback bad">{{ trans('bundle_learning.checkpoint_last_failed', ['grade' => (int)$cpLast->user_grade, 'total' => $checkpointInfo['total_grade']]) }}</div>
                                        @endif

                                        @if(!$checkpointInfo['can_submit'] and !$currentCheckpoint['passed'])
                                            <div class="feedback bad">{{ trans('bundle_learning.inline_quiz_no_attempts') }}</div>
                                        @endif

                                        @if(!empty($cpQuiz->time) and $checkpointInfo['can_submit'])
                                            <p class="muted tiny" style="margin-top:10px">{{ trans('bundle_learning.checkpoint_start_hint') }}</p>
                                        @endif
                                    </div>

                                    <div class="js-checkpoint-body"></div>

                                    <div class="v15-assessment-footer js-checkpoint-footer">
                                        @if($checkpointInfo['can_submit'] and !(!empty($cpLast) and $cpLast->status === \App\Models\QuizzesResult::$waiting))
                                            <button type="button" class="action sm primary js-checkpoint-start">{{ ($currentCheckpoint['passed'] or !empty($cpLast)) ? trans('bundle_learning.checkpoint_retake') : trans('bundle_learning.checkpoint_start') }} →</button>
                                        @endif
                                        @if(!empty($cpLast))
                                            <a href="/panel/quizzes/results/{{ $cpLast->id }}/details" class="action sm js-checkpoint-last-result" target="_blank">{{ trans('bundle_learning.inline_quiz_view_detail') }} ↗</a>
                                        @endif
                                        <a href="{{ $lessonPageUrl($chapter->id) }}" class="action sm">{{ trans('bundle_learning.checkpoint_back') }}</a>
                                    </div>
                                </div>

                            @else
                                <div class="edk-empty-frame">{{ trans('bundle_learning.lesson_no_content') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- ================= Tab Giới thiệu / Ghi chú / Quizzes ================= --}}
                    <div class="shell">
                        <div class="section-tabs js-lesson-tabs" role="tablist">
                            <button type="button" class="active" data-tab="about">{{ trans('bundle_learning.tab_about') }}</button>
                            @if(!empty($currentVideo))
                                <button type="button" data-tab="notes">{{ trans('bundle_learning.tab_notes') }}</button>
                            @endif
                            <button type="button" data-tab="quizzes">Quizzes</button>
                        </div>

                        <div class="tab-body">
                            <div class="tab-pane" data-pane="about">
                                <h3>{{ trans('bundle_learning.tab_about') }}</h3>
                                @if(!empty($currentVideo) and !empty($currentVideo['model']->description))
                                    <div>{!! clean($currentVideo['model']->description) !!}</div>
                                @elseif(!empty($webinar->summary))
                                    <p>{{ trim(strip_tags($webinar->summary)) }}</p>
                                @endif
                                <p class="muted">{{ trans('bundle_learning.lesson_about_hint') }}</p>

                                @if($otherItems->isNotEmpty())
                                    <h3 style="margin-top:18px">{{ trans('bundle_learning.lesson_resources') }}</h3>
                                    <div class="q-summary edk-resource-list">
                                        @foreach($otherItems as $item)
                                            <div class="q-line {{ $contentUnlocked ? '' : 'locked' }}">
                                                <div class="q-text">
                                                    <b>{{ $item['title'] }}</b>
                                                    <span>{{ ['file' => trans('bundle_learning.syllabus_file'), 'text_lesson' => trans('bundle_learning.syllabus_text_lesson'), 'session' => trans('bundle_learning.syllabus_session'), 'quiz' => 'Quiz'][$item['type']] ?? '' }}{{ $item['done'] ? ' · ✓' : '' }}</span>
                                                </div>
                                                @if($contentUnlocked)
                                                    <a href="{{ $itemLearningUrl($item['type'], $item['id']) }}" class="action sm">{{ trans('bundle_learning.open') }}</a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if(!empty($currentVideo))
                                <div class="tab-pane" data-pane="notes" hidden>
                                    <h3>{{ trans('bundle_learning.tab_notes') }}</h3>
                                    <p class="muted tiny">{{ trans('bundle_learning.notes_hint') }}</p>
                                    <div class="notes-editor field js-lesson-note"
                                         data-url="/course/learning/{{ $webinar->slug }}/personal-note/store"
                                         data-item-id="{{ $currentVideo['id'] }}">
                                        <textarea rows="6" placeholder="{{ trans('bundle_learning.notes_placeholder') }}">{{ optional($personalNote)->note }}</textarea>
                                    </div>
                                    <div class="flex" style="margin-top:10px">
                                        <button type="button" class="action sm primary js-lesson-note-save">{{ trans('public.save') }}</button>
                                    </div>
                                </div>
                            @endif

                            <div class="tab-pane" data-pane="quizzes" hidden>
                                @php
                                    $miniDone = $miniQuizzes->where('done', true)->count();
                                    $miniAllDone = ($miniQuizzes->isNotEmpty() && $miniDone === $miniQuizzes->count());
                                @endphp
                                <h3 class="js-mini-title {{ $miniAllDone ? 'v10-quiz-title-complete' : '' }}">Mini Quiz <span class="tag outline"><span class="js-mini-done">{{ $miniDone }}</span>/{{ $miniQuizzes->count() }} {{ trans('bundle_learning.done_short') }}</span></h3>
                                <p class="muted tiny">{{ trans('bundle_learning.mini_quiz_inline_hint') }}</p>

                                <div class="q-summary js-mini-summary {{ $miniAllDone ? 'v10-quiz-complete' : '' }}">
                                    @forelse($miniQuizzes as $miniQuiz)
                                        @php
                                            $miniVideoIndex = $videos->search(function ($video) use ($miniQuiz) { return $video['id'] === $miniQuiz['file_id']; });
                                        @endphp
                                        <div class="q-line {{ $contentUnlocked ? '' : 'locked' }} js-mini-line" data-quiz-id="{{ $miniQuiz['id'] }}">
                                            <div class="q-text">
                                                <b>{{ $miniVideoIndex !== false ? 'Video ' . str_pad($miniVideoIndex + 1, 2, '0', STR_PAD_LEFT) . ' · ' : '' }}{{ $miniQuiz['title'] }}</b>
                                                <span class="js-mini-state">{{ $miniQuiz['done'] ? '✓ ' . trans('bundle_learning.status_completed') : (!is_null($miniQuiz['show_at_second']) ? trans('bundle_learning.mini_quiz_at', ['time' => gmdate('i:s', (int)$miniQuiz['show_at_second'])]) : trans('bundle_learning.mini_quiz_pending')) }}</span>
                                            </div>
                                            @if($contentUnlocked)
                                                <button type="button" class="action sm {{ $miniQuiz['done'] ? '' : 'primary' }} js-mini-open"
                                                        data-quiz-id="{{ $miniQuiz['id'] }}"
                                                        data-url="/panel/courses/bundle-learning/quizzes/{{ $miniQuiz['id'] }}">{{ $miniQuiz['done'] ? trans('bundle_learning.mini_quiz_review') : trans('bundle_learning.take_quiz') }}</button>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="muted tiny">{{ trans('bundle_learning.no_mini_quiz') }}</p>
                                    @endforelse
                                </div>

                                <div id="mini-quiz-inline" class="inner edk-mini-inline" hidden></div>

                                @foreach($lesson['exercise'] as $exercise)
                                    <div class="q-line" style="margin-top:14px">
                                        <div class="q-text">
                                            <b>{{ trans('bundle_learning.activity_exercise') }} · {{ $exercise['title'] }}</b>
                                            <span>{{ $exercise['done'] ? '✓ ' . trans('bundle_learning.test_done') : trans('bundle_learning.exercise_assigned') }}</span>
                                        </div>
                                        <a href="{{ $lessonPageUrl($chapter->id, 'view=exercise&test=' . $exercise['link_id']) }}" class="action sm">{{ trans('bundle_learning.test_start') }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= Cột "Nội dung bài học" ================= --}}
                <aside class="shell sidebar">
                    <div class="flex between"><h3 style="margin:0">{{ trans('bundle_learning.lesson_outline') }}</h3></div>

                    <div id="course-outline">
                        @foreach($progress['modules'] as $outlineModule)
                            @php
                                $isCurrentModule = (int)$outlineModule['webinar_id'] === (int)$module['webinar_id'];
                                $outlineModuleNumber = $outlineModule['order_index'] + 1;
                            @endphp
                            <div class="stepper-module">
                                <button type="button" class="module-heading {{ $isCurrentModule ? 'active' : '' }} js-outline-toggle" {{ $outlineModule['unlocked'] ? '' : 'data-locked=1' }}>
                                    <span style="flex:1;min-width:0">Module {{ $outlineModuleNumber }} · {{ $outlineModule['title'] }}</span>
                                    <span class="v9-item-state">
                                        @if($outlineModule['completed'])
                                            {!! $doneMark !!}
                                        @elseif(!$outlineModule['unlocked'])
                                            <span class="v10-lock-line" style="color:#fff">{!! $icon('lock') !!}</span>
                                        @else
                                            {!! $icon($isCurrentModule ? 'up' : 'down') !!}
                                        @endif
                                    </span>
                                </button>

                                <div class="course-stepper" {{ $isCurrentModule ? '' : 'hidden' }}>
                                    @foreach($outlineModule['lessons'] as $outlineLesson)
                                        @php
                                            $isCurrentLesson = (int)$outlineLesson['chapter_id'] === (int)$lesson['chapter_id'];
                                            $outlineLessonNumber = $outlineLesson['order_index'] + 1;
                                        @endphp
                                        <div class="step-lesson">
                                            <span class="v10-lesson-icon" aria-hidden="true">{!! $icon('book') !!}</span>
                                            <a href="{{ $outlineLesson['unlocked'] ? $lessonPageUrl($outlineLesson['chapter_id']) : '#' }}" class="step-lesson-header {{ $outlineLesson['unlocked'] ? '' : 'is-disabled' }}">
                                                <span class="v9-head-title">Lesson {{ $outlineLessonNumber }} — {{ $outlineLesson['title'] }}</span>
                                                <span class="v9-head-status">
                                                    @if($outlineLesson['completed'])
                                                        {!! $doneMark !!}
                                                    @elseif(!$outlineLesson['unlocked'])
                                                        {!! $lockMark !!}
                                                    @else
                                                        {!! $icon($isCurrentLesson ? 'up' : 'down') !!}
                                                    @endif
                                                </span>
                                            </a>

                                            @if($isCurrentLesson)
                                                <div class="step-lesson-body">
                                                    {{-- Pre Lesson --}}
                                                    @if(!empty($pre))
                                                        <div class="step-group">
                                                            <div class="step-group-title"><span class="step-symbol">{!! $icon('book') !!}</span>Pre Lesson</div>
                                                            <a href="{{ $lessonPageUrl($chapter->id, 'view=pre') }}" class="step-item {{ $view === 'pre' ? 'current' : '' }}">
                                                                <span class="v9-item-text">My Word List · {{ $pre['known'] }}/{{ $pre['required'] }}</span>
                                                                <span class="v9-item-state">{!! $pre['done'] ? $doneMark : $icon('dot') !!}</span>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    {{-- Video Learning (+ Bài Tập giáo viên giao, đặt sau Video 01 như design) --}}
                                                    <div class="step-group">
                                                        <div class="step-group-title"><span class="step-symbol">{!! $icon('play') !!}</span>Video Learning</div>
                                                        @foreach($videos as $index => $video)
                                                            <a href="{{ $contentUnlocked ? $lessonPageUrl($chapter->id, 'view=video&item=' . $video['id']) : '#' }}"
                                                               class="step-item {{ ($view === 'video' and !empty($currentVideo) and $currentVideo['id'] === $video['id']) ? 'current' : '' }} {{ $contentUnlocked ? '' : 'is-disabled' }}">
                                                                <span class="v9-item-text">{!! $icon('play') !!} Video {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                                                <span class="v9-item-state">{!! $video['done'] ? $doneMark : ($contentUnlocked ? $icon('dot') : $lockMark) !!}</span>
                                                            </a>

                                                            @if($index === 0)
                                                                @foreach($lesson['exercise'] as $exercise)
                                                                    <a href="{{ $contentUnlocked ? $lessonPageUrl($chapter->id, 'view=exercise&test=' . $exercise['link_id']) : '#' }}" class="step-item {{ ($view === 'exercise' and !empty($currentTest) and $currentTest['link_id'] === $exercise['link_id']) ? 'current' : '' }} {{ $contentUnlocked ? '' : 'is-disabled' }}">
                                                                        <span class="v9-item-text">{!! $icon('file') !!} {{ trans('bundle_learning.activity_exercise') }}</span>
                                                                        <span class="v9-item-state">{!! $exercise['done'] ? $doneMark : ($contentUnlocked ? $icon('dot') : $lockMark) !!}</span>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        @endforeach

                                                        @if($videos->isEmpty())
                                                            @foreach($lesson['exercise'] as $exercise)
                                                                <a href="{{ $contentUnlocked ? $lessonPageUrl($chapter->id, 'view=exercise&test=' . $exercise['link_id']) : '#' }}" class="step-item {{ ($view === 'exercise' and !empty($currentTest) and $currentTest['link_id'] === $exercise['link_id']) ? 'current' : '' }} {{ $contentUnlocked ? '' : 'is-disabled' }}">
                                                                    <span class="v9-item-text">{!! $icon('file') !!} {{ trans('bundle_learning.activity_exercise') }}</span>
                                                                    <span class="v9-item-state">{!! $exercise['done'] ? $doneMark : ($contentUnlocked ? $icon('dot') : $lockMark) !!}</span>
                                                                </a>
                                                            @endforeach
                                                        @endif

                                                        @foreach($otherItems as $item)
                                                            <a href="{{ $contentUnlocked ? $itemLearningUrl($item['type'], $item['id']) : '#' }}" class="step-item {{ $contentUnlocked ? '' : 'is-disabled' }}">
                                                                <span class="v9-item-text">{!! $icon('file') !!} {{ \Illuminate\Support\Str::limit($item['title'], 38) }}</span>
                                                                <span class="v9-item-state">{!! $item['done'] ? $doneMark : ($contentUnlocked ? $icon('dot') : $lockMark) !!}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>

                                                    {{-- Post Lesson --}}
                                                    @if(!empty($lesson['post']))
                                                        <div class="step-group">
                                                            <div class="step-group-title"><span class="step-symbol">{!! $icon('target') !!}</span>Post Lesson</div>
                                                            @foreach($lesson['post'] as $postIndex => $post)
                                                                <a href="{{ $contentUnlocked ? $lessonPageUrl($chapter->id, 'view=post&test=' . $post['link_id']) : '#' }}" class="step-item {{ ($view === 'post' and !empty($currentTest) and $currentTest['link_id'] === $post['link_id']) ? 'current' : '' }} {{ $contentUnlocked ? '' : 'is-disabled' }}">
                                                                    <span class="v9-item-text">{!! $icon('file') !!} {{ trans('bundle_learning.post_item', ['number' => str_pad($postIndex + 1, 2, '0', STR_PAD_LEFT)]) }}</span>
                                                                    <span class="v9-item-state">{!! $post['done'] ? $doneMark : ($contentUnlocked ? $icon('dot') : $lockMark) !!}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Mid-term / Final sau module đủ điều kiện --}}
                            @foreach($checkpointsAfter[$outlineModuleNumber] ?? [] as $checkpoint)
                                @php $checkpointQuiz = $checkpointQuizzes->get($checkpoint['quiz_id']); @endphp
                                @php $isCurrentCheckpoint = ($view === 'checkpoint' and !empty($currentCheckpoint) and (int)$currentCheckpoint['quiz_id'] === (int)$checkpoint['quiz_id']); @endphp
                                <div class="v15-checkpoint {{ $checkpoint['type'] === 'final' ? 'v15-final' : '' }}">
                                    @if($checkpoint['unlocked'] and !empty($checkpointQuiz))
                                        {{-- GĐ7: làm ngay trong khung player (không mở trang quiz của module) --}}
                                        <a class="v15-checkpoint-btn {{ $isCurrentCheckpoint ? 'is-current' : '' }}" href="{{ $checkpointUrl($checkpointQuiz->id) }}">
                                            <span class="v15-checkpoint-title">{{ $checkpoint['title'] ?: trans('bundle_learning.checkpoint_' . $checkpoint['type']) }}</span>
                                            <span class="v15-checkpoint-status">{!! $checkpoint['passed'] ? '<span class="v15-checked">✓ ' . e(trans('bundle_learning.status_completed')) . '</span>' : '<span class="tag outline">' . e(trans('bundle_learning.library_start')) . '</span>' !!}</span>
                                        </a>
                                    @else
                                        <span class="v15-checkpoint-btn {{ $checkpoint['passed'] ? '' : 'is-disabled' }}">
                                            <span class="v15-checkpoint-title">{{ $checkpoint['title'] ?: trans('bundle_learning.checkpoint_' . $checkpoint['type']) }}</span>
                                            <span class="v15-checkpoint-status">{!! $checkpoint['passed'] ? $doneMark : $lockMark !!}</span>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </aside>
            </div>
        </section>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    @include('design_1.web.courses.learning_page.includes.bundle_player_script')

    <script>
        (function () {
            'use strict';

            var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';

            function notify(type, message) {
                if (message && typeof window.showToast === 'function') {
                    window.showToast(type, '', message);
                }
            }

            function post(url, data) {
                var body = new FormData();
                body.append('_token', csrf);
                Object.keys(data).forEach(function (key) { body.append(key, data[key]); });

                return fetch(url, {
                    method: 'POST',
                    body: body,
                    credentials: 'same-origin',
                    headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
                }).then(function (response) {
                    return response.json().catch(function () { return {}; }).then(function (json) {
                        return {ok: response.ok, json: json};
                    });
                });
            }

            // Player: Plyr cho video (gắn instance vào phần tử để script ghi nhận tiến độ xem dùng được)
            document.querySelectorAll('.js-lesson-plyr').forEach(function (element) {
                if (typeof Plyr === 'undefined') {
                    return;
                }
                var player = new Plyr(element, {speed: {selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2]}});
                element.plyr = player;
            });

            // Tab Giới thiệu / Ghi chú / Quizzes
            document.querySelectorAll('.js-lesson-tabs button').forEach(function (button) {
                button.addEventListener('click', function () {
                    var tab = button.getAttribute('data-tab');
                    document.querySelectorAll('.js-lesson-tabs button').forEach(function (b) { b.classList.toggle('active', b === button); });
                    document.querySelectorAll('#player .tab-pane').forEach(function (pane) {
                        pane.hidden = pane.getAttribute('data-pane') !== tab;
                    });
                });
            });

            function openTab(tab) {
                var button = document.querySelector('.js-lesson-tabs button[data-tab="' + tab + '"]');
                if (button) {
                    button.click();
                }
            }

            // GĐ7 - Mini Quiz làm ngay tại tab Quizzes (video tự dừng ở mốc quiz)
            var miniBox = document.getElementById('mini-quiz-inline');
            var miniLabels = {
                done: @json('✓ ' . trans('bundle_learning.status_completed')),
                waiting: @json('⏳ ' . trans('bundle_learning.mini_quiz_waiting')),
                review: @json(trans('bundle_learning.mini_quiz_review'))
            };

            function markMiniLine(quizId, status) {
                var line = document.querySelector('.js-mini-line[data-quiz-id="' + quizId + '"]');
                if (!line || line.getAttribute('data-done') === '1') {
                    return;
                }
                line.querySelector('.js-mini-state').textContent = status === 'waiting' ? miniLabels.waiting : miniLabels.done;
                if (status !== 'passed') {
                    return;
                }
                line.setAttribute('data-done', '1');
                var button = line.querySelector('.js-mini-open');
                if (button) {
                    button.classList.remove('primary');
                    button.textContent = miniLabels.review;
                }
                var counter = document.querySelector('.js-mini-done');
                var lines = document.querySelectorAll('.js-mini-line');
                if (counter) {
                    var done = parseInt(counter.textContent, 10) + 1;
                    counter.textContent = done;
                    if (done >= lines.length) {
                        document.querySelector('.js-mini-title').classList.add('v10-quiz-title-complete');
                        document.querySelector('.js-mini-summary').classList.add('v10-quiz-complete');
                    }
                }
            }

            function showMiniQuiz(quizId, url, fromVideo) {
                if (!miniBox || !window.EdkInlineQuiz) {
                    return false;
                }
                openTab('quizzes');
                document.querySelectorAll('.js-mini-line').forEach(function (line) {
                    line.classList.toggle('is-active', line.getAttribute('data-quiz-id') === String(quizId));
                });
                miniBox.hidden = false;

                var extraButtons = [];
                if (fromVideo && !fromVideo.quiz.blocking) {
                    extraButtons.push({label: @json(trans('bundle_learning.later')), onClick: function () { fromVideo.resume(); }});
                }

                window.EdkInlineQuiz.mount(miniBox, {
                    url: url,
                    mode: 'mini',
                    extraButtons: extraButtons,
                    onResult: function (json) {
                        if (json.status !== 'passed' && json.status !== 'waiting') {
                            return;
                        }
                        markMiniLine(quizId, json.status);
                        if (fromVideo) {
                            fromVideo.markPassed(json.status);
                        } else if (window.bundleLearningPlayerApi) {
                            window.bundleLearningPlayerApi.markQuizPassed(quizId, json.status);
                        }

                        // Nút xem tiếp video (về khung player)
                        var footer = miniBox.querySelector('.js-iq-footer');
                        var video = document.querySelector('.edk-video-frame');
                        if (footer && video) {
                            footer.innerHTML = '';
                            var next = document.createElement('button');
                            next.type = 'button';
                            next.className = 'edk-iq-btn primary';
                            next.textContent = @json(trans('bundle_learning.inline_quiz_continue_video')) + ' →';
                            next.addEventListener('click', function () {
                                video.scrollIntoView({behavior: 'smooth', block: 'center'});
                                if (fromVideo) {
                                    fromVideo.resume();
                                }
                            });
                            footer.appendChild(next);
                        }
                    }
                });

                setTimeout(function () { miniBox.scrollIntoView({behavior: 'smooth', block: 'start'}); }, 60);
                return true;
            }

            document.addEventListener('edk:mini-quiz', function (event) {
                var detail = event.detail;
                if (showMiniQuiz(detail.quiz.id, detail.quiz.inline_url, detail)) {
                    event.preventDefault();
                }
            });

            document.querySelectorAll('.js-mini-open').forEach(function (button) {
                button.addEventListener('click', function () {
                    showMiniQuiz(button.getAttribute('data-quiz-id'), button.getAttribute('data-url'), null);
                });
            });

            // GĐ7 - Mid-term / Final trong khung player: bấm "Bắt đầu" mới tải câu hỏi và đếm giờ
            var checkpoint = document.querySelector('.js-checkpoint');
            if (checkpoint && window.EdkInlineQuiz) {
                var startButton = checkpoint.querySelector('.js-checkpoint-start');
                if (startButton) {
                    startButton.addEventListener('click', function () {
                        checkpoint.querySelector('.js-checkpoint-intro').hidden = true;
                        startButton.remove();
                        // Link kết quả cũ: kết quả lượt mới hiện ngay trong bài
                        checkpoint.querySelectorAll('.js-checkpoint-last-result').forEach(function (link) { link.remove(); });
                        var body = checkpoint.querySelector('.js-checkpoint-body');
                        window.EdkInlineQuiz.mount(body, {
                            url: checkpoint.getAttribute('data-url'),
                            mode: 'checkpoint',
                            onResult: function (json) {
                                if (json.status === 'passed') {
                                    notify('success', json.message);
                                }
                            }
                        });
                    });
                }
            }

            // Cột "Nội dung bài học": mở / đóng module
            document.querySelectorAll('.js-outline-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (button.hasAttribute('data-locked')) {
                        notify('error', @json(trans('bundle_learning.syllabus_module_locked')));
                        return;
                    }
                    var stepper = button.parentElement.querySelector('.course-stepper');
                    if (stepper) {
                        stepper.hidden = !stepper.hidden;
                    }
                });
            });

            // Pre Lesson: đánh dấu từ đã thuộc
            var pre = document.querySelector('.js-pre-lesson');
            if (pre) {
                var required = parseInt(pre.getAttribute('data-required'), 10);
                var total = parseInt(pre.getAttribute('data-total'), 10);
                var completeButton = pre.querySelector('.js-pre-complete');

                function render(known) {
                    pre.querySelector('.js-pre-known').textContent = known;
                    pre.querySelector('.js-pre-bar').style.width = (total > 0 ? Math.round(known / total * 100) : 0) + '%';
                    pre.querySelector('.js-pre-target').classList.toggle('success', known >= required);
                    if (completeButton) {
                        completeButton.disabled = known < required;
                    }
                }

                pre.querySelectorAll('.js-pre-word').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var known = !button.classList.contains('done');
                        button.classList.add('is-busy');
                        post(pre.getAttribute('data-word-url'), {word_id: button.getAttribute('data-word-id'), known: known ? 1 : 0}).then(function (result) {
                            button.classList.remove('is-busy');
                            if (!result.ok) {
                                notify('error', @json(trans('public.request_failed')));
                                return;
                            }
                            button.classList.toggle('done', known);
                            button.setAttribute('aria-pressed', known ? 'true' : 'false');
                            button.querySelector('.js-pre-check').textContent = known ? '✓ ' : '';
                            render(result.json.known);
                        });
                    });
                });

                if (completeButton) {
                    completeButton.addEventListener('click', function () {
                        completeButton.disabled = true;
                        post(pre.getAttribute('data-complete-url'), {}).then(function (result) {
                            if (result.ok && result.json.redirect) {
                                window.location.href = result.json.redirect;
                                return;
                            }
                            completeButton.disabled = false;
                            notify('error', result.json.message || @json(trans('public.request_failed')));
                        });
                    });
                }
            }

            // Ghi chú cá nhân của video
            var noteSave = document.querySelector('.js-lesson-note-save');
            if (noteSave) {
                noteSave.addEventListener('click', function () {
                    var box = document.querySelector('.js-lesson-note');
                    var details = box.querySelector('textarea').value.trim();
                    if (!details) {
                        return;
                    }
                    noteSave.disabled = true;
                    post(box.getAttribute('data-url'), {details: details, item_id: box.getAttribute('data-item-id'), item_type: 'file'}).then(function (result) {
                        noteSave.disabled = false;
                        notify(result.ok ? 'success' : 'error', result.ok ? @json(trans('bundle_learning.notes_saved')) : @json(trans('public.request_failed')));
                    });
                });
            }
        })();
    </script>
@endpush
