@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<style>
/* ─── Page wrapper ─── */
.gd-page { width: 100%; box-sizing: border-box; }

/* ─── Top row ─── */
.gd-top-row {
    display: flex; gap: 14px; margin-bottom: 16px; align-items: stretch;
}
.gd-info-card {
    flex: 2 1 0; background: #fff; border-radius: 14px;
    padding: 22px 55px;
    align-items: center;
    display: flex; flex-direction: column; justify-content: center; gap: 7px;
}
.gd-info-title {
    font-weight: 700; font-size: 1.28rem; letter-spacing: .03em;
    text-transform: uppercase; color: #111; line-height: 1.45;
}
.gd-info-sub { font-size: 1.28rem; color: #4b5563; }
.gd-info-sub strong { color: #111; font-weight: 700; }

/* AI card — 1/3 width, switches between summary and detail */
.gd-ai-card {
    flex: 2 1 0; background: #fff; border-radius: 14px;
    padding: 20px 22px;
    display: flex; flex-direction: column; align-items: center;
}
/* Summary sub-view */
#gdAISummary {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 10px; text-align: center;
}
.gd-ai-card-label  { font-size: 1.28rem; color: color: #111; font-weight: 600; }
.gd-ai-card-score  { font-size: 2.4rem; font-weight: 800; color: #111; line-height: 1; }
.gd-ai-detail-btn  {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fff; border: 1px solid #d1d5db; color: #374151;
    padding: 10px 25px; border-radius: 20px; font-size: 1rem; font-weight: 600;
    cursor: pointer; text-decoration: none; transition: border-color .18s, color .18s;
    white-space: nowrap;
}
.gd-ai-detail-btn:hover { border-color: #6366f1; color: #6366f1; text-decoration: none; }
/* Detail sub-view */
#gdAIDetail { display: none; width: 100%; }
.gd-ai-detail-header {
    display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;
}
.gd-ai-detail-title { font-weight: 700; font-size: .88rem; color: #111; }
.gd-ai-overall {
    text-align: center; padding: 6px 0 12px;
    border-bottom: 1px solid #d1d5db; margin-bottom: 12px;
}
.gd-ai-overall-score { font-size: 2rem; font-weight: 800; color: #2563eb; line-height: 1; }
.gd-ai-overall-label { font-size: .76rem; color: #6b7280; margin-top: 4px; }
/* AI criteria rows — mirror teacher rows but read-only */
.gd-ai-crit-row { display: flex; align-items: center; gap: 10px; margin-bottom: 9px; }
.gd-ai-crit-label { flex: 1; font-size: .8rem; font-weight: 700; color: #374151; }
.gd-ai-crit-val {
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px;
    padding: 6px 14px; font-weight: 800; color: #2563eb; font-size: .9rem;
    min-width: 52px; text-align: center;
}

/* ─── Generic section card ─── */
.gd-card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 14px; overflow: hidden; }

/* ─── Collapsible header (Đề thi) ─── */
.gd-collapse-hdr {
    display: flex; align-items: center; justify-content: center; position: relative;
    background: #e5e7eb; border-radius: 10px; padding: 14px 18px;
    cursor: pointer; user-select: none;
    font-weight: 700; font-size: .9rem; color: #374151;
    margin-bottom: 0;
}
.gd-collapse-hdr .gd-chev {
    position: absolute; right: 16px; font-size: .8rem; color: #6b7280;
    transition: transform .24s;
}
.gd-collapse-hdr.is-open .gd-chev { transform: rotate(180deg); }
.gd-collapse-body { display: none; padding: 14px 18px 2px; }
.gd-collapse-body.is-open { display: block; }
.gd-question-prompt {
    background: #fff; border-left: 3px solid #6366f1;
    padding: 10px 14px; border-radius: 0 6px 6px 0;
    font-size: .88rem; line-height: 1.7; margin-bottom: 10px;
}

/* ─── Essay card ─── */
.gd-essay-card {
    background: #fff; border-radius: 10px; padding: 14px 18px;
    margin-bottom: 14px;
}
.gd-essay-card-label {
    text-align: center; font-weight: 700; font-size: .88rem;
    color: #374151; margin-bottom: 10px;
}
.gd-essay-hint {
    font-size: .75rem; color: #9ca3af; font-style: italic; margin-bottom: 12px; text-align: center;
}
.gd-essay-body {
    font-size: .9rem; line-height: 1.85; white-space: pre-wrap;
    color: #111; min-height: 80px; padding: 14px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;
    cursor: text; user-select: text; -webkit-user-select: text;
    outline: none;
}
.gd-essay-body mark {
    background: #fef9c3; border-radius: 3px; cursor: pointer; position: relative;
}
.gd-essay-body mark::after {
    content: attr(data-comment);
    display: none; position: absolute; left: 0; top: 100%; z-index: 200;
    background: #1e293b; color: #fff; font-size: .74rem; padding: 5px 9px;
    border-radius: 5px; white-space: normal; min-width: 180px; max-width: 280px;
    box-shadow: 0 4px 12px rgba(0,0,0,.25);
}
.gd-essay-body mark:hover::after { display: block; }

/* ─── Feedback editor card ─── */
.gd-feedback-card {
    background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
    margin-bottom: 14px; overflow: hidden;
}
/* Summernote tweaks */
.note-editor.note-frame { border: none !important; box-shadow: none !important; background: #fff !important; }
.note-editor.note-frame .note-toolbar { 
    background: #f9fafb !important; border-bottom: 1px solid #e5e7eb; border-radius: 10px 10px 0 0;
    display: flex; justify-content: center; padding: 8px 4px !important;
}
.note-editor.note-frame .note-toolbar .note-btn-group {
    display: flex; justify-content: center;
}
.note-editor.note-frame .note-editable { 
    border-radius: 0 0 10px 10px; background: #fff !important; padding: 16px 14px !important; font-size: .9rem;
    min-height: 200px;
}

/* ensure annotation modal sits above any dark backdrop */
#gdAnnotModal { z-index: 20000 !important; }
#gdAnnotModal .modal-dialog { z-index: 20001 !important; }
/* keep bootstrap default backdrop slightly underneath */
.modal-backdrop { z-index: 19990 !important; }

/* close button appearance: make it just an × without box */
.modal-header .close {
    padding: 0 !important;
    margin: 0 !important;
    width: auto !important;
    height: auto !important;
    background: transparent !important;
    border: none !important;
    font-size: 1.2rem;
    line-height: 1;
    opacity: .6;
}
.modal-header .close:hover {
    opacity: 1;
}

/* ─── Bottom row: criteria + score ─── */
.gd-bottom-row { display: grid; grid-template-columns: 1fr 1.6fr; gap: 28px; align-items: start; margin-bottom: 16px; }
.gd-crit-col { display: flex; flex-direction: column; gap: 0; }
.gd-score-col { display: flex; flex-direction: column; gap: 0; }

.gd-crit-row {
    display: flex; align-items: center; gap: 14px; margin-bottom: 16px;
    min-height: 36px; justify-content: space-between;
}
.gd-crit-label {
    flex: 0 1 auto; font-size: .82rem; font-weight: 700; color: #374151;
    white-space: nowrap;
}
.gd-crit-bar-input {
    flex: 0 0 280px;
    border: 1px solid #d1d5db; border-radius: 6px;
    padding: 9px 12px; font-size: .92rem; font-weight: 700;
    color: #2563eb; background: #fff; text-align: center;
    transition: border-color .18s, background .18s;
}
.gd-crit-bar-input:focus { outline: none; border-color: #6366f1; }

/* ─── Score box (2 inputs height only) ─── */
.gd-score-box {
    background: #fff; border: 1px solid #d1d5db; border-radius: 16px; padding: 16px 20px;
    text-align: center; height: 88px; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0; margin-bottom: 16px;
}
.gd-score-box-label { font-size: 1.1rem; color: #111; font-weight: 700; letter-spacing: -.01em; }
.gd-score-display { font-size: 2.2rem; color: #111; font-weight: 800; margin-top: 8px; min-height: 36px; line-height: 1; }
.gd-score-input {
    width: 100%; text-align: center;
    border: none; border-radius: 0;
    padding: 0; font-size: 1rem; font-weight: 400;
    color: #111; background: transparent; display: none;
}
.gd-score-input:focus { outline: none; }
.gd-score-hint { font-size: .72rem; color: #999; max-width: 180px; line-height: 1.4; display: none; }

/* ─── Actions (aligned with edges of score box) ─── */
.gd-actions { display: flex; justify-content: space-between; width: 100%; margin-top: 52px; margin-bottom: 0; }
.gd-btn-exit {
    background: #fff; border: 1px solid #d1d5db; color: #111;
    padding: 10px 20px; border-radius: 16px; font-weight: 700; font-size: .88rem;
    text-decoration: none; transition: all .18s; text-align: center; width: 200px;
}
.gd-btn-exit:hover { background: #f5f5f5; }
.gd-btn-save {
    background: #fff; border: 1px solid #d1d5db; color: #111;
    padding: 10px 20px; border-radius: 16px; font-weight: 700; font-size: .88rem;
    cursor: pointer; transition: all .18s; width: 200px;
}
.gd-btn-save:hover { background: #f5f5f5; }
.gd-btn-save:disabled { background: #e5e5e5; cursor: not-allowed; }

/* ─── Floating annotation toolbar ─── */
#gd-annot-toolbar {
    display: none; position: fixed; z-index: 9999;
    background: #1e293b; border-radius: 6px; padding: 4px 6px;
    box-shadow: 0 4px 14px rgba(0,0,0,.3);
}
#gd-annot-toolbar button {
    background: none; border: none; color: #fff; font-size: .78rem;
    cursor: pointer; padding: 3px 10px; border-radius: 4px;
}
#gd-annot-toolbar button:hover { background: rgba(255,255,255,.14); }

/* ─── AI modal criteria rows ─── */
.gd-ai-modal-row {
    display: flex; align-items: center; gap: 12px; padding: 9px 0;
    border-bottom: 1px solid #f3f4f6;
}
.gd-ai-modal-row:last-child { border-bottom: none; }
.gd-ai-modal-name { flex: 1; font-size: .86rem; color: #374151; font-weight: 500; }
.gd-ai-modal-val { background: #eff6ff; border-radius: 8px; padding: 5px 14px; font-weight: 800; color: #2563eb; font-size: .9rem; }

@media (max-width: 860px) {
    .gd-top-row { flex-direction: column; }
    .gd-info-card, .gd-ai-card { flex: 1; }
    .gd-bottom-row { grid-template-columns: 1fr; }
    .gd-score-col { width: 100%; }
    .gd-crit-label { flex: 0 0 150px; }
}

/* ═══════════════════════════════════════════════════════════
   SPEAKING GRADING — Per-section two-column layout
   ═══════════════════════════════════════════════════════════ */

/* Whole-section card */
.sp-gd-section-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    margin-bottom: 16px; overflow: hidden;
}

/* Gray two-column header bar */
.sp-gd-sec-header {
    display: grid; grid-template-columns: 1fr 1fr;
    background: #e5e7eb; border-bottom: 1px solid #d1d5db;
}
.sp-gd-sec-hdr-left {
    padding: 13px 18px; font-weight: 700; font-size: .9rem; color: #374151;
    cursor: pointer; user-select: none; border-right: 1px solid #d1d5db;
    display: flex; align-items: center; justify-content: space-between;
}
.sp-gd-sec-hdr-right {
    padding: 13px 18px; font-weight: 700; font-size: .9rem; color: #374151;
    display: flex; align-items: center; justify-content: center;
}
.sp-gd-chev { font-size: .78rem; transition: transform .22s; }
.sp-gd-sec-hdr-left.is-open .sp-gd-chev { transform: rotate(180deg); }

/* Collapsible task area */
.sp-gd-task-body { display: none; padding: 14px 18px 10px; border-bottom: 1px solid #f3f4f6; background: #fafafa; }
.sp-gd-task-body.is-open { display: block; }

/* Media row inside task body */
.sp-gd-task-media-row { display: flex; gap: 12px; align-items: start; margin-top: 12px; }
.sp-gd-media-left, .sp-gd-media-right { flex: 1; }
.sp-gd-media-element { width: 100%; max-width: 240px; border-radius: 6px; }
.sp-gd-media-empty { font-size: .88rem; color: #9ca3af; text-align: center; padding: 14px; border: 1px dashed #d1d5db; border-radius: 6px; }

/* Two-column body (below task) */
.sp-gd-sec-body { display: grid; grid-template-columns: 1fr 1fr; min-height: 260px; }
.sp-gd-left-col { padding: 14px 16px; border-right: 1px solid #f3f4f6; display: flex; flex-direction: column; }
.sp-gd-right-col { padding: 14px 16px; display: flex; flex-direction: column; gap: 10px; }

/* Editor wrapper fills available height */
.sp-gd-editor-wrap { flex: 1; display: flex; flex-direction: column; }
.sp-gd-editor-wrap .note-editor.note-frame { flex: 1; display: flex; flex-direction: column; }
.sp-gd-editor-wrap .note-editor.note-frame .note-editable { flex: 1; min-height: 160px; }

/* Student audio box */
.sp-gd-audio-box {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;
    padding: 10px 14px;
}
.sp-gd-audio-empty { font-size: .8rem; color: #9ca3af; font-style: italic; text-align: center; padding: 6px 0; }

/* AI read-only criteria inside section card */
.sp-gd-ai-crit-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.sp-gd-ai-crit-label { flex: 1; font-size: .82rem; font-weight: 600; color: #374151; }
.sp-gd-ai-crit-val {
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px;
    padding: 5px 14px; font-weight: 800; color: #2563eb; font-size: .88rem;
    min-width: 60px; text-align: center;
}
.sp-gd-ai-feedback-box {
    font-size: .84rem; color: #374151; background: #f9fafb; border: 1px solid #e5e7eb;
    border-radius: 6px; padding: 10px; min-height: 80px; line-height: 1.6;
}

/* AI mode toggle — applied on .gd-page */
.sp-gd-ai-only   { display: none; }
.sp-gd-teach-only { display: block; }
.gd-page.ai-mode .sp-gd-ai-only    { display: block; }
.gd-page.ai-mode .sp-gd-teach-only { display: none; }
/* Special rule: flex containers */
.gd-page.ai-mode .sp-gd-left-col .sp-gd-ai-only { display: flex; flex-direction: column; flex: 1; }
.gd-page.ai-mode .sp-gd-right-col .sp-gd-ai-only { display: flex; flex-direction: column; }
/* Hide save row in AI mode */
.gd-page.ai-mode .sp-gd-save-wrap { display: none; }

/* AI mode banner at top of form */
.sp-gd-ai-banner {
    display: none; background: #eff6ff; border: 1px solid #bfdbfe;
    border-radius: 10px; padding: 10px 20px; margin-bottom: 14px;
    text-align: center; font-size: .87rem; font-weight: 600; color: #1d4ed8;
}
.gd-page.ai-mode .sp-gd-ai-banner { display: block; }
.gd-page.ai-mode .gd-ai-detail-btn.sp-ai-expand { display: none !important; }

/* Speaking bottom scores row */
.sp-gd-bottom-row {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 14px;
    padding: 16px 20px; margin-bottom: 14px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
}
/* center criteria column between circle and actions */
.sp-gd-bottom-row .gd-crit-col {
    flex: 0 1 auto; min-width: 240px; margin: 0 auto;
    text-align: left; /* keep labels left-aligned */
}
.sp-gd-bottom-row .sp-gd-actions-col {
    flex: 0 0 auto;
}
.sp-gd-scores-circle {
    width: 92px; height: 92px; border-radius: 50%; border: 2px solid #d1d5db;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    flex-shrink: 0; gap: 1px;
}
.sp-gd-circle-val { font-size: 1.9rem; font-weight: 800; color: #111; line-height: 1; }
.sp-gd-circle-lbl { font-size: .7rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .03em; }
.sp-gd-totals-grid { flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; min-width: 280px; }
.sp-gd-total-row { display: flex; align-items: center; gap: 8px; }
.sp-gd-total-lbl { flex: 1; font-size: .8rem; font-weight: 600; color: #374151; white-space: nowrap; }
.sp-gd-total-box {
    min-width: 70px; padding: 4px 10px; border: 1px solid #d1d5db;
    border-radius: 6px; text-align: center; font-size: .88rem;
    font-weight: 700; color: #2563eb; background: #eff6ff;
}
.sp-gd-overall-box {
    background: #fff; border: 1px solid #d1d5db; border-radius: 12px;
    padding: 10px 18px; text-align: center; min-width: 100px; flex-shrink: 0;
}
.sp-gd-overall-label { font-size: .76rem; color: #6b7280; font-weight: 600; }
.sp-gd-overall-val { font-size: 2rem; font-weight: 800; color: #111; line-height: 1.1; }
.sp-gd-actions-col { display: flex; flex-direction: column; gap: 8px; align-items: stretch; min-width: 150px; }

@media (max-width: 860px) {
    .sp-gd-sec-body { grid-template-columns: 1fr; }
    .sp-gd-left-col { border-right: none; border-bottom: 1px solid #f3f4f6; }
    .sp-gd-totals-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
@php
    /** @var \App\Models\IeltsTestAttempt $attempt */
    $writingCriteriaKeys = [
        'task_achievement' => 'Task Achievement',
        'coherence'        => 'Coherence & Cohesion',
        'lexical'          => 'Lexical Resource',
        'grammar'          => 'Grammatical Range',
    ];
    $speakingCriteriaKeys = [
        'fluency'       => 'Fluency and Coherence',
        'lexical'       => 'Vocabulary',
        'grammar'       => 'Grammatical Range & Accuracy',
        'pronunciation' => 'Pronunciation',
    ];
    $criteriaMap = $skill === 'writing' ? $writingCriteriaKeys : $speakingCriteriaKeys;

    $teacherCriteria  = $teacherCriteria ?? [];
    $existingFeedback = $existingFeedback ?? '';
    $existingBand     = $existingBand ?? '';
    $aiOverallBand    = $aiOverallBand ?? null;
    $aimBand          = $aimBand ?? null;
    $aiCriteria       = $aiCriteria ?? [];
    $annotatedEssays  = $teacherCriteria['annotated_essays'] ?? [];

    $webinarTitle = optional($test->webinar)->title ?? null;
    $skillLabel   = strtoupper($skill);

    /* ── Speaking-specific: per-section teacher criteria + AI criteria ── */
    $sectionTeacherCriteriaMap = [];
    $aiSectionCriteriaMap = [];
    if ($skill === 'speaking') {
        $sectionsCriteriaStore = $teacherCriteria['sections'] ?? null;
        foreach ($sections as $sec) {
            $sid = (string)$sec->id;
            if ($sectionsCriteriaStore) {
                $sectionTeacherCriteriaMap[$sid] = $sectionsCriteriaStore[$sid] ?? [];
            } else {
                // backward-compat: old flat format — leave per-section inputs empty
                // (flat totals still shown in the bottom totals row via $teacherCriteria)
                $sectionTeacherCriteriaMap[$sid] = [];
            }
        }
        // Per-section AI criteria from per-answer speaking_bands
        $spAiKeys = ['fluency', 'lexical', 'grammar', 'pronunciation'];
        foreach ($sections as $sec) {
            $sid = (string)$sec->id;
            $secAns_ai = $answers->filter(fn($a) => $a->question && $a->question->section_id === $sec->id);
            $bandsAccum = array_fill_keys($spAiKeys, []);
            foreach ($secAns_ai as $ans) {
                $bands = $ans->speaking_bands ?? [];
                if (is_array($bands) && count($bands) === 4) {
                    foreach ($spAiKeys as $i => $k) {
                        $bandsAccum[$k][] = (float)$bands[$i];
                    }
                }
            }
            $secAiCrit = [];
            foreach ($spAiKeys as $k) {
                $secAiCrit[$k] = !empty($bandsAccum[$k])
                    ? round(array_sum($bandsAccum[$k]) / count($bandsAccum[$k]) * 2) / 2
                    : null;
            }
            $aiSectionCriteriaMap[$sid] = $secAiCrit;
        }
    }
@endphp

<div class="gd-page">

    {{-- ════ TOP ROW ════ --}}
    <div class="gd-top-row">

        {{-- Attempt info --}}
        <div class="gd-info-card">
            <div class="gd-info-title">{{ $skillLabel }} &mdash; {{ $test->title }} &mdash; {{ $user->full_name }}</div>
            <div class="gd-info-sub">Aim Band: <strong>{{ $aimBand ?? '-' }}</strong></div>
            <div class="gd-info-sub">Khóa học: <strong>{{ $webinarTitle ?? '-' }}</strong></div>
        </div>

        {{-- AI band --}}
        <div class="gd-ai-card">

            {{-- Summary view (default) --}}
            <div id="gdAISummary">
                <div class="gd-ai-card-label">Overall Band Score From AI: {{ $aiOverallBand ?? 'N/A' }}</div>
                
                <a href="javascript:void(0)" class="gd-ai-detail-btn sp-ai-expand" onclick="gdToggleAIView(true)">
                    Chi tiết &rarr;
                </a>
            </div>

            {{-- Detail view (same layout as teacher criteria, read-only) --}}
            <div id="gdAIDetail">
                <div class="gd-ai-detail-header">
                    <span class="gd-ai-detail-title">AI Scoring Detail</span>
                    <a href="javascript:void(0)" class="gd-ai-detail-btn" onclick="gdToggleAIView(false)">&larr; Thu gọn</a>
                </div>
                <div class="gd-ai-overall">
                    <div class="gd-ai-overall-score">{{ $aiOverallBand ?? 'N/A' }}</div>
                    <div class="gd-ai-overall-label">Overall Band Score (AI)</div>
                    @if($aimBand)
                        <div style="font-size:.78rem;color:#374151;margin-top:4px;">Target: <strong style="color:#dc2626;">{{ $aimBand }}</strong></div>
                    @endif
                </div>
                @if(!empty($aiCriteria))
                    @foreach($criteriaMap as $key => $label)
                        <div class="gd-ai-crit-row">
                            <div class="gd-ai-crit-label">{{ $label }}:</div>
                            <div class="gd-ai-crit-val">{{ $aiCriteria[$key] ?? '—' }}</div>
                        </div>
                    @endforeach
                @else
                    <p style="color:#9ca3af;font-size:.82rem;text-align:center;margin:8px 0 0;">No AI data available.</p>
                @endif
            </div>

        </div>

    </div>{{-- /gd-top-row --}}

    {{-- ════ GRADING FORM ════ --}}
    <form id="gradingForm" method="POST" action="{{ route('panel.ielts_grading.submit', $attempt->id) }}">
        @csrf
        <input type="hidden" name="skill" value="{{ $skill }}">

        @if($skill === 'speaking')
        {{-- ════════════════════════════════════════════
             SPEAKING — PER-SECTION TWO-COLUMN LAYOUT
             ════════════════════════════════════════════ --}}

        {{-- AI mode banner --}}
        <div class="sp-gd-ai-banner">
            &#128308; Bạn đang xem bài chấm của AI (chế độ xem, không thể chỉnh sửa).
            <a href="javascript:void(0)" onclick="gdToggleAIView(false)" style="color:#1d4ed8;font-weight:700;margin-left:10px;">
                &larr; Quay lại chấm bài
            </a>
        </div>

        @foreach($sections as $secIdx => $section)
        @php
            $secAnswers = $answers->filter(fn($a) => $a->question && $a->question->section_id === $section->id);
            $sectionLabel = $section->title ?: ('Part ' . ($secIdx + 1));

            // Task audio
            $taskAudioUrl = null;
            if (!empty($section->audio_url)) {
                $taskAudioUrl = $section->audio_url;
            } elseif (!empty($section->audio_file)) {
                $af = $section->audio_file;
                $taskAudioUrl = (str_starts_with($af, '/') || str_starts_with($af, 'http')) ? $af : '/store/' . $af;
            }

            // Task image
            $taskImg = $section->image_file ?? null;
            if (!$taskImg && $section->questionGroup) {
                $taskImg = $section->questionGroup->task_image ?? null;
            }

            // Student audio (first answer in section that has audio)
            $studentAudio = $secAnswers->first(fn($a) => !empty($a->audio_url));
            $studentAudioUrl = $studentAudio ? $studentAudio->audio_url : null;

            $sid = (string)$section->id;
            $secTeacherCrit = $sectionTeacherCriteriaMap[$sid] ?? [];
            $secTeacherFeedback = $secTeacherCrit['feedback'] ?? ($secIdx === 0 && empty($sectionTeacherCriteriaMap) ? $existingFeedback : '');
            $secAiCrit = $aiSectionCriteriaMap[$sid] ?? [];
        @endphp

        <div class="sp-gd-section-card">

            {{-- ── Gray two-column header ── --}}
            <div class="sp-gd-sec-header">
                <div class="sp-gd-sec-hdr-left is-open"
                     onclick="spGdToggleTask('{{ $section->id }}')"
                     id="sp-hdr-{{ $section->id }}">
                    {{ $sectionLabel }}
                    <span class="sp-gd-chev">&#9660;</span>
                </div>
                <div class="sp-gd-sec-hdr-right">Bản ghi âm của học viên</div>
            </div>

            {{-- ── Collapsible task body ── --}}
            <div class="sp-gd-task-body is-open" id="sp-task-{{ $section->id }}">
                @if($section->instructions)
                    <div style="font-size:.8rem;color:#6b7280;font-style:italic;margin-bottom:8px;">{{ $section->instructions }}</div>
                @endif
                @if($taskImg)
                    @php
                        $imgUrl = (str_starts_with($taskImg, '/') || str_starts_with($taskImg, 'http')) ? $taskImg : '/store/' . $taskImg;
                    @endphp
                    <img src="{{ $imgUrl }}" style="max-width:100%;border-radius:6px;margin-bottom:10px;" alt="Task" onerror="this.style.display='none';">
                @endif
                @php
                    // Determine prompt text: section > questionGroup > first question
                    $promptText = null;
                    if (!empty($section->passage_text)) {
                        $promptText = $section->passage_text;
                    } elseif ($section->questionGroup && !empty($section->questionGroup->passage_text)) {
                        $promptText = $section->questionGroup->passage_text;
                    } elseif ($secAnswers->isNotEmpty()) {
                        foreach ($secAnswers as $secAns) {
                            if ($secAns->question && !empty($secAns->question->question_text)) {
                                $promptText = $secAns->question->question_text;
                                break;
                            }
                        }
                    }
                @endphp

                @if($promptText)
                    <div class="gd-question-prompt">{!! nl2br(e($promptText)) !!}</div>
                @else
                    <p style="color:#9ca3af;font-size:.83rem;margin:0;">No question content for this section.</p>
                @endif

                {{-- Task & student media row (video left, student audio right) --}}
                @php
                    // assemble media and student audio URLs
                    $mediaUrl = $taskAudioUrl;
                    if (empty($mediaUrl) && !empty($section->video_url)) {
                        $mediaUrl = $section->video_url;
                    }
                    if (empty($mediaUrl) && $section->questionGroup) {
                        $vf = $section->questionGroup->video_file ?? null;
                        if ($vf) {
                            $mediaUrl = (str_starts_with($vf, '/')||str_starts_with($vf,'http')) ? $vf : '/store/'.$vf;
                        }
                    }
                @endphp
                {{-- show row even if no student audio so teacher sees notice --}}
                @if($mediaUrl || true)
                    <div class="sp-gd-task-media-row">
                        @if($mediaUrl)
                            <div class="sp-gd-media-left">
                                <{{ str_contains($mediaUrl, '.mp4') ? 'video controls' : 'audio controls' }} class="sp-gd-media-element">
                                    <source src="{{ $mediaUrl }}">
                                </{{ str_contains($mediaUrl, '.mp4') ? 'video' : 'audio' }}>
                            </div>
                        @endif
                        <div class="sp-gd-media-right">
                            @if($studentAudioUrl)
                                <audio controls class="sp-gd-media-element">
                                    <source src="{{ $studentAudioUrl }}">
                                </audio>
                            @else
                                <div class="sp-gd-media-empty">Chưa có bản ghi âm của học viên</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Two-column body ── --}}
            <div class="sp-gd-sec-body">

                {{-- LEFT col — Teacher feedback editor (teacher mode) --}}
                <div class="sp-gd-left-col sp-gd-teach-only">
                    <div class="sp-gd-editor-wrap">
                        <textarea class="sp-gd-section-editor"
                                  name="section_feedback[{{ $section->id }}]"
                                  id="secEditor-{{ $section->id }}">{{ old('section_feedback.' . $section->id, $secTeacherFeedback) }}</textarea>
                    </div>
                </div>

                {{-- LEFT col — AI feedback (AI mode) --}}
                <div class="sp-gd-left-col sp-gd-ai-only">
                    <div style="font-size:.78rem;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px;">AI Feedback</div>
                    <div class="sp-gd-ai-feedback-box">
                        @php
                            $aiFeedbackText = null;
                            foreach ($secAnswers as $secAns) {
                                if (!empty($secAns->grader_feedback)) { $aiFeedbackText = $secAns->grader_feedback; break; }
                            }
                        @endphp
                        @if($aiFeedbackText)
                            {!! nl2br(e($aiFeedbackText)) !!}
                        @else
                            <span style="color:#9ca3af;font-style:italic;">—</span>
                        @endif
                    </div>
                </div>

                {{-- RIGHT col — student audio + criteria --}}
                <div class="sp-gd-right-col">


                    {{-- Teacher criteria inputs (teacher mode) --}}
                    <div class="sp-gd-teach-only">
                        @foreach($criteriaMap as $key => $label)
                            @php $cScore = $secTeacherCrit[$key] ?? ''; @endphp
                            <div class="gd-crit-row" style="margin-bottom:10px;">
                                <div class="gd-crit-label">{{ $label }}:</div>
                                <input type="number"
                                       class="gd-crit-bar-input js-sec-crit-input"
                                       name="criteria_scores[{{ $section->id }}][{{ $key }}]"
                                       data-section="{{ $section->id }}"
                                       data-key="{{ $key }}"
                                       min="0" max="9" step="0.5"
                                       placeholder="0–9"
                                       value="{{ $cScore !== '' ? $cScore : '' }}">
                            </div>
                        @endforeach
                    </div>

                    {{-- AI criteria display (AI mode, read-only) --}}
                    <div class="sp-gd-ai-only">
                        @foreach($criteriaMap as $key => $label)
                            <div class="sp-gd-ai-crit-row">
                                <div class="sp-gd-ai-crit-label">{{ $label }}:</div>
                                <div class="sp-gd-ai-crit-val">{{ $secAiCrit[$key] !== null ? $secAiCrit[$key] : '—' }}</div>
                            </div>
                        @endforeach
                    </div>

                </div>{{-- /.sp-gd-right-col --}}
            </div>{{-- /.sp-gd-sec-body --}}
        </div>{{-- /.sp-gd-section-card --}}
        @endforeach

        {{-- ── Speaking bottom: totals + overall + actions ── --}}
        <div class="sp-gd-bottom-row sp-gd-teach-only">
            <div class="sp-gd-scores-circle">
                <div class="sp-gd-circle-val" id="spGdBandDisplay">
                    {{ $existingBand !== '' ? $existingBand : '—' }}
                </div>
                <div class="sp-gd-circle-lbl">Scores</div>
                <input type="number" name="band_score" id="spBandScoreInput"
                       min="0" max="9" step="0.5"
                       value="{{ old('band_score', $existingBand) }}"
                       style="display:none;" required>
            </div>

            <div class="gd-crit-col">
                @foreach($criteriaMap as $key => $label)
                    @php $flat = $teacherCriteria[$key] ?? null; @endphp
                    <div class="gd-crit-row" style="margin-bottom:10px;">
                        <div class="gd-crit-label">{{ $label }}:</div>
                        <div class="sp-gd-total-box" id="spTotal-{{ $key }}">
                            {{ $flat !== null ? $flat : '—' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sp-gd-actions-col sp-gd-save-wrap">
                <button type="submit" class="gd-btn-save" id="spSaveBtn">Save</button>
                <a href="{{ route('panel.ielts_grading.index', ['skill' => 'speaking']) }}"
                   class="gd-btn-exit">Exit</a>
            </div>
        </div>

        @else
        {{-- ════════════════════════════════════════════
             WRITING — original layout (unchanged)
             ════════════════════════════════════════════ --}}
        <input type="hidden" name="annotated_essays" id="annotatedEssaysInput" value="">

        {{-- ── Exam sections ── --}}
        @foreach($sections as $section)
        @php
            $secAnswers = $answers->filter(fn($a) => $a->question && $a->question->section_id === $section->id);
            $sectionLabel = $section->title
                ?: ($skill === 'writing'
                        ? 'Đề thi (Task ' . $loop->iteration . ')'
                        : 'Đề thi (Part ' . $loop->iteration . ')');
            // Task image: check section's own image_file first, then questionGroup
            $taskImg = $section->image_file ?? null;
            if (!$taskImg && $section->questionGroup) {
                $taskImg = $section->questionGroup->task_image ?? null;
            }
        @endphp

        {{-- Collapsible "Đề thi" header — open by default --}}
        <div class="gd-collapse-hdr is-open" onclick="gdToggleCollapse('sec-{{ $section->id }}')" id="hdr-sec-{{ $section->id }}">
            {{ $sectionLabel }}
            <span class="gd-chev">&#9660;</span>
        </div>
        <div class="gd-collapse-body is-open" id="body-sec-{{ $section->id }}">
            {{-- Section instructions (time guidance etc.) --}}
            @if($section->instructions)
                <div style="font-size:.82rem;color:#6b7280;margin-bottom:10px;font-style:italic;">{{ $section->instructions }}</div>
            @endif
            {{-- Task image --}}
            @if($taskImg)
                @php
                    $imgUrl = $taskImg;
                    if (!str_starts_with($taskImg, '/') && !str_starts_with($taskImg, 'http')) {
                        $imgUrl = '/store/' . $taskImg;
                    }
                @endphp
                <img src="{{ $imgUrl }}" style="max-width:100%;border-radius:6px;margin-bottom:12px;" alt="Task image" onerror="this.style.display='none';">
            @endif
            {{-- Writing/Speaking task prompt — stored as HTML in section.passage_text --}}
            @if($section->passage_text)
                <div class="gd-question-prompt">{!! $section->passage_text !!}</div>
            @elseif($secAnswers->isNotEmpty())
                {{-- Fallback: try question_text on each question --}}
                @foreach($secAnswers as $secAns)
                    @if($secAns->question && $secAns->question->question_text)
                        <div class="gd-question-prompt">{!! nl2br(e($secAns->question->question_text)) !!}</div>
                    @endif
                @endforeach
            @else
                <p style="color:#9ca3af;font-size:.84rem;margin:0;">No question content for this section.</p>
            @endif
        </div>

        {{-- ── Student essay for each answer in this section ── --}}
        @foreach($secAnswers as $secAns)
            @php
                $essayText  = $secAns->answer_text ?? '';
                $existAnnot = $annotatedEssays[(string)$secAns->id] ?? null;
            @endphp
            @if($essayText || $existAnnot)
                <div class="gd-essay-card">
                    <div class="gd-essay-card-label">Bài làm của học viên</div>
                    <div class="gd-essay-hint">💡 Bạn có thể chỉ chỉ trích (thêm feedback) bằng cách chọn văn bản, nhưng KHÔNG thể chỉnh sửa nội dung bài làm</div>
                    <div class="gd-essay-body"
                         id="essay-{{ $secAns->id }}"
                         data-answer-id="{{ $secAns->id }}"
                         contenteditable="true"
                         spellcheck="false">@if($existAnnot){!! $existAnnot !!}@else{!! nl2br(e($essayText)) !!}@endif</div>
                </div>
            @endif
        @endforeach

        @endforeach{{-- /sections --}}

        {{-- ── Comments & Feedback ── --}}
        <div class="gd-feedback-card">
            <textarea id="feedbackEditor" name="feedback">{{ old('feedback', $existingFeedback) }}</textarea>
        </div>

        {{-- ── Criteria + Score ── --}}
        <div class="gd-bottom-row">

            {{-- Criteria inputs --}}
            <div class="gd-crit-col">
                @foreach($criteriaMap as $key => $label)
                    @php $tScore = $teacherCriteria[$key] ?? ''; @endphp
                    <div class="gd-crit-row">
                        <div class="gd-crit-label">{{ $label }}:</div>
                        <input type="number"
                               class="gd-crit-bar-input js-criteria-score"
                               name="criteria_scores[{{ $key }}]"
                               min="0" max="9" step="0.5"
                               value="{{ $tScore !== '' ? $tScore : '' }}"
                               data-key="{{ $key }}">
                    </div>
                @endforeach
            </div>

            {{-- Score summary --}}
            <div class="gd-score-col">
                <div class="gd-score-box">
                    <div id="gdScoreDisplay" class="gd-score-display">—</div>
                    <input type="number"
                           id="bandScore"
                           name="band_score"
                           class="gd-score-input"
                           min="0" max="9" step="0.5"
                           value="{{ old('band_score', $existingBand) }}"
                           required>
                    <div class="gd-score-hint">Auto-calculated from criteria (nearest 0.5)</div>
                </div>
                
                {{-- Action buttons (aligned with score box) --}}
                <div class="gd-actions">
                    <a href="{{ route('panel.ielts_grading.index', ['skill' => $skill]) }}" class="gd-btn-exit">Exit</a>
                    <button type="submit" class="gd-btn-save" id="saveBtn">Save</button>
                </div>
            </div>

        </div>{{-- /gd-bottom-row --}}

    @endif{{-- /if speaking / else writing --}}
    </form>
</div>{{-- /gd-page --}}

{{-- ════ FLOATING ANNOTATION TOOLBAR ════ --}}
<div id="gd-annot-toolbar">
    <button type="button" onclick="gdOpenAnnotModal()">&#9998; Annotate</button>
    <button type="button" onclick="gdRemoveAnnot()">&#10006; Remove</button>
</div>

{{-- ════ ANNOTATION COMMENT MODAL ════ --}}
<!-- Modal placed here but will be moved to body on load to avoid stacking issues -->
<div class="modal fade" id="gdAnnotModal" tabindex="-1">
    <div class="modal-dialog" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:.94rem;">Add Comment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <textarea id="gdAnnotInput"
                          style="width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.88rem;resize:vertical;min-height:80px;"
                          placeholder="Enter your comment…"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="gdConfirmAnnot()">Save Comment</button>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>
/* ── Summernote — writing feedback editor ── */
$(function () {
    if (document.getElementById('feedbackEditor')) {
        $('#feedbackEditor').summernote({
            height: 220,
            placeholder: 'Comments & Feedback',
            toolbar: [
                ['history', ['undo', 'redo']],
                ['style',   ['bold', 'italic', 'underline', 'strikethrough']],
                ['para',    ['ul', 'ol', 'paragraph']],
            ],
        });
    }

    /* ── Summernote — speaking per-section editors ── */
    document.querySelectorAll('.sp-gd-section-editor').forEach(function (el) {
        $(el).summernote({
            height: 200,
            placeholder: 'Comments & Feedback',
            toolbar: [
                ['history', ['undo', 'redo']],
                ['style',   ['bold', 'italic', 'underline', 'strikethrough']],
                ['para',    ['ul', 'ol', 'paragraph']],
            ],
        });
    });
});

/* ── AI card view toggle (speaking: also toggles .gd-page.ai-mode) ── */
function gdToggleAIView(showDetail) {
    document.getElementById('gdAISummary').style.display = showDetail ? 'none' : '';
    document.getElementById('gdAIDetail').style.display  = showDetail ? ''     : 'none';
    var page = document.querySelector('.gd-page');
    if (page) {
        if (showDetail) { page.classList.add('ai-mode'); }
        else            { page.classList.remove('ai-mode'); }
    }
}

/* ── Speaking: toggle collapsible task area ── */
function spGdToggleTask(id) {
    var hdr  = document.getElementById('sp-hdr-' + id);
    var body = document.getElementById('sp-task-' + id);
    if (!hdr || !body) return;
    hdr.classList.toggle('is-open');
    body.classList.toggle('is-open');
}

/* ── Speaking: recalculate per-criteria totals + overall band ── */
function spGdRecalcTotals() {
    var criteriaKeys = ['fluency', 'lexical', 'grammar', 'pronunciation'];
    // Gather all section inputs grouped by section id
    var sectionMap = {};
    document.querySelectorAll('.js-sec-crit-input').forEach(function (inp) {
        var sec = inp.getAttribute('data-section');
        var key = inp.getAttribute('data-key');
        var val = parseFloat(inp.value);
        if (!sectionMap[sec]) { sectionMap[sec] = {}; }
        if (!isNaN(val)) { sectionMap[sec][key] = val; }
    });
    // Average each criterion across sections
    var totals = {};
    criteriaKeys.forEach(function (k) {
        var vals = Object.values(sectionMap)
            .map(function (s) { return s[k]; })
            .filter(function (v) { return v !== undefined && !isNaN(v); });
        totals[k] = vals.length
            ? Math.round(vals.reduce(function (a, b) { return a + b; }, 0) / vals.length * 2) / 2
            : null;
        var box = document.getElementById('spTotal-' + k);
        if (box) { box.textContent = totals[k] !== null ? totals[k] : '\u2014'; }
    });
    // Overall band
    var allVals = criteriaKeys.map(function (k) { return totals[k]; }).filter(function (v) { return v !== null; });
    var overall = allVals.length
        ? Math.round(allVals.reduce(function (a, b) { return a + b; }, 0) / allVals.length * 2) / 2
        : null;
    var disp = document.getElementById('spGdBandDisplay');
    var inp  = document.getElementById('spBandScoreInput');
    if (disp) { disp.textContent = overall !== null ? overall : '\u2014'; }
    if (inp)  { inp.value = overall !== null ? overall : ''; }
}

/* ── Collapsible sections ── */
function gdToggleCollapse(id) {
    var hdr  = document.getElementById('hdr-' + id);
    var body = document.getElementById('body-' + id);
    if (!hdr || !body) return;
    hdr.classList.toggle('is-open');
    body.classList.toggle('is-open');
}

/* ── Auto-calc overall band from criteria (writing only) ── */
function gdRecalcBand() {
    var vals = Array.from(document.querySelectorAll('.js-criteria-score'))
        .map(function (i) { return parseFloat(i.value); })
        .filter(function (v) { return !isNaN(v); });
    var score = '—';
    if (vals.length > 0) {
        var avg = vals.reduce(function (s, v) { return s + v; }, 0) / vals.length;
        score = Math.round(avg * 2) / 2;
    }
    var bandEl  = document.getElementById('bandScore');
    var dispEl  = document.getElementById('gdScoreDisplay');
    if (bandEl) { bandEl.value = score !== '—' ? score : ''; }
    if (dispEl) { dispEl.textContent = score; }
}
$(document).ready(function () {
    /* writing criteria live recalc */
    var writingInputs = document.querySelectorAll('.js-criteria-score');
    writingInputs.forEach(function (inp) {
        inp.addEventListener('input', gdRecalcBand);
    });
    if (writingInputs.length) { gdRecalcBand(); }

    /* speaking criteria live recalc */
    document.querySelectorAll('.js-sec-crit-input').forEach(function (inp) {
        inp.addEventListener('input', spGdRecalcTotals);
    });
    spGdRecalcTotals();

    // move annotation modal to <body> so Bootstrap's backdrop doesn't cover it
    var $modal = $('#gdAnnotModal');
    if ($modal.length) {
        $modal.appendTo('body');
    }
});

/* ── Essay: block all edits, allow selection only ── */
document.querySelectorAll('.gd-essay-body').forEach(function (el) {
    // Block keyboard input (typing, delete, backspace, paste, cut)
    el.addEventListener('keydown', function (e) { e.preventDefault(); });
    el.addEventListener('keypress', function (e) { e.preventDefault(); });
    el.addEventListener('paste', function (e) { e.preventDefault(); });
    el.addEventListener('cut', function (e) { e.preventDefault(); });
    el.addEventListener('drop', function (e) { e.preventDefault(); });
    // Restore innerHTML if somehow mutated (extra safety)
    el.setAttribute('data-original', el.innerHTML);
});

/* ── Annotation system ── */
var _gdRange  = null;
var _gdEssay  = null;

document.querySelectorAll('.gd-essay-body').forEach(function (el) {
    el.addEventListener('mouseup', function () {
        var sel = window.getSelection();
        if (!sel || sel.isCollapsed) { gdHideToolbar(); return; }
        var rng = sel.getRangeAt(0);
        if (!el.contains(rng.commonAncestorContainer)) { gdHideToolbar(); return; }
        _gdRange = rng.cloneRange();
        _gdEssay = el;
        var toolbar = document.getElementById('gd-annot-toolbar');
        var rect = rng.getBoundingClientRect();
        toolbar.style.left = (rect.left + window.scrollX + rect.width / 2 - 60) + 'px';
        toolbar.style.top  = (rect.top  + window.scrollY - 46) + 'px';
        toolbar.style.display = 'block';
    });
});

document.addEventListener('mousedown', function (e) {
    var t = document.getElementById('gd-annot-toolbar');
    if (t && !t.contains(e.target)) gdHideToolbar();
});

function gdHideToolbar() {
    var t = document.getElementById('gd-annot-toolbar');
    if (t) t.style.display = 'none';
}

function gdOpenAnnotModal() {
    gdHideToolbar();
    document.getElementById('gdAnnotInput').value = '';
    $('#gdAnnotModal').modal('show');
    setTimeout(function () { document.getElementById('gdAnnotInput').focus(); }, 400);
}

function gdConfirmAnnot() {
    var comment = document.getElementById('gdAnnotInput').value.trim();
    if (!comment || !_gdRange || !_gdEssay) { $('#gdAnnotModal').modal('hide'); return; }
    try {
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(_gdRange);
        var mark = document.createElement('mark');
        mark.setAttribute('data-comment', comment);
        try {
            _gdRange.surroundContents(mark);
        } catch (e) {
            var frag = _gdRange.extractContents();
            mark.appendChild(frag);
            _gdRange.insertNode(mark);
        }
        sel.removeAllRanges();
    } catch (ex) { console.warn('Annotation error:', ex); }
    $('#gdAnnotModal').modal('hide');
    _gdRange = null;
}

function gdRemoveAnnot() {
    gdHideToolbar();
    if (!_gdRange) return;
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(_gdRange);
    var c  = _gdRange.commonAncestorContainer;
    var mk = c.nodeType === 1
        ? c.closest('mark')
        : (c.parentElement ? c.parentElement.closest('mark') : null);
    if (mk) {
        var p = mk.parentNode;
        while (mk.firstChild) p.insertBefore(mk.firstChild, mk);
        p.removeChild(mk);
    }
    sel.removeAllRanges();
    _gdRange = null;
}

/* ── Form submit: collect annotated essays (writing) + Summernote content (speaking) ── */
document.getElementById('gradingForm').addEventListener('submit', function () {
    /* Writing: serialize annotated essay HTML */
    var annotatedInput = document.getElementById('annotatedEssaysInput');
    if (annotatedInput) {
        var annotated = {};
        document.querySelectorAll('.gd-essay-body').forEach(function (el) {
            var aid = el.getAttribute('data-answer-id');
            if (aid) { annotated[aid] = el.innerHTML; }
        });
        annotatedInput.value = JSON.stringify(annotated);
    }
    /* Speaking: flush each Summernote section editor back to its hidden textarea */
    document.querySelectorAll('.sp-gd-section-editor').forEach(function (el) {
        var $el = $(el);
        if ($el.data('summernote')) {
            el.value = $el.summernote('code');
        }
    });
    /* Disable save button to prevent double-submit */
    var saveBtn = document.getElementById('saveBtn') || document.getElementById('spSaveBtn');
    if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving\u2026'; }
});
</script>
@endpush
