@extends('design_1.web.layouts.app')

@php
    // Các cờ này khiến layout ẩn header/footer mặc định, giống cách
    // home/index.blade.php đang làm — để trang tự kiểm soát toàn bộ giao diện.
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;

    $studentName  = optional(auth()->user())->full_name ?: 'Học viên';
    $completedAt  = $attempt->completed_at ?? $attempt->started_at;
    $takenCount   = count($attempt->test_ids_taken ?? []);
    $canViewKey   = !$isDemoData && $testBlocks->isNotEmpty();
    $hasRecording = !empty($attempt->speaking_recording_path);
    $hasConsult   = !empty($consultRequest);
@endphp

@push('styles_top')
<style>
/* ════════════════════════════════════════════════════════════════════
   Trang kết quả Placement Test — tiền tố prs- (placement result) để không
   đụng CSS của theme / Bootstrap.
   ════════════════════════════════════════════════════════════════════ */
.prs-page {
    --prs-primary: #511D99;
    --prs-primary-dark: #3b1475;
    --prs-accent: #7c3aed;
    --prs-soft: #f5f0ff;
    --prs-soft-2: #ede4ff;
    --prs-border: #e7ddfa;
    --prs-text: #1f1235;
    --prs-muted: #6b6380;
    --prs-green: #16a34a;
    --prs-green-soft: #ecfdf3;
    --prs-red: #e11d48;
    --prs-red-soft: #fff1f3;
    --prs-amber: #d97706;
    --prs-amber-soft: #fff7e6;
    background: #fbf9ff;
    min-height: 100vh;
    color: var(--prs-text);
}
.prs-page *, .prs-modal * { box-sizing: border-box; }

.prs-topbar {
    display: flex; align-items: center; justify-content: space-between;
    max-width: 1120px; margin: 0 auto; padding: 18px 20px 6px;
}
.prs-topbar__brand { font-size: 24px; font-weight: 900; color: var(--prs-primary); letter-spacing: .5px; text-decoration: none !important; }
.prs-topbar__back { font-size: 14px; font-weight: 600; color: var(--prs-primary); text-decoration: none !important; }
.prs-topbar__back:hover { color: var(--prs-accent); }

.prs-wrap { max-width: 1120px; margin: 0 auto; padding: 12px 20px 64px; display: flex; flex-direction: column; gap: 18px; }

.prs-card {
    background: #fff;
    border: 1px solid var(--prs-border);
    border-radius: 18px;
    box-shadow: 0 6px 24px rgba(81, 29, 153, .06);
}

.prs-demo-note {
    background: #fef3c7; border-left: 4px solid #f59e0b; color: #78350f;
    padding: 12px 16px; border-radius: 12px; font-size: 14px;
}

/* ── 1. Chứng chỉ ───────────────────────────────────────────────── */
.prs-cert {
    position: relative; overflow: hidden;
    display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: center;
    padding: 32px 40px;
    background:
        radial-gradient(circle at 85% 20%, rgba(124, 58, 237, .10), transparent 45%),
        radial-gradient(circle at 10% 110%, rgba(81, 29, 153, .06), transparent 40%),
        #fff;
}
.prs-cert__brand { font-size: 22px; font-weight: 900; color: var(--prs-primary); line-height: 1; }
.prs-cert__kicker { margin-top: 6px; font-size: 13px; font-weight: 600; letter-spacing: 3px; color: var(--prs-accent); text-transform: uppercase; }
.prs-cert__title { margin: 14px 0 2px; font-size: 64px; line-height: 1; font-weight: 900; color: var(--prs-primary-dark); letter-spacing: -1px; }
.prs-cert__name { font-size: 28px; font-weight: 800; color: var(--prs-primary); margin-bottom: 14px; word-break: break-word; }
.prs-cert__desc { font-size: 15px; color: var(--prs-muted); max-width: 380px; line-height: 1.5; margin-bottom: 16px; }
.prs-cert__date-label { font-size: 13px; color: var(--prs-muted); }
.prs-cert__date { font-size: 18px; font-weight: 800; color: var(--prs-primary); }

.prs-level {
    position: relative; z-index: 1;
    width: 100%; aspect-ratio: 1 / 1; max-width: 280px; justify-self: end;
    border-radius: 34px;
    background: linear-gradient(150deg, #f3ecff 0%, #e2d2ff 55%, #cdb5ff 100%);
    border: 3px solid rgba(255, 255, 255, .85);
    box-shadow: 0 18px 40px rgba(124, 58, 237, .25), inset 0 2px 18px rgba(255, 255, 255, .9);
    display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
    padding: 20px;
}
.prs-level__label { font-size: 17px; font-weight: 600; letter-spacing: 1.5px; color: var(--prs-primary); }
.prs-level__value { font-size: 120px; line-height: 1; font-weight: 900; color: var(--prs-primary); letter-spacing: -3px; margin: 4px 0 10px; }
.prs-level__value--long { font-size: 96px; }
.prs-level__pill {
    white-space: nowrap;
    background: rgba(255, 255, 255, .7); border: 1px solid rgba(124, 58, 237, .25);
    color: var(--prs-primary); font-weight: 700; font-size: 16px;
    padding: 6px 20px; border-radius: 999px;
}
.prs-spark { position: absolute; color: var(--prs-primary); pointer-events: none; }
.prs-dot { position: absolute; border-radius: 50%; pointer-events: none; }

/* ── 2. Speaking ────────────────────────────────────────────────── */
.prs-speaking { display: flex; align-items: center; gap: 20px; padding: 18px 24px; }
.prs-speaking__icon {
    position: relative; flex-shrink: 0;
    width: 76px; height: 76px; border-radius: 50%;
    background: linear-gradient(145deg, #efe5ff, #d9c5ff);
    display: flex; align-items: center; justify-content: center;
    color: var(--prs-primary); font-size: 30px;
    box-shadow: inset 0 0 0 6px rgba(255, 255, 255, .7);
}
.prs-speaking__icon-badge {
    position: absolute; right: -2px; bottom: 2px;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--prs-accent); color: #fff; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    border: 3px solid #fff;
}
.prs-speaking__body { flex: 1; min-width: 0; }
.prs-speaking__title { font-size: 21px; font-weight: 800; color: var(--prs-primary-dark); margin-bottom: 4px; }
.prs-speaking__text { font-size: 14px; color: var(--prs-muted); line-height: 1.5; }
.prs-speaking__actions { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
.prs-chip {
    border: 1px solid var(--prs-border); background: var(--prs-soft);
    color: var(--prs-primary); font-weight: 600; font-size: 14px;
    padding: 9px 16px; border-radius: 999px; white-space: nowrap;
}

/* ── Nút dùng chung ─────────────────────────────────────────────── */
.prs-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    border-radius: 999px; padding: 11px 22px; font-weight: 700; font-size: 14px;
    border: 2px solid var(--prs-primary); cursor: pointer;
    text-decoration: none !important; transition: background .15s, color .15s, transform .15s, box-shadow .15s;
    white-space: nowrap; line-height: 1.2;
}
.prs-btn--primary { background: var(--prs-primary); color: #fff !important; }
.prs-btn--primary:hover { background: var(--prs-primary-dark); border-color: var(--prs-primary-dark); box-shadow: 0 6px 16px rgba(81, 29, 153, .25); }
.prs-btn--ghost { background: #fff; color: var(--prs-primary) !important; }
.prs-btn--ghost:hover { background: var(--prs-soft); }
.prs-btn--block { width: 100%; border-radius: 12px; padding: 13px 22px; font-size: 15px; }
.prs-btn--square { border-radius: 10px; }
.prs-btn[disabled] { opacity: .5; cursor: not-allowed; box-shadow: none; }

/* ── 3. Form tư vấn ────────────────────────────────────────────── */
.prs-consult { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr); padding: 16px; gap: 0; }
.prs-consult__form-box { background: var(--prs-soft); border-radius: 14px; padding: 20px 22px; }
.prs-consult__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 18px; margin-bottom: 16px; }
.prs-field label { display: block; font-size: 13px; font-weight: 600; color: var(--prs-text); margin-bottom: 6px; }
.prs-field label .req { color: var(--prs-red); }
.prs-input { position: relative; }
.prs-input > i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--prs-primary); font-size: 14px; pointer-events: none; }
.prs-input > .prs-input__caret { left: auto; right: 14px; color: var(--prs-muted); }
.prs-input input, .prs-input select {
    width: 100%; height: 44px; border: 1px solid #ddd2f3; border-radius: 10px;
    background: #fff; padding: 0 14px 0 40px; font-size: 14px; color: var(--prs-text);
    outline: none; transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none; appearance: none;
}
.prs-input select { padding-right: 36px; cursor: pointer; }
.prs-input input::placeholder { color: #9b93ad; }
.prs-input input:focus, .prs-input select:focus { border-color: var(--prs-accent); box-shadow: 0 0 0 3px rgba(124, 58, 237, .15); }
.prs-field.has-error input, .prs-field.has-error select { border-color: var(--prs-red); }
.prs-field__error { font-size: 12px; color: var(--prs-red); margin-top: 4px; min-height: 0; }
.prs-consult__general-error { font-size: 13px; color: var(--prs-red); margin-bottom: 10px; }

.prs-consult__done { display: flex; align-items: center; gap: 16px; padding: 10px 4px; min-height: 100%; }
.prs-consult__done-icon {
    width: 56px; height: 56px; border-radius: 50%; flex-shrink: 0;
    background: var(--prs-green-soft); color: var(--prs-green);
    display: flex; align-items: center; justify-content: center; font-size: 24px;
}
.prs-consult__done-title { font-weight: 800; font-size: 17px; color: var(--prs-primary-dark); }
.prs-consult__done-text { font-size: 14px; color: var(--prs-muted); margin: 2px 0 8px; }
.prs-link-btn { background: none; border: none; padding: 0; color: var(--prs-primary); font-weight: 700; font-size: 14px; cursor: pointer; text-decoration: underline; }

.prs-consult__aside { display: flex; flex-direction: column; justify-content: center; padding: 10px 26px 10px 30px; margin-left: 22px; border-left: 1px solid var(--prs-border); }
.prs-consult__aside h3 { font-size: 28px; line-height: 1.25; font-weight: 800; color: var(--prs-primary-dark); margin: 0 0 10px; }
.prs-consult__aside p { font-size: 14px; color: var(--prs-muted); margin: 0; }

/* ── 4. Khoá học ────────────────────────────────────────────────── */
.prs-courses { padding: 22px 24px 26px; }
.prs-courses__head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
.prs-courses__title { font-size: 26px; font-weight: 800; color: var(--prs-primary-dark); margin: 0 0 4px; }
.prs-courses__sub { font-size: 14px; color: var(--prs-muted); margin: 0; }
.prs-courses__all { font-size: 14px; font-weight: 700; color: var(--prs-primary) !important; text-decoration: underline !important; white-space: nowrap; padding-top: 6px; }
.prs-courses__grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }

.prs-course {
    position: relative;
    background: var(--prs-soft); border: 1px solid var(--prs-border); border-radius: 16px;
    padding: 18px; display: flex; flex-direction: column; gap: 14px;
    transition: transform .2s, box-shadow .2s;
}
.prs-course:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(81, 29, 153, .12); }
.prs-course.is-recommended { border: 2px solid var(--prs-accent); background: linear-gradient(180deg, #f1e8ff, var(--prs-soft)); }
.prs-course__badge {
    position: absolute; top: -12px; right: 16px;
    background: var(--prs-accent); color: #fff; font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 999px; box-shadow: 0 4px 10px rgba(124, 58, 237, .3);
}
.prs-course__top { display: flex; gap: 16px; align-items: flex-start; }
.prs-course__icon {
    width: 74px; height: 74px; flex-shrink: 0; border-radius: 16px;
    background: linear-gradient(145deg, #e5d6ff, #c6a9ff);
    color: #fff; font-size: 30px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: inset 0 2px 8px rgba(255, 255, 255, .55), 0 6px 14px rgba(124, 58, 237, .25);
    text-shadow: 0 2px 6px rgba(59, 20, 117, .35);
}
.prs-course__range {
    display: inline-block; background: #e4d6ff; color: var(--prs-primary);
    font-size: 12px; font-weight: 700; padding: 3px 12px; border-radius: 999px; margin-bottom: 6px;
}
.prs-course__name { font-size: 18px; font-weight: 800; color: var(--prs-primary-dark); margin: 0 0 4px; }
.prs-course__desc { font-size: 13px; color: var(--prs-muted); margin: 0 0 10px; line-height: 1.45; }
.prs-course__meta { display: flex; gap: 16px; flex-wrap: wrap; font-size: 13px; color: var(--prs-muted); }
.prs-course__meta i { color: var(--prs-primary); margin-right: 5px; }
.prs-course__actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: auto; }
.prs-course__actions .prs-btn { padding: 9px 10px; font-size: 13px; }

/* ════════════════════════════════════════════════════════════════════
   Modal "Chi tiết bài làm"
   ════════════════════════════════════════════════════════════════════ */
.prs-modal {
    --prs-primary: #511D99; --prs-primary-dark: #3b1475; --prs-accent: #7c3aed;
    --prs-soft: #f5f0ff; --prs-border: #e7ddfa; --prs-text: #1f1235; --prs-muted: #6b6380;
    --prs-green: #16a34a; --prs-green-soft: #ecfdf3; --prs-red: #e11d48; --prs-red-soft: #fff1f3;
    --prs-amber: #d97706; --prs-amber-soft: #fff7e6;
    position: fixed; inset: 0; z-index: 1060;
    display: flex; align-items: center; justify-content: center; padding: 24px;
    color: var(--prs-text);
}
.prs-modal[hidden] { display: none; }
.prs-modal__backdrop { position: absolute; inset: 0; background: rgba(24, 12, 44, .55); backdrop-filter: blur(2px); }
.prs-modal__dialog {
    position: relative; width: 100%; max-width: 980px; max-height: calc(100vh - 48px);
    background: #fff; border-radius: 18px; overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: 0 30px 70px rgba(24, 12, 44, .35);
    animation: prsPop .18s ease-out;
}
@keyframes prsPop { from { transform: translateY(12px) scale(.98); opacity: 0; } to { transform: none; opacity: 1; } }
.prs-modal__header {
    background: linear-gradient(90deg, var(--prs-primary), #6a2bc4);
    color: #fff; padding: 16px 22px; display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.prs-modal__title { font-size: 20px; font-weight: 800; margin: 0; color: #fff; }
.prs-modal__close {
    background: none; border: none; color: #fff; font-size: 22px; cursor: pointer;
    width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.prs-modal__close:hover { background: rgba(255, 255, 255, .15); }
.prs-modal__top { padding: 14px 22px 0; flex-shrink: 0; }
.prs-modal__body { padding: 14px 22px 18px; overflow-y: auto; flex: 1; min-height: 0; }
.prs-modal__footer { padding: 12px 22px; border-top: 1px solid var(--prs-border); display: flex; justify-content: flex-end; flex-shrink: 0; }

/* Player audio */
.prs-player-slot[hidden] { display: none; }
.prs-player {
    display: flex; align-items: center; gap: 16px;
    background: var(--prs-soft); border: 1px solid var(--prs-border); border-radius: 14px; padding: 10px 16px;
}
.prs-player__play {
    width: 50px; height: 50px; border-radius: 50%; border: none; flex-shrink: 0;
    background: var(--prs-primary); color: #fff; font-size: 17px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 14px rgba(81, 29, 153, .3);
}
.prs-player__play:hover { background: var(--prs-primary-dark); }
.prs-player__play .fa-play { margin-left: 3px; }
.prs-player__wave { flex: 1; min-width: 0; height: 40px; display: flex; align-items: center; gap: 3px; cursor: pointer; overflow: hidden; }
.prs-player__wave span { flex: 1 1 0; min-width: 2px; border-radius: 2px; background: #b9a3e6; transition: background .1s; }
.prs-player__wave span.is-played { background: var(--prs-primary); }
.prs-player__time { font-size: 14px; color: var(--prs-muted); font-variant-numeric: tabular-nums; white-space: nowrap; }
.prs-player__speed {
    border: 1px solid var(--prs-border); background: #e9defe; color: var(--prs-primary);
    font-weight: 700; font-size: 13px; padding: 6px 12px; border-radius: 999px; cursor: pointer; min-width: 52px;
}
.prs-player__clips { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 8px; }
.prs-player__clips-label { font-size: 12px; font-weight: 700; color: var(--prs-muted); text-transform: uppercase; letter-spacing: .4px; }
.prs-clip-btn {
    border: 1px solid var(--prs-border); background: #fff; color: var(--prs-primary);
    font-size: 13px; font-weight: 600; padding: 4px 12px; border-radius: 999px; cursor: pointer;
}
.prs-clip-btn.is-active { background: var(--prs-primary); border-color: var(--prs-primary); color: #fff; }
.prs-player__instruction { font-size: 13px; color: var(--prs-primary-dark); font-weight: 600; margin-top: 8px; }
.prs-player__instruction:empty { display: none; }

/* Tabs */
.prs-tabs { display: flex; gap: 6px; margin-top: 12px; background: var(--prs-soft); border-radius: 12px; padding: 4px; }
.prs-tab {
    flex: 1; white-space: nowrap; border: none; background: transparent; color: var(--prs-primary);
    font-weight: 700; font-size: 15px; padding: 9px 10px; border-radius: 9px; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
}
.prs-tab:hover { background: #ebe1ff; }
.prs-tab.is-active { background: var(--prs-primary); color: #fff; }
.prs-tab__ref { font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 999px; background: #e9defe; color: var(--prs-primary); }
.prs-tab.is-active .prs-tab__ref { background: rgba(255, 255, 255, .22); color: #fff; }

.prs-pane[hidden] { display: none; }
.prs-pane__summary { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-bottom: 12px; font-size: 13px; color: var(--prs-muted); }
.prs-pane__summary strong { color: var(--prs-primary); }
.prs-pane__ref-note { background: #f4f1fa; border-left: 4px solid #a78bfa; padding: 10px 14px; border-radius: 10px; font-size: 13px; color: #4c1d95; margin-bottom: 14px; }

/* Thẻ câu hỏi */
.prs-q {
    display: grid; grid-template-columns: 92px minmax(0, 1fr); gap: 14px;
    border: 1px solid var(--prs-border); border-left-width: 6px; border-radius: 12px;
    padding: 14px 16px; margin-bottom: 14px; background: #fff;
}
.prs-q--correct { border-left-color: #22c55e; }
.prs-q--wrong { border-left-color: #f43f5e; }
.prs-q--blank { border-left-color: #f59e0b; }
.prs-q__side { display: flex; flex-direction: column; align-items: center; gap: 8px; padding-top: 2px; }
.prs-q__num { background: #ede4ff; color: var(--prs-primary); font-weight: 700; font-size: 13px; padding: 4px 14px; border-radius: 8px; white-space: nowrap; }
.prs-q__status { font-weight: 700; font-size: 12px; padding: 3px 14px; border-radius: 999px; white-space: nowrap; }
.prs-q--correct .prs-q__status { background: #dcfce7; color: var(--prs-green); }
.prs-q--wrong .prs-q__status { background: #ffe4e8; color: var(--prs-red); }
.prs-q--blank .prs-q__status { background: #fef0c7; color: var(--prs-amber); }

.prs-q__instruction {
    background: var(--prs-soft); border-radius: 8px; padding: 7px 12px;
    font-size: 13.5px; font-weight: 600; color: var(--prs-primary-dark); margin-bottom: 10px;
}
.prs-q__text { font-size: 15px; font-weight: 700; color: var(--prs-text); margin: 6px 0 10px; line-height: 1.5; }
.prs-q__text p { margin-bottom: 6px; }
.prs-q__audio-hint {
    display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px;
    background: #eef2ff; border: 1px solid #c7d2fe; color: #4338ca;
    font-size: 13px; font-weight: 600; padding: 5px 12px; border-radius: 999px; cursor: pointer;
}
.prs-q__audio-hint:hover { background: #e0e7ff; }

.prs-opt {
    display: flex; align-items: center; gap: 10px;
    border: 1px solid #e5def3; border-radius: 8px; padding: 7px 12px; margin-bottom: 6px;
    font-size: 14px; background: #fff;
}
.prs-opt__radio { width: 16px; height: 16px; border-radius: 50%; border: 2px solid #c9bfe0; flex-shrink: 0; position: relative; }
.prs-opt__letter { font-weight: 600; color: var(--prs-muted); }
.prs-opt.is-given.is-wrong { border-color: #f43f5e; background: var(--prs-red-soft); color: #9f1239; }
.prs-opt.is-given.is-right { border-color: #22c55e; background: var(--prs-green-soft); color: #166534; }
.prs-opt.is-given .prs-opt__radio { border-color: currentColor; }
.prs-opt.is-given .prs-opt__radio::after { content: ''; position: absolute; inset: 2px; border-radius: 50%; background: currentColor; }
.prs-opt__mine { font-weight: 600; }

.prs-img-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-bottom: 6px; }
.prs-img-opt { border: 2px solid #e5def3; border-radius: 10px; padding: 8px; text-align: center; font-size: 13px; font-weight: 600; background: #fff; }
.prs-img-opt img { width: 100%; aspect-ratio: 3 / 2; object-fit: contain; background: #f8fafc; border-radius: 6px; display: block; margin-bottom: 6px; }
.prs-img-opt.is-given.is-wrong { border-color: #f43f5e; background: var(--prs-red-soft); }
.prs-img-opt.is-given.is-right { border-color: #22c55e; background: var(--prs-green-soft); }

.prs-given { padding: 8px 12px; border-radius: 8px; font-size: 14px; }
.prs-given.is-right { background: var(--prs-green-soft); color: #166534; }
.prs-given.is-wrong { background: var(--prs-red-soft); color: #9f1239; }
.prs-blank-fill { font-weight: 700; padding: 0 4px; border-bottom: 2px solid currentColor; }
.prs-blank-fill.is-right { color: var(--prs-green); }
.prs-blank-fill.is-wrong { color: var(--prs-red); }
.prs-no-answer { background: var(--prs-amber-soft); color: #92400e; padding: 7px 12px; border-radius: 8px; font-size: 13px; margin-bottom: 8px; }
.prs-q__correct { margin-top: 10px; font-size: 13.5px; font-weight: 700; color: var(--prs-green); }

.prs-passage { background: #faf7ff; border: 1px solid var(--prs-border); border-radius: 12px; padding: 16px 18px; margin-bottom: 14px; }
.prs-passage__head { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 10px; }
.prs-passage__label { font-weight: 800; color: var(--prs-primary-dark); flex-shrink: 0; }
.prs-passage__instruction { background: var(--prs-soft); border-radius: 8px; padding: 5px 12px; font-size: 13px; font-weight: 600; color: #4c1d95; flex: 1; min-width: 0; }
.prs-word-bank { display: flex; flex-wrap: wrap; gap: 8px; background: var(--prs-soft); border: 1px dashed #a78bfa; border-radius: 10px; padding: 10px 12px; margin-bottom: 12px; }
.prs-word-chip { background: #fff; border: 1px solid #ddd6fe; color: var(--prs-primary); font-weight: 600; font-size: 13px; padding: 3px 12px; border-radius: 20px; }

/* Nội dung rich text (HTMLPurifier chuẩn hoá style về dạng không dấu cách) */
.pt-rich ul, .pt-rich ol { padding-left: 24px !important; margin-bottom: 10px !important; }
.pt-rich ul { list-style: disc !important; }
.pt-rich ol { list-style: decimal !important; }
.pt-rich li { display: list-item !important; list-style: inherit !important; }
.pt-rich p { margin-bottom: 8px; }
.pt-rich p:last-child { margin-bottom: 0; }
.pt-rich [style*="text-align:center"], .pt-rich [style*="text-align: center"] { text-align: center !important; }
.pt-rich [style*="text-align:right"], .pt-rich [style*="text-align: right"] { text-align: right !important; }
.pt-rich [style*="text-align:justify"], .pt-rich [style*="text-align: justify"] { text-align: justify !important; }
.pt-rich [style*="text-align:left"], .pt-rich [style*="text-align: left"] { text-align: left !important; }

body.prs-modal-open { overflow: hidden; }
.prs-consult__done[hidden], .prs-page form[hidden], .prs-consult__general-error[hidden] { display: none !important; }

/* ── Responsive ─────────────────────────────────────────────────── */
@media (max-width: 991px) {
    .prs-cert { grid-template-columns: 1fr 240px; padding: 28px; }
    .prs-cert__title { font-size: 52px; }
    .prs-level__value { font-size: 96px; }
    .prs-level__value--long { font-size: 76px; }
    .prs-consult { grid-template-columns: 1fr; }
    .prs-consult__aside { order: -1; border-left: none; margin: 0; padding: 8px 6px 16px; }
    .prs-consult__aside h3 { font-size: 24px; }
    .prs-courses__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .prs-speaking { flex-wrap: wrap; }
    .prs-speaking__actions { width: 100%; justify-content: flex-end; }
}
@media (max-width: 767px) {
    .prs-wrap { padding: 8px 14px 48px; }
    .prs-cert { grid-template-columns: 1fr; padding: 24px 20px; text-align: center; }
    .prs-cert__desc { margin-left: auto; margin-right: auto; }
    .prs-cert__title { font-size: 44px; }
    .prs-cert__name { font-size: 24px; }
    .prs-level { justify-self: center; max-width: 230px; }
    .prs-level__pill { font-size: 14px; padding: 5px 14px; }
    .prs-cert .prs-spark, .prs-cert .prs-dot { display: none; }
    .prs-speaking { padding: 18px; }
    .prs-speaking__icon { width: 60px; height: 60px; font-size: 24px; }
    .prs-speaking__title { font-size: 18px; }
    .prs-speaking__actions { justify-content: stretch; }
    .prs-speaking__actions > * { flex: 1; }
    .prs-consult__grid { grid-template-columns: 1fr; }
    .prs-courses { padding: 18px 16px 20px; }
    .prs-courses__head { flex-direction: column; gap: 6px; }
    .prs-courses__title { font-size: 22px; }
    .prs-courses__grid { grid-template-columns: 1fr; }

    .prs-modal { padding: 0; }
    .prs-modal__dialog { max-height: 100vh; height: 100%; border-radius: 0; }
    .prs-modal__title { font-size: 16px; }
    .prs-modal__top, .prs-modal__body { padding-left: 14px; padding-right: 14px; }
    .prs-player { gap: 10px; padding: 8px 10px; }
    .prs-player__play { width: 42px; height: 42px; font-size: 15px; }
    .prs-player__time { font-size: 12px; }
    .prs-q { grid-template-columns: 1fr; gap: 8px; }
    .prs-q__side { flex-direction: row; align-items: center; }
    .prs-tab { font-size: 14px; }
    .prs-tab__ref { display: none; } /* ghi chú "Tham khảo" đã có trong nội dung tab */
}
</style>
@endpush

@section('content')
<div class="prs-page">
    <div class="prs-topbar">
        <a href="/" class="prs-topbar__brand">EDTIKA</a>
        <a href="/" class="prs-topbar__back"><i class="fas fa-arrow-left mr-1"></i>Về trang chủ</a>
    </div>

    <div class="prs-wrap">

        @if($isDemoData)
            <div class="prs-demo-note">
                <i class="fas fa-flask mr-1"></i>
                <strong>Bản xem trước giao diện.</strong> Bạn chưa có kết quả Placement Test thật trong hệ thống —
                đây là dữ liệu mẫu để xem giao diện.
            </div>
        @endif

        {{-- ═══ 1. Chứng chỉ ═══ --}}
        <section class="prs-card prs-cert" aria-label="Chứng chỉ Placement Test">
            {{-- Hoạ tiết trang trí --}}
            <svg class="prs-spark" style="top:22px;right:31%;width:22px;" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <svg class="prs-spark" style="top:18px;right:34px;width:20px;" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <svg class="prs-spark" style="bottom:22px;right:18px;width:20px;" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <span class="prs-dot" style="top:20px;right:44%;width:10px;height:10px;border:2px solid #511D99;"></span>
            <span class="prs-dot" style="top:44%;right:12px;width:9px;height:9px;border:2px solid #511D99;"></span>
            <span class="prs-dot" style="bottom:38%;right:33%;width:6px;height:6px;background:#511D99;"></span>

            <div>
                <div class="prs-cert__brand">EDTIKA</div>
                <div class="prs-cert__kicker">IELTS Placement Test</div>
                <h1 class="prs-cert__title">Certificate</h1>
                <div class="prs-cert__name">{{ $studentName }}</div>
                <p class="prs-cert__desc">has completed the Edtika IELTS Placement Test and achieved the following result.</p>
                <div class="prs-cert__date-label">Completion date</div>
                <div class="prs-cert__date">{{ $completedAt ? $completedAt->format('d M, Y') : '—' }}</div>
            </div>

            <div class="prs-level">
                <div class="prs-level__label">CEFR LEVEL</div>
                <div class="prs-level__value {{ mb_strlen((string) $attempt->final_level) > 2 ? 'prs-level__value--long' : '' }}">{{ $attempt->final_level ?: '—' }}</div>
                @if($levelLabel)
                    <div class="prs-level__pill">{{ $levelLabel }}</div>
                @endif
            </div>
        </section>

        {{-- ═══ 2. Speaking + nút xem đáp án ═══ --}}
        <section class="prs-card prs-speaking">
            <div class="prs-speaking__icon" aria-hidden="true">
                <i class="fas fa-headset"></i>
                <span class="prs-speaking__icon-badge"><i class="fas {{ $hasRecording ? 'fa-check' : 'fa-microphone' }}"></i></span>
            </div>
            <div class="prs-speaking__body">
                @if($hasRecording || $isDemoData)
                    <div class="prs-speaking__title">Phần Speaking đã ghi nhận</div>
                    <div class="prs-speaking__text">Hãy hoàn thành thông tin bên dưới để đội ngũ học thuật gửi kết quả chấm chữa chi tiết phần Speaking cho bạn nhé!</div>
                @else
                    <div class="prs-speaking__title">Bạn đã bỏ qua phần Speaking</div>
                    <div class="prs-speaking__text">Để lại thông tin bên dưới, đội ngũ học thuật sẽ liên hệ để đánh giá thêm kỹ năng nói và tư vấn lộ trình cho bạn.</div>
                @endif
            </div>
            <div class="prs-speaking__actions">
                <span class="prs-chip">({{ $takenCount }}/{{ $maxSteps }} đề hoàn thành)</span>
                <button type="button" class="prs-btn prs-btn--primary" data-prs-open-modal
                        @if(!$canViewKey) disabled title="Chưa có dữ liệu bài làm để xem" @endif>
                    Xem đáp án
                </button>
            </div>
        </section>

        {{-- ═══ 3. Form tư vấn ═══ --}}
        <section class="prs-card prs-consult" id="prsConsult">
            <div class="prs-consult__form-box">

                <div class="prs-consult__done" data-prs-consult-done @unless($hasConsult) hidden @endunless>
                    <div class="prs-consult__done-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <div class="prs-consult__done-title">Đã gửi thông tin thành công</div>
                        <div class="prs-consult__done-text">
                            Đội ngũ học thuật của Edtika sẽ liên hệ với bạn sớm nhất.
                            <span data-prs-consult-time>
                                @if($hasConsult)
                                    (Gửi lúc {{ $consultRequest->created_at->format('H:i, d/m/Y') }})
                                @endif
                            </span>
                        </div>
                        <button type="button" class="prs-link-btn" data-prs-consult-again>Gửi lại thông tin khác</button>
                    </div>
                </div>

                <form action="{{ route('placement.submit_consult') }}" method="POST" data-prs-consult-form novalidate @if($hasConsult) hidden @endif>
                    @csrf
                    <div class="prs-consult__general-error" data-prs-general-error hidden></div>

                    <div class="prs-consult__grid">
                        <div class="prs-field @error('full_name') has-error @enderror" data-field="full_name">
                            <label for="prsFullName">Họ và tên <span class="req">*</span></label>
                            <div class="prs-input">
                                <i class="far fa-user"></i>
                                <input id="prsFullName" type="text" name="full_name" maxlength="100" autocomplete="name"
                                       placeholder="Nhập họ và tên của bạn"
                                       value="{{ old('full_name', $consultPrefill['full_name'] ?? '') }}">
                            </div>
                            <div class="prs-field__error" data-error>@error('full_name'){{ $message }}@enderror</div>
                        </div>

                        <div class="prs-field @error('email') has-error @enderror" data-field="email">
                            <label for="prsEmail">Email (không bắt buộc)</label>
                            <div class="prs-input">
                                <i class="far fa-envelope"></i>
                                <input id="prsEmail" type="email" name="email" maxlength="150" autocomplete="email"
                                       placeholder="Nhập email của bạn"
                                       value="{{ old('email', $consultPrefill['email'] ?? '') }}">
                            </div>
                            <div class="prs-field__error" data-error>@error('email'){{ $message }}@enderror</div>
                        </div>

                        <div class="prs-field @error('mobile') has-error @enderror" data-field="mobile">
                            <label for="prsMobile">Số điện thoại <span class="req">*</span></label>
                            <div class="prs-input">
                                <i class="fas fa-phone-alt"></i>
                                <input id="prsMobile" type="tel" name="mobile" maxlength="20" autocomplete="tel"
                                       placeholder="Nhập số điện thoại của bạn"
                                       value="{{ old('mobile', $consultPrefill['mobile'] ?? '') }}">
                            </div>
                            <div class="prs-field__error" data-error>@error('mobile'){{ $message }}@enderror</div>
                        </div>

                        <div class="prs-field @error('course_key') has-error @enderror" data-field="course_key">
                            <label for="prsCourse">Bạn quan tâm đến khóa học nào? <span class="req">*</span></label>
                            <div class="prs-input">
                                <i class="far fa-compass"></i>
                                <select id="prsCourse" name="course_key">
                                    <option value="">Chọn khóa học bạn quan tâm</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course['key'] }}" @selected(old('course_key') === $course['key'])>
                                            {{ $course['title'] }} ({{ $course['range'] }}){{ $recommendedCourse === $course['key'] ? ' — Phù hợp với bạn' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down prs-input__caret"></i>
                            </div>
                            <div class="prs-field__error" data-error>@error('course_key'){{ $message }}@enderror</div>
                        </div>
                    </div>

                    <button type="submit" class="prs-btn prs-btn--primary prs-btn--block" data-prs-consult-submit>
                        <span data-label>Gửi thông tin</span> <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>

            <div class="prs-consult__aside">
                <h3>Bạn muốn được tư vấn thêm về lộ trình học?</h3>
                <p>Đội ngũ học thuật của Edtika luôn sẵn sàng hỗ trợ bạn!</p>
            </div>
        </section>

        {{-- ═══ 4. Khoá học (dữ liệu giả — xem App\Support\Placement\PlacementCourseCatalog) ═══ --}}
        <section class="prs-card prs-courses">
            <div class="prs-courses__head">
                <div>
                    <h2 class="prs-courses__title">Khóa học dành riêng cho bạn</h2>
                    <p class="prs-courses__sub">Từ kết quả bài thi đã hoàn thành, Edtika thiết kế riêng định hướng học tập giúp bạn tối ưu thời gian và bứt phá band điểm.</p>
                </div>
                <a href="/classes" class="prs-courses__all">Xem tất cả khóa học <i class="fas fa-arrow-right ml-1"></i></a>
            </div>

            <div class="prs-courses__grid">
                @foreach($courses as $course)
                    @php $isRecommended = $recommendedCourse === $course['key']; @endphp
                    <article class="prs-course {{ $isRecommended ? 'is-recommended' : '' }}">
                        @if($isRecommended)
                            <span class="prs-course__badge"><i class="fas fa-star mr-1"></i>Phù hợp với bạn</span>
                        @endif
                        <div class="prs-course__top">
                            <div class="prs-course__icon" aria-hidden="true"><i class="fas {{ $course['icon'] }}"></i></div>
                            <div>
                                <span class="prs-course__range">{{ $course['range'] }}</span>
                                <h3 class="prs-course__name">{{ $course['title'] }}</h3>
                                <p class="prs-course__desc">{{ $course['description'] }}</p>
                                <div class="prs-course__meta">
                                    <span><i class="fas fa-signal"></i>{{ $course['tier'] }}</span>
                                    <span><i class="far fa-clock"></i>{{ $course['duration'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="prs-course__actions">
                            <a href="{{ $course['buy_url'] }}" class="prs-btn prs-btn--primary prs-btn--square">Mua khóa học</a>
                            <a href="{{ $course['detail_url'] }}" class="prs-btn prs-btn--ghost prs-btn--square">Xem chi tiết</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

    </div>
</div>

{{-- ═══ Modal "Chi tiết bài làm" — 1 modal, mỗi đề 1 tab ═══ --}}
@if($canViewKey)
<div class="prs-modal" id="prsAnswerModal" hidden aria-hidden="true">
    <div class="prs-modal__backdrop" data-prs-close></div>
    <div class="prs-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="prsModalTitle">

        <div class="prs-modal__header">
            <h5 class="prs-modal__title" id="prsModalTitle">Chi tiết bài làm</h5>
            <button type="button" class="prs-modal__close" data-prs-close aria-label="Đóng"><i class="fas fa-times"></i></button>
        </div>

        <div class="prs-modal__top">
            {{-- Player audio của từng đề — chỉ hiện player của tab đang mở --}}
            @foreach($testBlocks as $block)
                <div class="prs-player-slot" data-prs-player-slot="{{ $block['index'] }}" hidden>
                    @if($block['audio_clips']->isNotEmpty())
                        @php $firstClip = $block['audio_clips']->first(); @endphp
                        <div class="prs-player" data-prs-player>
                            <audio preload="metadata" src="{{ $firstClip['url'] }}"></audio>
                            <button type="button" class="prs-player__play" data-play aria-label="Phát / tạm dừng"><i class="fas fa-play"></i></button>
                            <div class="prs-player__wave" data-wave role="slider" aria-label="Tua audio" tabindex="0"></div>
                            <span class="prs-player__time" data-time>00:00 / 00:00</span>
                            <button type="button" class="prs-player__speed" data-speed aria-label="Tốc độ phát">1x</button>
                        </div>
                        @if($block['audio_clips']->count() > 1)
                            <div class="prs-player__clips">
                                <span class="prs-player__clips-label">Audio:</span>
                                @foreach($block['audio_clips'] as $ci => $clip)
                                    <button type="button" class="prs-clip-btn {{ $ci === 0 ? 'is-active' : '' }}"
                                            data-prs-clip="{{ $ci }}" data-src="{{ $clip['url'] }}"
                                            data-instruction="{{ $clip['instruction'] }}">{{ $clip['label'] }}</button>
                                @endforeach
                            </div>
                        @endif
                        <div class="prs-player__instruction" data-instruction-text>{{ $firstClip['instruction'] }}</div>
                    @endif
                </div>
            @endforeach

            <div class="prs-tabs" role="tablist">
                @foreach($testBlocks as $block)
                    <button type="button" class="prs-tab" role="tab" data-prs-tab="{{ $block['index'] }}"
                            data-title="Chi tiết bài làm — Đề {{ $block['index'] + 1 }} (Level {{ $block['test']->level }})">
                        Đề {{ $block['index'] + 1 }}
                        @unless($block['is_scored'])<span class="prs-tab__ref">Tham khảo</span>@endunless
                    </button>
                @endforeach
            </div>
        </div>

        <div class="prs-modal__body" data-prs-body>
            @foreach($testBlocks as $block)
                @php
                    $blockPassages = collect($block['test']->reading_passages ?? [])->keyBy('position');
                    $clipCounter = -1;
                @endphp
                <div class="prs-pane" role="tabpanel" data-prs-pane="{{ $block['index'] }}" hidden>

                    <div class="prs-pane__summary">
                        <span>Level <strong>{{ $block['test']->level }}</strong></span>
                        <span>·</span>
                        <span><strong>{{ $block['score'] ?? '—' }}/{{ count($block['questions']) }}</strong> câu đúng</span>
                    </div>

                    @unless($block['is_scored'])
                        <div class="prs-pane__ref-note">
                            <i class="fas fa-info-circle mr-1"></i>Đề này chỉ mang tính tham khảo, không tính vào kết quả level của bạn.
                        </div>
                    @endunless

                    @foreach($block['questions'] as $idx => $item)

                        {{-- Đoạn văn đọc đặt trước câu idx+1 --}}
                        @if($blockPassages->has($idx + 1))
                            @include('web.placement.partials.result_passage', ['psg' => $blockPassages[$idx + 1]])
                        @endif

                        @php
                            $q = $item['question'];
                            $isCorrect = (bool) $item['is_correct'];
                            $given = $item['given'];
                            $isBlank = is_array($given)
                                ? collect($given)->filter(fn ($v) => trim((string) $v) !== '')->isEmpty()
                                : trim((string) $given) === '';
                            $state = $isCorrect ? 'correct' : ($isBlank ? 'blank' : 'wrong');
                            $stateLabel = ['correct' => 'Đúng', 'wrong' => 'Sai', 'blank' => 'Bỏ trống'][$state];
                            $givenScalar = is_scalar($given) ? trim((string) $given) : null;
                            if (!empty($q['audio_group_start']) && !empty($q['audio_url'])) { $clipCounter++; }
                        @endphp

                        <div class="prs-q prs-q--{{ $state }}">
                            <div class="prs-q__side">
                                <span class="prs-q__num">Câu {{ $idx + 1 }}</span>
                                <span class="prs-q__status">{{ $stateLabel }}</span>
                            </div>

                            <div class="prs-q__main">
                                @if(!empty($q['instruction']))
                                    <div class="prs-q__instruction pt-rich">{!! ptRichText($q['instruction']) !!}</div>
                                @endif

                                @if(!empty($q['audio_group_start']) && !empty($q['audio_url']))
                                    <button type="button" class="prs-q__audio-hint" data-prs-jump-clip="{{ $clipCounter }}">
                                        <i class="fas fa-headphones"></i>Nghe audio cho câu {{ $q['audio_group_range'] ?? ($idx + 1) }}
                                    </button>
                                @endif

                                @if($q['type'] === 'multiple_choice')
                                    <div class="prs-q__text pt-rich">{!! ptRichText($q['question_text']) !!}</div>
                                    @if($isBlank)
                                        <div class="prs-no-answer"><i class="fas fa-exclamation-circle mr-1"></i>Bạn không trả lời câu này.</div>
                                    @endif
                                    @foreach($q['options'] as $oi => $option)
                                        @php $isGiven = !$isBlank && $givenScalar === trim((string) $option); @endphp
                                        <div class="prs-opt {{ $isGiven ? 'is-given ' . ($isCorrect ? 'is-right' : 'is-wrong') : '' }}">
                                            <span class="prs-opt__radio"></span>
                                            <span><span class="prs-opt__letter">{{ chr(65 + $oi) }}.</span> {{ $option }}
                                                @if($isGiven)<span class="prs-opt__mine">(bạn đã chọn)</span>@endif
                                            </span>
                                        </div>
                                    @endforeach

                                @elseif($q['type'] === 'listening_image_choice')
                                    <div class="prs-q__text pt-rich">{!! ptRichText($q['question_text']) !!}</div>
                                    @if($isBlank)
                                        <div class="prs-no-answer"><i class="fas fa-exclamation-circle mr-1"></i>Bạn không trả lời câu này.</div>
                                    @endif
                                    <div class="prs-img-grid">
                                        @foreach($q['image_options'] as $imgOpt)
                                            @php $isGiven = !$isBlank && $givenScalar === trim((string) $imgOpt['label']); @endphp
                                            <div class="prs-img-opt {{ $isGiven ? 'is-given ' . ($isCorrect ? 'is-right' : 'is-wrong') : '' }}">
                                                @if($imgOpt['url'])
                                                    <img src="{{ $imgOpt['url'] }}" alt="Ảnh {{ $imgOpt['label'] }}" loading="lazy">
                                                @endif
                                                {{ $imgOpt['label'] }} @if($isGiven)(bạn đã chọn)@endif
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($q['type'] === 'error_correction')
                                    <div class="prs-q__text pt-rich">{!! ptRichText($q['question_text']) !!}</div>
                                    @if($isBlank)
                                        <div class="prs-no-answer"><i class="fas fa-exclamation-circle mr-1"></i>Bạn không trả lời câu này.</div>
                                    @else
                                        <div class="prs-given {{ $isCorrect ? 'is-right' : 'is-wrong' }}">Bạn trả lời: <strong>{{ $givenScalar }}</strong></div>
                                    @endif

                                @elseif($q['type'] === 'sentence_completion')
                                    @php
                                        $parts = preg_split('/_{2,}/', (string) $q['question_text']);
                                        // given có thể null khi hết giờ auto-submit -> ép về mảng.
                                        $givenArr = is_array($given) ? $given : [];
                                    @endphp
                                    <div class="prs-q__text" style="font-weight:500;">
                                        @foreach($parts as $pIdx => $part)
                                            {!! nl2br(e($part)) !!}
                                            @if($pIdx < count($parts) - 1)
                                                @php $fill = trim((string) ($givenArr[$pIdx] ?? '')); @endphp
                                                <span class="prs-blank-fill {{ $isCorrect ? 'is-right' : 'is-wrong' }}">{{ $fill !== '' ? $fill : '(bỏ trống)' }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="prs-q__correct">Đáp án đúng: {{ $item['correct_display'] }}</div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Đoạn văn ở vị trí "Cuối bài" --}}
                    @if($blockPassages->has(count($block['questions']) + 1))
                        @include('web.placement.partials.result_passage', ['psg' => $blockPassages[count($block['questions']) + 1]])
                    @endif
                </div>
            @endforeach
        </div>

        <div class="prs-modal__footer">
            <button type="button" class="prs-btn prs-btn--primary prs-btn--square" data-prs-close>Đóng</button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts_bottom')
<script>
(function () {
    'use strict';

    /* ─────────────── Player audio ─────────────── */
    function fmt(sec) {
        if (!isFinite(sec) || sec < 0) sec = 0;
        var m = Math.floor(sec / 60), s = Math.floor(sec % 60);
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    // Sinh dạng sóng "giả" nhưng cố định theo seed để mỗi lần mở trông như nhau.
    function buildWave(el, seed, count) {
        el.innerHTML = '';
        var x = seed * 9301 + 49297;
        for (var i = 0; i < count; i++) {
            x = (x * 9301 + 49297) % 233280;
            var r = x / 233280;
            var env = 0.45 + 0.55 * Math.abs(Math.sin(i / 5.5 + seed));
            var h = Math.max(12, Math.round((0.25 + r * 0.75) * env * 100));
            var bar = document.createElement('span');
            bar.style.height = h + '%';
            el.appendChild(bar);
        }
    }

    var SPEEDS = [1, 1.25, 1.5, 0.75];
    var allAudios = [];

    function initPlayer(slot, seed) {
        var root = slot.querySelector('[data-prs-player]');
        if (!root) return null;

        var audio = root.querySelector('audio');
        var playBtn = root.querySelector('[data-play]');
        var wave = root.querySelector('[data-wave]');
        var timeEl = root.querySelector('[data-time]');
        var speedBtn = root.querySelector('[data-speed]');
        var instructionEl = slot.querySelector('[data-instruction-text]');
        var clipBtns = slot.querySelectorAll('[data-prs-clip]');
        var speedIdx = 0;
        var barCount = window.innerWidth < 576 ? 36 : 96;

        allAudios.push(audio);
        buildWave(wave, seed + 1, barCount);
        var bars = wave.children;

        function render() {
            var d = audio.duration, t = audio.currentTime;
            timeEl.textContent = fmt(t) + ' / ' + fmt(d);
            var played = d ? Math.round((t / d) * bars.length) : 0;
            for (var i = 0; i < bars.length; i++) bars[i].classList.toggle('is-played', i < played);
        }
        function setIcon() {
            playBtn.innerHTML = audio.paused ? '<i class="fas fa-play"></i>' : '<i class="fas fa-pause"></i>';
        }

        playBtn.addEventListener('click', function () {
            if (audio.paused) {
                allAudios.forEach(function (a) { if (a !== audio) a.pause(); });
                var p = audio.play();
                if (p && p.catch) p.catch(function () {});
            } else {
                audio.pause();
            }
        });
        audio.addEventListener('play', setIcon);
        audio.addEventListener('pause', setIcon);
        audio.addEventListener('ended', function () { setIcon(); render(); });
        audio.addEventListener('timeupdate', render);
        audio.addEventListener('loadedmetadata', function () { audio.playbackRate = SPEEDS[speedIdx]; render(); });

        function seekTo(ratio) {
            if (!audio.duration) return;
            audio.currentTime = Math.min(Math.max(ratio, 0), 1) * audio.duration;
            render();
        }
        wave.addEventListener('click', function (e) {
            var rect = wave.getBoundingClientRect();
            seekTo((e.clientX - rect.left) / rect.width);
        });
        wave.addEventListener('keydown', function (e) {
            if (!audio.duration) return;
            if (e.key === 'ArrowRight') { audio.currentTime = Math.min(audio.duration, audio.currentTime + 5); e.preventDefault(); }
            if (e.key === 'ArrowLeft') { audio.currentTime = Math.max(0, audio.currentTime - 5); e.preventDefault(); }
            render();
        });

        speedBtn.addEventListener('click', function () {
            speedIdx = (speedIdx + 1) % SPEEDS.length;
            audio.playbackRate = SPEEDS[speedIdx];
            speedBtn.textContent = SPEEDS[speedIdx] + 'x';
        });

        function selectClip(i, autoplay) {
            var btn = clipBtns[i];
            if (btn) {
                clipBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                if (audio.getAttribute('src') !== btn.dataset.src) {
                    audio.pause();
                    audio.setAttribute('src', btn.dataset.src);
                    audio.load();
                    buildWave(wave, seed + 1 + i * 7, barCount);
                    bars = wave.children;
                }
                if (instructionEl) instructionEl.textContent = btn.dataset.instruction || '';
            }
            render();
            if (autoplay) {
                allAudios.forEach(function (a) { if (a !== audio) a.pause(); });
                var p = audio.play();
                if (p && p.catch) p.catch(function () {});
            }
        }
        clipBtns.forEach(function (b, i) { b.addEventListener('click', function () { selectClip(i, false); }); });

        render();
        return { selectClip: selectClip };
    }

    /* ─────────────── Modal + tabs ─────────────── */
    var modal = document.getElementById('prsAnswerModal');
    if (modal) {
        var titleEl = document.getElementById('prsModalTitle');
        var bodyEl = modal.querySelector('[data-prs-body]');
        var tabs = modal.querySelectorAll('[data-prs-tab]');
        var panes = modal.querySelectorAll('[data-prs-pane]');
        var slots = modal.querySelectorAll('[data-prs-player-slot]');
        var players = {};
        var lastFocus = null;

        slots.forEach(function (slot) {
            var key = slot.getAttribute('data-prs-player-slot');
            players[key] = initPlayer(slot, parseInt(key, 10) * 13);
        });

        function pauseAll() { allAudios.forEach(function (a) { a.pause(); }); }

        function activate(key) {
            key = String(key);
            pauseAll();
            tabs.forEach(function (t) {
                var on = t.getAttribute('data-prs-tab') === key;
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                if (on) titleEl.textContent = t.dataset.title;
            });
            panes.forEach(function (p) { p.hidden = p.getAttribute('data-prs-pane') !== key; });
            slots.forEach(function (s) {
                var on = s.getAttribute('data-prs-player-slot') === key;
                s.hidden = !on || !s.querySelector('[data-prs-player]');
            });
            bodyEl.scrollTop = 0;
        }

        function open() {
            lastFocus = document.activeElement;
            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('prs-modal-open');
            activate(tabs.length ? tabs[0].getAttribute('data-prs-tab') : 0);
            var closeBtn = modal.querySelector('.prs-modal__close');
            if (closeBtn) closeBtn.focus();
        }
        function close() {
            pauseAll();
            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('prs-modal-open');
            if (lastFocus && lastFocus.focus) lastFocus.focus();
        }

        document.querySelectorAll('[data-prs-open-modal]').forEach(function (b) {
            b.addEventListener('click', function () { if (!b.disabled) open(); });
        });
        modal.querySelectorAll('[data-prs-close]').forEach(function (b) { b.addEventListener('click', close); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) close();
        });
        tabs.forEach(function (t) {
            t.addEventListener('click', function () { activate(t.getAttribute('data-prs-tab')); });
        });

        // Nút "Nghe audio cho câu x–y" trong câu hỏi -> chọn đúng đoạn audio ở player trên đầu.
        modal.querySelectorAll('[data-prs-jump-clip]').forEach(function (b) {
            b.addEventListener('click', function () {
                var pane = b.closest('[data-prs-pane]');
                var player = pane ? players[pane.getAttribute('data-prs-pane')] : null;
                if (player) player.selectClip(parseInt(b.dataset.prsJumpClip, 10) || 0, true);
            });
        });
    }

    /* ─────────────── Form tư vấn ─────────────── */
    var form = document.querySelector('[data-prs-consult-form]');
    var done = document.querySelector('[data-prs-consult-done]');
    if (form && window.fetch && window.FormData) {
        var submitBtn = form.querySelector('[data-prs-consult-submit]');
        var submitLabel = submitBtn.querySelector('[data-label]');
        var generalErr = form.querySelector('[data-prs-general-error]');

        function clearErrors() {
            form.querySelectorAll('[data-field]').forEach(function (f) {
                f.classList.remove('has-error');
                f.querySelector('[data-error]').textContent = '';
            });
            generalErr.hidden = true;
            generalErr.textContent = '';
        }
        function showErrors(errors) {
            Object.keys(errors || {}).forEach(function (name) {
                var f = form.querySelector('[data-field="' + name + '"]');
                if (!f) return;
                f.classList.add('has-error');
                f.querySelector('[data-error]').textContent = errors[name][0];
            });
        }
        function setLoading(on) {
            submitBtn.disabled = on;
            submitLabel.textContent = on ? 'Đang gửi...' : 'Gửi thông tin';
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors();
            setLoading(true);

            fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
                credentials: 'same-origin'
            }).then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (data) {
                    return { status: res.status, data: data };
                });
            }).then(function (r) {
                setLoading(false);
                if (r.status === 200 && r.data.ok) {
                    form.hidden = true;
                    done.hidden = false;
                    done.querySelector('[data-prs-consult-time]').textContent = '(Gửi lúc ' + r.data.sent_at + ')';
                    return;
                }
                if (r.status === 422) {
                    showErrors(r.data.errors);
                    return;
                }
                generalErr.hidden = false;
                generalErr.textContent = r.status === 429
                    ? 'Bạn gửi quá nhanh, vui lòng thử lại sau ít phút.'
                    : (r.status === 419 ? 'Phiên làm việc đã hết hạn, vui lòng tải lại trang.' : 'Có lỗi xảy ra, vui lòng thử lại.');
            }).catch(function () {
                setLoading(false);
                generalErr.hidden = false;
                generalErr.textContent = 'Không kết nối được máy chủ, vui lòng thử lại.';
            });
        });

        var againBtn = document.querySelector('[data-prs-consult-again]');
        if (againBtn) {
            againBtn.addEventListener('click', function () {
                done.hidden = true;
                form.hidden = false;
                var first = form.querySelector('input');
                if (first) first.focus();
            });
        }
    }
})();
</script>
@endpush
