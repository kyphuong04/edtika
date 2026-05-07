@php
    $appHeader = true;
    $appFooter = true;
    $isEnglish = app()->getLocale() === 'en';
    $nextLocale = $isEnglish ? 'vi' : 'en';
    $nextLocaleLabel = $isEnglish ? 'VI' : 'ENG';
    $isHomeActive = request()->path() === '/';
    $isClassesActive = request()->is('classes') || request()->is('classes/*');
    $isPlacementActive = request()->is('panel/ielts-tests/practice') || request()->is('panel/ielts-tests/practice/*');
    $isMockActive = request()->is('panel/ielts-tests/mock') || request()->is('panel/ielts-tests/mock/*');
    $isDictionaryActive = request()->is('panel/dictionary') || request()->is('panel/dictionary/*');
    $isNewsActive = request()->is('blog') || request()->is('blog/*');

    $headerLinks = [
        ['label' => 'Trang chủ', 'url' => '/', 'active' => $isHomeActive],
        ['label' => 'Khóa học', 'url' => '/classes', 'active' => $isClassesActive],
        ['label' => 'Kiểm tra đầu vào', 'url' => '/panel/ielts-tests/practice', 'requiresAuth' => true, 'active' => $isPlacementActive],
        ['label' => 'Luyện đề', 'url' => '/panel/ielts-tests/mock', 'requiresAuth' => true, 'active' => $isMockActive],
        ['label' => 'Từ điển & Flashcard', 'url' => '/panel/dictionary', 'requiresAuth' => true, 'active' => $isDictionaryActive],
        ['label' => 'Tin tức', 'url' => '/blog', 'active' => $isNewsActive],
    ];

    $heroBreadcrumbs = [
        ['label' => 'Edtika', 'url' => '/'],
        ['label' => 'Khóa học', 'url' => '/classes'],
    ];

    $currentBands = [
        ['key' => 'band_0_3', 'label' => 'BAND 0 - 3.0'],
        ['key' => 'band_3_5_4_0', 'label' => 'BAND 3.5 - 4.0'],
        ['key' => 'band_4_5_5_0', 'label' => 'BAND 4.5 - 5.0'],
        ['key' => 'band_5_5_6_0', 'label' => 'BAND 5.5 - 6.0'],
    ];

    $targetBands = [
        ['key' => 'band_4_0_4_5', 'label' => 'BAND 4.0 - 4.5 +'],
        ['key' => 'band_5_0_5_5', 'label' => 'BAND 5.0 - 5.5+'],
        ['key' => 'band_6_0_6_5', 'label' => 'BAND 6.0 - 6.5 +'],
        ['key' => 'band_7_plus', 'label' => 'BAND 7.0 +'],
    ];

    $courseHighlightsByTarget = [
        'band_4_0_4_5' => [
            [
                'title' => '2 module',
                'subtitle' => 'Nền tảng ngữ pháp và từ vựng cốt lõi',
                'icon' => 'Tick.png',
                'alt' => 'Tick icon',
            ],
            [
                'title' => '8 tháng',
                'subtitle' => 'Học theo lộ trình cá nhân hóa',
                'icon' => 'Paper.png',
                'alt' => 'Paper icon',
            ],
            [
                'title' => '3 tháng',
                'subtitle' => 'Hoàn thành khoá học với lịch học đều đặn',
                'icon' => 'calendar.png',
                'alt' => 'Calendar icon',
            ],
            [
                'title' => 'Band 4.0 - 4.5 +',
                'subtitle' => 'Mục tiêu đầu ra trong tương lai',
                'icon' => 'Star.png',
                'alt' => 'Star icon',
            ],
        ],
        'band_5_0_5_5' => [
            [
                'title' => '2 module',
                'subtitle' => 'Gồm 8 phần nhỏ',
                'icon' => 'Tick.png',
                'alt' => 'Tick icon',
            ],
            [
                'title' => '12 tháng',
                'subtitle' => 'Học theo lộ trình',
                'icon' => 'Paper.png',
                'alt' => 'Paper icon',
            ],
            [
                'title' => '4 tháng',
                'subtitle' => 'Hoàn thành khoá học với tần suất học mỗi ngày',
                'icon' => 'calendar.png',
                'alt' => 'Calendar icon',
            ],
            [
                'title' => 'Band 5.0 - 5.5 +',
                'subtitle' => 'Mục tiêu đầu ra trong tương lai',
                'icon' => 'Star.png',
                'alt' => 'Star icon',
            ],
        ],
        'band_6_0_6_5' => [
            [
                'title' => '3 module',
                'subtitle' => 'Mở rộng kỹ năng học thuật và phản xạ đề thi',
                'icon' => 'Tick.png',
                'alt' => 'Tick icon',
            ],
            [
                'title' => '14 tháng',
                'subtitle' => 'Lộ trình tăng band từng giai đoạn',
                'icon' => 'Paper.png',
                'alt' => 'Paper icon',
            ],
            [
                'title' => '6 tháng',
                'subtitle' => 'Hoàn thiện trọn bộ Listening Reading Writing Speaking',
                'icon' => 'calendar.png',
                'alt' => 'Calendar icon',
            ],
            [
                'title' => 'Band 6.0 - 6.5 +',
                'subtitle' => 'Mục tiêu đầu ra trong tương lai',
                'icon' => 'Star.png',
                'alt' => 'Star icon',
            ],
        ],
        'band_7_plus' => [
            [
                'title' => '4 module',
                'subtitle' => 'Chiến lược làm bài chuyên sâu cho band cao',
                'icon' => 'Tick.png',
                'alt' => 'Tick icon',
            ],
            [
                'title' => '18 tháng',
                'subtitle' => 'Lộ trình tối ưu với cố vấn học thuật',
                'icon' => 'Paper.png',
                'alt' => 'Paper icon',
            ],
            [
                'title' => '8 tháng',
                'subtitle' => 'Luyện đề chuyên sâu và phản biện bài làm',
                'icon' => 'calendar.png',
                'alt' => 'Calendar icon',
            ],
            [
                'title' => 'Band 7.0 +',
                'subtitle' => 'Mục tiêu đầu ra trong tương lai',
                'icon' => 'Star.png',
                'alt' => 'Star icon',
            ],
        ],
    ];

    $defaultTargetKey = $targetBands[1]['key'];
    $courseHighlights = $courseHighlightsByTarget[$defaultTargetKey] ?? [];

    $suggestedCoursesByTarget = [
        'band_4_0_4_5' => [
            [
                'name' => 'BAND 4.0 - 4.5',
                'items' => ['24h Fast generations', 'Guided Relaxed generations', 'General commercial terms'],
                'price' => '1.099.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
            [
                'name' => 'BAND 4.5 +',
                'items' => ['30h Fast generations', 'Unlimited Relaxed generations', 'General commercial terms'],
                'price' => '1.299.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
        ],
        'band_5_0_5_5' => [
            [
                'name' => 'BAND 5.0 - 5.5',
                'items' => ['30h Fast generations', 'Unlimited Relaxed generations', 'General commercial terms'],
                'price' => '1.499.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
            [
                'name' => 'BAND 5.0 - 5.5 +',
                'items' => ['30h Fast generations', 'Unlimited Relaxed generations', 'General commercial terms'],
                'price' => '1.499.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
        ],
        'band_6_0_6_5' => [
            [
                'name' => 'BAND 6.0 - 6.5',
                'items' => ['40h Fast generations', 'Unlimited Relaxed generations', 'Priority commercial terms'],
                'price' => '1.799.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
            [
                'name' => 'BAND 6.5 +',
                'items' => ['50h Fast generations', 'Unlimited Relaxed generations', 'Priority commercial terms'],
                'price' => '1.999.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
        ],
        'band_7_plus' => [
            [
                'name' => 'BAND 7.0 +',
                'items' => ['60h Fast generations', 'Unlimited Relaxed generations', 'Advanced commercial terms'],
                'price' => '2.199.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
            [
                'name' => 'BAND 7.5 +',
                'items' => ['80h Fast generations', 'Unlimited Relaxed generations', 'Advanced commercial terms'],
                'price' => '2.499.000 đ',
                'billing' => "per editor/month\nbilled monthly",
            ],
        ],
    ];

    $allActiveCourses = \App\Models\Webinar::query()
        ->where('status', \App\Models\Webinar::$active)
        ->where('private', false)
        ->get();

    $fallbackCourse = $allActiveCourses->first();

    $normalizeValue = static function ($value) {
        $value = mb_strtolower((string) $value, 'UTF-8');
        return preg_replace('/[^a-z0-9]/', '', $value);
    };

    $resolveCourseDetailUrl = static function ($courseName) use ($allActiveCourses, $fallbackCourse, $normalizeValue) {
        $needle = $normalizeValue($courseName);

        $matchedCourse = $allActiveCourses->first(function ($activeCourse) use ($needle, $normalizeValue) {
            $title = $normalizeValue($activeCourse->title);

            return str_contains($title, $needle) || str_contains($needle, $title);
        });

        if (!empty($matchedCourse)) {
            return url('/classes/' . $matchedCourse->slug);
        }

        if (!empty($fallbackCourse)) {
            return url('/classes/' . $fallbackCourse->slug);
        }

        return '/classes';
    };

    foreach ($suggestedCoursesByTarget as $targetKey => $targetCourses) {
        foreach ($targetCourses as $courseIndex => $suggestedCourse) {
            $suggestedCoursesByTarget[$targetKey][$courseIndex]['detail_url'] = $resolveCourseDetailUrl($suggestedCourse['name']);
        }
    }

    $suggestedCourses = $suggestedCoursesByTarget[$defaultTargetKey] ?? [];

@endphp

@extends("design_1.web.layouts.app")

@push("styles_top")
    <style>
        .edtika-classes-page {
            --edtika-purple: #511d99;
            --edtika-surface: rgba(212, 211, 254, 0.5);
            --edtika-shadow: 0 24px 50px rgba(92, 69, 154, 0.18);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            background: linear-gradient(180deg, #ecebf3 0%, #edf0f6 62%, #eaf4f1 100%);
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
        }

        .edtika-classes-page::before,
        .edtika-classes-page::after {
            content: '';
            position: absolute;
            pointer-events: none;
            z-index: 0;
            border-radius: 999px;
            filter: blur(2px);
        }

        .edtika-classes-page::before {
            top: 74px;
            left: 10%;
            width: 104px;
            height: 104px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.82) 0%, rgba(255, 255, 255, 0.04) 70%);
            opacity: 0.65;
        }

        .edtika-classes-page::after {
            top: 160px;
            right: 12%;
            width: 174px;
            height: 174px;
            background: radial-gradient(circle, rgba(126, 90, 204, 0.22) 0%, rgba(126, 90, 204, 0.04) 62%, rgba(126, 90, 204, 0) 100%);
            opacity: 0.6;
        }

        .edtika-classes-page__container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1312px;
            padding: 0 28px;
            margin: 0 auto;
        }

        .edtika-classes-page__header {
            height: 106px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .edtika-classes-page__brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 54px;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--edtika-purple);
            text-decoration: none;
            white-space: nowrap;
            line-height: 1;
        }

        .edtika-classes-page__nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex: 1 1 auto;
            margin: 0 24px;
        }

        .edtika-classes-page__nav-link {
            position: relative;
            color: #1f1a32;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity .2s ease;
        }

        .edtika-classes-page__nav-link:hover,
        .edtika-classes-page__nav-link.is-active {
            color: #111118;
            opacity: .86;
        }

        .edtika-classes-page__nav-link.is-active {
            font-weight: 900;
            opacity: 1;
        }

        .edtika-classes-page__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .edtika-classes-page__lang {
            position: relative;
            width: 80px;
            height: 44px;
            border: 2px solid #d8cdea;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.12);
            overflow: hidden;
            padding-left: 8px;
        }

        .edtika-classes-page__lang-thumb {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: var(--edtika-purple);
            transition: left .2s ease, right .2s ease;
        }

        .edtika-classes-page__lang.is-alt .edtika-classes-page__lang-thumb {
            left: auto;
            right: 5px;
        }

        .edtika-classes-page__lang-button {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent;
            color: var(--edtika-purple);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            letter-spacing: .01em;
            justify-content: flex-end;
            padding-right: 9px;
            padding-left: 44px;
        }

        .edtika-classes-page__lang.is-alt .edtika-classes-page__lang-button {
            justify-content: flex-start;
            padding-left: 8px;
            padding-right: 48px;
        }

        .edtika-classes-page__login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            padding: 0 24px;
            border-radius: 999px;
            background: var(--edtika-purple);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.18);
            transition: opacity .2s ease;
        }

        .edtika-classes-page__login:hover,
        .edtika-classes-page__login:focus {
            color: #fff;
            text-decoration: none;
            opacity: .92;
        }

        .edtika-classes-page__main {
            position: relative;
            padding: 0 0 82px;
        }

        .edtika-classes-page__main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 176px;
            background: #d2d2d6;
            z-index: 0;
        }

        .edtika-classes-page__hero-stage {
            position: relative;
            z-index: 1;
            padding-top: 62px;
        }

        .edtika-classes-page__hero-stage::before,
        .edtika-classes-page__hero-stage::after {
            content: '';
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }

        .edtika-classes-page__hero-stage::before {
            top: 20px;
            left: 50%;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.38);
        }

        .edtika-classes-page__hero-stage::after {
            top: 52px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 13px solid transparent;
            border-right: 13px solid transparent;
            border-bottom: 20px solid rgba(255, 255, 255, 0.24);
        }

        .edtika-classes-page__hero,
        .edtika-classes-page__selection {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 28px;
            background: var(--edtika-surface);
            backdrop-filter: blur(18px) saturate(140%);
            box-shadow: var(--edtika-shadow);
            width: 100%;
        }

        .edtika-classes-page__hero {
            min-height: 206px;
            padding: 44px 38px 34px;
            background: linear-gradient(180deg, rgba(212, 211, 254, 0.52) 0%, rgba(212, 211, 254, 0.48) 100%);
            overflow: visible;
        }

        .edtika-classes-page__hero::before,
        .edtika-classes-page__hero::after,
        .edtika-classes-page__selection::before,
        .edtika-classes-page__selection::after {
            content: '';
            position: absolute;
            pointer-events: none;
            border-radius: 999px;
            filter: blur(1px);
        }

        .edtika-classes-page__hero::before {
            inset: 16px auto auto 28px;
            width: 132px;
            height: 132px;
            background: radial-gradient(circle at 38% 34%, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0) 62%);
            opacity: 0.55;
        }

        .edtika-classes-page__hero::after {
            inset: auto -10px -8px auto;
            width: 192px;
            height: 192px;
            background: radial-gradient(circle, rgba(81, 29, 153, 0.18) 0%, rgba(81, 29, 153, 0.02) 66%, rgba(81, 29, 153, 0) 100%);
        }

        .edtika-classes-page__hero-icon {
            position: absolute;
            filter: drop-shadow(0 18px 26px rgba(81, 29, 153, 0.24));
            user-select: none;
            object-fit: contain;
        }

        .edtika-classes-page__hero-icon--left {
            top: 33px;
            left: 34px;
            width: 88px;
            height: 88px;
            z-index: 2;
        }

        .edtika-classes-page__hero-icon--right {
            top: -62px;
            right: 26px;
            width: 210px;
            height: 210px;
            transform: rotate(12deg);
            z-index: 2;
        }

        .edtika-classes-page__hero-breadcrumb {
            position: absolute;
            left: 34px;
            top: 120px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(34, 26, 58, 0.46);
            font-size: 14px;
            font-weight: 500;
            z-index: 2;
        }

        .edtika-classes-page__hero-breadcrumb a {
            color: inherit;
            text-decoration: none;
        }

        .edtika-classes-page__hero-title {
            position: relative;
            z-index: 1;
            max-width: 760px;
            margin: 0 auto;
            padding-top: 12px;
            color: #121212;
            text-align: center;
            font-size: clamp(56px, 4.2vw, 64px);
            line-height: 1.06;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .edtika-classes-page__hero-title span {
            color: var(--edtika-purple);
        }

        .edtika-classes-page__selection {
            margin-top: 28px;
            padding: 38px 40px 42px;
            border-radius: 32px;
            z-index: 1;
        }

        .edtika-classes-page__selection-title {
            margin: 0;
            color: #111111;
            text-align: center;
            font-size: clamp(34px, 2.9vw, 48px);
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .edtika-classes-page__selection-title span {
            color: var(--edtika-purple);
        }

        .edtika-classes-page__selection-subtitle {
            margin-top: 14px;
            color: rgba(61, 54, 82, 0.46);
            text-align: center;
            font-size: 15px;
            font-weight: 500;
        }

        .edtika-classes-page__selector-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 48px;
            max-width: 860px;
            margin: 38px auto 0;
        }

        .edtika-classes-page__selector-card {
            width: 100%;
            max-width: 352px;
            justify-self: center;
            padding: 18px 16px 18px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.65);
            box-shadow: 0 18px 30px rgba(83, 64, 144, 0.12);
        }

        .edtika-classes-page__selector-title {
            margin: 0 0 16px;
            color: #111111;
            text-align: center;
            font-size: 16px;
            line-height: 1.2;
            font-weight: 800;
        }

        .edtika-classes-page__band-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .edtika-classes-page__band-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 54px;
            padding: 8px 16px;
            border: 1.5px solid var(--edtika-purple);
            border-radius: 999px;
            background: transparent;
            color: var(--edtika-purple);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .02em;
            cursor: pointer;
            transition: background-color .2s ease, color .2s ease, transform .2s ease, opacity .2s ease;
        }

        .edtika-classes-page__band-button:hover,
        .edtika-classes-page__band-button.is-active {
            background: var(--edtika-purple);
            color: #fff;
        }

        .edtika-classes-page__band-button:focus-visible,
        .edtika-classes-page__cta:focus-visible,
        .edtika-classes-page__login:focus-visible,
        .edtika-classes-page__nav-link:focus-visible,
        .edtika-classes-page__lang-button:focus-visible {
            outline: 2px solid rgba(81, 29, 153, 0.55);
            outline-offset: 2px;
        }

        .edtika-classes-page__band-button.is-disabled {
            opacity: 0.28;
            cursor: not-allowed;
            pointer-events: none;
        }

        .edtika-classes-page__help {
            margin-top: 40px;
            color: rgba(75, 67, 94, 0.58);
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }

        .edtika-classes-page__cta-row {
            display: flex;
            justify-content: center;
            margin-top: 16px;
        }

        .edtika-classes-page__cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 342px;
            min-height: 56px;
            padding: 10px 28px;
            border: 1.5px solid var(--edtika-purple);
            border-radius: 999px;
            background: transparent;
            color: var(--edtika-purple);
            font-size: 15px;
            font-weight: 800;
            text-decoration: none;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }

        .edtika-classes-page__cta:hover {
            background: var(--edtika-purple);
            color: #fff;
            transform: translateY(-1px);
        }

        .edtika-classes-page__facts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px 26px;
            margin-top: 24px;
        }

        .edtika-classes-page__fact-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 126px;
            padding: 26px 32px;
            border-radius: 34px;
            border: 1px solid rgba(255, 255, 255, 0.56);
            background: rgba(212, 211, 254, 0.5);
            backdrop-filter: blur(14px) saturate(128%);
            box-shadow: 0 14px 28px rgba(92, 69, 154, 0.2);
        }

        .edtika-classes-page__fact-body {
            min-width: 0;
        }

        .edtika-classes-page__fact-title {
            margin: 0;
            color: #0f0f14;
            font-size: 40px;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .edtika-classes-page__fact-subtitle {
            margin: 6px 0 0;
            color: rgba(55, 50, 72, 0.7);
            font-size: 17px;
            line-height: 1.4;
            font-weight: 500;
        }

        .edtika-classes-page__fact-icon {
            width: 88px;
            height: 88px;
            flex-shrink: 0;
            object-fit: contain;
            filter: drop-shadow(0 12px 20px rgba(81, 29, 153, 0.24));
        }

        .edtika-classes-page__suggested {
            margin-top: 28px;
            padding: 42px 30px 48px;
            border-radius: 34px;
            border: 1px solid rgba(255, 255, 255, 0.56);
            background: rgba(212, 211, 254, 0.5);
            backdrop-filter: blur(14px) saturate(128%);
            box-shadow: 0 14px 28px rgba(92, 69, 154, 0.2);
        }

        .edtika-classes-page__suggested-title {
            margin: 0;
            text-align: center;
            color: #0f0f14;
            font-size: clamp(34px, 3vw, 48px);
            line-height: 1.2;
            font-weight: 900;
        }

        .edtika-classes-page__suggested-grid {
            margin-top: 44px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 388px));
            gap: 28px;
            justify-content: center;
        }

        .edtika-classes-page__bundle-card {
            width: 100%;
            max-width: 388px;
            min-height: 610px;
            display: flex;
            flex-direction: column;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.35);
            box-shadow: 0 12px 18px rgba(31, 21, 56, 0.14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .edtika-classes-page__bundle-top {
            height: 218px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .edtika-classes-page__bundle-top img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            opacity: 0.82;
            transform: scale(1.12);
        }

        .edtika-classes-page__bundle-body {
            padding: 22px 22px 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .edtika-classes-page__bundle-name {
            margin: 0;
            color: #171822;
            font-size: clamp(34px, 2.8vw, 42px);
            line-height: 1.08;
            font-weight: 900;
            letter-spacing: -0.01em;
            text-align: center;
        }

        .edtika-classes-page__bundle-features {
            list-style: none;
            margin: 24px 0 16px;
            padding: 0;
            display: grid;
            gap: 12px;
        }

        .edtika-classes-page__bundle-features li {
            position: relative;
            padding-left: 22px;
            color: #2f313a;
            font-size: 17px;
            line-height: 1.28;
            font-weight: 500;
        }

        .edtika-classes-page__bundle-features li::before {
            content: '\2713';
            position: absolute;
            left: 0;
            top: 0;
            color: var(--edtika-purple);
            font-size: 14px;
            font-weight: 900;
        }

        .edtika-classes-page__bundle-bottom {
            margin-top: auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            column-gap: 8px;
        }

        .edtika-classes-page__bundle-price {
            margin-top: 0;
            color: #1a1a23;
            font-size: 18px;
            line-height: 0.98;
            font-weight: 900;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .edtika-classes-page__bundle-billing {
            margin-top: 4px;
            color: #454750;
            font-size: 10px;
            line-height: 1.12;
            font-weight: 500;
            white-space: pre-line;
        }

        .edtika-classes-page__bundle-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .edtika-classes-page__bundle-btn {
            min-width: 74px;
            height: 32px;
            padding: 0 10px;
            border-radius: 999px;
            border: 2px solid var(--edtika-purple);
            font-size: 14px;
            line-height: 1;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .edtika-classes-page__bundle-btn--buy {
            color: var(--edtika-purple);
            background: transparent;
        }

        .edtika-classes-page__bundle-btn--buy:hover,
        .edtika-classes-page__bundle-btn--buy:focus {
            background: var(--edtika-purple);
            color: #fff;
            text-decoration: none;
        }

        .edtika-classes-page__bundle-btn--view {
            color: #fff;
            background: var(--edtika-purple);
        }

        .edtika-classes-page__bundle-btn--view:hover,
        .edtika-classes-page__bundle-btn--view:focus {
            background: #fff;
            color: var(--edtika-purple);
            text-decoration: none;
        }

        .edtika-auth-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(16, 16, 20, 0.38);
        }

        .edtika-auth-modal.is-open {
            display: flex;
        }

        .edtika-auth-modal__dialog {
            width: min(1180px, 100%);
            height: min(734px, calc(100vh - 40px));
            overflow: hidden;
            border-radius: 36px;
            background: rgba(212, 211, 254, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.46);
            box-shadow: 0 24px 56px rgba(28, 19, 50, 0.24);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
        }

        .edtika-auth-modal__close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 0;
            background: rgba(81, 29, 153, 0.18);
            color: #2e1454;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            z-index: 3;
        }

        .edtika-auth-modal__content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100%;
            min-height: 0;
        }

        .edtika-auth-modal__form-side {
            padding: 72px 60px 52px;
            overflow-y: auto;
            min-height: 0;
            max-height: 100%;
        }

        .edtika-auth-modal__tabs {
            display: inline-flex;
            gap: 8px;
            padding: 4px;
            border-radius: 999px;
            border: 1px solid rgba(81, 29, 153, 0.2);
            background: rgba(255, 255, 255, 0.36);
            margin-bottom: 28px;
        }

        .edtika-auth-modal__tab {
            border: 0;
            border-radius: 999px;
            min-width: 132px;
            height: 38px;
            padding: 0 18px;
            font-weight: 700;
            font-size: 14px;
            color: #2a2a33;
            background: transparent;
            cursor: pointer;
        }

        .edtika-auth-modal__tab.is-active {
            color: #fff;
            background: #511D99;
        }

        .edtika-auth-pane {
            display: none;
        }

        .edtika-auth-pane.is-active {
            display: block;
        }

        .edtika-auth-pane__title {
            margin: 0 0 24px;
            font-size: 42px;
            line-height: 1.18;
            color: #101014;
            font-weight: 900;
        }

        .edtika-auth-methods {
            display: flex;
            gap: 6px;
            padding: 4px;
            border: 1px solid rgba(81, 29, 153, 0.24);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.34);
            margin-bottom: 22px;
        }

        .edtika-auth-method {
            flex: 1;
            height: 40px;
            border-radius: 999px;
            border: 0;
            background: transparent;
            color: #2f2f38;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .edtika-auth-method.is-active {
            background: #511D99;
            color: #fff;
        }

        .edtika-auth-role-switch {
            display: flex;
            gap: 4px;
            padding: 4px;
            border: 1px solid rgba(81, 29, 153, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.34);
        }

        .edtika-auth-role-option {
            flex: 1;
            margin: 0;
            cursor: pointer;
        }

        .edtika-auth-role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .edtika-auth-role-option span {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            border-radius: 999px;
            color: #2f2f38;
            font-size: 14px;
            font-weight: 700;
            transition: background-color .2s ease, color .2s ease;
        }

        .edtika-auth-role-option input:checked + span {
            background: #511D99;
            color: #fff;
        }

        .edtika-auth-field {
            margin-bottom: 14px;
        }

        .edtika-auth-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #2f2f38;
        }

        .edtika-auth-input-wrap {
            position: relative;
        }

        .edtika-auth-input {
            width: 100%;
            height: 44px;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.16);
            background: rgba(255, 255, 255, 0.5);
            padding: 0 14px;
            font-size: 15px;
            color: #1f1f27;
        }

        .edtika-auth-input:focus {
            outline: none;
            border-color: rgba(81, 29, 153, 0.45);
            background: rgba(255, 255, 255, 0.68);
        }

        .edtika-auth-input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9797a3;
            font-size: 16px;
            pointer-events: none;
        }

        .edtika-auth-forgot {
            display: block;
            width: 100%;
            text-align: right;
            margin-top: 2px;
            margin-bottom: 18px;
            color: #2f2f38;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .edtika-auth-submit {
            width: 100%;
            height: 48px;
            border: 0;
            border-radius: 999px;
            background: #511D99;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
        }

        .edtika-auth-check {
            margin: 4px 0 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: #2f2f38;
        }

        .edtika-auth-check input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .edtika-auth-check__box {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1px solid rgba(81, 29, 153, 0.4);
            background: rgba(255, 255, 255, 0.6);
            color: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .edtika-auth-check input:checked + .edtika-auth-check__box {
            background: #511D99;
            border-color: #511D99;
            color: #fff;
        }

        .edtika-auth-check__text strong {
            font-weight: 800;
            color: #15151d;
        }

        .edtika-auth-switch-note {
            margin-top: 20px;
            text-align: center;
            color: #6b6b76;
            font-size: 14px;
        }

        .edtika-auth-switch-note button {
            border: 0;
            background: transparent;
            color: #15151d;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .edtika-auth-modal__slider-side {
            background: rgba(16, 16, 20, 0.18);
            padding: 28px;
            display: flex;
            align-items: stretch;
            justify-content: center;
            min-height: 0;
        }

        .edtika-auth-slider {
            width: 100%;
            height: 100%;
            min-height: 0;
            border-radius: 26px;
            overflow: hidden;
            position: relative;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .edtika-auth-slider__image {
            width: min(88%, 520px);
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            opacity: 0.94;
        }

        .edtika-auth-slider__pagination {
            position: absolute;
            bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .edtika-auth-slider__pagination span {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.34);
        }

        .edtika-auth-slider__pagination span.is-active {
            width: 30px;
            background: #511D99;
        }

        .edtika-footer {
            position: relative;
            isolation: isolate;
            z-index: 1;
            margin-top: 0;
            padding-top: 0;
            overflow: hidden;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
        }

        .edtika-footer::before,
        .edtika-footer::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(68px);
            background: linear-gradient(136deg, rgba(134, 34, 255, 0.34), rgba(60, 225, 97, 0.34));
            opacity: .62;
        }

        .edtika-footer::before {
            width: 640px;
            height: 330px;
            left: -210px;
            top: 120px;
            border-radius: 55% 45% 58% 42% / 48% 39% 61% 52%;
            transform: rotate(-12deg);
        }

        .edtika-footer::after {
            width: 640px;
            height: 350px;
            right: -220px;
            top: 120px;
            border-radius: 38% 62% 46% 54% / 62% 43% 57% 38%;
            transform: rotate(13deg);
        }

        .edtika-footer__top {
            display: grid;
            grid-template-columns: minmax(340px, 1.02fr) minmax(740px, 1.98fr);
            gap: 132px;
            align-items: start;
            padding: 56px 0 30px;
            max-width: 1312px;
            margin: 0 auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        .edtika-footer__right {
            display: grid;
            grid-template-columns: 1fr 1fr 1.25fr;
            gap: 56px;
            align-items: start;
        }

        .edtika-footer__brand {
            margin: 0;
            color: #511d99;
            font-size: 42px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .edtika-footer__description {
            margin-top: 22px;
            margin-bottom: 0;
            font-size: 18px;
            line-height: 1.5;
            color: #26262f;
            max-width: 430px;
        }

        .edtika-footer__column-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 800;
            color: #111118;
        }

        .edtika-footer__title-mark {
            display: block;
            margin-top: 5px;
            width: 28px;
            height: 4px;
            border-radius: 999px;
            background: #511d99;
        }

        .edtika-footer__list {
            margin: 22px 0 0;
            padding: 0;
            list-style: none;
        }

        .edtika-footer__list li {
            margin: 0 0 14px;
            font-size: 16px;
            line-height: 1.35;
            color: #1f1f27;
        }

        .edtika-footer__list a,
        .edtika-footer__text {
            color: inherit;
            text-decoration: none;
        }

        .edtika-footer__list a:hover,
        .edtika-footer__list a:focus {
            color: #511d99;
        }

        .edtika-footer__contact-row {
            margin-top: 20px;
        }

        .edtika-footer__contact-row:first-child {
            margin-top: 22px;
        }

        .edtika-footer__contact-label {
            margin: 0;
            font-size: 16px;
            line-height: 1.25;
            font-weight: 800;
            color: #101018;
        }

        .edtika-footer__contact-value {
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 16px;
            line-height: 1.45;
            color: #1f1f27;
        }

        .edtika-footer__bottom {
            padding: 24px 0 22px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
            position: relative;
            max-width: 1312px;
            margin: 0 auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        .edtika-footer__bottom::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            width: 100vw;
            height: 1px;
            background: rgba(16, 16, 20, 0.16);
        }

        .edtika-footer__copyright {
            margin: 0;
            font-size: 16px;
            color: #21212a;
        }

        .edtika-footer__copyright-brand {
            color: #511d99;
            font-weight: 700;
        }

        .edtika-footer__social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .edtika-footer__social-link {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #fff;
            background: #511d99;
        }

        .edtika-footer__social-link svg {
            width: 13px;
            height: 13px;
            color: #fff;
        }

        .edtika-footer__policies {
            margin: 0;
            text-align: right;
            font-size: 16px;
            color: #1f1f27;
        }

        .edtika-footer__dot {
            display: inline-block;
            margin: 0 6px;
            color: #511d99;
        }

        @media (max-width: 1199px) {
            .edtika-classes-page__nav {
                display: none;
            }

            .edtika-classes-page__selector-grid {
                grid-template-columns: 1fr;
            }

            .edtika-classes-page__facts {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .edtika-classes-page__suggested-grid {
                grid-template-columns: 1fr;
            }

            .edtika-auth-modal__content {
                grid-template-columns: 1fr;
            }

            .edtika-auth-modal__slider-side {
                display: none;
            }

            .edtika-auth-modal__form-side {
                padding: 48px 26px 30px;
            }

            .edtika-footer__top {
                grid-template-columns: 1fr;
                gap: 42px;
                padding-top: 44px;
            }

            .edtika-footer__right {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 28px;
            }

            .edtika-footer__bottom {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .edtika-footer__copyright,
            .edtika-footer__policies {
                text-align: center;
            }
        }

        @media (max-width: 767px) {
            .edtika-classes-page__main::before {
                height: 136px;
            }

            .edtika-classes-page__hero-stage {
                padding-top: 44px;
            }

            .edtika-classes-page__hero-stage::before {
                width: 24px;
                height: 24px;
                top: 14px;
            }

            .edtika-classes-page__hero-stage::after {
                top: 34px;
            }

            .edtika-classes-page__container,
            .edtika-footer__top,
            .edtika-footer__bottom {
                padding-left: 16px;
                padding-right: 16px;
            }

            .edtika-classes-page__header {
                height: 84px;
                gap: 12px;
            }

            .edtika-classes-page__brand {
                font-size: 40px;
            }

            .edtika-classes-page__hero {
                min-height: 188px;
                padding: 38px 20px 22px;
            }

            .edtika-classes-page__hero-icon--left {
                top: 22px;
                left: 16px;
                width: 62px;
                height: 62px;
            }

            .edtika-classes-page__hero-icon--right {
                top: -26px;
                right: 8px;
                width: 132px;
                height: 132px;
            }

            .edtika-classes-page__hero-breadcrumb {
                left: 16px;
                top: 86px;
                font-size: 12px;
            }

            .edtika-classes-page__hero-title {
                padding-top: 16px;
                font-size: 38px;
            }

            .edtika-classes-page__selection {
                padding: 28px 18px 22px;
                margin-top: 22px;
                border-radius: 24px;
            }

            .edtika-classes-page__selector-grid {
                grid-template-columns: 1fr;
            }

            .edtika-classes-page__selector-grid {
                gap: 16px;
                margin-top: 24px;
            }

            .edtika-classes-page__help {
                font-size: 14px;
                margin-top: 24px;
            }

            .edtika-classes-page__selector-title,
            .edtika-classes-page__band-button,
            .edtika-classes-page__cta {
                font-size: 16px;
            }

            .edtika-classes-page__band-button {
                min-height: 46px;
            }

            .edtika-classes-page__cta {
                min-width: 260px;
                min-height: 52px;
            }

            .edtika-classes-page__facts {
                margin-top: 18px;
            }

            .edtika-classes-page__fact-card {
                min-height: 98px;
                padding: 18px 18px;
                border-radius: 22px;
            }

            .edtika-classes-page__fact-title {
                font-size: 30px;
            }

            .edtika-classes-page__fact-subtitle {
                margin-top: 4px;
                font-size: 14px;
            }

            .edtika-classes-page__fact-icon {
                width: 64px;
                height: 64px;
            }

            .edtika-classes-page__suggested {
                margin-top: 18px;
                padding: 24px 16px;
                border-radius: 24px;
            }

            .edtika-classes-page__suggested-title {
                font-size: 26px;
            }

            .edtika-classes-page__suggested-grid {
                margin-top: 18px;
                gap: 14px;
                grid-template-columns: 1fr;
            }

            .edtika-classes-page__bundle-card {
                max-width: 100%;
                min-height: 560px;
            }

            .edtika-classes-page__bundle-top {
                height: 196px;
            }

            .edtika-classes-page__bundle-name {
                font-size: clamp(32px, 9vw, 40px);
            }

            .edtika-classes-page__bundle-features li {
                font-size: 15px;
                line-height: 1.3;
            }

            .edtika-footer__right {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .edtika-footer__brand {
                font-size: 36px;
            }

            .edtika-footer__description {
                font-size: 16px;
            }

            .edtika-auth-modal {
                padding: 10px;
            }

            .edtika-auth-modal__dialog {
                border-radius: 24px;
            }

            .edtika-auth-pane__title {
                font-size: 30px;
            }

            .edtika-auth-modal__tab {
                min-width: 108px;
            }
        }
    </style>
@endpush

@section("content")
    <div class="edtika-classes-page">
        <div class="edtika-classes-page__container">
            <header class="edtika-classes-page__header">
                <a href="/" class="edtika-classes-page__brand" aria-label="EDTIKA Home">EDTIKA</a>

                <nav class="edtika-classes-page__nav" aria-label="{{ $isEnglish ? 'Main navigation' : 'Điều hướng chính' }}">
                    @foreach($headerLinks as $headerLink)
                        <a href="{{ $headerLink['url'] }}" class="edtika-classes-page__nav-link {{ !empty($headerLink['active']) ? 'is-active' : '' }}" @if(!empty($headerLink['requiresAuth']) && auth()->guest()) data-open-auth-modal="true" @endif>
                            {{ $headerLink['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="edtika-classes-page__actions">
                    <div class="edtika-classes-page__lang {{ $nextLocale === 'vi' ? 'is-alt' : '' }}" aria-label="{{ $isEnglish ? 'Language switch' : 'Chuyển ngôn ngữ' }}">
                        <div class="edtika-classes-page__lang-thumb"></div>
                        <form action="/locale" method="post" class="m-0 h-100">
                            {{ csrf_field() }}
                            <button class="edtika-classes-page__lang-button" type="submit" name="locale" value="{{ $nextLocale }}">{{ $nextLocaleLabel }}</button>
                        </form>
                    </div>

                    <a href="/login" class="edtika-classes-page__login" data-open-auth-modal="true">Đăng nhập</a>
                </div>
            </header>

            <main class="edtika-classes-page__main">
                <div class="edtika-classes-page__hero-stage">
                    <section class="edtika-classes-page__hero" aria-label="Lộ trình học IELTS">
                        <img src="{{ asset('store/icons/Video.png') }}" alt="Video icon" class="edtika-classes-page__hero-icon edtika-classes-page__hero-icon--left">
                        <img src="{{ asset('store/icons/Document.png') }}" alt="Document icon" class="edtika-classes-page__hero-icon edtika-classes-page__hero-icon--right">

                        <h1 class="edtika-classes-page__hero-title">
                            Lộ trình Học &amp; Luyện<br>
                            <span>IELTS</span> toàn diện
                        </h1>

                        <div class="edtika-classes-page__hero-breadcrumb">
                            @foreach($heroBreadcrumbs as $index => $heroBreadcrumb)
                                @if($index > 0)
                                    <span aria-hidden="true">&gt;</span>
                                @endif
                                <a href="{{ $heroBreadcrumb['url'] }}">{{ $heroBreadcrumb['label'] }}</a>
                            @endforeach
                        </div>
                    </section>
                </div>

                <section class="edtika-classes-page__selection js-ielts-band-selector" aria-label="Chọn khoá học phù hợp">
                    <h2 class="edtika-classes-page__selection-title">
                        Chọn khoá phù hợp để học tập và luyện đề cùng <span>Edtika</span>
                    </h2>

                    <div class="edtika-classes-page__selection-subtitle">Sub-title</div>

                    <form method="get" action="/panel/ielts-tests/practice" class="edtika-classes-page__selector-grid">
                        <input type="hidden" name="current_band" value="{{ $currentBands[0]['key'] }}" data-band-field="current">
                        <input type="hidden" name="target_band" value="{{ $targetBands[1]['key'] }}" data-band-field="target">

                        <div class="edtika-classes-page__selector-card">
                            <h3 class="edtika-classes-page__selector-title">Trình độ hiện tại</h3>

                            <div class="edtika-classes-page__band-list" data-band-group="current">
                                @foreach($currentBands as $bandIndex => $band)
                                    <button
                                        type="button"
                                        class="edtika-classes-page__band-button js-band-option {{ $bandIndex === 0 ? 'is-active' : '' }}"
                                        data-band-group="current"
                                        data-band-index="{{ $bandIndex }}"
                                        data-band-key="{{ $band['key'] }}"
                                    >
                                        {{ $band['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="edtika-classes-page__selector-card">
                            <h3 class="edtika-classes-page__selector-title">Mục tiêu tương lai</h3>

                            <div class="edtika-classes-page__band-list" data-band-group="target">
                                @foreach($targetBands as $bandIndex => $band)
                                    <button
                                        type="button"
                                        class="edtika-classes-page__band-button js-band-option {{ $bandIndex === 1 ? 'is-active' : '' }}"
                                        data-band-group="target"
                                        data-band-index="{{ $bandIndex }}"
                                        data-band-key="{{ $band['key'] }}"
                                    >
                                        {{ $band['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="edtika-classes-page__help" style="grid-column: 1 / -1;">
                            Bạn chưa biết trình độ hiện tại? Chúng tôi có bài kiểm tra đầu vào miễn phí!
                        </div>

                        <div class="edtika-classes-page__cta-row" style="grid-column: 1 / -1;">
                            <button type="submit" class="edtika-classes-page__cta">Kiểm tra đầu vào</button>
                        </div>
                    </form>
                </section>

                <section class="edtika-classes-page__facts" aria-label="Thông tin khoá học" data-highlight-section>
                    @foreach($courseHighlights as $highlightIndex => $highlight)
                        <article class="edtika-classes-page__fact-card" data-fact-card="{{ $highlightIndex }}">
                            <div class="edtika-classes-page__fact-body">
                                <h3 class="edtika-classes-page__fact-title" data-fact-title>{{ $highlight['title'] }}</h3>
                                <p class="edtika-classes-page__fact-subtitle" data-fact-subtitle>{{ $highlight['subtitle'] }}</p>
                            </div>

                            <img
                                src="{{ asset('store/icons/' . $highlight['icon']) }}"
                                alt="{{ $highlight['alt'] }}"
                                class="edtika-classes-page__fact-icon"
                                data-fact-icon
                            >
                        </article>
                    @endforeach
                </section>

                <section class="edtika-classes-page__suggested" aria-label="Gợi ý khóa học phù hợp" data-suggested-section>
                    <h3 class="edtika-classes-page__suggested-title">Gợi ý khóa học phù hợp với bạn</h3>

                    <div class="edtika-classes-page__suggested-grid">
                        @foreach($suggestedCourses as $courseIndex => $course)
                            <article class="edtika-classes-page__bundle-card" data-suggested-card="{{ $courseIndex }}">
                                <div class="edtika-classes-page__bundle-top">
                                    <img src="{{ asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png') }}" alt="Abstract background">
                                </div>

                                <div class="edtika-classes-page__bundle-body">
                                    <h4 class="edtika-classes-page__bundle-name" data-suggested-name>{{ $course['name'] }}</h4>

                                    <ul class="edtika-classes-page__bundle-features">
                                        @foreach($course['items'] as $featureIndex => $feature)
                                            <li data-suggested-feature="{{ $featureIndex }}">{{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    <div class="edtika-classes-page__bundle-bottom">
                                        <div>
                                            <div class="edtika-classes-page__bundle-price" data-suggested-price>{{ $course['price'] }}</div>
                                            <div class="edtika-classes-page__bundle-billing" data-suggested-billing>{{ $course['billing'] }}</div>
                                        </div>

                                        <div class="edtika-classes-page__bundle-actions">
                                            <a href="/bundles" class="edtika-classes-page__bundle-btn edtika-classes-page__bundle-btn--buy">Mua</a>
                                            <a href="{{ $course['detail_url'] ?? '/classes' }}" class="edtika-classes-page__bundle-btn edtika-classes-page__bundle-btn--view" data-suggested-view>Xem</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>

        <div class="edtika-auth-modal" id="edtikaAuthModal" aria-hidden="true">
            <div class="edtika-auth-modal__dialog" role="dialog" aria-modal="true" aria-label="Hộp thoại đăng nhập">
                <button type="button" class="edtika-auth-modal__close" id="edtikaAuthModalClose" aria-label="Đóng">&times;</button>

                <div class="edtika-auth-modal__content">
                    <div class="edtika-auth-modal__form-side">
                        <div class="edtika-auth-modal__tabs" role="tablist" aria-label="Tabs xác thực">
                            <button type="button" class="edtika-auth-modal__tab is-active" data-auth-tab="login">Đăng nhập</button>
                            <button type="button" class="edtika-auth-modal__tab" data-auth-tab="register">Đăng ký</button>
                        </div>

                        <div class="edtika-auth-pane is-active" data-auth-pane="login">
                            <h3 class="edtika-auth-pane__title">Đăng nhập vào tài khoản của bạn</h3>

                            <div class="edtika-auth-methods" role="tablist" aria-label="Phương thức đăng nhập">
                                <button type="button" class="edtika-auth-method is-active" data-login-method="email">Email</button>
                                <button type="button" class="edtika-auth-method" data-login-method="phone">Điện thoại</button>
                            </div>

                            <form method="POST" action="/login">
                                @csrf
                                <input type="hidden" name="type" id="edtikaLoginType" value="email">

                                <div class="edtika-auth-field" data-login-field="email">
                                    <label class="edtika-auth-label" for="edtikaLoginEmail">Email *</label>
                                    <input id="edtikaLoginEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                                </div>

                                <div class="edtika-auth-field" data-login-field="phone" style="display: none;">
                                    <label class="edtika-auth-label" for="edtikaLoginPhone">Điện thoại *</label>
                                    <input id="edtikaLoginPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaLoginPassword">Mật khẩu *</label>
                                    <div class="edtika-auth-input-wrap">
                                        <input id="edtikaLoginPassword" class="edtika-auth-input" type="password" name="password" autocomplete="current-password">
                                        <span class="edtika-auth-input-icon">◌</span>
                                    </div>
                                </div>

                                <a class="edtika-auth-forgot" href="/forget-password">Bạn quên mật khẩu?</a>
                                <button type="submit" class="edtika-auth-submit">Đăng nhập</button>
                            </form>

                            <div class="edtika-auth-switch-note">
                                Bạn chưa có tài khoản? <button type="button" data-auth-tab-switch="register">Đăng ký</button>
                            </div>
                        </div>

                        <div class="edtika-auth-pane" data-auth-pane="register">
                            <h3 class="edtika-auth-pane__title">Tạo tài khoản mới</h3>

                            <form method="POST" action="/register">
                                @csrf

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label">Chọn vai trò</label>

                                    <div class="edtika-auth-role-switch">
                                        <label class="edtika-auth-role-option">
                                            <input type="radio" name="account_type" value="user" checked>
                                            <span>Học viên</span>
                                        </label>

                                        <label class="edtika-auth-role-option">
                                            <input type="radio" name="account_type" value="teacher">
                                            <span>Giảng viên</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaRegisterEmail">Email *</label>
                                    <input id="edtikaRegisterEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaRegisterPhone">Điện thoại (Tùy chọn)</label>
                                    <input id="edtikaRegisterPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaRegisterFullName">Họ và tên *</label>
                                    <input id="edtikaRegisterFullName" class="edtika-auth-input" type="text" name="full_name" autocomplete="name">
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaRegisterPassword">Mật khẩu *</label>
                                    <div class="edtika-auth-input-wrap">
                                        <input id="edtikaRegisterPassword" class="edtika-auth-input" type="password" name="password" autocomplete="new-password">
                                        <span class="edtika-auth-input-icon">◌</span>
                                    </div>
                                </div>

                                <div class="edtika-auth-field">
                                    <label class="edtika-auth-label" for="edtikaRegisterPasswordConfirmation">Nhập lại mật khẩu *</label>
                                    <div class="edtika-auth-input-wrap">
                                        <input id="edtikaRegisterPasswordConfirmation" class="edtika-auth-input" type="password" name="password_confirmation" autocomplete="new-password">
                                        <span class="edtika-auth-input-icon">◌</span>
                                    </div>
                                </div>

                                <label class="edtika-auth-check">
                                    <input type="checkbox" name="term" value="1" required>
                                    <span class="edtika-auth-check__box">✓</span>
                                    <span class="edtika-auth-check__text">Tôi đồng ý với <strong>điều khoản &amp; quy tắc</strong></span>
                                </label>

                                <button type="submit" class="edtika-auth-submit">Đăng ký</button>
                            </form>

                            <div class="edtika-auth-switch-note">
                                Bạn đã có tài khoản? <button type="button" data-auth-tab-switch="login">Đăng nhập</button>
                            </div>
                        </div>
                    </div>

                    <div class="edtika-auth-modal__slider-side">
                        <div class="edtika-auth-slider">
                            <img class="edtika-auth-slider__image" src="{{ asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png') }}" alt="Auth slider image">

                            <div class="edtika-auth-slider__pagination" aria-hidden="true">
                                <span class="is-active"></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="edtika-footer" aria-label="Chân trang">
            <div class="edtika-footer__top">
                <div>
                    <h3 class="edtika-footer__brand">EDTIKA</h3>
                    <p class="edtika-footer__description">
                        Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập và đào tạo.
                    </p>
                </div>

                <div class="edtika-footer__right">
                    <div>
                        <h4 class="edtika-footer__column-title">Hỗ trợ<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/panel/ielts-tests/practice">Kiểm tra đầu vào</a></li>
                            <li><a href="/panel/ielts-tests/mock">Luyện đề</a></li>
                            <li><a href="/panel/dictionary">Từ điển &amp; Flashcard</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">Giới thiệu<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/classes">Khóa học</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="/blog">Tin tức</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">Thông tin liên hệ<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">Số điện thoại/Hotline</p>
                            <p class="edtika-footer__contact-value">0987 654 321</p>
                        </div>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">Thư điện tử</p>
                            <p class="edtika-footer__contact-value">contact@edtika.com</p>
                        </div>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">Địa chỉ</p>
                            <p class="edtika-footer__contact-value">Thành phố Hồ Chí Minh, Việt Nam</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="edtika-footer__bottom">
                <p class="edtika-footer__copyright">
                    Bản quyền 2026 © <span class="edtika-footer__copyright-brand">Edtika.</span> Mọi quyền được bảo lưu.
                </p>

                <div class="edtika-footer__social" aria-label="Social links">
                    <a class="edtika-footer__social-link" href="#" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M13.74 20V12.7H16.21L16.58 9.86H13.74V8.05C13.74 7.23 13.97 6.68 15.15 6.68H16.68V4.14C16.42 4.1 15.52 4 14.47 4C12.29 4 10.8 5.33 10.8 7.77V9.86H8.34V12.7H10.8V20H13.74Z" fill="currentColor"/>
                        </svg>
                    </a>
                    <a class="edtika-footer__social-link" href="#" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="4" y="4" width="16" height="16" rx="5" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="2"/>
                            <circle cx="16.7" cy="7.3" r="1" fill="currentColor"/>
                        </svg>
                    </a>
                    <a class="edtika-footer__social-link" href="#" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M7.23 9.5H4.75V17.5H7.23V9.5Z" fill="currentColor"/>
                            <path d="M6 8.36C6.79 8.36 7.43 7.72 7.43 6.93C7.43 6.14 6.79 5.5 6 5.5C5.21 5.5 4.57 6.14 4.57 6.93C4.57 7.72 5.21 8.36 6 8.36Z" fill="currentColor"/>
                            <path d="M11.1 9.5H8.72V17.5H11.19V13.54C11.19 12.5 11.39 11.49 12.68 11.49C13.95 11.49 13.97 12.68 13.97 13.61V17.5H16.45V13.11C16.45 10.95 15.99 9.29 13.47 9.29C12.26 9.29 11.45 9.95 11.1 10.57V9.5Z" fill="currentColor"/>
                        </svg>
                    </a>
                </div>

                <p class="edtika-footer__policies">
                    Điều khoản &amp; Chính sách <span class="edtika-footer__dot">•</span> Chính sách bảo mật
                </p>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function ($) {
            'use strict';

            $(document).ready(function () {
                const $selector = $('.js-ielts-band-selector');

                if (!$selector.length) {
                    return;
                }

                const $currentField = $selector.find('[data-band-field="current"]');
                const $targetField = $selector.find('[data-band-field="target"]');
                const $currentButtons = $selector.find('[data-band-group="current"] .js-band-option');
                const $targetButtons = $selector.find('[data-band-group="target"] .js-band-option');
                const $factsSection = $('[data-highlight-section]');
                const $suggestedSection = $('[data-suggested-section]');
                const highlightsByTarget = @json($courseHighlightsByTarget);
                const suggestedCoursesByTarget = @json($suggestedCoursesByTarget);
                const iconsBasePath = @json(asset('store/icons'));

                const authModal = document.getElementById('edtikaAuthModal');
                const authModalCloseBtn = document.getElementById('edtikaAuthModalClose');
                const authModalOpenBtns = document.querySelectorAll('[data-open-auth-modal="true"]');
                const authTabs = document.querySelectorAll('[data-auth-tab]');
                const authPanes = document.querySelectorAll('[data-auth-pane]');
                const authTabSwitchBtns = document.querySelectorAll('[data-auth-tab-switch]');
                const loginMethodBtns = document.querySelectorAll('[data-login-method]');
                const loginFieldBlocks = document.querySelectorAll('[data-login-field]');
                const loginTypeInput = document.getElementById('edtikaLoginType');

                function renderHighlightsByTarget(targetKey) {
                    if (!$factsSection.length) {
                        return;
                    }

                    const highlights = highlightsByTarget[targetKey] || [];

                    if (!highlights.length) {
                        return;
                    }

                    $factsSection.find('[data-fact-card]').each(function (index) {
                        const highlight = highlights[index];

                        if (!highlight) {
                            return;
                        }

                        const $card = $(this);
                        $card.find('[data-fact-title]').text(highlight.title || '');
                        $card.find('[data-fact-subtitle]').text(highlight.subtitle || '');

                        const $icon = $card.find('[data-fact-icon]');

                        if ($icon.length && highlight.icon) {
                            $icon.attr('src', iconsBasePath + '/' + highlight.icon);
                            $icon.attr('alt', highlight.alt || highlight.title || 'Course highlight icon');
                        }
                    });
                }

                function renderSuggestedCoursesByTarget(targetKey) {
                    if (!$suggestedSection.length) {
                        return;
                    }

                    const courses = suggestedCoursesByTarget[targetKey] || [];

                    if (!courses.length) {
                        return;
                    }

                    $suggestedSection.find('[data-suggested-card]').each(function (index) {
                        const course = courses[index];

                        if (!course) {
                            return;
                        }

                        const $card = $(this);
                        $card.find('[data-suggested-name]').text(course.name || '');
                        $card.find('[data-suggested-price]').text(course.price || '');
                        $card.find('[data-suggested-billing]').text(course.billing || '');
                        $card.find('[data-suggested-view]').attr('href', course.detail_url || '/classes');

                        $card.find('[data-suggested-feature]').each(function (featureIndex) {
                            const value = Array.isArray(course.items) ? (course.items[featureIndex] || '') : '';
                            $(this).text(value);
                        });
                    });
                }

                function setAuthTab(tabName) {
                    authTabs.forEach(function (tab) {
                        tab.classList.toggle('is-active', tab.getAttribute('data-auth-tab') === tabName);
                    });

                    authPanes.forEach(function (pane) {
                        pane.classList.toggle('is-active', pane.getAttribute('data-auth-pane') === tabName);
                    });
                }

                function openAuthModal() {
                    if (!authModal) {
                        return;
                    }

                    authModal.classList.add('is-open');
                    authModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    setAuthTab('login');
                }

                function closeAuthModal() {
                    if (!authModal) {
                        return;
                    }

                    authModal.classList.remove('is-open');
                    authModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                function setLoginMethod(method) {
                    loginMethodBtns.forEach(function (methodBtn) {
                        methodBtn.classList.toggle('is-active', methodBtn.getAttribute('data-login-method') === method);
                    });

                    loginFieldBlocks.forEach(function (fieldBlock) {
                        const isTarget = fieldBlock.getAttribute('data-login-field') === method;
                        fieldBlock.style.display = isTarget ? 'block' : 'none';
                    });

                    if (loginTypeInput) {
                        loginTypeInput.value = method === 'phone' ? 'mobile' : 'email';
                    }
                }

                function syncTargetState() {
                    const currentIndex = parseInt($currentButtons.filter('.is-active').attr('data-band-index'), 10) || 0;

                    $targetButtons.each(function () {
                        const $button = $(this);
                        const bandIndex = parseInt($button.attr('data-band-index'), 10) || 0;
                        const isAllowed = bandIndex >= currentIndex;

                        $button.toggleClass('is-disabled', !isAllowed);
                        $button.prop('disabled', !isAllowed);
                    });

                    let $activeTarget = $targetButtons.filter('.is-active');

                    if (!$activeTarget.length || $activeTarget.hasClass('is-disabled')) {
                        $activeTarget = $targetButtons.filter(function () {
                            return !$(this).hasClass('is-disabled');
                        }).first();

                        if ($activeTarget.length) {
                            $targetButtons.removeClass('is-active');
                            $activeTarget.addClass('is-active');
                            $targetField.val($activeTarget.attr('data-band-key'));
                        }
                    }

                    renderHighlightsByTarget($targetField.val());
                    renderSuggestedCoursesByTarget($targetField.val());
                }

                $currentButtons.on('click', function () {
                    const $button = $(this);
                    const currentIndex = parseInt($button.attr('data-band-index'), 10) || 0;

                    $currentButtons.removeClass('is-active');
                    $button.addClass('is-active');
                    $currentField.val($button.attr('data-band-key'));

                    $targetButtons.each(function () {
                        const $targetButton = $(this);
                        const targetIndex = parseInt($targetButton.attr('data-band-index'), 10) || 0;
                        const isAllowed = targetIndex >= currentIndex;

                        $targetButton.toggleClass('is-disabled', !isAllowed);
                        $targetButton.prop('disabled', !isAllowed);
                    });

                    const $activeTarget = $targetButtons.filter('.is-active');

                    if (!$activeTarget.length || $activeTarget.hasClass('is-disabled')) {
                        const $firstAllowed = $targetButtons.filter(function () {
                            return !$(this).hasClass('is-disabled');
                        }).first();

                        if ($firstAllowed.length) {
                            $targetButtons.removeClass('is-active');
                            $firstAllowed.addClass('is-active');
                            $targetField.val($firstAllowed.attr('data-band-key'));
                        }
                    }

                    renderHighlightsByTarget($targetField.val());
                    renderSuggestedCoursesByTarget($targetField.val());
                });

                $targetButtons.on('click', function () {
                    const $button = $(this);

                    if ($button.hasClass('is-disabled')) {
                        return;
                    }

                    $targetButtons.removeClass('is-active');
                    $button.addClass('is-active');
                    $targetField.val($button.attr('data-band-key'));
                    renderHighlightsByTarget($targetField.val());
                    renderSuggestedCoursesByTarget($targetField.val());
                });

                authModalOpenBtns.forEach(function (btn) {
                    btn.addEventListener('click', function (event) {
                        event.preventDefault();
                        openAuthModal();
                    });
                });

                if (authModalCloseBtn) {
                    authModalCloseBtn.addEventListener('click', closeAuthModal);
                }

                if (authModal) {
                    authModal.addEventListener('click', function (event) {
                        if (event.target === authModal) {
                            closeAuthModal();
                        }
                    });
                }

                window.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeAuthModal();
                    }
                });

                authTabs.forEach(function (tabBtn) {
                    tabBtn.addEventListener('click', function () {
                        setAuthTab(tabBtn.getAttribute('data-auth-tab'));
                    });
                });

                authTabSwitchBtns.forEach(function (tabSwitchBtn) {
                    tabSwitchBtn.addEventListener('click', function () {
                        setAuthTab(tabSwitchBtn.getAttribute('data-auth-tab-switch'));
                    });
                });

                loginMethodBtns.forEach(function (methodBtn) {
                    methodBtn.addEventListener('click', function () {
                        setLoginMethod(methodBtn.getAttribute('data-login-method'));
                    });
                });

                syncTargetState();
                renderHighlightsByTarget($targetField.val());
                renderSuggestedCoursesByTarget($targetField.val());
                setLoginMethod('email');
            });
        })(jQuery);
    </script>
@endpush
