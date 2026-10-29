/**
 * rich-table.js — Bảng kiểu Word cho các ô Summernote của trình tạo đề IELTS
 * (Passage, Instructions, Answer help, Hint).
 *
 * Gồm:
 *   - Nút "Bảng" trên thanh công cụ: chọn kích thước bằng lưới (tối đa 10 × 8).
 *   - Thanh "Bảng" hiện ngay dưới thanh công cụ khi con trỏ nằm trong bảng:
 *       chèn hàng / cột, xoá hàng / cột, gộp ô, tách ô, hàng tiêu đề,
 *       nền ô, căn ô (ngang + dọc), kiểu viền, độ rộng bảng, xoá bảng.
 *   - Kéo chuột qua nhiều ô để chọn vùng (giống Word); Delete / Backspace xoá chữ trong vùng.
 *   - Kéo đường viền giữa 2 cột để chỉnh độ rộng cột.
 *   - Dán bảng từ Word / Excel / Google Docs: bỏ định dạng rác, giữ gộp ô, nền ô, chữ đậm.
 *
 * Mọi định dạng được ghi bằng thuộc tính style / colspan / rowspan vì server
 * (IeltsTestInlineController::sanitizeInlineText) xoá hết class và data-*.
 * Các class rt-* chỉ dùng trong lúc soạn và được IeltsRichTable.clean() gỡ trước khi lưu.
 *
 * Dùng:
 *   makeSummernote($el, h, undefined, {
 *       toolbar: [..., ['table', ['rtTable']]],
 *       buttons: Object.assign({}, otherButtons, IeltsRichTable.buttons),
 *       popover: IeltsRichTable.popover(),
 *       callbacks: { onPaste: function (e) { IeltsRichTable.handlePaste(this, e); } }
 *   });
 *   IeltsRichTable.attach($el);   // sau khi Summernote đã khởi tạo
 */
(function (window, $) {
    'use strict';

    if (!$) return;

    // ── Kiểu mặc định (inline style vì class bị server xoá) ─────────────
    var BORDER_COLOR = '#c9c3d9';
    var TABLE_CSS = 'width: 100%; border-collapse: collapse; margin: 8px 0';
    var CELL_CSS = 'border: 1px solid ' + BORDER_COLOR + '; padding: 6px 10px; vertical-align: top';
    var HEAD_BG = '#f1ecfb';

    var GRID_COLS = 10;
    var GRID_ROWS = 8;
    var RESIZE_ZONE = 5;      // px quanh đường viền cột để bắt kéo
    var MIN_COL_PX = 32;

    var CELL_COLORS = [
        '', '#ffffff', '#f1ecfb', '#e0e7ff', '#dbeafe', '#cffafe', '#dcfce7',
        '#fef9c3', '#ffedd5', '#fee2e2', '#fce7f3', '#f3f4f6', '#e5e7eb', '#d1d5db'
    ];

    var BORDER_COLORS = ['#c9c3d9', '#9ca3af', '#1f2937', '#511d99'];

    var editors = [];   // { $note, editable, bar, state }

    // =====================================================================
    //  Tiện ích DOM
    // =====================================================================
    function isCell(node) {
        return !!node && node.nodeType === 1 && (node.tagName === 'TD' || node.tagName === 'TH');
    }

    function closestCell(node, editable) {
        while (node && node !== editable) {
            if (isCell(node)) return node;
            node = node.parentNode;
        }
        return null;
    }

    function tableOf(cell) {
        var node = cell;
        while (node && node.tagName !== 'TABLE') node = node.parentNode;
        return node;
    }

    function span(cell, attr) {
        var v = parseInt(cell.getAttribute(attr), 10);
        return v > 1 ? v : 1;
    }

    function setSpan(cell, attr, value) {
        if (value > 1) cell.setAttribute(attr, String(value));
        else cell.removeAttribute(attr);
    }

    function blankCell(tag, styleFrom) {
        var cell = document.createElement(tag || 'td');
        cell.style.cssText = styleFrom ? cellStyleCopy(styleFrom) : CELL_CSS;
        cell.innerHTML = '<br>';
        return cell;
    }

    /** Sao chép viền / padding / căn dọc của ô mẫu, bỏ nền, độ rộng, chữ đậm. */
    function cellStyleCopy(cell) {
        var probe = document.createElement('td');
        probe.style.cssText = cell.style.cssText;
        probe.style.backgroundColor = '';
        probe.style.width = '';
        probe.style.fontWeight = '';
        probe.style.textAlign = '';
        return probe.style.cssText || CELL_CSS;
    }

    function renameCell(cell, tag) {
        if (cell.tagName === tag.toUpperCase()) return cell;
        var next = document.createElement(tag);
        Array.prototype.forEach.call(cell.attributes, function (a) { next.setAttribute(a.name, a.value); });
        while (cell.firstChild) next.appendChild(cell.firstChild);
        cell.parentNode.replaceChild(next, cell);
        return next;
    }

    function placeCaret(cell) {
        if (!cell) return;
        var range = document.createRange();
        range.selectNodeContents(cell);
        range.collapse(true);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    }

    // =====================================================================
    //  Lưới bảng (xử lý colspan / rowspan)
    // =====================================================================
    /**
     * grid[r][c] = { cell, r0, c0 } — ô phủ vị trí (r, c).
     * Trả về { grid, rows, cols, pos: Map(cell -> {r, c, rs, cs}) }.
     */
    function buildGrid(table) {
        var rows = Array.prototype.slice.call(table.rows);
        var grid = [];
        var pos = new Map();
        var cols = 0;

        rows.forEach(function (tr, r) {
            grid[r] = grid[r] || [];
            var c = 0;
            Array.prototype.forEach.call(tr.cells, function (cell) {
                while (grid[r][c]) c++;
                var rs = Math.min(span(cell, 'rowspan'), rows.length - r);
                var cs = span(cell, 'colspan');
                pos.set(cell, { r: r, c: c, rs: rs, cs: cs });
                for (var i = 0; i < rs; i++) {
                    grid[r + i] = grid[r + i] || [];
                    for (var j = 0; j < cs; j++) {
                        grid[r + i][c + j] = { cell: cell, r0: r, c0: c };
                    }
                }
                c += cs;
                if (c > cols) cols = c;
            });
        });

        grid.forEach(function (row) { if (row.length > cols) cols = row.length; });
        return { grid: grid, rows: rows, cols: cols, pos: pos };
    }

    /** Chèn `cell` vào hàng r tại cột c (trước ô đầu tiên bắt đầu ở cột > c). */
    function insertCellAt(info, r, c, cell) {
        var tr = info.rows[r];
        var before = null;
        Array.prototype.some.call(tr.cells, function (other) {
            var p = info.pos.get(other);
            if (p && p.c > c) { before = other; return true; }
            return false;
        });
        tr.insertBefore(cell, before);
    }

    /** Bù ô cho hàng bị thiếu (bảng dán từ ngoài vào có thể lệch). */
    function fillHoles(table) {
        var info = buildGrid(table);
        for (var r = 0; r < info.rows.length; r++) {
            for (var c = 0; c < info.cols; c++) {
                if (!info.grid[r][c]) {
                    var ref = (info.grid[r][c - 1] || info.grid[r - 1] && info.grid[r - 1][c] || {}).cell;
                    insertCellAt(info, r, c, blankCell('td', ref));
                    info = buildGrid(table);
                }
            }
        }
    }

    /** rowspan vượt quá số hàng còn lại (sau khi xoá hàng) -> cắt về đúng giá trị. */
    function normalizeSpans(table) {
        var info = buildGrid(table);
        info.pos.forEach(function (p, cell) {
            if (span(cell, 'rowspan') !== p.rs) setSpan(cell, 'rowspan', p.rs);
        });
    }

    /** Gỡ hàng rỗng và cột không còn ô nào bắt đầu (sau khi gộp ô). */
    function compact(table) {
        var info = buildGrid(table);

        for (var r = info.rows.length - 1; r >= 0; r--) {
            if (info.rows[r].cells.length) continue;
            var done = new Set();
            (info.grid[r] || []).forEach(function (g) {
                if (g && !done.has(g.cell)) {
                    done.add(g.cell);
                    setSpan(g.cell, 'rowspan', span(g.cell, 'rowspan') - 1);
                }
            });
            info.rows[r].parentNode.removeChild(info.rows[r]);
            info = buildGrid(table);
        }

        for (var c = info.cols - 1; c > 0; c--) {
            var starts = false;
            for (var i = 0; i < info.grid.length; i++) {
                var g = info.grid[i] && info.grid[i][c];
                if (g && g.c0 === c) { starts = true; break; }
            }
            if (starts) continue;
            var seen = new Set();
            info.grid.forEach(function (row) {
                var g2 = row && row[c];
                if (g2 && !seen.has(g2.cell)) {
                    seen.add(g2.cell);
                    setSpan(g2.cell, 'colspan', span(g2.cell, 'colspan') - 1);
                }
            });
            info = buildGrid(table);
        }
    }

    // =====================================================================
    //  Thao tác trên bảng
    // =====================================================================
    var Ops = {
        insertRow: function (cell, below) {
            var table = tableOf(cell);
            var info = buildGrid(table);
            var p = info.pos.get(cell);
            var ref = below ? p.r + p.rs - 1 : p.r;
            var refRow = info.rows[ref];
            var tr = document.createElement('tr');
            var grown = new Set();
            var firstNew = null;

            for (var c = 0; c < info.cols; c++) {
                var g = info.grid[ref][c];
                if (!g) continue;
                var gp = info.pos.get(g.cell);
                var crosses = below ? (gp.r + gp.rs - 1 > ref) : (gp.r < ref);
                if (crosses) {
                    if (!grown.has(g.cell)) {
                        grown.add(g.cell);
                        setSpan(g.cell, 'rowspan', gp.rs + 1);
                    }
                    c = gp.c + gp.cs - 1;
                    continue;
                }
                var nc = blankCell('td', g.cell);
                setSpan(nc, 'colspan', gp.cs);
                if (g.cell.style.width) nc.style.width = g.cell.style.width;
                tr.appendChild(nc);
                if (!firstNew) firstNew = nc;
                c = gp.c + gp.cs - 1;
            }

            // Thêm hàng dưới hàng tiêu đề (<thead>) -> đưa vào đầu <tbody>.
            var section = refRow.parentNode;
            if (below && section.tagName === 'THEAD' && refRow === section.lastElementChild) {
                var body = table.tBodies[0] || table.appendChild(document.createElement('tbody'));
                body.insertBefore(tr, body.firstChild);
            } else if (below) {
                section.insertBefore(tr, refRow.nextSibling);
            } else {
                section.insertBefore(tr, refRow);
            }
            return firstNew;
        },

        insertCol: function (cell, right) {
            var table = tableOf(cell);
            var info = buildGrid(table);
            var p = info.pos.get(cell);
            var ref = right ? p.c + p.cs - 1 : p.c;
            var handled = new Set();
            var inserts = [];
            var focus = null;

            for (var r = 0; r < info.rows.length; r++) {
                var g = info.grid[r][ref];
                if (!g || handled.has(g.cell)) continue;
                handled.add(g.cell);
                var gp = info.pos.get(g.cell);
                var crosses = right ? (gp.c + gp.cs - 1 > ref) : (gp.c < ref);
                if (crosses) {
                    setSpan(g.cell, 'colspan', gp.cs + 1);
                    continue;
                }
                var nc = blankCell(g.cell.tagName.toLowerCase(), g.cell);
                if (g.cell.tagName === 'TH') {
                    nc.style.backgroundColor = g.cell.style.backgroundColor;
                    nc.style.fontWeight = g.cell.style.fontWeight;
                }
                setSpan(nc, 'rowspan', gp.rs);
                inserts.push({ ref: g.cell, cell: nc, after: right });
                if (g.cell === cell) focus = nc;
            }

            inserts.forEach(function (it) {
                it.ref.parentNode.insertBefore(it.cell, it.after ? it.ref.nextSibling : it.ref);
            });
            clearWidths(table);
            return focus;
        },

        deleteRows: function (cells) {
            var table = tableOf(cells[0]);
            var info = buildGrid(table);
            var r1 = Infinity;
            var r2 = -1;
            cells.forEach(function (cell) {
                var p = info.pos.get(cell);
                r1 = Math.min(r1, p.r);
                r2 = Math.max(r2, p.r + p.rs - 1);
            });

            for (var r = r2; r >= r1; r--) {
                info = buildGrid(table);
                var row = info.rows[r];
                var done = new Set();
                for (var c = 0; c < info.cols; c++) {
                    var g = info.grid[r][c];
                    if (!g || done.has(g.cell)) continue;
                    done.add(g.cell);
                    var gp = info.pos.get(g.cell);
                    if (gp.rs === 1) continue;
                    setSpan(g.cell, 'rowspan', gp.rs - 1);
                    if (gp.r === r) {
                        // Ô gộp bắt đầu ở hàng bị xoá -> dời xuống hàng dưới.
                        insertCellAt(info, r + 1, gp.c, g.cell);
                    }
                }
                row.parentNode.removeChild(row);
            }

            if (!table.rows.length) return Ops.deleteTable(table);
            var rest = buildGrid(table);
            var rr = Math.min(r1, rest.rows.length - 1);
            return rest.grid[rr] && rest.grid[rr][0] ? rest.grid[rr][0].cell : null;
        },

        deleteCols: function (cells) {
            var table = tableOf(cells[0]);
            var info = buildGrid(table);
            var c1 = Infinity;
            var c2 = -1;
            cells.forEach(function (cell) {
                var p = info.pos.get(cell);
                c1 = Math.min(c1, p.c);
                c2 = Math.max(c2, p.c + p.cs - 1);
            });

            for (var c = c2; c >= c1; c--) {
                info = buildGrid(table);
                var done = new Set();
                for (var r = 0; r < info.rows.length; r++) {
                    var g = info.grid[r][c];
                    if (!g || done.has(g.cell)) continue;
                    done.add(g.cell);
                    var gp = info.pos.get(g.cell);
                    if (gp.cs > 1) setSpan(g.cell, 'colspan', gp.cs - 1);
                    else g.cell.parentNode.removeChild(g.cell);
                }
            }

            if (!table.querySelector('td, th')) return Ops.deleteTable(table);
            // Hàng không còn ô nhưng vẫn nằm trong rowspan của ô khác -> giảm rowspan rồi mới bỏ hàng.
            compact(table);

            clearWidths(table);
            var rest = buildGrid(table);
            var cc = Math.min(c1, rest.cols - 1);
            return rest.grid[0][cc] ? rest.grid[0][cc].cell : null;
        },

        /** Vùng chữ nhật phủ trọn các ô đã chọn (mở rộng theo ô gộp). */
        rect: function (cells) {
            var table = tableOf(cells[0]);
            var info = buildGrid(table);
            var r1 = Infinity, c1 = Infinity, r2 = -1, c2 = -1;
            cells.forEach(function (cell) {
                var p = info.pos.get(cell);
                if (!p) return;
                r1 = Math.min(r1, p.r); c1 = Math.min(c1, p.c);
                r2 = Math.max(r2, p.r + p.rs - 1); c2 = Math.max(c2, p.c + p.cs - 1);
            });

            var changed = true;
            while (changed) {
                changed = false;
                for (var r = r1; r <= r2; r++) {
                    for (var c = c1; c <= c2; c++) {
                        var g = info.grid[r] && info.grid[r][c];
                        if (!g) continue;
                        var p2 = info.pos.get(g.cell);
                        if (p2.r < r1) { r1 = p2.r; changed = true; }
                        if (p2.c < c1) { c1 = p2.c; changed = true; }
                        if (p2.r + p2.rs - 1 > r2) { r2 = p2.r + p2.rs - 1; changed = true; }
                        if (p2.c + p2.cs - 1 > c2) { c2 = p2.c + p2.cs - 1; changed = true; }
                    }
                }
            }

            var list = [];
            for (var rr = r1; rr <= r2; rr++) {
                for (var cc = c1; cc <= c2; cc++) {
                    var gg = info.grid[rr] && info.grid[rr][cc];
                    if (gg && list.indexOf(gg.cell) === -1) list.push(gg.cell);
                }
            }
            return { table: table, r1: r1, c1: c1, r2: r2, c2: c2, cells: list, info: info };
        },

        merge: function (cells) {
            if (cells.length < 2) return null;
            var box = Ops.rect(cells);
            var main = box.info.grid[box.r1][box.c1].cell;
            var parts = [];

            box.cells.forEach(function (cell) {
                var text = (cell.textContent || '').replace(/\u00a0/g, ' ').trim();
                var hasMedia = !!cell.querySelector('img, table');
                if (cell !== main && (text || hasMedia)) parts.push(cell.innerHTML);
            });

            var mainEmpty = !(main.textContent || '').replace(/\u00a0/g, ' ').trim() && !main.querySelector('img, table');
            if (parts.length) {
                main.innerHTML = (mainEmpty ? '' : main.innerHTML + '<br>') + parts.join('<br>');
            }

            box.cells.forEach(function (cell) {
                if (cell !== main) cell.parentNode.removeChild(cell);
            });
            setSpan(main, 'rowspan', box.r2 - box.r1 + 1);
            setSpan(main, 'colspan', box.c2 - box.c1 + 1);
            main.style.width = '';
            compact(box.table);
            return main;
        },

        split: function (cell) {
            var table = tableOf(cell);
            var info = buildGrid(table);
            var p = info.pos.get(cell);
            if (p.rs === 1 && p.cs === 1) return cell;

            setSpan(cell, 'rowspan', 1);
            setSpan(cell, 'colspan', 1);

            for (var j = 1; j < p.cs; j++) {
                var nc = blankCell(cell.tagName.toLowerCase(), cell);
                if (cell.tagName === 'TH') nc.style.backgroundColor = cell.style.backgroundColor;
                cell.parentNode.insertBefore(nc, cell.nextSibling);
            }

            for (var i = 1; i < p.rs; i++) {
                info = buildGrid(table);
                for (var k = 0; k < p.cs; k++) {
                    insertCellAt(info, p.r + i, p.c + k, blankCell('td', cell));
                    info = buildGrid(table);
                }
            }
            return cell;
        },

        isHeaderOn: function (table) {
            var first = table.rows[0];
            return !!first && Array.prototype.every.call(first.cells, function (c) { return c.tagName === 'TH'; });
        },

        toggleHeader: function (table) {
            var first = table.rows[0];
            if (!first) return null;
            var on = !Ops.isHeaderOn(table);
            var cells = Array.prototype.slice.call(first.cells);
            var focus = null;
            cells.forEach(function (cell) {
                var next = renameCell(cell, on ? 'th' : 'td');
                if (on) {
                    if (!next.style.backgroundColor) next.style.backgroundColor = HEAD_BG;
                    next.style.fontWeight = '700';
                    if (!next.style.textAlign) next.style.textAlign = 'left';
                } else {
                    if (next.style.backgroundColor === hexToRgb(HEAD_BG) || next.style.backgroundColor === HEAD_BG) {
                        next.style.backgroundColor = '';
                    }
                    next.style.fontWeight = '';
                }
                if (!focus) focus = next;
            });
            return focus;
        },

        setBackground: function (cells, color) {
            cells.forEach(function (cell) { cell.style.backgroundColor = color || ''; });
        },

        setAlign: function (cells, h, v) {
            cells.forEach(function (cell) {
                if (h) cell.style.textAlign = h;
                if (v) cell.style.verticalAlign = v;
                // Đoạn văn trong ô có căn lề riêng (nút căn lề của Summernote) -> theo ô.
                if (h) Array.prototype.forEach.call(cell.querySelectorAll('p, div'), function (p) { p.style.textAlign = ''; });
            });
        },

        /** mode: all | outer | horizontal | none */
        setBorders: function (table, mode, color) {
            var line = '1px solid ' + (color || currentBorderColor(table));
            var info = buildGrid(table);
            info.pos.forEach(function (p, cell) {
                ['border', 'borderTop', 'borderRight', 'borderBottom', 'borderLeft'].forEach(function (k) { cell.style[k] = ''; });
                if (mode === 'all') {
                    cell.style.border = line;
                } else if (mode === 'horizontal') {
                    cell.style.border = 'none';
                    cell.style.borderTop = line;
                    cell.style.borderBottom = line;
                } else if (mode === 'outer') {
                    cell.style.border = 'none';
                    if (p.r === 0) cell.style.borderTop = line;
                    if (p.c === 0) cell.style.borderLeft = line;
                    if (p.r + p.rs >= info.rows.length) cell.style.borderBottom = line;
                    if (p.c + p.cs >= info.cols) cell.style.borderRight = line;
                } else {
                    cell.style.border = 'none';
                }
            });
            table.style.border = '';
        },

        setWidth: function (table, full) {
            table.style.width = full ? '100%' : 'auto';
            if (!full) clearWidths(table);
        },

        deleteTable: function (table) {
            var next = table.nextElementSibling || table.previousElementSibling;
            var parent = table.parentNode;
            parent.removeChild(table);
            if (!parent.innerHTML.trim()) parent.innerHTML = '<p><br></p>';
            return next || null;
        }
    };

    function hexToRgb(hex) {
        var m = /^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i.exec(hex || '');
        return m ? 'rgb(' + parseInt(m[1], 16) + ', ' + parseInt(m[2], 16) + ', ' + parseInt(m[3], 16) + ')' : hex;
    }

    function currentBorderColor(table) {
        var cell = table.querySelector('td, th');
        if (!cell) return BORDER_COLOR;
        var s = cell.style;
        return s.borderTopColor || s.borderBottomColor || s.borderLeftColor || s.borderColor || BORDER_COLOR;
    }

    function borderMode(table) {
        var info = buildGrid(table);
        var all = true, any = false, vertical = false;
        info.pos.forEach(function (p, cell) {
            var cs = window.getComputedStyle(cell);
            var top = cs.borderTopStyle !== 'none' && parseFloat(cs.borderTopWidth) > 0;
            var left = cs.borderLeftStyle !== 'none' && parseFloat(cs.borderLeftWidth) > 0;
            if (top || left) any = true;
            if (left && p.c > 0) vertical = true;
            if (!top || !left) all = false;
        });
        if (all) return 'all';
        if (!any) return 'none';
        return vertical ? 'outer' : 'horizontal';
    }

    function clearWidths(table) {
        Array.prototype.forEach.call(table.querySelectorAll('td, th'), function (cell) { cell.style.width = ''; });
    }

    // =====================================================================
    //  Chuẩn hoá bảng (mới chèn / dán vào)
    // =====================================================================
    function styleNewTable(table, withHeader) {
        table.removeAttribute('class');
        table.removeAttribute('border');
        table.removeAttribute('cellpadding');
        table.removeAttribute('cellspacing');
        table.removeAttribute('width');
        table.style.cssText = TABLE_CSS;
        Array.prototype.forEach.call(table.querySelectorAll('td, th'), function (cell) {
            var bg = cell.style.backgroundColor;
            var ta = cell.style.textAlign;
            var va = cell.style.verticalAlign;
            var fw = cell.style.fontWeight;
            cell.removeAttribute('class');
            cell.style.cssText = CELL_CSS;
            if (bg) cell.style.backgroundColor = bg;
            if (ta) cell.style.textAlign = ta;
            if (va) cell.style.verticalAlign = va;
            if (fw) cell.style.fontWeight = fw;
            if (!cell.innerHTML.trim()) cell.innerHTML = '<br>';
        });
        if (withHeader && !Ops.isHeaderOn(table)) Ops.toggleHeader(table);
    }

    // ── Dán từ Word / Excel / Google Docs ─────────────────────────────────
    var KEEP_TAGS = {
        TABLE: 1, THEAD: 1, TBODY: 1, TFOOT: 1, TR: 1, TD: 1, TH: 1, CAPTION: 0,
        P: 1, BR: 1, B: 1, STRONG: 1, I: 1, EM: 1, U: 1, SPAN: 1, SUB: 1, SUP: 1,
        UL: 1, OL: 1, LI: 1, A: 1
    };

    function cssValue(style, prop) {
        var m = new RegExp('(?:^|;)\\s*' + prop + '\\s*:\\s*([^;]+)', 'i').exec(style || '');
        return m ? m[1].trim() : '';
    }

    function cleanPastedNode(node) {
        Array.prototype.slice.call(node.childNodes).forEach(function (child) {
            if (child.nodeType === 8) { node.removeChild(child); return; }          // comment
            if (child.nodeType !== 1) return;

            var tag = child.tagName.toUpperCase().replace(/^O:/, 'O_');
            if (tag === 'STYLE' || tag === 'SCRIPT' || tag === 'META' || tag === 'LINK' || tag === 'TITLE' || tag === 'XML' || tag === 'O_P') {
                node.removeChild(child);
                return;
            }

            cleanPastedNode(child);

            if (!KEEP_TAGS[tag]) {
                // Bỏ thẻ, giữ nội dung (font, div, h*, …)
                while (child.firstChild) node.insertBefore(child.firstChild, child);
                node.removeChild(child);
                return;
            }

            var style = child.getAttribute('style') || '';
            var keep = {};
            if (tag === 'TD' || tag === 'TH') {
                var bg = cssValue(style, 'background-color') || cssValue(style, 'background');
                if (/^(#[0-9a-f]{3,6}|rgb\([^)]+\))$/i.test(bg) && !/^(#fff(fff)?|rgb\(255,\s*255,\s*255\))$/i.test(bg)) keep.backgroundColor = bg;
                var bgAttr = child.getAttribute('bgcolor');
                if (!keep.backgroundColor && bgAttr && !/^#?fff(fff)?$/i.test(bgAttr)) keep.backgroundColor = bgAttr;
                var va = cssValue(style, 'vertical-align') || child.getAttribute('valign');
                if (/^(top|middle|bottom)$/i.test(va)) keep.verticalAlign = va.toLowerCase();
            }
            var ta = cssValue(style, 'text-align') || child.getAttribute('align');
            if (/^(left|center|right|justify)$/i.test(ta)) keep.textAlign = ta.toLowerCase();
            if (tag === 'SPAN' || tag === 'P') {
                var fw = cssValue(style, 'font-weight');
                if (/^(bold|[6-9]00)$/i.test(fw)) keep.fontWeight = '700';
                if (/italic/i.test(cssValue(style, 'font-style'))) keep.fontStyle = 'italic';
                if (/underline/i.test(cssValue(style, 'text-decoration'))) keep.textDecoration = 'underline';
            }

            var href = tag === 'A' ? child.getAttribute('href') : null;
            var cs = child.getAttribute('colspan');
            var rs = child.getAttribute('rowspan');
            while (child.attributes.length) child.removeAttribute(child.attributes[0].name);
            if (href && !/^\s*javascript:/i.test(href)) child.setAttribute('href', href);
            if (cs && parseInt(cs, 10) > 1) child.setAttribute('colspan', String(parseInt(cs, 10)));
            if (rs && parseInt(rs, 10) > 1) child.setAttribute('rowspan', String(parseInt(rs, 10)));
            Object.keys(keep).forEach(function (k) { child.style[k] = keep[k]; });

            // Span không còn định dạng gì -> bỏ thẻ
            if (tag === 'SPAN' && !child.getAttribute('style')) {
                while (child.firstChild) node.insertBefore(child.firstChild, child);
                node.removeChild(child);
            }
        });
    }

    function cleanPastedHtml(html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var body = doc.body;
        cleanPastedNode(body);

        Array.prototype.forEach.call(body.querySelectorAll('table'), function (table) {
            // Gom <thead>/<tfoot> về 1 <tbody> (hàng tiêu đề nhận biết bằng <th>).
            var sections = Array.prototype.filter.call(table.children, function (el) { return /^(THEAD|TBODY|TFOOT)$/.test(el.tagName); });
            if (sections.length > 1 || (sections[0] && sections[0].tagName !== 'TBODY')) {
                var tbody = doc.createElement('tbody');
                Array.prototype.slice.call(table.rows).forEach(function (tr) { tbody.appendChild(tr); });
                sections.forEach(function (sec) { table.removeChild(sec); });
                table.appendChild(tbody);
            }

            // Đoạn văn trong ô: bỏ <p> bọc ngoài nếu ô chỉ có 1 đoạn
            Array.prototype.forEach.call(table.querySelectorAll('td, th'), function (cell) {
                var ps = cell.querySelectorAll(':scope > p');
                if (ps.length === 1 && cell.children.length === 1) {
                    var p = ps[0];
                    var align = p.style.textAlign;
                    while (p.firstChild) cell.insertBefore(p.firstChild, p);
                    cell.removeChild(p);
                    if (align && !cell.style.textAlign) cell.style.textAlign = align;
                }
                cell.innerHTML = cell.innerHTML.replace(/&nbsp;/g, ' ').trim();
            });
            styleNewTable(table, false);
            fillHoles(table);
            // Hàng đầu là th (Excel/Docs) -> giữ kiểu tiêu đề
            var first = table.rows[0];
            if (first && Array.prototype.every.call(first.cells, function (c) { return c.tagName === 'TH'; })) {
                Array.prototype.forEach.call(first.cells, function (c) {
                    if (!c.style.backgroundColor) c.style.backgroundColor = HEAD_BG;
                    c.style.fontWeight = '700';
                    if (!c.style.textAlign) c.style.textAlign = 'left';
                });
            }
        });

        return body.innerHTML.replace(/\s+/g, ' ').replace(/>\s+</g, '><').trim();
    }

    // =====================================================================
    //  Gắn vào 1 editor
    // =====================================================================
    function contextOf($note) {
        return $note.data('summernote');
    }

    function commit(ed, focusCell) {
        clearSelection(ed);
        if (focusCell && ed.editable.contains(focusCell)) {
            // Ô (sau thao tác) hoặc đoạn văn cạnh bảng vừa xoá.
            ed.editable.focus();
            placeCaret(focusCell);
            ed.state.current = isCell(focusCell) ? focusCell : null;
        } else if (focusCell === null || focusCell) {
            ed.state.current = null;
        }
        Array.prototype.forEach.call(ed.editable.querySelectorAll('table'), normalizeSpans);
        // Đánh dấu đường kẻ mờ TRƯỚC khi chụp lịch sử, để Ctrl+Z không bị lệch 1 bước.
        markGuides(ed.editable);
        var ctx = contextOf(ed.$note);
        if (ctx) {
            try { ctx.invoke('editor.afterCommand'); } catch (e) { ed.$note.trigger('summernote.change'); }
        }
        refreshBar(ed);
    }

    // ── Chọn vùng ô ───────────────────────────────────────────────────────
    function clearSelection(ed) {
        Array.prototype.forEach.call(ed.editable.querySelectorAll('.rt-sel'), function (c) {
            c.classList.remove('rt-sel');
            if (!c.className) c.removeAttribute('class');
        });
        ed.state.cells = [];
    }

    function selectRange(ed, a, b) {
        clearSelection(ed);
        if (!a || !b || tableOf(a) !== tableOf(b)) return;
        if (a === b) return;
        var box = Ops.rect([a, b]);
        box.cells.forEach(function (c) { c.classList.add('rt-sel'); });
        ed.state.cells = box.cells;
        window.getSelection().removeAllRanges();
    }

    function targetCells(ed) {
        if (ed.state.cells.length) return ed.state.cells.filter(function (c) { return ed.editable.contains(c); });
        return ed.state.current && ed.editable.contains(ed.state.current) ? [ed.state.current] : [];
    }

    // ── Kéo chỉnh độ rộng cột ─────────────────────────────────────────────
    function resizeHit(ed, e) {
        var cell = closestCell(e.target, ed.editable);
        if (!cell) return null;
        var rect = cell.getBoundingClientRect();
        var table = tableOf(cell);
        var info = buildGrid(table);
        var p = info.pos.get(cell);
        if (!p) return null;

        if (e.clientX >= rect.right - RESIZE_ZONE && e.clientX <= rect.right + RESIZE_ZONE && p.c + p.cs < info.cols) {
            return { table: table, col: p.c + p.cs - 1 };
        }
        if (e.clientX <= rect.left + RESIZE_ZONE && e.clientX >= rect.left - RESIZE_ZONE && p.c > 0) {
            return { table: table, col: p.c - 1 };
        }
        return null;
    }

    function measureCols(table) {
        var info = buildGrid(table);
        var widths = [];
        for (var c = 0; c < info.cols; c++) {
            for (var r = 0; r < info.rows.length; r++) {
                var g = info.grid[r][c];
                if (g && g.c0 === c && info.pos.get(g.cell).cs === 1) {
                    widths[c] = g.cell.getBoundingClientRect().width;
                    break;
                }
            }
        }
        var total = table.getBoundingClientRect().width;
        var known = widths.reduce(function (s, w) { return s + (w || 0); }, 0);
        var missing = 0;
        for (var i = 0; i < info.cols; i++) if (!widths[i]) missing++;
        for (var k = 0; k < info.cols; k++) {
            if (!widths[k]) widths[k] = missing ? Math.max(MIN_COL_PX, (total - known) / missing) : MIN_COL_PX;
        }
        return { widths: widths, info: info };
    }

    function applyColWidths(table, info, widths) {
        var sum = widths.reduce(function (s, w) { return s + w; }, 0) || 1;
        var pct = widths.map(function (w) { return w / sum * 100; });
        info.pos.forEach(function (p, cell) {
            var w = 0;
            for (var c = p.c; c < p.c + p.cs; c++) w += pct[c] || 0;
            cell.style.width = w.toFixed(2).replace(/\.?0+$/, '') + '%';
        });
    }

    function startResize(ed, hit, e) {
        e.preventDefault();
        var m = measureCols(hit.table);
        var startX = e.clientX;
        var base = m.widths.slice();
        var k = hit.col;
        document.body.classList.add('rt-resizing');

        function move(ev) {
            var dx = ev.clientX - startX;
            var pair = base[k] + base[k + 1];
            var left = Math.max(MIN_COL_PX, Math.min(pair - MIN_COL_PX, base[k] + dx));
            var next = base.slice();
            next[k] = left;
            next[k + 1] = pair - left;
            applyColWidths(hit.table, m.info, next);
        }

        function up() {
            document.removeEventListener('mousemove', move);
            document.removeEventListener('mouseup', up);
            document.body.classList.remove('rt-resizing');
            commit(ed);
        }

        document.addEventListener('mousemove', move);
        document.addEventListener('mouseup', up);
    }

    // ── Đường kẻ hướng dẫn khi bảng không viền (chỉ trong editor) ──────────
    function markGuides(editable) {
        Array.prototype.forEach.call(editable.querySelectorAll('table'), function (table) {
            table.classList.toggle('rt-guides', borderMode(table) !== 'all');
            if (!table.className) table.removeAttribute('class');
        });
    }

    // =====================================================================
    //  Thanh công cụ "Bảng"
    // =====================================================================
    function icon(name) {
        return '<i class="fas fa-' + name + '"></i>';
    }

    function btn(action, html, title, extra) {
        return '<button type="button" class="rt-btn' + (extra ? ' ' + extra : '') + '" data-rt="' + action + '" title="' + title + '" aria-label="' + title + '">' + html + '</button>';
    }

    function svgRowAbove() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="9" width="16" height="9" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M2 13.5h16M7.3 9v9M12.6 9v9" stroke="currentColor" stroke-width="1.2"/><path d="M10 1.5v5M7.5 4h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgRowBelow() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="2" width="16" height="9" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M2 6.5h16M7.3 2v9M12.6 2v9" stroke="currentColor" stroke-width="1.2"/><path d="M10 13.5v5M7.5 16h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgColLeft() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="9" y="2" width="9" height="16" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M13.5 2v16M9 7.3h9M9 12.6h9" stroke="currentColor" stroke-width="1.2"/><path d="M1.5 10h5M4 7.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgColRight() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="2" width="9" height="16" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M6.5 2v16M2 7.3h9M2 12.6h9" stroke="currentColor" stroke-width="1.2"/><path d="M13.5 10h5M16 7.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgDelRow() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="6" width="16" height="8" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M7.5 8l5 4M12.5 8l-5 4" stroke="#dc2626" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgDelCol() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="6" y="2" width="8" height="16" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M8 7.5l4 5M12 7.5l-4 5" stroke="#dc2626" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
    function svgMerge() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="4" width="16" height="12" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M5 10h3.5M8.5 10l-1.6-1.6M8.5 10l-1.6 1.6M15 10h-3.5M11.5 10l1.6-1.6M11.5 10l1.6 1.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg>';
    }
    function svgSplit() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="4" width="16" height="12" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M10 4v12" stroke="currentColor" stroke-width="1.5" stroke-dasharray="2 1.6"/><path d="M8 10H4.5M4.5 10l1.6-1.6M4.5 10l1.6 1.6M12 10h3.5M15.5 10l-1.6-1.6M15.5 10l-1.6 1.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg>';
    }
    function svgHeader() {
        return '<svg viewBox="0 0 20 20" aria-hidden="true"><rect x="2" y="3" width="16" height="14" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><rect x="2.8" y="3.8" width="14.4" height="4.2" fill="currentColor" opacity=".35"/><path d="M2 8h16M2 12.5h16M10 3v14" stroke="currentColor" stroke-width="1.2"/></svg>';
    }
    function svgBorder(mode) {
        var grid = '<rect x="3" y="3" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1" stroke-dasharray="1.5 1.5" opacity=".45"/><path d="M10 3v14M3 10h14" stroke="currentColor" stroke-width="1" stroke-dasharray="1.5 1.5" opacity=".45"/>';
        var solid = {
            all: '<rect x="3" y="3" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M10 3v14M3 10h14" stroke="currentColor" stroke-width="1.6"/>',
            outer: '<rect x="3" y="3" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6"/>',
            horizontal: '<path d="M3 3h14M3 10h14M3 17h14" stroke="currentColor" stroke-width="1.6"/>',
            none: ''
        }[mode];
        return '<svg viewBox="0 0 20 20" aria-hidden="true">' + grid + solid + '</svg>';
    }

    function menu(name, buttonHtml, title) {
        return '<button type="button" class="rt-btn rt-menu-toggle" data-rt-toggle="' + name + '" title="' + title + '" aria-label="' + title + '">' + buttonHtml + '<span class="rt-caret"></span></button>';
    }

    var MENU_BODIES = null;

    function menuBodies() {
        if (MENU_BODIES) return MENU_BODIES;
        var swatches = CELL_COLORS.map(function (c) {
            return '<button type="button" class="rt-swatch' + (c ? '' : ' rt-swatch--none') + '" data-rt="bg" data-value="' + c + '" title="' + (c || 'Không màu') + '"' + (c ? ' style="background:' + c + '"' : '') + '></button>';
        }).join('');

        var bgBody = '<div class="rt-pop-title">Màu nền ô</div><div class="rt-swatches">' + swatches + '</div>'
            + '<label class="rt-custom-color">Màu khác <input type="color" data-rt-color="bg" value="#f1ecfb"></label>';

        var alignBody = '<div class="rt-pop-title">Căn ngang</div><div class="rt-row">'
            + btn('align-h', icon('align-left'), 'Căn trái', 'rt-btn--sq" data-value="left')
            + btn('align-h', icon('align-center'), 'Căn giữa', 'rt-btn--sq" data-value="center')
            + btn('align-h', icon('align-right'), 'Căn phải', 'rt-btn--sq" data-value="right')
            + btn('align-h', icon('align-justify'), 'Căn đều', 'rt-btn--sq" data-value="justify')
            + '</div><div class="rt-pop-title">Căn dọc</div><div class="rt-row">'
            + btn('align-v', '<span class="rt-va rt-va--top"></span>', 'Trên', 'rt-btn--sq" data-value="top')
            + btn('align-v', '<span class="rt-va rt-va--middle"></span>', 'Giữa', 'rt-btn--sq" data-value="middle')
            + btn('align-v', '<span class="rt-va rt-va--bottom"></span>', 'Dưới', 'rt-btn--sq" data-value="bottom')
            + '</div>';

        var borderBody = '<div class="rt-pop-title">Kiểu viền</div><div class="rt-row">'
            + btn('border', svgBorder('all'), 'Tất cả viền', 'rt-btn--sq" data-value="all')
            + btn('border', svgBorder('outer'), 'Chỉ viền ngoài', 'rt-btn--sq" data-value="outer')
            + btn('border', svgBorder('horizontal'), 'Chỉ đường ngang', 'rt-btn--sq" data-value="horizontal')
            + btn('border', svgBorder('none'), 'Không viền', 'rt-btn--sq" data-value="none')
            + '</div><div class="rt-pop-title">Màu viền</div><div class="rt-swatches">'
            + BORDER_COLORS.map(function (c) {
                return '<button type="button" class="rt-swatch" data-rt="border-color" data-value="' + c + '" title="' + c + '" style="background:' + c + '"></button>';
            }).join('')
            + '</div>';

        var widthBody = '<div class="rt-pop-title">Độ rộng bảng</div>'
            + '<button type="button" class="rt-item" data-rt="width" data-value="full">' + icon('arrows-alt-h') + ' Vừa khung (100%)</button>'
            + '<button type="button" class="rt-item" data-rt="width" data-value="auto">' + icon('compress-alt') + ' Tự co theo nội dung</button>'
            + '<button type="button" class="rt-item" data-rt="width" data-value="equal">' + icon('grip-lines-vertical') + ' Chia đều các cột</button>';

        MENU_BODIES = { bg: bgBody, align: alignBody, border: borderBody, width: widthBody };
        return MENU_BODIES;
    }

    function barHtml() {
        return '<span class="rt-label">' + icon('table') + ' Bảng</span>'
            + '<span class="rt-group">'
            + btn('row-above', svgRowAbove(), 'Chèn hàng phía trên')
            + btn('row-below', svgRowBelow(), 'Chèn hàng phía dưới')
            + btn('col-left', svgColLeft(), 'Chèn cột bên trái')
            + btn('col-right', svgColRight(), 'Chèn cột bên phải')
            + '</span><span class="rt-group">'
            + btn('del-row', svgDelRow(), 'Xoá hàng')
            + btn('del-col', svgDelCol(), 'Xoá cột')
            + '</span><span class="rt-group">'
            + btn('merge', svgMerge(), 'Gộp ô (kéo chuột chọn nhiều ô trước)')
            + btn('split', svgSplit(), 'Tách ô')
            + btn('header', svgHeader(), 'Bật / tắt hàng tiêu đề')
            + '</span><span class="rt-group">'
            + menu('bg', '<span class="rt-bg-icon">' + icon('fill-drip') + '<span class="rt-bg-bar"></span></span>', 'Màu nền ô')
            + menu('align', icon('align-left'), 'Căn chữ trong ô')
            + menu('border', svgBorder('all'), 'Viền bảng')
            + menu('width', icon('arrows-alt-h'), 'Độ rộng bảng')
            + '</span><span class="rt-group">'
            + btn('delete-table', icon('trash-alt'), 'Xoá bảng', 'rt-btn--danger')
            + '</span>'
            + '<span class="rt-hint">Kéo chuột qua nhiều ô để chọn · kéo viền cột để đổi độ rộng</span>';
    }

    // =====================================================================
    //  Khung nổi (lưới chèn bảng + menu của thanh Bảng)
    //  Gắn thẳng vào <body>, position: fixed -> không bị khung soạn thảo,
    //  overflow / z-index của trang che, kể cả khi kéo giãn chiều cao editor.
    // =====================================================================
    var Float = {
        el: null,
        owner: null,     // { type: 'grid' | 'menu', anchor, ed?, context? }

        ensure: function () {
            if (this.el) return this.el;
            var el = document.createElement('div');
            el.className = 'rt-float';
            el.hidden = true;
            document.body.appendChild(el);
            this.el = el;
            bindFloat(el);
            return el;
        },

        isOpenFor: function (anchor) {
            return !!(this.owner && this.owner.anchor === anchor && !this.el.hidden);
        },

        open: function (anchor, html, owner) {
            var el = this.ensure();
            this.close();
            owner.anchor = anchor;
            this.owner = owner;
            el.innerHTML = html;
            el.hidden = false;
            el.style.visibility = 'hidden';
            el.style.left = '0px';
            el.style.top = '0px';
            anchor.classList.add('is-open');
            this.place();
            el.style.visibility = '';
        },

        place: function () {
            if (!this.owner || this.el.hidden) return;
            var r = this.owner.anchor.getBoundingClientRect();
            var w = this.el.offsetWidth;
            var h = this.el.offsetHeight;
            var vw = document.documentElement.clientWidth;
            var vh = window.innerHeight;
            var top = r.bottom + 6;
            if (top + h > vh - 8 && r.top - h - 6 >= 8) top = r.top - h - 6;    // không đủ chỗ -> lật lên trên
            top = Math.max(8, Math.min(top, vh - h - 8));
            var left = Math.max(8, Math.min(r.left, vw - w - 8));
            this.el.style.top = Math.round(top) + 'px';
            this.el.style.left = Math.round(left) + 'px';
        },

        close: function () {
            if (this.owner && this.owner.anchor) this.owner.anchor.classList.remove('is-open');
            this.owner = null;
            if (this.el) {
                this.el.hidden = true;
                this.el.innerHTML = '';
            }
        }
    };

    function closeMenus(ed) {
        if (Float.owner && (!ed || Float.owner.ed === ed)) Float.close();
    }

    function openMenu(ed, toggle) {
        if (Float.isOpenFor(toggle)) { Float.close(); return; }
        var name = toggle.dataset.rtToggle;
        Float.open(toggle, '<div class="rt-float-menu" data-menu="' + name + '">' + menuBodies()[name] + '</div>', { type: 'menu', ed: ed });

        // Trạng thái hiện tại của bảng trong menu viền.
        var cell = targetCells(ed)[0];
        var table = cell && tableOf(cell);
        if (table && name === 'border') {
            var mode = borderMode(table);
            Array.prototype.forEach.call(Float.el.querySelectorAll('[data-rt="border"]'), function (b) {
                b.classList.toggle('is-active', b.dataset.value === mode);
            });
        }
    }

    function openGrid(context, anchor) {
        if (Float.isOpenFor(anchor)) { Float.close(); return; }
        Float.open(anchor, gridHtml(), { type: 'grid', context: context });
    }

    function bindFloat(el) {
        // Giữ vùng chọn trong editor khi bấm vào khung nổi.
        el.addEventListener('mousedown', function (e) {
            if (e.target.tagName !== 'INPUT') e.preventDefault();
        });

        el.addEventListener('mouseover', function (e) {
            var c = e.target.closest('.rt-grid-cell');
            if (!c || !Float.owner || Float.owner.type !== 'grid') return;
            var r = +c.dataset.r;
            var col = +c.dataset.c;
            Array.prototype.forEach.call(el.querySelectorAll('.rt-grid-cell'), function (x) {
                x.classList.toggle('is-on', +x.dataset.r <= r && +x.dataset.c <= col);
            });
            var size = el.querySelector('.rt-grid-size');
            if (size) size.textContent = col + ' × ' + r;
        });

        el.addEventListener('click', function (e) {
            var owner = Float.owner;
            if (!owner) return;

            if (owner.type === 'grid') {
                var c = e.target.closest('.rt-grid-cell');
                if (!c) return;
                e.preventDefault();
                var context = owner.context;
                Float.close();
                insertTable(context, +c.dataset.c, +c.dataset.r);
                return;
            }

            var b = e.target.closest('[data-rt]');
            if (!b || b.disabled) return;
            e.preventDefault();
            var ed = owner.ed;
            Float.close();
            runAction(ed, b.dataset.rt, b.dataset.value);
        });

        el.addEventListener('change', function (e) {
            if (!e.target.matches('input[data-rt-color]') || !Float.owner || !Float.owner.ed) return;
            var ed = Float.owner.ed;
            var value = e.target.value;
            Float.close();
            runAction(ed, 'bg', value);
        });
    }

    // Đóng khung nổi khi bấm ra ngoài, cuộn trang, đổi kích thước cửa sổ, Esc.
    document.addEventListener('mousedown', function (e) {
        if (!Float.owner) return;
        if (Float.el.contains(e.target) || Float.owner.anchor.contains(e.target)) return;
        Float.close();
    }, true);
    window.addEventListener('scroll', function (e) {
        if (Float.owner && !(Float.el && Float.el.contains(e.target))) Float.close();
    }, true);
    window.addEventListener('resize', function () { Float.close(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && Float.owner) Float.close();
    });

    function refreshBar(ed) {
        var cells = targetCells(ed);
        var cell = cells[0];
        var show = !!cell;
        ed.bar.hidden = !show;
        if (!show) { closeMenus(ed); return; }

        var table = tableOf(cell);
        if (!table) { ed.state.current = null; ed.bar.hidden = true; closeMenus(ed); return; }
        var info = buildGrid(table);
        var p = info.pos.get(cell) || { rs: 1, cs: 1 };
        $(ed.bar).find('[data-rt="merge"]').prop('disabled', ed.state.cells.length < 2);
        $(ed.bar).find('[data-rt="split"]').prop('disabled', !(cells.length === 1 && (p.rs > 1 || p.cs > 1)));
        $(ed.bar).find('[data-rt="header"]').toggleClass('is-active', Ops.isHeaderOn(table));
        $(ed.bar).find('[data-rt="border"]').each(function () {
            this.classList.toggle('is-active', this.dataset.value === borderMode(table));
        });
        var bg = cell.style.backgroundColor || '';
        $(ed.bar).find('.rt-bg-bar').css('background', bg || 'transparent');
    }

    function runAction(ed, action, value) {
        var cells = targetCells(ed);
        if (!cells.length && action !== 'noop') return;
        var cell = cells[0];
        var table = tableOf(cell);
        var focus;

        switch (action) {
            case 'row-above': focus = Ops.insertRow(cells[0], false); break;
            case 'row-below': focus = Ops.insertRow(cells[cells.length - 1], true); break;
            case 'col-left': focus = Ops.insertCol(cells[0], false); break;
            case 'col-right': focus = Ops.insertCol(cells[cells.length - 1], true); break;
            case 'del-row': focus = Ops.deleteRows(cells); break;
            case 'del-col': focus = Ops.deleteCols(cells); break;
            case 'merge': focus = Ops.merge(cells); break;
            case 'split': focus = Ops.split(cell); break;
            case 'header': focus = Ops.toggleHeader(table); break;
            case 'bg': Ops.setBackground(cells, value); focus = cell; break;
            case 'align-h': Ops.setAlign(cells, value, null); focus = cell; break;
            case 'align-v': Ops.setAlign(cells, null, value); focus = cell; break;
            case 'border': Ops.setBorders(table, value); focus = cell; break;
            case 'border-color': Ops.setBorders(table, borderMode(table) === 'none' ? 'all' : borderMode(table), value); focus = cell; break;
            case 'width':
                if (value === 'equal') {
                    var n = buildGrid(table);
                    var eq = [];
                    for (var i = 0; i < n.cols; i++) eq.push(1);
                    table.style.width = table.style.width === 'auto' ? '100%' : (table.style.width || '100%');
                    applyColWidths(table, n, eq);
                } else {
                    Ops.setWidth(table, value === 'full');
                }
                focus = cell;
                break;
            case 'delete-table': focus = Ops.deleteTable(table); break;
            default: return;
        }

        var keepSel = ['bg', 'align-h', 'align-v'].indexOf(action) !== -1 && ed.state.cells.length > 1;
        var kept = keepSel ? ed.state.cells.slice() : null;
        commit(ed, focus === undefined ? null : focus);
        if (kept) {
            kept.forEach(function (c) { c.classList.add('rt-sel'); });
            ed.state.cells = kept;
            refreshBar(ed);
        }
    }

    function bindBar(ed) {
        var $bar = $(ed.bar);

        // Giữ vùng chọn trong editor khi bấm nút trên thanh bảng.
        $bar.on('mousedown', function (e) {
            if (!$(e.target).is('input')) e.preventDefault();
        });

        $bar.on('click', '[data-rt-toggle]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openMenu(ed, this);
        });

        $bar.on('click', '[data-rt]', function (e) {
            e.preventDefault();
            if (this.disabled) return;
            closeMenus(ed);
            runAction(ed, this.dataset.rt, this.dataset.value);
        });

    }

    // =====================================================================
    //  Sự kiện trong vùng soạn thảo
    // =====================================================================
    var drag = null;   // { ed, anchor, active } — kéo chọn vùng ô đang diễn ra

    // 1 bộ lắng nghe chung cho cả trang (không gắn lại mỗi lần tạo editor).

    // Con trỏ sang ô nhập khác (editor khác, input…) -> ẩn thanh Bảng của editor cũ.
    document.addEventListener('focusin', function (e) {
        var t = e.target;
        if (Float.el && Float.el.contains(t)) return;
        editors.forEach(function (ed) {
            var frame = ed.editable.closest('.note-editor');
            if ((frame && frame.contains(t)) || ed.bar.contains(t)) return;   // nút Summernote của chính editor này
            ed.bar.hidden = true;
            clearSelection(ed);
        });
    });
    document.addEventListener('mouseup', function () {
        if (!drag) return;
        var ed = drag.ed;
        var wasActive = drag.active;
        ed.state.current = drag.anchor;
        drag = null;
        if (wasActive && ed.state.cells.length > 1) {
            window.getSelection().removeAllRanges();
            refreshBar(ed);
        }
    });

    function bindEditable(ed) {
        var editable = ed.editable;

        function updateCurrent() {
            var sel = window.getSelection();
            if (!sel.rangeCount) return;
            var node = sel.getRangeAt(0).startContainer;
            if (!editable.contains(node)) return;
            ed.state.current = closestCell(node, editable);
            if (!ed.state.current) clearSelection(ed);
            refreshBar(ed);
        }

        editable.addEventListener('mousemove', function (e) {
            if (drag || document.body.classList.contains('rt-resizing')) return;
            editable.classList.toggle('rt-col-resize', !!resizeHit(ed, e));
        });

        editable.addEventListener('mousedown', function (e) {
            if (e.button !== 0) return;
            var hit = resizeHit(ed, e);
            if (hit) { startResize(ed, hit, e); return; }

            var cell = closestCell(e.target, editable);
            if (e.shiftKey && cell && ed.state.current && tableOf(cell) === tableOf(ed.state.current)) {
                e.preventDefault();
                selectRange(ed, ed.state.current, cell);
                refreshBar(ed);
                return;
            }

            clearSelection(ed);
            if (!cell) return;
            drag = { ed: ed, anchor: cell, active: false };
        });

        editable.addEventListener('mouseover', function (e) {
            if (!drag || drag.ed !== ed) return;
            var cell = closestCell(e.target, editable);
            if (!cell || tableOf(cell) !== tableOf(drag.anchor)) return;
            if (cell !== drag.anchor) drag.active = true;
            if (drag.active) {
                selectRange(ed, drag.anchor, cell);
                if (cell === drag.anchor) placeCaret(cell);
            }
        });

        editable.addEventListener('mouseup', function () { setTimeout(updateCurrent, 0); });
        editable.addEventListener('keyup', updateCurrent);
        editable.addEventListener('focus', function () { setTimeout(updateCurrent, 0); });

        // Tab / Shift+Tab: sang ô kế / ô trước như Word; Tab ở ô cuối -> thêm hàng.
        // (capture để chạy trước Summernote — bản 0.8.18 trong dự án không xử lý Tab).
        editable.addEventListener('keydown', function (e) {
            if (e.key !== 'Tab' || e.ctrlKey || e.metaKey || e.altKey) return;
            var sel = window.getSelection();
            var cell = sel.rangeCount ? closestCell(sel.getRangeAt(0).startContainer, editable) : null;
            if (!cell) return;

            e.preventDefault();
            e.stopPropagation();
            clearSelection(ed);

            var table = tableOf(cell);
            var all = Array.prototype.slice.call(table.querySelectorAll('td, th')).filter(function (c) { return tableOf(c) === table; });
            var i = all.indexOf(cell);
            var target = all[i + (e.shiftKey ? -1 : 1)];

            if (!target && !e.shiftKey) {
                target = Ops.insertRow(cell, true);
                commit(ed, target);
                return;
            }
            if (!target) return;

            var range = document.createRange();
            range.selectNodeContents(target);
            sel.removeAllRanges();
            sel.addRange(range);
            ed.state.current = target;
            refreshBar(ed);
        }, true);

        editable.addEventListener('keydown', function (e) {
            if (ed.state.cells.length > 1) {
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    e.preventDefault();
                    var kept = ed.state.cells.slice();
                    kept.forEach(function (c) { c.innerHTML = '<br>'; });
                    commit(ed, kept[0]);
                    return;
                }
                if (['Shift', 'Control', 'Meta', 'Alt'].indexOf(e.key) === -1) {
                    var first = ed.state.cells[0];
                    clearSelection(ed);
                    placeCaret(first);
                    refreshBar(ed);
                }
            }
        });

        ed.$note.on('summernote.change.rt', function () {
            markGuides(editable);
            if (ed.state.current && !editable.contains(ed.state.current)) {
                ed.state.current = null;
                refreshBar(ed);
            }
        });
    }

    function attach($notes) {
        $($notes).each(function () {
            var $note = $(this);
            var $editor = $note.next('.note-editor');
            var editable = $editor.find('.note-editable')[0];
            if (!editable || $editor.children('.rt-bar').length) return;

            // Editor bị huỷ / tạo lại (form câu hỏi đóng mở) -> bỏ bản ghi cũ.
            editors = editors.filter(function (x) { return document.contains(x.editable); });

            var bar = document.createElement('div');
            bar.className = 'rt-bar';
            bar.hidden = true;
            bar.innerHTML = barHtml();
            var $toolbar = $editor.find('.note-toolbar').first();
            if ($toolbar.length) $toolbar.after(bar);
            else $editor.prepend(bar);

            var ed = { $note: $note, editable: editable, bar: bar, state: { current: null, cells: [] } };
            editors.push(ed);
            bindBar(ed);
            bindEditable(ed);
            markGuides(editable);
        });
    }

    // =====================================================================
    //  Nút "Bảng" (lưới chọn kích thước) cho thanh công cụ Summernote
    // =====================================================================
    function gridHtml() {
        var cells = '';
        for (var r = 1; r <= GRID_ROWS; r++) {
            for (var c = 1; c <= GRID_COLS; c++) {
                cells += '<button type="button" class="rt-grid-cell" data-r="' + r + '" data-c="' + c + '" tabindex="-1"></button>';
            }
        }
        return '<div class="rt-grid-wrap">'
            + '<div class="rt-grid-label">Chèn bảng <b class="rt-grid-size">1 × 1</b></div>'
            + '<div class="rt-grid" style="grid-template-columns: repeat(' + GRID_COLS + ', 18px)">' + cells + '</div>'
            + '<div class="rt-grid-note">Hàng đầu là hàng tiêu đề (tắt được trên thanh Bảng)</div>'
            + '</div>';
    }

    function insertTable(context, cols, rows) {
        var editable = context.layoutInfo.editable[0];
        var before = new Set(editable.querySelectorAll('table'));

        context.invoke('editor.insertTable', cols + 'x' + rows);

        var table = null;
        Array.prototype.some.call(editable.querySelectorAll('table'), function (t) {
            if (!before.has(t)) { table = t; return true; }
            return false;
        });
        if (!table) return;

        styleNewTable(table, rows > 1);

        // Luôn có 1 đoạn trống sau bảng để gõ tiếp.
        var next = table.nextElementSibling;
        if (!next || next.tagName === 'TABLE') {
            var p = document.createElement('p');
            p.innerHTML = '<br>';
            table.parentNode.insertBefore(p, table.nextSibling);
        }
        // Summernote có thể để lại <p> rỗng phía trước bảng khi chèn ở đầu.
        var prev = table.previousElementSibling;
        if (prev && prev.tagName === 'P' && !prev.textContent.trim() && !prev.querySelector('img') && prev === editable.firstElementChild) {
            prev.parentNode.removeChild(prev);
        }

        var first = table.querySelector('td, th');
        markGuides(editable);
        editable.focus();
        placeCaret(first);
        context.invoke('editor.afterCommand');

        var ed = editors.filter(function (x) { return x.editable === editable; })[0];
        if (ed) {
            ed.state.current = first;
            refreshBar(ed);
        }
    }

    var buttons = {
        rtTable: function (context) {
            var ui = $.summernote.ui;
            return ui.button({
                className: 'rt-table-toggle',
                contents: '<i class="fas fa-table"></i> <span class="note-icon-caret"></span>',
                tooltip: 'Chèn bảng',
                click: function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openGrid(context, e.currentTarget);
                }
            }).render();
        }
    };

    /** Popover mặc định của Summernote nhưng tắt popover bảng (đã có thanh Bảng). */
    function popover() {
        var base = $.extend(true, {}, $.summernote.options.popover);
        base.table = [];
        return base;
    }

    /** Callback onPaste: chỉ can thiệp khi dữ liệu dán có bảng. */
    function handlePaste(noteEl, e) {
        var ev = e && (e.originalEvent || e);
        var data = ev && ev.clipboardData;
        if (!data) return;
        var html = data.getData('text/html');
        if (!html || !/<table[\s>]/i.test(html)) return;

        ev.preventDefault();
        if (e.preventDefault) e.preventDefault();
        var clean = cleanPastedHtml(html);
        if (!clean) return;
        var $note = $(noteEl);
        var ed = editors.filter(function (x) { return x.$note[0] === $note[0]; })[0];
        var editable = ed ? ed.editable : $note.next('.note-editor').find('.note-editable')[0];
        var isEmpty = editable && !(editable.textContent || '').trim() && !editable.querySelector('img, table, iframe');

        if (isEmpty) {
            // Ô đang trống: thay hẳn nội dung (tránh đoạn trống thừa phía trên bảng).
            editable.innerHTML = clean + '<p><br></p>';
            editable.focus();
            placeCaret(editable.querySelector('td, th') || editable.lastElementChild);
            var ctx = contextOf($note);
            if (ctx) ctx.invoke('editor.afterCommand');
        } else {
            $note.summernote('pasteHTML', clean);
        }
        if (ed) { markGuides(ed.editable); setTimeout(function () { refreshBar(ed); }, 0); }
    }

    /** Gỡ class chỉ dùng lúc soạn (rt-sel, rt-guides) khỏi HTML trước khi lưu. */
    function clean(html) {
        if (!html || html.indexOf('rt-') === -1) return html;
        var box = document.createElement('div');
        box.innerHTML = html;
        Array.prototype.forEach.call(box.querySelectorAll('.rt-sel, .rt-guides'), function (el) {
            el.classList.remove('rt-sel', 'rt-guides');
            if (!el.className) el.removeAttribute('class');
        });
        return box.innerHTML;
    }

    window.IeltsRichTable = {
        buttons: buttons,
        popover: popover,
        attach: attach,
        handlePaste: handlePaste,
        clean: clean,
        // Lộ ra để kiểm thử
        _ops: Ops,
        _cleanPastedHtml: cleanPastedHtml,
        _buildGrid: buildGrid
    };
})(window, window.jQuery);