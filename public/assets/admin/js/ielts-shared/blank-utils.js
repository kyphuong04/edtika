/**
 * blank-utils.js — NGUỒN DUY NHẤT cho việc nhận diện chỗ trống ___.
 *
 * Dùng chung cho:
 *  - Editor tạo đề (đếm blank để bắt giáo viên nhập đủ đáp án)
 *  - Trang làm bài (thay blank bằng ô input)
 * Hai bên PHẢI gọi file này để số ô trống luôn khớp nhau.
 *
 * Quy tắc: chỉ xét nội dung CHỮ (text node) — bỏ qua thuộc tính HTML.
 * Một blank = 2+ ký tự gạch dưới (_ ＿ ‗), cho phép khoảng trắng/&nbsp; xen giữa.
 */
(function (window, document) {
    'use strict';

    var BLANK_SOURCE = '[_\\uFF3F\\u2017](?:[\\s\\u00A0]*[_\\uFF3F\\u2017])+';
    var SKIP_PARENTS = /^(TEXTAREA|SCRIPT|STYLE)$/;
    var BLOCK_SELECTOR = 'p,div,li,td,th,h1,h2,h3,h4,h5,h6,blockquote,pre,table,ul,ol';

    function blankRegex() {
        return new RegExp(BLANK_SOURCE, 'g');
    }

    function parse(html) {
        var tpl = document.createElement('template');
        tpl.innerHTML = html == null ? '' : String(html);
        return tpl;
    }

    function textNodes(root) {
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var nodes = [];
        while (walker.nextNode()) {
            var node = walker.currentNode;
            if (node.parentElement && SKIP_PARENTS.test(node.parentElement.nodeName)) continue;
            nodes.push(node);
        }
        return nodes;
    }

    /** Số chỗ trống trong HTML. */
    function count(html) {
        var total = 0;
        textNodes(parse(html).content).forEach(function (node) {
            var matches = node.nodeValue.match(blankRegex());
            if (matches) total += matches.length;
        });
        return total;
    }

    /**
     * Thay mỗi chỗ trống bằng <input class="exam-blank-input">.
     * Giữ nguyên định dạng markup cũ (renderers.js _dragDropLike regex theo nó).
     */
    function render(html, savedValues, disabled) {
        var tpl = parse(html);
        var index = 0;

        textNodes(tpl.content).forEach(function (node) {
            var text = node.nodeValue;
            var re = blankRegex();
            var match;
            var last = 0;
            var frag = null;

            while ((match = re.exec(text)) !== null) {
                frag = frag || document.createDocumentFragment();

                if (match.index > last) {
                    frag.appendChild(document.createTextNode(text.slice(last, match.index)));
                }

                var input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.setAttribute('class', 'exam-blank-input');
                input.setAttribute('data-blank-index', String(index));
                var value = savedValues && savedValues[index] != null ? String(savedValues[index]) : '';
                input.setAttribute('value', value);
                if (disabled) input.setAttribute('disabled', '');
                frag.appendChild(input);

                index++;
                last = match.index + match[0].length;
            }

            if (!frag) return;
            if (last < text.length) frag.appendChild(document.createTextNode(text.slice(last)));
            node.parentNode.replaceChild(frag, node);
        });

        return { html: tpl.innerHTML, blankCount: index };
    }

    /**
     * Phát hiện 1 dải gạch dưới bị định dạng cắt làm 2 (VD "__<b>__</b>").
     * Theo quy tắc trên, dải này bị tính là 2 blank — cảnh báo giáo viên.
     */
    function hasSplitBlank(html) {
        var nodes = textNodes(parse(html).content);

        for (var i = 0; i < nodes.length - 1; i++) {
            var a = nodes[i];
            var b = nodes[i + 1];

            if (!/[_\uFF3F\u2017][\s\u00A0]*$/.test(a.nodeValue)) continue;
            if (!/^[\s\u00A0]*[_\uFF3F\u2017]/.test(b.nodeValue)) continue;
            if (a.nextSibling && a.nextSibling.nodeName === 'BR') continue;

            var blockA = a.parentElement ? a.parentElement.closest(BLOCK_SELECTOR) : null;
            var blockB = b.parentElement ? b.parentElement.closest(BLOCK_SELECTOR) : null;
            if (blockA === blockB) return true;
        }

        return false;
    }

    window.IeltsBlanks = {
        count: count,
        render: render,
        hasSplitBlank: hasSplitBlank
    };
})(window, document);