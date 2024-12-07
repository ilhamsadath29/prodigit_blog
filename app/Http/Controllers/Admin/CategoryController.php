<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Categories/Index', ['categories' => $categories]);
    }

    public function create()
    {
        return Inertia::render('Admin/Categories/Form');
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update($validated);
        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index');
    }
}
