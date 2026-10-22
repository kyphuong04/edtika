/**
 * flowchart-builder.js — trình dựng sơ đồ theo từng bước trong trình tạo đề
 * (create_inline_complete_with_groups.blade.php).
 *
 *   flow_chart_completion  : Reading  — "Flow Chart Completion"
 *   diagram_completion     : Listening — "Diagram Labeling (điền từ)"
 *
 * Mỗi bước = tiêu đề (in đậm) + các dòng mô tả; ___ là ô trống. Mũi tên ↓ tự
 * thêm giữa các bước. Bản xem trước và việc đếm ô trống dùng chung
 * ielts-shared/flowchart.js với trang làm bài để số ô luôn khớp nhau.
 *
 * Giao tiếp với file blade:
 *   formHtml(type)        HTML form câu hỏi
 *   reset(form)           về trạng thái 2 bước trống
 *   populate(form, q)     nạp câu hỏi đã có để sửa
 *   collect(form)         đọc + kiểm tra; trả về null (đã báo lỗi) nếu chưa hợp lệ
 *   summaryHtml(q)        dòng tóm tắt trong danh sách câu hỏi
 */
(function (window, document) {
    'use strict';

    var Flow = window.IeltsFlowchart;
    var TYPES = ['flow_chart_completion', 'diagram_completion'];

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function isFlowType(type) {
        return TYPES.indexOf(type) !== -1;
    }

    function isSteps(type) {
        return type === 'diagram_completion';
    }

    function typeOf(form) {
        var root = form && form.querySelector('.ifb');
        return root ? root.getAttribute('data-type') : 'flow_chart_completion';
    }

    /* ------------------------------------------------------------------ */
    /* HTML                                                               */
    /* ------------------------------------------------------------------ */
    function stepHtml(type, index, step) {
        step = step || { title: '', lines: [] };
        var steps = isSteps(type);
        var lines = Array.isArray(step.lines) ? step.lines.join('\n') : String(step.lines || '');

        return ''
            + '<div class="ifb-step" data-ifb-step>'
            + '  <div class="ifb-step-head">'
            + '    <span class="ifb-step-no">Bước <b data-ifb-step-no>' + (index + 1) + '</b></span>'
            + '    <span class="ifb-step-tools">'
            + '      <button type="button" class="btn btn-sm btn-outline-secondary" data-ifb-move="-1" title="Chuyển lên"><i class="fas fa-arrow-up"></i></button>'
            + '      <button type="button" class="btn btn-sm btn-outline-secondary" data-ifb-move="1" title="Chuyển xuống"><i class="fas fa-arrow-down"></i></button>'
            + '      <button type="button" class="btn btn-sm btn-outline-danger" data-ifb-remove title="Xoá bước"><i class="fas fa-trash"></i></button>'
            + '    </span>'
            + '  </div>'
            + '  <div class="form-row">'
            + '    <div class="form-group" style="max-width:280px;">'
            + '      <label class="input-label">' + (steps ? 'Nhãn bước' : 'Tiêu đề bước') + ' <small class="text-muted">(in đậm, có thể bỏ trống)</small></label>'
            + '      <input type="text" class="form-control ifb-step-title" maxlength="200" value="' + esc(step.title) + '"'
            + '             placeholder="' + (steps ? 'VD: Step 1:' : 'VD: Seeds') + '">'
            + '    </div>'
            + '    <div class="form-group flex-fill">'
            + '      <label class="input-label">Nội dung <small class="text-muted">(mỗi dòng 1 ý, gõ ___ ở chỗ cần điền)</small></label>'
            + '      <textarea class="form-control ifb-step-lines" rows="2"'
            + '                placeholder="' + (steps ? 'VD: Find Portal photo: ___ on campus on Open Day.' : 'VD: stripping their ___') + '">' + esc(lines) + '</textarea>'
            + '    </div>'
            + '  </div>'
            + '</div>';
    }

    function formHtml(type) {
        var steps = isSteps(type);
        var label = steps ? 'Diagram Labeling (điền từ)' : 'Flow Chart Completion';

        return ''
            + '<div class="mb-8"><span class="question-type-badge">' + label + '</span></div>'
            + '<div class="ifb" data-type="' + esc(type) + '">'
            + '  <div class="form-row">'
            + '    <div class="form-group flex-fill">'
            + '      <label class="input-label">Tiêu đề sơ đồ</label>'
            + '      <input type="text" class="form-control ifb-title" maxlength="200"'
            + '             placeholder="' + (steps ? 'VD: Open Day Competition' : 'VD: From Cocoa Beans to Chocolate') + '">'
            + '    </div>'
            + '  </div>'
            + '  <div class="alert alert-info py-2 px-3 mb-12">'
            + '    Mỗi bước là 1 ' + (steps ? 'thẻ' : 'khối căn giữa') + '; mũi tên <b>↓</b> tự thêm giữa các bước. '
            + '    Gõ <code>___</code> ở chỗ học viên cần điền — số câu được đánh theo thứ tự từ trên xuống. '
            + '    Dùng <code>/</code> trong ô đáp án nếu có nhiều đáp án được chấp nhận.'
            + '  </div>'
            + '  <div class="ifb-steps" data-ifb-steps></div>'
            + '  <button type="button" class="btn btn-sm btn-outline-primary mb-12" data-ifb-add>'
            + '    <i class="fas fa-plus mr-4"></i> Thêm bước'
            + '  </button>'
            + '  <div class="ifb-preview-wrap">'
            + '    <label class="input-label">Xem trước</label>'
            + '    <div class="ifb-preview" data-ifb-preview></div>'
            + '  </div>'
            + '  <div class="ifb-answers-wrap">'
            + '    <label class="input-label">Đáp án</label>'
            + '    <div class="ifb-answers-summary text-muted" data-ifb-summary></div>'
            + '    <div class="ifb-answers" data-ifb-answers></div>'
            + '  </div>'
            + '</div>'
            + '<div class="form-row">'
            + '  <div class="form-group flex-fill">'
            + '    <label class="input-label">Answer Help</label>'
            + '    <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180"'
            + '              placeholder="Optional hint, model answer, or explanation for review..."></textarea>'
            + '  </div>'
            + '</div>'
            + '<div class="form-row mt-8">'
            + '  <div class="form-group" style="max-width:120px;">'
            + '    <label class="input-label">Points</label>'
            + '    <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">'
            + '  </div>'
            + '</div>';
    }

    /* ------------------------------------------------------------------ */
    /* Đọc / ghi dữ liệu form                                             */
    /* ------------------------------------------------------------------ */
    function readFlow(form) {
        var root = form.querySelector('.ifb');
        if (!root) return { title: '', steps: [] };

        var steps = Array.prototype.map.call(root.querySelectorAll('[data-ifb-step]'), function (stepEl) {
            return {
                title: stepEl.querySelector('.ifb-step-title').value,
                lines: stepEl.querySelector('.ifb-step-lines').value.split(/\r?\n/),
            };
        });

        return { title: root.querySelector('.ifb-title').value, steps: steps };
    }

    function readAnswerValues(form) {
        return Array.prototype.map.call(form.querySelectorAll('.ifb-answer-input'), function (input) {
            return input.value;
        });
    }

    function splitVariants(value) {
        return String(value == null ? '' : value)
            .split(/\s*\/\s*/)
            .map(function (v) { return v.trim(); })
            .filter(Boolean);
    }

    /** Ngữ cảnh ngắn của từng ô trống: "stripping their ___" -> hiện cạnh ô đáp án. */
    function blankContexts(flow) {
        var normalized = Flow.normalize(flow);
        var out = [];
        if (!normalized) return out;

        var re = /[_＿‗](?:[\s ]*[_＿‗])+/g;
        // Cùng thứ tự với IeltsFlowchart.toHtml(): tiêu đề sơ đồ, rồi từng bước (tiêu đề -> các dòng).
        [{ title: normalized.title, lines: [] }].concat(normalized.steps).forEach(function (step) {
            [step.title].concat(step.lines).forEach(function (text) {
                if (!text) return;
                var m;
                re.lastIndex = 0;
                while ((m = re.exec(text)) !== null) {
                    var before = text.slice(Math.max(0, m.index - 28), m.index).trim();
                    var after = text.slice(m.index + m[0].length, m.index + m[0].length + 20).trim();
                    out.push((m.index > 28 ? '…' : '') + before + ' ___ ' + after);
                }
            });
        });
        return out;
    }

    function renumberSteps(form) {
        Array.prototype.forEach.call(form.querySelectorAll('[data-ifb-step-no]'), function (el, i) {
            el.textContent = String(i + 1);
        });
    }

    /** Cập nhật xem trước + danh sách ô đáp án; giữ nguyên đáp án đã gõ theo vị trí. */
    function refresh(form, answerValues) {
        var root = form.querySelector('.ifb');
        if (!root) return;

        var type = typeOf(form);
        var flow = readFlow(form);
        var values = Array.isArray(answerValues) ? answerValues : readAnswerValues(form);

        // Xem trước: ô trống đánh số 1, 2, 3… theo thứ tự (số câu thật tính khi lưu đề).
        var html = Flow.toHtml(flow, { variant: Flow.variantOf(type) });
        var index = 0;
        var preview = root.querySelector('[data-ifb-preview]');
        preview.innerHTML = html
            ? html.replace(/[_＿‗](?:[\s ]*[_＿‗])+/g, function () {
                index++;
                return '<span class="ifc-preview-blank"><b>' + index + '</b>chỗ trống</span>';
            })
            : '<div class="text-muted" style="font-size:13px;">Nhập nội dung các bước để xem trước sơ đồ.</div>';

        var contexts = blankContexts(flow);
        var summary = root.querySelector('[data-ifb-summary]');
        summary.innerHTML = contexts.length
            ? 'Phát hiện <strong>' + contexts.length + '</strong> chỗ trống. Nhập đáp án cho từng ô.'
            : 'Chưa có chỗ trống nào — gõ <code>___</code> trong nội dung bước.';

        root.querySelector('[data-ifb-answers]').innerHTML = contexts.map(function (ctx, i) {
            return ''
                + '<div class="ifb-answer d-flex align-items-center mb-2" style="gap:8px;width:100%;">'
                + '  <span class="ifb-answer-no">' + (i + 1) + '</span>'
                + '  <span class="ifb-answer-ctx" title="' + esc(ctx) + '">' + esc(ctx) + '</span>'
                + '  <input type="text" class="form-control form-control-sm ifb-answer-input" data-blank-index="' + i + '"'
                + '         placeholder="Đáp án ô ' + (i + 1) + ' (VD: outer shells / shells)" value="' + esc(values[i] || '') + '">'
                + '</div>';
        }).join('');
    }

    function setSteps(form, steps) {
        var root = form.querySelector('.ifb');
        if (!root) return;
        var type = typeOf(form);
        var list = root.querySelector('[data-ifb-steps]');
        list.innerHTML = steps.map(function (step, i) { return stepHtml(type, i, step); }).join('');
    }

    function reset(form) {
        var root = form && form.querySelector('.ifb');
        if (!root) return;
        root.querySelector('.ifb-title').value = '';
        setSteps(form, [{ title: '', lines: [] }, { title: '', lines: [] }]);
        refresh(form, []);
    }

    function answerGroupsOf(question) {
        var groups = question && question.correctAnswerGroups;
        if (Array.isArray(groups) && groups.length) {
            return groups.map(function (g) { return (Array.isArray(g) ? g : splitVariants(g)).join(' / '); });
        }

        var raw = question ? (question.correctAnswers || question.correctAnswer) : null;
        if (typeof raw === 'string') {
            try { raw = JSON.parse(raw); } catch (e) { raw = raw ? [raw] : []; }
        }
        if (!Array.isArray(raw)) return [];

        return raw.map(function (item) {
            return Array.isArray(item) ? item.join(' / ') : String(item == null ? '' : item);
        });
    }

    function populate(form, question) {
        var root = form && form.querySelector('.ifb');
        if (!root) return;

        var flow = Flow.fromQuestion(question) || { title: '', steps: [] };
        root.querySelector('.ifb-title').value = flow.title || '';
        setSteps(form, flow.steps.length ? flow.steps : [{ title: '', lines: [] }]);
        refresh(form, answerGroupsOf(question));
    }

    /**
     * Đọc form để lưu. Trả về:
     *   { flowchart, text (HTML dự phòng cho question_text), plain, answers: ["a / b", ...], answerGroups: [["a","b"], ...] }
     * hoặc null nếu chưa hợp lệ (đã alert).
     */
    function collect(form) {
        var flow = Flow.normalize(readFlow(form));

        if (!flow) {
            window.alert('Sơ đồ chưa có nội dung. Hãy nhập ít nhất 1 bước.');
            return null;
        }

        var blankCount = Flow.countBlanks(flow);
        if (blankCount === 0) {
            window.alert('Sơ đồ chưa có chỗ trống nào. Gõ ___ ở chỗ học viên cần điền.');
            return null;
        }

        var answerGroups = readAnswerValues(form).map(splitVariants);
        var missing = [];
        for (var i = 0; i < blankCount; i++) {
            if (!answerGroups[i] || !answerGroups[i].length) missing.push(i + 1);
        }
        if (missing.length) {
            window.alert('Còn thiếu đáp án cho ô: ' + missing.join(', ') + '.\n'
                + 'Ô không có đáp án sẽ bị chấm sai cho mọi học viên.');
            return null;
        }

        answerGroups = answerGroups.slice(0, blankCount);
        var text = Flow.toPlainHtml(flow);

        return {
            flowchart: flow,
            text: text,
            plain: text.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim(),
            answers: answerGroups.map(function (g) { return g.join(' / '); }),
            answerGroups: answerGroups,
        };
    }

    function summaryHtml(question) {
        var flow = Flow.fromQuestion(question);
        var steps = flow ? flow.steps.length : 0;
        var answers = answerGroupsOf(question);
        var blanks = answers.length || (flow ? Flow.countBlanks(flow) : 0);

        return '<div style="font-size:12px;margin-top:4px;">'
            + '<span class="question-answer-badge">Sơ đồ: ' + steps + ' bước, ' + blanks + ' ô trống</span>'
            + (answers.length
                ? '<span style="margin-left:6px;color:#16a34a;">✓ ' + answers.map(esc).join(', ') + '</span>'
                : '')
            + '</div>';
    }

    /* ------------------------------------------------------------------ */
    /* Sự kiện (uỷ quyền ở document — form được dựng lại nhiều lần)       */
    /* ------------------------------------------------------------------ */
    document.addEventListener('click', function (event) {
        var root = event.target.closest && event.target.closest('.ifb');
        if (!root) return;
        var form = root.parentNode;

        if (event.target.closest('[data-ifb-add]')) {
            event.preventDefault();
            var list = root.querySelector('[data-ifb-steps]');
            var count = list.querySelectorAll('[data-ifb-step]').length;
            list.insertAdjacentHTML('beforeend', stepHtml(typeOf(form), count, null));
            var added = list.lastElementChild && list.lastElementChild.querySelector('.ifb-step-title');
            if (added) added.focus();
            refresh(form);
            return;
        }

        var removeBtn = event.target.closest('[data-ifb-remove]');
        if (removeBtn) {
            event.preventDefault();
            var steps = root.querySelectorAll('[data-ifb-step]');
            if (steps.length <= 1) {
                window.alert('Sơ đồ cần ít nhất 1 bước.');
                return;
            }
            removeBtn.closest('[data-ifb-step]').remove();
            renumberSteps(form);
            // Xoá bước có thể làm lệch thứ tự ô trống -> giữ đáp án theo vị trí, người soạn tự kiểm tra lại.
            refresh(form);
            return;
        }

        var moveBtn = event.target.closest('[data-ifb-move]');
        if (moveBtn) {
            event.preventDefault();
            var step = moveBtn.closest('[data-ifb-step]');
            var dir = parseInt(moveBtn.getAttribute('data-ifb-move'), 10);
            var sibling = dir < 0 ? step.previousElementSibling : step.nextElementSibling;
            if (!sibling) return;
            if (dir < 0) sibling.before(step); else sibling.after(step);
            renumberSteps(form);
            refresh(form);
        }
    });

    document.addEventListener('input', function (event) {
        var target = event.target;
        if (!target.classList) return;
        if (!target.classList.contains('ifb-title')
            && !target.classList.contains('ifb-step-title')
            && !target.classList.contains('ifb-step-lines')) return;

        var root = target.closest('.ifb');
        if (root) refresh(root.parentNode);
    });

    window.IeltsFlowBuilder = {
        TYPES: TYPES,
        isFlowType: isFlowType,
        formHtml: formHtml,
        reset: reset,
        populate: populate,
        collect: collect,
        summaryHtml: summaryHtml,
    };
})(window, document);
