<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class SalesController extends Controller
{
   public function index()
{
    $perPage = request('per_page', 10);
    
    // Get paginated list of unique sales_ids
    $pagination = Sale::select('sales_id')
        ->groupBy('sales_id')
        ->orderByRaw('MAX(created_at) DESC')
        ->paginate($perPage);
    
    // Get all sales for these paginated IDs
    $sales = Sale::with('vendor')
        ->whereIn('sales_id', $pagination->pluck('sales_id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('sales_id');

        $vendors = Vendor::whereRaw('flag != ?', [pack('C', 1)])->get();

    $vendors = Vendor::orderBy('company_name')->get();

    return view('sales.index', compact('sales', 'vendors', 'pagination'));
}

    public function create()
    {
        $vendors = Vendor::all();
        $products = Product::all();

         $vendors = Vendor::whereRaw('flag != ?', [pack('C', 1)])->get();

        return view('sales.create', compact('vendors', 'products'));
    }

public function store(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',       // Ensure vendor exists
        'sale_date' => 'required|date',                    // Sale date must be valid
        'products' => 'required|array',                    // Products array must be present
        'products.*.product_id' => 'required|exists:products,id', // Ensure product exists
        'products.*.quantity' => 'required|numeric|min:1',  // Quantity must be a valid number and at least 1
    ]);

    // Generate a unique sales ID for the transaction
    $saleId = $this->generateSalesId();
    $saleDate = $request->input('sale_date');  // Get the sale date from the form

    // Start a database transaction to ensure consistency
    DB::beginTransaction();

    try {
        $hasSales = false;  // Flag to track if any valid sales were made

        // Loop through each product in the request
        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['product_id']);  // Get the product from the database

            // Check if the product exists and if there is enough stock
            if (!$product || $product->quantity < $productData['quantity']) {
                // If any product doesn't have enough stock, rollback and return error
                DB::rollBack();
                return redirect()->back()->with('error', '⚠ Insufficient stock for one or more products.');
            }

            // Decrease stock for the selected product
            $product->decrement('quantity', $productData['quantity']);

            // Create a new sale record
            Sale::create([
                'sales_id'   => $saleId,
                'vendor_id'  => $request->vendor_id,
                'pid'        => $product->pid,
                'name'       => $product->name,
                'company'    => $product->company,
                'color'      => $product->color,
                'style'      => $product->style,
                'size'       => $product->size,
                'sku'        => $product->sku,
                'quantity'   => $productData['quantity'],  // Use the selected quantity
                'sales_date' => $saleDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update the 'sold' field for the product
            $product->update([
                'sold' => Sale::where('pid', $product->pid)->sum('quantity'),
            ]);

            $hasSales = true;  // Mark that a sale has occurred
        }

        // If no sales were processed, rollback and show an error
        if (!$hasSales) {
            DB::rollBack();
            return redirect()->back()->with('error', '⚠ No valid product selected or insufficient stock.');
        }

        // Commit the transaction after successfully creating the sales records
        DB::commit();

        // Redirect to the sales index with a success message
        return redirect()->route('sales.index')->with('success', '✅ Sale recorded successfully.');
    } catch (\Exception $e) {
        // If an error occurs, rollback the transaction and log the error
        DB::rollBack();
        \Log::error("Sale store error: " . $e->getMessage());
        return redirect()->back()->with('error', '❌ Error occurred while recording sale.');
    }
}


  public function edit($salesId)
{
    $salesGroup = Sale::with(['product', 'vendor'])->where('sales_id', $salesId)->get();

    // Optional safety check
    if ($salesGroup->isEmpty()) {
        return redirect()->route('sales.index')->with('error', 'Sale not found');
    }

    return view('sales.edit', compact('salesGroup'));
}

public function update(Request $request, $salesId)
{
    $request->validate([
        'sale_date' => 'required|date',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.sold_quantity' => 'required|integer|min:1',
    ]);

    $salesGroup = Sale::where('sales_id', $salesId)->get();

    foreach ($salesGroup as $index => $sale) {
        $productId = $request->products[$index]['product_id'];
        $newQty = (int) $request->products[$index]['sold_quantity'];

        $product = Product::findOrFail($productId);

        $availableStock = $product->quantity + $sale->quantity;

        if ($newQty > $availableStock) {
            return back()->with('error', "Not enough stock for SKU: {$product->sku}");
        }

        // Update product stock
        $product->quantity = $availableStock - $newQty;
        $product->save();

        // ✅ Update sale entry
        $sale->quantity = $newQty;
        $sale->sales_date = $request->sale_date; // ✅ This line ensures sales_date is updated
        $sale->save();
    }

    return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
}
    

    public function show($salesId)
    {
        $sales = Sale::where('sales_id', $salesId)->get();

        if ($sales->isEmpty()) {
            return redirect()->route('sales.index')->with('error', 'Sale not found.');
        }

        return view('sales.show', compact('sales', 'salesId'));
    }

    public function destroy($salesId)
{
    $sales = Sale::where('sales_id', $salesId)->get();

    if ($sales->isEmpty()) {
        return redirect()->route('sales.index')->with('error', 'Sale not found.');
    }

    DB::transaction(function () use ($sales) {
        foreach ($sales as $sale) {
            $product = Product::find($sale->pid); // Assuming 'pid' is the product ID in sales

            if ($product) {
                $product->increment('quantity', $sale->quantity); // Restore stock
            }

            $sale->delete();
        }
    });

    return redirect()->route('sales.index')->with('success', 'All products under this sale have been deleted successfully.');
}

    private function generateSalesId(): string
{
    $year = now()->format('Y'); // Get current year
    $month = strtoupper(now()->format('F')); // Get current month in uppercase (e.g., 'AUG')
    
    // Count sales only for the current month and year
    $count = Sale::whereYear('created_at', now()->year) // Filter by year
                ->whereMonth('created_at', now()->month) // Filter by current month
                ->distinct('sales_id')
                ->pluck('sales_id')
                ->unique()
                ->count();

    // Increment the count and pad with leading zeros
    $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

    // Return the sales ID with year, month, and sequential number
    return "SAL-{$year}-{$month}-{$nextNumber}";
}

}