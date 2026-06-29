{{--
    IELTS Writing Section - Wireframe-matched design
    Left: WRITING panel (task content + image)
    Right: YOUR WRITING panel (textarea + word count)
--}}
@php
    $writingQuestions = collect($questions ?? [])->filter(fn($q) => !empty($q))->values();

    if ($writingQuestions->isEmpty() && !empty($question)) {
        $writingQuestions = collect([$question]);
    }

    $sectionGroups = collect();
    if (!empty($section?->id)) {
        $sectionGroups = \App\Models\IeltsQuestionGroup::where('section_id', $section->id)
            ->orderBy('question_start')
            ->get();
    }

    $resolveMediaUrl = function ($path) {
        if (empty($path) || !is_string($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return \Storage::disk('public')->url($path);
    };

    $extractTaskImageFromQuestionData = function ($questionData) {
        if (empty($questionData)) {
            return null;
        }

        $decoded = is_array($questionData) ? $questionData : json_decode($questionData, true);
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        if (!is_array($decoded)) {
            return null;
        }

        foreach (['task_image', 'task_image_url', 'image', 'image_url'] as $key) {
            if (!empty($decoded[$key]) && is_string($decoded[$key])) {
                return $decoded[$key];
            }
        }

        return null;
    };

    $taskItems = [];

    foreach ($writingQuestions as $idx => $q) {
        $questionNumber = (int) ($q->question_number ?? ($idx + 1));

        $taskText = $q->question_text
            ?? $q->content
            ?? $section->content
            ?? $section->passage_text
            ?? '';

        $isHtmlTask = $taskText !== strip_tags($taskText);

        if ($isHtmlTask) {
            $renderedTaskHtml = $taskText;
        } else {
            $taskParts = preg_split('/\r?\n\r?\n/', trim((string) $taskText), 2);
            $boldLine  = $taskParts[0] ?? '';
            $bodyText  = $taskParts[1] ?? '';

            $renderedTaskHtml = '';
            if (!empty($boldLine)) {
                $renderedTaskHtml .= '<p class="wf-wr-q-bold">' . nl2br(e($boldLine)) . '</p>';
            }
            if (!empty($bodyText)) {
                $renderedTaskHtml .= '<div class="wf-wr-q-text">' . nl2br(e($bodyText)) . '</div>';
            }
        }

        $taskPart = !empty($q->part_id) ? \App\Models\IeltsTestPart::find($q->part_id) : null;
        $taskGroup = $q->questionGroup;

        if (!$taskGroup && $sectionGroups->isNotEmpty()) {
            $taskGroup = $sectionGroups->first(function ($group) use ($questionNumber) {
                return !empty($group->question_start)
                    && !empty($group->question_end)
                    && $questionNumber >= (int) $group->question_start
                    && $questionNumber <= (int) $group->question_end;
            });
        }

        $rawImagePathCandidates = [
            $taskGroup?->task_image ?? null,
            $q->question_image ?? null,
            $q->image_file ?? null,
            $extractTaskImageFromQuestionData($q->question_data ?? null),
            $taskPart?->task_image ?? null,
            $section->image_file ?? null,
            $section->image_url ?? null,
        ];

        $rawImagePath = collect($rawImagePathCandidates)->first(fn($value) => !empty($value));
        $imageUrl = $resolveMediaUrl($rawImagePath);

        $partNum = (int) ($taskPart->part_number ?? ($questionNumber > 1 ? 2 : 1));
        $minWords = $partNum === 1 ? 150 : 250;
        $recommendedTime = $partNum === 1 ? 20 : 40;

        $savedAnswer = (string) ($userAnswers[$q->id] ?? ($userAnswer ?? ''));

        $taskItems[] = [
            'questionId' => (int) ($q->id ?? 0),
            'questionNumber' => $questionNumber,
            'minWords' => $minWords,
            'recommendedTime' => $recommendedTime,
            'renderedTaskHtml' => $renderedTaskHtml,
            'imageUrl' => $imageUrl,
            'hasImageFlag' => !empty($q->has_image),
            'savedAnswer' => $savedAnswer,
        ];
    }

    $activeTask = $taskItems[0] ?? [
        'questionId' => 0,
        'questionNumber' => 1,
        'minWords' => 150,
        'recommendedTime' => 20,
        'renderedTaskHtml' => '',
        'imageUrl' => null,
        'hasImageFlag' => false,
        'savedAnswer' => '',
    ];

    $taskPayload = collect($taskItems)->map(function ($item) {
        return [
            'questionId' => (int) $item['questionId'],
            'questionNumber' => (int) $item['questionNumber'],
            'minWords' => (int) $item['minWords'],
            'recommendedTime' => (int) $item['recommendedTime'],
            'savedAnswer' => (string) ($item['savedAnswer'] ?? ''),
        ];
    })->values();
@endphp

<style>
/* ── WRITING WIREFRAME ───────────────────────── */
.wf-wr-container {
    display: flex;
    width: 100%; height: 100%;
    background: #e5e5e5;
    gap: 0;
    overflow: hidden;
}

/* Panels */
.wf-wr-panel {
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
}
.wf-wr-left  { flex: 0 0 45%; min-width: 280px; }
.wf-wr-right { flex: 1; min-width: 320px; }

/* Grey panel header */
.wf-wr-panel-header {
    flex-shrink: 0;
    background: #d8d8d8;
    padding: 14px 24px;
    font-size: 22px;
    font-weight: 900;
    color: #111;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-bottom: 1px solid #ccc;
}

/* Panel body */
.wf-wr-panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
}
.wf-wr-panel-body::-webkit-scrollbar { width: 5px; }
.wf-wr-panel-body::-webkit-scrollbar-track { background: #f0f0f0; }
.wf-wr-panel-body::-webkit-scrollbar-thumb { background: #bbb; border-radius: 3px; }

/* ── LEFT: task content ─ */
.wf-wr-task-card { display: none; }
.wf-wr-task-card.is-active { display: block; }

.wf-wr-q-label {
    font-size: 14px; color: #333; margin-bottom: 10px; line-height: 1.5;
}
.wf-wr-q-bold { font-weight: 700; margin-bottom: 6px; font-size: 15px; line-height: 1.5; }
.wf-wr-q-text { font-size: 15px; line-height: 1.6; margin-bottom: 10px; color: #111; }
.wf-wr-q-text p { margin-bottom: 8px; }
.wf-wr-word-min {
    font-size: 14px; color: #333; margin-top: 12px; margin-bottom: 0;
}
/* Image placeholder / actual image */
.wf-wr-img-box {
    background: #f0f0f0;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    margin: 16px 0;
    overflow: hidden;
    text-align: center;
}
.wf-wr-img-box img { max-width: 100%; display: block; }
.wf-wr-img-placeholder {
    width: 100%; min-height: 200px;
    background: #e8e8e8;
    display: flex; align-items: center; justify-content: center;
    color: #bbb; font-size: 13px;
}

/* ── DIVIDER ─────────────────────────────────── */
.wf-wr-divider {
    width: 7px;
    background: #e5e5e5;
    cursor: col-resize;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wf-wr-divider-thumb {
    width: 2px; height: 36px;
    background: #bbb; border-radius: 2px;
}

/* ── RIGHT: textarea ─────────────────────────── */
.wf-wr-right-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.wf-wr-textarea {
    flex: 1;
    width: 100%;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    padding: 20px 24px;
    font-size: 15px;
    font-family: Arial, sans-serif;
    line-height: 1.8;
    resize: none;
    outline: none;
    color: #111;
    background: #fff;
    overflow-y: auto;
}
.wf-wr-textarea::placeholder { color: #bbb; }
.wf-wr-textarea::-webkit-scrollbar { width: 5px; }
.wf-wr-textarea::-webkit-scrollbar-track { background: #f0f0f0; }
.wf-wr-textarea::-webkit-scrollbar-thumb { background: #bbb; border-radius: 3px; }

.wf-wr-bottom-bar {
    flex-shrink: 0;
    padding: 10px 24px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.wf-wr-wordcount-pill {
    display: inline-flex; align-items: center;
    background: #f3f4f6; border: 1.5px solid #e5e7eb;
    border-radius: 20px; padding: 5px 16px;
    font-size: 14px; color: #374151; font-weight: 600;
}
.wf-wr-wordcount-pill #wfWordCount { color: #111; }
.wf-wr-autosave { font-size: 12px; color: #9ca3af; }
.wf-wr-font-btns { display: flex; gap: 4px; }
.wf-wr-font-btn {
    padding: 4px 10px;
    border: 1.5px solid #d1d5db;
    border-radius: 5px;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
    color: #374151;
    font-weight: 600;
    transition: background .15s;
}
.wf-wr-font-btn:hover { background: #f3f4f6; }
</style>

<div class="wf-wr-container">

    {{-- LEFT PANEL: WRITING ─────────────────── --}}
    <div class="wf-wr-panel wf-wr-left" id="writingTaskPanel">
        <div class="wf-wr-panel-header">WRITING</div>
        <div class="wf-wr-panel-body">
            @foreach($taskItems as $index => $item)
                <div class="wf-wr-task-card {{ $index === 0 ? 'is-active' : '' }}" data-writing-index="{{ $index }}" data-q-num="{{ $item['questionNumber'] }}">
                    <p class="wf-wr-q-label">
                        Question {{ $item['questionNumber'] }}: You should spend about <strong>{{ $item['recommendedTime'] }} minutes</strong> on this task.
                    </p>

                    <div class="wf-wr-q-text">{!! $item['renderedTaskHtml'] !!}</div>

                    <p class="wf-wr-word-min">Write at least <strong>{{ $item['minWords'] }} words</strong>.</p>

                    @if(!empty($item['imageUrl']))
                        <div class="wf-wr-img-box">
                            <img src="{{ $item['imageUrl'] }}" alt="Task diagram">
                        </div>
                    @elseif(!empty($item['hasImageFlag']))
                        <div class="wf-wr-img-box">
                            <div class="wf-wr-img-placeholder">[Image not available]</div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- DRAG DIVIDER ─────────────────────────── --}}
    <div class="wf-wr-divider" id="writingDivider">
        <div class="wf-wr-divider-thumb"></div>
    </div>

    {{-- RIGHT PANEL: YOUR WRITING ─────────────── --}}
    <div class="wf-wr-panel wf-wr-right" id="writingAnswerPanel">
        <div class="wf-wr-panel-header">YOUR WRITING</div>
        <div class="wf-wr-right-body">

            <textarea
                id="writingAnswer"
                class="wf-wr-textarea"
                data-question-id="{{ $activeTask['questionId'] }}"
                placeholder="Write your essay here..."
                oninput="WfWriting.onInput()"
            >{{ $activeTask['savedAnswer'] }}</textarea>

            <div class="wf-wr-bottom-bar">
                <div class="wf-wr-wordcount-pill">
                    Word count: <span id="wfWordCount">0</span>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <span id="wfAutoSave" class="wf-wr-autosave">&bull; Saved</span>
                    <div class="wf-wr-font-btns">
                        <button type="button" class="wf-wr-font-btn" onclick="WfWriting.decreaseFontSize()">A&minus;</button>
                        <button type="button" class="wf-wr-font-btn" onclick="WfWriting.increaseFontSize()">A+</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>{{-- end container --}}

<script>
window.WfWriting = {
    questionItems: @json($taskPayload),
    currentIndex: 0,
    fontSize: 15,
    saveTimer: null,

    init: function () {
        if (!Array.isArray(this.questionItems) || this.questionItems.length === 0) {
            this.questionItems = [{ questionId: 0, questionNumber: 1, minWords: 150, recommendedTime: 20, savedAnswer: '' }];
        }

        this.updateWordCount();
        this.initResizer();

        const ta = document.getElementById('writingAnswer');
        if (ta) {
            ta.focus();
        }
    },

    getCurrentItem: function () {
        return this.questionItems[this.currentIndex] || this.questionItems[0];
    },

    onInput: function () {
        this.updateWordCount();
        this.persistCurrentAnswerLocally();
        this.scheduleAutoSave();
    },

    persistCurrentAnswerLocally: function () {
        const ta = document.getElementById('writingAnswer');
        const item = this.getCurrentItem();
        if (!ta || !item) return;

        item.savedAnswer = ta.value;
        this.updateAnsweredCircle(item.questionNumber, ta.value);
    },

    updateAnsweredCircle: function (questionNumber, value) {
        const circle = document.querySelector('.idp-q-circle[data-q-num="' + questionNumber + '"]');
        if (!circle) return;

        if (value && value.trim()) {
            circle.classList.add('answered');
        } else {
            circle.classList.remove('answered');
        }
    },

    updateWordCount: function () {
        const ta    = document.getElementById('writingAnswer');
        if (!ta) return;

        const text  = ta.value.trim();
        const words = text ? text.split(/\s+/).filter(function (w) { return w.length > 0; }).length : 0;
        const el    = document.getElementById('wfWordCount');
        if (el) el.textContent = words;
    },

    scheduleAutoSave: function () {
        clearTimeout(this.saveTimer);
        const sp = document.getElementById('wfAutoSave');
        if (sp) {
            sp.textContent = '\u2022 Typing...';
            sp.style.color = '#f59e0b';
        }

        this.saveTimer = setTimeout(() => this.saveAnswer(), 1200);
    },

    saveAnswer: function () {
        const ta = document.getElementById('writingAnswer');
        const sp = document.getElementById('wfAutoSave');
        const item = this.getCurrentItem();

        if (!ta || !item || !item.questionId) {
            if (sp) {
                sp.textContent = '\u2022 Saved';
                sp.style.color = '#10b981';
            }
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const url = (typeof saveUrl !== 'undefined' && saveUrl) ? saveUrl : null;
        if (!url) {
            if (sp) {
                sp.textContent = '\u2022 Saved';
                sp.style.color = '#10b981';
            }
            return;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question_id: item.questionId, answer_text: ta.value })
        }).then(function (r) {
            if (sp) {
                sp.textContent = r.ok ? '\u2022 Saved' : '\u2022 Error';
                sp.style.color = r.ok ? '#10b981' : '#ef4444';
            }
        }).catch(function () {
            if (sp) {
                sp.textContent = '\u2022 Error';
                sp.style.color = '#ef4444';
            }
        });
    },

    goToQuestionNum: function (questionNumber) {
        const index = this.questionItems.findIndex(function (item) {
            return Number(item.questionNumber) === Number(questionNumber);
        });

        if (index >= 0) {
            this.switchToIndex(index);
        }
    },

    switchToIndex: function (index) {
        if (index < 0 || index >= this.questionItems.length) {
            return;
        }

        this.persistCurrentAnswerLocally();
        this.currentIndex = index;

        document.querySelectorAll('.wf-wr-task-card').forEach(function (card) {
            card.classList.remove('is-active');
        });

        const activeCard = document.querySelector('.wf-wr-task-card[data-writing-index="' + index + '"]');
        if (activeCard) {
            activeCard.classList.add('is-active');
        }

        const ta = document.getElementById('writingAnswer');
        const item = this.getCurrentItem();
        if (ta && item) {
            ta.value = item.savedAnswer || '';
            ta.setAttribute('data-question-id', String(item.questionId || 0));
            this.updateWordCount();
        }
    },

    increaseFontSize: function () {
        if (this.fontSize < 28) {
            this.fontSize += 2;
            this.applyFont();
        }
    },

    decreaseFontSize: function () {
        if (this.fontSize > 12) {
            this.fontSize -= 2;
            this.applyFont();
        }
    },

    applyFont: function () {
        const ta = document.getElementById('writingAnswer');
        if (ta) ta.style.fontSize = this.fontSize + 'px';
    },

    initResizer: function () {
        const div   = document.getElementById('writingDivider');
        const left  = document.getElementById('writingTaskPanel');
        if (!div || !left) return;

        let drag = false;

        div.addEventListener('mousedown', function () {
            drag = true;
            document.body.style.cursor = 'col-resize';
        });

        document.addEventListener('mouseup', function () {
            drag = false;
            document.body.style.cursor = '';
        });

        document.addEventListener('mousemove', function (e) {
            if (!drag) return;

            const container = left.parentElement;
            const rect = container.getBoundingClientRect();
            const pct = ((e.clientX - rect.left) / rect.width) * 100;
            if (pct > 20 && pct < 75) {
                left.style.flex = '0 0 ' + pct + '%';
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    if (window.WfWriting && typeof window.WfWriting.init === 'function') {
        window.WfWriting.init();
    }
});
</script>
