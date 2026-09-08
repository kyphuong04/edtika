@extends('admin.layouts.app')

@push('styles_top')
<style>
.pt-question-card { background:#f9fafb; border:2px solid #e5e7eb; border-radius:10px; padding:16px; margin-bottom:16px; position:relative; }
.pt-question-card .pt-q-badge { display:inline-block; background:#511D99; color:#fff; padding:3px 10px; border-radius:6px; font-size:12px; font-weight:600; margin-bottom:10px; }
.pt-option-row { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.pt-option-row input[type="text"] { flex:1; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; }
.pt-blank-answer-row { display:flex; align-items:center; gap:8px; margin-bottom:6px; }
.pt-blank-answer-row span { min-width:70px; font-size:12px; color:#6b7280; font-weight:600; }
.pt-blank-answer-row input { flex:1; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; }
.pt-file-preview { display:inline-flex; align-items:center; gap:8px; background:#e0e7ff; color:#511D99; padding:6px 10px; border-radius:6px; font-size:12px; margin-top:6px; }
.pt-remove-btn { border:none; background:transparent; color:#ef4444; cursor:pointer; }

/* ── Khối file audio dùng chung ── */
.pt-clip-card { background:#eef2ff; border:2px solid #c7d2fe; border-radius:10px; padding:16px; margin-bottom:12px; }
.pt-clip-card .pt-q-badge { background:#4338CA; }
.pt-audio-note { font-size:12.5px; border-radius:8px; padding:8px 12px; margin-bottom:10px; line-height:1.5; }
.pt-audio-note--ok { background:#eef2ff; border:1px solid #c7d2fe; color:#3730a3; }
.pt-audio-note--warn { background:#fef3c7; border:1px solid #fcd34d; color:#78350f; }
.pt-audio-note--error { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }
.pt-move-btns { position:absolute; top:16px; right:44px; display:flex; gap:4px; }
.pt-move-btns button { border:1px solid #d1d5db; background:#fff; color:#6b7280; border-radius:6px; width:26px; height:26px; line-height:1; cursor:pointer; font-size:11px; }
.pt-move-btns button:disabled { opacity:.35; cursor:not-allowed; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $pageTitle }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.placement_tests.index') }}">Placement Test</a></div>
            <div class="breadcrumb-item">{{ $placementTest ? 'Sửa đề' : 'Tạo đề mới' }}</div>
        </div>
    </div>

    <div class="section-body">

    {{-- Tab: Tạo/sửa đề  ||  Danh sách đề đã tạo --}}
    <ul class="nav nav-tabs mb-4" id="ptTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pt-tab-form" data-toggle="tab" href="#pt-pane-form" role="tab" style="font-weight:600;">
                <i class="fas fa-edit mr-5"></i>{{ $placementTest ? 'Sửa đề' : 'Tạo đề mới' }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pt-tab-list" data-toggle="tab" href="#pt-pane-list" role="tab" style="font-weight:600;">
                <i class="fas fa-list mr-5"></i>Danh sách đề đã tạo <span class="badge badge-secondary ml-4">{{ $existingTests->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pt-tab-speaking" data-toggle="tab" href="#pt-pane-speaking" role="tab" style="font-weight:600;">
                <i class="fas fa-microphone mr-5"></i>Câu hỏi Speaking <span class="badge badge-secondary ml-4">{{ $speakingQuestions->count() }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="ptTabsContent">
    <div class="tab-pane fade show active" id="pt-pane-form" role="tabpanel">

    <form id="placementTestForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($placementTest) @method('PUT') @endif

        <div id="mediaFilesHolder" style="display:none;"></div>

        <div class="test-info-card" style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:24px;">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">Level *</label>
                        <select name="level" class="form-control" required>
                            <option value="">-- Chọn level --</option>
                            @foreach(['A1','A2','B1','B2','B2+'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('level', $placementTest->level ?? '') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="input-label">Tiêu đề đề thi *</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $placementTest->title ?? '') }}" placeholder="VD: Placement Test B1 - Bộ 1">
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <label class="input-label">Mô tả (nội bộ)</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $placementTest->description ?? '') }}</textarea>
            </div>

            <div class="mt-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="input-label mb-0">Đoạn văn đọc <small class="text-muted">(có thể thêm nhiều đoạn, mỗi đoạn hiển thị tại 1 vị trí trong bài)</small></label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddPassage"><i class="fas fa-plus mr-4"></i>Thêm đoạn văn</button>
                </div>
                <div id="passagesContainer"></div>
                <input type="hidden" name="reading_passages" id="passagesDataInput" value="">
            </div>

            {{-- File audio dùng chung: 1 file có thể gán cho nhiều câu liền nhau --}}
            <div class="mt-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="input-label mb-0">
                        File audio
                        <small class="text-muted">(một file có thể dùng cho nhiều câu liền nhau, VD: câu 9–10 nghe chung 1 file)</small>
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddAudioClip"><i class="fas fa-plus mr-4"></i>Thêm file audio</button>
                </div>
                <div id="audioClipsContainer"></div>
                <input type="hidden" name="audio_clips_data" id="audioClipsDataInput" value="">
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-12">
            <h4 class="mb-0">Câu hỏi (<span id="questionCountLabel">0</span>/{{ \App\Models\PlacementTest::MAX_QUESTIONS }})</h4>
            <button type="button" class="btn btn-primary btn-sm" id="btnAddQuestion"><i class="fas fa-plus mr-5"></i>Thêm câu hỏi</button>
        </div>

        <div id="questionsContainer"></div>

        <input type="hidden" name="questions_data" id="questionsDataInput" value="">

        <div class="mt-30 d-flex justify-content-end gap-8">
            <button type="submit" name="submit_action" value="draft" class="btn btn-outline-secondary btn-lg rounded-12"><i class="fas fa-save mr-5"></i>Lưu nháp</button>
            <button type="submit" name="submit_action" value="publish" class="btn btn-primary btn-lg"><i class="fas fa-check mr-5"></i>Lưu & Xuất bản</button>
        </div>
    </form>

    </div>{{-- /pt-pane-form --}}

    <div class="tab-pane fade" id="pt-pane-list" role="tabpanel">
        {{-- Tiến độ pool: 3 B1, 2 A2, 2 B2, 1 A1, 1 B2+ --}}
        <div class="row mb-20">
            @foreach($poolProgress as $p)
                <div class="col-md-2 col-4 mb-12">
                    <div style="border:2px solid {{ $p['current'] >= $p['required'] ? '#22c55e' : '#e5e7eb' }};border-radius:12px;padding:14px;text-align:center;">
                        <div style="font-weight:700;font-size:18px;color:#511D99;">{{ $p['level'] }}</div>
                        <div style="font-size:13px;color:#6b7280;">{{ $p['current'] }} / {{ $p['required'] }} đề</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $p['published'] }} đã xuất bản</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Tiêu đề</th>
                            <th>Số câu</th>
                            <th>Trạng thái</th>
                            <th>Cập nhật</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($existingTests as $t)
                            <tr @if($placementTest && $placementTest->id === $t->id) style="background:#f5f3ff;" @endif>
                                <td><span class="badge badge-primary">{{ $t->level }}</span></td>
                                <td>{{ $t->title }}</td>
                                <td>
                                    {{ $t->questions_count }}/{{ \App\Models\PlacementTest::MAX_QUESTIONS }}
                                    @if($t->questions_count < \App\Models\PlacementTest::MAX_QUESTIONS)
                                        <span class="text-warning ml-4"><i class="fas fa-exclamation-triangle"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->status === 'published')
                                        <span class="badge badge-success">Đã xuất bản</span>
                                    @else
                                        <span class="badge badge-secondary">Nháp</span>
                                    @endif
                                </td>
                                <td>{{ $t->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.placement_tests.edit', $t) }}" class="btn btn-sm btn-outline-primary rounded-12"><i class="fas fa-edit"></i> Sửa</a>
                                    <form action="{{ route('admin.placement_tests.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá đề này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-12"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-30">Chưa có đề nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>{{-- /pt-pane-list --}}

    <div class="tab-pane fade" id="pt-pane-speaking" role="tabpanel">
        <div class="card mb-20">
            <div class="card-header"><h4 class="mb-0">Thêm câu hỏi Speaking mới</h4></div>
            <div class="card-body">
                <form action="{{ route('admin.placement_speaking.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="question_text" class="form-control" rows="4" required placeholder="VD: Describe a memorable trip you have taken. Why was it memorable?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-12"><i class="fas fa-plus mr-5"></i>Thêm câu hỏi</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:55%;">Câu hỏi</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($speakingQuestions as $q)
                            <tr>
                                <td>
                                    <form action="{{ route('admin.placement_speaking.update', $q) }}" method="POST" class="d-flex align-items-center gap-8">
                                        @csrf @method('PUT')
                                        <input type="text" name="question_text" value="{{ $q->question_text }}" class="form-control form-control-sm">
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-12">Lưu</button>
                                    </form>
                                </td>
                                <td>
                                    @if($q->is_active)
                                        <span class="badge badge-success">Đang dùng</span>
                                    @else
                                        <span class="badge badge-secondary">Đã tắt</span>
                                    @endif
                                </td>
                                <td>{{ $q->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <form action="{{ route('admin.placement_speaking.toggle_status', $q) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $q->is_active ? 'secondary' : 'success' }} rounded-12">
                                            {{ $q->is_active ? 'Tắt' : 'Bật' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.placement_speaking.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá câu hỏi này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-12"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-30">Chưa có câu hỏi Speaking nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>{{-- /pt-pane-speaking --}}

    </div>{{-- /ptTabsContent --}}

    </div>{{-- /section-body --}}
</section>

<script>
const MAX_QUESTIONS = {{ \App\Models\PlacementTest::MAX_QUESTIONS }};
let questions = @json($questionsData ?? []);
let uploadSeq = 0;
let passages = @json($passagesData ?? []);
let passageSeq = passages.length ? Math.max(...passages.map(p => parseInt(String(p.id).replace('p','')) || 0)) : 0;

/* ── File audio ở cấp ĐỀ ────────────────────────────────────────────────
   Mỗi clip: { id, label, path, input_name, url }
     - path: đường dẫn file đã lưu trên server (khi sửa đề)
     - input_name: tên input file gửi kèm request (khi vừa chọn file mới)
     - url: link nghe thử (URL server hoặc blob URL của file vừa chọn)
   Câu hỏi chỉ giữ audio_clip_id trỏ tới clip. Các câu LIỀN NHAU cùng
   audio_clip_id sẽ được gộp thành 1 nhóm, học viên chỉ thấy 1 trình phát. */
let audioClips = @json($audioClipsData ?? []);
let clipSeq = audioClips.length ? Math.max(...audioClips.map(c => parseInt(String(c.id).replace('a','')) || 0)) : 0;

const container = document.getElementById('questionsContainer');
const countLabel = document.getElementById('questionCountLabel');

const TYPE_LABELS = {
    multiple_choice: 'Multiple Choice',
    sentence_completion: 'Sentence Completion',
    error_correction: 'Find & Correct the Mistake',
    listening_image_choice: 'Listening - Choose the Image',
};

const QUESTION_TEXT_LABELS = {
    multiple_choice: 'Nội dung câu hỏi *',
    sentence_completion: 'Câu có chỗ trống * (dùng ___ cho mỗi chỗ trống)',
    error_correction: 'Câu có lỗi sai *',
    listening_image_choice: 'Câu hỏi * (vd: Which one is Laura\'s brother?)',
};

function countBlanks(text) {
    const matches = String(text || '').match(/_{2,}/g);
    return matches ? matches.length : 0;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text == null ? '' : String(text);
    return div.innerHTML;
}

function blankQuestionTemplate() {
    return {
        type: 'multiple_choice',
        has_audio: false,
        audio_clip_id: null,
        linked_passage_id: null,
        question_text: '',
        options: ['', ''],
        word_bank: [],
        blank_hints: [],
        correct_answer: null,
        correct_answer_text: '',
        answer_help: '',
        points: 1,
        option_image_input_names: [null, null, null],
        existing_option_images: [null, null, null],
        option_image_urls: [null, null, null],
    };
}

function addQuestion() {
    if (questions.length >= MAX_QUESTIONS) {
        alert('Mỗi đề tối đa ' + MAX_QUESTIONS + ' câu hỏi.');
        return;
    }
    questions.push(blankQuestionTemplate());
    render();
}

function removeQuestion(index) {
    if (!confirm('Xoá câu hỏi này?')) return;
    questions.splice(index, 1);
    render();
}

/* Đổi vị trí câu hỏi. Cần thiết cho nhóm audio: các câu dùng chung 1 file
   phải nằm liền nhau thì mới gộp được thành 1 trình phát. */
function moveQuestion(index, delta) {
    const target = index + delta;
    if (target < 0 || target >= questions.length) return;
    const [item] = questions.splice(index, 1);
    questions.splice(target, 0, item);
    render();
}

function updateField(index, field, value) {
    questions[index][field] = value;
}

function changeType(index, type) {
    const fresh = blankQuestionTemplate();
    // Giữ lại nội dung chung, reset phần dữ liệu riêng theo dạng cũ để tránh lẫn dữ liệu.
    questions[index] = {
        ...fresh,
        type,
        question_text: questions[index].question_text,
        points: questions[index].points,
        answer_help: questions[index].answer_help,
        audio_clip_id: questions[index].audio_clip_id,
        has_audio: !!questions[index].audio_clip_id,
    };
    render();
}

function setQuestionClip(index, clipId) {
    questions[index].audio_clip_id = clipId || null;
    questions[index].has_audio = !!clipId;
    render();
}

function toggleLinkedToPassage(index, checked) {
    questions[index].linked_to_passage = checked;
}

function onOptionImageSelected(index, optIndex, inputEl) {
    if (inputEl.files && inputEl.files[0]) {
        const inputName = 'option_image_' + (++uploadSeq);
        inputEl.name = inputName;
        questions[index].option_image_input_names[optIndex] = inputName;
        document.getElementById('mediaFilesHolder').appendChild(inputEl);

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('option-img-preview-' + index + '-' + optIndex);
            if (img) { img.src = e.target.result; img.style.display = 'block'; }
        };
        reader.readAsDataURL(inputEl.files[0]);

        const placeholder = document.createElement('input');
        placeholder.type = 'file';
        placeholder.accept = 'image/*';
        placeholder.className = 'form-control form-control-sm mt-2';
        placeholder.onchange = function () { onOptionImageSelected(index, optIndex, this); };
        inputEl.insertAdjacentElement('afterend', placeholder);
    }
}

function addOption(index) {
    questions[index].options.push('');
    render();
}

function removeOption(index, optIndex) {
    if (questions[index].options.length <= 2) {
        alert('Cần tối thiểu 2 lựa chọn.');
        return;
    }
    const removedValue = questions[index].options[optIndex];
    questions[index].options.splice(optIndex, 1);
    if (questions[index].correct_answer === removedValue) {
        questions[index].correct_answer = null;
    }
    render();
}

function updateOption(index, optIndex, value) {
    const oldValue = questions[index].options[optIndex];
    if (questions[index].correct_answer === oldValue) {
        questions[index].correct_answer = value;
    }
    questions[index].options[optIndex] = value;
}

function setCorrectOption(index, value) {
    questions[index].correct_answer = value;
}

function setCorrectImage(index, letter) {
    questions[index].correct_answer = letter;
}

function updateWordBank(index, value) {
    questions[index].word_bank = value.split(',').map(v => v.trim()).filter(Boolean);
}

function updateBlankAnswer(index, blankIndex, value) {
    if (!Array.isArray(questions[index].correct_answer)) {
        questions[index].correct_answer = [];
    }
    questions[index].correct_answer[blankIndex] = value.split('/').map(v => v.trim()).filter(Boolean);
}

function updateBlankHint(index, blankIndex, value) {
    if (!Array.isArray(questions[index].blank_hints)) {
        questions[index].blank_hints = [];
    }
    questions[index].blank_hints[blankIndex] = value;
}

/* ── Đoạn văn đọc ──────────────────────────────────────────────────────── */

function addPassage() {
    passageSeq++;
    passages.push({ id: 'p' + passageSeq, content: '', position: 1 });
    renderPassages();
}

function removePassage(id) {
    if (!confirm('Xoá đoạn văn này? Các câu hỏi đang gắn với đoạn này sẽ bị bỏ liên kết.')) return;
    passages = passages.filter(p => p.id !== id);
    questions.forEach(q => { if (q.linked_passage_id === id) q.linked_passage_id = null; });
    renderPassages();
    render();
}

function updatePassageContent(id, value) {
    const p = passages.find(p => p.id === id);
    if (p) p.content = value;
}

function passageSelectHtml(index, q) {
    const options = passages.map(p =>
        `<option value="${p.id}" ${q.linked_passage_id === p.id ? 'selected' : ''}>${escapeHtml((p.content || '').slice(0, 40))}...</option>`
    ).join('');
    return `<div class="form-group mt-2 mb-0">
        <label class="input-label">Gắn với đoạn văn</label>
        <select class="form-control" onchange="updateField(${index}, 'linked_passage_id', this.value || null)">
            <option value="">— Không gắn —</option>
            ${options}
        </select>
    </div>`;
}

function updatePassagePosition(id, value) {
    const p = passages.find(p => p.id === id);
    if (p) p.position = parseInt(value, 10) || 1;
}

function renderPassages() {
    const passagesContainer = document.getElementById('passagesContainer');
    const totalQ = questions.length;

    passagesContainer.innerHTML = passages.map((p, idx) => {
        let posOpts = '';
        for (let i = 1; i <= Math.max(totalQ, 1); i++) {
            posOpts += `<option value="${i}" ${p.position === i ? 'selected' : ''}>Trước câu ${i}</option>`;
        }
        posOpts += `<option value="${totalQ + 1}" ${p.position === totalQ + 1 ? 'selected' : ''}>Cuối bài</option>`;

        return `<div class="pt-question-card" style="background:#faf5ff;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="pt-q-badge">Đoạn văn ${idx + 1}</span>
                <button type="button" class="pt-remove-btn" onclick="removePassage('${p.id}')"><i class="fas fa-trash"></i></button>
            </div>
            <div class="form-group mb-2">
                <label class="input-label">Nội dung</label>
                <textarea class="form-control" rows="4" oninput="updatePassageContent('${p.id}', this.value)">${escapeHtml(p.content)}</textarea>
            </div>
            <div class="form-group mb-0" style="max-width:240px;">
                <label class="input-label">Vị trí hiển thị</label>
                <select class="form-control" onchange="updatePassagePosition('${p.id}', this.value); renderPassages();">${posOpts}</select>
            </div>
        </div>`;
    }).join('') || '<div class="text-muted mb-2">Chưa có đoạn văn nào.</div>';
}

/* ── File audio dùng chung ─────────────────────────────────────────────── */

function clipUsageIndexes(clipId) {
    const result = [];
    questions.forEach((q, i) => { if (q.audio_clip_id === clipId) result.push(i); });
    return result;
}

function isContiguous(indexes) {
    for (let i = 1; i < indexes.length; i++) {
        if (indexes[i] !== indexes[i - 1] + 1) return false;
    }
    return true;
}

function clipDisplayName(clip) {
    return clip.label || (clip.path ? clip.path.split('/').pop() : clip.id);
}

function addAudioClip() {
    clipSeq++;
    audioClips.push({ id: 'a' + clipSeq, label: '', path: null, input_name: null, url: null });
    renderAudioClips();
}

function removeAudioClip(id) {
    const used = clipUsageIndexes(id);
    if (used.length) {
        const nums = used.map(i => i + 1).join(', ');
        if (!confirm(`File này đang được dùng ở câu ${nums}. Xoá sẽ bỏ audio của các câu đó. Tiếp tục?`)) return;
    }

    const clip = audioClips.find(c => c.id === id);
    if (clip) {
        if (clip.input_name) {
            const held = document.querySelector('#mediaFilesHolder input[name="' + clip.input_name + '"]');
            if (held) held.remove();
        }
        if (clip.url && String(clip.url).startsWith('blob:')) URL.revokeObjectURL(clip.url);
    }

    audioClips = audioClips.filter(c => c.id !== id);
    questions.forEach(q => {
        if (q.audio_clip_id === id) { q.audio_clip_id = null; q.has_audio = false; }
    });

    renderAudioClips();
    render();
}

function updateClipLabel(id, value) {
    const clip = audioClips.find(c => c.id === id);
    if (clip) clip.label = value;
    // Không render lại ở đây để không mất con trỏ khi đang gõ.
}

function onClipFileSelected(clipId, inputEl) {
    const file = inputEl.files && inputEl.files[0];
    if (!file) return;

    const clip = audioClips.find(c => c.id === clipId);
    if (!clip) return;

    // Gỡ input của lần chọn trước để không upload thừa file đã bị thay thế.
    if (clip.input_name) {
        const previous = document.querySelector('#mediaFilesHolder input[name="' + clip.input_name + '"]');
        if (previous) previous.remove();
    }
    if (clip.url && String(clip.url).startsWith('blob:')) URL.revokeObjectURL(clip.url);

    const inputName = 'clip_audio_' + (++uploadSeq);
    inputEl.name = inputName;
    document.getElementById('mediaFilesHolder').appendChild(inputEl);

    clip.input_name = inputName;
    clip.url = URL.createObjectURL(file);
    if (!clip.label) clip.label = file.name;

    renderAudioClips();
    render(); // cập nhật tên file hiển thị trong select của từng câu
}

function renderAudioClips() {
    const wrap = document.getElementById('audioClipsContainer');

    if (!audioClips.length) {
        wrap.innerHTML = '<div class="text-muted mb-2">Chưa có file audio nào. Bấm "Thêm file audio" nếu đề có phần Listening.</div>';
        return;
    }

    wrap.innerHTML = audioClips.map((clip, idx) => {
        const used = clipUsageIndexes(clip.id);
        let usageBadge;

        if (!used.length) {
            usageBadge = '<span class="badge badge-secondary">Chưa gán cho câu nào</span>';
        } else {
            const nums = used.map(i => i + 1).join(', ');
            usageBadge = `<span class="badge badge-info">Dùng cho câu ${nums}</span>`;
            if (used.length > 1 && !isContiguous(used)) {
                usageBadge += ' <span class="badge badge-warning">Các câu không liền nhau</span>';
            }
        }

        const fileLabel = clip.path && !clip.input_name
            ? 'File (MP3) <small class="text-muted">— giữ file hiện có nếu không chọn file mới</small>'
            : 'File (MP3) *';

        return `<div class="pt-clip-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="pt-q-badge"><i class="fas fa-headphones mr-4"></i>File audio ${idx + 1}</span>
                <button type="button" class="pt-remove-btn" onclick="removeAudioClip('${clip.id}')" title="Xoá file"><i class="fas fa-trash"></i></button>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label class="input-label">Tên gợi nhớ</label>
                    <input type="text" class="form-control" value="${escapeHtml(clip.label || '')}" placeholder="VD: Audio câu 9-10" oninput="updateClipLabel('${clip.id}', this.value)">
                </div>
                <div class="col-md-7">
                    <label class="input-label">${fileLabel}</label>
                    <input type="file" accept="audio/*" class="form-control" onchange="onClipFileSelected('${clip.id}', this)">
                </div>
            </div>
            <audio controls style="width:100%;margin-top:10px;${clip.url ? '' : 'display:none;'}" src="${clip.url || ''}"></audio>
            <div class="mt-2">${usageBadge}</div>
        </div>`;
    }).join('');
}

function audioClipSelectHtml(index, q) {
    const requiresAudio = q.type === 'listening_image_choice';

    if (!audioClips.length) {
        return `<label class="input-label">File audio${requiresAudio ? ' *' : ''}</label>
            <div class="form-control-plaintext text-muted" style="font-size:13px;">
                Chưa có file audio. Thêm ở khối <strong>File audio</strong> phía trên.
            </div>`;
    }

    const emptyLabel = requiresAudio ? '— Chưa chọn —' : '— Không có audio —';
    const options = audioClips.map(clip =>
        `<option value="${clip.id}" ${q.audio_clip_id === clip.id ? 'selected' : ''}>${escapeHtml(clipDisplayName(clip))}</option>`
    ).join('');

    return `<label class="input-label">File audio${requiresAudio ? ' *' : ''}</label>
        <select class="form-control" onchange="setQuestionClip(${index}, this.value || null)">
            <option value="" ${!q.audio_clip_id ? 'selected' : ''}>${emptyLabel}</option>
            ${options}
        </select>`;
}

/* Thông báo ngay dưới câu hỏi: nhóm đang gộp mấy câu, hoặc cảnh báo khi
   các câu dùng chung file lại không nằm liền nhau (sẽ vỡ thành nhiều player). */
function audioGroupNoticeHtml(index, q) {
    if (!q.audio_clip_id) {
        return q.type === 'listening_image_choice'
            ? '<div class="pt-audio-note pt-audio-note--warn">Dạng Listening bắt buộc phải chọn file audio.</div>'
            : '';
    }

    const clip = audioClips.find(c => c.id === q.audio_clip_id);
    if (!clip) {
        return '<div class="pt-audio-note pt-audio-note--error">File audio đã bị xoá. Hãy chọn lại file khác.</div>';
    }

    const used = clipUsageIndexes(q.audio_clip_id);
    if (used.length <= 1) return '';

    const nums = used.map(i => i + 1).join(', ');

    if (isContiguous(used)) {
        return `<div class="pt-audio-note pt-audio-note--ok">
            <i class="fas fa-link mr-4"></i>Dùng chung với câu ${nums} — học viên chỉ thấy <strong>1 trình phát</strong> cho cả nhóm.
        </div>`;
    }

    return `<div class="pt-audio-note pt-audio-note--warn">
        <i class="fas fa-exclamation-triangle mr-4"></i>File này dùng ở câu ${nums} nhưng các câu <strong>không liền nhau</strong>,
        nên sẽ hiện thành nhiều trình phát riêng. Dùng nút <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i> để xếp các câu này cạnh nhau.
    </div>`;
}

/* ── Render ────────────────────────────────────────────────────────────── */

function render() {
    countLabel.textContent = questions.length;
    container.innerHTML = questions.map((q, index) => renderQuestionCard(q, index)).join('');
    renderPassages();
    renderAudioClips();
}

function renderQuestionCard(q, index) {
    let bodyHtml = '';

    if (q.type === 'multiple_choice') {
        bodyHtml += '<div class="mb-2"><label class="input-label">Lựa chọn (tick vào ô đúng)</label>';
        (q.options || []).forEach((opt, optIndex) => {
            bodyHtml += `<div class="pt-option-row">
                <input type="radio" name="correct_${index}" ${q.correct_answer === opt && opt !== '' ? 'checked' : ''} onchange="setCorrectOption(${index}, this.closest('.pt-option-row').querySelector('.pt-option-text').value)">
                <input type="text" class="pt-option-text" value="${escapeHtml(opt)}" placeholder="Nội dung lựa chọn" oninput="updateOption(${index}, ${optIndex}, this.value)">
                <button type="button" class="pt-remove-btn" onclick="removeOption(${index}, ${optIndex})"><i class="fas fa-times"></i></button>
            </div>`;
        });
        bodyHtml += `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOption(${index})"><i class="fas fa-plus mr-4"></i>Thêm lựa chọn</button></div>
        ${passageSelectHtml(index, q)}`;

    } else if (q.type === 'sentence_completion') {
        const blankCount = countBlanks(q.question_text);
        bodyHtml += `<div class="form-group mb-2">
            <label class="input-label">Word Bank <small class="text-muted">(tuỳ chọn — cách nhau bởi dấu phẩy, hiển thị như hộp từ gợi ý)</small></label>
            <input type="text" class="form-control" value="${escapeHtml((q.word_bank || []).join(', '))}" placeholder="go, do, learn, make, take" oninput="updateWordBank(${index}, this.value)">
        </div>`;
        bodyHtml += `<div class="alert alert-info py-2 px-3 mb-2">Dùng <code>___</code> (ít nhất 2 dấu gạch dưới liền nhau) cho mỗi chỗ trống. Phát hiện <strong>${blankCount}</strong> chỗ trống.</div>`;
        if (blankCount > 0) {
            const existingAnswers = Array.isArray(q.correct_answer) ? q.correct_answer : [];
            const existingHints = Array.isArray(q.blank_hints) ? q.blank_hints : [];
            bodyHtml += '<div class="mb-2">';
            for (let b = 0; b < blankCount; b++) {
                const val = (existingAnswers[b] || []).join(' / ');
                const hint = existingHints[b] || '';
                bodyHtml += `<div class="pt-blank-answer-row">
                    <span>Chỗ trống ${b + 1}</span>
                    <input type="text" value="${escapeHtml(val)}" placeholder="Đáp án đúng, cách nhau bởi / nếu có nhiều đáp án" oninput="updateBlankAnswer(${index}, ${b}, this.value)">
                    <input type="text" style="max-width:150px;" value="${escapeHtml(hint)}" placeholder="Gợi ý, vd: move" oninput="updateBlankHint(${index}, ${b}, this.value)">
                </div>`;
            }
            bodyHtml += '</div>';
        }
        bodyHtml += passageSelectHtml(index, q);

    } else if (q.type === 'error_correction') {
        bodyHtml += `<div class="form-group">
            <label class="input-label">Câu đúng hoàn chỉnh * <small class="text-muted">(so khớp không phân biệt hoa/thường)</small></label>
            <input type="text" class="form-control" value="${escapeHtml(q.correct_answer_text || '')}" placeholder="VD: She goes to school every day by bus because it is very fast and cheap." oninput="updateField(${index}, 'correct_answer_text', this.value)">
        </div>`;

    } else if (q.type === 'listening_image_choice') {
        bodyHtml += '<div class="row">';
        ['A', 'B', 'C'].forEach((letter, optIndex) => {
            const previewUrl = (q.option_image_urls || [])[optIndex] || '';
            bodyHtml += `<div class="col-md-4 mb-2">
                <label class="input-label">Ảnh ${letter} *</label>
                <div class="d-flex align-items-center mb-1">
                    <input type="radio" name="correct_img_${index}" ${q.correct_answer === letter ? 'checked' : ''} onchange="setCorrectImage(${index}, '${letter}')">
                    <span class="ml-2 small text-muted">Đây là đáp án đúng</span>
                </div>
                <img id="option-img-preview-${index}-${optIndex}" src="${previewUrl}" style="width:100%;max-height:110px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;${previewUrl ? '' : 'display:none;'}">
                <input type="file" accept="image/*" class="form-control form-control-sm mt-2" onchange="onOptionImageSelected(${index}, ${optIndex}, this)">
            </div>`;
        });
        bodyHtml += '</div>';
    }

    const answerHelpBlock = `
        <div class="form-group mt-2">
            <label class="input-label">Giải thích đáp án <small class="text-muted">(chỉ Manager/CEO thấy khi xem chi tiết bài làm học viên — học viên không bao giờ thấy)</small></label>
            <textarea class="form-control" rows="4" placeholder="VD: Dùng 'have been' vì đây là thì hiện tại hoàn thành tiếp diễn, diễn tả hành động bắt đầu trong quá khứ và vẫn tiếp diễn." oninput="updateField(${index}, 'answer_help', this.value)">${escapeHtml(q.answer_help || '')}</textarea>
        </div>
    `;

    return `<div class="pt-question-card">
        <span class="pt-q-badge">Câu ${index + 1} · ${TYPE_LABELS[q.type] || q.type}</span>

        <div class="pt-move-btns">
            <button type="button" onclick="moveQuestion(${index}, -1)" ${index === 0 ? 'disabled' : ''} title="Chuyển lên trên"><i class="fas fa-arrow-up"></i></button>
            <button type="button" onclick="moveQuestion(${index}, 1)" ${index === questions.length - 1 ? 'disabled' : ''} title="Chuyển xuống dưới"><i class="fas fa-arrow-down"></i></button>
        </div>
        <button type="button" class="pt-remove-btn" style="position:absolute;top:16px;right:16px;font-size:16px;" onclick="removeQuestion(${index})" title="Xoá câu"><i class="fas fa-trash"></i></button>

        <div class="row mb-2">
            <div class="col-md-4">
                <label class="input-label">Dạng câu hỏi</label>
                <select class="form-control" onchange="changeType(${index}, this.value)">
                    ${Object.entries(TYPE_LABELS).map(([val, label]) => `<option value="${val}" ${q.type === val ? 'selected' : ''}>${label}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <label class="input-label">Điểm</label>
                <input type="number" class="form-control" min="0" step="0.25" value="${q.points ?? 1}" oninput="updateField(${index}, 'points', parseFloat(this.value) || 0)">
            </div>
            <div class="col-md-4">
                ${audioClipSelectHtml(index, q)}
            </div>
        </div>

        ${audioGroupNoticeHtml(index, q)}

        <div class="form-group">
            <label class="input-label">${QUESTION_TEXT_LABELS[q.type] || 'Nội dung câu hỏi *'}</label>
            <textarea class="form-control" rows="4" oninput="updateField(${index}, 'question_text', this.value); refreshBlankCount(${index}, this.value)">${escapeHtml(q.question_text)}</textarea>
        </div>

        ${bodyHtml}
        ${answerHelpBlock}
    </div>`;
}

// Với sentence completion, số chỗ trống thay đổi ngay khi gõ text -> cần render lại phần đáp án.
// Debounce nhẹ để không giật khi đang gõ.
let refreshTimer = null;
function refreshBlankCount(index, text) {
    questions[index].question_text = text;
    if (questions[index].type !== 'sentence_completion') return;
    clearTimeout(refreshTimer);
    refreshTimer = setTimeout(() => {
        const activeEl = document.activeElement;
        const cursorPos = activeEl && activeEl.tagName === 'TEXTAREA' ? activeEl.selectionStart : null;
        render();
        const textarea = container.querySelectorAll('.pt-question-card')[index]?.querySelector('textarea');
        if (textarea && cursorPos !== null) {
            textarea.focus();
            textarea.setSelectionRange(cursorPos, cursorPos);
        }
    }, 600);
}

document.getElementById('btnAddQuestion').addEventListener('click', addQuestion);
document.getElementById('btnAddPassage').addEventListener('click', addPassage);
document.getElementById('btnAddAudioClip').addEventListener('click', addAudioClip);

document.getElementById('placementTestForm').addEventListener('submit', function (e) {
    if (questions.length === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất 1 câu hỏi.');
        return;
    }

    // ── Kiểm tra các file audio ──
    for (let i = 0; i < audioClips.length; i++) {
        const clip = audioClips[i];
        if (!clip.path && !clip.input_name) {
            e.preventDefault();
            alert('File audio ' + (i + 1) + ' (' + (clip.label || clip.id) + ') chưa chọn file.');
            return;
        }
    }

    const unusedClips = audioClips.filter(c => clipUsageIndexes(c.id).length === 0);
    if (unusedClips.length) {
        const names = unusedClips.map(c => clipDisplayName(c)).join(', ');
        if (!confirm('Các file audio sau chưa được gán cho câu nào: ' + names + '.\n\nVẫn lưu?')) {
            e.preventDefault();
            return;
        }
    }

    const brokenGroups = audioClips.filter(c => {
        const used = clipUsageIndexes(c.id);
        return used.length > 1 && !isContiguous(used);
    });
    if (brokenGroups.length) {
        const detail = brokenGroups
            .map(c => clipDisplayName(c) + ' → câu ' + clipUsageIndexes(c.id).map(i => i + 1).join(', '))
            .join('\n');
        if (!confirm('Các file audio sau đang dùng cho những câu KHÔNG liền nhau:\n\n' + detail
            + '\n\nHọc viên sẽ thấy nhiều trình phát riêng thay vì 1. Vẫn lưu?')) {
            e.preventDefault();
            return;
        }
    }

    // ── Kiểm tra từng câu hỏi ──
    for (let i = 0; i < questions.length; i++) {
        const q = questions[i];

        if (q.type !== 'listening_image_choice' && (!q.question_text || !q.question_text.trim())) {
            e.preventDefault();
            alert('Câu ' + (i + 1) + ' chưa có nội dung.');
            return;
        }

        if (q.audio_clip_id && !audioClips.some(c => c.id === q.audio_clip_id)) {
            e.preventDefault();
            alert('Câu ' + (i + 1) + ' đang trỏ tới file audio đã bị xoá. Hãy chọn lại.');
            return;
        }

        if (q.type === 'multiple_choice') {
            if (!q.correct_answer) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' chưa chọn đáp án đúng.');
                return;
            }
            if (q.linked_passage_id) {
                const p = passages.find(p => p.id === q.linked_passage_id);
                if (!p || !p.content.trim()) {
                    e.preventDefault();
                    alert('Câu ' + (i + 1) + ' gắn với đoạn văn nhưng đoạn văn đó chưa có nội dung.');
                    return;
                }
            }
        } else if (q.type === 'sentence_completion') {
            const blanks = countBlanks(q.question_text);
            if (blanks === 0) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' (Sentence Completion) chưa có chỗ trống ___.');
                return;
            }
            if (!Array.isArray(q.correct_answer) || q.correct_answer.length !== blanks || q.correct_answer.some(a => !a || !a.length)) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' chưa nhập đủ đáp án cho từng chỗ trống.');
                return;
            }
            if (q.linked_passage_id) {
                const p = passages.find(p => p.id === q.linked_passage_id);
                if (!p || !p.content.trim()) {
                    e.preventDefault();
                    alert('Câu ' + (i + 1) + ' gắn với đoạn văn nhưng đoạn văn đó chưa có nội dung.');
                    return;
                }
            }
        } else if (q.type === 'error_correction') {
            if (!q.correct_answer_text || !q.correct_answer_text.trim()) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' (Find & Correct the Mistake) chưa nhập câu đúng.');
                return;
            }
        } else if (q.type === 'listening_image_choice') {
            if (!q.question_text || !q.question_text.trim()) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' chưa có nội dung câu hỏi.');
                return;
            }
            for (let opt = 0; opt < 3; opt++) {
                if (!q.option_image_input_names[opt] && !q.existing_option_images[opt]) {
                    e.preventDefault();
                    alert('Câu ' + (i + 1) + ' còn thiếu ảnh cho lựa chọn ' + String.fromCharCode(65 + opt) + '.');
                    return;
                }
            }
            if (!q.correct_answer) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' chưa chọn ảnh đáp án đúng.');
                return;
            }
            if (!q.audio_clip_id) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' (Listening - Choose the Image) cần chọn file audio.');
                return;
            }
        }
    }

    // Chỉ gửi các trường server cần; bỏ `url` vì đó là blob URL tạm của trình duyệt.
    const clipsPayload = audioClips.map(c => ({
        id: c.id,
        label: c.label || '',
        path: c.path || null,
        input_name: c.input_name || null,
    }));

    document.getElementById('questionsDataInput').value = JSON.stringify(questions);
    document.getElementById('passagesDataInput').value = JSON.stringify(passages);
    document.getElementById('audioClipsDataInput').value = JSON.stringify(clipsPayload);
});

render();
</script>


@endsection