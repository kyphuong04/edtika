{{-- 
    IDP IELTS CBT Interface - 100% Exact Replica
    Supports all question types from official IDP interface
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IELTS {{ ucfirst($currentSection->skill ?? 'Test') }}</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            color: #000;
            background: #fff;
            line-height: 1.5;
            overflow: hidden;
            height: 100vh;
        }
        
        /* ========== HEADER ========== */
        .idp-header {
            height: 40px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .idp-header-left { display: flex; align-items: center; gap: 16px; }
        .idp-logo { font-size: 22px; font-weight: bold; color: #E31837; font-style: italic; font-family: serif; }
        .idp-test-taker { color: #333; font-size: 13px; }
        .idp-audio-playing { color: #333; font-size: 12px; display: flex; align-items: center; gap: 4px; }
        .idp-audio-playing::before { content: "🔊"; }
        .idp-header-right { display: flex; align-items: center; gap: 12px; }
        .idp-header-icon { color: #666; font-size: 18px; cursor: pointer; }
        
        /* ========== PART BAR ========== */
        .idp-part-bar {
            height: 50px;
            background: #CC6600;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 40px; left: 0; right: 0;
            z-index: 999;
        }
        .idp-part-title { color: #fff; font-size: 15px; font-weight: bold; }
        .idp-part-instruction { color: #fff; font-size: 13px; margin-left: 8px; }
        .idp-part-instruction a { color: #87CEEB; }
        
        /* ========== MAIN LAYOUT ========== */
        .idp-main {
            display: flex;
            position: fixed;
            top: 90px; left: 0; right: 0; bottom: 44px;
        }
        
        /* Left Panel */
        .idp-left {
            flex: 1;
            background: #fff;
            overflow-y: auto;
            padding: 20px 28px;
            border-right: 1px solid #ccc;
        }
        .idp-passage-title { font-size: 17px; font-weight: bold; margin-bottom: 12px; }
        .idp-passage-note { font-size: 13px; font-style: italic; color: #666; margin-bottom: 12px; }
        .idp-passage-text { font-size: 15px; line-height: 1.7; text-align: justify; }
        .idp-passage-text p { margin-bottom: 14px; }
        
        /* Divider */
        .idp-divider {
            width: 18px;
            background: #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
        .idp-divider-icon { color: #888; font-size: 12px; }
        
        /* Right Panel */
        .idp-right {
            flex: 1;
            background: #f5f5f0;
            overflow-y: auto;
            padding: 20px 28px;
        }
        
        /* ========== QUESTION STYLING ========== */
        .idp-questions-header {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .idp-questions-instruction {
            font-size: 14px;
            margin-bottom: 16px;
            line-height: 1.5;
        }
        .idp-help-link {
            float: right;
            color: #0066CC;
            font-size: 13px;
            text-decoration: none;
        }
        .idp-help-link:hover { text-decoration: underline; }
        
        /* Question Number Badge */
        .idp-q-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 20px;
            padding: 0 5px;
            border: 1px solid #CC6600;
            color: #CC6600;
            font-size: 12px;
            font-weight: bold;
            background: #fff;
            margin-right: 6px;
        }
        
        /* Question Text - Blue color like IDP */
        .idp-q-text {
            color: #0066CC;
            font-size: 14px;
        }
        
        /* Radio/Checkbox Options */
        .idp-options { margin-top: 8px; margin-left: 28px; }
        .idp-option {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 6px;
            cursor: pointer;
        }
        .idp-option input[type="radio"],
        .idp-option input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            cursor: pointer;
        }
        .idp-option label {
            font-size: 14px;
            color: #000;
            cursor: pointer;
        }
        
        /* Input Box - Dashed blue border */
        .idp-input {
            display: inline-block;
            min-width: 80px;
            height: 24px;
            border: 1px dashed #0066CC;
            padding: 2px 8px;
            font-size: 14px;
            text-align: center;
            background: #fff;
        }
        .idp-input:focus {
            outline: none;
            border: 2px solid #0066CC;
            border-style: solid;
        }
        
        /* Larger input for sentence completion */
        .idp-input-lg {
            min-width: 120px;
            height: 26px;
        }
        
        /* Question Item Container */
        .idp-question {
            margin-bottom: 14px;
            line-height: 1.8;
        }
        
        /* Word Bank */
        .idp-word-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }
        .idp-word {
            padding: 4px 10px;
            border: 1px solid #999;
            background: #fff;
            font-size: 13px;
            cursor: grab;
        }
        .idp-word:hover { background: #f0f0f0; }
        .idp-word.used { opacity: 0.4; text-decoration: line-through; }
        
        /* Matching Table */
        .idp-match-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 12px;
        }
        .idp-match-table th {
            background: #fff;
            padding: 8px 10px;
            text-align: center;
            border: 1px solid #ccc;
            font-weight: bold;
        }
        .idp-match-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            background: #fff;
        }
        .idp-match-table td:first-child { text-align: left; }
        .idp-match-table td:not(:first-child) { text-align: center; width: 40px; }
        .idp-match-table input[type="radio"] { width: 16px; height: 16px; }
        
        /* Note/Form Completion */
        .idp-note-title { font-weight: bold; font-size: 15px; margin-bottom: 10px; }
        .idp-note-section { margin-bottom: 16px; }
        .idp-note-subtitle { font-weight: bold; font-size: 14px; margin-bottom: 6px; }
        .idp-note-list { list-style: disc; padding-left: 20px; }
        .idp-note-list li { margin-bottom: 6px; font-size: 14px; line-height: 1.8; }
        .idp-note-bullet { list-style: none; padding-left: 0; }
        .idp-note-bullet li::before { content: "•"; margin-right: 8px; }
        .idp-note-dash { list-style: none; padding-left: 20px; }
        .idp-note-dash li::before { content: "-"; margin-right: 8px; }
        
        /* Flow Chart */
        .idp-flowchart { padding: 10px 0; }
        .idp-flow-box {
            background: #f5f5f5;
            border: 1px solid #ccc;
            padding: 10px 14px;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.6;
        }
        .idp-flow-box.highlight { background: #fff; border-color: #0066CC; }
        .idp-flow-arrow { text-align: center; font-size: 16px; color: #666; margin: 2px 0; }
        
        /* Table Completion */
        .idp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .idp-table th {
            background: #e8e8e8;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #ccc;
            font-weight: bold;
        }
        .idp-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            background: #fff;
        }
        
        /* Map/Diagram - Two column layout */
        .idp-map-layout { display: flex; gap: 20px; }
        .idp-map-image { flex: 1; }
        .idp-map-image img { max-width: 100%; border: 1px solid #ccc; }
        .idp-map-questions { flex: 1; }
        
        /* Group Separator */
        .idp-group-separator {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        
        /* ========== WRITING SECTION ========== */
        .idp-writing-layout { display: flex; height: 100%; }
        .idp-writing-left {
            flex: 1;
            padding: 20px 28px;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid #ccc;
        }
        .idp-writing-right {
            flex: 1;
            padding: 20px;
            background: #f5f5f0;
            display: flex;
            flex-direction: column;
        }
        .idp-textarea {
            flex: 1;
            width: 100%;
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 15px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            resize: none;
        }
        .idp-textarea:focus { outline: 2px solid #0066CC; }
        .idp-word-count { text-align: right; padding: 6px 0; font-size: 13px; color: #666; }
        
        /* ========== LISTENING OVERLAY ========== */
        .idp-audio-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.97);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .idp-audio-overlay.hidden { display: none; }
        .idp-audio-icon { font-size: 60px; color: #555; margin-bottom: 20px; }
        .idp-audio-msg { font-size: 15px; text-align: center; max-width: 450px; margin-bottom: 20px; line-height: 1.5; }
        .idp-play-btn {
            padding: 12px 32px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .idp-play-btn:hover { background: #333; }
        
        /* ========== FOOTER ========== */
        .idp-footer {
            height: 44px;
            background: #fff;
            border-top: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .idp-parts-nav { display: flex; align-items: center; gap: 16px; }
        .idp-part-group { display: flex; align-items: center; gap: 6px; }
        .idp-part-label { font-size: 13px; color: #000; }
        .idp-part-label.active { font-weight: bold; }
        .idp-q-nums { display: flex; gap: 2px; }
        .idp-q-btn {
            min-width: 20px;
            height: 20px;
            font-size: 11px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .idp-q-btn:hover { background: #eee; }
        .idp-q-btn.current { border: 2px solid #CC6600; font-weight: bold; }
        .idp-q-btn.answered { background: #d4edff; }
        .idp-progress { font-size: 11px; color: #888; }
        
        .idp-nav-btns { display: flex; align-items: center; gap: 8px; }
        .idp-nav-arrow {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: none;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .idp-nav-arrow.prev { background: #888; color: #fff; }
        .idp-nav-arrow.next { background: #333; color: #fff; }
        .idp-nav-arrow:hover { opacity: 0.9; }
        .idp-submit-btn {
            width: 30px; height: 30px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Scrollbar */
        .idp-left::-webkit-scrollbar, .idp-right::-webkit-scrollbar { width: 8px; }
        .idp-left::-webkit-scrollbar-thumb, .idp-right::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        
        /* Modal */
        .idp-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 3000; }
        .idp-modal.hidden { display: none; }
        .idp-modal-box { background: #fff; padding: 28px 36px; border-radius: 8px; text-align: center; max-width: 400px; }
        .idp-modal-title { font-size: 17px; font-weight: bold; margin-bottom: 12px; }
        .idp-modal-text { font-size: 14px; margin-bottom: 20px; }
        .idp-modal-btns { display: flex; gap: 10px; justify-content: center; }
        .idp-modal-btn { padding: 8px 24px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer; }
        .idp-modal-btn.cancel { background: #ddd; }
        .idp-modal-btn.confirm { background: #CC6600; color: #fff; }
    </style>
</head>
<body>
    @php
        $skill = $currentSection->skill ?? 'reading';
        $partNum = $currentSection->part_number ?? 1;
        $allQuestions = $questions ?? collect([]);
        $firstQ = $allQuestions->first();
        $lastQ = $allQuestions->last();
        $qStart = $firstQ->order_number ?? 1;
        $qEnd = $lastQ->order_number ?? ($qStart + $allQuestions->count() - 1);
        $groupedQuestions = $allQuestions->groupBy('question_group_id');
    @endphp

    {{-- HEADER --}}
    <header class="idp-header">
        <div class="idp-header-left">
            <span class="idp-logo">IELTS</span>
            <span class="idp-test-taker">Test taker ID</span>
            @if($skill === 'listening')
                <span class="idp-audio-playing">Audio is playing</span>
            @endif
        </div>
        <div class="idp-header-right">
            <span class="idp-header-icon">📶</span>
            <span class="idp-header-icon">🔔</span>
            <span class="idp-header-icon">☰</span>
        </div>
    </header>

    {{-- PART BAR --}}
    <div class="idp-part-bar">
        <span class="idp-part-title">Part {{ $partNum }}</span>
        <span class="idp-part-instruction">
            @if($skill === 'reading')
                Read the text and answer questions <a href="#">{{ $qStart }}-{{ $qEnd }}</a>.
            @elseif($skill === 'listening')
                Listen and answer questions {{ $qStart }}-{{ $qEnd }}.
            @elseif($skill === 'writing')
                @if($partNum == 1)
                    You should spend about 20 minutes on this task. Write at least 150 words.
                @else
                    You should spend about 40 minutes on this task. Write at least 250 words.
                @endif
            @endif
        </span>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="idp-main">
        @if($skill === 'writing')
            {{-- WRITING LAYOUT --}}
            @include('design_1.panel.ielts_tests.partials.idp_writing', [
                'section' => $currentSection,
                'question' => $firstQ,
                'userAnswer' => $userAnswers[$firstQ->id ?? 0] ?? ''
            ])
        @elseif($skill === 'listening' && empty($currentSection->passage_text))
            {{-- LISTENING FULL WIDTH (no passage) --}}
            <div class="idp-right" style="flex: none; width: 100%;">
                @include('design_1.panel.ielts_tests.partials.idp_questions_panel', [
                    'groupedQuestions' => $groupedQuestions,
                    'userAnswers' => $userAnswers,
                    'skill' => $skill
                ])
            </div>
        @else
            {{-- READING / LISTENING WITH PASSAGE --}}
            <div class="idp-left" id="leftPanel">
                @if(!empty($currentSection->passage_title))
                    <h2 class="idp-passage-title">{{ $currentSection->passage_title }}</h2>
                @endif
                @if(!empty($currentSection->subtitle))
                    <p class="idp-passage-note">{{ $currentSection->subtitle }}</p>
                @endif
                <div class="idp-passage-text">
                    {!! $currentSection->passage_text ?? $currentSection->content ?? '' !!}
                </div>
            </div>
            
            <div class="idp-divider" id="divider"><span class="idp-divider-icon">↔</span></div>
            
            <div class="idp-right" id="rightPanel">
                @include('design_1.panel.ielts_tests.partials.idp_questions_panel', [
                    'groupedQuestions' => $groupedQuestions,
                    'userAnswers' => $userAnswers,
                    'skill' => $skill
                ])
            </div>
        @endif
    </main>

    {{-- FOOTER --}}
    @php
        $sections = $test->sections()->where('skill', $skill)->orderBy('sort_order')->get();
        $partsData = [];
        foreach($sections as $idx => $sec) {
            $secQs = $sec->questions()->orderBy('order_number')->pluck('order_number', 'id')->toArray();
            $answered = 0;
            foreach($secQs as $qid => $qnum) {
                if(!empty($userAnswers[$qid] ?? null)) $answered++;
            }
            $partsData[$sec->part_number ?? ($idx+1)] = [
                'section_id' => $sec->id,
                'questions' => array_values($secQs),
                'answered' => $answered,
                'total' => count($secQs)
            ];
        }
    @endphp
    
    <footer class="idp-footer">
        <div class="idp-parts-nav">
            @foreach($partsData as $pNum => $pInfo)
                <div class="idp-part-group">
                    <span class="idp-part-label {{ $pNum == $partNum ? 'active' : '' }}">Part {{ $pNum }}</span>
                    <div class="idp-q-nums">
                        @foreach($pInfo['questions'] as $qn)
                            <span class="idp-q-btn {{ $qn == $qStart ? 'current' : '' }} {{ isset($userAnswers[$qn]) ? 'answered' : '' }}" onclick="goTo({{ $qn }})">{{ $qn }}</span>
                        @endforeach
                    </div>
                    <span class="idp-progress">{{ $pInfo['answered'] }} of {{ $pInfo['total'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="idp-nav-btns">
            <button class="idp-nav-arrow prev" onclick="prevQ()">←</button>
            <button class="idp-nav-arrow next" onclick="nextQ()">→</button>
            <button class="idp-submit-btn" onclick="showModal()">✓</button>
        </div>
    </footer>

    {{-- LISTENING AUDIO OVERLAY --}}
    @if($skill === 'listening')
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-audio-icon">🎧</div>
            <p class="idp-audio-msg">You will be listening to an audio clip during this test. You will not be permitted to pause or rewind the audio while answering the questions.<br><br>To continue, click Play.</p>
            <button class="idp-play-btn" onclick="playAudio()">▶ Play</button>
        </div>
        <audio id="audioPlayer" src="{{ $currentSection->audio_url ?? '' }}"></audio>
    @endif

    {{-- SUBMIT MODAL --}}
    <div class="idp-modal hidden" id="submitModal">
        <div class="idp-modal-box">
            <div class="idp-modal-title">Submit Section?</div>
            <p class="idp-modal-text">Are you sure you want to submit? You cannot change your answers after submission.</p>
            <div class="idp-modal-btns">
                <button class="idp-modal-btn cancel" onclick="hideModal()">Cancel</button>
                <button class="idp-modal-btn confirm" onclick="submitSection()">Submit</button>
            </div>
        </div>
    </div>

    <script>
        const attemptId = {{ $attempt->id }};
        const csrf = '{{ csrf_token() }}';
        const saveUrl = '{{ route("panel.ielts_tests.save_answer", $attempt->id) }}';
        const submitUrl = '{{ route("panel.ielts_tests.finish_section", $attempt->id) }}';
        
        // Save answer
        function saveAnswer(qId, value) {
            fetch(saveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ question_id: qId, answer_text: value })
            }).then(r => r.json()).then(d => {
                const btn = document.querySelector(`.idp-q-btn[data-num="${qId}"]`);
                if(btn && value) btn.classList.add('answered');
            });
        }
        
        let saveTimer;
        function autoSave(qId, value) {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => saveAnswer(qId, value), 600);
        }
        
        // Navigation
        function goTo(num) {
            const el = document.querySelector(`[data-q-num="${num}"]`);
            if(el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        function prevQ() { /* implement */ }
        function nextQ() { /* implement */ }
        
        // Modal
        function showModal() { document.getElementById('submitModal').classList.remove('hidden'); }
        function hideModal() { document.getElementById('submitModal').classList.add('hidden'); }
        function submitSection() {
            fetch(submitUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } })
                .then(r => r.json())
                .then(d => { if(d.redirect) window.location.href = d.redirect; });
        }
        
        // Audio
        function playAudio() {
            document.getElementById('audioOverlay').classList.add('hidden');
            const audio = document.getElementById('audioPlayer');
            if(audio) audio.play();
        }
        
        // Word count for writing
        function updateWordCount() {
            const ta = document.getElementById('writingAnswer');
            const wc = document.getElementById('wordCount');
            if(ta && wc) {
                const words = ta.value.trim() ? ta.value.trim().split(/\s+/).length : 0;
                wc.textContent = words;
            }
        }
        
        // Resizable divider
        document.addEventListener('DOMContentLoaded', function() {
            const divider = document.getElementById('divider');
            const left = document.getElementById('leftPanel');
            const right = document.getElementById('rightPanel');
            if(divider && left && right) {
                let resizing = false;
                divider.addEventListener('mousedown', () => { resizing = true; document.body.style.cursor = 'col-resize'; });
                document.addEventListener('mousemove', e => {
                    if(!resizing) return;
                    const pct = (e.clientX / window.innerWidth) * 100;
                    if(pct > 20 && pct < 80) {
                        left.style.flex = `0 0 ${pct}%`;
                        right.style.flex = `0 0 ${100-pct-1.5}%`;
                    }
                });
                document.addEventListener('mouseup', () => { resizing = false; document.body.style.cursor = ''; });
            }
            updateWordCount();
        });
    </script>
</body>
</html>
