<style>
    /* GĐ4 - Màn chấm bài */
    .bg-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
    .bg-tab { padding: 8px 14px; border-radius: 99px; border: 1px solid var(--bl-border); color: var(--bl-ink); font-size: 13px; font-weight: 600; }
    .bg-tab.is-active { background: var(--bl-primary); border-color: var(--bl-primary); color: #fff; }
    .bg-tab__count { display: inline-block; min-width: 20px; padding: 0 6px; margin-left: 4px; border-radius: 99px; background: rgba(0, 0, 0, .08); font-size: 11px; text-align: center; }
    .bg-tab.is-active .bg-tab__count { background: rgba(255, 255, 255, .25); }

    .bg-table { width: 100%; font-size: 13px; }
    .bg-table th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--bl-sub); padding: 12px 16px; border-bottom: 1px solid var(--bl-border); }
    .bg-table td { padding: 12px 16px; border-bottom: 1px solid var(--bl-border); vertical-align: middle; }

    .bg-status { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; background: #fff5e6; color: #a35d00; }
    .bg-status--passed { background: var(--bl-success-soft); color: var(--bl-success); }
    .bg-status--not_passed { background: #FEF1F1; color: #e03043; }

    .bg-assignment-desc { font-size: 14px; }
    .bg-thread { display: flex; flex-direction: column; gap: 12px; }
    .bg-message { padding: 12px 14px; border-radius: 14px; border: 1px solid var(--bl-border); background: #fcfbfe; }
    .bg-message--teacher { background: var(--bl-primary-soft); border-color: transparent; }
    .bg-message__head { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 6px; }
    .bg-message__body { font-size: 14px; white-space: normal; }
</style>
