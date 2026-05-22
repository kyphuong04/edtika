{{--
    IELTS Results Page
    - Single-skill tests: left card (score) + right card (illustration/quote)
    - Multi-skill tests: grid of per-skill cards, no illustration/quote
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IELTS Results &mdash; {{ $test->title ?? 'Test' }}</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #e5e5e5;
            height: 100vh;
            overflow: hidden;
            color: #111;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== HEADER ===== */
        .res-header {
            position: fixed;
            top: 12px; left: 12px; right: 12px;
            height: 60px;
            background: #fff;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }
        .res-header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }
        .res-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #d9d9d9;
            border: 2px solid #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            color: #555;
            overflow: hidden;
            flex-shrink: 0;
        }
        .res-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .res-username {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .res-header-title {
            font-size: 15px;
            font-weight: 700;
            color: #111;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        .res-back-btn {
            padding: 8px 20px;
            background: #fff;
            border: 1.5px solid #333;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .res-back-btn:hover { background: #f5f5f5; color: #111; text-decoration: none; }
        .res-back-btn::before { content: '\2190'; font-size: 14px; }

        /* ===== MAIN LAYOUT ===== */
        .res-body {
            position: fixed;
            top: 84px; left: 12px; right: 12px; bottom: 12px;
            display: flex;
            gap: 14px;
            overflow: hidden;
        }

        /* ===== LEFT CARD ===== */
        .res-left {
            flex: 1;
            background: #fff;
            border-radius: 12px;
            padding: 20px 22px 16px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.07);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* Donut + Legend row */
        .res-score-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            margin-bottom: 16px;
        }

        /* SVG Donut */
        .res-donut-wrap {
            position: relative;
            flex-shrink: 0;
        }
        .res-donut-wrap svg { display: block; }
        .res-donut-score {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 900;
            color: #111;
            pointer-events: none;
        }

        /* Legend */
        .res-legend { display: flex; flex-direction: column; gap: 16px; }
        .res-legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            color: #333;
        }
        .legend-box {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .legend-box.correct   { background: #c0c0c0; }
        .legend-box.incorrect { background: #111; border: 3px solid #2563eb; }

        /* Question Dot Grid */
        .res-dots-label {
            font-size: 11px;
            font-weight: 700;
            color: #999;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .res-dots-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
            margin-bottom: 12px;
            flex: 1;
            align-content: start;
            place-items: center;
        }
        .res-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #111;
            color: #fff;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }
        .res-dot.correct    { background: #c0c0c0; color: #555; }
        .res-dot.unanswered { background: #e0e0e0; color: #999; }

        /* View Details */
        .res-detail-btn-wrap { text-align: center; }
        .res-detail-btn {
            display: inline-block;
            padding: 10px 36px;
            border: 1.5px solid #555;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }
        .res-detail-btn:hover { background: #f5f5f5; color: #111; text-decoration: none; }

        /* ===== RIGHT CARD ===== */
        .res-right {
            width: 42%;
            flex-shrink: 0;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            overflow-y: auto;
        }

        /* Illustration */
        .res-illustration {
            width: 100%;
            background: #efefef;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-height: 0;
        }
        .res-illustration svg { width: 85%; height: auto; }

        /* Quote */
        .res-quote {
            text-align: center;
            font-size: 15px;
            line-height: 1.75;
            color: #444;
            font-style: italic;
            padding: 0 4px;
        }
        .res-quote-author {
            display: block;
            margin-top: 10px;
            font-size: 12px;
            font-style: normal;
            font-weight: 700;
            color: #999;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        /* ===== WRITING TASK CARDS ===== */
        .wr-tasks-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            flex: 1;
            justify-content: center;
        }
        .wr-task-card {
            border: 1.5px solid #ccc;
            border-radius: 12px;
            padding: 22px 28px;
            background: #fafafa;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .wr-task-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #111;
            text-align: center;
        }
        .wr-task-card-status {
            font-size: 13px;
            color: #555;
            text-align: center;
        }
        .wr-task-pending {
            font-style: italic;
            color: #888;
        }

        /* ===== SPEAKING CRITERIA ===== */
        .sp-left-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            align-items: center;
            justify-content: center;
            gap: 0;
        }
        .sp-overall {
            text-align: center;
            font-size: 38px;
            font-weight: 900;
            color: #111;
            letter-spacing: 0.5px;
            margin-bottom: 36px;
        }
        .sp-overall-label {
            font-size: 22px;
        }
        .sp-criteria-list {
            display: flex;
            flex-direction: column;
            gap: 28px;
            align-items: center;
            width: 100%;
        }
        .sp-criterion-row {
            display: flex;
            align-items: center;
            gap: 22px;
            width: 100%;
            max-width: 360px;
        }
        .sp-score-badge {
            width: 72px;
            height: 72px;
            border: 1.5px solid #bbb;
            border-radius: 12px;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            color: #111;
            flex-shrink: 0;
        }
        .sp-score-badge.empty {
            background: #f0f0f0;
            color: #bbb;
        }
        .sp-score-badge.empty::after {
            content: '—';
            font-size: 18px;
            color: #bbb;
        }
        .sp-criterion-name {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }
        /* Speaking right panel */
        .sp-right-player {
            width: 100%;
            flex: 1;
            min-height: 0;
            background: #e8e8e8;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sp-right-player svg { opacity: 0.4; }
        .sp-right-feedback {
            font-size: 14px;
            line-height: 1.75;
            color: #444;
            text-align: center;
            padding: 0 4px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .res-body { flex-direction: column; overflow-y: auto; }
            .res-right { width: 100%; flex-shrink: 0; }
            .res-illustration { flex: none; aspect-ratio: 4/3; }
            .res-header-title { font-size: 11px; letter-spacing: 0; }
        }
        @media (max-width: 480px) {
            .res-dots-grid { gap: 6px; }
            .res-score-row { gap: 16px; }
        }

        /* ===== MULTI-SKILL LAYOUT ===== */
        .res-multi-body {
            position: fixed;
            top: 84px; left: 12px; right: 12px; bottom: 12px;
            overflow-y: auto;
            padding: 4px 0 8px;
        }
        .res-multi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            height: 100%;
        }
        .res-multi-grid.skills-1 { grid-template-columns: 1fr; max-width: 560px; margin: 0 auto; }
        .res-multi-grid.skills-3 { grid-template-columns: repeat(3, 1fr); }

        .res-skill-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 22px 16px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            gap: 0;
            overflow: hidden;
            min-height: 0;
        }
        .res-skill-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-shrink: 0;
        }
        .res-skill-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #888;
        }
        .res-skill-band {
            font-size: 22px;
            font-weight: 900;
            color: #111;
        }
        .res-skill-band .band-unit {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            margin-left: 3px;
        }
        .res-skill-divider {
            height: 1px;
            background: #eee;
            margin: 0 0 14px;
            flex-shrink: 0;
        }
        .res-skill-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        /* Mini donut for multi-skill */
        .res-mini-lr-wrap {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-height: 0;
        }
        .res-mini-score-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 0;
            flex-shrink: 0;
        }
        .res-mini-dots-side {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .res-mini-donut-wrap { position: relative; flex-shrink: 0; }
        .res-mini-donut-wrap svg { display: block; }
        .res-mini-donut-score {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 900; color: #111;
            pointer-events: none;
        }
        .res-mini-legend { display: flex; flex-direction: column; gap: 8px; }
        .res-mini-legend-item {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #333;
        }
        .mini-legend-box {
            width: 22px; height: 22px; border-radius: 4px; flex-shrink: 0;
        }
        .mini-legend-box.correct   { background: #c0c0c0; }
        .mini-legend-box.incorrect { background: #111; border: 2px solid #2563eb; }

        /* Mini dots */
        .res-mini-dots-label {
            font-size: 10px; font-weight: 700; color: #999;
            letter-spacing: 1.1px; text-transform: uppercase; margin-bottom: 8px;
            flex-shrink: 0;
        }
        .res-mini-dots-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            margin-bottom: 10px;
            flex: 1;
            align-content: start;
            place-items: center;
        }
        .res-mini-dot {
            width: 26px; height: 26px; border-radius: 50%;
            background: #111; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; color: #fff;
        }
        .res-mini-dot.correct    { background: #c0c0c0; color: #555; }
        .res-mini-dot.unanswered { background: #e0e0e0; color: #999; }

        /* Writing / Speaking for multi-skill card */
        .res-multi-wr-list { display: flex; flex-direction: column; gap: 18px; flex: 1; justify-content: center; align-items: center; }
        .res-multi-wr-item {
            border: 1.5px solid #e0e0e0; border-radius: 10px; padding: 18px 32px;
            background: #fafafa; width: 78%;
        }
        .res-multi-wr-title { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 6px; }
        .res-multi-wr-status { font-size: 13px; color: #555; }
        .res-multi-wr-pending { font-style: italic; color: #999; }

        .res-multi-sp-overall {
            text-align: center; font-size: 32px; font-weight: 900; color: #111;
            margin-bottom: 14px; flex-shrink: 0;
        }
        .res-multi-sp-overall .sp-lbl { font-size: 14px; }
        .res-multi-sp-criteria { display: grid; grid-template-columns: auto auto; gap: 10px 24px; flex: 1; align-content: center; justify-content: center; }
        .res-multi-sp-row { display: flex; align-items: center; gap: 10px; }
        .res-multi-sp-badge {
            width: 48px; height: 48px; border: 1.5px solid #ccc; border-radius: 8px;
            background: #fafafa; display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 900; color: #111; flex-shrink: 0;
        }
        .res-multi-sp-name { font-size: 13px; color: #333; }

        /* Shared detail btn */
        .res-skill-footer { margin-top: 10px; text-align: center; flex-shrink: 0; }
        .res-skill-detail-btn {
            display: inline-block; padding: 8px 28px;
            border: 1.5px solid #555; border-radius: 50px;
            font-size: 13px; font-weight: 600; color: #333; text-decoration: none;
            transition: background .2s;
        }
        .res-skill-detail-btn:hover { background: #f5f5f5; color: #111; text-decoration: none; }

        @media (max-width: 900px) {
            .res-multi-grid { grid-template-columns: 1fr !important; }
            .res-multi-body { position: static; overflow-y: auto; }
        }
    </style>
</head>
<body>

@php
    /* User */
    $authUser    = auth()->user();
    $userName    = strtoupper($authUser->full_name ?? $authUser->name ?? 'TEST TAKER');
    $userAvatar  = $authUser->avatar ?? null;
    $userInitial = strtoupper(substr($authUser->full_name ?? $authUser->name ?? 'T', 0, 1));

    /* IELTS band conversion */
    $ieltsBandTable = [
        0=>0.0, 1=>1.0, 2=>1.0, 3=>2.0, 4=>2.5, 5=>2.5,
        6=>3.0, 7=>3.0, 8=>3.5, 9=>3.5, 10=>3.5,
        11=>4.0, 12=>4.0, 13=>4.5, 14=>4.5, 15=>4.5,
        16=>5.0, 17=>5.0, 18=>5.5, 19=>5.5, 20=>5.5, 21=>5.5, 22=>5.5,
        23=>6.0, 24=>6.0, 25=>6.0, 26=>6.0,
        27=>6.5, 28=>6.5, 29=>6.5,
        30=>7.0, 31=>7.0, 32=>7.0,
        33=>7.5, 34=>7.5,
        35=>8.0, 36=>8.0,
        37=>8.5, 38=>8.5,
        39=>9.0, 40=>9.0,
    ];

    /* Detect all skills actually completed in this attempt (most reliable) */
    $allSkills = [];
    if ($attempt->listening_completed) $allSkills[] = 'listening';
    if ($attempt->reading_completed)   $allSkills[] = 'reading';
    if ($attempt->writing_completed)   $allSkills[] = 'writing';
    if ($attempt->speaking_completed)  $allSkills[] = 'speaking';

    // Fallback: derive from test sections if no completed flags set
    if (empty($allSkills)) {
        if ($test->relationLoaded('sections') && $test->sections->isNotEmpty()) {
            $allSkills = $test->sections->pluck('skill')->filter()->unique()->values()->toArray();
        } else {
            $allSkills = \App\Models\IeltsTestSection::where('test_id', $test->id)
                ->whereNotNull('skill')->distinct()->pluck('skill')->values()->toArray();
        }
        if (empty($allSkills) && $attempt->answers->isNotEmpty()) {
            foreach ($attempt->answers as $_a) {
                if ($_a->question && $_a->question->section && $_a->question->section->skill) {
                    $allSkills[] = $_a->question->section->skill;
                }
            }
            $allSkills = array_values(array_unique($allSkills));
        }
    }
    $skillOrder = ['listening','reading','writing','speaking'];
    usort($allSkills, fn($a,$b) => (array_search($a,$skillOrder)??99) <=> (array_search($b,$skillOrder)??99));

    $isMultiSkill = count($allSkills) > 1;

    /* Detect current skill (for single-skill layout) */
    $skill = $attempt->current_skill ?? null;
    if (!$skill) {
        if ($attempt->speaking_completed)      $skill = 'speaking';
        elseif ($attempt->writing_completed)   $skill = 'writing';
        elseif ($attempt->reading_completed)   $skill = 'reading';
        elseif ($attempt->listening_completed) $skill = 'listening';
    }
    if (!$skill && $attempt->answers->isNotEmpty()) {
        foreach ($attempt->answers as $_ans) {
            if ($_ans->question && $_ans->question->section && $_ans->question->section->skill) {
                $skill = $_ans->question->section->skill;
                break;
            }
        }
    }
    if (!$skill || !in_array($skill, ['listening','reading','writing','speaking'])) {
        $skill = $allSkills[0] ?? 'listening';
    }

    /* Page title & back URL */
    $pageTitle = $isMultiSkill
        ? strtoupper($test->title ?? 'TEST') . ' - RESULTS'
        : strtoupper($skill) . ' - ' . strtoupper($test->title ?? 'TEST');
    $backUrl = ($test->type === 'mock')
        ? route('panel.ielts_tests.mock')
        : route('panel.ielts_tests.practice');

    /* Helper: build per-skill data */
    if (!function_exists('buildSkillData')) {
    function buildSkillData(string $skl, $attempt, array $bt): array {
        $d = ['skill' => $skl];
        if ($skl === 'writing') {
            $d['is_writing'] = true;
            $d['graded'] = !empty($attempt->writing_graded_at);
            $d['sections'] = \App\Models\IeltsTestSection::where('test_id', $attempt->test_id)
                ->where('skill','writing')->orderBy('section_number')->get();
            return $d;
        }
        if ($skl === 'speaking') {
            $d['is_speaking'] = true;
            $d['graded'] = !empty($attempt->speaking_graded_at);
            $d['band']   = $attempt->speaking_band;
            $raw = $attempt->speaking_criteria;
            $d['criteria'] = $raw ? (is_array($raw) ? $raw : json_decode($raw, true)) : null;
            return $d;
        }
        $answers = $attempt->answers->filter(fn($a) =>
            $a->question && $a->question->section && $a->question->section->skill === $skl
        );
        $answerMap = [];
        foreach ($answers as $ans) {
            $n = $ans->question->question_number ?? null;
            if ($n !== null) $answerMap[$n] = $ans->is_correct ? 'correct' : 'incorrect';
        }
        // Use actual total questions from test (not just answered ones)
        $questionsForSkill = \App\Models\IeltsTestQuestion::whereHas('section', fn($q) =>
            $q->where('test_id', $attempt->test_id)->where('skill', $skl)
        )->get();
        // For questions like table_completion, count individual cell answers as separate items
        $actualTotal = 0;
        foreach ($questionsForSkill as $qItem) {
            if (($qItem->question_type ?? '') === 'table_completion') {
                $actualTotal += count($qItem->table_completion_answers_array ?? []);
            } else {
                $actualTotal += 1;
            }
        }
        $rawScore      = (int)($skl === 'reading' ? ($attempt->reading_score??0) : ($attempt->listening_score??0));
        $answeredCount = $answers->count();
        $total         = max(1, $actualTotal ?: $answeredCount);
        $correct       = min($rawScore, $total);
        $incorrect     = max(0, $answeredCount - $correct); // only actually wrong answers
        $band       = $bt[min($correct,40)] ?? 0.0;
        $circum     = round(2*M_PI*70, 2);
        $cArc       = $total > 0 ? round(($correct/$total)*$circum,2) : 0;
        $iArc       = $total > 0 ? round(($incorrect/$total)*$circum,2) : $circum;
        return $d + compact('answerMap','rawScore','total','correct','incorrect','band','circum') + ['correctArc'=>$cArc,'incorrectArc'=>$iArc];
    }
    } // end function_exists buildSkillData

    /* Build data for all skills */
    $skillsData = [];
    foreach ($allSkills as $_s) $skillsData[$_s] = buildSkillData($_s, $attempt, $ieltsBandTable);

    /* Single-skill convenience vars */
    $isWriting  = ($skill === 'writing');
    $isSpeaking = ($skill === 'speaking');
    if (!$isMultiSkill) {
        $sd = $skillsData[$skill] ?? buildSkillData($skill, $attempt, $ieltsBandTable);
        if ($isWriting) {
            $writingSections = $sd['sections'];
        } elseif ($isSpeaking) {
            $speakingBand     = $sd['band'];
            $speakingCriteria = $sd['criteria'];
            $isSpGraded       = $sd['graded'];
        } else {
            extract(array_intersect_key($sd, array_flip(['answerMap','rawScore','total','correct','incorrect','band','circum','correctArc','incorrectArc'])));
            $totalQuestions = $sd['total'];
            $correctCount   = $sd['correct'];
            $incorrectCount = $sd['incorrect'];
            $bandScore      = $sd['band'];
        }
    }

    /* Speaking criteria keys */
    $spCriteriaKeys = [
        ['key'=>['fluency','fluency_and_coherence','fc'],                                                  'label'=>'Fluency &amp; Coherence'],
        ['key'=>['lexical','lexical_resource','vocabulary','lr'],                                           'label'=>'Lexical Resource'],
        ['key'=>['grammar','grammatical_range','grammar_and_accuracy','gra','grammatical_range_and_accuracy'],'label'=>'Grammar Range &amp; Accuracy'],
        ['key'=>['pronunciation','p'],                                                                      'label'=>'Pronunciation'],
    ];
    if (!function_exists('spScore')) {
    function spScore(array $keys, ?array $criteria): ?float {
        if (!$criteria) return null;
        foreach ($keys as $k) { if (isset($criteria[$k])) return (float)$criteria[$k]; }
        $lower = array_change_key_case($criteria, CASE_LOWER);
        foreach ($keys as $k) { if (isset($lower[strtolower($k)])) return (float)$lower[strtolower($k)]; }
        return null;
    }
    } // end function_exists spScore

    /* Motivational quotes (single-skill only) */
    $quotes = [
        ['text'=>'Every expert was once a beginner. Keep going - your score will grow with every practice.',              'author'=>'Unknown'],
        ['text'=>'The secret of getting ahead is getting started. Each attempt brings you closer to your goal.',         'author'=>'Mark Twain'],
        ['text'=>'Success is not final, failure is not fatal: it is the courage to continue that counts.',               'author'=>'Winston Churchill'],
        ['text'=>'It does not matter how slowly you go as long as you do not stop.',                                     'author'=>'Confucius'],
        ['text'=>"Believe you can and you're halfway there. Your next attempt will be even better.",                     'author'=>'Theodore Roosevelt'],
        ['text'=>'Great things are not done by impulse, but by a series of small things brought together.',              'author'=>'Vincent Van Gogh'],
        ['text'=>'Hardships often prepare ordinary people for an extraordinary destiny. Stay consistent.',               'author'=>'C.S. Lewis'],
        ['text'=>"The more that you read, the more things you will know. The more you learn, the more places you'll go.",'author'=>'Dr. Seuss'],
        ['text'=>'Practice is the hardest part of learning, and training is the essence of transformation.',             'author'=>'Ann Voskamp'],
        ['text'=>'Education is not the filling of a bucket, but the lighting of a fire.',                                'author'=>'W.B. Yeats'],
    ];
    $quote = $quotes[$attempt->id % count($quotes)];
@endphp

{{-- HEADER --}}
<header class="res-header">
    <div class="res-header-user">
        <div class="res-avatar">
            @if($userAvatar)
                <img src="{{ $userAvatar }}" alt="{{ $userName }}">
            @else
                {{ $userInitial }}
            @endif
        </div>
        <span class="res-username">{{ $userName }}</span>
    </div>
    <span class="res-header-title">{{ $pageTitle }}</span>
    <a href="{{ $backUrl }}" class="res-back-btn">Back</a>
</header>

@if($isMultiSkill)
{{-- MULTI-SKILL GRID LAYOUT --}}
<div class="res-multi-body">
    @php $skillCount = count($allSkills); @endphp
    <div class="res-multi-grid skills-{{ $skillCount }}">

        @foreach($skillsData as $skl => $sd)
        @php
            $skillLabel = strtoupper($skl);
            $reviewUrl  = route('panel.ielts_tests.review', $attempt->id) . '?skill=' . $skl;
        @endphp
        <div class="res-skill-card">
            {{-- Card header: skill label + band/status --}}
            <div class="res-skill-card-header">
                <span class="res-skill-label">{{ $skillLabel }}</span>
                @if(!empty($sd['is_writing']))
                    <span class="res-skill-band">
                        @if($sd['graded']) <span style="font-size:13px;color:#555;">Graded</span>
                        @else <span style="font-size:12px;color:#bbb;font-style:italic;">Pending</span>
                        @endif
                    </span>
                @elseif(!empty($sd['is_speaking']))
                    <span class="res-skill-band">
                        @if($sd['graded'] && $sd['band'] !== null)
                            {{ number_format((float)$sd['band'],1) }}<span class="band-unit">band</span>
                        @else <span style="font-size:12px;color:#bbb;font-style:italic;">Pending</span>
                        @endif
                    </span>
                @else
                    <span class="res-skill-band">{{ number_format((float)$sd['band'],1) }}<span class="band-unit">band</span></span>
                @endif
            </div>
            <div class="res-skill-divider"></div>

            {{-- Card content --}}
            <div class="res-skill-content">

                @if(!empty($sd['is_writing']))
                    {{-- Writing --}}
                    <div class="res-multi-wr-list">
                        @forelse($sd['sections'] as $wsec)
                        @php $pNum = $wsec->section_number ?? ($loop->index + 1); @endphp
                        <div class="res-multi-wr-item">
                            <div class="res-multi-wr-title">Task {{ $pNum }}</div>
                            @if($sd['graded'])
                                <div class="res-multi-wr-status">Evaluated &mdash; click View Details</div>
                            @else
                                <div class="res-multi-wr-status res-multi-wr-pending">Awaiting evaluation</div>
                            @endif
                        </div>
                        @empty
                        <div class="res-multi-wr-item">
                            <div class="res-multi-wr-title">Task 1 &amp; Task 2</div>
                            @if($sd['graded'])
                                <div class="res-multi-wr-status">Evaluated &mdash; click View Details</div>
                            @else
                                <div class="res-multi-wr-status res-multi-wr-pending">Awaiting evaluation</div>
                            @endif
                        </div>
                        @endforelse
                    </div>

                @elseif(!empty($sd['is_speaking']))
                    {{-- Speaking --}}
                    @if($sd['graded'] && $sd['band'] !== null)
                        <div class="res-multi-sp-overall">{{ number_format((float)$sd['band'],1) }} <span class="sp-lbl">OVERALL</span></div>
                    @else
                        <div class="res-multi-sp-overall" style="color:#bbb">&mdash; <span class="sp-lbl">OVERALL</span></div>
                    @endif
                    <div class="res-multi-sp-criteria">
                        @foreach($spCriteriaKeys as $crit)
                        @php $sc = $sd['graded'] ? spScore($crit['key'], $sd['criteria']) : null; @endphp
                        <div class="res-multi-sp-row">
                            <div class="res-multi-sp-badge {{ $sc===null ? 'empty' : '' }}" style="{{ $sc===null ? 'color:transparent' : '' }}">
                                {{ $sc !== null ? number_format($sc,1) : '0' }}
                            </div>
                            <span class="res-multi-sp-name">{!! $crit['label'] !!}</span>
                        </div>
                        @endforeach
                    </div>

                @else
                    {{-- Listening / Reading --}}
                    <div class="res-mini-lr-wrap">
                        <div class="res-mini-score-row">
                            <div class="res-mini-donut-wrap">
                                @php
                                    $mc  = round(2*M_PI*50, 2); // r=50
                                    $cA  = $sd['total']>0 ? round(($sd['correct']/$sd['total'])*$mc,2) : 0;
                                    $iA  = $sd['total']>0 ? round(($sd['incorrect']/$sd['total'])*$mc,2) : $mc;
                                @endphp
                                <svg width="120" height="120" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e5e5" stroke-width="16"/>
                                    @if($sd['incorrect']>0)
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#111" stroke-width="16"
                                            stroke-dasharray="{{ $iA }} {{ $mc }}" stroke-dashoffset="0"
                                            stroke-linecap="butt" transform="rotate(-90 60 60)"/>
                                    @endif
                                    @if($sd['correct']>0)
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#c0c0c0" stroke-width="16"
                                            stroke-dasharray="{{ $cA }} {{ $mc }}"
                                            stroke-dashoffset="{{ round(-$iA,2) }}"
                                            stroke-linecap="butt" transform="rotate(-90 60 60)"/>
                                    @endif
                                </svg>
                                <div class="res-mini-donut-score">{{ number_format((float)$sd['band'],1) }}</div>
                            </div>
                            <div class="res-mini-legend">
                                <div class="res-mini-legend-item">
                                    <div class="mini-legend-box correct"></div>
                                    <span>Correct: <strong>{{ $sd['correct'] }}/{{ $sd['total'] }}</strong></span>
                                </div>
                                <div class="res-mini-legend-item">
                                    <div class="mini-legend-box incorrect"></div>
                                    <span>Wrong: <strong>{{ $sd['incorrect'] }}/{{ $sd['total'] }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="res-mini-dots-side">
                            <div class="res-mini-dots-label">Questions overview</div>
                            <div class="res-mini-dots-grid">
                                @for($qi=1; $qi<=$sd['total']; $qi++)
                                    @php $ds = $sd['answerMap'][$qi] ?? 'unanswered'; @endphp
                                    <div class="res-mini-dot {{ $ds }}" title="Q{{ $qi }}: {{ $ds }}">{{ $qi }}</div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endif

            </div>{{-- /.res-skill-content --}}

            <div class="res-skill-footer">
                <a href="{{ $reviewUrl }}" class="res-skill-detail-btn">View Details</a>
            </div>
        </div>{{-- /.res-skill-card --}}
        @endforeach

    </div>{{-- /.res-multi-grid --}}
</div>{{-- /.res-multi-body --}}

@else
{{-- SINGLE-SKILL LAYOUT (original) --}}
<div class="res-body">

@if($isWriting)
    {{-- WRITING LEFT CARD --}}
    <div class="res-left">
        <div class="wr-tasks-list">
            @forelse($writingSections as $section)
            @php $partNum = $section->section_number ?? ($loop->index + 1); @endphp
            <div class="wr-task-card">
                <div class="wr-task-card-title">IELTS Band score and Suggestions &mdash; Task {{ $partNum }}</div>
                @if($attempt->writing_graded_at)
                    <div class="wr-task-card-status">Your writing has been evaluated. Please click &ldquo;View detail&rdquo; to see more.</div>
                @else
                    <div class="wr-task-card-status wr-task-pending">Your writing is awaiting evaluation. Results will appear here once graded.</div>
                @endif
            </div>
            @empty
            <div class="wr-task-card">
                <div class="wr-task-card-title">IELTS Band score and Suggestions &mdash; Task 1</div>
                @if($attempt->writing_graded_at)
                    <div class="wr-task-card-status">Your writing has been evaluated. Please click &ldquo;View detail&rdquo; to see more.</div>
                @else
                    <div class="wr-task-card-status wr-task-pending">Your writing is awaiting evaluation. Results will appear here once graded.</div>
                @endif
            </div>
            @endforelse
        </div>
        <div class="res-detail-btn-wrap" style="margin-top: 24px;">
            <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="res-detail-btn">View details</a>
        </div>
    </div>

@elseif($isSpeaking)
    {{-- SPEAKING LEFT CARD --}}
    @php
        $spCritFull = [
            ['key'=>['fluency','fluency_and_coherence','fc'],                                                   'label'=>'Fluency and Coherence'],
            ['key'=>['lexical','lexical_resource','vocabulary','lr'],                                            'label'=>'Lexical Resource'],
            ['key'=>['grammar','grammatical_range','grammar_and_accuracy','gra','grammatical_range_and_accuracy'],'label'=>'Grammatical Range &amp; Accuracy'],
            ['key'=>['pronunciation','p'],                                                                       'label'=>'Pronunciation'],
        ];
    @endphp
    <div class="res-left">
        <div class="sp-left-inner">
            <div class="sp-overall">
                @if($isSpGraded && $speakingBand !== null)
                    {{ number_format((float)$speakingBand, 1) }} <span class="sp-overall-label">OVERALL</span>
                @else
                    &mdash; <span class="sp-overall-label">OVERALL</span>
                @endif
            </div>
            <div class="sp-criteria-list">
                @foreach($spCritFull as $crit)
                @php $score = $isSpGraded ? spScore($crit['key'], $speakingCriteria) : null; @endphp
                <div class="sp-criterion-row">
                    <div class="sp-score-badge {{ $score===null ? 'empty' : '' }}">
                        {{ $score !== null ? number_format($score, 1) : '' }}
                    </div>
                    <span class="sp-criterion-name">{!! $crit['label'] !!}</span>
                </div>
                @endforeach
            </div>
            <div class="res-detail-btn-wrap" style="margin-top: 36px;">
                <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="res-detail-btn">View details</a>
            </div>
        </div>
    </div>

@else
    {{-- LISTENING / READING LEFT CARD --}}
    <div class="res-left">
        <div class="res-score-row">
            <div class="res-donut-wrap">
                <svg width="180" height="180" viewBox="0 0 180 180" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="90" cy="90" r="70" fill="none" stroke="#e5e5e5" stroke-width="22"/>
                    @if($incorrectCount > 0)
                    <circle cx="90" cy="90" r="70" fill="none" stroke="#111" stroke-width="22"
                            stroke-dasharray="{{ $incorrectArc }} {{ $circum }}" stroke-dashoffset="0"
                            stroke-linecap="butt" transform="rotate(-90 90 90)"/>
                    @endif
                    @if($correctCount > 0)
                    <circle cx="90" cy="90" r="70" fill="none" stroke="#c0c0c0" stroke-width="22"
                            stroke-dasharray="{{ $correctArc }} {{ $circum }}"
                            stroke-dashoffset="{{ round(-$incorrectArc, 2) }}"
                            stroke-linecap="butt" transform="rotate(-90 90 90)"/>
                    @endif
                </svg>
                <div class="res-donut-score">{{ number_format($bandScore, 1) }}</div>
            </div>
            <div class="res-legend">
                <div class="res-legend-item">
                    <div class="legend-box correct"></div>
                    <span>Correct: <strong>{{ $correctCount }}/{{ $totalQuestions }}</strong></span>
                </div>
                <div class="res-legend-item">
                    <div class="legend-box incorrect"></div>
                    <span>Incorrect: <strong>{{ $incorrectCount }}/{{ $totalQuestions }}</strong></span>
                </div>
            </div>
        </div>
        <div class="res-dots-label">Questions overview</div>
        @php $dotCols = $totalQuestions <= 10 ? $totalQuestions : ($totalQuestions <= 20 ? 10 : 8); @endphp
        <div class="res-dots-grid" style="grid-template-columns: repeat({{ $dotCols }}, 1fr)">
            @for($i = 1; $i <= $totalQuestions; $i++)
                @php $dotState = $answerMap[$i] ?? 'unanswered'; @endphp
                <div class="res-dot {{ $dotState }}" title="Q{{ $i }}: {{ $dotState }}">{{ $i }}</div>
            @endfor
        </div>
        <div class="res-detail-btn-wrap">
            <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="res-detail-btn">View details</a>
        </div>
    </div>
@endif

    {{-- RIGHT CARD: illustration + quote --}}
    <div class="res-right">
        <div class="res-illustration">
            <svg viewBox="0 0 340 260" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <ellipse cx="170" cy="238" rx="125" ry="12" fill="#d0d0d0"/>
                <rect x="152" y="148" width="38" height="58" rx="8" fill="#4a4a8a"/>
                <rect x="154" y="202" width="13" height="36" rx="5" fill="#333268"/>
                <rect x="173" y="202" width="13" height="36" rx="5" fill="#333268"/>
                <ellipse cx="160" cy="238" rx="10" ry="5" fill="#222"/>
                <ellipse cx="180" cy="238" rx="10" ry="5" fill="#222"/>
                <rect x="104" y="150" width="48" height="30" rx="4" fill="#fff" stroke="#ccc" stroke-width="1.5"/>
                <line x1="128" y1="150" x2="128" y2="180" stroke="#ccc" stroke-width="1.2"/>
                <rect x="132" y="148" width="22" height="10" rx="5" fill="#4a4a8a"/>
                <rect x="190" y="132" width="11" height="36" rx="5" fill="#4a4a8a" transform="rotate(-22 195 150)"/>
                <polygon points="220,98 223,109 234,109 225,115 228,126 220,120 212,126 215,115 206,109 217,109" fill="#f5c518" stroke="#e0a800" stroke-width="1"/>
                <circle cx="171" cy="130" r="21" fill="#f5cba7"/>
                <ellipse cx="171" cy="113" rx="21" ry="11" fill="#3d2200"/>
                <circle cx="164" cy="129" r="2.5" fill="#222"/>
                <circle cx="178" cy="129" r="2.5" fill="#222"/>
                <path d="M164,136 Q171,143 178,136" stroke="#a05020" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                <rect x="154" y="111" width="34" height="5" rx="2" fill="#222"/>
                <polygon points="171,101 193,114 149,114" fill="#222"/>
                <line x1="193" y1="114" x2="195" y2="123" stroke="#f5c518" stroke-width="2"/>
                <circle cx="195" cy="125" r="3.5" fill="#f5c518"/>
                <rect x="44" y="172" width="32" height="6" rx="2" fill="#c09040"/>
                <rect x="54" y="178" width="12" height="22" rx="2" fill="#c09040"/>
                <rect x="46" y="199" width="28" height="6" rx="2" fill="#c09040"/>
                <ellipse cx="60" cy="157" rx="20" ry="22" fill="#f5c518"/>
                <circle cx="250" cy="88" r="5" fill="#e74c3c"/>
                <circle cx="263" cy="72" r="4" fill="#3498db"/>
                <circle cx="280" cy="95" r="3.5" fill="#2ecc71"/>
                @if(!$isWriting && !$isSpeaking)
                <rect x="234" y="130" width="82" height="50" rx="10" fill="#4a4a8a"/>
                <polygon points="264,180 278,180 271,194" fill="#4a4a8a"/>
                <text x="275" y="152" text-anchor="middle" font-size="10" fill="#aabbff" font-weight="bold" letter-spacing="1">IELTS BAND</text>
                <text x="275" y="174" text-anchor="middle" font-size="24" fill="#fff" font-weight="900">{{ number_format($bandScore, 1) }}</text>
                @endif
            </svg>
        </div>
        <div class="res-quote">
            "{{ $quote['text'] }}"
            <span class="res-quote-author">&mdash; {{ $quote['author'] }}</span>
        </div>
    </div>

</div>{{-- /.res-body --}}
@endif

</body>
</html>
