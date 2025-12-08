@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp


<div id="panelSidebar" class="panel-sidebar panel-sidebar--collapsed bg-white">
    <div class="panel-sidebar__contents bg-white {{ (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : '' }}" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <x-iconsax-lin-add class="icons text-dark close-icon" width="24px" height="24px"/>
        </div>

        <div id="sidebarAccordions" class="mt-20">
            {{-- Menu Items --}}
            @include('design_1.panel.includes.sidebar.items')
        </div>
    </div>

</div>