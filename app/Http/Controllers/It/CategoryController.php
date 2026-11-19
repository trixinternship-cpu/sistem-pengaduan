<?php

namespace App\Http\Controllers\It;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('complaints')->orderBy('nama_kategori')->paginate(15);
        return view('it.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('it.categories.form', ['category' => new Category()]);
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('it.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('it.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('it.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('it.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}