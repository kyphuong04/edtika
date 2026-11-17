{{--
    GĐ6 - Hoạt động theo design của một lesson (chapter) trong trình soạn module:
    Pre Lesson (bộ từ trong Từ điển), Bài Tập (giao thêm cho cả lớp), Post Lesson (không giới hạn số bài).
    Khung này nằm TRONG form của trình soạn module nên không dùng <form> và không đặt name cho input;
    mọi thao tác gửi AJAX (lesson_activities_script) rồi thay lại HTML của khung.
    Biến: $bundle, $chapter, $lessonActivityOptions (['wordSets', 'tests'])
--}}
@php
    $activityLinks = $chapter->lessonTests()->with('test:id,title,status,is_active')->get();
    $activityGroups = [
        'exercise' => $activityLinks->where('role', 'exercise')->values(),
        'post' => $activityLinks->where('role', 'post')->values(),
    ];
    $activityWordSets = $lessonActivityOptions['wordSets'];
    $activityTests = $lessonActivityOptions['tests'];
    $currentSet = !empty($chapter->pre_vocabulary_set_id) ? $activityWordSets->firstWhere('id', $chapter->pre_vocabulary_set_id) : null;
    $activityBaseUrl = '/panel/bundles/' . $bundle->id . '/lessons/' . $chapter->id;
    $activityStatusLabel = function ($status, $isActive = true) {
        if ($status === 'published' or $status === \App\Models\BundleVocabularySet::STATUS_APPROVED) {
            return $isActive ? null : trans('bundle_learning.activity_status_inactive');
        }
        return trans('bundle_learning.activity_status_not_approved');
    };
@endphp

<div class="js-lesson-activities lesson-activities" data-url="{{ $activityBaseUrl }}">
    <div class="lesson-activities__head">
        <b>{{ trans('bundle_learning.activity_title') }}</b>
        <span>{{ trans('bundle_learning.activity_hint') }}</span>
    </div>

    {{-- ===== Pre Lesson ===== --}}
    <div class="lesson-activities__row">
        <div class="lesson-activities__label">
            <span class="lesson-activities__badge">Pre Lesson</span>
            <small>{{ trans('bundle_learning.activity_pre_hint', ['percent' => config('bundle_learning.pre_lesson_pass_percent', 80)]) }}</small>
        </div>

        <div class="lesson-activities__body">
            @if(!empty($currentSet))
                <div class="lesson-activities__current">
                    ✓ {{ $currentSet->name }} · {{ (int)$currentSet->words_count }} {{ trans('bundle_learning.words') }}
                    · {{ trans('bundle_learning.activity_pre_required', ['count' => \App\Services\Bundle\LessonActivityService::requiredWords((int)$currentSet->words_count)]) }}
                </div>
            @elseif(!empty($chapter->pre_vocabulary_set_id))
                <div class="lesson-activities__warn">{{ trans('bundle_learning.activity_word_set_missing') }}</div>
            @endif

            <div class="lesson-activities__controls">
                <select class="form-control js-activity-word-set" aria-label="{{ trans('bundle_learning.activity_choose_word_set') }}">
                    <option value="">{{ trans('bundle_learning.activity_no_pre_lesson') }}</option>
                    @foreach($activityWordSets as $wordSet)
                        @php $setNote = $activityStatusLabel($wordSet->status); @endphp
                        <option value="{{ $wordSet->id }}" {{ (int)$chapter->pre_vocabulary_set_id === (int)$wordSet->id ? 'selected' : '' }} {{ $setNote ? 'disabled' : '' }}>
                            {{ $wordSet->name }} · {{ (int)$wordSet->words_count }} {{ trans('bundle_learning.words') }}{{ $setNote ? ' (' . $setNote . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-primary js-activity-save-pre">{{ trans('public.save') }}</button>
            </div>

            @if($activityWordSets->isEmpty())
                <small class="lesson-activities__muted">{{ trans('bundle_learning.activity_no_word_sets') }} <a href="/panel/dictionary" target="_blank">{{ trans('bundle_learning.activity_open_dictionary') }} ↗</a></small>
            @endif
        </div>
    </div>

    {{-- ===== Bài Tập / Post Lesson ===== --}}
    @foreach(['exercise', 'post'] as $activityRole)
        <div class="lesson-activities__row">
            <div class="lesson-activities__label">
                <span class="lesson-activities__badge">{{ $activityRole === 'exercise' ? trans('bundle_learning.activity_exercise') : 'Post Lesson' }}</span>
                <small>{{ trans('bundle_learning.activity_' . $activityRole . '_hint') }}</small>
            </div>

            <div class="lesson-activities__body">
                @if($activityGroups[$activityRole]->isNotEmpty())
                    <ul class="lesson-activities__list">
                        @foreach($activityGroups[$activityRole] as $linkIndex => $link)
                            @php $testNote = !empty($link->test) ? $activityStatusLabel($link->test->status, (bool)$link->test->is_active) : trans('bundle_learning.activity_test_deleted'); @endphp
                            <li>
                                <span class="lesson-activities__num">{{ $activityRole === 'post' ? trans('bundle_learning.post_item', ['number' => str_pad($linkIndex + 1, 2, '0', STR_PAD_LEFT)]) : '#' . ($linkIndex + 1) }}</span>
                                <span class="lesson-activities__title">{{ optional($link->test)->title ?? '—' }}</span>
                                @if($testNote)
                                    <span class="lesson-activities__status" title="{{ trans('bundle_learning.activity_hidden_for_students') }}">{{ $testNote }}</span>
                                @endif
                                <span class="lesson-activities__actions">
                                    @if(!empty($link->test))
                                        <a href="/panel/my-ielts-tests/{{ $link->test->id }}/edit-inline" target="_blank" title="{{ trans('public.edit') }}">✎</a>
                                    @endif
                                    <button type="button" class="js-activity-move" data-id="{{ $link->id }}" data-dir="-1" title="{{ trans('bundle_learning.modules_move_up') }}" {{ $linkIndex === 0 ? 'disabled' : '' }}>↑</button>
                                    <button type="button" class="js-activity-move" data-id="{{ $link->id }}" data-dir="1" title="{{ trans('bundle_learning.modules_move_down') }}" {{ $linkIndex === $activityGroups[$activityRole]->count() - 1 ? 'disabled' : '' }}>↓</button>
                                    <button type="button" class="js-activity-remove" data-id="{{ $link->id }}" title="{{ trans('public.delete') }}">×</button>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="lesson-activities__controls">
                    <select class="form-control js-activity-test" data-role="{{ $activityRole }}" aria-label="{{ trans('bundle_learning.activity_choose_test') }}">
                        <option value="">{{ trans('bundle_learning.activity_choose_test') }}</option>
                        @foreach($activityTests as $activityTest)
                            @php $optNote = $activityStatusLabel($activityTest->status, (bool)$activityTest->is_active); @endphp
                            <option value="{{ $activityTest->id }}">{{ $activityTest->title }}{{ $optNote ? ' (' . $optNote . ')' : '' }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary js-activity-add-test" data-role="{{ $activityRole }}">
                        + {{ $activityRole === 'exercise' ? trans('bundle_learning.activity_assign_exercise') : trans('bundle_learning.activity_add_post') }}
                    </button>
                </div>

                @if($activityTests->isEmpty())
                    <small class="lesson-activities__muted">{{ trans('bundle_learning.activity_no_tests') }} <a href="/panel/my-ielts-tests/create-inline" target="_blank">{{ trans('bundle_learning.activity_create_test') }} ↗</a></small>
                @endif
            </div>
        </div>
    @endforeach
</div>
