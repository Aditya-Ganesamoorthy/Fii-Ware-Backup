<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->paginate(10); // 10 items per page
        return view('categories.index', compact('categories'));
    }

    public function products(Category $category)
    {
        return response()->json($category->products);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255', 'unique:categories,name'],
            'description' => 'nullable|string',
        ], [
            'name.regex' => 'The category name may only contain letters and spaces.',
            'name.unique' => 'The category is already available. Please enter a new category.',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => 'nullable|string',
        ], [
            'name.regex' => 'The category name may only contain letters and spaces.',
            'name.unique' => 'This category name already exists. Please choose another name.',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
