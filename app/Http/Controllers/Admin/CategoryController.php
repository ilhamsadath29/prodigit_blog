<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::all();
        return Inertia::render('Admin/Categories/Index', ['categories' => $categories]);
    }

    public function create()
    {
        $this->authorize('create', Category::class);
        return Inertia::render('Admin/Categories/Form');
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        $validated = $request->validated();
        $category = Category::create($validated);
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return Inertia::render('Admin/Categories/Form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $validated = $request->validated();
        $category->update($validated);
        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('admin.categories.index');
    }
}
