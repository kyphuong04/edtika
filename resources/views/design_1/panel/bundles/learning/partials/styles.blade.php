<style>
    /* GĐ3 - Trang học viên của bundle. Màu theo design EDTIKA */
    :root {
        --bl-primary: #5A2B81;
        --bl-primary-soft: #EBE5FC;
        --bl-ink: #21182e;
        --bl-sub: #756a82;
        --bl-border: #eee8f4;
        --bl-success: #008A5A;
        --bl-success-soft: #EBFEF7;
    }

    .panel-title-and-breadcrumb { display: none !important; }
    .bl-page { padding: 16px 24px 60px; color: var(--bl-ink); }
    @media (max-width: 991px) { .bl-page { padding: 16px 16px 60px; } }

    .bl-layout { display: flex; gap: 24px; align-items: flex-start; }
    .bl-sidebar { width: 240px; flex-shrink: 0; position: sticky; top: 16px; }
    .bl-main { flex: 1; min-width: 0; }
    @media (max-width: 991px) {
        .bl-layout { flex-direction: column; }
        .bl-sidebar { width: 100%; position: static; }
    }

    .bl-card { background: #fff; border: 1px solid var(--bl-border); border-radius: 18px; padding: 18px; box-shadow: 0 10px 32px rgba(51, 24, 82, .045); }
    .bl-muted { color: var(--bl-sub); }
    .bl-small { font-size: 12px; }
    .bl-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bl-sub); margin-bottom: 10px; }
    .bl-link { color: var(--bl-primary); font-weight: 600; }

    .bl-course-switch { list-style: none; padding: 0; margin: 0; }
    .bl-course-switch a { display: block; padding: 8px 10px; border-radius: 10px; color: var(--bl-ink); font-size: 13px; }
    .bl-course-switch a:hover, .bl-course-switch a.is-active { background: var(--bl-primary-soft); color: var(--bl-primary); font-weight: 600; }

    .bl-crumb { font-size: 12px; color: var(--bl-sub); display: flex; gap: 6px; flex-wrap: wrap; }
    .bl-crumb a { color: var(--bl-sub); }
    .bl-title { font-size: 28px; font-weight: 700; letter-spacing: -.5px; margin: 8px 0; }
    .bl-progress-row { display: flex; align-items: center; gap: 12px; margin-top: 12px; }
    .bl-progress-num { font-weight: 700; color: var(--bl-primary); }

    .bl-bar { flex: 1; height: 8px; border-radius: 99px; background: var(--bl-primary-soft); overflow: hidden; }
    .bl-bar i { display: block; height: 100%; background: var(--bl-primary); border-radius: 99px; }

    .bl-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin: 16px 0; }
    @media (max-width: 767px) { .bl-stats { grid-template-columns: 1fr; } }
    .bl-stat { display: flex; align-items: center; gap: 12px; }
    .bl-stat__icon { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; background: var(--bl-primary-soft); color: var(--bl-primary); }
    .bl-stat__value { font-size: 18px; font-weight: 700; }

    .bl-filters { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
    .bl-filter { flex: 1; min-width: 160px; }
    .bl-filter label { font-size: 12px; color: var(--bl-sub); margin-bottom: 4px; display: block; }

    .bl-module { margin-top: 24px; }
    .bl-module__head { display: flex; justify-content: space-between; align-items: flex-end; gap: 12px; margin-bottom: 12px; }
    .bl-module__title { font-size: 18px; font-weight: 700; margin: 6px 0 0; }
    .bl-tag { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 99px; background: var(--bl-primary-soft); color: var(--bl-primary); }
    .bl-tag--outline { background: #fff; border: 1px solid var(--bl-border); color: var(--bl-sub); }
    .bl-lock { font-size: 12px; color: var(--bl-sub); }
    .bl-done { font-size: 12px; font-weight: 600; color: var(--bl-success); background: var(--bl-success-soft); padding: 3px 10px; border-radius: 99px; }

    .bl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
    .bl-lesson { padding: 0; overflow: hidden; display: flex; flex-direction: column; }
    .bl-lesson__cover { position: relative; height: 96px; background: linear-gradient(135deg, var(--bl-primary), #8d5bb8); color: #fff; }
    .bl-skill-listening { background: linear-gradient(135deg, #2b5f81, #5b9bb8); }
    .bl-skill-reading { background: linear-gradient(135deg, #2b8160, #5bb893); }
    .bl-skill-speaking { background: linear-gradient(135deg, #81502b, #b8875b); }
    .bl-lesson__mark { position: absolute; top: 12px; left: 14px; font-size: 10px; font-weight: 700; letter-spacing: .1em; opacity: .85; }
    .bl-lesson__num { position: absolute; bottom: 8px; right: 14px; font-size: 32px; font-weight: 800; opacity: .35; }
    .bl-lesson__body { padding: 14px 16px 16px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
    .bl-lesson__title { font-size: 15px; font-weight: 700; margin: 0; }
    .bl-metrics { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin: 6px 0; }
    .bl-metrics div { font-size: 11px; color: var(--bl-sub); display: flex; flex-direction: column; }
    .bl-metrics span { color: var(--bl-primary); }
    .bl-metrics b { color: var(--bl-ink); font-size: 12px; }
    .bl-state-line { display: flex; justify-content: space-between; font-size: 12px; color: var(--bl-sub); }

    .bl-btn { display: inline-flex; align-items: center; justify-content: center; margin-top: 10px; padding: 8px 14px; border-radius: 10px; border: 1px solid var(--bl-border); background: #fff; color: var(--bl-ink); font-size: 13px; font-weight: 600; }
    .bl-btn:disabled { opacity: .5; cursor: not-allowed; }
    .bl-btn--primary { background: var(--bl-primary); border-color: var(--bl-primary); color: #fff !important; }

    .bl-checkpoints { margin-top: 24px; }
    .bl-checkpoint { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 0; border-top: 1px solid var(--bl-border); }
    .bl-checkpoint:first-of-type { border-top: 0; }

    /* Trang tổng quan lesson */
    .bl-overview { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 24px; }
    @media (max-width: 991px) { .bl-overview { grid-template-columns: 1fr; } }
    .bl-pills { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 12px; }
    .bl-quickstats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 12px; }
    .bl-quickstats span { font-size: 11px; color: var(--bl-sub); display: flex; flex-direction: column; padding: 10px; border-radius: 12px; background: #fcfbfe; border: 1px solid var(--bl-border); }
    .bl-quickstats b { font-size: 15px; color: var(--bl-ink); }
    .bl-items { list-style: none; padding: 0; margin: 0; }
    .bl-items li { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 0; border-top: 1px solid var(--bl-border); }
    .bl-items li:first-child { border-top: 0; }
    .bl-item__type { font-size: 11px; color: var(--bl-sub); }
    .bl-words { width: 100%; font-size: 13px; }
    .bl-words th, .bl-words td { padding: 8px 6px; border-top: 1px solid var(--bl-border); vertical-align: top; }
</style>
