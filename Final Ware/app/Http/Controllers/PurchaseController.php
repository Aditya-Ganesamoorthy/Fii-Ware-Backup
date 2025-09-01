<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
  
  
public function index()
{
    $perPage = request('per_page', 10); // Get per_page from request or default to 10
    
    // Get paginated list of unique purchase_ids
    $pagination = Purchase::select('purchase_id')
        ->groupBy('purchase_id')
        ->orderByRaw('MAX(created_at) DESC')
        ->paginate($perPage);
    
    // Get all purchases for these paginated IDs
    $purchases = Purchase::with('vendor')
        ->whereIn('purchase_id', $pagination->pluck('purchase_id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('purchase_id');


    $vendors = Vendor::orderBy('company_name')->get();

    return view('purchase.index', compact('purchases', 'vendors', 'pagination'));
}

public function create()
    {
        $vendors = Vendor::all();
        $products = Product::all();

         $vendors = Vendor::whereRaw('flag != ?', [pack('C', 1)])->get();

        return view('purchase.create', compact('vendors', 'products'));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'purchase_date' => 'nullable|date',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    // Filter out products with missing product_id or quantity
    $selectedProducts = array_filter($request->products, function ($product) {
        return !empty($product['product_id']) && !empty($product['quantity']) && $product['quantity'] > 0;
    });

    if (empty($selectedProducts)) {
        return back()->withErrors(['products' => 'Please select at least one product with quantity.'])->withInput();
    }

    // Generate purchase ID and use current date if not provided
    $purchaseId = $this->generatePurchaseId();
    $purchaseDate = $request->purchase_date ?? now();

    // Begin DB transaction
    DB::transaction(function () use ($selectedProducts, $request, $purchaseId, $purchaseDate) {
        foreach ($selectedProducts as $productData) {
            $productModel = Product::findOrFail($productData['product_id']);

            // Create the purchase entry
            Purchase::create([
                'purchase_id'   => $purchaseId,
                'vendor_id'     => $request->vendor_id,
                'product_id'    => $productData['product_id'],
                'product_name'  => $productModel->name,
                'quantity'      => $productData['quantity'],
                'purchase_date' => $purchaseDate,
            ]);

            // ✅ Correctly increment product stock
            $productModel->quantity += $productData['quantity'];
            $productModel->save();
        }
    });

    return redirect()->route('purchase.index')->with('success', 'Purchase created and stock updated successfully!');
}


public function filterByVendor(Request $request)
{
    $vendorId = $request->vendor_id;

    $query = Purchase::with('vendor')->orderBy('created_at', 'desc');

    if ($vendorId) {
        $query->where('vendor_id', $vendorId);
    }

    $purchases = $query->get()->groupBy('purchase_id');

    return view('purchase.partials.table', compact('purchases'))->render(); // returns only the table HTML
}


    
       public function show($id)
{
    $purchases = Purchase::with('vendor', 'product')->where('purchase_id', $id)->get();

    if ($purchases->isEmpty()) {
        return redirect()->route('purchase.index')->with('error', 'Purchase not found.');
    }

    return view('purchase.show', [
        'purchases' => $purchases,
        'purchaseId' => $id // <<< THIS is important!
    ]);
    }

   public function edit($id)
{
    // Fetch all purchases that share the same purchase_id
    $firstPurchase = Purchase::findOrFail($id);
    $purchases = Purchase::where('purchase_id', $firstPurchase->purchase_id)->get();

    return view('purchase.edit', [
        'purchases'     => $purchases,
        'purchase'      => $firstPurchase,
        'purchase_id'   => $firstPurchase->purchase_id,
        'vendor_id'     => $firstPurchase->vendor_id,
        'purchase_date' => $firstPurchase->purchase_date,
        'vendors'       => Vendor::all(),
        'products'      => Product::all(),
    ]);
}

    public function search(Request $request)
{
    $search = $request->q;

    $products = Product::select('id', 'name', 'sku')
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
        })
        ->paginate(10);

    return response()->json([
        'data' => $products->items(),
        'total' => $products->total()
    ]);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'nullable|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $purchaseGroup = Purchase::where('purchase_id', $id)->get();

        foreach ($purchaseGroup as $purchase) {
            $pid = $purchase->id;

            if (isset($request->products[$pid]['quantity'])) {
                $purchase->quantity = $request->products[$pid]['quantity'];
            }

            if ($request->filled('purchase_date')) {
                $purchase->purchase_date = $request->purchase_date;
            }

            $purchase->vendor_id = $request->vendor_id;
            $purchase->save();
        }

        return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully!');
    }
public function updateGroup(Request $request, $purchase_id)
{
    // Validation
    $request->validate([
        'purchase_date' => 'required|date',
        'vendor_id'     => 'required|exists:vendors,id',
        'products'      => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity'   => 'required|integer|min:1',
    ]);

    // Update each purchase row
    foreach ($request->products as $purchaseId => $data) {
        $purchase = Purchase::find($purchaseId);

        if ($purchase) {
            $purchase->purchase_date = $request->purchase_date;
            $purchase->vendor_id = $request->vendor_id;
            $purchase->quantity = $data['quantity'];
            $purchase->save();
        }
    }

    // Recalculate stock per product
    $productIds = collect($request->products)->pluck('product_id')->unique();

    foreach ($productIds as $productId) {
        $totalPurchased = Purchase::where('product_id', $productId)->sum('quantity');

        $product = Product::find($productId);
        if ($product) {
            $product->quantity = $totalPurchased;
            $product->save();
        }
    }

    return redirect()->route('purchase.index')->with('success', 'Purchase updated and product stock recalculated.');
}


public function deleteItemsPage($purchaseId)
{
    $purchases = Purchase::with('vendor', 'product')
        ->where('purchase_id', $purchaseId)
        ->get();

    return view('purchase.delete_items', compact('purchases', 'purchaseId'));
}

public function deleteSelectedItems(Request $request, $purchaseId)
{
    $request->validate([
        'items' => 'required|array',
        'items.*' => 'exists:purchases,id',
    ]);

    $selectedPurchases = Purchase::whereIn('id', $request->items)->get();

    foreach ($selectedPurchases as $purchase) {
        // Update product stock
        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->quantity -= $purchase->quantity;

            // Optional: Prevent stock from going negative
            if ($product->quantity < 0) {
                $product->quantity = 0;
            }

            $product->save();
        }

        // Delete the individual purchase item
        $purchase->delete();
    }

    return redirect()->route('purchase.index')->with('success', 'Selected items deleted and product stock updated.');
}


    public function destroy($purchaseId)
    {
        $purchases = Purchase::where('purchase_id', $purchaseId)->get();

        if ($purchases->isEmpty()) {
            return redirect()->route('purchase.index')->with('error', 'Purchase not found.');
        }

        DB::transaction(function () use ($purchases) {
            foreach ($purchases as $purchase) {
                $product = Product::find($purchase->product_id);

                if ($product) {
                    $product->decrement('quantity', $purchase->quantity);
                }

                $purchase->delete();
            }
        });

        return redirect()->route('purchase.index')->with('success', 'All products under this purchase have been deleted successfully.');
    }

    private function generatePurchaseId(): string
    {
        $year = now()->format('Y');
        $month = strtoupper(now()->format('M'));
        $prefix = "PUR-{$year}-{$month}-";

        $latest = Purchase::where('purchase_id', 'LIKE', "{$prefix}%")
            ->latest('id')
            ->first();

        $number = 1;
        if ($latest) {
            $lastNumber = (int)Str::afterLast($latest->purchase_id, '-');
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
}

