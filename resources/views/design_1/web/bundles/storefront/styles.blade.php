<style>
    /* GĐ5 - Trang danh sách / chi tiết khóa học / mua nhanh. Màu theo design EDTIKA */
    :root {
        --sf-p: #5A2B81; --sf-l: #EBE5FC; --sf-ink: #21182e; --sf-sub: #756a82; --sf-border: #eee8f4;
        --sf-success: #008A5A; --sf-mint: #EBFEF7; --sf-red: #e03043; --sf-pink: #FEF1F1;
    }
    .sf-page { color: var(--sf-ink); padding: 24px 0 72px; }
    .sf-wrap { max-width: 1260px; margin: 0 auto; padding: 0 24px; }
    @media (max-width: 767px) { .sf-wrap { padding: 0 16px; } }
    .sf-card { background: #fff; border: 1px solid var(--sf-border); border-radius: 24px; box-shadow: 0 9px 34px rgba(72, 45, 93, .05); }
    .sf-muted { color: var(--sf-sub); }
    .sf-crumb { font-size: 12px; color: var(--sf-sub); display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px; }
    .sf-crumb a { color: var(--sf-sub); }

    .sf-hero { text-align: center; padding: 56px 24px; }
    .sf-hero h1 { font-size: clamp(32px, 5vw, 56px); font-weight: 750; letter-spacing: -2px; line-height: 1.08; margin: 0 auto 16px; max-width: 900px; }
    .sf-hero h1 span { color: var(--sf-p); }
    .sf-hero p { font-size: 15px; color: var(--sf-sub); max-width: 760px; margin: 0 auto; }

    .sf-section-title { font-size: 24px; font-weight: 700; letter-spacing: -.5px; margin: 0 0 6px; }
    .sf-bands { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 16px; }
    @media (max-width: 767px) { .sf-bands { grid-template-columns: 1fr; } }
    .sf-band-label { font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--sf-sub); margin-bottom: 8px; }
    .sf-band-options { display: flex; flex-wrap: wrap; gap: 8px; }
    .sf-band-options button { border: 1px solid var(--sf-border); background: #fff; border-radius: 99px; padding: 8px 14px; font-size: 12px; font-weight: 600; color: var(--sf-ink); }
    .sf-band-options button.is-selected { background: var(--sf-p); border-color: var(--sf-p); color: #fff; }
    .sf-band-options button:disabled { opacity: .4; cursor: not-allowed; }

    .sf-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin: 20px 0; }
    @media (max-width: 991px) { .sf-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .sf-stat { padding: 18px; display: flex; justify-content: space-between; align-items: center; }
    .sf-stat__n { font-size: 22px; font-weight: 750; }
    .sf-stat__s { font-size: 12px; color: var(--sf-sub); }
    .sf-stat__ico { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; background: var(--sf-l); color: var(--sf-p); }

    .sf-catalog { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 16px; }
    .sf-course { position: relative; padding: 22px; display: flex; flex-direction: column; gap: 10px; overflow: hidden; }
    .sf-course.is-recommended { border-color: var(--sf-p); }
    .sf-class { font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--sf-p); }
    .sf-course h3 { font-size: 20px; font-weight: 700; margin: 0; }
    .sf-course p { font-size: 13px; color: var(--sf-sub); margin: 0; }
    .sf-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .sf-tag { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 99px; background: var(--sf-l); color: var(--sf-p); }
    .sf-tag--outline { background: #fff; border: 1px solid var(--sf-border); color: var(--sf-sub); }
    .sf-tag--success { background: var(--sf-mint); color: var(--sf-success); }
    .sf-tag--match { background: var(--sf-p); color: #fff; }
    .sf-course__foot { margin-top: auto; display: flex; justify-content: space-between; align-items: flex-end; gap: 12px; flex-wrap: wrap; padding-top: 12px; border-top: 1px solid var(--sf-border); }
    .sf-price { font-size: 20px; font-weight: 750; color: var(--sf-ink); }
    .sf-old { font-size: 13px; color: var(--sf-sub); text-decoration: line-through; margin-left: 6px; }
    .sf-ribbon { position: absolute; top: 14px; right: 14px; min-width: 40px; height: 40px; padding: 0 8px; border-radius: 99px; display: grid; place-items: center; font-size: 12px; font-weight: 800; background: linear-gradient(135deg, var(--sf-p), #9a5ccc); color: #fff; }

    .sf-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; border-radius: 12px; border: 1px solid var(--sf-border); background: #fff; color: var(--sf-ink) !important; font-size: 13px; font-weight: 600; cursor: pointer; }
    .sf-btn--primary { background: var(--sf-p); border-color: var(--sf-p); color: #fff !important; }
    .sf-btn--lg { padding: 13px 22px; font-size: 15px; border-radius: 14px; }
    .sf-btn:disabled { opacity: .5; cursor: not-allowed; }

    /* Chi tiết */
    .sf-detail-hero { display: grid; grid-template-columns: minmax(0, 1fr) 460px; gap: 32px; padding: 32px; }
    @media (max-width: 991px) { .sf-detail-hero { grid-template-columns: 1fr; padding: 20px; } }
    .sf-eyebrow { font-size: 12px; font-weight: 700; letter-spacing: .1em; color: var(--sf-p); }
    .sf-detail-hero h1 { font-size: clamp(28px, 4vw, 44px); font-weight: 750; letter-spacing: -1.2px; margin: 8px 0 12px; }
    .sf-buy { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-top: 20px; }
    .sf-video { position: relative; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, var(--sf-p), #2a1340); aspect-ratio: 16 / 9; }
    .sf-video iframe, .sf-video video { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
    .sf-video__placeholder { position: absolute; inset: 0; display: grid; place-items: center; color: #fff; font-size: 40px; opacity: .6; }

    .sf-gifts { margin-top: 24px; padding: 24px; background: linear-gradient(135deg, #fbf8ff, #f1eafd); }
    .sf-gift-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-top: 16px; }
    .sf-gift { background: #fff; border-radius: 16px; padding: 16px; text-align: center; border: 1px solid var(--sf-border); }
    .sf-gift__ico { font-size: 28px; }
    .sf-gift b { display: block; font-size: 13px; margin-top: 8px; }
    .sf-gift span { font-size: 11px; color: var(--sf-sub); }

    .sf-slides { display: flex; gap: 12px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 4px 2px 12px; margin-top: 16px; }
    .sf-slide { flex: 0 0 240px; scroll-snap-align: start; text-align: left; padding: 16px; border-radius: 18px; border: 1px solid var(--sf-border); background: #fff; cursor: pointer; }
    .sf-slide.is-current { border-color: var(--sf-p); background: var(--sf-l); }
    .sf-slide__n { font-size: 12px; font-weight: 800; color: var(--sf-p); }
    .sf-slide b { display: block; font-size: 14px; margin: 6px 0; }
    .sf-slide span { font-size: 12px; color: var(--sf-sub); }
    .sf-slide-desc { padding: 20px 24px; margin-top: 8px; }

    /* Panel mua nhanh */
    .sf-veil { position: fixed; inset: 0; background: rgba(33, 24, 46, .4); z-index: 1999; opacity: 0; pointer-events: none; transition: opacity .2s; }
    .sf-panel { position: fixed; top: 0; right: 0; bottom: 0; width: 420px; max-width: 100%; background: #fff; z-index: 2000; transform: translateX(100%); transition: transform .25s ease; display: flex; flex-direction: column; box-shadow: -10px 0 40px rgba(33, 24, 46, .15); }
    .sf-panel-open .sf-veil { opacity: 1; pointer-events: auto; }
    .sf-panel-open .sf-panel { transform: none; }
    .sf-panel__head { display: flex; justify-content: space-between; align-items: center; padding: 18px 20px; border-bottom: 1px solid var(--sf-border); }
    .sf-panel__body { flex: 1; overflow-y: auto; padding: 20px; }
    .sf-panel__foot { padding: 16px 20px; border-top: 1px solid var(--sf-border); display: flex; flex-direction: column; gap: 8px; }
    .sf-steps { display: flex; gap: 8px; margin-bottom: 16px; }
    .sf-step { flex: 1; text-align: center; font-size: 11px; color: var(--sf-sub); }
    .sf-step span { display: grid; place-items: center; width: 26px; height: 26px; margin: 0 auto 4px; border-radius: 50%; background: #f4f1f8; font-weight: 700; }
    .sf-step.is-current { color: var(--sf-p); font-weight: 600; }
    .sf-step.is-current span { background: var(--sf-p); color: #fff; }
    .sf-line { display: flex; justify-content: space-between; font-size: 13px; padding: 8px 0; border-top: 1px solid var(--sf-border); }
    .sf-line--total { font-size: 16px; font-weight: 750; }
    .sf-coupon { display: flex; gap: 8px; margin-top: 12px; }
    .sf-coupon input { flex: 1; }
    .sf-coupon-msg { font-size: 12px; margin-top: 6px; }
    .sf-methods { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; }
    .sf-method { border: 1px solid var(--sf-border); border-radius: 12px; padding: 10px; font-size: 12px; cursor: pointer; }
    .sf-method input { margin-right: 6px; }
    .sf-method b { font-size: 13px; }
    .sf-method:has(input:checked) { border-color: var(--sf-p); background: var(--sf-l); }
    .sf-owned { padding: 16px; border-radius: 16px; background: var(--sf-mint); color: var(--sf-success); font-weight: 600; }
</style>
