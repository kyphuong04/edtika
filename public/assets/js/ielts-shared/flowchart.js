/**
 * flowchart.js — NGUỒN DUY NHẤT dựng HTML cho 2 dạng sơ đồ điền từ:
 *
 *   flow_chart_completion  (Reading)    : các bước xếp dọc, căn giữa, nối bằng mũi tên ↓
 *   diagram_completion     (Listening)  : nhãn "Diagram Labeling"; mỗi bước là 1 thẻ,
 *                                          tiêu đề sơ đồ nằm trong băng màu phía trên
 *
 * Dữ liệu lưu ở question_data.flowchart:
 *   { title: "From Cocoa Beans to Chocolate",
 *     steps: [ { title: "Seeds", lines: ["classifying, cleaning, and roasting", "stripping their ___"] }, ... ] }
 *
 * Mỗi ___ (2+ dấu gạch dưới) là 1 ô trống, đánh số theo thứ tự đọc: tiêu đề bước rồi tới
 * từng dòng, từ bước đầu tới bước cuối. Số ô = số đáp án = slotCount ở server.
 * HTML trả về vẫn giữ nguyên ___ dạng chữ để IeltsBlanks.render() thay bằng ô nhập.
 *
 * Dùng chung cho: trình tạo đề (xem trước + đếm ô trống), trang làm bài, trang chữa bài.
 */
(function (window) {
    'use strict';

    var TYPES = ['flow_chart_completion', 'diagram_completion'];

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function clean(value) {
        return String(value == null ? '' : value).replace(/\s+/g, ' ').trim();
    }

    function isFlowType(type) {
        return TYPES.indexOf(type) !== -1;
    }

    /** 'chart' = Flow chart Reading (căn giữa); 'steps' = Diagram Listening (thẻ từng bước). */
    function variantOf(type) {
        return type === 'diagram_completion' ? 'steps' : 'chart';
    }

    /** Chuẩn hoá dữ liệu: bỏ dòng / bước rỗng, chấp nhận lines là chuỗi nhiều dòng. */
    function normalize(raw) {
        if (!raw || typeof raw !== 'object') return null;

        var steps = (Array.isArray(raw.steps) ? raw.steps : []).map(function (step) {
            step = step || {};
            var lines = Array.isArray(step.lines)
                ? step.lines
                : String(step.lines == null ? '' : step.lines).split(/\r?\n/);

            return {
                title: clean(step.title),
                lines: lines.map(clean).filter(Boolean),
            };
        }).filter(function (step) {
            return step.title || step.lines.length;
        });

        if (!steps.length) return null;

        return { title: clean(raw.title), steps: steps };
    }

    /** Lấy sơ đồ của 1 câu hỏi (payload trang làm bài / trình tạo đề). */
    function fromQuestion(question) {
        if (!question) return null;
        var data = question.question_data;
        if (typeof data === 'string') {
            try { data = JSON.parse(data); } catch (e) { data = null; }
        }
        return normalize(data && data.flowchart);
    }

    function stepHtml(step, variant) {
        var html = '';
        var lines = step.lines.slice();

        if (variant === 'steps') {
            // "Step 1: Find Portal photo: ___" — nhãn bước đứng đầu dòng đầu tiên.
            var first = '';
            if (step.title) first += '<strong class="ifc-step-label">' + esc(step.title) + '</strong>';
            if (lines.length) first += (first ? ' ' : '') + esc(lines.shift());
            if (first) html += '<div class="ifc-line">' + first + '</div>';
        } else if (step.title) {
            html += '<div class="ifc-step-title">' + esc(step.title) + '</div>';
        }

        lines.forEach(function (line) {
            html += '<div class="ifc-line">' + esc(line) + '</div>';
        });

        if (variant === 'steps') {
            html = '<div class="ifc-step-body">' + html + '</div>';
        }

        return '<div class="ifc-step">' + html + '</div>';
    }

    /**
     * HTML sơ đồ. opts.variant: 'chart' | 'steps'.
     * Trả về '' nếu không có dữ liệu.
     */
    function toHtml(raw, opts) {
        var flow = normalize(raw);
        if (!flow) return '';

        var variant = (opts && opts.variant) === 'steps' ? 'steps' : 'chart';
        var arrow = '<div class="ifc-arrow" aria-hidden="true">&#8595;</div>';

        var body = flow.steps.map(function (step) {
            return stepHtml(step, variant);
        }).join(arrow);

        return '<div class="ifc ifc--' + variant + '">'
            + (flow.title ? '<div class="ifc-title">' + esc(flow.title) + '</div>' : '')
            + '<div class="ifc-flow">' + body + '</div>'
            + '</div>';
    }

    /**
     * HTML đơn giản (không class) để lưu vào question_text — dùng cho các trang
     * chưa biết dạng mới (danh sách câu hỏi, export...). Không dùng để hiển thị sơ đồ.
     */
    function toPlainHtml(raw) {
        var flow = normalize(raw);
        if (!flow) return '';

        var parts = [];
        if (flow.title) parts.push('<p><strong>' + esc(flow.title) + '</strong></p>');

        flow.steps.forEach(function (step, index) {
            if (index > 0) parts.push('<p>&#8595;</p>');
            var rows = [];
            if (step.title) rows.push('<strong>' + esc(step.title) + '</strong>');
            step.lines.forEach(function (line) { rows.push(esc(line)); });
            parts.push('<p>' + rows.join('<br>') + '</p>');
        });

        return parts.join('');
    }

    function countBlanks(raw) {
        var html = toHtml(raw, { variant: 'chart' });
        if (!html) return 0;
        if (window.IeltsBlanks) return window.IeltsBlanks.count(html);
        var matches = html.match(/[_＿‗](?:[\s ]*[_＿‗])+/g);
        return matches ? matches.length : 0;
    }

    window.IeltsFlowchart = {
        TYPES: TYPES,
        isFlowType: isFlowType,
        variantOf: variantOf,
        normalize: normalize,
        fromQuestion: fromQuestion,
        toHtml: toHtml,
        toPlainHtml: toPlainHtml,
        countBlanks: countBlanks,
    };
})(window);
