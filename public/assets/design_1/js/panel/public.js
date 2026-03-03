(function ($) {
    "use strict"

    // Toggle sidebar for mobile
    $('body').on('click', '.js-show-panel-sidebar', function (e) {
        e.preventDefault();
        const $panelSidebar = $('#panelSidebar');
        $panelSidebar.toggleClass('show-sidebar')
    })

    // Toggle sidebar collapse/expand for desktop
    $('body').on('click', '.js-toggle-panel-sidebar', function (e) {
        e.preventDefault();
        const $panelSidebar = $('#panelSidebar');
        $panelSidebar.toggleClass('panel-sidebar--collapsed');
        
        // Save state to localStorage
        const isCollapsed = $panelSidebar.hasClass('panel-sidebar--collapsed');
        localStorage.setItem('panelSidebarCollapsed', isCollapsed ? 'true' : 'false');
        
        // Adjust content width immediately
        adjustContentWidth(isCollapsed);
    })

    // Function to adjust content width
    function adjustContentWidth(isCollapsed) {
        const $content = $('.panel-content');
        const $bottomBar = $('.panel-bottom-bar');
        const isDesktop = window.innerWidth >= 992;
        const sidebarGap = isDesktop ? 28 : 0;
        
        if (isCollapsed) {
            const collapsedOffset = 90 + sidebarGap;
            $content.css({
                'width': 'calc(100vw - ' + collapsedOffset + 'px)',
                'margin-left': collapsedOffset + 'px'
            });
            if ($bottomBar.length) {
                $bottomBar.css({
                    'width': 'calc(100% - ' + collapsedOffset + 'px)',
                    'left': collapsedOffset + 'px'
                });
            }
        } else {
            const expandedOffset = 258 + sidebarGap;
            $content.css({
                'width': 'calc(100vw - ' + expandedOffset + 'px)',
                'margin-left': expandedOffset + 'px'
            });
            if ($bottomBar.length) {
                $bottomBar.css({
                    'width': 'calc(100% - ' + expandedOffset + 'px)',
                    'left': expandedOffset + 'px'
                });
            }
        }
    }

    // Handle sidebar hover for collapsed state
    $('#panelSidebar').on('mouseenter', function() {
        if ($(this).hasClass('panel-sidebar--collapsed')) {
            adjustContentWidth(false);
        }
    }).on('mouseleave', function() {
        if ($(this).hasClass('panel-sidebar--collapsed')) {
            adjustContentWidth(true);
        }
    });

    // Restore sidebar state from localStorage on page load
    $(document).ready(function() {
        const savedState = localStorage.getItem('panelSidebarCollapsed');
        const $panelSidebar = $('#panelSidebar');
        
        if (savedState === 'false') {
            $panelSidebar.removeClass('panel-sidebar--collapsed');
            adjustContentWidth(false);
        } else if (savedState === 'true') {
            $panelSidebar.addClass('panel-sidebar--collapsed');
            adjustContentWidth(true);
        } else {
            // Default collapsed state
            adjustContentWidth(true);
        }
    });
})(jQuery)
