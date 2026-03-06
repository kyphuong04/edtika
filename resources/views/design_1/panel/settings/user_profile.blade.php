@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <style>
        /* ===== Profile Page â€“ Wireframe-style redesign ===== */
        .upp-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
        }
        .upp-avatar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 200px;
        }
        .upp-avatar-wrap {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            border: 2px solid #d1d5db;
            flex-shrink: 0;
        }
        .upp-avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upp-edit-btn {
            margin-top: 16px;
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 8px 28px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .upp-edit-btn:hover { background: #374151; color:#fff; }

        .upp-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 32px;
            flex: 1;
        }
        .upp-field-label {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        .upp-field-value {
            font-size: 14px;
            color: #374151;
        }
        .upp-bio-block {
            grid-column: 1 / -1;
        }

        /* ===== Edit Modal ===== */
        .up-modal .modal-dialog { max-width: 820px; }
        .up-modal .modal-content { border-radius: 20px; border: none; }
        .up-modal .modal-header { border-bottom: none; padding: 28px 32px 12px; }
        .up-modal .modal-body { padding: 0 32px 32px; }
        .up-modal .modal-title { font-size: 18px; font-weight: 700; color: #111827; }

        .up-modal-avatar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            min-width: 200px;
        }
        .up-modal-avatar-center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .up-modal-avatar-wrap {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            background: #f3f4f6;
            border: 2px solid #d1d5db;
        }
        .up-modal-avatar-wrap img { width:100%; height:100%; object-fit:cover; }

        .up-modal-update-btn {
            margin-top: 16px;
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 160px;
        }
        .up-modal-update-btn:hover { background: #374151; }

        .up-modal-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
            flex: 1;
            min-width: 0;
        }
        .up-modal-field-label {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }
        .up-modal-field-value {
            font-size: 13px;
            color: #374151;
        }
        .up-modal-bio-col { grid-column: 1 / -1; }

        .up-divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0 20px; }

        /* Password fields with eye icon */
        .pwd-field-wrap {
            position: relative;
        }
        .pwd-field-wrap input {
            padding-right: 44px;
        }
        .pwd-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #9ca3af;
            line-height: 1;
        }
        .pwd-toggle:hover { color: #6b7280; }
        .pwd-toggle svg { width: 18px; height: 18px; }

        /* Courses grid */
        .course-card-profile {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .course-card-profile:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .course-card-profile img { width:100%; height:100px; object-fit:cover; }

        /* Larger avatar in modal */
        /* (handled by .up-modal-avatar-wrap above) */

        /* Note HTML preview */
        .note-html-preview {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .note-html-preview strong, .note-html-preview b { font-weight: 700; }
        .note-html-preview em, .note-html-preview i { font-style: italic; }
        .note-html-preview u { text-decoration: underline; }
        .note-html-preview p { margin-bottom: 0; }

        /* Device card */
        .device-card {
            background: #f3f4f6;
            border: 1.5px solid #d1d5db;
            border-radius: 16px;
            padding: 20px 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 130px;
        }
        .device-card-body {
            flex: 1;
        }
        .device-card-footer {
            display: flex;
            justify-content: center;
            padding-top: 12px;
        }
        .device-delete-btn {
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 6px 22px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .device-delete-btn:hover { background: #374151; }
    </style>
@endpush

@section('content')
<div style="max-width: none; width: 100%;">
    <div style="display: grid; grid-template-columns: 1.6fr 1.1fr; gap: 48px; align-items: start;">
        {{-- ===================== LEFT COLUMN ===================== --}}
        <div style="min-width: 0;">

            {{-- ===== Profile Display Card â€” Wireframe image 3 ===== --}}
            <div class="upp-card">
                <h3 class="font-18 font-weight-bold text-dark mb-24">{{ trans('update.my_profile_and_password') }}</h3>

                <div class="d-flex gap-32 flex-wrap">
                    {{-- Avatar + Edit button --}}
                    <div class="upp-avatar-col">
                        <div class="upp-avatar-wrap">
                            <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}" id="uppAvatarImg">
                        </div>
                        <button type="button" class="upp-edit-btn" data-toggle="modal" data-target="#editProfileModal">
                            {{ trans('public.edit') }}
                        </button>
                    </div>

                    {{-- Info grid --}}
                    @php
                        $cardParts = explode(' ', $user->full_name ?? '', 2);
                        $cardFirst = $cardParts[0] ?? '—';
                        $cardLast  = (isset($cardParts[1]) && $cardParts[1] !== '') ? $cardParts[1] : '—';
                    @endphp
                    <div class="upp-info-grid">
                        <div>
                            <p class="upp-field-label">{{ trans('auth.first_name') }}</p>
                            <p class="upp-field-value">{{ $cardFirst }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('auth.last_name') }}</p>
                            <p class="upp-field-value">{{ $cardLast }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('public.phone') }}</p>
                            <p class="upp-field-value">{{ $user->mobile ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('public.email') }}</p>
                            <p class="upp-field-value">{{ $user->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('update.gender') }}</p>
                            <p class="upp-field-value">
                                @if(!empty($user->gender))
                                    {{ $user->gender == 'male' ? trans('update.man') : trans('update.woman') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="upp-field-label">DOB</p>
                            <p class="upp-field-value">{{ !empty($user->birthday) ? date('j M Y', $user->birthday) : '—' }}</p>
                        </div>
                        <div class="upp-bio-block">
                            <p class="upp-field-label">{{ trans('panel.bio') }}</p>
                            <p class="upp-field-value">{{ $user->bio ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>{{-- /upp-card --}}


            {{-- Courses Grid --}}
            <div class="bg-white p-32 rounded-24 border-gray-200 mt-32" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                @php
                    $allCourseCards = collect();
                    foreach ($purchasedCourses as $c) {
                        $allCourseCards->push(['course' => $c, 'purchased' => true]);
                    }
                    foreach ($suggestedCourses as $c) {
                        $allCourseCards->push(['course' => $c, 'purchased' => false]);
                    }
                @endphp

                @if($allCourseCards->isNotEmpty())
                    <div class="row g-48">
                        @foreach($allCourseCards as $item)
                            @php
                                $c = $item['course'];
                                $isPurchased = $item['purchased'];
                                $sale = $isPurchased ? ($purchaseSales[$c->id] ?? null) : null;
                                $regDate = $sale ? date('d/m/Y', $sale->created_at) : null;
                                $expDate = ($sale && !empty($c->access_days))
                                    ? date('d/m/Y', $c->getExpiredAccessDays($sale->created_at))
                                    : null;
                            @endphp
                            <div class="col-6">
                                <div style="border:1.5px solid #d1d5db;border-radius:20px;padding:24px;background:#f3f4f6;height:100%;display:flex;flex-direction:column;">
                                    {{-- Title --}}
                                    <p class="font-14 font-weight-bold text-dark text-center mb-16" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">{{ $c->title }}</p>

                                    {{-- Info + Button row --}}
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
                                        {{-- Info section (left) --}}
                                        <div style="flex:1;">
                                            @if($isPurchased)
                                                @if($regDate)
                                                    <p class="font-12 text-gray-600 mb-6">Ngày đăng ký: {{ $regDate }}</p>
                                                @endif
                                                @if($expDate)
                                                    <p class="font-12 text-gray-600 mb-0">Ngày hết hạn: {{ $expDate }}</p>
                                                @else
                                                    <p class="font-12 text-gray-600 mb-0">Không hết hạn</p>
                                                @endif
                                            @else
                                                <p class="font-12 text-gray-500 mb-0">Chưa mua khóa học</p>
                                            @endif
                                        </div>

                                        {{-- Button (right) --}}
                                        <div style="flex-shrink:0;">
                                            @if($isPurchased)
                                                <a href="{{ $c->getLearningPageUrl() }}" target="_blank"
                                                   style="border:1.5px solid #374151;border-radius:20px;padding:8px 18px;font-size:13px;font-weight:600;color:#111827;text-decoration:none;white-space:nowrap;display:inline-block;">
                                                    Continue &rarr;
                                                </a>
                                            @else
                                                <a href="/webinars/{{ $c->slug }}" target="_blank"
                                                   style="border:1.5px solid #374151;border-radius:20px;padding:8px 18px;font-size:13px;font-weight:600;color:#111827;text-decoration:none;white-space:nowrap;display:inline-block;">
                                                    Mua ngay &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="d-flex-center flex-column py-32 text-center">
                        <x-iconsax-bul-video-play class="icons text-gray-300" width="40px" height="40px"/>
                        <p class="font-14 text-gray-500 mt-12">{{ trans('update.no_purchased_courses') }}</p>
                        <a href="/courses" class="btn btn-outline-primary btn-sm mt-12">{{ trans('update.browse_courses') }}</a>
                    </div>
                @endif
            </div>

        </div>{{-- end left col --}}

        {{-- ===================== RIGHT COLUMN (narrower) ===================== --}}
        <div style="min-width: 0;">

            {{-- Continue Learning --}}
            <div class="bg-white p-24 rounded-24 border-gray-200">
                <h4 class="font-16 font-weight-bold text-dark mb-16 text-center">{{ trans('update.my_courses') }}</h4>
                @if(!empty($continueLearningCourse))
                    @php
                        $clProgress     = $continueLearningCourse->getProgress(true);
                        $clSessions     = $continueLearningCourse->sessions()->count();
                        $clQuizzes      = $continueLearningCourse->quizzes()->count();
                    @endphp
                    <div style="border:1.5px solid #d1d5db;border-radius:16px;padding:20px 20px 16px;background:#fff;">
                        {{-- Course title --}}
                        <h5 class="font-14 font-weight-bold text-dark mb-16">{{ $continueLearningCourse->title }}</h5>

                        {{-- Stats + button row --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="font-13 text-dark mb-6">Số bài học: <strong>{{ $clSessions }}</strong></p>
                                <p class="font-13 text-dark mb-0">Số đề luyện tập: <strong>{{ $clQuizzes }}</strong></p>
                            </div>
                            <a href="{{ $continueLearningCourse->getLearningPageUrl() }}" target="_blank"
                               style="border:1.5px solid #374151;border-radius:20px;padding:7px 18px;font-size:13px;font-weight:600;color:#111827;text-decoration:none;white-space:nowrap;">
                                Continue &rarr;
                            </a>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-16" style="background:#e5e7eb;border-radius:6px;height:6px;">
                            <div style="width:{{ $clProgress }}%;background:#9ca3af;border-radius:6px;height:6px;"></div>
                        </div>
                    </div>
                @else
                    <div class="d-flex-center flex-column py-24 text-center">
                        <x-iconsax-bul-book-1 class="icons text-gray-300" width="32px" height="32px"/>
                        <p class="font-13 text-gray-500 mt-8">{{ trans('update.no_purchased_courses') }}</p>
                    </div>
                @endif
            </div>

            {{-- Course Notes --}}
            @if(!empty(getFeaturesSettings('course_notes_status')))
            <div class="bg-white p-24 rounded-24 border-gray-200 mt-32">
                <div class="d-flex align-items-center justify-content-center mb-16" style="position:relative;">
                    <h4 class="font-16 font-weight-bold text-dark mb-0">{{ trans('update.course_notes') }}</h4>
                    @if(!empty($recentNotes) && $recentNotes->count() > 3)
                        <button type="button" id="js-toggle-all-notes" class="font-13 text-primary border-0 bg-transparent p-0" style="cursor:pointer; position:absolute; right:0;">{{ trans('panel.view_all') }}</button>
                    @endif
                </div>
                @if(!empty($recentNotes) && $recentNotes->isNotEmpty())
                    <div class="d-flex flex-column gap-10">
                        @foreach($recentNotes as $noteIndex => $note)
                            @php
                                $itemType = $note->getItemType();
                                $noteUrl  = (!empty($note->course) && !empty($itemType))
                                    ? $note->course->getLearningPageUrl() . '?type=' . $itemType . '&item=' . $note->targetable_id
                                    : null;
                            @endphp
                            @if($noteUrl)
                            <a href="{{ $noteUrl }}" class="d-block p-12 rounded-10 text-dark js-profile-note-item{{ $noteIndex >= 3 ? ' d-none' : '' }}" style="background:#f9fafb;border:1px solid #e5e7eb;text-decoration:none;">
                                <div class="font-13 text-dark mb-4 note-html-preview">{!! $note->note !!}</div>
                                <span class="font-11 text-gray-400">{{ dateTimeFormat($note->created_at, 'j M Y') }}</span>
                            </a>
                            @else
                            <div class="p-12 rounded-10 js-profile-note-item{{ $noteIndex >= 3 ? ' d-none' : '' }}" style="background:#f9fafb;border:1px solid #e5e7eb;">
                                <div class="font-13 text-dark mb-4 note-html-preview">{!! $note->note !!}</div>
                                <span class="font-11 text-gray-400">{{ dateTimeFormat($note->created_at, 'j M Y') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="d-flex-center flex-column py-24 text-center">
                        <x-iconsax-bul-note class="icons text-gray-300" width="32px" height="32px"/>
                        <p class="font-13 text-gray-500 mt-8">{{ trans('update.no_course_notes') }}</p>
                    </div>
                @endif
            </div>
            @endif

            <script>
                (function () {
                    var btn = document.getElementById('js-toggle-all-notes');
                    if (!btn) return;
                    var expanded = false;
                    btn.addEventListener('click', function () {
                        expanded = !expanded;
                        document.querySelectorAll('.js-profile-note-item').forEach(function (el, i) {
                            if (i >= 3) {
                                el.classList.toggle('d-none', !expanded);
                            }
                        });
                        btn.textContent = expanded ? 'Show Less' : '{{ trans('panel.view_all') }}';
                    });
                })();
            </script>

            {{-- Login History --}}
            <div class="bg-white p-24 rounded-24 border-gray-200 mt-32">
                <h4 class="font-16 font-weight-bold text-dark mb-16 text-center">{{ trans('update.login_history') }}</h4>
                @if(!empty($userLoginHistories) && $userLoginHistories->isNotEmpty())
                    <div class="d-flex flex-column gap-16" style="max-width: 380px; margin: 0 auto;">
                        @foreach($userLoginHistories->take(2) as $session)
                            <div class="device-card">
                                <div class="device-card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-10 mb-6">
                                        <div class="d-flex-center size-36 rounded-10 bg-white" style="border:1px solid #e5e7eb;">
                                            <x-iconsax-bul-monitor class="icons text-gray-500" width="18px" height="18px"/>
                                        </div>
                                        <p class="font-13 font-weight-bold text-dark mb-0">
                                            {{ $session->device ?? ($session->os ?? trans('update.unknown_device')) }}
                                        </p>
                                    </div>
                                    <p class="font-11 text-gray-500 mb-1">
                                        {{ $session->browser ?? '' }}
                                        @if(!empty($session->ip)) &middot; {{ $session->ip }} @endif
                                        @if(!empty($session->country)) &middot; {{ $session->country }} @endif
                                    </p>
                                    <p class="font-11 text-gray-400">{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</p>
                                </div>
                                <div class="device-card-footer">
                                    @if(empty($session->session_end_at))
                                        <a href="/panel/users/login-history/{{ $session->id }}/end-session"
                                           data-msg="{{ trans('update.this_device_will_be_logout_from_your_account') }}"
                                           data-confirm="{{ trans('update.end_session') }}"
                                           class="delete-action device-delete-btn">
                                            {{ trans('public.delete') }}
                                        </a>
                                    @else
                                        <span class="font-11 text-gray-400 bg-white px-12 py-4 rounded-20" style="border:1px solid #e5e7eb;">{{ trans('update.session_ended_label') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if($userLoginHistories->count() > 2)
                            <a href="/panel/setting/step/login_history" class="font-13 text-primary text-center mt-8 d-block">
                                {{ trans('panel.view_all') }} ({{ $userLoginHistories->count() }})
                            </a>
                        @endif
                    </div>
                @else
                    <div class="d-flex-center flex-column py-24 text-center">
                        <x-iconsax-bul-monitor class="icons text-gray-300" width="32px" height="32px"/>
                        <p class="font-13 text-gray-500 mt-8">{{ trans('update.no_login_history') }}</p>
                    </div>
                @endif
            </div>

        </div>{{-- end right col --}}

    </div>{{-- end grid --}}
</div>{{-- /outer wrapper --}}
@endsection

@push('panel_modals')
{{-- ===== EDIT PROFILE MODAL â€” Wireframe image 4 ===== --}}
<div class="modal fade up-modal" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">{{ trans('update.my_profile_and_password') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('public.close') }}" style="border:none;background:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{-- ---- Profile Info Form ---- --}}
                <form method="post" id="userSettingForm" action="/panel/setting" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="step" value="basic_information">
                    <input type="hidden" name="next_step" value="0">

                    <div>
                        <div class="d-flex gap-24" style="align-items:stretch;">

                            {{-- Left: avatar (click to change) + Update submit button --}}
                            <div class="up-modal-avatar-col">
                                <div class="up-modal-avatar-center">
                                    <div class="up-modal-avatar-wrap" onclick="document.getElementById('profileImage').click()" style="cursor:pointer;" title="{{ trans('update.update') }}">
                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}" id="userProfileImage">
                                    </div>
                                </div>
                                <input type="file" name="avatar" id="profileImage" class="d-none" accept="image/*">
                                {{-- hidden language to preserve value --}}
                                <input type="hidden" name="language" value="{{ $user->language ?? '' }}">
                                <button type="submit" class="up-modal-update-btn">
                                    {{ trans('update.update') }}
                                </button>
                            </div>

                            {{-- Right: profile fields (2-col grid) --}}
                            @php $modalNameParts = explode(' ', $user->full_name ?? '', 2); @endphp
                            <div class="up-modal-info-grid flex-1" style="min-width:0;">
                                {{-- First Name --}}
                                <div>
                                    <p class="up-modal-field-label">{{ trans('auth.first_name') }}</p>
                                    <input type="text" name="first_name"
                                           value="{{ $modalNameParts[0] ?? '' }}"
                                           class="form-control form-control-sm @error('first_name') is-invalid @enderror" required>
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                {{-- Last Name --}}
                                <div>
                                    <p class="up-modal-field-label">{{ trans('auth.last_name') }}</p>
                                    <input type="text" name="last_name"
                                           value="{{ $modalNameParts[1] ?? '' }}"
                                           class="form-control form-control-sm">
                                </div>
                                {{-- Phone --}}
                                <div>
                                    <p class="up-modal-field-label">{{ trans('auth.phone_number') }}</p>
                                    <input type="tel" name="mobile" value="{{ $user->mobile }}"
                                           class="form-control form-control-sm @error('mobile') is-invalid @enderror">
                                    @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                {{-- Email --}}
                                <div>
                                    <p class="up-modal-field-label">{{ trans('public.email') }}</p>
                                    <input type="email" name="email" value="{{ $user->email }}"
                                           class="form-control form-control-sm @error('email') is-invalid @enderror">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                {{-- Gender --}}
                                <div>
                                    <p class="up-modal-field-label">{{ trans('update.gender') }}</p>
                                    <select name="gender" class="form-control form-control-sm">
                                        <option value="">&mdash; {{ trans('public.select') }} &mdash;</option>
                                        <option value="male"   @if(($user->gender ?? '') == 'male')   selected @endif>{{ trans('update.man') }}</option>
                                        <option value="female" @if(($user->gender ?? '') == 'female') selected @endif>{{ trans('update.woman') }}</option>
                                    </select>
                                </div>
                                {{-- DOB --}}
                                <div>
                                    <p class="up-modal-field-label">DOB</p>
                                    <input type="date" name="birthday"
                                           value="{{ !empty($user->birthday) ? date('Y-m-d', $user->birthday) : '' }}"
                                           class="form-control form-control-sm">
                                </div>
                                {{-- Bio --}}
                                <div class="up-modal-bio-col">
                                    <p class="up-modal-field-label">{{ trans('panel.bio') }}</p>
                                    <textarea name="bio" rows="4" class="form-control form-control-sm">{{ $user->bio }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <hr class="up-divider">

                {{-- ---- Change Password Section ---- --}}
                <h6 class="font-16 font-weight-bold text-dark" style="margin-bottom: 24px;">{{ trans('update.change_password') }}</h6>

                <form method="post" id="changePasswordForm" action="/panel/setting" style="padding-top: 8px;">
                    @csrf
                    <input type="hidden" name="step" value="change_password">
                    <input type="hidden" name="next_step" value="0">

                    {{-- Current Password --}}
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.current_password') }}</label>
                        <div class="pwd-field-wrap">
                            <input type="password" name="current_password" id="currentPassword"
                                   class="form-control @error('current_password') is-invalid @enderror" placeholder="{{ trans('auth.password') }}">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <button type="button" class="pwd-toggle" onclick="togglePwd('currentPassword', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('auth.new_password') }}</label>
                        <div class="pwd-field-wrap">
                            <input type="password" name="password" id="newPassword"
                                   class="form-control" placeholder="{{ trans('auth.password') }}">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('newPassword', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.confirm_password') }}</label>
                        <div class="pwd-field-wrap">
                            <input type="password" name="password_confirmation" id="confirmPassword"
                                   class="form-control" placeholder="{{ trans('update.confirm_new_password') }}">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('confirmPassword', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block mt-4"
                            style="background:#4b5563;color:#fff;border-radius:10px;font-weight:600;padding:10px;">
                        {{ trans('update.change_password') }}
                    </button>
                </form>

            </div>{{-- /modal-body --}}
        </div>
    </div>
</div>
@endpush

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var saveErrorLang   = '{{ trans('site.store_error_try_again') }}';
        var saveLang        = '{{ trans('public.save') }}';
        var closeLang       = '{{ trans('public.close') }}';

        /* Avatar live-preview */
        document.getElementById('profileImage').addEventListener('change', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('userProfileImage').src = e.target.result;
                    var main = document.getElementById('uppAvatarImg');
                    if (main) main.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        /* Password eye-toggle */
        function togglePwd(inputId, btn) {
            var input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.style.color = '#374151';
            } else {
                input.type = 'password';
                btn.style.color = '#9ca3af';
            }
        }
    </script>
    <script src="/assets/design_1/js/panel/user_setting.min.js"></script>
@endpush
