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
            min-width: 160px;
        }
        .upp-avatar-wrap {
            width: 140px;
            height: 140px;
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
        .up-modal .modal-dialog { max-width: 680px; }
        .up-modal .modal-content { border-radius: 20px; border: none; }
        .up-modal .modal-header { border-bottom: none; padding: 28px 28px 8px; }
        .up-modal .modal-body { padding: 0 28px 28px; }
        .up-modal .modal-title { font-size: 18px; font-weight: 700; color: #111827; }

        .up-modal-avatar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 140px;
        }
        .up-modal-avatar-wrap {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            background: #f3f4f6;
            border: 2px solid #d1d5db;
        }
        .up-modal-avatar-wrap img { width:100%; height:100%; object-fit:cover; }

        .up-modal-update-btn {
            margin-top: 14px;
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 7px 22px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .up-modal-update-btn:hover { background: #374151; }

        .up-modal-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 20px;
            flex: 1;
        }
        .up-modal-field-label {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
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

        /* Device card */
        .device-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row g-24">

        {{-- ===================== LEFT COLUMN ===================== --}}
        <div class="col-12 col-lg-7">

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
                    <div class="upp-info-grid">
                        <div>
                            <p class="upp-field-label">{{ trans('auth.name') }}</p>
                            <p class="upp-field-value">{{ $user->full_name ?? 'â€”' }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('public.email') }}</p>
                            <p class="upp-field-value">{{ $user->email ?? 'â€”' }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('public.phone') }}</p>
                            <p class="upp-field-value">{{ $user->mobile ?? 'â€”' }}</p>
                        </div>
                        <div>
                            <p class="upp-field-label">{{ trans('update.gender') }}</p>
                            <p class="upp-field-value">
                                @if(!empty($user->gender))
                                    {{ $user->gender == 'male' ? trans('update.man') : trans('update.woman') }}
                                @else
                                    â€”
                                @endif
                            </p>
                        </div>
                        @if(!empty($user->bio))
                        <div class="upp-bio-block">
                            <p class="upp-field-label">{{ trans('panel.bio') }}</p>
                            <p class="upp-field-value">{{ $user->bio }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>{{-- /upp-card --}}


            {{-- Courses Grid --}}
            <div class="bg-white p-24 rounded-24 border-gray-200 mt-24">
                <h4 class="font-16 font-weight-bold text-dark mb-16">{{ trans('update.my_courses') }}</h4>

                @if($purchasedCourses->isNotEmpty())
                    <div class="row g-16">
                        @foreach($purchasedCourses as $course)
                            <div class="col-6 col-lg-4">
                                <div class="course-card-profile">
                                    <a href="{{ $course->getLearningPageUrl() }}" target="_blank">
                                        <img src="{{ $course->getIcon() }}" alt="{{ $course->title }}">
                                    </a>
                                    <div class="p-12">
                                        <h6 class="font-13 font-weight-bold text-dark text-ellipsis" title="{{ $course->title }}">
                                            {{ truncate($course->title, 30) }}
                                        </h6>
                                        @if(!empty($course->teacher))
                                            <p class="font-11 text-gray-500 mt-4">{{ trans('public.by') }} {{ $course->teacher->full_name }}</p>
                                        @endif
                                        @php $courseProgress = $course->getProgress(true); @endphp
                                        <div class="mt-8">
                                            <div class="progress-card d-flex bg-gray-100" style="border-radius:4px;height:4px;">
                                                <div class="progress-bar bg-primary" style="width:{{ $courseProgress }}%;border-radius:4px;"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4">
                                                <span class="font-11 text-gray-500">{{ $courseProgress }}%</span>
                                                <a href="{{ $course->getLearningPageUrl() }}" target="_blank" class="font-11 text-primary font-weight-bold">
                                                    {{ trans('update.continue_learning') }} &rarr;
                                                </a>
                                            </div>
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

        {{-- ===================== RIGHT COLUMN ===================== --}}
        <div class="col-12 col-lg-5">

            {{-- Continue Learning --}}
            <div class="bg-white p-24 rounded-24 border-gray-200">
                <h4 class="font-16 font-weight-bold text-dark mb-16">{{ trans('update.in_progress') }}</h4>
                @if(!empty($continueLearningCourse))
                    @php $clProgress = $continueLearningCourse->getProgress(true); @endphp
                    <div class="d-flex gap-12 align-items-start">
                        <img src="{{ $continueLearningCourse->getIcon() }}" alt="" style="width:72px;height:72px;border-radius:10px;object-fit:cover;flex-shrink:0;">
                        <div class="flex-1 min-w-0">
                            <h5 class="font-14 font-weight-bold text-dark text-ellipsis">{{ truncate($continueLearningCourse->title, 40) }}</h5>
                            @if(!empty($continueLearningCourse->teacher))
                                <span class="font-12 text-gray-500">{{ trans('public.by') }} {{ $continueLearningCourse->teacher->full_name }}</span>
                            @endif
                            <div class="mt-12">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <span class="font-11 text-gray-500">{{ trans('update.progress') }}</span>
                                    <span class="font-11 font-weight-bold text-primary">{{ $clProgress }}%</span>
                                </div>
                                <div class="progress-card d-flex bg-gray-100" style="border-radius:6px;height:6px;">
                                    <div class="progress-bar bg-primary" style="width:{{ $clProgress }}%;border-radius:6px;"></div>
                                </div>
                            </div>
                            <a href="{{ $continueLearningCourse->getLearningPageUrl() }}" target="_blank" class="btn btn-primary btn-sm w-100 mt-12">
                                {{ trans('update.continue_learning') }} &rarr;
                            </a>
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
            <div class="bg-white p-24 rounded-24 border-gray-200 mt-24">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <h4 class="font-16 font-weight-bold text-dark mb-0">{{ trans('update.course_notes') }}</h4>
                    @if(!empty($recentNotes) && $recentNotes->count() > 3)
                        <button type="button" id="js-toggle-all-notes" class="font-13 text-primary border-0 bg-transparent p-0" style="cursor:pointer;">{{ trans('panel.view_all') }}</button>
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
                                $notePreview = truncate(strip_tags($note->note), 120);
                            @endphp
                            @if($noteUrl)
                            <a href="{{ $noteUrl }}" class="d-block p-12 rounded-10 text-dark js-profile-note-item{{ $noteIndex >= 3 ? ' d-none' : '' }}" style="background:#f9fafb;border:1px solid #e5e7eb;text-decoration:none;">
                                <p class="font-13 text-dark mb-4">{{ $notePreview }}</p>
                                <span class="font-11 text-gray-400">{{ dateTimeFormat($note->created_at, 'j M Y') }}</span>
                            </a>
                            @else
                            <div class="p-12 rounded-10 js-profile-note-item{{ $noteIndex >= 3 ? ' d-none' : '' }}" style="background:#f9fafb;border:1px solid #e5e7eb;">
                                <p class="font-13 text-dark mb-4">{{ $notePreview }}</p>
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
            <div class="bg-white p-24 rounded-24 border-gray-200 mt-24">
                <h4 class="font-16 font-weight-bold text-dark mb-16">{{ trans('update.login_history') }}</h4>
                @if(!empty($userLoginHistories) && $userLoginHistories->isNotEmpty())
                    <div class="d-flex flex-column gap-10">
                        @foreach($userLoginHistories->take(5) as $session)
                            <div class="device-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="d-flex-center size-36 rounded-10 bg-gray-100">
                                            <x-iconsax-bul-monitor class="icons text-gray-500" width="18px" height="18px"/>
                                        </div>
                                        <div>
                                            <p class="font-13 font-weight-bold text-dark mb-2">
                                                {{ $session->device ?? ($session->os ?? trans('update.unknown_device')) }}
                                            </p>
                                            <p class="font-11 text-gray-500">
                                                {{ $session->browser ?? '' }}
                                                @if(!empty($session->ip)) Â· {{ $session->ip }} @endif
                                                @if(!empty($session->country)) Â· {{ $session->country }} @endif
                                            </p>
                                            <p class="font-11 text-gray-400 mt-2">{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @if(empty($session->session_end_at))
                                        <a href="/panel/users/login-history/{{ $session->id }}/end-session"
                                           data-msg="{{ trans('update.this_device_will_be_logout_from_your_account') }}"
                                           data-confirm="{{ trans('update.end_session') }}"
                                           class="delete-action btn btn-outline-danger btn-sm"
                                           style="white-space:nowrap;">
                                            {{ trans('update.end_session') }}
                                        </a>
                                    @else
                                        <span class="font-11 text-gray-400">{{ trans('update.session_ended_label') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if($userLoginHistories->count() > 5)
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

    </div>{{-- end row --}}
</div>
@endsection

@push('panel_modals')
{{-- ===== EDIT PROFILE MODAL â€” Wireframe image 4 ===== --}}
<div class="modal fade up-modal" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">{{ trans('update.my_profile_and_password') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('public.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{-- ---- Profile Info Form ---- --}}
                <form method="post" id="userSettingForm" action="/panel/setting" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="step" value="basic_information">
                    <input type="hidden" name="next_step" value="0">

                    <div class="d-flex gap-24 flex-wrap align-items-start mb-4">

                        {{-- Left: avatar + Update button --}}
                        <div class="up-modal-avatar-col">
                            <div class="up-modal-avatar-wrap">
                                <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}" id="userProfileImage">
                            </div>
                            <label class="up-modal-update-btn text-center mb-0" for="profileImage" style="cursor:pointer;">
                                {{ trans('update.update') }}
                            </label>
                            <input type="file" name="avatar" id="profileImage" class="d-none" accept="image/*">
                        </div>

                        {{-- Right: profile fields (2-col grid) --}}
                        <div class="up-modal-info-grid flex-1" style="min-width:200px;">
                            {{-- Name --}}
                            <div class="col-span-2" style="grid-column:1/-1;">
                                <p class="up-modal-field-label">{{ trans('auth.name') }}</p>
                                <input type="text" name="full_name" value="{{ $user->full_name }}"
                                       class="form-control form-control-sm @error('full_name') is-invalid @enderror" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Phone --}}
                            <div>
                                <p class="up-modal-field-label">{{ trans('public.phone') }}</p>
                                <div class="register-mobile-form-group position-relative bg-white @error('mobile') is-invalid @enderror">
                                    <div class="d-flex gap-4">
                                        <select name="country_code" class="form-control form-control-sm country-code-select2" style="width:90px;flex-shrink:0;">
                                            @foreach(getCountriesMobileCode() as $country => $code)
                                                <option value="{{ $code }}" @if($code == ($user->country_code ?? '')) selected @endif>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" name="mobile" value="{{ $user->mobile }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                @error('mobile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                            {{-- Language --}}
                            @if(!empty($userLanguages))
                            <div>
                                <p class="up-modal-field-label">{{ trans('auth.language') }}</p>
                                <select name="language" class="form-control form-control-sm">
                                    <option value="">{{ trans('auth.language') }}</option>
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}" @if(mb_strtolower($user->language ?? '') == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            {{-- Bio --}}
                            <div class="up-modal-bio-col">
                                <p class="up-modal-field-label">{{ trans('panel.bio') }}</p>
                                <textarea name="bio" rows="3" class="form-control form-control-sm">{{ $user->bio }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-12">
                        <button type="submit" class="btn btn-primary px-24">{{ trans('update.save_settings') }}</button>
                    </div>
                </form>

                <hr class="up-divider">

                {{-- ---- Change Password Section ---- --}}
                <h6 class="font-16 font-weight-bold text-dark mb-4">{{ trans('update.change_password') }}</h6>

                <form method="post" id="changePasswordForm" action="/panel/setting">
                    @csrf
                    <input type="hidden" name="step" value="basic_information">
                    <input type="hidden" name="next_step" value="0">

                    {{-- Current Password --}}
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.current_password') }}</label>
                        <div class="pwd-field-wrap">
                            <input type="password" name="current_password" id="currentPassword"
                                   class="form-control" placeholder="{{ trans('auth.password') }}">
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
