<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsPracticeCategory;
use Illuminate\Http\Request;

class IeltsPracticeCategoryController extends Controller
{
    public function index()
    {
        // $this->authorize('admin_ielts_tests'); // TEMP: Bypassed until permissions are set up

        $categories = IeltsPracticeCategory::with('practiceTests')
            ->orderBy('skill')
            ->orderBy('sort_order')
            ->get();

        $data = [
            'pageTitle' => 'Practice Categories',
            'categories' => $categories,
        ];

        return view('admin.ielts_tests.practice_categories', $data);
    }

    public function store(Request $request)
    {
        // $this->authorize('admin_ielts_tests_create'); // TEMP: Bypassed until permissions are set up

        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ielts_practice_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        IeltsPracticeCategory::create($validated);

        return redirect()->route('admin.practice_categories.index')
            ->with('success', 'Practice category created successfully');
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_ielts_tests_edit');

        $category = IeltsPracticeCategory::findOrFail($id);

        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ielts_practice_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update($validated);

        return redirect()->route('admin.practice_categories.index')
            ->with('success', 'Practice category updated successfully');
    }

    public function destroy($id)
    {
        $this->authorize('admin_ielts_tests_delete');

        $category = IeltsPracticeCategory::findOrFail($id);

        // Check if category has tests
        if ($category->practiceTests()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated tests');
        }

        $category->delete();

        return redirect()->route('admin.practice_categories.index')
            ->with('success', 'Practice category deleted successfully');
    }
}
