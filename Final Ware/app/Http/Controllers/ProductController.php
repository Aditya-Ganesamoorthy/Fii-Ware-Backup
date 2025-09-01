<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->orderByDesc('id')->paginate(10); // <-- Use paginate here
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

   public function store(Request $request)
{
    try {
        // Convert SKU to uppercase before validation
        $request['sku'] = strtoupper($request->input('sku'));

        $validated = $request->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'company' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'style' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'color' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:100'],
            'size' => ['required', 'in:S,M,L,X,XL,XXL,Meter'],
            'sku' => [
                'required',
                'unique:products,sku',
                'max:16',
                'regex:/^[A-Z0-9,-]+$/'
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.regex' => 'Name must only contain letters (no digits).',
            'company.regex' => 'Company must only contain letters (no digits).',
            'style.regex' => 'Style must only contain letters (no digits).',
            'color.regex' => 'Color must only contain letters (no digits).',
            'size.in' => 'Size must be one of: S, M, L, X, XL, XXL,Meter.',
            'sku.regex' => 'SKU must be up to 16 characters and only contain uppercase letters, digits, hyphens (-), and commas (,).',
            'sku.unique' => 'SKU already exists.',
        ]);

        // Check for duplicate product by attributes
        $duplicate = Product::where('name', $validated['name'])
            ->where('company', $validated['company'])
            ->where('style', $validated['style'])
            ->where('color', $validated['color'])
            ->where('size', $validated['size'])
            ->where('category_id', $validated['category_id'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'duplicate' => 'This product already exists.'
            ])->withInput();
        }

        // Add system fields
        $validated['pid'] = strtoupper('P-' . Str::random(8));
        $validated['quantity'] = 0;
        $validated['image'] = 'placeholders/product.png';

        Product::create($validated);

return redirect()->route('products.create')->with('success', '✅ Product created successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        Log::error('Product creation failed: ' . $e->getMessage());
        return back()->with('error', 'Error: Something went wrong.')->withInput();
    }
}

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    try {
        // Convert SKU to uppercase before validation
        $request['sku'] = strtoupper($request->input('sku'));

        $validated = $request->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'company' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'style' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'color' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:100'],
            'size' => ['required', 'in:S,M,L,X,XL,XXL,Meter'],
            'sku' => [
                'required',
                'unique:products,sku,' . $product->id,
                'max:16',
                'regex:/^[A-Z0-9,-]+$/'
            ],
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
        ], [
            'name.regex' => 'Name must only contain letters (no digits).',
            'company.regex' => 'Company must only contain letters (no digits).',
            'style.regex' => 'Style must only contain letters (no digits).',
            'color.regex' => 'Color must only contain letters (no digits).',
            'size.in' => 'Size must be one of: S, M, L, X, XL, XXL,Meter.',
            'sku.regex' => 'SKU must be up to 16 characters and only contain uppercase letters, digits, hyphens (-), and commas (,).',
            'sku.unique' => 'SKU already exists.',
        ]);

        // Check for duplicate product (excluding current product)
        $duplicate = Product::where('id', '!=', $product->id)
            ->where('name', $validated['name'])
            ->where('company', $validated['company'])
            ->where('style', $validated['style'])
            ->where('color', $validated['color'])
            ->where('size', $validated['size'])
            ->where('category_id', $validated['category_id'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'duplicate' => 'Another product with the same attributes already exists.'
            ])->withInput();
        }

        // Ensure image placeholder if missing
        if (!$product->image) {
            $validated['image'] = 'placeholders/product.png';
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        Log::error('Product update failed: ' . $e->getMessage());
        return back()->with('error', 'Update failed. Check logs.')->withInput();
    }
}

    public function confirmDelete(Product $product)
    {
        return view('products.delete', compact('product'));
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Delete failed. Check logs.');
        }
    }
}