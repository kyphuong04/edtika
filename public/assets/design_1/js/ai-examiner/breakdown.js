/* EDTIKA AI Examiner — màn Breakdown (kết quả chấm chữa chi tiết) */
(function () {
    'use strict';

    var AIE = window.AIE;
    var DATA = window.AIE_BREAKDOWN;
    var esc = AIE.escape;
    var $ = AIE.$;

    var chevron = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>';

    var state = {
        criterionKey: DATA.activeCriterion,
        openSubKey: null,
        activeIssueNo: null
    };

    var els = {
        miniCards: $('#aieMiniCards'),
        priorities: $('#aiePriorities'),
        subs: $('#aieSubCriteria'),
        essay: $('#aieEssay'),
        popover: $('#aiePopover'),
        sampleSelect: $('#aieSampleBand'),
        sampleText: $('#aieSampleText'),
        sampleWords: $('#aieSampleWords')
    };

    function fmt(band) { return Number(band).toFixed(1); }

    function criterion() {
        return DATA.result.criteria.filter(function (c) { return c.key === state.criterionKey; })[0] || DATA.result.criteria[0];
    }

    function openSub() {
        return criterion().sub_criteria.filter(function (s) { return s.key === state.openSubKey; })[0] || null;
    }

    function defaultSubKey(c) {
        var withIssues = c.sub_criteria.filter(function (s) { return s.issues.length > 0; })[0];
        return (withIssues || c.sub_criteria[0]).key;
    }

    // ===== Cột phải =====
    function renderMiniCards() {
        els.miniCards.innerHTML = DATA.result.criteria.map(function (c) {
            return '<button type="button" class="aie-mini-card ' + (c.key === state.criterionKey ? 'is-active' : '') + '" data-criterion="' + esc(c.key) + '">' +
                '<b>' + esc(c.code) + '</b><small>' + esc(c.name) + '</small>' +
                '<span class="aie-mini-card__foot"><span class="aie-meter"><span style="width:' + Math.round(c.band / 9 * 100) + '%"></span></span><strong>' + fmt(c.band) + '</strong></span>' +
                '</button>';
        }).join('');
    }

    function renderPriorities() {
        var items = criterion().priorities || [];
        els.priorities.innerHTML = items.length
            ? '<h4>Ưu tiên cải thiện:</h4><ul>' + items.map(function (p) { return '<li>' + esc(p) + '</li>'; }).join('') + '</ul>'
            : '';
    }

    function list(items) {
        return '<ul>' + (items || []).map(function (t) { return '<li>' + esc(t) + '</li>'; }).join('') + '</ul>';
    }

    function renderSubs() {
        els.subs.innerHTML = criterion().sub_criteria.map(function (s) {
            var open = s.key === state.openSubKey;
            var issuesHtml = s.issues.length
                ? s.issues.map(function (i) {
                    return '<div class="aie-issue ' + (state.activeIssueNo === i.no && open ? 'is-active' : '') + '" data-issue="' + i.no + '" role="button" tabindex="0">' +
                        '<span class="aie-badge aie-badge--lg">' + i.no + '</span>' +
                        '<div><b>' + esc(i.title) + '</b><span>' + esc(i.description) + '</span></div></div>';
                }).join('')
                : '<p class="aie-noissue">✓ Không phát hiện lỗi ở tiêu chí này.</p>';

            return '<div class="aie-acc ' + (open ? 'is-open' : '') + '" data-sub="' + esc(s.key) + '">' +
                '<button type="button" class="aie-acc__head" aria-expanded="' + open + '"><span>' + esc(s.name) + ' ( ' + fmt(s.band) + ' )</span>' +
                (s.issues.length ? '<span class="aie-acc__count">' + s.issues.length + ' lỗi</span>' : '') + chevron + '</button>' +
                '<div class="aie-acc__body aie-fb">' +
                '<h5>Nhận xét chung:</h5><p>' + esc(s.comment) + '</p>' +
                '<h5>Điểm mạnh cụ thể:</h5>' + list(s.strengths) +
                '<h5 class="is-strong">Lỗi / điểm cần lưu ý:</h5>' + issuesHtml +
                '<h5>Hướng cải thiện:</h5>' + list(s.improvements) +
                '</div></div>';
        }).join('');
    }

    // ===== Cột trái: highlight lỗi trong bài làm =====
    function renderEssay() {
        var text = DATA.essay;
        var sub = openSub();
        var issues = sub ? sub.issues.filter(function (i) { return i.start !== null && i.end !== null; }) : [];
        issues = issues.slice().sort(function (a, b) { return a.start - b.start; });

        var chars = Array.from(text); // offset theo ký tự (khớp mb_* bên PHP)
        var html = '';
        var cursor = 0;
        issues.forEach(function (i) {
            if (i.start < cursor) return; // bỏ đoạn chồng lấn
            html += esc(chars.slice(cursor, i.start).join(''));
            html += '<mark data-issue="' + i.no + '" class="' + (state.activeIssueNo === i.no ? 'is-active' : '') + '">' + esc(chars.slice(i.start, i.end).join('')) + '</mark>' +
                '<button type="button" class="aie-badge ' + (state.activeIssueNo === i.no ? 'is-active' : '') + '" data-issue="' + i.no + '" aria-label="Lỗi số ' + i.no + '">' + i.no + '</button>';
            cursor = i.end;
        });
        html += esc(chars.slice(cursor).join(''));
        els.essay.innerHTML = html;
    }

    // ===== Popover chi tiết lỗi =====
    function closePopover() {
        els.popover.classList.remove('is-open');
        state.activeIssueNo = null;
        AIE.$$('.is-active[data-issue]').forEach(function (el) { el.classList.remove('is-active'); });
    }

    function openPopover(no) {
        var sub = openSub();
        if (!sub) return;
        var issues = sub.issues;
        var idx = -1;
        issues.forEach(function (i, k) { if (i.no === no) idx = k; });
        if (idx < 0) return;
        var issue = issues[idx];
        state.activeIssueNo = no;

        AIE.$$('[data-issue]').forEach(function (el) {
            el.classList.toggle('is-active', Number(el.getAttribute('data-issue')) === no);
        });

        els.popover.innerHTML =
            '<div class="aie-popover__head">' +
            '<span class="aie-badge aie-badge--lg">' + issue.no + '</span>' +
            '<span class="aie-popover__chip">Phân loại lỗi</span>' +
            '<span class="aie-popover__nav">' +
            (issues.length > 1 ? '<button type="button" data-pop="prev" aria-label="Lỗi trước">‹</button><span>' + (idx + 1) + ' / ' + issues.length + '</span><button type="button" data-pop="next" aria-label="Lỗi sau">›</button>' : '') +
            '<button type="button" class="aie-popover__close" data-pop="close" aria-label="Đóng">&times;</button></span>' +
            '</div>' +
            '<div class="aie-popover__title">' + esc(issue.title) + ' <small style="color:#7b6c90;font-weight:400">· ' + esc(issue.category || '') + '</small></div>' +
            '<h6>Giải thích chi tiết:</h6><p>' + esc(issue.explanation) + '</p>' +
            '<h6>Đề xuất khắc phục:</h6><p>' + esc(issue.suggestion) + '</p>' +
            '<h6>Câu sửa:</h6><p class="aie-popover__fix">' + esc(issue.correction) + '</p>';

        // Định vị ngay dưới đoạn được highlight
        var anchor = els.essay.querySelector('mark[data-issue="' + no + '"]');
        var host = els.popover.parentElement;
        els.popover.classList.add('is-open');
        if (anchor) {
            var hostRect = host.getBoundingClientRect();
            var rects = anchor.getClientRects();
            var last = rects[rects.length - 1] || anchor.getBoundingClientRect();
            var popW = els.popover.offsetWidth;
            var left = Math.min(Math.max(8, last.right - hostRect.left - popW / 2), hostRect.width - popW - 8);
            els.popover.style.left = Math.max(0, left) + 'px';
            els.popover.style.top = (last.bottom - hostRect.top + 10) + 'px';
            var popRect = els.popover.getBoundingClientRect();
            if (popRect.bottom > window.innerHeight || popRect.top < 80) {
                anchor.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            els.popover.style.left = '8px';
            els.popover.style.top = '60px';
        }
    }

    function renderRight() {
        renderMiniCards();
        renderPriorities();
        renderSubs();
    }

    function selectCriterion(key) {
        state.criterionKey = key;
        state.openSubKey = defaultSubKey(criterion());
        closePopover();
        renderRight();
        renderEssay();
        if (window.history && history.replaceState) {
            history.replaceState(null, '', window.location.pathname + '?criterion=' + encodeURIComponent(key));
        }
    }

    // ===== Events =====
    els.miniCards.addEventListener('click', function (e) {
        var card = e.target.closest('[data-criterion]');
        if (card) selectCriterion(card.getAttribute('data-criterion'));
    });

    els.subs.addEventListener('click', function (e) {
        var issueEl = e.target.closest('.aie-issue');
        if (issueEl) {
            var no = Number(issueEl.getAttribute('data-issue'));
            openPopover(no);
            AIE.$$('.aie-issue').forEach(function (el) { el.classList.toggle('is-active', el === issueEl); });
            return;
        }
        var head = e.target.closest('.aie-acc__head');
        if (!head) return;
        var key = head.parentElement.getAttribute('data-sub');
        // Chỉ mở 1 tiêu chí con mỗi lần; bấm lại để đóng
        state.openSubKey = state.openSubKey === key ? null : key;
        closePopover();
        renderSubs();
        renderEssay();
    });

    els.subs.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('aie-issue')) {
            e.preventDefault();
            e.target.click();
        }
    });

    els.essay.addEventListener('click', function (e) {
        var target = e.target.closest('[data-issue]');
        if (!target) return;
        e.stopPropagation();
        openPopover(Number(target.getAttribute('data-issue')));
        renderSubs();
    });

    els.popover.addEventListener('click', function (e) {
        e.stopPropagation();
        var btn = e.target.closest('[data-pop]');
        if (!btn) return;
        var action = btn.getAttribute('data-pop');
        if (action === 'close') { closePopover(); renderSubs(); return; }
        var issues = openSub().issues;
        var idx = 0;
        issues.forEach(function (i, k) { if (i.no === state.activeIssueNo) idx = k; });
        idx = action === 'next' ? (idx + 1) % issues.length : (idx - 1 + issues.length) % issues.length;
        openPopover(issues[idx].no);
        renderSubs();
    });

    document.addEventListener('click', function (e) {
        if (!els.popover.classList.contains('is-open')) return;
        if (e.target.closest('#aiePopover') || e.target.closest('.aie-issue') || e.target.closest('#aieEssay [data-issue]')) return;
        closePopover();
        renderSubs();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && els.popover.classList.contains('is-open')) { closePopover(); renderSubs(); }
    });

    // Bài mẫu
    function renderSample() {
        var sample = DATA.result.samples[els.sampleSelect.value];
        els.sampleText.textContent = sample ? sample.text : 'Chưa có bài mẫu cho mức band này.';
        els.sampleWords.textContent = sample ? 'Words: ' + sample.words : '';
    }
    els.sampleSelect.addEventListener('change', renderSample);

    // Kéo thanh chia 2 cột
    (function () {
        var splitter = $('#aieSplitter');
        var wrap = $('#aieBreakdown');
        if (!splitter || !wrap) return;
        var dragging = false;
        splitter.addEventListener('mousedown', function (e) { dragging = true; e.preventDefault(); document.body.style.userSelect = 'none'; });
        document.addEventListener('mouseup', function () { dragging = false; document.body.style.userSelect = ''; });
        document.addEventListener('mousemove', function (e) {
            if (!dragging) return;
            var rect = wrap.getBoundingClientRect();
            var ratio = Math.min(0.7, Math.max(0.3, (e.clientX - rect.left) / rect.width));
            wrap.style.setProperty('--aie-left', ratio + 'fr');
            wrap.style.setProperty('--aie-right', (1 - ratio) + 'fr');
        });
        splitter.addEventListener('dblclick', function () {
            wrap.style.removeProperty('--aie-left');
            wrap.style.removeProperty('--aie-right');
        });
    })();

    // ===== Init =====
    state.openSubKey = defaultSubKey(criterion());
    renderRight();
    renderEssay();
    renderSample();
})();
