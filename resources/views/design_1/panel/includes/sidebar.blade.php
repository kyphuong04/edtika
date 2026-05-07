@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp


<div id="panelSidebar" class="panel-sidebar bg-white">
    <div class="panel-sidebar__contents bg-white {{ (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : '' }}">

        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <x-iconsax-lin-add class="icons text-dark close-icon" width="24px" height="24px"/>
        </div>

        {{-- Logo in sidebar (always visible, outside scroll) --}}
        <div class="panel-sidebar__logo-wrap" style="padding: 16px 20px 4px; overflow: hidden; flex-shrink: 0;">
            <a href="/" style="display: inline-block; text-decoration: none;">
                <span style="font-size: 52px; line-height: 1; font-weight: 900; color: #4C1D95; letter-spacing: -0.03em;">EDTIKA</span>
            </a>
        </div>

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

