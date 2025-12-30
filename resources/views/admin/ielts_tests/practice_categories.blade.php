@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Practice Categories</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item">Practice Categories</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h4 class="mb-0">Manage Practice Categories</h4>
                    <p class="text-gray mb-0 mt-1">Organize practice tests by skill and topic</p>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                    <i class="fas fa-plus mr-2"></i>
                    Add Category
                </button>
            </div>
            <div class="card-body">
                @php
                    $groupedCategories = $categories->groupBy('skill');
                @endphp

                @foreach(['listening', 'reading', 'writing', 'speaking'] as $skill)
                    @if($groupedCategories->has($skill))
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-{{ $skill === 'listening' ? 'headphones' : ($skill === 'reading' ? 'book-open' : ($skill === 'writing' ? 'pencil-alt' : 'microphone')) }} mr-2"></i>
                            {{ ucfirst($skill) }} Categories
                            <span class="badge badge-light ml-2">{{ $groupedCategories[$skill]->count() }}</span>
                        </h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Tests</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedCategories[$skill] as $category)
                                    <tr>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>{{ Str::limit($category->description, 50) }}</td>
                                        <td>{{ $category->practiceTests->count() }} tests</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editCategory({{ $category->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.practice_categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Delete this category? Tests will not be deleted.')"
                                                        {{ $category->practiceTests->count() > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                @endforeach

                @if($categories->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-gray mb-3"></i>
                        <h5>No categories yet</h5>
                        <p class="text-gray">Create your first practice category</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Practice Category</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.practice_categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Skill *</label>
                        <select name="skill" class="form-control" required>
                            <option value="">Select skill...</option>
                            <option value="listening">Listening</option>
                            <option value="reading">Reading</option>
                            <option value="writing">Writing</option>
                            <option value="speaking">Speaking</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required>
                        <small class="text-gray">e.g., "Table Completion", "Sentence Completion"</small>
                    </div>

                    <div class="form-group">
                        <label>Slug *</label>
                        <input type="text" name="slug" class="form-control" required>
                        <small class="text-gray">URL-friendly name, e.g., "table-completion"</small>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Icon (Font Awesome)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-table">
                    </div>

                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
function editCategory(categoryId) {
    alert('Edit functionality coming soon. Use delete and re-create for now.');
}
</script>
@endpush
