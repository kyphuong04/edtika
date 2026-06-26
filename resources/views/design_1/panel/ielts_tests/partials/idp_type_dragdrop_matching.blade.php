{{--
    Drag & Drop Matching – IDP Style
    Two sub-types controlled by $dragType ('disappear' | 'reuse'):
      disappear : option chip is hidden once placed in a drop zone
      reuse     : option chip always stays visible in the pool (can be re-used)

    $questions   – Collection of IeltsTestQuestion
    $userAnswers – array [question_id => JSON-encoded answers]
    $dragType    – 'disappear' | 'reuse'  (default 'reuse')
--}}

@php
    $dragType   = $dragType ?? 'reuse';
    $panelId    = 'dd_panel_' . ($questions->first()->question_group_id ?? uniqid());
    $instanceId = 'dd_' . ($questions->first()->question_group_id ?? uniqid());

    // Collect the draggable options from the first question's answer_options
    $firstQ   = $questions->first();
    $options  = [];
    if ($firstQ) {
        $raw = $firstQ->answer_options;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $options = is_array($decoded) ? $decoded : [];
        } elseif (is_array($raw)) {
            $options = $raw;
        }
    }

    // Build a flat list of blank slots across all questions
    // Each question stores its full text and correct answers in correctAnswers JSON
    // At render time we only need: question id, number, saved answers, and text
    $slots = [];
    foreach ($questions as $q) {
        $qNum     = $q->question_number ?? 1;
        $rawText  = $q->question_text ?? '';

        // Decode saved answer for this question (array indexed by blank)
        $savedRaw = $userAnswers[$q->id] ?? '';
        $savedArr = [];
        if (is_string($savedRaw) && strlen($savedRaw)) {
            $dec = json_decode($savedRaw, true);
            $savedArr = is_array($dec) ? $dec : [];
        } elseif (is_array($savedRaw)) {
            $savedArr = $savedRaw;
        }

        // Count blanks in text
        $blankCount = max(1, substr_count($rawText, '___'));

        $slots[] = [
            'qid'       => $q->id,
            'num'       => $qNum,
            'text'      => $rawText,
            'blankCount'=> $blankCount,
            'saved'     => $savedArr,
        ];
    }
@endphp

<div class="idp-dd-wrap" id="{{ $instanceId }}" data-drag-type="{{ $dragType }}">

    {{-- ─── Options Pool ────────────────────────────────────────────── --}}
    <div class="idp-dd-pool" id="{{ $panelId }}"
         style="display:flex;flex-wrap:wrap;gap:8px;padding:10px 14px;background:#f0f4ff;border:1px solid #c7d2fe;border-radius:8px;margin-bottom:16px;">
        <span style="width:100%;font-size:12px;font-weight:700;color:#4338ca;margin-bottom:2px;">List of options</span>
        @foreach($options as $optIdx => $opt)
            <span class="idp-dd-chip"
                  draggable="true"
                  data-opt="{{ $opt }}"
                  data-opt-idx="{{ $optIdx }}"
                  id="{{ $instanceId }}_opt_{{ $optIdx }}"
                  style="display:inline-flex;align-items:center;padding:5px 12px;background:#fff;border:1.5px solid #818cf8;border-radius:20px;cursor:grab;font-size:13px;font-weight:500;color:#1e1b4b;user-select:none;transition:opacity .2s;">
                {{ $opt }}
            </span>
        @endforeach
    </div>

    {{-- ─── Questions / Blanks ──────────────────────────────────────── --}}
    <div class="idp-dd-questions">
        @foreach($slots as $slot)
            @php
                $qid        = $slot['qid'];
                $qNum       = $slot['num'];
                $rawText    = $slot['text'];
                $blankCount = $slot['blankCount'];
                $saved      = $slot['saved'];

                // Split text on ___ markers
                $textParts  = preg_split('/_{3,}/', $rawText);
            @endphp

            <div class="idp-dd-row" data-qid="{{ $qid }}" style="display:flex;align-items:flex-start;gap:8px;margin-bottom:10px;line-height:1.6;">
                <span class="idp-q-num" style="flex-shrink:0;">{{ $qNum }}</span>
                <span style="flex:1;font-size:14px;">
                    @foreach($textParts as $pIdx => $part)
                        {!! $part !!}
                        @if($pIdx < count($textParts) - 1)
                            @php
                                $blankIdx  = $pIdx;
                                $savedVal  = $saved[$blankIdx] ?? '';
                                $zoneId    = $instanceId . '_zone_' . $qid . '_' . $blankIdx;
                            @endphp
                            <span class="idp-dd-zone {{ $savedVal ? 'filled' : '' }}"
                                  id="{{ $zoneId }}"
                                  data-qid="{{ $qid }}"
                                  data-blank="{{ $blankIdx }}"
                                  data-instance="{{ $instanceId }}"
                                  ondragover="event.preventDefault();this.classList.add('drag-over');"
                                  ondragleave="this.classList.remove('drag-over');"
                                  ondrop="iddmDrop(event, '{{ $instanceId }}', {{ $qid }}, {{ $blankIdx }})"
                                  style="display:inline-flex;align-items:center;justify-content:space-between;min-width:110px;padding:3px 8px;border:2px dashed #818cf8;border-radius:4px;background:{{ $savedVal ? '#e0e7ff' : '#fff' }};cursor:pointer;vertical-align:middle;gap:4px;">
                                <span class="idp-dd-zone-text" style="font-size:13px;font-weight:600;color:#3730a3;">{{ $savedVal }}</span>
                                @if($savedVal)
                                    <button type="button"
                                            onclick="iddmClear('{{ $instanceId }}', {{ $qid }}, {{ $blankIdx }})"
                                            style="border:none;background:transparent;color:#9ca3af;cursor:pointer;padding:0;line-height:1;font-size:14px;"
                                            title="Clear">×</button>
                                @endif
                            </span>
                        @endif
                    @endforeach
                </span>
            </div>
        @endforeach
    </div>
</div>

@once
<style>
.idp-dd-chip.used      { opacity: .25; pointer-events: none; }
.idp-dd-chip:hover     { background: #e0e7ff !important; }
.idp-dd-zone.drag-over { background: #dbeafe !important; border-color: #3b82f6 !important; }
.idp-dd-zone.filled    { border-style: solid; }
</style>

<script>
(function () {
    // Drag start
    document.addEventListener('dragstart', function (e) {
        const chip = e.target.closest('.idp-dd-chip');
        if (!chip) return;
        e.dataTransfer.setData('text/plain', chip.dataset.opt);
        e.dataTransfer.setData('dd-chip-id', chip.id);
        chip.style.opacity = '.4';
    });
    document.addEventListener('dragend', function (e) {
        const chip = e.target.closest('.idp-dd-chip');
        if (chip) chip.style.opacity = '';
    });
})();

function iddmDrop(e, instanceId, qid, blankIdx) {
    e.preventDefault();
    const zone = document.getElementById(instanceId + '_zone_' + qid + '_' + blankIdx);
    if (!zone) return;
    zone.classList.remove('drag-over');

    const word     = e.dataTransfer.getData('text/plain');
    const chipId   = e.dataTransfer.getData('dd-chip-id');
    const dragType = document.getElementById(instanceId).dataset.dragType || 'reuse';

    // If zone already has a value, restore its old chip first
    const oldText = zone.querySelector('.idp-dd-zone-text')?.textContent?.trim();
    if (oldText) iddmRestoreChip(instanceId, oldText);

    // Fill zone
    iddmFillZone(zone, word, instanceId, qid, blankIdx);

    // Handle chip visibility
    if (dragType === 'disappear') {
        const chip = document.getElementById(chipId);
        if (chip) chip.classList.add('used');
    }

    // Persist answer
    iddmSaveAnswer(instanceId, qid);
}

function iddmFillZone(zone, word, instanceId, qid, blankIdx) {
    zone.innerHTML = `<span class="idp-dd-zone-text" style="font-size:13px;font-weight:600;color:#3730a3;">${escIddm(word)}</span>
        <button type="button" onclick="iddmClear('${instanceId}', ${qid}, ${blankIdx})"
                style="border:none;background:transparent;color:#9ca3af;cursor:pointer;padding:0;line-height:1;font-size:14px;" title="Clear">×</button>`;
    zone.classList.add('filled');
    zone.style.background = '#e0e7ff';
    zone.style.borderStyle = 'solid';
}

function iddmClear(instanceId, qid, blankIdx) {
    const zone = document.getElementById(instanceId + '_zone_' + qid + '_' + blankIdx);
    if (!zone) return;
    const old = zone.querySelector('.idp-dd-zone-text')?.textContent?.trim();
    if (old) iddmRestoreChip(instanceId, old);

    zone.innerHTML = '';
    zone.classList.remove('filled');
    zone.style.background = '#fff';
    zone.style.borderStyle = 'dashed';
    iddmSaveAnswer(instanceId, qid);
}

function iddmRestoreChip(instanceId, word) {
    const pool = document.getElementById(instanceId).querySelector('.idp-dd-pool');
    if (!pool) return;
    const chip = Array.from(pool.querySelectorAll('.idp-dd-chip')).find(c => c.dataset.opt === word);
    if (chip) chip.classList.remove('used');
}

function iddmSaveAnswer(instanceId, qid) {
    const wrap  = document.getElementById(instanceId);
    const zones = Array.from(wrap.querySelectorAll(`.idp-dd-zone[data-qid="${qid}"]`));
    const answers = zones.map(z => z.querySelector('.idp-dd-zone-text')?.textContent?.trim() || '');
    // Save as JSON array; the backend expects correct_answer to be JSON-encoded array
    if (typeof saveAnswer === 'function') {
        saveAnswer(qid, JSON.stringify(answers));
    }
}

function escIddm(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endonce
