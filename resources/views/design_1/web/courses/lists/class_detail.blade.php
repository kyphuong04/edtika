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
        ['label' => 'Kiến thức & Tin tức', 'url' => '/blog', 'active' => $isNewsActive],
    ];

    $heroBreadcrumbs = [
        ['label' => 'Edtika', 'url' => '/'],
        ['label' => 'Khóa học', 'url' => '/classes'],
    ];

    $courseTitle = !empty($course->title) ? mb_strtoupper($course->title, 'UTF-8') : 'KHÓA HỌC IELTS';
    $courseTeacher = !empty($course->teacher) ? $course->teacher->full_name : 'Edtika Team';
    $courseTeacherAvatar = !empty($course->teacher) ? $course->teacher->getAvatar(80) : asset('store/icons/user.png');
    $courseCover = !empty($course->getImageCover()) ? $course->getImageCover() : asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png');
    $courseDetailTopics = [
        ['key' => 'intro', 'label' => 'Giới thiệu về khoá học'],
        ['key' => 'content', 'label' => 'Nội dung khoá học'],
        ['key' => 'quiz', 'label' => 'Làm quiz trong lúc học'],
        ['key' => 'mock', 'label' => 'Luyện đề và thi thử'],
        ['key' => 'mentor', 'label' => 'Writing & Speaking được chấm bởi Mentor'],
        ['key' => 'feedback', 'label' => 'Đánh giá để cải thiện khoá học'],
    ];
    $courseIntroText = trim((string) strip_tags($course->description ?? ''));

    if (empty($courseIntroText)) {
        $courseIntroText = 'Khoá học này được thiết kế để giúp bạn xây nền tảng vững chắc và cải thiện toàn diện các kỹ năng IELTS. Bạn sẽ học theo lộ trình rõ ràng, luyện tập thường xuyên và nhận phản hồi chi tiết để tăng điểm số hiệu quả.';
    }

    $courseChapters = $course->chapters()
        ->where('status', 'active')
        ->orderBy('order')
        ->with([
            'chapterItems' => function ($query) {
                $query->orderBy('order')->with(['session', 'file', 'textLesson', 'quiz']);
            }
        ])
        ->get();

    $courseContentModules = [];
    $courseTotalParts = 0;
    $courseTotalLessons = 0;

    foreach ($courseChapters as $moduleIndex => $chapter) {
        $moduleItems = [];

        foreach ($chapter->chapterItems as $chapterItem) {
            $itemTitle = '';
            $itemType = $chapterItem->type;

            if ($itemType === 'session' && !empty($chapterItem->session) && $chapterItem->session->status === 'active') {
                $itemTitle = $chapterItem->session->title;
                $courseTotalLessons++;
            } elseif ($itemType === 'file' && !empty($chapterItem->file) && $chapterItem->file->status === 'active') {
                $itemTitle = $chapterItem->file->title;
                $courseTotalLessons++;
            } elseif ($itemType === 'text_lesson' && !empty($chapterItem->textLesson) && $chapterItem->textLesson->status === 'active') {
                $itemTitle = $chapterItem->textLesson->title;
                $courseTotalLessons++;
            } elseif ($itemType === 'quiz' && !empty($chapterItem->quiz) && $chapterItem->quiz->status === 'active') {
                $itemTitle = $chapterItem->quiz->title;
            }

            if (!empty($itemTitle)) {
                $moduleItems[] = [
                    'type' => $itemType,
                    'title' => $itemTitle,
                ];
            }
        }

        $courseTotalParts += count($moduleItems);

        $courseContentModules[] = [
            'id' => $chapter->id,
            'title' => !empty($chapter->title) ? $chapter->title : ('Module ' . ($moduleIndex + 1)),
            'items' => $moduleItems,
            'itemsCount' => count($moduleItems),
        ];
    }

    if (empty($courseContentModules)) {
        $courseContentModules[] = [
            'id' => 'default-module',
            'title' => 'Module 1',
            'items' => [
                ['type' => 'session', 'title' => 'Bài 1: Giới thiệu tổng quan khoá học'],
                ['type' => 'quiz', 'title' => 'Quiz 1'],
            ],
            'itemsCount' => 2,
        ];

        $courseTotalParts = 2;
        $courseTotalLessons = 1;
    }

    $courseOverviewText = 'Khóa học này bao gồm ' . count($courseContentModules) . ' module, ' . $courseTotalParts . ' phần, ' . $courseTotalLessons . ' bài học';
@endphp

@extends("design_1.web.layouts.app")

@push("styles_top")
    <style>
        .edtika-class-detail-page {
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

        .edtika-class-detail-page::before,
        .edtika-class-detail-page::after {
            content: '';
            position: absolute;
            pointer-events: none;
            z-index: 0;
            border-radius: 999px;
            filter: blur(2px);
        }

        .edtika-class-detail-page::before {
            top: 74px;
            left: 10%;
            width: 104px;
            height: 104px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.82) 0%, rgba(255, 255, 255, 0.04) 70%);
            opacity: 0.65;
        }

        .edtika-class-detail-page::after {
            top: 160px;
            right: 12%;
            width: 174px;
            height: 174px;
            background: radial-gradient(circle, rgba(126, 90, 204, 0.22) 0%, rgba(126, 90, 204, 0.04) 62%, rgba(126, 90, 204, 0) 100%);
            opacity: 0.6;
        }

        .edtika-class-detail-page__container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1312px;
            padding: 0 28px;
            margin: 0 auto;
        }

        .edtika-class-detail-page__header {
            height: 106px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .edtika-class-detail-page__brand {
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

        .edtika-class-detail-page__nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex: 1 1 auto;
            margin: 0 24px;
        }

        .edtika-class-detail-page__nav-link {
            color: #1f1a32;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity .2s ease;
        }

        .edtika-class-detail-page__nav-link:hover,
        .edtika-class-detail-page__nav-link.is-active {
            color: #111118;
            opacity: .86;
        }

        .edtika-class-detail-page__nav-link.is-active {
            font-weight: 900;
            opacity: 1;
        }

        .edtika-class-detail-page__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .edtika-class-detail-page__lang {
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

        .edtika-class-detail-page__lang-thumb {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: var(--edtika-purple);
            transition: left .2s ease, right .2s ease;
        }

        .edtika-class-detail-page__lang.is-alt .edtika-class-detail-page__lang-thumb {
            left: auto;
            right: 5px;
        }

        .edtika-class-detail-page__lang-button {
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

        .edtika-class-detail-page__lang.is-alt .edtika-class-detail-page__lang-button {
            justify-content: flex-start;
            padding-left: 8px;
            padding-right: 48px;
        }

        .edtika-class-detail-page__login {
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

        .edtika-class-detail-page__avatar-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.18);
            background: #fff;
            display: inline-flex;
            flex-shrink: 0;
        }

        .edtika-class-detail-page__avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .edtika-class-detail-page__main {
            position: relative;
            padding: 10px 0 80px;
        }

        .edtika-class-detail-page__topics {
            position: relative;
            padding: 74px 0 36px;
        }

        .edtika-class-detail-page__topics-title {
            margin: 0;
            color: #07070c;
            font-size: clamp(34px, 4.2vw, 58px);
            line-height: 1.06;
            font-weight: 900;
            letter-spacing: -0.03em;
            text-align: center;
        }

        .edtika-class-detail-page__topics-grid {
            position: relative;
            width: min(100%, 1030px);
            height: 660px;
            margin: 54px auto 0;
        }

        .edtika-class-detail-page__topic-column {
            position: absolute;
            inset: 0;
            z-index: 3;
            pointer-events: none;
        }

        .edtika-class-detail-page__topic-column--left {
            inset-inline-start: 0;
        }

        .edtika-class-detail-page__topic-column--right {
            inset-inline-end: 0;
        }

        .edtika-class-detail-page__topic-pill {
            position: absolute;
            width: 268px;
            min-height: 76px;
            padding: 16px 26px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 28px;
            background: rgba(212, 211, 254, 0.4);
            backdrop-filter: blur(18px) saturate(145%);
            -webkit-backdrop-filter: blur(18px) saturate(145%);
            box-shadow: 0 16px 30px rgba(85, 63, 152, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.64);
            color: #5c31ba;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 800;
            text-align: center;
            cursor: pointer;
            transition: transform .22s ease, box-shadow .22s ease, background-color .22s ease, color .22s ease;
            appearance: none;
            outline: none;
            pointer-events: auto;
        }

        .edtika-class-detail-page__topic-pill:nth-child(1) {
            left: calc(50% - 424px);
            top: 104px;
        }

        .edtika-class-detail-page__topic-pill:nth-child(2) {
            left: calc(50% - 516px);
            top: 248px;
        }

        .edtika-class-detail-page__topic-pill:nth-child(3) {
            left: calc(50% - 476px);
            top: 400px;
        }

        .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(1) {
            left: calc(50% + 156px);
            top: 104px;
        }

        .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(2) {
            left: calc(50% + 254px);
            top: 244px;
        }

        .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(3) {
            left: calc(50% + 214px);
            top: 402px;
        }

        .edtika-class-detail-page__topic-pill:hover,
        .edtika-class-detail-page__topic-pill:focus-visible,
        .edtika-class-detail-page__topic-pill.is-active {
            background: rgba(212, 211, 254, 1);
            box-shadow: 0 18px 34px rgba(85, 63, 152, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.84);
            color: #4f24b0;
            transform: translateY(-2px);
        }

        .edtika-class-detail-page__topic-scene {
            position: absolute;
            left: 50%;
            top: 54%;
            width: 560px;
            height: 560px;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            pointer-events: none;
        }

        .edtika-class-detail-page__topic-ring {
            position: absolute;
            width: 534px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: rgba(212, 211, 254, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.54);
            box-shadow: 0 22px 52px rgba(105, 87, 167, 0.10), inset 0 1px 0 rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(16px) saturate(120%);
            -webkit-backdrop-filter: blur(16px) saturate(120%);
        }

        .edtika-class-detail-page__topic-core {
            position: absolute;
            width: 352px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: rgba(212, 211, 254, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: 0 18px 38px rgba(105, 87, 167, 0.16), inset 0 1px 0 rgba(255, 255, 255, 0.68);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
        }

        .edtika-class-detail-page__intro-panel-wrap {
            margin: 34px auto 0;
            width: min(100%, 1220px);
            display: none;
        }

        .edtika-class-detail-page__intro-panel-wrap.is-visible {
            display: block;
        }

        .edtika-class-detail-page__content-panel-wrap {
            margin: 34px auto 0;
            width: min(100%, 1220px);
            display: none;
        }

        .edtika-class-detail-page__content-panel-wrap.is-visible {
            display: block;
        }

        .edtika-class-detail-page__empty-panel-wrap {
            margin: 34px auto 0;
            width: min(100%, 1220px);
            display: none;
        }

        .edtika-class-detail-page__empty-panel-wrap.is-visible {
            display: block;
        }

        .edtika-class-detail-page__empty-panel {
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(212, 211, 254, 0.4);
            backdrop-filter: blur(18px) saturate(145%);
            -webkit-backdrop-filter: blur(18px) saturate(145%);
            box-shadow: 0 24px 42px rgba(84, 65, 142, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.72);
            padding: 42px 46px;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .edtika-class-detail-page__empty-panel p {
            margin: 0;
            color: rgba(31, 28, 46, 0.72);
            font-size: 20px;
            line-height: 1.6;
            font-weight: 600;
        }

        .edtika-class-detail-page__content-panel {
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(212, 211, 254, 0.4);
            backdrop-filter: blur(18px) saturate(145%);
            -webkit-backdrop-filter: blur(18px) saturate(145%);
            box-shadow: 0 24px 42px rgba(84, 65, 142, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.72);
            padding: 22px;
        }

        .edtika-class-detail-page__content-overview {
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.74);
            background: rgba(248, 248, 255, 0.72);
            box-shadow: 0 10px 22px rgba(84, 65, 142, 0.12);
            padding: 14px 16px;
            margin-bottom: 16px;
        }

        .edtika-class-detail-page__content-overview-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: radial-gradient(circle at 30% 30%, rgba(170, 145, 232, 0.95), rgba(81, 29, 153, 0.85));
            box-shadow: 0 10px 20px rgba(81, 29, 153, 0.26);
            flex-shrink: 0;
        }

        .edtika-class-detail-page__content-overview-title {
            margin: 0;
            font-size: 16px;
            line-height: 1.3;
            font-weight: 800;
            color: #1a1630;
        }

        .edtika-class-detail-page__content-overview-text {
            margin: 2px 0 0;
            font-size: 13px;
            line-height: 1.4;
            color: rgba(31, 28, 46, 0.7);
            font-weight: 600;
        }

        .edtika-class-detail-page__content-list {
            max-height: 510px;
            overflow-y: auto;
            padding-right: 6px;
        }

        .edtika-class-detail-page__content-list::-webkit-scrollbar {
            width: 6px;
        }

        .edtika-class-detail-page__content-list::-webkit-scrollbar-track {
            background: rgba(212, 211, 254, 0.45);
            border-radius: 999px;
        }

        .edtika-class-detail-page__content-list::-webkit-scrollbar-thumb {
            background: rgba(81, 29, 153, 0.9);
            border-radius: 999px;
        }

        .edtika-class-detail-page__module-card {
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.74);
            background: rgba(244, 244, 252, 0.68);
            box-shadow: 0 10px 22px rgba(84, 65, 142, 0.12);
            padding: 0;
            margin-top: 14px;
            overflow: hidden;
        }

        .edtika-class-detail-page__module-card:first-child {
            margin-top: 0;
        }

        .edtika-class-detail-page__module-head {
            width: 100%;
            border: 0;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px;
            text-align: left;
            cursor: pointer;
        }

        .edtika-class-detail-page__module-title {
            margin: 0;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 800;
            color: #141224;
        }

        .edtika-class-detail-page__module-meta {
            margin-top: 2px;
            font-size: 13px;
            line-height: 1.3;
            color: rgba(31, 28, 46, 0.58);
            font-weight: 700;
        }

        .edtika-class-detail-page__module-arrow {
            width: 18px;
            height: 18px;
            border-right: 2px solid rgba(34, 32, 46, 0.72);
            border-bottom: 2px solid rgba(34, 32, 46, 0.72);
            transform: rotate(45deg);
            transition: transform .2s ease;
            margin-right: 6px;
            flex-shrink: 0;
        }

        .edtika-class-detail-page__module-card.is-open .edtika-class-detail-page__module-arrow {
            transform: rotate(-135deg);
            margin-top: 6px;
        }

        .edtika-class-detail-page__module-body {
            display: none;
            padding: 0 16px 16px;
        }

        .edtika-class-detail-page__module-card.is-open .edtika-class-detail-page__module-body {
            display: block;
        }

        .edtika-class-detail-page__module-items {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .edtika-class-detail-page__module-item {
            margin-top: 10px;
        }

        .edtika-class-detail-page__module-item:first-child {
            margin-top: 0;
        }

        .edtika-class-detail-page__module-item-row {
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            border: 1px solid rgba(225, 224, 238, 1);
            background: #fff;
            padding: 12px 14px;
        }

        .edtika-class-detail-page__module-item-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid rgba(160, 159, 184, 0.9);
            position: relative;
            flex-shrink: 0;
        }

        .edtika-class-detail-page__module-item-icon.is-quiz::before,
        .edtika-class-detail-page__module-item-icon.is-quiz::after {
            content: '';
            position: absolute;
            background: rgba(105, 97, 150, 0.9);
        }

        .edtika-class-detail-page__module-item-icon.is-quiz::before {
            width: 10px;
            height: 2px;
            top: 11px;
            left: 6px;
        }

        .edtika-class-detail-page__module-item-icon.is-quiz::after {
            width: 2px;
            height: 10px;
            top: 7px;
            left: 10px;
        }

        .edtika-class-detail-page__module-item-icon.is-lesson::before {
            content: '';
            position: absolute;
            inset: 5px;
            border-radius: 3px;
            border: 1px solid rgba(105, 97, 150, 0.9);
        }

        .edtika-class-detail-page__module-item-title {
            margin: 0;
            font-size: 14px;
            line-height: 1.4;
            color: #1f1d2f;
            font-weight: 700;
        }

        .edtika-class-detail-page__content-empty {
            border-radius: 14px;
            border: 1px dashed rgba(160, 159, 184, 0.8);
            background: rgba(255, 255, 255, 0.7);
            padding: 16px;
            margin-top: 10px;
            font-size: 14px;
            color: rgba(31, 28, 46, 0.7);
            font-weight: 600;
        }

        .edtika-class-detail-page__intro-panel {
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(212, 211, 254, 0.4);
            backdrop-filter: blur(18px) saturate(145%);
            -webkit-backdrop-filter: blur(18px) saturate(145%);
            box-shadow: 0 24px 42px rgba(84, 65, 142, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.72);
            padding: 44px 56px;
        }

        .edtika-class-detail-page__intro-panel p {
            margin: 0;
            color: rgba(17, 14, 28, 0.9);
            font-size: 24px;
            line-height: 1.7;
            font-weight: 500;
            text-align: left;
        }

        .edtika-class-detail-page__buy-wrap {
            margin-top: 56px;
            display: flex;
            justify-content: center;
        }

        .edtika-class-detail-page__buy-btn {
            width: min(100%, 660px);
            min-height: 62px;
            border-radius: 999px;
            border: 0;
            background: #511D99;
            color: #fff;
            font-size: clamp(24px, 2.2vw, 32px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 16px 30px rgba(64, 22, 127, 0.32);
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        }

        .edtika-class-detail-page__buy-btn:hover,
        .edtika-class-detail-page__buy-btn:focus-visible {
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 20px 34px rgba(64, 22, 127, 0.36);
            opacity: .98;
        }

        .edtika-class-detail-page__banner {
            width: 100%;
            height: clamp(210px, 29vw, 330px);
            border-radius: 44px;
            overflow: hidden;
            background: #cdccd3;
            position: relative;
            box-shadow: 0 20px 36px rgba(46, 39, 66, 0.12);
        }

        .edtika-class-detail-page__banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.52;
        }

        .edtika-class-detail-page__info-card {
            margin: -56px auto 0;
            width: 100%;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            background: rgba(212, 211, 254, 0.5);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            box-shadow: var(--edtika-shadow);
            padding: 34px 36px;
            position: relative;
            z-index: 3;
            overflow: hidden;
        }

        .edtika-class-detail-page__info-card::before {
            content: '';
            position: absolute;
            inset: auto -56px -48px auto;
            width: 194px;
            height: 194px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(81, 29, 153, 0.24) 0%, rgba(81, 29, 153, 0) 70%);
        }

        .edtika-class-detail-page__icon {
            position: absolute;
            right: 26px;
            top: -18px;
            width: clamp(150px, 17vw, 240px);
            height: auto;
            filter: drop-shadow(0 18px 26px rgba(81, 29, 153, 0.22));
            transform: rotate(11deg);
            pointer-events: none;
        }

        .edtika-class-detail-page__breadcrumbs {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(34, 26, 58, 0.6);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .edtika-class-detail-page__breadcrumbs a {
            color: inherit;
            text-decoration: none;
        }

        .edtika-class-detail-page__stats {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 14px;
            color: rgba(27, 21, 46, 0.78);
            font-size: 14px;
            font-weight: 600;
            flex-wrap: wrap;
        }

        .edtika-class-detail-page__stat {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
        }

        .edtika-class-detail-page__stat svg {
            width: 18px;
            height: 18px;
            color: #1d1a2b;
            flex-shrink: 0;
        }

        .edtika-class-detail-page__title {
            margin: 0;
            color: #0f0f14;
            font-size: clamp(44px, 5vw, 60px);
            line-height: 1.08;
            font-weight: 900;
            letter-spacing: -0.02em;
            width: 100%;
            text-align: center;
        }

        .edtika-class-detail-page__teacher {
            margin-top: 22px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #111118;
            font-size: 22px;
            font-weight: 700;
        }

        .edtika-class-detail-page__teacher-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.68);
            box-shadow: 0 8px 16px rgba(81, 29, 153, 0.2);
        }

        @media (max-width: 1199px) {
            .edtika-class-detail-page__topics-grid {
                width: 100%;
                height: 560px;
            }

            .edtika-class-detail-page__topic-pill {
                width: 224px;
                min-height: 68px;
                font-size: 16px;
                padding: 14px 18px;
            }

            .edtika-class-detail-page__topic-scene {
                top: 53%;
                width: 430px;
                height: 430px;
            }

            .edtika-class-detail-page__topic-ring {
                width: 408px;
            }

            .edtika-class-detail-page__topic-core {
                width: 270px;
            }

            .edtika-class-detail-page__topic-pill:nth-child(1) {
                left: calc(50% - 346px);
                top: 92px;
            }

            .edtika-class-detail-page__topic-pill:nth-child(2) {
                left: calc(50% - 420px);
                top: 228px;
            }

            .edtika-class-detail-page__topic-pill:nth-child(3) {
                left: calc(50% - 386px);
                top: 370px;
            }

            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(1) {
                left: calc(50% + 122px);
                top: 92px;
            }

            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(2) {
                left: calc(50% + 198px);
                top: 228px;
            }

            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(3) {
                left: calc(50% + 164px);
                top: 370px;
            }

            .edtika-class-detail-page__topics {
                padding-top: 58px;
            }

            .edtika-class-detail-page__intro-panel {
                border-radius: 32px;
                padding: 32px 30px;
            }

            .edtika-class-detail-page__content-panel {
                border-radius: 28px;
                padding: 16px;
            }

            .edtika-class-detail-page__empty-panel {
                border-radius: 28px;
                min-height: 240px;
                padding: 28px 24px;
            }

            .edtika-class-detail-page__empty-panel p {
                font-size: 18px;
            }

            .edtika-class-detail-page__content-list {
                max-height: 460px;
            }

            .edtika-class-detail-page__module-title {
                font-size: 22px;
            }

            .edtika-class-detail-page__intro-panel p {
                font-size: 20px;
                line-height: 1.6;
            }

            .edtika-class-detail-page__buy-btn {
                min-height: 58px;
                font-size: clamp(22px, 2.5vw, 28px);
            }
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
            .edtika-class-detail-page__nav {
                display: none;
            }

            .edtika-class-detail-page__info-card {
                width: 100%;
                padding: 24px 22px;
            }

            .edtika-class-detail-page__icon {
                width: 170px;
                right: 8px;
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
            .edtika-class-detail-page__container,
            .edtika-footer__top,
            .edtika-footer__bottom {
                padding-left: 16px;
                padding-right: 16px;
            }

            .edtika-class-detail-page__header {
                height: 84px;
                gap: 12px;
            }

            .edtika-class-detail-page__brand {
                font-size: 40px;
            }

            .edtika-class-detail-page__banner {
                border-radius: 28px;
            }

            .edtika-class-detail-page__info-card {
                margin-top: -40px;
                border-radius: 26px;
                padding: 20px 16px;
            }

            .edtika-class-detail-page__icon {
                width: 112px;
                top: -14px;
            }

            .edtika-class-detail-page__stats {
                font-size: 14px;
                gap: 12px;
                margin-bottom: 12px;
            }

            .edtika-class-detail-page__stat {
                font-size: 14px;
            }

            .edtika-class-detail-page__stat svg {
                width: 14px;
                height: 14px;
            }

            .edtika-class-detail-page__title {
                font-size: 34px;
                max-width: calc(100% - 84px);
            }

            .edtika-class-detail-page__teacher {
                font-size: 16px;
                margin-top: 16px;
            }

            .edtika-class-detail-page__teacher-avatar {
                width: 36px;
                height: 36px;
            }

            .edtika-class-detail-page__topics {
                padding-top: 42px;
            }

            .edtika-class-detail-page__topics-title {
                font-size: clamp(28px, 8vw, 38px);
            }

            .edtika-class-detail-page__topics-grid {
                margin-top: 30px;
                height: auto;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 14px;
            }

            .edtika-class-detail-page__topic-column {
                position: static;
                inset: auto;
                width: 100%;
                max-width: 320px;
                display: grid;
                gap: 12px;
            }

            .edtika-class-detail-page__topic-pill {
                position: static;
                width: 100%;
                min-height: 62px;
                padding: 12px 16px;
                font-size: 14px;
                border-radius: 20px;
            }

            .edtika-class-detail-page__topic-pill:nth-child(1),
            .edtika-class-detail-page__topic-pill:nth-child(2),
            .edtika-class-detail-page__topic-pill:nth-child(3),
            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(1) {
                left: auto;
                top: auto;
            }

            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(2),
            .edtika-class-detail-page__topic-column--right .edtika-class-detail-page__topic-pill:nth-child(3) {
                left: auto;
                top: auto;
            }

            .edtika-class-detail-page__topic-scene {
                position: relative;
                left: auto;
                top: auto;
                transform: none;
                width: 300px;
                height: 300px;
                order: 2;
            }

            .edtika-class-detail-page__topic-ring {
                width: 280px;
            }

            .edtika-class-detail-page__topic-core {
                width: 190px;
            }

            .edtika-class-detail-page__topic-column--left {
                order: 1;
            }

            .edtika-class-detail-page__topic-column--right {
                order: 3;
            }

            .edtika-class-detail-page__intro-panel-wrap {
                margin-top: 26px;
            }

            .edtika-class-detail-page__content-panel-wrap {
                margin-top: 20px;
            }

            .edtika-class-detail-page__empty-panel-wrap {
                margin-top: 20px;
            }

            .edtika-class-detail-page__intro-panel {
                border-radius: 24px;
                padding: 18px 16px;
            }

            .edtika-class-detail-page__content-panel {
                border-radius: 20px;
                padding: 12px;
            }

            .edtika-class-detail-page__empty-panel {
                border-radius: 20px;
                min-height: 180px;
                padding: 20px 16px;
            }

            .edtika-class-detail-page__empty-panel p {
                font-size: 15px;
                line-height: 1.5;
            }

            .edtika-class-detail-page__content-overview {
                border-radius: 12px;
                padding: 10px;
                gap: 10px;
            }

            .edtika-class-detail-page__content-overview-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
            }

            .edtika-class-detail-page__content-overview-title {
                font-size: 14px;
            }

            .edtika-class-detail-page__content-overview-text {
                font-size: 11px;
            }

            .edtika-class-detail-page__content-list {
                max-height: 420px;
                padding-right: 2px;
            }

            .edtika-class-detail-page__module-head {
                padding: 12px;
            }

            .edtika-class-detail-page__module-title {
                font-size: 18px;
            }

            .edtika-class-detail-page__module-meta {
                font-size: 12px;
            }

            .edtika-class-detail-page__module-body {
                padding: 0 12px 12px;
            }

            .edtika-class-detail-page__module-item-row {
                padding: 10px;
            }

            .edtika-class-detail-page__module-item-title {
                font-size: 13px;
            }

            .edtika-class-detail-page__intro-panel p {
                font-size: 15px;
                line-height: 1.6;
            }

            .edtika-class-detail-page__buy-wrap {
                margin-top: 22px;
            }

            .edtika-class-detail-page__buy-btn {
                min-height: 50px;
                font-size: 20px;
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
        }
    </style>
@endpush

@section("content")
    <div class="edtika-class-detail-page">
        <div class="edtika-class-detail-page__container">
            <header class="edtika-class-detail-page__header">
                <a href="/" class="edtika-class-detail-page__brand" aria-label="EDTIKA Home">EDTIKA</a>

                <nav class="edtika-class-detail-page__nav" aria-label="Điều hướng chính">
                    @foreach($headerLinks as $headerLink)
                        <a href="{{ $headerLink['url'] }}" class="edtika-class-detail-page__nav-link {{ !empty($headerLink['active']) ? 'is-active' : '' }}" @if(!empty($headerLink['requiresAuth']) && auth()->guest()) data-open-auth-modal="true" @endif>
                            {{ $headerLink['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="edtika-class-detail-page__actions">
                    <div class="edtika-class-detail-page__lang {{ $nextLocale === 'vi' ? 'is-alt' : '' }}" aria-label="Chuyển ngôn ngữ">
                        <div class="edtika-class-detail-page__lang-thumb"></div>
                        <form action="/locale" method="post" class="m-0 h-100">
                            {{ csrf_field() }}
                            <button class="edtika-class-detail-page__lang-button" type="submit" name="locale" value="{{ $nextLocale }}">{{ $nextLocaleLabel }}</button>
                        </form>
                    </div>

                    @if(auth()->check())
                        <a href="/panel" class="edtika-class-detail-page__avatar-link" aria-label="{{ auth()->user()->full_name }}" title="{{ auth()->user()->full_name }}">
                            <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}" class="edtika-class-detail-page__avatar-image">
                        </a>
                    @else
                        <a href="/login" class="edtika-class-detail-page__login" data-open-auth-modal="true">Đăng nhập</a>
                    @endif
                </div>
            </header>

            <main class="edtika-class-detail-page__main">
                <section class="edtika-class-detail-page__banner" aria-label="Hero banner khóa học">
                    <img src="{{ $courseCover }}" alt="{{ $course->title }}">
                </section>

                <section class="edtika-class-detail-page__info-card" aria-label="Thông tin khóa học">
                    <img src="{{ asset('store/icons/Document.png') }}" alt="Course icon" class="edtika-class-detail-page__icon">

                    <div class="edtika-class-detail-page__breadcrumbs">
                        @foreach($heroBreadcrumbs as $index => $heroBreadcrumb)
                            @if($index > 0)
                                <span aria-hidden="true">&gt;</span>
                            @endif

                            <a href="{{ $heroBreadcrumb['url'] }}">{{ $heroBreadcrumb['label'] }}</a>
                        @endforeach
                    </div>

                    <div class="edtika-class-detail-page__stats">
                        <span class="edtika-class-detail-page__stat">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M16.5 20.5V18.5C16.5 17.12 15.38 16 14 16H6.5C5.12 16 4 17.12 4 18.5V20.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <circle cx="10.25" cy="9.5" r="3.5" stroke="currentColor" stroke-width="1.7"/>
                                <path d="M17 8.5C18.38 8.5 19.5 9.62 19.5 11C19.5 12.38 18.38 13.5 17 13.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            </svg>
                            {{ number_format($studentsCount) }} Học viên
                        </span>

                        <span class="edtika-class-detail-page__stat">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M4 6.5C4 5.67 4.67 5 5.5 5H18.5C19.33 5 20 5.67 20 6.5V17.5C20 18.33 19.33 19 18.5 19H5.5C4.67 19 4 18.33 4 17.5V6.5Z" stroke="currentColor" stroke-width="1.7"/>
                                <path d="M8 9H16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <path d="M8 12H16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <path d="M8 15H13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            </svg>
                            {{ $lessonsCount }} Bài giảng
                        </span>

                        <span class="edtika-class-detail-page__stat">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M4 7.5H20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <path d="M7.5 4V9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <path d="M16.5 4V9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <rect x="4" y="5.5" width="16" height="14.5" rx="2" stroke="currentColor" stroke-width="1.7"/>
                            </svg>
                            {{ $modulesCount }} Module
                        </span>
                    </div>

                    <h1 class="edtika-class-detail-page__title">{{ $courseTitle }}</h1>

                    <div class="edtika-class-detail-page__teacher">
                        <img src="{{ $courseTeacherAvatar }}" alt="{{ $courseTeacher }}" class="edtika-class-detail-page__teacher-avatar">
                        <span>{{ $courseTeacher }}</span>
                    </div>
                </section>

                <section class="edtika-class-detail-page__topics" aria-labelledby="course-details-title">
                    <h2 class="edtika-class-detail-page__topics-title" id="course-details-title">Chi tiết và nội dung khoá học</h2>

                    <div class="edtika-class-detail-page__topics-grid" aria-label="Chi tiết và nội dung khoá học">
                        <div class="edtika-class-detail-page__topic-scene" aria-hidden="true">
                            <span class="edtika-class-detail-page__topic-ring"></span>
                            <span class="edtika-class-detail-page__topic-core"></span>
                        </div>

                        <div class="edtika-class-detail-page__topic-column edtika-class-detail-page__topic-column--left" aria-label="Nhóm menu bên trái">
                            @foreach(array_slice($courseDetailTopics, 0, 3) as $index => $topic)
                                <button
                                    type="button"
                                    class="edtika-class-detail-page__topic-pill {{ $index === 0 ? 'is-active' : '' }}"
                                    data-course-topic
                                    data-topic-key="{{ $topic['key'] }}"
                                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                                >
                                    {{ $topic['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="edtika-class-detail-page__topic-column edtika-class-detail-page__topic-column--right" aria-label="Nhóm menu bên phải">
                            @foreach(array_slice($courseDetailTopics, 3) as $topic)
                                <button
                                    type="button"
                                    class="edtika-class-detail-page__topic-pill"
                                    data-course-topic
                                    data-topic-key="{{ $topic['key'] }}"
                                    aria-pressed="false"
                                >
                                    {{ $topic['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="edtika-class-detail-page__intro-panel-wrap is-visible" data-course-intro-panel>
                        <div class="edtika-class-detail-page__intro-panel">
                            <p>{{ $courseIntroText }}</p>
                        </div>

                        <div class="edtika-class-detail-page__buy-wrap">
                            <button type="button" class="edtika-class-detail-page__buy-btn">Mua khoá học này</button>
                        </div>
                    </div>

                    <div class="edtika-class-detail-page__content-panel-wrap" data-course-content-panel>
                        <div class="edtika-class-detail-page__content-panel">
                            <div class="edtika-class-detail-page__content-overview">
                                <span class="edtika-class-detail-page__content-overview-icon" aria-hidden="true"></span>
                                <div>
                                    <h3 class="edtika-class-detail-page__content-overview-title">Tổng quan chương trình học</h3>
                                    <p class="edtika-class-detail-page__content-overview-text">{{ $courseOverviewText }}</p>
                                </div>
                            </div>

                            <div class="edtika-class-detail-page__content-list" data-content-accordion>
                                @foreach($courseContentModules as $module)
                                    <article class="edtika-class-detail-page__module-card {{ $loop->first ? 'is-open' : '' }}" data-module-card>
                                        <button type="button" class="edtika-class-detail-page__module-head" data-module-toggle aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            <div>
                                                <h4 class="edtika-class-detail-page__module-title">{{ $module['title'] }}</h4>
                                                <p class="edtika-class-detail-page__module-meta">{{ $module['itemsCount'] }} phần</p>
                                            </div>

                                            <span class="edtika-class-detail-page__module-arrow" aria-hidden="true"></span>
                                        </button>

                                        <div class="edtika-class-detail-page__module-body" data-module-body>
                                            @if(empty($module['items']))
                                                <div class="edtika-class-detail-page__content-empty">Chưa có nội dung cho module này.</div>
                                            @else
                                                <ul class="edtika-class-detail-page__module-items">
                                                    @foreach($module['items'] as $item)
                                                        <li class="edtika-class-detail-page__module-item">
                                                            <div class="edtika-class-detail-page__module-item-row">
                                                                <span class="edtika-class-detail-page__module-item-icon {{ $item['type'] === 'quiz' ? 'is-quiz' : 'is-lesson' }}" aria-hidden="true"></span>
                                                                <p class="edtika-class-detail-page__module-item-title">{{ $item['title'] }}</p>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        <div class="edtika-class-detail-page__buy-wrap">
                            <button type="button" class="edtika-class-detail-page__buy-btn">Mua khoá học này</button>
                        </div>
                    </div>

                    <div class="edtika-class-detail-page__empty-panel-wrap" data-course-empty-panel>
                        <div class="edtika-class-detail-page__empty-panel">
                            <p>Nội dung cho mục này đang được cập nhật. Vui lòng quay lại sau để xem chi tiết.</p>
                        </div>

                        <div class="edtika-class-detail-page__buy-wrap">
                            <button type="button" class="edtika-class-detail-page__buy-btn">Mua khoá học này</button>
                        </div>
                    </div>
                </section>
            </main>
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
                            <li><a href="/blog">Kiến thức & Tin tức</a></li>
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
        </footer>
    </div>
@endsection

@push("scripts_bottom")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var page = document.querySelector('.edtika-class-detail-page');

            if (!page) {
                return;
            }

            var topicButtons = Array.prototype.slice.call(page.querySelectorAll('[data-course-topic]'));

            if (!topicButtons.length) {
                return;
            }

            var introPanel = page.querySelector('[data-course-intro-panel]');
            var contentPanel = page.querySelector('[data-course-content-panel]');
            var emptyPanel = page.querySelector('[data-course-empty-panel]');
            var moduleToggles = Array.prototype.slice.call(page.querySelectorAll('[data-module-toggle]'));

            var setActiveTopic = function (activeButton) {
                topicButtons.forEach(function (button) {
                    var isActive = button === activeButton;

                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                if (introPanel && activeButton) {
                    introPanel.classList.toggle('is-visible', activeButton.getAttribute('data-topic-key') === 'intro');
                }

                if (contentPanel && activeButton) {
                    contentPanel.classList.toggle('is-visible', activeButton.getAttribute('data-topic-key') === 'content');
                }

                if (emptyPanel && activeButton) {
                    var topicKey = activeButton.getAttribute('data-topic-key');
                    emptyPanel.classList.toggle('is-visible', topicKey !== 'intro' && topicKey !== 'content');
                }
            };

            topicButtons.forEach(function (button, index) {
                if (index === 0) {
                    setActiveTopic(button);
                }

                button.addEventListener('click', function () {
                    setActiveTopic(button);
                });
            });

            moduleToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    var moduleCard = toggle.closest('[data-module-card]');

                    if (!moduleCard) {
                        return;
                    }

                    var isOpen = moduleCard.classList.contains('is-open');

                    moduleCard.classList.toggle('is-open', !isOpen);
                    toggle.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
                });
            });
        });
    </script>
@endpush