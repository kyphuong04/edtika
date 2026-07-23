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
                <textarea name="description" class="form-control" rows="2">{{ old('description', $placementTest->description ?? '') }}</textarea>
            </div>
            <div class="form-group mb-0 mt-3">
                <label class="input-label">
                    Reading Passage <small class="text-muted">(đoạn văn dùng chung — tối đa 1 đoạn/đề, dùng cho các câu Multiple Choice có tick "Gắn với đoạn văn")</small>
                </label>
                <textarea name="reading_passage" id="readingPassageInput" class="form-control" rows="4" placeholder="VD: Ben lives in a small town near the sea...">{{ old('reading_passage', $placementTest->reading_passage ?? '') }}</textarea>
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
    </div>{{-- /ptTabsContent --}}

    </div>{{-- /section-body --}}
</section>

<script>
const MAX_QUESTIONS = {{ \App\Models\PlacementTest::MAX_QUESTIONS }};
let questions = @json($questionsData ?? []);
let uploadSeq = 0;

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
        linked_to_passage: false,
        question_text: '',
        options: ['', ''],
        word_bank: [],
        blank_hints: [],
        correct_answer: null,
        correct_answer_text: '',
        points: 1,
        audio_input_name: null,
        existing_audio_path: null,
        audio_url: null,
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
        has_audio: type === 'listening_image_choice' ? true : questions[index].has_audio,
        audio_input_name: questions[index].audio_input_name,
        existing_audio_path: questions[index].existing_audio_path,
        audio_url: questions[index].audio_url,
    };
    render();
}

function toggleAudio(index, checked) {
    questions[index].has_audio = checked;
    render();
}

function toggleLinkedToPassage(index, checked) {
    questions[index].linked_to_passage = checked;
}

// function onAudioFileSelected(index, inputEl) {
//     if (inputEl.files && inputEl.files[0]) {
//         const inputName = 'question_audio_' + (++uploadSeq);
//         inputEl.name = inputName;
//         questions[index].audio_input_name = inputName;
//         document.getElementById('mediaFilesHolder').appendChild(inputEl);
//         const badge = document.getElementById('audio-filename-' + index);
//         if (badge) { badge.textContent = inputEl.files[0].name; badge.style.display = 'inline-flex'; }
//         const placeholder = document.createElement('input');
//         placeholder.type = 'file';
//         placeholder.accept = 'audio/*';
//         placeholder.className = 'form-control';
//         placeholder.onchange = function () { onAudioFileSelected(index, this); };
//         inputEl.insertAdjacentElement('afterend', placeholder);
//     }
// }

function onAudioFileSelected(index, inputEl) {
    if (inputEl.files && inputEl.files[0]) {
        const file = inputEl.files[0];
        const inputName = 'question_audio_' + (++uploadSeq);
        inputEl.name = inputName;
        questions[index].audio_input_name = inputName;
        document.getElementById('mediaFilesHolder').appendChild(inputEl);

        const badge = document.getElementById('audio-filename-' + index);
        if (badge) { badge.textContent = file.name; badge.style.display = 'inline-flex'; }

        // Tạo URL tạm để nghe thử ngay trên trình duyệt, không cần chờ submit
        // form/upload lên server. Thu hồi URL cũ (nếu có) để tránh rò rỉ bộ nhớ
        // khi người dùng đổi qua đổi lại nhiều file audio trước khi lưu.
        const preview = document.getElementById('audio-preview-' + index);
        if (preview) {
            if (preview.dataset.blobUrl) {
                URL.revokeObjectURL(preview.dataset.blobUrl);
            }
            const objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            preview.dataset.blobUrl = objectUrl;
            preview.style.display = 'block';
        }

        const placeholder = document.createElement('input');
        placeholder.type = 'file';
        placeholder.accept = 'audio/*';
        placeholder.className = 'form-control';
        placeholder.onchange = function () { onAudioFileSelected(index, this); };
        inputEl.insertAdjacentElement('afterend', placeholder);
    }
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

function render() {
    countLabel.textContent = questions.length;
    container.innerHTML = questions.map((q, index) => renderQuestionCard(q, index)).join('');
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
        <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input" id="passage_check_${index}" ${q.linked_to_passage ? 'checked' : ''} onchange="toggleLinkedToPassage(${index}, this.checked)">
            <label class="form-check-label" for="passage_check_${index}">Gắn với đoạn văn dùng chung (Reading Comprehension) — cần nhập Reading Passage ở trên</label>
        </div>`;

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

    const showAudioToggle = q.type !== 'listening_image_choice';
    const showAudioBlock = q.has_audio || q.type === 'listening_image_choice';

    // const audioBlock = showAudioBlock ? `
    //     <div class="mb-2">
    //         <label class="input-label">File audio (MP3) *</label>
    //         <input type="file" accept="audio/*" class="form-control" onchange="onAudioFileSelected(${index}, this)">
    //         ${q.audio_url ? `<div class="pt-file-preview"><i class="fas fa-volume-up"></i> File hiện có (giữ nguyên nếu không chọn file mới)</div>` : ''}
    //         <div id="audio-filename-${index}" class="pt-file-preview" style="display:none;"></div>
    //     </div>
    // ` : '';

    const audioBlock = showAudioBlock ? `
        <div class="mb-2">
            <label class="input-label">File audio (MP3) *</label>
            <input type="file" accept="audio/*" class="form-control" onchange="onAudioFileSelected(${index}, this)">
            ${q.audio_url ? `<div class="pt-file-preview"><i class="fas fa-volume-up"></i> File hiện có (giữ nguyên nếu không chọn file mới)</div>` : ''}
            <div id="audio-filename-${index}" class="pt-file-preview" style="display:none;"></div>
            <audio id="audio-preview-${index}" controls style="width:100%;margin-top:8px;${q.audio_url ? '' : 'display:none;'}" src="${q.audio_url || ''}"></audio>
        </div>
    ` : '';

    return `<div class="pt-question-card">
        <span class="pt-q-badge">Câu ${index + 1} · ${TYPE_LABELS[q.type] || q.type}</span>
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
            ${showAudioToggle ? `<div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="audio_check_${index}" ${q.has_audio ? 'checked' : ''} onchange="toggleAudio(${index}, this.checked)">
                    <label class="form-check-label" for="audio_check_${index}">Có audio (Listening)</label>
                </div>
            </div>` : `<div class="col-md-4 d-flex align-items-end"><span class="badge badge-info">Listening (bắt buộc audio)</span></div>`}
        </div>

        ${audioBlock}

        <div class="form-group">
            <label class="input-label">${QUESTION_TEXT_LABELS[q.type] || 'Nội dung câu hỏi *'}</label>
            <textarea class="form-control" rows="2" oninput="updateField(${index}, 'question_text', this.value); refreshBlankCount(${index}, this.value)">${escapeHtml(q.question_text)}</textarea>
        </div>

        ${bodyHtml}
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

document.getElementById('placementTestForm').addEventListener('submit', function (e) {
    if (questions.length === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất 1 câu hỏi.');
        return;
    }

    const readingPassage = (document.getElementById('readingPassageInput').value || '').trim();

    for (let i = 0; i < questions.length; i++) {
        const q = questions[i];

        if (q.type !== 'listening_image_choice' && (!q.question_text || !q.question_text.trim())) {
            e.preventDefault();
            alert('Câu ' + (i + 1) + ' chưa có nội dung.');
            return;
        }

        if (q.type === 'multiple_choice') {
            if (!q.correct_answer) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' chưa chọn đáp án đúng.');
                return;
            }
            if (q.linked_to_passage && !readingPassage) {
                e.preventDefault();
                alert('Câu ' + (i + 1) + ' gắn với đoạn văn nhưng bạn chưa nhập Reading Passage ở trên.');
                return;
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
        }

        if ((q.has_audio || q.type === 'listening_image_choice') && !q.audio_input_name && !q.existing_audio_path) {
            e.preventDefault();
            alert('Câu ' + (i + 1) + ' cần file audio nhưng chưa chọn.');
            return;
        }
    }

    document.getElementById('questionsDataInput').value = JSON.stringify(questions);
});

render();
</script>


@endsection