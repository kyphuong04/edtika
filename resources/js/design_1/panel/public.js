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
        
        if (isCollapsed) {
            $content.css({
                'width': 'calc(100vw - 70px)',
                'margin-left': '70px'
            });
            if ($bottomBar.length) {
                $bottomBar.css({
                    'width': 'calc(100% - 70px)',
                    'left': '70px'
                });
            }
        } else {
            $content.css({
                'width': 'calc(100vw - 258px)',
                'margin-left': '258px'
            });
            if ($bottomBar.length) {
                $bottomBar.css({
                    'width': 'calc(100% - 258px)',
                    'left': '258px'
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
