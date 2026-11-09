@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .bundle-vocab-scroll {
        max-height: min(58vh, 560px);
        overflow-y: auto;
        overflow-x: auto;
    }

    .bundle-vocab-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb {
        background: rgba(81, 29, 153, 0.35);
        border-radius: 10px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(81, 29, 153, 0.5);
    }

    .bundle-vocab-pagination .pagination {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .bundle-vocab-pagination .page-item {
        display: inline-flex;
        margin: 0;
    }

    .bundle-vocab-pagination .page-link {
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(81, 29, 153, 0.18);
        color: #511D99;
        background: #fff;
        text-decoration: none;
    }

    .bundle-vocab-pagination .page-item.active .page-link {
        background: #511D99;
        border-color: #511D99;
        color: #fff;
    }

    .bundle-vocab-pagination .page-item.disabled .page-link {
        opacity: 0.45;
        pointer-events: none;
    }

    /* ── Folder bộ từ vựng ─────────────────────────────────── */
    .vf-section { background: #fff; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
    .vf-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .vf-hint { font-size: 12px; color: #64748b; margin: 4px 0 0; }
    .vf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; margin-top: 14px; }
    .vf-card { border: 1px solid rgba(81, 29, 153, 0.14); border-radius: 12px; padding: 12px; background: #fff; }
    .vf-card__top { display: flex; gap: 12px; align-items: flex-start; }
    .vf-card__icon { flex: 0 0 48px; width: 48px; height: 48px; border-radius: 10px; background: #f1ebfa; color: #5B2A86; display: flex; align-items: center; justify-content: center; }
    .vf-card__name { font-size: 14px; font-weight: 700; color: #1e1b4b; margin: 0; word-break: break-word; }
    .vf-card__meta { font-size: 12px; color: #64748b; margin-top: 2px; }
    .vf-card__desc { font-size: 12px; color: #64748b; margin: 6px 0 0; word-break: break-word; }
    .vf-card__actions { display: flex; gap: 8px; align-items: center; margin-top: 10px; flex-wrap: wrap; }
    .vf-card__actions form { margin: 0; }
    .vf-details > summary { list-style: none; cursor: pointer; }
    .vf-details > summary::-webkit-details-marker { display: none; }
    .vf-form { margin-top: 12px; padding: 12px; border-radius: 10px; background: #f8f6fc; }
    .vf-form .form-group { margin-bottom: 10px; }
    .vf-form-row { display: grid; grid-template-columns: 1fr 1fr 90px; gap: 10px; }
    .vf-icons { display: flex; flex-wrap: wrap; gap: 8px; }
    .vf-icon-option { position: relative; margin: 0; cursor: pointer; }
    .vf-icon-option input { position: absolute; opacity: 0; pointer-events: none; }
    .vf-icon-option span { width: 40px; height: 40px; border-radius: 10px; border: 1.5px solid #e3dcf2; background: #fff; color: #5B2A86; display: flex; align-items: center; justify-content: center; }
    .vf-icon-option input:checked + span { border-color: #5B2A86; background: #efe9f8; }
    .vf-icon-option input:focus-visible + span { outline: 2px solid #b49de0; outline-offset: 2px; }
    .vf-empty { margin-top: 14px; padding: 16px; border: 1px dashed #d9cdf0; border-radius: 10px; text-align: center; font-size: 13px; color: #64748b; }
    .vf-folder-tag { display: inline-block; margin-top: 2px; padding: 1px 8px; border-radius: 999px; background: #f1ebfa; color: #5B2A86; font-size: 11px; font-weight: 600; }
    .vf-folder-tag.is-missing { background: #fef3c7; color: #92400e; }
    @media (max-width: 575px) { .vf-form-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-20">
            <div>
                <h2 class="section-title mb-4">{{ trans('panel.bundle_vocabulary_library') }}</h2>
                <p class="text-gray-500 mb-0">{{ trans('panel.bundle_vocabulary_manage_hint') }}</p>
            </div>
            @if($canApprove)
                <div class="d-flex align-items-center flex-wrap gap-8">
                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="btn btn-sm {{ request()->get('status') ? 'btn-outline-primary' : 'btn-primary' }}">{{ trans('panel.bundle_vocabulary_all_statuses') }}</a>
                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage?status=pending') }}" class="btn btn-sm {{ request()->get('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">{{ trans('panel.bundle_vocabulary_pending_only') }}</a>

                    <form method="get" action="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="d-flex align-items-center gap-8">
                    <select name="status" class="form-control">
                        <option value="">{{ trans('panel.bundle_vocabulary_all_statuses') }}</option>
                        <option value="pending" {{ request()->get('status') === 'pending' ? 'selected' : '' }}>{{ trans('panel.bundle_vocabulary_pending_only') }}</option>
                        <option value="approved" {{ request()->get('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request()->get('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="draft" {{ request()->get('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <button class="btn btn-primary" type="submit">{{ trans('public.filter') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Folder bộ từ vựng: teacher tự tạo, học viên thấy ở tab Academic Word List ── --}}
    <div class="col-12">
        <div class="vf-section" id="vocabularyFolders">
            <div class="vf-head">
                <div>
                    <h4 class="font-16 mb-0">Folder bộ từ vựng</h4>
                    <p class="vf-hint">Học viên thấy các bộ từ được xếp theo folder ở tab Academic Word List. Mỗi bộ từ bắt buộc thuộc 1 folder của bạn; tạo folder không cần duyệt.</p>
                </div>
            </div>

            @if($errors->has('folder_name') || $errors->has('folder_delete') || $errors->has('folder_level') || $errors->has('folder_icon'))
                <div class="alert alert-danger mt-12 mb-0">
                    {{ $errors->first('folder_name') ?: ($errors->first('folder_delete') ?: ($errors->first('folder_level') ?: $errors->first('folder_icon'))) }}
                </div>
            @endif

            @if($vocabularyFolders->isEmpty())
                <div class="vf-empty">Bạn chưa có folder nào. Tạo folder đầu tiên để có thể đăng bộ từ vựng.</div>
            @else
                <div class="vf-grid">
                    @foreach($vocabularyFolders as $folderItem)
                        <div class="vf-card">
                            <div class="vf-card__top">
                                <div class="vf-card__icon">@include('design_1.panel.dictionary.partials.folder_icon', ['icon' => $folderItem->icon, 'size' => 26])</div>
                                <div style="min-width:0;">
                                    <p class="vf-card__name">{{ $folderItem->name }}</p>
                                    <div class="vf-card__meta">
                                        {{ $folderItem->level ?: 'Mọi trình độ' }}
                                        @if($folderItem->skill) · {{ $folderItem->skill }} @endif
                                        · {{ $folderItem->sets_count }} bộ từ
                                    </div>
                                    @if($folderItem->description)
                                        <p class="vf-card__desc">{{ $folderItem->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <details class="vf-details">
                                <summary class="vf-card__actions">
                                    <span class="btn btn-sm btn-outline-primary">{{ trans('public.edit') }}</span>
                                </summary>
                                <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/folders/'.$folderItem->id.'/update') }}" class="vf-form">
                                    {{ csrf_field() }}
                                    @php $__f = $folderItem; @endphp
                    <div class="form-group">
                        <label class="input-label">Tên folder <span class="text-danger">*</span></label>
                        <input type="text" name="folder_name" class="form-control" maxlength="120" value="{{ $__f ? $__f->name : old('folder_name') }}" placeholder="Ví dụ: CEFR Vocabulary — A1" required>
                    </div>
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.description') }}</label>
                        <textarea name="folder_description" class="form-control" rows="2" maxlength="500" placeholder="Folder dành cho ai, học gì...">{{ $__f ? $__f->description : old('folder_description') }}</textarea>
                    </div>
                    <div class="vf-form-row">
                        <div class="form-group">
                            <label class="input-label">Trình độ</label>
                            <select name="folder_level" class="form-control">
                                <option value="">Mọi trình độ</option>
                                @foreach($folderLevels as $lv)
                                    <option value="{{ $lv }}" {{ ($__f ? $__f->level : old('folder_level')) === $lv ? 'selected' : '' }}>{{ $lv }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="input-label">Kỹ năng</label>
                            <input type="text" name="folder_skill" class="form-control" maxlength="100" value="{{ $__f ? $__f->skill : old('folder_skill') }}" placeholder="IELTS Vocabulary">
                        </div>
                        <div class="form-group">
                            <label class="input-label">Thứ tự</label>
                            <input type="number" name="folder_sort_order" class="form-control" min="0" max="9999" value="{{ $__f ? $__f->sort_order : old('folder_sort_order', 0) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label">Icon</label>
                        <div class="vf-icons">
                            @foreach($folderIcons as $iconKey => $iconLabel)
                                <label class="vf-icon-option" title="{{ $iconLabel }}">
                                    <input type="radio" name="folder_icon" value="{{ $iconKey }}" {{ ($__f ? $__f->icon : old('folder_icon', 'book')) === $iconKey ? 'checked' : '' }}>
                                    <span>@include('design_1.panel.dictionary.partials.folder_icon', ['icon' => $iconKey, 'size' => 22])</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                                </form>
                            </details>

                            <div class="vf-card__actions">
                                <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/folders/'.$folderItem->id.'/delete') }}"
                                      onsubmit="return confirm('Xoá folder &quot;{{ addslashes($folderItem->name) }}&quot;?');">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-outline-danger" @if($folderItem->sets_count > 0) disabled title="Folder còn bộ từ, hãy chuyển bộ từ sang folder khác trước khi xoá" @endif>{{ trans('public.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <details class="vf-details mt-12" {{ ($errors->has('folder_name') && !old('folder_id_editing')) || $vocabularyFolders->isEmpty() ? 'open' : '' }}>
                <summary><span class="btn btn-sm btn-primary">+ Tạo folder mới</span></summary>
                <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/folders/store') }}" class="vf-form">
                    {{ csrf_field() }}
                    @php $__f = null; @endphp
                    <div class="form-group">
                        <label class="input-label">Tên folder <span class="text-danger">*</span></label>
                        <input type="text" name="folder_name" class="form-control" maxlength="120" value="{{ $__f ? $__f->name : old('folder_name') }}" placeholder="Ví dụ: CEFR Vocabulary — A1" required>
                    </div>
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.description') }}</label>
                        <textarea name="folder_description" class="form-control" rows="2" maxlength="500" placeholder="Folder dành cho ai, học gì...">{{ $__f ? $__f->description : old('folder_description') }}</textarea>
                    </div>
                    <div class="vf-form-row">
                        <div class="form-group">
                            <label class="input-label">Trình độ</label>
                            <select name="folder_level" class="form-control">
                                <option value="">Mọi trình độ</option>
                                @foreach($folderLevels as $lv)
                                    <option value="{{ $lv }}" {{ ($__f ? $__f->level : old('folder_level')) === $lv ? 'selected' : '' }}>{{ $lv }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="input-label">Kỹ năng</label>
                            <input type="text" name="folder_skill" class="form-control" maxlength="100" value="{{ $__f ? $__f->skill : old('folder_skill') }}" placeholder="IELTS Vocabulary">
                        </div>
                        <div class="form-group">
                            <label class="input-label">Thứ tự</label>
                            <input type="number" name="folder_sort_order" class="form-control" min="0" max="9999" value="{{ $__f ? $__f->sort_order : old('folder_sort_order', 0) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label">Icon</label>
                        <div class="vf-icons">
                            @foreach($folderIcons as $iconKey => $iconLabel)
                                <label class="vf-icon-option" title="{{ $iconLabel }}">
                                    <input type="radio" name="folder_icon" value="{{ $iconKey }}" {{ ($__f ? $__f->icon : old('folder_icon', 'book')) === $iconKey ? 'checked' : '' }}>
                                    <span>@include('design_1.panel.dictionary.partials.folder_icon', ['icon' => $iconKey, 'size' => 22])</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Tạo folder</button>
                </form>
            </details>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="bg-white rounded-12 p-16 mb-20">
            <h4 class="font-16 mb-12">{{ trans('panel.bundle_vocabulary_create_new') }}</h4>
            <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_bundle') }}</label>
                    <select name="bundle_id" class="form-control" required>
                        <option value="">Select bundle</option>
                        @foreach($availableBundles as $bundle)
                            <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>#{{ $bundle->id }} - {{ $bundle->slug }}</option>
                        @endforeach
                    </select>
                    @error('bundle_id')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Folder <span class="text-danger">*</span></label>
                    <select name="folder_id" class="form-control" required {{ $vocabularyFolders->isEmpty() ? 'disabled' : '' }}>
                        <option value="">Chọn folder</option>
                        @foreach($vocabularyFolders as $folderItem)
                            <option value="{{ $folderItem->id }}" {{ (string) old('folder_id') === (string) $folderItem->id ? 'selected' : '' }}>{{ $folderItem->name }}{{ $folderItem->level ? ' ('.$folderItem->level.')' : '' }}</option>
                        @endforeach
                    </select>
                    @if($vocabularyFolders->isEmpty())
                        <p class="font-12 text-warning mt-6 mb-0">Hãy <a href="#vocabularyFolders">tạo folder</a> trước khi đăng bộ từ vựng.</p>
                    @endif
                    @error('folder_id')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_set_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('public.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_intro_content') }}</label>
                    <textarea name="intro_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_intro_content_placeholder') }}">{{ old('intro_content') }}</textarea>
                    @error('intro_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_feature_content') }}</label>
                    <textarea name="feature_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_feature_content_placeholder') }}">{{ old('feature_content') }}</textarea>
                    @error('feature_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_source_file') }}</label>
                    <input type="file" name="source_file" class="form-control" accept=".csv,.txt,.xls,.xlsx" required>
                    <p class="font-12 text-gray-500 mt-8 mb-0">{{ trans('panel.bundle_vocabulary_file_template_hint') }}</p>
                    @error('source_file')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('public.save') }}</button>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="bg-white rounded-12 p-16">
            <h4 class="font-16 mb-12">{{ trans('public.list') }}</h4>

            <div class="table-responsive bundle-vocab-scroll">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('panel.bundle_vocabulary_set_name') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_bundle') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_status') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_words_count') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sets as $set)
                            <tr>
                                <td>{{ $set->id }}</td>
                                <td>
                                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id) }}" class="text-dark">{{ $set->name }}</a>
                                    <div class="text-gray-500 font-12">{{ !empty($set->creator) ? $set->creator->full_name : '-' }}</div>
                                    @if(!empty($set->folder))
                                        <span class="vf-folder-tag">{{ $set->folder->name }}</span>
                                    @else
                                        <span class="vf-folder-tag is-missing">Chưa chọn folder</span>
                                    @endif
                                </td>
                                <td>{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}</td>
                                <td>
                                    <span class="badge {{ $set->status === 'approved' ? 'badge-success' : ($set->status === 'pending' ? 'badge-warning' : ($set->status === 'rejected' ? 'badge-danger' : 'badge-secondary')) }}">
                                        {{ ucfirst($set->status) }}
                                    </span>
                                </td>
                                <td>{{ $set->words_count }}</td>
                                <td>
                                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id) }}" class="btn btn-sm btn-outline-primary">{{ trans('public.edit') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500">{{ trans('public.no_result') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-16 bundle-vocab-pagination">{{ $sets->appends(request()->all())->links() }}</div>
        </div>
    </div>
</div>
@endsection
