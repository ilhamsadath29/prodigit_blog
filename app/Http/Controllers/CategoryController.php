<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Categories/Index', ['categories' => $categories]);
    }

    public function create()
    {
        return Inertia::render('Categories/Form');
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('img')) {
            $validated['img_path'] = $request->file('img')->store('images', 'public');
        }

        $category = Category::create($validated);

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Categories/Form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->hasFile('img')) {
            if ($category->img_path) {
                Storage::disk('public')->delete($category->img_path);
            }
            
            $validated['img_path'] = $request->file('img')->store('images', 'public');
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
