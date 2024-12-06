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

        if ($request->hasFile('img')) {
            $validated['img_path'] = $request->file('img')->store('categories', 'public');
        }

        $category = Category::create($validated);

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->hasFile('img')) {
            if ($category->img_path) {
                Storage::disk('public')->delete($category->img_path);
            }
            
            $validated['img_path'] = $request->file('img')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        if ($category->img_path) {
            Storage::disk('public')->delete($category->img_path);
        }
        return redirect()->route('categories.index');
    }
}
