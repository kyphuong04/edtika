@php
    // Determine the base URL for tabs
    if (!empty($edit_new_user) && !empty($user_type) && !empty($user)) {
        // Editing a user from manage page
        $baseUrl = "/panel/manage/{$user_type}/{$user->id}/edit/step";
    } else {
        // User's own settings
        $baseUrl = "/panel/setting/step";
    }
@endphp

<a href="{{ $baseUrl }}/basic_information" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "basic_information") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-note class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('public.basic_information') }}</span>
</a>

<a href="{{ $baseUrl }}/extra_information" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "extra_information") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-note-add class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('public.extra_information') }}</span>
</a>

<a href="{{ $baseUrl }}/financial" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "financial") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-receipt-search class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('public.identity_and_financial') }}</span>
</a>

<a href="{{ $baseUrl }}/images" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "images") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-gallery class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('public.images') }}</span>
</a>

<a href="{{ $baseUrl }}/about" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "about") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-profile class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('public.about') }}</span>
</a>

{{--@if(!$user->isUser())
    <a href="{{ $baseUrl }}/zoom" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "zoom") ? 'active' : '' }}">
        <div class="size-20">
            <x-iconsax-lin-video-octagon class="icons" width="20px" height="20px"/>
        </div>
        <span class="ml-4">{{ trans('update.zoom') }}</span>
    </a>
@endif--}}

<a href="{{ $baseUrl }}/login_history" class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($currentStep == "login_history") ? 'active' : '' }}">
    <div class="size-20">
        <x-iconsax-lin-shield-security class="icons" width="20px" height="20px"/>
    </div>
    <span class="ml-4">{{ trans('update.login_history') }}</span>
</a>
