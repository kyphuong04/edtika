/**
 * explain-builder.js — khung "Giải thích chi tiết" trong form câu hỏi của trình tạo đề
 * (Reading / Listening). Dữ liệu hiện ở sidebar "Giải thích đáp án" trang chữa bài.
 *
 * Mỗi SỐ CÂU nhập riêng (câu 7 chỗ trống = 7 tab). Lưu vào question_data.explain:
 *   { slots: { "0": { translation, analysis, evidence, vocab[], paraphrase{rows[], note},
 *                     wrong{ options{key: html}, common[{answer, reason}] } } } }
 * Xem App\Support\Ielts\ReviewExplanation (server) + review.js AnswerHelp (trang chữa bài).
 *
 * Matching / Diagram / Map labeling: 1 form = nhiều statement = nhiều câu hỏi
 * -> collectMatching() trả mảng theo thứ tự statement, mỗi câu hỏi giữ slot 0.
 *
 * Trình tạo đề gọi:
 *   panelHtml(type, skill)   chèn sau getQuestionFormHTML()
 *   reset(form)              trong resetQuestionForm()
 *   load(form, slots|array)  trong editQuestion()
 *   collect(form)            trong saveQuestionToGroup() -> {slots} | null
 *   collectMatching(form)    -> [slotData|null, ...] theo statement
 */
(function (window, document) {
    'use strict';

    var TFNG = {
        true_false_not_given: ['TRUE', 'FALSE', 'NOT GIVEN'],
        yes_no_not_given: ['YES', 'NO', 'NOT GIVEN'],
    };
    var MCQ = ['multiple_choice_single', 'multiple_choice_multiple'];
    var MATCHING = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings',
        'diagram_labeling', 'map_labeling'];
    var DRAG = ['drag_drop_disappear', 'drag_drop_reuse'];
    var TEXT_BLANK = ['sentence_completion', 'summary_completion', 'note_completion', 'drag_drop_disappear', 'drag_drop_reuse'];
    var FLOW = ['flow_chart_completion', 'diagram_completion'];
    var SKILLS = ['reading', 'listening'];
    var BLANK_RE = /[_＿‗](?:[\s ]*[_＿‗])+/g;

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function plain(html) {
        var box = document.createElement('div');
        box.innerHTML = String(html || '');
        return (box.textContent || '').replace(/ /g, ' ').replace(/\s+/g, ' ').trim();
    }

    function lines(html) {
        var box = document.createElement('div');
        box.innerHTML = String(html || '')
            .replace(/<br\s*\/?>/gi, '\n')
            .replace(/<\/(p|div|li|h\d|tr)>/gi, '\n');
        return (box.textContent || '').replace(/ /g, ' ').split(/\n+/)
            .map(function (l) { return l.replace(/\s+/g, ' ').trim(); }).filter(Boolean);
    }

    function short(text, max) {
        text = String(text || '');
        return text.length > max ? text.slice(0, max - 1) + '…' : text;
    }

    function panelOf(form) {
        return form ? form.querySelector('.eb') : null;
    }

    function stateOf(form) {
        if (!form.__eb) form.__eb = { slots: {}, current: 0 };
        return form.__eb;
    }

    // ── Rich editor (Summernote) của mục Phân tích ─────────────────────
    function getRich(el) {
        if (!el) return '';
        if (typeof window.getAnswerHelpValue === 'function') return window.getAnswerHelpValue(el) || '';
        return el.value || '';
    }

    function setRich(el, value) {
        if (!el) return;
        if (typeof window.setAnswerHelpValue === 'function') window.setAnswerHelpValue(el, value || '');
        else el.value = value || '';
    }

    // ── Ô soạn ngắn (đậm / nghiêng / gạch chân) cho nhận xét & lý do sai ──
    function miniHtml(cls, value, placeholder) {
        return '<div class="eb-mini-wrap">'
            + '<div class="eb-mini-tools">'
            + '<button type="button" data-eb-cmd="bold" title="In đậm (Ctrl+B)"><b>B</b></button>'
            + '<button type="button" data-eb-cmd="italic" title="In nghiêng (Ctrl+I)"><i>I</i></button>'
            + '<button type="button" data-eb-cmd="underline" title="Gạch chân (Ctrl+U)"><u>U</u></button>'
            + '</div>'
            + '<div class="eb-mini ' + cls + '" contenteditable="true" data-placeholder="' + esc(placeholder || '') + '">' + (value || '') + '</div>'
            + '</div>';
    }

    function miniValue(el) {
        if (!el) return '';
        var html = el.innerHTML.replace(/<div><br><\/div>/g, '<br>').trim();
        return plain(html) ? html : '';
    }

    // =====================================================================
    //  Danh sách số câu theo dạng bài (đọc trực tiếp từ form đang soạn)
    // =====================================================================
    function blankContextsFromLines(list) {
        var out = [];
        list.forEach(function (line) {
            var matches = line.match(BLANK_RE) || [];
            matches.forEach(function (_, k) {
                var n = 0;
                out.push(line.replace(BLANK_RE, function () { return n++ === k ? '____' : '…'; }));
            });
        });
        return out;
    }

    function slotList(form) {
        var panel = panelOf(form);
        var type = panel ? panel.dataset.qtype : '';
        var textInput = form.querySelector('.question-text-input');
        var text = textInput && typeof window.getContentEditorValue === 'function'
            ? window.getContentEditorValue(textInput)
            : (textInput ? textInput.value : '');

        if (MATCHING.indexOf(type) !== -1) {
            return Array.prototype.map.call(form.querySelectorAll('.matching-statement-row'), function (row, i) {
                var input = row.querySelector('.matching-statement-text');
                return { label: 'Statement ' + (i + 1), ctx: input ? input.value.trim() : '' };
            });
        }

        if (FLOW.indexOf(type) !== -1) {
            return Array.prototype.map.call(form.querySelectorAll('.ifb-answer-ctx'), function (el, i) {
                return { label: 'Chỗ trống ' + (i + 1), ctx: el.textContent.trim() };
            });
        }

        if (type === 'table_completion') {
            var out = [];
            var heads = Array.prototype.map.call(form.querySelectorAll('.tc-col-title'), function (i) { return i.value.trim(); });
            form.querySelectorAll('.tc-builder-body tr').forEach(function (tr) {
                var cells = Array.prototype.slice.call(tr.querySelectorAll('.tc-cell-text'));
                cells.forEach(function (cellInput, c) {
                    var value = cellInput.value || '';
                    blankContextsFromLines([value.replace(/\s+/g, ' ')]).forEach(function (ctx) {
                        var rowHead = c > 0 && cells[0] ? cells[0].value.trim() : '';
                        out.push({
                            label: 'Chỗ trống ' + (out.length + 1),
                            ctx: (rowHead ? rowHead + ' – ' : '') + (heads[c] ? heads[c] + ': ' : '') + ctx,
                        });
                    });
                });
            });
            return out;
        }

        if (TEXT_BLANK.indexOf(type) !== -1) {
            return blankContextsFromLines(lines(text)).map(function (ctx, i) {
                return { label: 'Chỗ trống ' + (i + 1), ctx: ctx };
            });
        }

        // MCQ, TFNG, Short answer: 1 câu
        return [{ label: 'Câu hỏi', ctx: plain(text) }];
    }

    // =====================================================================
    //  "Vì sao đáp án khác sai" — phương án theo dạng bài
    // =====================================================================
    /** [{ key, label }] các phương án SAI có thể giải thích; null = dạng điền từ. */
    function wrongOptions(form, slotIndex) {
        var type = panelOf(form).dataset.qtype;

        if (MCQ.indexOf(type) !== -1) {
            var out = [];
            var idx = 0;
            form.querySelectorAll('.mc-option-row').forEach(function (row) {
                var input = row.querySelector('input[type="text"]');
                var value = input ? input.value.trim() : '';
                if (!value) return;
                var letter = String.fromCharCode(65 + idx++);
                var mark = row.querySelector('input[type="radio"], input[type="checkbox"]');
                if (!(mark && mark.checked)) out.push({ key: letter, label: letter + '. ' + value });
            });
            return out;
        }

        if (TFNG[type]) {
            var select = form.querySelector('.question-answer-select');
            var key = select ? String(select.value || '').toUpperCase() : '';   // giá trị form: "True" / "Not Given"
            return TFNG[type].filter(function (l) { return l !== key; }).map(function (l) {
                return { key: l, label: 'Không phải ' + l };
            });
        }

        if (MATCHING.indexOf(type) !== -1) {
            var row = form.querySelectorAll('.matching-statement-row')[slotIndex];
            var correct = row && row.querySelector('.matching-correct-select')
                ? String(row.querySelector('.matching-correct-select').value || '').toUpperCase() : '';
            return Array.prototype.map.call(form.querySelectorAll('.matching-option-text'), function (input) {
                var k = input.getAttribute('data-key');
                return { key: k, label: k + (input.value.trim() ? '. ' + input.value.trim() : '') };
            }).filter(function (o) { return String(o.key).toUpperCase() !== correct; });
        }

        if (DRAG.indexOf(type) !== -1) {
            var answer = form.querySelector('.dd-answer-input[data-blank-index="' + slotIndex + '"]');
            var right = answer ? answer.value.trim().toLowerCase() : '';
            var seen = {};
            return Array.prototype.map.call(form.querySelectorAll('.dd-option-input'), function (input) {
                return input.value.trim();
            }).filter(function (v) {
                var k = v.toLowerCase();
                if (!v || k === right || seen[k]) return false;
                seen[k] = true;
                return true;
            }).map(function (v) { return { key: v, label: v }; });
        }

        return null;   // điền từ: "Đáp án sai thường gặp"
    }

    // =====================================================================
    //  Giao diện
    // =====================================================================
    function panelHtml(type, skill) {
        if (SKILLS.indexOf(String(skill || '').toLowerCase()) === -1) return '';
        if (!type || type === 'essay' || type === 'speaking_prompt') return '';

        var isReading = String(skill).toLowerCase() === 'reading';

        return '<div class="eb" data-qtype="' + esc(type) + '" data-skill="' + esc(String(skill).toLowerCase()) + '">'
            + '<button type="button" class="eb-toggle" aria-expanded="false">'
            + '  <span class="eb-toggle-icon"><i class="far fa-lightbulb"></i></span>'
            + '  <span class="eb-toggle-text"><b>Giải thích chi tiết</b> <small>hiện ở sidebar "Giải thích đáp án" của trang chữa bài</small></span>'
            + '  <span class="eb-badge"></span><i class="fas fa-chevron-down eb-chevron"></i>'
            + '</button>'
            + '<div class="eb-body" hidden>'
            + '  <p class="eb-intro">Nhập riêng cho <b>từng số câu</b>. Mục nào để trống sẽ tự ẩn ở trang chữa bài; câu chưa nhập gì vẫn dùng ô <b>Answer Help</b> như cũ.</p>'
            + '  <div class="eb-slotbar"><div class="eb-slots" role="tablist"></div>'
            + '    <button type="button" class="eb-refresh" title="Cập nhật danh sách câu theo nội dung vừa sửa"><i class="fas fa-sync-alt"></i> Cập nhật</button></div>'
            + '  <p class="eb-ctx"></p>'
            + '  <p class="eb-none" hidden>Chưa có câu nào: hãy nhập nội dung câu hỏi (có <code>___</code> cho mỗi chỗ trống) hoặc dựng statement trước, rồi bấm <b>Cập nhật</b>.</p>'
            + '  <div class="eb-fields">'
            + '    <div class="eb-field"><label>Bản dịch câu hỏi</label>'
            + '      <textarea class="form-control eb-translation" rows="2" placeholder="VD: Người viết không khuyến nghị việc bổ sung đa vitamin cho hầu hết mọi người."></textarea></div>'
            + '    <div class="eb-field"><label>Phân tích câu hỏi / Vì sao đây là đáp án đúng</label>'
            + '      <textarea class="form-control eb-analysis js-answer-help-editor" data-height="140" placeholder="Phân tích từ khoá, cách suy ra đáp án..."></textarea></div>'
            + (isReading
                ? '    <div class="eb-field"><label>Câu trích trong bài đọc (bằng chứng)</label>'
                  + '      <textarea class="form-control eb-evidence" rows="2" placeholder="Dán NGUYÊN VĂN câu/cụm trong passage chứa đáp án"></textarea>'
                  + '      <small class="eb-help">Trang chữa bài có nút "Xem vị trí trong bài" — tìm đúng đoạn này trong passage và tô vàng. Phải chép đúng nguyên văn.</small></div>'
                : '    <p class="eb-help eb-help--box"><i class="fas fa-headphones"></i> Listening: phần trích transcript lấy từ đánh dấu đáp án trong <b>Transcript</b> của Part, không cần nhập ở đây.</p>')
            + '    <div class="eb-field"><label>Từ vựng quan trọng</label>'
            + '      <div class="eb-rows eb-vocab"></div>'
            + '      <button type="button" class="eb-add" data-eb-add="vocab"><i class="fas fa-plus"></i> Thêm từ</button></div>'
            + '    <div class="eb-field"><label>Đối chiếu paraphrase</label>'
            + '      <div class="eb-rows eb-para"></div>'
            + '      <button type="button" class="eb-add" data-eb-add="para"><i class="fas fa-plus"></i> Thêm dòng</button>'
            + '      <small class="eb-help">Các cụm ở cột "Trong câu hỏi" và các từ vựng sẽ được tô vàng trong câu hỏi ở trang chữa bài.</small>'
            + '      <label class="eb-sub">Nhận xét paraphrase</label>'
            + miniHtml('eb-para-note', '', 'VD: Cụm "does not recommend" được paraphrase thành "does not advise"...')
            + '    </div>'
            + '    <div class="eb-field eb-wrong-field"><label class="eb-wrong-title"></label><div class="eb-wrong"></div></div>'
            + '  </div>'
            + '</div>'
            + '</div>';
    }

    function rowHtml(kind, a, b) {
        if (kind === 'vocab') {
            return '<div class="eb-row" data-kind="vocab">'
                + '<input type="text" class="form-control form-control-sm eb-a" placeholder="Từ / cụm (VD: not recommend)" value="' + esc(a) + '">'
                + '<input type="text" class="form-control form-control-sm eb-b" placeholder="Nghĩa (VD: không khuyến nghị)" value="' + esc(b) + '">'
                + '<button type="button" class="eb-del" title="Xoá dòng"><i class="fas fa-times"></i></button></div>';
        }
        if (kind === 'para') {
            return '<div class="eb-row" data-kind="para">'
                + '<input type="text" class="form-control form-control-sm eb-a" placeholder="Trong câu hỏi / đáp án" value="' + esc(a) + '">'
                + '<input type="text" class="form-control form-control-sm eb-b" placeholder="Cụm tương ứng trong bài" value="' + esc(b) + '">'
                + '<button type="button" class="eb-del" title="Xoá dòng"><i class="fas fa-times"></i></button></div>';
        }
        // common: đáp án sai thường gặp
        return '<div class="eb-row eb-row--common" data-kind="common">'
            + '<input type="text" class="form-control form-control-sm eb-a" placeholder="Đáp án sai (VD: papers)" value="' + esc(a) + '">'
            + miniHtml('eb-b', b || '', 'Vì sao sai (VD: thừa "s", đề chỉ có 1 tờ giấy)')
            + '<button type="button" class="eb-del" title="Xoá dòng"><i class="fas fa-times"></i></button></div>';
    }

    function hasData(slot) {
        return !!(slot && (slot.translation || slot.analysis || slot.evidence
            || (slot.vocab && slot.vocab.length)
            || (slot.paraphrase && ((slot.paraphrase.rows && slot.paraphrase.rows.length) || slot.paraphrase.note))
            || (slot.wrong && ((slot.wrong.options && Object.keys(slot.wrong.options).length) || (slot.wrong.common && slot.wrong.common.length)))));
    }

    function renderSlots(form) {
        var panel = panelOf(form);
        var st = stateOf(form);
        var list = slotList(form);
        st.count = list.length;
        if (st.current >= list.length) st.current = Math.max(0, list.length - 1);

        panel.querySelector('.eb-slots').innerHTML = list.map(function (s, i) {
            var filled = hasData(st.slots[i]);
            return '<button type="button" role="tab" class="eb-slot' + (i === st.current ? ' is-active' : '') + (filled ? ' is-filled' : '')
                + '" data-eb-slot="' + i + '" title="' + esc(s.ctx) + '">' + esc(list.length > 1 ? String(i + 1) : s.label)
                + (filled ? ' <i class="fas fa-check"></i>' : '') + '</button>';
        }).join('');

        var ctx = list[st.current];
        panel.querySelector('.eb-ctx').innerHTML = ctx
            ? '<b>' + esc(ctx.label) + ':</b> ' + esc(short(ctx.ctx, 220) || '(chưa có nội dung)')
            : '';
        panel.querySelector('.eb-none').hidden = list.length > 0;
        panel.querySelector('.eb-fields').hidden = list.length === 0;
        updateBadge(form);
    }

    function updateBadge(form) {
        var st = stateOf(form);
        var panel = panelOf(form);
        var n = 0;
        var total = st.count || 0;
        for (var i = 0; i < total; i++) if (hasData(st.slots[i])) n++;
        var badge = panel.querySelector('.eb-badge');
        badge.textContent = total ? ('Đã nhập ' + n + '/' + total + ' câu') : '';
        badge.classList.toggle('is-done', total > 0 && n === total);
    }

    function renderFields(form) {
        var panel = panelOf(form);
        var st = stateOf(form);
        var slot = st.slots[st.current] || {};
        var type = panel.dataset.qtype;

        panel.querySelector('.eb-translation').value = slot.translation || '';
        setRich(panel.querySelector('.eb-analysis'), slot.analysis || '');
        var ev = panel.querySelector('.eb-evidence');
        if (ev) ev.value = slot.evidence || '';

        panel.querySelector('.eb-vocab').innerHTML = (slot.vocab || []).map(function (v) { return rowHtml('vocab', v.term, v.meaning); }).join('');
        var para = slot.paraphrase || {};
        panel.querySelector('.eb-para').innerHTML = (para.rows || []).map(function (r) { return rowHtml('para', r.question, r.passage); }).join('');
        panel.querySelector('.eb-para-note').innerHTML = para.note || '';

        var wrong = slot.wrong || {};
        var opts = wrongOptions(form, st.current);
        var title = panel.querySelector('.eb-wrong-title');
        var box = panel.querySelector('.eb-wrong');

        if (opts === null) {
            title.textContent = 'Đáp án sai thường gặp';
            box.innerHTML = '<small class="eb-help">Trang chữa bài đã tự phân tích bài làm (vượt số từ, sai chính tả, số ít/số nhiều, thừa/thiếu từ). Thêm ở đây các đáp án sai hay gặp khác.</small>'
                + '<div class="eb-rows eb-common">' + (wrong.common || []).map(function (c) { return rowHtml('common', c.answer, c.reason); }).join('') + '</div>'
                + '<button type="button" class="eb-add" data-eb-add="common"><i class="fas fa-plus"></i> Thêm đáp án sai</button>';
        } else {
            title.textContent = TFNG[type] ? 'Vì sao không phải các nhãn còn lại'
                : (MCQ.indexOf(type) !== -1 ? 'Vì sao các phương án khác không đúng' : 'Phương án gây nhiễu (chỉ cần nhập các phương án dễ nhầm)');
            var saved = wrong.options || {};
            var find = function (key) {
                var k = String(key).toLowerCase();
                var hit = Object.keys(saved).filter(function (x) { return x.toLowerCase() === k; })[0];
                return hit ? saved[hit] : '';
            };
            box.innerHTML = opts.length
                ? opts.map(function (o) {
                    return '<div class="eb-opt" data-key="' + esc(o.key) + '"><div class="eb-opt-label">' + esc(short(o.label, 140)) + '</div>'
                        + miniHtml('eb-opt-reason', find(o.key), 'Vì sao không chọn phương án này (bỏ trống nếu không cần)') + '</div>';
                }).join('')
                : '<small class="eb-help">Chưa có phương án sai — hãy nhập các phương án và chọn đáp án đúng trước.</small>';
        }
    }

    /** Đọc các ô đang hiện -> state.slots[current]. */
    function saveCurrent(form) {
        var panel = panelOf(form);
        if (!panel || !panel.__rendered) return;
        var st = stateOf(form);
        var slot = {};

        var t = panel.querySelector('.eb-translation').value.trim();
        if (t) slot.translation = t;
        var a = getRich(panel.querySelector('.eb-analysis'));
        if (plain(a) || /<(img|table)/i.test(a)) slot.analysis = a;
        var ev = panel.querySelector('.eb-evidence');
        if (ev && ev.value.trim()) slot.evidence = ev.value.trim();

        var vocab = [];
        panel.querySelectorAll('.eb-vocab .eb-row').forEach(function (row) {
            var term = row.querySelector('.eb-a').value.trim();
            var meaning = row.querySelector('.eb-b').value.trim();
            if (term) vocab.push({ term: term, meaning: meaning });
        });
        if (vocab.length) slot.vocab = vocab;

        var rows = [];
        panel.querySelectorAll('.eb-para .eb-row').forEach(function (row) {
            var q = row.querySelector('.eb-a').value.trim();
            var p = row.querySelector('.eb-b').value.trim();
            if (q || p) rows.push({ question: q, passage: p });
        });
        var note = miniValue(panel.querySelector('.eb-para-note'));
        if (rows.length || note) {
            slot.paraphrase = {};
            if (rows.length) slot.paraphrase.rows = rows;
            if (note) slot.paraphrase.note = note;
        }

        var wrong = {};
        var options = {};
        panel.querySelectorAll('.eb-wrong .eb-opt').forEach(function (opt) {
            var v = miniValue(opt.querySelector('.eb-opt-reason'));
            if (v) options[opt.getAttribute('data-key')] = v;
        });
        if (Object.keys(options).length) wrong.options = options;
        var common = [];
        panel.querySelectorAll('.eb-common .eb-row').forEach(function (row) {
            var ans = row.querySelector('.eb-a').value.trim();
            var reason = miniValue(row.querySelector('.eb-b'));
            var dup = common.some(function (c) { return c.answer.toLowerCase() === ans.toLowerCase() && plain(c.reason) === plain(reason); });
            if (ans && reason && !dup) common.push({ answer: ans, reason: reason });
        });
        if (common.length) wrong.common = common;
        if (wrong.options || wrong.common) slot.wrong = wrong;

        if (hasData(slot)) st.slots[st.current] = slot;
        else delete st.slots[st.current];
        updateBadge(form);
    }

    function render(form) {
        var panel = panelOf(form);
        if (!panel) return;
        renderSlots(form);
        renderFields(form);
        panel.__rendered = true;
    }

    function select(form, index) {
        saveCurrent(form);
        stateOf(form).current = index;
        render(form);
    }

    function setOpen(form, open) {
        var panel = panelOf(form);
        var body = panel.querySelector('.eb-body');
        body.hidden = !open;
        panel.classList.toggle('is-open', open);
        panel.querySelector('.eb-toggle').setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            if (typeof window.initAnswerHelpEditors === 'function') window.initAnswerHelpEditors(panel);
            render(form);
        } else {
            saveCurrent(form);
        }
    }

    function bind(form) {
        var panel = panelOf(form);
        if (!panel || panel.__bound) return;
        panel.__bound = true;

        panel.addEventListener('click', function (e) {
            var t = e.target;

            if (t.closest('.eb-toggle')) {
                e.preventDefault();
                setOpen(form, panel.querySelector('.eb-body').hidden);
                return;
            }

            var slotBtn = t.closest('[data-eb-slot]');
            if (slotBtn) {
                select(form, parseInt(slotBtn.getAttribute('data-eb-slot'), 10) || 0);
                return;
            }

            if (t.closest('.eb-refresh')) {
                saveCurrent(form);
                render(form);
                return;
            }

            var add = t.closest('[data-eb-add]');
            if (add) {
                var kind = add.getAttribute('data-eb-add');
                var list = kind === 'common' ? panel.querySelector('.eb-common') : panel.querySelector('.eb-' + kind);
                list.insertAdjacentHTML('beforeend', rowHtml(kind, '', ''));
                var input = list.lastElementChild.querySelector('input');
                if (input) input.focus();
                return;
            }

            var del = t.closest('.eb-del');
            if (del) {
                del.closest('.eb-row').remove();
                saveCurrent(form);
                renderSlots(form);
                return;
            }

            var cmd = t.closest('[data-eb-cmd]');
            if (cmd) {
                e.preventDefault();
                document.execCommand(cmd.getAttribute('data-eb-cmd'), false, null);
            }
        });

        // Nút B/I/U không lấy mất con trỏ khỏi ô soạn.
        panel.addEventListener('mousedown', function (e) {
            if (e.target.closest('[data-eb-cmd]')) e.preventDefault();
        });

        // Dán vào ô soạn ngắn: chỉ lấy chữ (tránh định dạng rác từ Word).
        panel.addEventListener('paste', function (e) {
            var mini = e.target.closest && e.target.closest('.eb-mini');
            if (!mini) return;
            e.preventDefault();
            var text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
        });

        // Gõ xong rời ô -> cập nhật dấu ✓ của số câu.
        panel.addEventListener('focusout', function () {
            window.setTimeout(function () {
                if (!panel.querySelector('.eb-body').hidden) {
                    saveCurrent(form);
                    var active = panel.querySelector('.eb-slot.is-active');
                    var st = stateOf(form);
                    if (active) {
                        var filled = hasData(st.slots[st.current]);
                        active.classList.toggle('is-filled', filled);
                        var icon = active.querySelector('i');
                        if (filled && !icon) active.insertAdjacentHTML('beforeend', ' <i class="fas fa-check"></i>');
                        if (!filled && icon) icon.remove();
                    }
                }
            }, 0);
        });
    }

    // =====================================================================
    //  API cho trình tạo đề
    // =====================================================================
    function reset(form) {
        var panel = panelOf(form);
        if (!panel) return;
        bind(form);
        form.__eb = { slots: {}, current: 0, count: 0 };
        panel.__rendered = false;
        panel.querySelector('.eb-body').hidden = true;
        panel.classList.remove('is-open');
        panel.querySelector('.eb-toggle').setAttribute('aria-expanded', 'false');
        setRich(panel.querySelector('.eb-analysis'), '');
        panel.querySelector('.eb-badge').textContent = '';
    }

    /** data: {slots:{...}} của 1 câu, hoặc mảng (Matching: mỗi phần tử = slot 0 của 1 statement). */
    function load(form, data) {
        var panel = panelOf(form);
        if (!panel) return;
        reset(form);
        var st = stateOf(form);

        if (Array.isArray(data)) {
            data.forEach(function (slot, i) { if (slot) st.slots[i] = JSON.parse(JSON.stringify(slot)); });
        } else if (data && data.slots) {
            Object.keys(data.slots).forEach(function (k) {
                if (data.slots[k]) st.slots[parseInt(k, 10) || 0] = JSON.parse(JSON.stringify(data.slots[k]));
            });
        }

        st.count = slotList(form).length;
        updateBadge(form);
    }

    function prunedSlots(form) {
        saveCurrent(form);
        var st = stateOf(form);
        var count = slotList(form).length;
        var out = {};
        Object.keys(st.slots).forEach(function (k) {
            var i = parseInt(k, 10);
            if (i >= 0 && i < count && hasData(st.slots[k])) out[String(i)] = st.slots[k];
        });
        return out;
    }

    /** {slots} của câu đang soạn, hoặc null nếu chưa nhập gì. */
    function collect(form) {
        if (!panelOf(form)) return undefined;
        var slots = prunedSlots(form);
        return Object.keys(slots).length ? { slots: slots } : null;
    }

    /** Matching: [ {slots:{0:...}} | null ] theo thứ tự statement trong form. */
    function collectMatching(form) {
        if (!panelOf(form)) return [];
        var slots = prunedSlots(form);
        var count = form.querySelectorAll('.matching-statement-row').length;
        var out = [];
        for (var i = 0; i < count; i++) out.push(slots[String(i)] ? { slots: { 0: slots[String(i)] } } : null);
        return out;
    }

    // =====================================================================
    //  "Tài liệu IELTS liên quan" — form thông tin đề (#reviewResourcesField)
    //  Giá trị thật: hidden input #reviewResourcesInput (JSON [{title, description, url}])
    // =====================================================================
    var ReviewResources = {
        MAX: 5,

        list: function () { return document.getElementById('reviewResourcesList'); },
        input: function () { return document.getElementById('reviewResourcesInput'); },

        rowHtml: function (r) {
            r = r || {};
            return '<div class="rr-row">'
                + '<input type="text" class="form-control form-control-sm rr-title" maxlength="200" placeholder="Tên tài liệu / khoá học" value="' + esc(r.title) + '">'
                + '<input type="text" class="form-control form-control-sm rr-desc" maxlength="400" placeholder="Mô tả ngắn" value="' + esc(r.description) + '">'
                + '<input type="url" class="form-control form-control-sm rr-url" maxlength="500" placeholder="https://..." value="' + esc(r.url) + '">'
                + '<button type="button" class="rr-del" title="Xoá"><i class="fas fa-times"></i></button>'
                + '</div>';
        },

        parse: function (value) {
            if (Array.isArray(value)) return value;
            try {
                var v = JSON.parse(value || '[]');
                return Array.isArray(v) ? v : [];
            } catch (e) {
                return [];
            }
        },

        set: function (value) {
            var list = this.list();
            if (!list) return;
            var rows = this.parse(value).slice(0, this.MAX);
            list.innerHTML = rows.map(this.rowHtml).join('');
            this.sync();
        },

        sync: function () {
            var list = this.list();
            var input = this.input();
            if (!list || !input) return;
            var rows = [];
            list.querySelectorAll('.rr-row').forEach(function (row) {
                var title = row.querySelector('.rr-title').value.trim();
                var url = row.querySelector('.rr-url').value.trim();
                row.querySelector('.rr-url').classList.toggle('is-invalid', !!url && !/^https?:\/\//i.test(url));
                if (title) rows.push({ title: title, description: row.querySelector('.rr-desc').value.trim(), url: url });
            });
            input.value = JSON.stringify(rows);
            var add = document.getElementById('reviewResourcesAdd');
            if (add) add.disabled = list.querySelectorAll('.rr-row').length >= this.MAX;
        },

        init: function () {
            var list = this.list();
            if (!list || list.__rrBound) return;
            list.__rrBound = true;
            var self = this;
            this.set(this.input() ? this.input().value : '[]');

            list.addEventListener('input', function () { self.sync(); });
            list.addEventListener('click', function (e) {
                var del = e.target.closest('.rr-del');
                if (!del) return;
                del.closest('.rr-row').remove();
                self.sync();
                self.input().dispatchEvent(new Event('input', { bubbles: true }));
            });

            var add = document.getElementById('reviewResourcesAdd');
            if (add) {
                add.addEventListener('click', function () {
                    if (list.querySelectorAll('.rr-row').length >= self.MAX) return;
                    list.insertAdjacentHTML('beforeend', self.rowHtml({}));
                    list.lastElementChild.querySelector('.rr-title').focus();
                    self.sync();
                });
            }
        },
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { ReviewResources.init(); });
    } else {
        ReviewResources.init();
    }

    window.IeltsReviewResources = ReviewResources;

    window.IeltsExplainBuilder = {
        panelHtml: panelHtml,
        reset: reset,
        load: load,
        collect: collect,
        collectMatching: collectMatching,
        // để kiểm thử
        _slotList: slotList,
    };
})(window, document);
