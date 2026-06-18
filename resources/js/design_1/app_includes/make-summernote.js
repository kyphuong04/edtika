(function ($) {
    "use strict";

    window.makeSummernote = function ($content, cardHeight = null, onChange = undefined, options = {}) {
        const height = cardHeight ? cardHeight : ($content.attr('data-height') ? $content.attr('data-height') : 300);
        const toolbar = options.toolbar || [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['paperSize', ['paperSize']],
        ];

        $content.summernote({
            dialogsInBody: true,
            tabsize: 2,
            height: height,
            placeholder: $content.attr('placeholder'),
            fontNames: [],
            callbacks: {
                onChange: onChange
            },
            toolbar: toolbar,
            ...options
        });
    }

})(jQuery);
