@extends('design_1.panel.layouts.panel')

{{-- GĐ4: chi tiết bài nộp + form chấm --}}

@push('styles_top')
    @include('design_1.panel.bundles.learning.partials.styles')
    @include('design_1.panel.bundles.grading.partials.styles')
@endpush

@section('content')
    @php
        $student = $history->student;
        $statusLabels = [
            'pending' => trans('bundle_learning.grading_status_pending'),
            'passed' => trans('bundle_learning.passed'),
            'not_passed' => trans('bundle_learning.not_passed'),
        ];
        $maxGrade = max((int)$assignment->grade, (int)$assignment->pass_grade, 0);
    @endphp

    <div class="bl-page">
        <div class="bl-crumb mb-12">
            <a href="/panel/bundles/{{ $bundle->id }}/modules">{{ $bundle->title }}</a>
            <span>›</span>
            <a href="/panel/bundles/{{ $bundle->id }}/grading">{{ trans('bundle_learning.grading_title') }}</a>
            <span>›</span>
            <b>{{ optional($student)->full_name }}</b>
        </div>

        <div class="bl-overview">
            <section class="bl-card">
                <span class="bl-tag bl-tag--outline">
                    {{ optional($assignment->webinar)->title }}
                    @if(!empty($assignment->chapter)) · {{ $assignment->chapter->title }} @endif
                </span>
                <h1 class="bl-title">{{ $assignment->title }}</h1>

                @if(!empty($assignment->description))
                    <div class="bl-muted bg-assignment-desc">{!! clean($assignment->description) !!}</div>
                @endif

                <h2 class="bl-module__title mt-24">{{ trans('bundle_learning.submission_history') }}</h2>

                <div class="bg-thread mt-12">
                    @forelse($history->messages as $message)
                        @php
                            $fromStudent = ($message->sender_id == $history->student_id);
                        @endphp
                        <div class="bg-message {{ $fromStudent ? '' : 'bg-message--teacher' }}">
                            <div class="bg-message__head">
                                <b>{{ optional($message->sender)->full_name }}</b>
                                <span class="bl-muted bl-small">{{ dateTimeFormat($message->created_at, 'j M Y H:i') }}</span>
                            </div>
                            <div class="bg-message__body">{!! nl2br(e($message->message)) !!}</div>
                            @if(!empty($message->file_path))
                                <a href="{{ $message->getDownloadUrl($assignment->id) }}" target="_blank" class="bl-link bl-small">
                                    📎 {{ $message->file_title ?: trans('bundle_learning.attachment') }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="bl-muted">{{ trans('bundle_learning.no_submission') }}</p>
                    @endforelse
                </div>
            </section>

            <aside class="bl-card align-self-start">
                <h3 class="font-16 font-weight-bold mb-4">{{ optional($student)->full_name }}</h3>
                <div class="bl-muted bl-small">{{ optional($student)->email }}</div>

                <div class="bl-quickstats">
                    <span>{{ trans('bundle_learning.filter_status') }}<b>{{ $statusLabels[$history->status] ?? $history->status }}</b></span>
                    <span>{{ trans('bundle_learning.grade') }}<b>{{ !is_null($history->grade) ? $history->grade : '-' }}/{{ $assignment->grade }}</b></span>
                    <span>{{ trans('bundle_learning.pass_grade') }}<b>{{ $assignment->pass_grade }}</b></span>
                    <span>{{ trans('bundle_learning.attempts') }}<b>{{ $history->messages->where('sender_id', $history->student_id)->whereNotNull('file_path')->count() }}{{ !empty($assignment->attempts) ? '/' . $assignment->attempts : '' }}</b></span>
                </div>

                <form method="post" action="/panel/bundles/{{ $bundle->id }}/grading/{{ $history->id }}" class="mt-16">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="form-group-label is-required">{{ trans('bundle_learning.grade') }} (0 - {{ $maxGrade }})</label>
                        <input type="number" name="grade" min="0" max="{{ $maxGrade }}" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade', $history->grade) }}" required>
                        @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('bundle_learning.comment') }}</label>
                        <textarea name="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" placeholder="{{ trans('bundle_learning.comment_hint') }}">{{ old('comment') }}</textarea>
                        @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bl-btn bl-btn--primary w-100">{{ trans('bundle_learning.save_grade') }}</button>
                    @if(!empty($nextPending))
                        <button type="submit" name="next" value="1" class="bl-btn w-100">{{ trans('bundle_learning.save_and_next') }} →</button>
                    @endif
                </form>

                <a href="/panel/bundles/{{ $bundle->id }}/grading" class="bl-btn w-100">← {{ trans('bundle_learning.back_to_list') }}</a>
            </aside>
        </div>
    </div>
@endsection
