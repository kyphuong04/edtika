@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp


<div id="panelSidebar" class="panel-sidebar panel-sidebar--collapsed bg-white">
    <div class="panel-sidebar__contents bg-white {{ (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : '' }}" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <x-iconsax-lin-add class="icons text-dark close-icon" width="24px" height="24px"/>
        </div>

        {{-- Logo in sidebar --}}
        <div class="panel-sidebar__logo-wrap" style="padding: 16px 20px 4px; overflow: hidden; flex-shrink: 0;">
            <a href="/" style="display: block;">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" style="max-width:100%; height:auto; max-height:40px; object-fit:contain;" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif
                @if(!empty($generalSettings['dark_mode_logo']))
                    <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" style="max-width:100%; height:auto; max-height:40px; object-fit:contain;" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif
            </a>
        </div>

        <div id="sidebarAccordions" class="pb-240">
            {{-- Menu Items --}}
            @include('design_1.panel.includes.sidebar.items')
        </div>
    </div>


    

</div>

