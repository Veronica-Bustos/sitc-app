<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\StoreRequest as CategoryStoreRequest;
use App\Http\Requests\Catalog\UpdateRequest as CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index(Request $request, \App\Filters\CategoryFilter $filters): View
    {
        $query = Category::query();

        $categories = $query->filter($filters)->withCount('items')->paginate(20)->withQueryString();

        return view('category.index', [
            'categories' => $categories,
            'parents' => Category::select('id', 'name')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('category.create');
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        $request->session()->flash('category.id', $category->id);

        return redirect()->route('categories.index');
    }

    public function show(Request $request, Category $category): View
    {
        return view('category.show', [
            'category' => $category,
        ]);
    }

    public function edit(Request $request, Category $category): View
    {
        return view('category.edit', [
            'category' => $category,
        ]);
    }

    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        $request->session()->flash('category.id', $category->id);

        return redirect()->route('categories.index');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index');
    }
}
