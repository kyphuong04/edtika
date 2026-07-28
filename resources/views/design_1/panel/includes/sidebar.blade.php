@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp


<div id="panelSidebar" class="panel-sidebar bg-white">
    <div class="panel-sidebar__contents bg-white {{ (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : '' }}">

        <!-- <div onclick="alert('BẤM ĐƯỢC RỒI!'); document.getElementById('panelSidebar').style.transform = 'translateX(-100%)';" 
            style="position: absolute; top: 10px; right: 10px; z-index: 999999; background: red; padding: 15px; border-radius: 8px; cursor: pointer;" 
            class="d-flex d-lg-none">
            <span style="color: white; font-weight: bold;">X ĐÓNG TẠM</span>
        </div> -->

        <div onclick="document.getElementById('panelSidebar').style.transform = 'translateX(-100%)'; document.body.classList.remove('panel-sidebar-open');" 
            class="cursor-pointer d-flex d-lg-none align-items-center justify-content-center"
            style="position: absolute; top: 15px; right: 15px; z-index: 99999; width: 40px; height: 40px; background: #f3f4f6; border-radius: 50%;">
            
            <svg style="pointer-events: none;" width="24px" height="24px" class="icons text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            
        </div>

        {{-- Logo in sidebar (always visible, outside scroll) --}}
        <div class="panel-sidebar__logo-wrap" style="padding: 16px 20px 4px; overflow: hidden; flex-shrink: 0;">
            <a href="/" style="display: inline-block; text-decoration: none;">
                <span style="font-size: 52px; line-height: 1; font-weight: 900; color: #4C1D95; letter-spacing: -0.03em;">EDTIKA</span>
            </a>
        </div>

        {{-- Switch courses button below logo for selected roles --}}
        @include('design_1.panel.includes.switch_courses')

        {{-- Scrollable main menu area --}}
        <div class="panel-sidebar__scroll-area">
            <div id="sidebarAccordions">
                {{-- Menu Items (Communications pinned at bottom for students; shown inline for admin/teacher) --}}
                @include('design_1.panel.includes.sidebar.items', ['excludeSections' => array_merge(($authUser->isAdmin() || $authUser->isTeacher()) ? [] : ['communications'], ['support'])])
            </div>
        </div>

        {{-- Pinned bottom: Communications + User (always visible, no scroll needed) --}}
        <div class="panel-sidebar__pinned-bottom">
            <div id="sidebarBottomAccordions">
                @include('design_1.panel.includes.sidebar.bottom_items')
            </div>
        </div>

    </div>
</div>

