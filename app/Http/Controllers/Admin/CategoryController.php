<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->withCount('products')->orderBy('sort_order')->paginate(12);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        Category::query()->create($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category->id);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('status', 'Category deleted.');
    }

    private function validateCategory(Request $request, ?int $categoryId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', 'unique:categories,slug,' . $categoryId],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
