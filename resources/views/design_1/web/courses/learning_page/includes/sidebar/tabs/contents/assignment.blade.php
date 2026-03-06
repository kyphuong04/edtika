@php
    $checkSequenceContent = $assignment->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

    $assignmentPersonalNote = $assignment->personalNote()->where('user_id', $authUser->id)->first();
    $hasPersonalNote = (!empty($assignmentPersonalNote) and !empty($assignmentPersonalNote->note));

    $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);

    $requestStudent = null;

    if (request()->get('type') == "assignment" and request()->get('item') == $assignment->id and !empty(request()->get('student'))) {
        $requestStudent = request()->get('student');
    }
@endphp


<div class="sidebar-timeline-item sidebar-content-item js-content-tab-item {{ ($user->isAdmin() or $course->isPartnerTeacher($user->id)) ? 'js-not-access-toast' : ($hasSequenceContentError ? 'js-sequence-content-error-modal' : '') }} {{ !empty($isLast) ? 'is-last' : '' }}"
     data-type="assignment"
     data-id="{{ $assignment->id }}"
     data-extra-key="student"
     data-extra-value="{{ !empty($requestStudent) ? $requestStudent : '' }}"
     data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
     data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
>
    {{-- Timeline dot --}}
    <div class="timeline-dot">
    </div>

    {{-- Content --}}
    <div class="timeline-content">
        <span class="timeline-title">{{ truncate($assignment->title, 35) }}</span>
        <div class="timeline-meta d-flex align-items-center gap-6 mt-2">
            <x-iconsax-lin-clipboard-text class="icons text-gray-400" width="12px" height="12px"/>
            <span class="timeline-type">{{ trans('update.assignment') }}</span>
            @if($hasSequenceContentError)
                <x-iconsax-bol-lock-circle class="icons text-danger ml-2" width="12px" height="12px"/>
            @endif
            @if($hasPersonalNote)
                <x-iconsax-bul-note class="icons text-gray-400" width="12px" height="12px"/>
            @endif
        </div>
    </div>
</div>
