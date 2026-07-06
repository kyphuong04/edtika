{{--
    IELTS Review Page - handles Reading/Listening AND Writing layouts
--}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review - {{ $test->title ?? '' }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 15px;
    color: #111;
    background: linear-gradient(180deg, #ecebf3 0%, #edf0f6 62%, #eaf4f1 100%);
    line-height: 1.5;
    overflow: hidden;
    height: 100vh;
}

/* ── SHARED HEADER ───────────────────────────── */
.rv-header {
    height: 60px;
    background: #fff;
    border-bottom: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
}
.rv-header-left  { display: flex; align-items: center; gap: 12px; flex: 1; }
.rv-header-right { display: flex; align-items: center; gap: 8px; flex: 1; justify-content: flex-end; }
.rv-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    border: 2px solid #ccc; background: #f0f0f0;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #888; overflow: hidden; flex-shrink: 0;
}
.rv-avatar img { width: 100%; height: 100%; object-fit: cover; }
.rv-username { font-size: 14px; font-weight: 600; color: #111; }
.rv-header-center {
    font-size: 16px; font-weight: 600; color: #111;
    position: absolute; left: 50%; transform: translateX(-50%);
    white-space: nowrap;
}
.rv-back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: #511D99; color: #fff;
    border: 1px solid #511D99; border-radius: 20px;
    padding: 6px 16px; font-size: 13px; text-decoration: none; cursor: pointer;
    transition: all .2s; white-space: nowrap;
}
.rv-back-btn:hover { background: #3f1777; border-color: #3f1777; color: #fff; }

/* ── SHARED BODY ─────────────────────────────── */
.rv-body {
    display: flex;
    position: fixed;
    top: 60px; left: 0; right: 0; bottom: 104px;
    gap: 12px; padding: 16px;
    background: transparent;
}

/* ── SHARED PANEL ────────────────────────────── */
.rv-panel {
    background: #fff;
    border: none;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.rv-panel-left  { flex: 1 1 48%; }
.rv-panel-right { flex: 1 1 48%; }
.rv-panel-heading {
    font-size: 20px; font-weight: 800;
    color: #111;
    padding: 16px 24px 14px;
    background: #fff;
    border-bottom: none;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}
.rv-panel-body {
    flex: 1; overflow-y: auto; padding: 20px 24px;
}
.rv-panel-body::-webkit-scrollbar { width: 6px; }
.rv-panel-body::-webkit-scrollbar-track { background: #f0f0f0; }
.rv-panel-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }

/* ── DIVIDER ─────────────────────────────────── */
.rv-divider {
    width: 6px; cursor: col-resize; background: transparent;
    flex-shrink: 0; border-radius: 3px; transition: background .15s;
}
.rv-divider:hover { background: #ccc; }

/* ── PASSAGE (R/L) ───────────────────────────── */
.rv-passage-text  { font-size: 15px; line-height: 1.8; color: #111; text-align: justify; }
.rv-passage-text p { margin-bottom: 12px; }
.rv-no-passage { text-align: center; padding: 40px 20px; color: #aaa; font-size: 14px; }
.rv-audio-box { background: #fff; border: none; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 16px; }
.rv-audio-box audio { width: 100%; margin-top: 12px; }

/* ── QUESTIONS (R/L) ─────────────────────────── */
.rv-q-header { margin-bottom: 18px; }
.rv-q-range { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 4px; }
.rv-q-instruction { font-size: 14px; color: #444; line-height: 1.5; font-weight: 600; }
.rv-question { margin-bottom: 28px; }
.rv-question-label { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 8px; }
.rv-question-text { font-size: 15px; font-weight: 700; color: #111; line-height: 1.5; margin-bottom: 12px; padding: 0 8px; }
.rv-options { padding-left: 24px; }
.rv-option { display: flex; align-items: center; gap: 10px; margin-bottom: 9px; padding: 3px 0; font-size: 15px; }
.rv-radio-circle { flex-shrink: 0; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #aaa; background: #fff; display: inline-block; }
.rv-option-text { flex: 1; color: #222; line-height: 1.4; }
.rv-result-icon { font-size: 17px; font-weight: bold; flex-shrink: 0; min-width: 22px; text-align: right; }
.rv-option.selected-correct .rv-radio-circle,
.rv-option.selected-wrong   .rv-radio-circle { background: #111; border-color: #111; }
.rv-option.correct-highlight .rv-radio-circle { background: #fff; border-color: #16a34a; border-width: 2.5px; }
.rv-option.neutral .rv-radio-circle { border-color: #bbb; }
.rv-option.selected-correct .rv-option-text  { color: #111; font-weight: 600; }
.rv-option.selected-wrong   .rv-option-text  { color: #111; font-weight: 600; }
.rv-option.correct-highlight .rv-option-text { color: #16a34a; font-weight: 600; }
.rv-option.neutral .rv-option-text           { color: #333; }
.rv-option.selected-correct .rv-result-icon  { color: #16a34a; }
.rv-option.selected-wrong   .rv-result-icon  { color: #dc2626; }
.rv-option.correct-highlight .rv-result-icon { color: #16a34a; }
.rv-fill-row { display: flex; flex-direction: column; gap: 8px; padding-left: 24px; margin-bottom: 10px; }
.rv-fill-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6b7280; }
.rv-fill-box { display: inline-block; padding: 6px 14px; border-radius: 6px; font-size: 15px; font-weight: 600; border: 2px solid; max-width: 400px; }
.rv-fill-box.correct   { background: #ecfdf5; border-color: #10b981; color: #065f46; }
.rv-fill-box.wrong     { background: #fef2f2; border-color: #ef4444; color: #991b1b; }
.rv-fill-box.no-answer { background: #f3f4f6; border-color: #d1d5db; color: #9ca3af; font-style: italic; }
.rv-fill-correct-ref { display: inline-flex; align-items: center; gap: 6px; font-size: 14px; color: #065f46; font-weight: 600; }
.rv-fill-correct-ref::before { content: "Correct answer: "; color: #6b7280; font-weight: 400; }
.rv-answer-help { margin-top: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #3b82f6; border-radius: 6px; padding: 12px 16px; }
.rv-answer-help-title { font-size: 11px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; color: #1e40af; margin-bottom: 6px; }
.rv-answer-help-text  { font-size: 13px; color: #374151; line-height: 1.65; }
.rv-answer-help-empty { font-size: 12px; color: #9ca3af; font-style: italic; }
.rv-matching-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
.rv-matching-table th { background: #fff; padding: 10px 12px; text-align: center; font-weight: bold; border: 1px solid #d1d5db; min-width: 36px; }
.rv-matching-table th:first-child { text-align: left; }
.rv-matching-table td { padding: 10px 12px; border: 1px solid #d1d5db; background: #fff; }
.rv-matching-table td:not(:first-child) { text-align: center; }
.rv-match-circle { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; font-size: 12px; font-weight: bold; }
.rv-match-circle.correct-choice { background: #10b981; color: #fff; }
.rv-match-circle.wrong-choice   { background: #ef4444; color: #fff; }
.rv-match-circle.correct-ref    { background: #22c55e; color: #fff; }
.rv-match-circle.empty          { border: 1.5px solid #d1d5db; background: #f9fafb; }

/* ── TABLE COMPLETION STYLING ────────────────── */
.idp-table-completion-styled {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: #ffffff;
    box-shadow: none;
    table-layout: auto;
    margin: 12px 0;
}
.idp-table-completion-styled thead {
    background: #e8e8e8;
}
.idp-table-completion-styled th {
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    border: 1px solid #c0c0c0;
    color: #000000;
    font-size: 13px;
    word-wrap: break-word;
    max-width: 200px;
}
.idp-table-completion-styled td {
    padding: 10px 12px;
    border: 1px solid #c0c0c0;
    vertical-align: top;
    line-height: 1.6;
    font-size: 13px;
    color: #333;
    word-wrap: break-word;
    max-width: 250px;
}
.idp-table-completion-styled td .cell-text {
    display: inline;
    margin-bottom: 8px;
    word-wrap: break-word;
}

/* ── SHARED FOOTER ───────────────────────────── */
.rv-footer {
    height: 80px; background: transparent;
    display: flex; align-items: center;
    padding: 0 4px;
    position: fixed; bottom: 12px; left: 12px; right: 12px; z-index: 1000;
}
.rv-footer-half {
    flex: 1; display: flex; align-items: center; justify-content: center; min-width: 0;
}
.rv-q-nav {
    display: flex; align-items: center; gap: 8px;
    background: #fff; border-radius: 16px; padding: 12px 16px;
    overflow-x: auto; flex: 0 1 auto; min-width: 0; max-width: 100%;
}
.rv-qn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; font-size: 14px; cursor: pointer; border-radius: 50%;
    border: 1.5px solid #555; background: #fff; color: #111; transition: all .15s; font-weight: 600;
    flex-shrink: 0;
}
.rv-qn:hover          { background: #f5f5f5; }
.rv-qn.rv-qn-current  { border: 2.5px solid #333 !important; font-weight: 700; color: #fff; background: #333; }
.rv-qn.rv-qn-correct  { border: 1.5px solid #ccc; color: #888; background: #fff; }
.rv-qn.rv-qn-wrong    { border: 2.5px solid #ef4444; color: #dc2626; background: #fff5f5; font-weight: 700; }
.rv-qn.rv-qn-unanswered { border: 1.5px dashed #ccc; color: #aaa; background: #fafafa; }
.rv-nav-buttons { display: flex; align-items: center; gap: 28px; }
.rv-nav-btn { height: 44px; padding: 0 22px; background: #511D99; color: #fff; border: 1px solid #511D99; border-radius: 8px; font-size: 14px; cursor: pointer; white-space: nowrap; transition: all .2s; }
.rv-nav-btn:hover    { background: #3f1777; border-color: #3f1777; }
.rv-nav-btn:disabled { background: #f5f5f5; color: #bbb; border-color: #e5e7eb; cursor: not-allowed; }

/* ══ WRITING REVIEW ══════════════════════════════════ */

/* Writing header - no avatar, has tabs on right */
.wv-header-tabs { display: flex; align-items: stretch; gap: 0; }
.wv-tab {
    padding: 0 22px; height: 60px;
    display: inline-flex; align-items: center;
    font-size: 13px; font-weight: 600; color: #666;
    border-left: 1px solid #eee;
    cursor: pointer; transition: all .2s;
    white-space: nowrap; text-decoration: none;
}
.wv-tab:hover    { background: #f5f5f5; color: #333; }
.wv-tab.active   { background: #f0f0f0; color: #111; border-bottom: 3px solid #111; }

/* Writing body - no fixed bottom offset */
.wv-body {
    display: flex;
    position: fixed;
    top: 60px; left: 0; right: 0; bottom: 0;
    background: transparent;
    gap: 12px; padding: 16px;
    overflow: hidden;
}

/* Writing left panel - stacked sections */
.wv-left { flex: 0 0 44%; min-width: 260px; display: flex; flex-direction: column; gap: 12px; overflow-y: auto; }
.wv-left::-webkit-scrollbar { width: 5px; }
.wv-left::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }

/* Writing card */
.wv-card {
    background: #fff; border-radius: 10px;
    border: none;
    overflow: hidden; flex-shrink: 0;
}
.wv-card-head {
    background: #fff; padding: 14px 20px;
    font-size: 16px; font-weight: 900;
    color: #111; letter-spacing: 0.3px;
    border-bottom: none;
}
.wv-card-body { padding: 18px 20px; }

/* Question prompt collapsible */
.wv-prompt { font-size: 14px; line-height: 1.65; color: #222; }
.wv-prompt-clamp { overflow: hidden; }
.wv-see-more {
    display: inline-block; margin-top: 10px;
    font-size: 13px; color: #2563eb; cursor: pointer;
    background: none; border: none; padding: 0;
    font-family: inherit;
}
.wv-see-more:hover { text-decoration: underline; }

/* Image box */
.wv-img-box { background: #fff; border: none; border-radius: 8px; margin: 12px 0; overflow: hidden; text-align: center; }
.wv-img-box img { max-width: 100%; display: block; }

/* Essay display */
.wv-essay {
    font-size: 15px; line-height: 1.85;
    color: #111; white-space: pre-wrap;
    font-family: Arial, sans-serif;
}
.wv-essay mark {
    background: #fef9c3; border-radius: 3px; cursor: pointer; position: relative;
}
.wv-essay mark::after {
    content: attr(data-comment);
    display: none; position: absolute; left: 0; top: 100%; z-index: 100;
    background: #1e293b; color: #fff; font-size: 12px; padding: 5px 9px;
    border-radius: 5px; white-space: normal; min-width: 160px; max-width: 260px;
    box-shadow: 0 4px 12px rgba(0,0,0,.25);
}
.wv-essay mark:hover::after { display: block; }
.wv-no-essay { color: #aaa; font-style: italic; font-size: 14px; text-align: center; padding: 30px 0; }

/* Writing right panel */
.wv-right { flex: 1; display: flex; flex-direction: column; background: #fff; border-radius: 10px; border: none; overflow: hidden; }
.wv-right-body { flex: 1; overflow-y: auto; padding: 24px 28px; }
.wv-right-body::-webkit-scrollbar { width: 5px; }
.wv-right-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }

/* Tab panel */
.wv-tp { display: none; }
.wv-tp.active { display: block; }

/* Overall grade */
.wv-overall {
    text-align: center; margin-bottom: 24px;
    font-size: 18px; font-weight: 800; color: #111; letter-spacing: 0.5px;
}
.wv-grade-val {
    font-size: 36px; font-weight: 900; color: #111;
    display: block; margin-top: 2px;
}
.wv-grade-pending { font-size: 14px; color: #aaa; font-style: italic; }

/* Criteria 2x2 grid */
.wv-criteria-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 12px; margin-bottom: 24px;
}
.wv-crit-box {
    border: 1.5px solid #e5e7eb; border-radius: 8px;
    padding: 14px 16px;
    display: flex; align-items: center; justify-content: space-between;
}
.wv-crit-label { font-size: 13px; color: #333; font-weight: 600; line-height: 1.3; }
.wv-crit-score {
    min-width: 50px; height: 36px;
    border: 1.5px solid #d1d5db; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; color: #111;
    background: #f9fafb; flex-shrink: 0; margin-left: 12px;
}
.wv-crit-score.empty { color: #ccc; font-size: 12px; }

/* Feedback */
.wv-feedback-section { margin-top: 20px; }
.wv-feedback-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; margin-bottom: 10px; }
.wv-feedback-body { font-size: 14px; line-height: 1.75; color: #374151; white-space: pre-wrap; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; }
.wv-feedback-empty { font-size: 13px; color: #aaa; font-style: italic; }

/* Grader info */
.wv-grader-info { margin-top: 20px; font-size: 13px; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 14px; }

/* Not ready panel */
.wv-not-ready { text-align: center; padding: 60px 20px; color: #aaa; }
.wv-not-ready-icon { font-size: 40px; margin-bottom: 16px; }
.wv-not-ready-text { font-size: 15px; }

/* Divider */
.wv-divider { width: 6px; cursor: col-resize; background: transparent; flex-shrink: 0; border-radius: 3px; transition: background .15s; }
.wv-divider:hover { background: #ccc; }

/* ══ SPEAKING REVIEW ═══════════════════════════════════════ */
.sp-rv-header {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    display: flex; align-items: stretch; gap: 10px;
    padding: 10px 16px; background: transparent;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, sans-serif;
}
.sp-rv-hblock {
    background: #fff; border: none; border-radius: 12px;
    display: flex; flex-direction: column; justify-content: center; gap: 10px;
    padding: 14px 22px;
}
/* Left wrapper: occupies same width as the card's left panel (55%) so criteria block sits above the right panel */
.sp-rv-header-left {
    flex: 0 0 calc(55% - 26px); /* 55vw minus header-left-pad(16) minus gap(10) */
    display: flex; gap: 10px;
}
.sp-rv-hblock-back,
.sp-rv-hblock-toggle {
    flex: 1;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
}
/* make button itself center even if it has intrinsic width */
.sp-rv-hblock-back .sp-rv-hbtn,
.sp-rv-hblock-toggle .sp-rv-hbtn {
    margin: auto;
}
.sp-rv-hblock-criteria {
    flex: 1;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 0; overflow: hidden;
}
/* Shared 3-column scoring grid used in both header criteria and native speaker block */
.sp-rv-score-3col {
    display: inline-grid;
    grid-template-columns: 110px 190px 80px;
    gap: 0 60px;
    align-items: center;
}
.sp-rv-s3c-circle {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    flex-shrink: 0;
}
.sp-rv-s3c-labels {
    display: flex; flex-direction: column; gap: 4px;
}
.sp-rv-s3c-boxes {
    display: flex; flex-direction: column; gap: 4px;
}
.sp-rv-s3c-lbl {
    font-size: 13px; font-weight: 600; color: #222;
    height: 28px; display: flex; align-items: center; white-space: nowrap;
}
.sp-rv-htitle {
    font-size: 15px; font-weight: 700; color: #111;
    line-height: 1.4; letter-spacing: 0;
    text-transform: uppercase;
}
.sp-rv-hbtn {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid #511D99; border-radius: 999px;
    padding: 7px 20px; font-size: 14px; font-weight: 500;
    background: #511D99; color: #fff; text-decoration: none;
    cursor: pointer; align-self: flex-start;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, sans-serif;
    transition: all .2s; white-space: nowrap;
}
.sp-rv-hbtn:hover { background: #3f1777; border-color: #3f1777; color: #fff; text-decoration: none; }
.sp-rv-grader-label {
    font-size: 16px; font-weight: 700; color: #111; white-space: nowrap;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, sans-serif;
}
.sp-rv-overall-circle {
    width: 90px; height: 90px; border-radius: 50%;
    border: 3px solid #333;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 900; color: #111; flex-shrink: 0;
}
.sp-rv-criteria-hgrid {
    display: flex; flex-direction: column;
    gap: 4px; flex-shrink: 0; min-width: 0; align-items: flex-start;
}
.sp-rv-crit-hitem { display: flex; align-items: center; gap: 30px; }
.sp-rv-crit-hlbl  { font-size: 13px; font-weight: 600; color: #222; white-space: nowrap; width: 185px; flex-shrink: 0; }
.sp-rv-crit-hbox {
    width: 80px; height: 28px; border: 1.5px solid #ccc; border-radius: 5px;
    background: #f5f5f5; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #111;
}
/* Speaking body */
.sp-rv-body {
    position: fixed; left: 0; right: 0; bottom: 104px;
    background: transparent; overflow: hidden;
}
.sp-rv-qpanel {
    display: flex; width: 100%; height: 100%;
    padding: 12px 16px; gap: 0;
}
/* Single unified card wrapping both left+right panels */
.sp-rv-card {
    flex: 1; display: flex; flex-direction: column;
    background: #fff; border: none; border-radius: 10px;
    overflow: hidden;
}
.sp-rv-card-head {
    font-size: 20px; font-weight: 900; padding: 14px 24px;
    background: #fff; border-bottom: none;
    flex-shrink: 0; letter-spacing: 0.5px; color: #111;
}
.sp-rv-card-body {
    flex: 1; display: flex; overflow: hidden;
}
.sp-rv-left {
    flex: 0 0 55%; border-right: none;
    display: flex; flex-direction: column; overflow: hidden;
}
.sp-rv-right {
    flex: 1; display: flex; flex-direction: column; overflow: hidden;
}
/* Kept for back-compat but not used for heading anymore */
.sp-rv-section-head {
    font-size: 17px; font-weight: 800; padding: 14px 20px;
    background: #f0f0f0; border-bottom: 1px solid #ddd;
    flex-shrink: 0; letter-spacing: 0.3px;
}
.sp-rv-pbody { flex: 1; overflow-y: auto; padding: 20px 24px; }
.sp-rv-pbody::-webkit-scrollbar { width: 5px; }
.sp-rv-pbody::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
.sp-rv-qlabel { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 2px; }
.sp-rv-qtext  { font-size: 14px; color: #333; line-height: 1.65; margin-bottom: 20px; }
/* Video (native speaker) */
.sp-rv-video-wrap {
    border-radius: 10px; overflow: hidden; margin: 0 auto 16px;
    background: #2a2a2a; border: 1px solid #222;
    max-width: 440px; width: 100%;
}
.sp-rv-video-wrap video {
    width: 100%; display: block; background: #000;
    max-height: 180px;
}
.sp-rv-video-placeholder {
    max-width: 440px; width: 100%; aspect-ratio: 16/9;
    margin: 0 auto;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 10px;
}
.sp-rv-video-placeholder svg { opacity: .4; }
.sp-rv-video-placeholder span { font-size: 12px; color: #777; }
/* Your answer audio box */
.sp-rv-student-wrap {
    background: #fff; border: none; border-radius: 12px;
    overflow: hidden; margin: 0 auto 6px;
    max-width: 440px; width: 100%;
}
.sp-rv-student-label {
    font-size: 13px; font-weight: 600; color: #222;
    text-align: center; padding: 10px 14px 6px; display: block;
    border-bottom: 1px solid #eee;
}
.sp-rv-student-audio {
    width: 100%; display: block; padding: 6px 12px 10px;
}
.sp-rv-audio-empty {
    font-size: 13px; color: #aaa; font-style: italic;
    padding: 12px 14px 14px; display: block; text-align: center;
}
/* Native speaker right-panel block */
.sp-rv-native-block {
    display: flex; justify-content: center;
    margin-bottom: 20px;
    background: transparent; border: none;
}
.sp-rv-native-label {
    font-size: 15px; font-weight: 800; color: #111;
    white-space: nowrap; text-align: center;
}
.sp-rv-native-score-circle {
    width: 90px; height: 90px; border-radius: 50%;
    border: 3px solid #888;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 700; color: #333; flex-shrink: 0;
}
/* Per-question criteria (Mentor tab) */
.sp-rv-criteria-q {
    display: flex; flex-direction: column; gap: 9px;
    flex: 1; align-self: center;
}
.sp-rv-crit-qrow {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; padding: 0;
    border: none; border-radius: 0; background: transparent;
}
.sp-rv-crit-qlabel { font-size: 13px; font-weight: 700; color: #222; }
.sp-rv-crit-qbox {
    width: 80px; height: 28px; border: 1.5px solid #ccc; border-radius: 5px;
    background: #f5f5f5; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #111;
}
.sp-rv-sep   { height: 1px; background: #e5e7eb; margin: 18px 0; }
.sp-rv-stitle {
    font-size: 14px; font-weight: 700; color: #111;
    margin-bottom: 10px; text-transform: none; letter-spacing: 0;
}
.sp-rv-textbox {
    background: #fff; border: 1px solid #d1d5db; border-radius: 8px;
    padding: 14px 16px; font-size: 14px; line-height: 1.75;
    color: #374151; white-space: pre-wrap;
}
.sp-rv-textbox-empty { color: #aaa; font-style: italic; }
/* Speaking footer */
.sp-rv-footer {
    position: fixed; bottom: 12px; left: 12px; right: 12px; z-index: 1000;
    height: 80px; background: transparent;
    display: flex; align-items: center;
    padding: 0 4px;
}
.sp-rv-qnav {
    display: flex; align-items: center; gap: 10px;
    background: #fff; border-radius: 16px; padding: 12px 16px;
    overflow-x: auto; flex: 0 1 auto; min-width: 0; max-width: 100%;
}

.rv-qn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; font-size: 15px; cursor: pointer;
    border-radius: 50%; border: 1.5px solid #555;
    background: #fff; color: #111; transition: all .2s; font-weight: 600;
    flex-shrink: 0;
}
.rv-qn:hover { background: #f5f5f5; }
.rv-qn.rv-qn-current { border: 2.5px solid #333 !important; background: #333; color: #fff; font-weight: 700; }
.rv-nav-buttons {
    display: flex; align-items: center; gap: 28px;
}
.rv-nav-btn {
    height: 44px; padding: 0 28px; background: #511D99; color: #fff;
    border: 1px solid #511D99; border-radius: 8px; font-size: 14px;
    cursor: pointer; white-space: nowrap; transition: all .2s;
}
.rv-nav-btn:hover { background: #3f1777; border-color: #3f1777; }
.rv-nav-btn:disabled { background: #f5f5f5; color: #aaa; border-color: #ddd; cursor: not-allowed; }
</style>
</head>
<body>

@php
    /* ── Detect which skill is being reviewed ─────────────── */
    // 1. Explicit ?skill= query param (set by multi-skill results page)
    $reviewSkill = request('skill');
    if ($reviewSkill && !in_array($reviewSkill, ['listening','reading','writing','speaking'])) {
        $reviewSkill = null;
    }

    // 2. current_skill on the attempt
    if (!$reviewSkill) $reviewSkill = $attempt->current_skill ?? null;

    // 3. Fallback: completed flags (prefer speaking/writing over R/L)
    if (!$reviewSkill) {
        if ($attempt->speaking_completed)      $reviewSkill = 'speaking';
        elseif ($attempt->writing_completed)   $reviewSkill = 'writing';
        elseif ($attempt->reading_completed)   $reviewSkill = 'reading';
        elseif ($attempt->listening_completed) $reviewSkill = 'listening';
    }

    // 3. Fallback: derive from test sections that exist
    if (!$reviewSkill) {
        $skills = $test->sections->pluck('skill')->unique()->values();
        if ($skills->contains('speaking'))      $reviewSkill = 'speaking';
        elseif ($skills->contains('writing'))   $reviewSkill = 'writing';
        elseif ($skills->contains('reading'))   $reviewSkill = 'reading';
        else                                    $reviewSkill = 'listening';
    }

    // Normalise
    if (!in_array($reviewSkill, ['listening','reading','writing','speaking'])) $reviewSkill = 'listening';

    /* ── Section sets ─────────────────────────────────────── */
    $rlSections      = $test->sections->filter(fn($s) => in_array($s->skill, ['listening','reading']))->values();
    $writingSections = $test->sections->filter(fn($s) => $s->skill === 'writing')->values();

    $isWritingReview  = ($reviewSkill === 'writing');
    $isSpeakingReview = ($reviewSkill === 'speaking');


    $u = $attempt->user ?? auth()->user();
    $isOwner = $attempt->user_id === auth()->id();

    /* ── Reading/Listening data ─────────────── */
    $allQuestions = [];
    $jsQuestions  = [];
    $jsSectionTitles = [];

    $resolvedPassages = [];
$resolvedAudios = [];

    if (!$isWritingReview && !$isSpeakingReview) {
        foreach ($rlSections as $si => $section) {
            // Resolve passage: ưu tiên nội dung từ Part (inline builder), fallback về Section
            $parts = $section->parts()->orderBy('sort_order')->get();
            $passageText = $section->passage_text ?? $section->content ?? '';
            if (empty(trim(strip_tags($passageText))) && $parts->isNotEmpty()) {
                $passageText = $parts->pluck('passage')->filter()->implode('<hr style="margin:20px 0;">');
            }
            $resolvedPassages[$si] = $passageText;

            // Resolve audio tương tự (ưu tiên audio của Part)
            $partAudio = $parts->pluck('audio_file')->filter()->first();
            $resolvedAudios[$si] = $section->audio_url
                ?? ($partAudio ? \Storage::disk('public')->url($partAudio) : null);

            foreach ($section->questions->sortBy('question_number') as $question) {
                $answer    = $attempt->answers->where('question_id', $question->id)->first();
                $hasAnswer = $answer && !empty($answer->answer_text);
                $isCorrect = $hasAnswer && $answer->is_correct;
                $allQuestions[] = [
                    'sectionIdx' => $si, 'question' => $question,
                    'answer' => $answer, 'hasAnswer' => $hasAnswer, 'isCorrect' => $isCorrect,
                ];
            }
        }

        $jsQuestions = array_values(array_map(fn($q) => [
            'sectionIdx' => $q['sectionIdx'],
            'qNum'       => $q['question']->question_number,
            'isCorrect'  => $q['isCorrect'],
            'hasAnswer'  => $q['hasAnswer'],
        ], $allQuestions));

        $jsSectionTitles = $rlSections->values()->map(fn($s, $i) => [
            'skill' => $s->skill,
            'title' => $s->title ?: ('Part '.($i+1)),
        ])->values()->toArray();
    }

    /* ── Speaking sections ──────────────────── */
    $speakingSections = $test->sections->filter(fn($s) => $s->skill === 'speaking')->values();

    if ($isSpeakingReview) {
        $spBand     = $attempt->speaking_band;
        $spFeedback = $attempt->speaking_feedback ?? null;
        $spGradedAt = $attempt->speaking_graded_at ?? null;

        $spCriteriaRaw = $attempt->speaking_criteria;
        $spCriteria    = is_array($spCriteriaRaw) ? $spCriteriaRaw
                         : (is_string($spCriteriaRaw) ? json_decode($spCriteriaRaw, true) : []);
        $spCriteria    = $spCriteria ?? [];

        $spGrader = null;
        if (!empty($attempt->speaking_graded_by)) {
            try { $spGrader = \App\User::find($attempt->speaking_graded_by); } catch(\Exception $e) {}
        }

        $spCriteriaMap = [
            'fluency'       => ['keys' => ['fluency','fluency_and_coherence','fc'],
                                'label' => 'Fluency and Coherence'],
            'lexical'       => ['keys' => ['lexical','lexical_resource','vocabulary','lr'],
                                'label' => 'Vocabulary'],
            'grammar'       => ['keys' => ['grammar','grammatical_range','grammar_and_accuracy','gra','grammatical_range_and_accuracy'],
                                'label' => 'Grammatical Range &amp; Accuracy'],
            'pronunciation' => ['keys' => ['pronunciation','p'],
                                'label' => 'Pronunciation'],
        ];

        $spScoreFn = function(array $keys, ?array $map): ?float {
            if (!$map) return null;
            $lower = array_change_key_case($map, CASE_LOWER);
            foreach ($keys as $k) {
                if (isset($lower[strtolower($k)])) return (float) $lower[strtolower($k)];
            }
            return null;
        };

        $spQuestionsData = [];
        foreach ($speakingSections as $section) {
            foreach ($section->questions->sortBy('question_number') as $question) {
                $answer = $attempt->answers->where('question_id', $question->id)->first();
                $answerBands = $answer?->speaking_bands ?? null;
                if (is_string($answerBands)) $answerBands = json_decode($answerBands, true);
                // Normalize AI numeric bands [f,l,g,p] → associative so $spScoreFn can look up by name
                if (is_array($answerBands) && count($answerBands) === 4 && array_keys($answerBands) === [0,1,2,3]) {
                    $answerBands = array_combine(['fluency','lexical','grammar','pronunciation'], $answerBands);
                }
                // Mentor's per-section criteria from $spCriteria['sections']
                $spSectionId       = (string)$section->id;
                $mentorSectionData = $spCriteria['sections'][$spSectionId] ?? null;
                $spQuestionsData[] = [
                    'section'        => $section,
                    'question'       => $question,
                    'answer'         => $answer,
                    'audioUrl'       => $answer?->file_url ?? null,
                    'bands'          => $answerBands ?? [],         // AI per-question bands (assoc)
                    'feedback'       => $answer?->grader_feedback ?? null,  // AI feedback
                    'mentorBands'    => $mentorSectionData,         // teacher's per-section criteria
                    'mentorFeedback' => $mentorSectionData['feedback'] ?? null,
                    'modelAnswer'    => $question->explanation ?? null,
                    'nativeAudio'    => (function() use ($question, $section) {
                        // Prefer question group video file
                        $groupVideoFile = $question->questionGroup->video_file ?? null;
                        if ($groupVideoFile) {
                            return \Storage::disk('public')->url($groupVideoFile);
                        }
                        // Fallback to section video
                        $sectionVideo = $section->video_url ?? null;
                        if ($sectionVideo) {
                            return $sectionVideo;
                        }
                        // Last resort: question audio
                        return $question->question_audio ?? null;
                    })(),
                ];
            }
        }

        // AI overall band + per-criterion averages for the header score block
        $aiSpBand = $attempt->speaking_score ?? null;
        $_aiSpKeys = ['fluency', 'lexical', 'grammar', 'pronunciation'];
        $aiSpCriteriaAccum = array_fill_keys($_aiSpKeys, []);
        foreach ($spQuestionsData as $_spQ) {
            foreach ($_aiSpKeys as $k) {
                $v = $spScoreFn([$k], $_spQ['bands']);
                if ($v !== null) { $aiSpCriteriaAccum[$k][] = $v; }
            }
        }
        $aiSpCriteria = [];
        foreach ($_aiSpKeys as $k) {
            $vals = $aiSpCriteriaAccum[$k];
            $aiSpCriteria[$k] = !empty($vals) ? round(array_sum($vals) / count($vals) * 2) / 2 : null;
        }
    }

    /* ── Writing data ───────────────────────── */
    if ($isWritingReview) {
        $writingBand     = $attempt->writing_band     ?? null;
        $writingFeedback = $attempt->writing_feedback ?? null;
        $writingCriteria = $attempt->writing_criteria ?? [];
        $writingGrader   = null;
        try { $writingGrader = $attempt->writingGrader; } catch(\Exception $e) {}
        $writingGradedAt = $attempt->writing_graded_at ?? null;

        $writingQuestionsData = [];
        foreach ($writingSections as $section) {
            foreach ($section->questions->sortBy('question_number') as $question) {
                $answer = $attempt->answers->where('question_id', $question->id)->first();
                $writingQuestionsData[] = [
                    'section'  => $section,
                    'question' => $question,
                    'essay'    => $answer->answer_text ?? null,
                    'answer'   => $answer,
                    'partNum'  => $section->part_number ?? 1,
                ];
            }
        }

        $writingCriteriaMap = [
            'task_achievement' => 'Task Achievement / Response',
            'coherence'        => 'Coherence &amp; Cohesion',
            'lexical'          => 'Lexical Resource',
            'grammar'          => 'Grammatical Range &amp; Accuracy',
        ];
    }
@endphp

@if($isWritingReview)
{{-- ╔══════════════════════════════════════════╗
     ║        WRITING REVIEW LAYOUT             ║
     ╚══════════════════════════════════════════╝ --}}

{{-- HEADER --}}
<header class="rv-header">
    <div class="rv-header-left">
        <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="rv-back-btn">
            &larr; Back
        </a>
    </div>

    <div class="rv-header-center">
        WRITING &ndash; {{ $test->title ?? '' }}
    </div>

    <div class="rv-header-right" style="padding-right: 0; flex: unset;">
        <div class="wv-header-tabs">
            <a href="#" class="wv-tab active" onclick="switchTab('ai', this); return false;">
                AI Grading
            </a>
            <a href="#" class="wv-tab" onclick="switchTab('model', this); return false;">
                Model Answer
            </a>
            <a href="#" class="wv-tab" onclick="switchTab('mentor', this); return false;">
                Mentor Grading
            </a>
        </div>
    </div>
</header>

{{-- BODY --}}
<div class="wv-body">

    {{-- LEFT: task + essay ─────────────────── --}}
    <div class="wv-left" id="wvLeft">

        @foreach($writingQuestionsData as $wqIdx => $wq)
            @php
                $taskText = $wq['question']->question_text ?? $wq['section']->content ?? $wq['section']->passage_text ?? '';
                // Resolve task image: try questionGroup relation, fall back to section lookup
                $wqGroup = $wq['question']->questionGroup ?? null;
                if (!$wqGroup) {
                    $wqGroup = \App\Models\IeltsQuestionGroup::where('section_id', $wq['section']->id)->first();
                }
                $wqRawImg = $wqGroup?->task_image ?? null;
                $imageUrl = null;
                if ($wqRawImg) {
                    $imageUrl = (str_starts_with($wqRawImg, 'http') || str_starts_with($wqRawImg, '/'))
                        ? $wqRawImg
                        : \Storage::disk('public')->url($wqRawImg);
                } else {
                    $imageUrl = $wq['question']->image_url ?? $wq['section']->image_url ?? null;
                }
                $taskType = 'Task ' . ($wqIdx + 1);
                $graderName = $writingGrader ? ($writingGrader->full_name ?? $writingGrader->name ?? '') : '';
            @endphp

            {{-- Question prompt card --}}
            <div class="wv-card">
                <div class="wv-card-head">
                    WRITING &ndash;
                    @if($graderName)
                        Mentor: {{ $graderName }}
                    @else
                        {{ $taskType }}
                    @endif
                </div>
                <div class="wv-card-body">
                    <p class="wv-prompt" id="wvPromptText-{{ $wqIdx }}" style="font-size:13px; color:#555; margin-bottom:8px;">
                        Question {{ $wq['question']->question_number ?? ($wqIdx+1) }}: You should spend about
                        {{ ($wqIdx === 0) ? '20' : '40' }} minutes on this task.
                    </p>
                    <div class="wv-prompt" id="wvPromptBody-{{ $wqIdx }}">
                        @if($taskText)
                            <div class="wv-prompt-clamp" id="wvClamp-{{ $wqIdx }}" style="max-height: 120px; overflow: hidden;">
                                {!! $taskText !!}
                            </div>
                            <button class="wv-see-more" id="wvSeeMore-{{ $wqIdx }}" onclick="togglePrompt({{ $wqIdx }})">
                                See more
                            </button>
                        @else
                            <span style="color:#aaa; font-style:italic; font-size:13px;">No question prompt available.</span>
                        @endif
                    </div>

                    @if($imageUrl)
                        <div class="wv-img-box"><img src="{{ $imageUrl }}" alt="Task diagram"></div>
                    @endif
                </div>
            </div>

            {{-- Essay card --}}
            <div class="wv-card">
                <div class="wv-card-head">YOUR WRITING</div>
                <div class="wv-card-body">
                    @php
                        $reviewAnnotated = $writingCriteria['annotated_essays'] ?? [];
                        $reviewAnswerId  = (string)($wq['answer']?->id ?? '');
                        $reviewAnnotHtml = $reviewAnswerId !== '' ? ($reviewAnnotated[$reviewAnswerId] ?? null) : null;
                    @endphp
                    @if($wq['essay'] || $reviewAnnotHtml)
                        @if($reviewAnnotHtml)
                            <div class="wv-essay">{!! $reviewAnnotHtml !!}</div>
                        @else
                            <div class="wv-essay">{{ $wq['essay'] }}</div>
                        @endif
                        @php
                            $wordCount = str_word_count(strip_tags($wq['essay'] ?? ''));
                        @endphp
                        <div style="margin-top:14px; font-size:12px; color:#9ca3af; border-top:1px solid #f3f4f6; padding-top:10px;">
                            Word count: <strong>{{ $wordCount }}</strong>
                        </div>
                    @else
                        <div class="wv-no-essay">No essay submitted for this task.</div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>{{-- end wv-left --}}

    {{-- DIVIDER --}}
    <div class="wv-divider" id="wvDivider"></div>

    {{-- RIGHT: grading panels ──────────────── --}}
    <div class="wv-right">
        <div class="wv-right-body">

            {{-- ▶ MENTOR GRADING TAB --}}
            <div class="wv-tp" id="wv-tp-mentor">

                @if($writingBand !== null)
                    <div class="wv-overall">
                        OVERALL GRADE
                        <span class="wv-grade-val">{{ number_format($writingBand, 1) }}</span>
                    </div>
                @else
                    <div class="wv-overall">
                        OVERALL GRADE
                        <span class="wv-grade-pending" style="display:block; margin-top:8px;">Not yet graded</span>
                    </div>
                @endif

                @if(!empty($writingCriteria))
                    <div class="wv-criteria-grid">
                        @foreach($writingCriteriaMap as $key => $label)
                            <div class="wv-crit-box">
                                <span class="wv-crit-label">{!! $label !!}</span>
                                <div class="wv-crit-score {{ isset($writingCriteria[$key]) ? '' : 'empty' }}">
                                    @if(isset($writingCriteria[$key]))
                                        {{ number_format((float)$writingCriteria[$key], 1) }}
                                    @else
                                        &mdash;
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wv-criteria-grid">
                        @foreach($writingCriteriaMap as $key => $label)
                            <div class="wv-crit-box">
                                <span class="wv-crit-label">{!! $label !!}</span>
                                <div class="wv-crit-score empty">&mdash;</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="wv-feedback-section">
                    <div class="wv-feedback-title">Feedback</div>
                    @if($writingFeedback)
                        <div class="wv-feedback-body">{!! clean($writingFeedback) !!}</div>
                    @else
                        <div class="wv-feedback-empty">No feedback has been provided yet.</div>
                    @endif
                </div>

                @if($writingGrader)
                    <div class="wv-grader-info">
                        Graded by <strong>{{ $writingGrader->full_name ?? $writingGrader->name ?? 'Mentor' }}</strong>
                        @if($writingGradedAt)
                            &middot; {{ \Carbon\Carbon::createFromTimestamp($writingGradedAt)->format('d M Y, H:i') }}
                        @endif
                    </div>
                @elseif(!$writingBand)
                    <div class="wv-grader-info" style="text-align:center; padding:20px 0;">
                        This essay is waiting to be graded by a mentor.
                    </div>
                @endif

            </div>{{-- end mentor tab --}}

            {{-- ▶ AI GRADING TAB --}}
            <div class="wv-tp active" id="wv-tp-ai">
                <div class="wv-not-ready">
                    <div class="wv-not-ready-icon">&#129302;</div>
                    <div class="wv-not-ready-text" style="font-weight:700; color:#555; margin-bottom:8px;">AI Grading</div>
                    <div class="wv-not-ready-text">AI auto-grading is not available for this attempt.</div>
                </div>
            </div>

            {{-- ▶ MODEL ANSWER TAB --}}
            <div class="wv-tp" id="wv-tp-model">
                @php
                    $anyModelAnswer = false;
                    foreach ($writingQuestionsData as $wq) {
                        if (!empty($wq['question']->explanation ?? null)) { $anyModelAnswer = true; break; }
                    }
                @endphp

                @if($anyModelAnswer)
                    @foreach($writingQuestionsData as $wqIdx => $wq)
                        @if(!empty($wq['question']->explanation))
                            <div style="margin-bottom: 28px;">
                                <div class="wv-feedback-title">Model Answer &ndash; Task {{ $wq['partNum'] ?? ($wqIdx+1) }}</div>
                                <div class="wv-feedback-body">{!! clean($wq['question']->explanation) !!}</div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="wv-not-ready">
                        <div class="wv-not-ready-icon">&#128196;</div>
                        <div class="wv-not-ready-text" style="font-weight:700; color:#555; margin-bottom:8px;">Model Answer</div>
                        <div class="wv-not-ready-text">No model answer has been provided for this test.</div>
                    </div>
                @endif
            </div>

        </div>{{-- end wv-right-body --}}
    </div>{{-- end wv-right --}}

</div>{{-- end wv-body --}}

<script>
function switchTab(tab, el) {
    document.querySelectorAll('.wv-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.wv-tp').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('wv-tp-' + tab)?.classList.add('active');
}

function togglePrompt(idx) {
    const clamp  = document.getElementById('wvClamp-' + idx);
    const btn    = document.getElementById('wvSeeMore-' + idx);
    if (!clamp) return;
    const isOpen = clamp.style.maxHeight === 'none';
    clamp.style.maxHeight = isOpen ? '120px' : 'none';
    clamp.style.overflow  = isOpen ? 'hidden' : 'visible';
    btn.textContent = isOpen ? 'See more' : 'See less';
}

// Resizable divider
(function () {
    const div   = document.getElementById('wvDivider');
    const left  = document.getElementById('wvLeft');
    if (!div || !left) return;
    let drag = false;
    div.addEventListener('mousedown',  () => { drag = true;  document.body.style.cursor = 'col-resize'; });
    document.addEventListener('mouseup',    () => { drag = false; document.body.style.cursor = ''; });
    document.addEventListener('mousemove', e => {
        if (!drag) return;
        const body = left.parentElement;
        const rect = body.getBoundingClientRect();
        const pct  = ((e.clientX - rect.left) / rect.width) * 100;
        if (pct > 20 && pct < 72) {
            left.style.flex = '0 0 ' + pct + '%';
        }
    });
})();
</script>

@elseif($isSpeakingReview)
{{-- ╔══════════════════════════════════════════╗
     ║       SPEAKING REVIEW LAYOUT             ║
     ╚══════════════════════════════════════════╝ --}}

@php
    $spGraderName = '';
    if (!empty($spGrader)) {
        $spGraderName = $spGrader->full_name ?? $spGrader->name ?? '';
    }
@endphp

{{-- HEADER --}}
<header class="sp-rv-header" id="spHeader">

    {{-- Blocks 1+2: Title/back + grader toggle — spans same width as left panel (55%) --}}
    <div class="sp-rv-header-left">
        <div class="sp-rv-hblock sp-rv-hblock-back">
            <div class="sp-rv-htitle">Speaking &ndash; {{ $test->title ?? '' }}</div>
            <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="sp-rv-hbtn">&larr; Back</a>
        </div>

        {{-- Label shows the TARGET tab (what you'll switch to). Default view = AI, so label = "Mentor" --}}
        <div class="sp-rv-hblock sp-rv-hblock-toggle">
            <div class="sp-rv-grader-label" id="spGraderLabel">Bài chấm của Mentor</div>
            <button class="sp-rv-hbtn" onclick="spToggleTab()">Xem &rarr;</button>
        </div>
    </div>

    {{-- Block 3: Overall scores — AI version (default) + Mentor version, toggled by JS --}}
    <div class="sp-rv-hblock sp-rv-hblock-criteria">
        {{-- AI version (shown when AI tab active) --}}
        <div id="spHdrAI" class="sp-rv-score-3col">
            <div class="sp-rv-s3c-circle">
                <div class="sp-rv-overall-circle">
                    {!! $aiSpBand ? number_format((float)$aiSpBand, 1) : '&mdash;' !!}
                </div>
            </div>
            <div class="sp-rv-s3c-labels">
                @foreach($spCriteriaMap as $critKey => $crit)
                    <div class="sp-rv-s3c-lbl">{!! $crit['label'] !!}:</div>
                @endforeach
            </div>
            <div class="sp-rv-s3c-boxes">
                @foreach($spCriteriaMap as $critKey => $crit)
                    @php $critScore = $spScoreFn($crit['keys'], $aiSpCriteria); @endphp
                    <div class="sp-rv-crit-hbox">{!! $critScore !== null ? number_format($critScore, 1) : '&mdash;' !!}</div>
                @endforeach
            </div>
        </div>
        {{-- Mentor version (shown when Mentor tab active) --}}
        <div id="spHdrMentor" class="sp-rv-score-3col" style="display:none;">
            <div class="sp-rv-s3c-circle">
                <div class="sp-rv-overall-circle">
                    {!! $spBand ? number_format((float)$spBand, 1) : '&mdash;' !!}
                </div>
            </div>
            <div class="sp-rv-s3c-labels">
                @foreach($spCriteriaMap as $critKey => $crit)
                    <div class="sp-rv-s3c-lbl">{!! $crit['label'] !!}:</div>
                @endforeach
            </div>
            <div class="sp-rv-s3c-boxes">
                @foreach($spCriteriaMap as $critKey => $crit)
                    @php $critScore = $spScoreFn($crit['keys'], $spCriteria); @endphp
                    <div class="sp-rv-crit-hbox">{!! $critScore !== null ? number_format($critScore, 1) : '&mdash;' !!}</div>
                @endforeach
            </div>
        </div>
    </div>

</header>

{{-- BODY --}}
<div class="sp-rv-body" id="spBody">
    @foreach($spQuestionsData as $spIdx => $spQ)
    @php
        $spQScores = [];
        foreach ($spCriteriaMap as $ck => $cv) {
            $sv = $spScoreFn($cv['keys'], $spQ['bands']);
            if ($sv !== null) $spQScores[] = $sv;
        }
        $spQAvg = count($spQScores) ? round(array_sum($spQScores) / count($spQScores), 1) : null;
        $spQPct = $spQAvg !== null ? round(($spQAvg / 9) * 100) . '%' : '&#8212;';
    @endphp
    <div class="sp-rv-qpanel" id="spPanel-{{ $spIdx }}" style="{{ $spIdx === 0 ? '' : 'display:none;' }}">

        {{-- Unified card --}}
        <div class="sp-rv-card">
            {{-- Card heading: SPEAKING --}}
            <div class="sp-rv-card-head" id="spLeftHead-{{ $spIdx }}">SPEAKING</div>

            <div class="sp-rv-card-body">

                {{-- LEFT: Question + Video + Student audio --}}
                <div class="sp-rv-left">
                    <div class="sp-rv-pbody">
                        <div style="margin-bottom:16px;">
                            <div class="sp-rv-qlabel">Questions {{ $spQ['question']->question_number ?? ($spIdx + 1) }}:</div>
                            <div class="sp-rv-qtext">{{ $spQ['question']->question_text ?? '' }}</div>
                        </div>

                        {{-- Native speaker video --}}
                        <div id="spNativeAudioWrap-{{ $spIdx }}" class="sp-rv-video-wrap">
                            @if($spQ['nativeAudio'])
                                <video controls controlsList="nodownload">
                                    <source src="{{ $spQ['nativeAudio'] }}" type="video/mp4">
                                    <source src="{{ $spQ['nativeAudio'] }}" type="audio/mpeg">
                                    <source src="{{ $spQ['nativeAudio'] }}" type="audio/webm">
                                </video>
                            @else
                                <div class="sp-rv-video-placeholder">
                                    <svg width="52" height="52" fill="#888" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span>No native speaker recording</span>
                                </div>
                            @endif
                        </div>

                        {{-- Student recorded answer --}}
                        <div class="sp-rv-student-wrap">
                            <span class="sp-rv-student-label">Your answer</span>
                            @if($spQ['audioUrl'])
                                <audio controls class="sp-rv-student-audio"><source src="{{ $spQ['audioUrl'] }}">Your browser does not support audio.</audio>
                            @else
                                <span class="sp-rv-audio-empty">No audio recording submitted.</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Criteria + Comments + Model Answer --}}
                <div class="sp-rv-right">
                    <div class="sp-rv-pbody">

                        {{-- ── AI TAB (default) ── --}}
                        <div id="spAI-{{ $spIdx }}">
                            {{-- Native speaker: same 3-column grid as header criteria block --}}
                            <div class="sp-rv-native-block">
                                <div class="sp-rv-score-3col">
                                    <div class="sp-rv-s3c-circle">
                                        <div class="sp-rv-native-label">Native speaker</div>
                                        <div class="sp-rv-native-score-circle">{!! $spQPct !!}</div>
                                    </div>
                                    <div class="sp-rv-s3c-labels">
                                        @foreach($spCriteriaMap as $critKey => $crit)
                                            <div class="sp-rv-s3c-lbl">{!! $crit['label'] !!}:</div>
                                        @endforeach
                                    </div>
                                    <div class="sp-rv-s3c-boxes">
                                        @foreach($spCriteriaMap as $critKey => $crit)
                                            @php $s = $spScoreFn($crit['keys'], $spQ['bands']); @endphp
                                            <div class="sp-rv-crit-qbox">{!! $s !== null ? number_format($s, 1) : '&mdash;' !!}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="sp-rv-sep"></div>
                            <div class="sp-rv-stitle">Comments</div>
                            <div class="sp-rv-textbox">
                                @if($spQ['feedback'])
                                    {!! $spQ['feedback'] !!}
                                @else
                                    <span class="sp-rv-textbox-empty">No comments yet.</span>
                                @endif
                            </div>

                            <div class="sp-rv-sep"></div>
                            <div class="sp-rv-stitle">Model answer</div>
                            <div class="sp-rv-textbox">
                                @if($spQ['modelAnswer'])
                                    {!! nl2br(e($spQ['modelAnswer'])) !!}
                                @else
                                    <span class="sp-rv-textbox-empty">No model answer provided.</span>
                                @endif
                            </div>
                        </div>

                        {{-- ── MENTOR TAB (hidden by default) ── --}}
                        <div id="spMentor-{{ $spIdx }}" style="display:none;">
                            <div class="sp-rv-criteria-q" style="margin-bottom:20px;">
                                @foreach($spCriteriaMap as $critKey => $crit)
                                    @php $s = $spScoreFn($crit['keys'], $spQ['mentorBands']); @endphp
                                    <div class="sp-rv-crit-qrow">
                                        <span class="sp-rv-crit-qlabel">{!! $crit['label'] !!}:</span>
                                        <div class="sp-rv-crit-qbox">{!! $s !== null ? number_format($s, 1) : '&mdash;' !!}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="sp-rv-sep"></div>
                            <div class="sp-rv-stitle">Comments</div>
                            <div class="sp-rv-textbox">
                                @if(!empty($spQ['mentorFeedback']))
                                    {!! $spQ['mentorFeedback'] !!}
                                @else
                                    <span class="sp-rv-textbox-empty">No feedback yet.</span>
                                @endif
                            </div>

                            <div class="sp-rv-sep"></div>
                            <div class="sp-rv-stitle">Model answer</div>
                            <div class="sp-rv-textbox">
                                @if($spQ['modelAnswer'])
                                    {!! nl2br(e($spQ['modelAnswer'])) !!}
                                @else
                                    <span class="sp-rv-textbox-empty">No model answer provided.</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- end card-body --}}
        </div>{{-- end sp-rv-card --}}

    </div>
    @endforeach
</div>

{{-- FOOTER --}}
<footer class="sp-rv-footer">
    {{-- Left half: Question nav circles --}}
    <div class="rv-footer-half">
        <div class="sp-rv-qnav" id="spQNav">
            @foreach($spQuestionsData as $spIdx => $spQ)
                <span class="rv-qn {{ $spIdx === 0 ? 'rv-qn-current' : '' }}"
                      id="spDot-{{ $spIdx }}"
                      onclick="spJump({{ $spIdx }})"
                      title="Q{{ $spQ['question']->question_number ?? ($spIdx+1) }}">
                    {{ $spQ['question']->question_number ?? ($spIdx + 1) }}
                </span>
            @endforeach
        </div>
    </div>
    {{-- Right half: Navigation buttons --}}
    <div class="rv-footer-half">
        <div class="rv-nav-buttons">
            <button id="spBtnPrev" onclick="spPrev()" disabled class="rv-nav-btn">
                &larr; Previous question
            </button>
            <button id="spBtnNext" onclick="spNext()" class="rv-nav-btn">
                Next question &rarr;
            </button>
        </div>
    </div>
</footer>

<script>
(function () {
    const total      = {{ count($spQuestionsData) }};
    const resultsUrl = '{{ route('panel.ielts_tests.results', $attempt->id) }}';
    const mentorName = @json($spGraderName);

    let curr      = 0;
    let activeTab = 'ai'; // default: show AI grading

    // Labels show the TARGET tab (what you'll switch TO by clicking)
    const graderLabels = {
        ai:     'Bài chấm của Mentor',  // currently on AI → button says switch to Mentor
        mentor: 'Bài chấm của AI',      // currently on Mentor → button says switch to AI
    };

    // Left-panel headings per tab
    const leftHeadings = {
        ai:     'SPEAKING',
        mentor: mentorName ? ('SPEAKING – Mentor chấm bài: ' + mentorName) : 'SPEAKING',
    };

    function render() {
        for (let i = 0; i < total; i++) {
            // Panel visibility
            const panel = document.getElementById('spPanel-' + i);
            if (panel) panel.style.display = (i === curr) ? '' : 'none';

            // Tab visibility
            const mentorEl = document.getElementById('spMentor-' + i);
            const aiEl     = document.getElementById('spAI-'     + i);
            if (mentorEl) mentorEl.style.display = (activeTab === 'mentor') ? '' : 'none';
            if (aiEl)     aiEl.style.display     = (activeTab === 'ai')     ? '' : 'none';

            // Left heading
            const head = document.getElementById('spLeftHead-' + i);
            if (head) head.textContent = leftHeadings[activeTab];

            // Native speaker audio/video wrap is always visible on both tabs
        }

        // Header score block: show AI version or Mentor version
        const hdrAI     = document.getElementById('spHdrAI');
        const hdrMentor = document.getElementById('spHdrMentor');
        if (hdrAI)     hdrAI.style.display     = (activeTab === 'ai')     ? '' : 'none';
        if (hdrMentor) hdrMentor.style.display = (activeTab === 'mentor') ? '' : 'none';

        // Toggle label
        const lbl = document.getElementById('spGraderLabel');
        if (lbl) lbl.textContent = graderLabels[activeTab];

        // Dots
        document.querySelectorAll('[id^="spDot-"]').forEach((dot, i) => {
            dot.classList.toggle('rv-qn-current', i === curr);
        });

        // Prev/Next buttons
        const prev = document.getElementById('spBtnPrev');
        const next = document.getElementById('spBtnNext');
        if (prev) prev.disabled = (curr === 0);
        if (next) {
            if (curr >= total - 1) {
                next.textContent = 'Back to Results';
                next.onclick = () => { window.location.href = resultsUrl; };
            } else {
                next.innerHTML = 'Next question &rarr;';
                next.onclick = spNext;
            }
        }
    }

    function adjustBodyTop() {
        const hdr  = document.getElementById('spHeader');
        const body = document.getElementById('spBody');
        if (hdr && body) body.style.top = hdr.offsetHeight + 'px';
    }

    window.spToggleTab = function () {
        activeTab = (activeTab === 'ai') ? 'mentor' : 'ai';
        render();
        adjustBodyTop();
    };
    window.spJump = function (i) { if (i >= 0 && i < total) { curr = i; render(); } };
    window.spNext = function ()  { if (curr < total - 1) { curr++; render(); } };
    window.spPrev = function ()  { if (curr > 0)         { curr--; render(); } };

    document.addEventListener('DOMContentLoaded', function () {
        adjustBodyTop();
        window.addEventListener('resize', adjustBodyTop);
        render();
    });
    if (document.readyState !== 'loading') { adjustBodyTop(); render(); }
})();
</script>

@else
{{-- ╔══════════════════════════════════════════╗
     ║  READING / LISTENING REVIEW LAYOUT       ║
     ╚══════════════════════════════════════════╝ --}}

{{-- HEADER --}}
<header class="rv-header">
    <div class="rv-header-left">
        <div class="rv-avatar">
            @if(!empty($u->avatar))
                <img src="{{ $u->avatar }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            @else
                {{ mb_strtoupper(mb_substr($u->full_name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <span class="rv-username">{{ $u->full_name ?? '' }}</span>
    </div>

    <div class="rv-header-center">
        @if($rlSections->isNotEmpty())
            {{ mb_strtoupper($rlSections->first()->skill) }} &ndash; {{ $test->title ?? '' }}
        @endif
    </div>

    <div class="rv-header-right">
        <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="rv-back-btn">
            &larr; Back to Results
        </a>
    </div>
</header>

<div class="rv-body" id="rvBody">

    {{-- LEFT PANEL --}}
    <div class="rv-panel rv-panel-left" id="rvPanelLeft">
        @foreach($rlSections as $si => $section)
            <div id="rvLeftSection-{{ $si }}" style="display:{{ $si === 0 ? 'flex' : 'none' }}; flex-direction:column; height:100%;">
                <div class="rv-panel-heading">{{ mb_strtoupper($section->skill) }}</div>
                <div class="rv-panel-body">
                    @if($section->skill === 'listening' && !empty($resolvedAudios[$si]))
                        <div class="rv-audio-box">
                            <div style="font-weight:600; margin-bottom:8px;">Audio Recording</div>
                            <audio controls><source src="{{ $resolvedAudios[$si] }}">Your browser does not support audio.</audio>
                            <div style="font-size:13px; color:#777; margin-top:10px;">You can replay the audio while reviewing.</div>
                        </div>
                    @endif
                    @if(!empty($resolvedPassages[$si]))
                        <div class="rv-passage-text">{!! $resolvedPassages[$si] !!}</div>
                    @else
                        <div class="rv-no-passage">No passage content available.</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- DIVIDER --}}
    <div class="rv-divider" id="rvDivider"></div>

    {{-- RIGHT PANEL --}}
    <div class="rv-panel rv-panel-right" id="rvPanelRight">
        @foreach($rlSections as $si => $section)
            <div id="rvRightSection-{{ $si }}" style="display:{{ $si === 0 ? 'flex' : 'none' }}; flex-direction:column; height:100%;">
                <div class="rv-panel-heading">ANSWER SHEET</div>
                <div class="rv-panel-body">

                    @php
                        $sectionQuestions = $section->questions->sortBy('question_number');
                        $qStart   = $sectionQuestions->first()?->question_number ?? 1;
                        $qEnd     = $sectionQuestions->last()?->question_number  ?? $sectionQuestions->count();
                        $qFlatIdx = 0;
                        foreach ($allQuestions as $idx => $aqd) {
                            if ($aqd['sectionIdx'] == $si) { $qFlatIdx = $idx; break; }
                        }
                        $instrMap = [
                            'true_false_not_given'     => 'Do the following statements agree with the information given?',
                            'yes_no_not_given'         => 'Do the following statements reflect the claims of the writer?',
                            'multiple_choice'          => 'Choose the correct answer.',
                            'multiple_choice_single'   => 'Choose the correct letter, A, B, C or D.',
                            'multiple_choice_multiple' => 'Choose TWO letters, A-E.',
                            'sentence_completion'      => 'Complete the sentences. Write NO MORE THAN TWO WORDS for each answer.',
                            'note_completion'          => 'Complete the notes. Write NO MORE THAN TWO WORDS AND/OR A NUMBER for each answer.',
                            'short_answer'             => 'Answer the questions. Write NO MORE THAN THREE WORDS AND/OR A NUMBER for each answer.',
                            'matching_features'        => 'Match each statement with the correct option.',
                            'matching_headings'        => 'Match each paragraph with the correct heading.',
                            'matching_information'     => 'Which paragraph contains the following information?',
                        ];
                        $firstType = $sectionQuestions->first()?->question_type ?? '';
                    @endphp

                    <div class="rv-q-header">
                        <div class="rv-q-range">Questions {{ $qStart }}&ndash;{{ $qEnd }}</div>
                        @if(isset($instrMap[$firstType]))
                            <div class="rv-q-instruction">{{ $instrMap[$firstType] }}</div>
                        @endif
                    </div>

                    @php $localIdx = 0; @endphp
                    @foreach($sectionQuestions as $question)
                        @php
                            $qFlatIdxCur = $qFlatIdx + $localIdx;
                            $answer      = $attempt->answers->where('question_id', $question->id)->first();
                            $hasAns      = $answer && !empty($answer->answer_text);
                            $isCorr      = $hasAns && $answer->is_correct;
                            $studentAns  = $hasAns ? trim($answer->answer_text) : null;
                            $correctAns  = $question->formatted_correct_answer ?? ($question->correct_answer ?? null);
                            $tableHeaders = [];
                            $tableRows = [];
                            $tableAnswersMap = [];
                            $savedMap = [];

                            if ($question->question_type === 'table_completion') {
                                $tableStructure = $question->table_structure ?? null;

                                if (is_string($tableStructure)) {
                                    $tableStructure = json_decode($tableStructure, true);
                                }

                                $tableHeaders = is_array($tableStructure) ? ($tableStructure['headers'] ?? []) : [];
                                $tableRows = is_array($tableStructure) ? ($tableStructure['rows'] ?? []) : [];

                                $savedAnswerData = $answer ? ($answer->answer_options ?? $answer->answer_text ?? null) : null;
                                if (is_string($savedAnswerData)) {
                                    $savedAnswerData = json_decode($savedAnswerData, true);
                                }

                                if (is_array($savedAnswerData) && !empty($savedAnswerData['answers']) && is_array($savedAnswerData['answers'])) {
                                    foreach ($savedAnswerData['answers'] as $savedAnswerItem) {
                                        if (isset($savedAnswerItem['row'], $savedAnswerItem['col'])) {
                                            $savedMap[$savedAnswerItem['row'] . '-' . $savedAnswerItem['col']] = $savedAnswerItem['answer'] ?? '';
                                        }
                                    }
                                }

                                foreach ($question->table_completion_answers_array as $answerItem) {
                                    $tableAnswersMap[$answerItem['row'] . '-' . $answerItem['col']] = $answerItem['answers'] ?? [];
                                }
                            }

                            $options = [];
                            if (!empty($question->answer_options)) {
                                $raw = is_string($question->answer_options)
                                    ? json_decode($question->answer_options, true)
                                    : $question->answer_options;
                                if (is_array($raw)) $options = $raw;
                            }

                            $qType      = $question->question_type ?? 'fill_blank';
                            $isMCQ      = in_array($qType, ['multiple_choice','multiple_choice_single','true_false_not_given','true_false','yes_no_not_given']);
                            $isMatching = str_contains($qType, 'matching');
                            $isFill     = !$isMCQ && !$isMatching;

                            if (in_array($qType, ['true_false_not_given','true_false']))
                                $options = [['key'=>'True','text'=>'True'],['key'=>'False','text'=>'False'],['key'=>'Not given','text'=>'Not given']];
                            elseif ($qType === 'yes_no_not_given')
                                $options = [['key'=>'Yes','text'=>'Yes'],['key'=>'No','text'=>'No'],['key'=>'Not given','text'=>'Not given']];
                        @endphp

                        <div class="rv-question" id="rvQ-{{ $question->question_number }}" data-qidx="{{ $qFlatIdxCur }}">
                            <div class="rv-question-label">Question {{ $question->question_number }}:</div>
                            @if($question->question_text)
                                <div class="rv-question-text">{!! $question->question_text !!}</div>
                            @endif

                            @if($question->question_type === 'table_completion')
                                @if(!empty($tableHeaders) || !empty($tableRows))
                                    <div class="table-completion-container">
                                        <table class="idp-table-completion-styled">
                                            @if(!empty($tableHeaders))
                                                <thead>
                                                    <tr>
                                                        @foreach($tableHeaders as $header)
                                                            <th>{!! nl2br(e($header)) !!}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                            @endif
                                            <tbody>
                                                @foreach($tableRows as $rowIndex => $row)
                                                    <tr>
                                                        @foreach($row as $colIndex => $cellContent)
                                                            @php
                                                                $cellText = is_string($cellContent) ? $cellContent : (string) $cellContent;
                                                                $cellKey = $rowIndex . '-' . $colIndex;
                                                                $savedValue = $savedMap[$cellKey] ?? '';
                                                                $cellAnswers = $tableAnswersMap[$cellKey] ?? [];
                                                                $normalizedStudent = strtolower(trim((string) $savedValue));
                                                                $isMatch = false;
                                                                foreach ($cellAnswers as $candidateAnswer) {
                                                                    if ($normalizedStudent !== '' && $normalizedStudent === strtolower(trim((string) $candidateAnswer))) {
                                                                        $isMatch = true;
                                                                        break;
                                                                    }
                                                                }
                                                                $correctLabel = !empty($cellAnswers) ? implode(' / ', $cellAnswers) : '';
                                                                $parts = preg_split('/(___)/', $cellText, -1, PREG_SPLIT_DELIM_CAPTURE);
                                                                $hasBlank = is_array($parts) && count($parts) > 1;
                                                            @endphp
                                                            <td>
                                                                @if($hasBlank)
                                                                    @foreach($parts as $part)
                                                                        @if($part === '___')
                                                                            <div class="rv-fill-row">
                                                                                <span class="rv-fill-label">{{ $isOwner ? 'Your Answer' : "Student's Answer" }}</span>
                                                                                @if($savedValue !== '')
                                                                                    <div class="rv-fill-box {{ $isMatch ? 'correct' : 'wrong' }}">
                                                                                        {{ $savedValue }} {!! $isMatch ? '&#10003;' : '&#10007;' !!}
                                                                                    </div>
                                                                                @else
                                                                                    <div class="rv-fill-box no-answer">(no answer provided)</div>
                                                                                @endif
                                                                                @if($correctLabel !== '')
                                                                                    <div class="rv-fill-correct-ref">{{ $correctLabel }}</div>
                                                                                @endif
                                                                            </div>
                                                                        @elseif(trim($part) !== '')
                                                                            <span class="cell-text">{!! nl2br(e($part)) !!}</span>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {!! nl2br(e($cellText)) !!}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <strong>Table structure not found.</strong> Please contact your instructor.
                                    </div>
                                @endif
                            @elseif($isMCQ && count($options))
                                <div class="rv-options">
                                    @foreach($options as $optIdx => $opt)
                                        @php
                                            $optKey  = is_array($opt) ? ($opt['key']  ?? chr(65+(int)$optIdx)) : chr(65+(int)$optIdx);
                                            $optText = is_array($opt) ? ($opt['text'] ?? $opt['label'] ?? $opt) : $opt;
                                            $sPicked = $hasAns && (
                                                strtolower(trim($studentAns)) === strtolower($optKey)  ||
                                                strtolower(trim($studentAns)) === strtolower($optText)
                                            );
                                            $isCorrectOpt = $correctAns && (
                                                strtolower(trim($correctAns)) === strtolower($optKey)  ||
                                                strtolower(trim($correctAns)) === strtolower($optText)
                                            );
                                            if      ($sPicked && $isCorrectOpt)              { $cls = 'selected-correct'; $icon = '&#10003;'; }
                                            elseif  ($sPicked && !$isCorrectOpt)             { $cls = 'selected-wrong';   $icon = '&#10007;'; }
                                            elseif  (!$sPicked && $isCorrectOpt && $hasAns)  { $cls = 'correct-highlight'; $icon = '&#10003;'; }
                                            else                                              { $cls = 'neutral';          $icon = ''; }
                                        @endphp
                                        <div class="rv-option {{ $cls }}">
                                            <span class="rv-radio-circle"></span>
                                            <span class="rv-option-text">{{ $optText }}</span>
                                            <span class="rv-result-icon">{!! $icon !!}</span>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($isFill)
                                <div class="rv-fill-row">
                                    <span class="rv-fill-label">{{ $isOwner ? 'Your Answer' : "Student's Answer" }}</span>
                                    @if($hasAns)
                                        <div class="rv-fill-box {{ $isCorr ? 'correct' : 'wrong' }}">
                                            {{ $studentAns }} {!! $isCorr ? '&#10003;' : '&#10007;' !!}
                                        </div>
                                        @if(!$isCorr && $correctAns)
                                            <div class="rv-fill-correct-ref">{{ $correctAns }}</div>
                                        @endif
                                    @else
                                        <div class="rv-fill-box no-answer">(no answer provided)</div>
                                        @if($correctAns)
                                            <div class="rv-fill-correct-ref">{{ $correctAns }}</div>
                                        @endif
                                    @endif
                                </div>

                            @elseif($isMatching && count($options))
                                @php
                                    $matchKeys = [];
                                    foreach ($options as $mo) { $k = is_array($mo) ? ($mo['key'] ?? '') : ''; if ($k) $matchKeys[] = $k; }
                                    if (empty($matchKeys)) $matchKeys = array_map(fn($i) => chr(65+$i), range(0, count($options)-1));
                                @endphp
                                <table class="rv-matching-table">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left;">Statement</th>
                                            @foreach($matchKeys as $mk)<th>{{ $mk }}</th>@endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($options as $rIdx => $row)
                                            @php
                                                $rowText    = is_array($row) ? ($row['text'] ?? $row['statement'] ?? '') : $row;
                                                $rowCorrect = is_array($row) ? ($row['correct'] ?? '') : '';
                                            @endphp
                                            <tr>
                                                <td>{{ (int)$rIdx + 1 }}. {{ $rowText }}</td>
                                                @foreach($matchKeys as $mk)
                                                    @php
                                                        $sChose  = ($hasAns && strtoupper(trim($studentAns)) === strtoupper($mk));
                                                        $isRight = (strtoupper($mk) === strtoupper($rowCorrect));
                                                    @endphp
                                                    <td>
                                                        @if($sChose && $isRight)       <span class="rv-match-circle correct-choice">&#10003;</span>
                                                        @elseif($sChose && !$isRight)  <span class="rv-match-circle wrong-choice">&#10007;</span>
                                                        @elseif(!$sChose && $isRight && $hasAns) <span class="rv-match-circle correct-ref">&#10003;</span>
                                                        @else                          <span class="rv-match-circle empty"></span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <div class="rv-answer-help">
                                <div class="rv-answer-help-title">ANSWER HELP</div>
                                @if(!empty($question->explanation))
                                    <div class="rv-answer-help-text">{!! clean($question->explanation) !!}</div>
                                @else
                                    <div class="rv-answer-help-empty">No explanation provided.</div>
                                @endif
                            </div>
                        </div>
                        @php $localIdx++; @endphp
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>

</div>{{-- end rv-body --}}

{{-- FOOTER --}}
<footer class="rv-footer">
    {{-- Left half: Question nav circles --}}
    <div class="rv-footer-half">
        <div class="rv-q-nav" id="rvQNav">
            @foreach($allQuestions as $qi => $qData)
                @php $dotClass = $qData['hasAnswer'] ? ($qData['isCorrect'] ? 'rv-qn-correct' : 'rv-qn-wrong') : 'rv-qn-unanswered'; @endphp
                <span class="rv-qn {{ $dotClass }} {{ $qi === 0 ? 'rv-qn-current' : '' }}"
                      id="rvDot-{{ $qi }}"
                      onclick="jumpToQuestion({{ $qi }})"
                      title="Q{{ $qData['question']->question_number }}:{{ $qData['hasAnswer'] ? ($qData['isCorrect'] ? 'Correct' : 'Incorrect') : 'Unanswered' }}">
                    {{ $qData['question']->question_number }}
                </span>
            @endforeach
        </div>
    </div>
    {{-- Right half: Navigation buttons + question counter --}}
    <div class="rv-footer-half">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 13px; color: #666; font-weight: 500; white-space: nowrap;">
                Question <span id="rvQCount">1</span> of <span id="rvQTotal">{{ count($allQuestions) }}</span>
            </div>
            <div class="rv-nav-buttons">
                <button class="rv-nav-btn" id="rvBtnPrev" onclick="prevQuestion()" disabled>&larr; Previous question</button>
                <button class="rv-nav-btn" id="rvBtnNext" onclick="nextQuestion()">Next question &rarr;</button>
            </div>
        </div>
    </div>
</footer>

<script>
(function () {
    const questions  = @json($jsQuestions);
    const totalQ     = questions.length;
    const totalSects = {{ $rlSections->count() }};
    const resultsUrl = '{{ route('panel.ielts_tests.results', $attempt->id) }}';
    let   currentQIdx = 0;

    function render() {
        if (totalQ === 0) return;
        const cur    = questions[currentQIdx];
        const secIdx = cur.sectionIdx;

        for (let i = 0; i < totalSects; i++) {
            const l = document.getElementById('rvLeftSection-'  + i);
            const r = document.getElementById('rvRightSection-' + i);
            if (l) l.style.display = (i === secIdx) ? 'flex' : 'none';
            if (r) r.style.display = (i === secIdx) ? 'flex' : 'none';
        }

        const qEl = document.getElementById('rvQ-' + cur.qNum);
        if (qEl) qEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        document.querySelectorAll('.rv-qn').forEach((dot, idx) => {
            dot.classList.toggle('rv-qn-current', idx === currentQIdx);
        });

        // Update question counter
        const qCountEl = document.getElementById('rvQCount');
        if (qCountEl) qCountEl.textContent = (currentQIdx + 1);

        document.getElementById('rvBtnPrev').disabled = (currentQIdx === 0);
        const nextBtn = document.getElementById('rvBtnNext');
        if (currentQIdx >= totalQ - 1) {
            nextBtn.textContent = 'Back to Results';
            nextBtn.onclick = () => { window.location.href = resultsUrl; };
        } else {
            nextBtn.innerHTML = 'Next question &rarr;';
            nextBtn.onclick = nextQuestion;
        }
    }

    window.jumpToQuestion = function (idx) { if (idx >= 0 && idx < totalQ) { currentQIdx = idx; render(); } };
    window.nextQuestion   = function ()    { if (currentQIdx < totalQ - 1) { currentQIdx++; render(); } };
    window.prevQuestion   = function ()    { if (currentQIdx > 0)          { currentQIdx--; render(); } };

    // Resizable divider
    const div   = document.getElementById('rvDivider');
    const left  = document.getElementById('rvPanelLeft');
    const right = document.getElementById('rvPanelRight');
    if (div && left && right) {
        let drag = false;
        div.addEventListener('mousedown', () => { drag = true; document.body.style.cursor = 'col-resize'; });
        document.addEventListener('mouseup',    () => { drag = false; document.body.style.cursor = ''; });
        document.addEventListener('mousemove', e => {
            if (!drag) return;
            const body = left.parentElement;
            const rect = body.getBoundingClientRect();
            const pct  = ((e.clientX - rect.left) / rect.width) * 100;
            if (pct > 18 && pct < 82) {
                left.style.flex  = '0 0 ' + pct + '%';
                right.style.flex = '0 0 ' + (100 - pct - 1) + '%';
            }
        });
    }

    render();
})();
</script>

@endif

@php
    /* ── IELTS Grading Star-Rating Modal data ───────────────────────── */
    $ratingInstructorId   = null;
    $ratingInstructorName = '';
    $ratingSkill          = null;

    if ($isWritingReview && ($writingGrader ?? null)) {
        $ratingInstructorId   = $writingGrader->id;
        $ratingInstructorName = $writingGrader->full_name ?? $writingGrader->name ?? '';
        $ratingSkill          = 'writing';
    } elseif ($isSpeakingReview && ($spGrader ?? null)) {
        $ratingInstructorId   = $spGrader->id;
        $ratingInstructorName = $spGrader->full_name ?? $spGrader->name ?? '';
        $ratingSkill          = 'speaking';
    }

    $showRatingModal = false;
    if ($ratingInstructorId && $isOwner) {
        $alreadyRated = \App\Models\IeltsGradingRating::where('attempt_id', $attempt->id)
            ->where('student_id', auth()->id())
            ->where('skill', $ratingSkill)
            ->exists();
        $showRatingModal = !$alreadyRated;
    }
@endphp

@if($showRatingModal)
{{-- ╔════════════════════════════════════════════╗
     ║   IELTS GRADING STAR-RATING MODAL          ║
     ╚════════════════════════════════════════════╝ --}}
<style>
#gradingRatingModal {
    display: none;
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(0,0,0,0.45);
    align-items: center; justify-content: center;
}
.grm-card {
    background: #fff;
    border-radius: 18px;
    padding: 36px 32px 28px;
    max-width: 400px; width: 90%;
    text-align: center;
    box-shadow: 0 12px 48px rgba(0,0,0,0.22);
    animation: grmIn .22s ease;
}
@keyframes grmIn { from { transform: scale(.92); opacity:0; } to { transform: scale(1); opacity:1; } }
.grm-icon { font-size: 40px; margin-bottom: 10px; }
.grm-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
.grm-sub   { font-size: 14px; color: #6b7280; margin-bottom: 24px; line-height: 1.5; }
.grm-sub strong { color: #374151; }
.grm-stars { display: flex; justify-content: center; gap: 6px; margin-bottom: 28px; }
.grm-star {
    background: none; border: none; cursor: pointer;
    font-size: 42px; color: #d1d5db; padding: 2px;
    transition: color .12s, transform .1s;
    line-height: 1;
}
.grm-star:hover, .grm-star.active { color: #f59e0b; transform: scale(1.1); }
.grm-actions { display: flex; gap: 10px; justify-content: center; }
.grm-btn-send {
    background: #4f46e5; color: #fff; border: none;
    border-radius: 9px; padding: 11px 30px;
    font-size: 15px; font-weight: 600; cursor: pointer;
    transition: background .2s;
}
.grm-btn-send:hover:not(:disabled) { background: #4338ca; }
.grm-btn-send:disabled { opacity: .55; cursor: not-allowed; }
.grm-btn-skip {
    background: #f3f4f6; color: #6b7280; border: none;
    border-radius: 9px; padding: 11px 20px;
    font-size: 15px; cursor: pointer;
    transition: background .2s;
}
.grm-btn-skip:hover { background: #e5e7eb; }
.grm-msg { margin-top: 12px; font-size: 13px; color: #ef4444; min-height: 16px; }
</style>

<div id="gradingRatingModal">
    <div class="grm-card">
        <div class="grm-icon">⭐</div>
        <div class="grm-title">Đánh Giá Bài Chấm</div>
        <div class="grm-sub">
            Bài chấm của <strong>{{ $ratingInstructorName }}</strong><br>
            có hữu ích với bạn không?
        </div>
        <div class="grm-stars" id="grmStars">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" class="grm-star" data-val="{{ $i }}"
                        onclick="grmSetStar({{ $i }})"
                        aria-label="{{ $i }} sao">&#9733;</button>
            @endfor
        </div>
        <div class="grm-actions">
            <button type="button" class="grm-btn-send" id="grmSendBtn" onclick="grmSubmit()">
                Gửi đánh giá
            </button>
            <button type="button" class="grm-btn-skip" onclick="grmSkip()">
                Bỏ qua
            </button>
        </div>
        <div class="grm-msg" id="grmMsg"></div>
    </div>
</div>

<script>
(function () {
    const GRM_ROUTE      = '{{ route("panel.ielts_grading.rate") }}';
    const GRM_CSRF       = '{{ csrf_token() }}';
    const GRM_ATTEMPT    = {{ (int) $attempt->id }};
    const GRM_INSTRUCTOR = {{ (int) $ratingInstructorId }};
    const GRM_SKILL      = '{{ $ratingSkill }}';
    const GRM_RESULT_URL = '{{ route("panel.ielts_tests.results", $attempt->id) }}';

    let grmSelected  = 0;
    let grmTargetUrl = GRM_RESULT_URL;
    const modal      = document.getElementById('gradingRatingModal');

    /* ── Show modal ─────────────────────────────────────────── */
    window.grmShow = function (url) {
        grmTargetUrl = url || GRM_RESULT_URL;
        grmSelected  = 0;
        document.querySelectorAll('.grm-star').forEach(s => s.classList.remove('active'));
        document.getElementById('grmMsg').textContent = '';
        const btn = document.getElementById('grmSendBtn');
        btn.disabled = false;
        btn.textContent = 'Gửi đánh giá';
        modal.style.display = 'flex';
    };

    /* ── Star rating ─────────────────────────────────────────── */
    window.grmSetStar = function (val) {
        grmSelected = val;
        document.querySelectorAll('.grm-star').forEach(function (s) {
            if (parseInt(s.dataset.val) <= val) {
                s.classList.add('active');
            } else {
                s.classList.remove('active');
            }
        });
    };

    document.querySelectorAll('.grm-star').forEach(function (s) {
        s.addEventListener('mouseover', function () {
            const hov = parseInt(s.dataset.val);
            document.querySelectorAll('.grm-star').forEach(function (b) {
                b.style.color = parseInt(b.dataset.val) <= hov ? '#fbbf24' : '';
            });
        });
        s.addEventListener('mouseleave', function () {
            document.querySelectorAll('.grm-star').forEach(function (b) {
                b.style.color = '';
            });
        });
    });

    /* ── Skip ───────────────────────────────────────────────── */
    window.grmSkip = function () {
        modal.style.display = 'none';
        window.location.href = grmTargetUrl;
    };

    /* ── Submit ─────────────────────────────────────────────── */
    window.grmSubmit = function () {
        if (grmSelected === 0) {
            document.getElementById('grmMsg').textContent = 'Vui lòng chọn số sao trước khi gửi.';
            return;
        }
        const btn = document.getElementById('grmSendBtn');
        btn.disabled = true;
        btn.textContent = 'Đang gửi...';

        fetch(GRM_ROUTE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': GRM_CSRF,
            },
            body: JSON.stringify({
                attempt_id:    GRM_ATTEMPT,
                instructor_id: GRM_INSTRUCTOR,
                skill:         GRM_SKILL,
                rating:        grmSelected,
            }),
        })
        .then(function () {
            modal.style.display = 'none';
            window.location.href = grmTargetUrl;
        })
        .catch(function () {
            modal.style.display = 'none';
            window.location.href = grmTargetUrl;
        });
    };

    /* ── Intercept all "leave to results" navigation ─────────── */
    document.addEventListener('click', function (e) {
        const el = e.target.closest('a, button');
        if (!el) return;

        const isBackLink = el.tagName === 'A' && (
            el.classList.contains('rv-back-btn') ||
            (el.classList.contains('sp-rv-hbtn') && el.getAttribute('href'))
        );
        const isLastNextBtn = el.tagName === 'BUTTON' &&
            (el.id === 'spBtnNext' || el.id === 'rvBtnNext') &&
            el.textContent.trim() === 'Back to Results';

        if (isBackLink || isLastNextBtn) {
            e.preventDefault();
            e.stopPropagation();
            const dest = isBackLink ? (el.href || GRM_RESULT_URL) : GRM_RESULT_URL;
            window.grmShow(dest);
        }
    }, true);

    /* ── Close on backdrop click ─────────────────────────────── */
    modal.addEventListener('click', function (e) {
        if (e.target === modal) grmSkip();
    });
})();
</script>
@endif

</body>
</html>