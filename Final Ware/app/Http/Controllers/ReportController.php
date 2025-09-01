<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockReport(Request $request)
{
    // Validate input parameters
    $validated = $request->validate([
        'search' => 'nullable|string|max:255',
        'per_page' => 'nullable|integer|in:10,25,50,100',
    ]);

    // Base query with category relationship
    $productsQuery = Product::with('category')
        ->select('products.*')
        ->when($request->search, function($query) use ($request) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('pid', 'like', '%'.$request->search.'%')
                  ->orWhere('company', 'like', '%'.$request->search.'%');
            });
        })
        ->orderBy('name');

    // Get paginated results
    $perPage = $validated['per_page'] ?? 10;
    $products = $productsQuery->paginate($perPage)
        ->appends($request->except('page'));  // Preserve all query parameters

    return view('reports.stock', compact('products'));
}
    public function stockSuggestions(Request $request)
    {
        $query = $request->input('query', '');
        $products = Product::where('name', 'like', '%'.$query.'%')
            ->select('id', 'name')
            ->limit(10)
            ->get();
        return response()->json($products);
    }

     public function purchaseReport()
{
    $perPage = request('per_page', 10); // Get per_page from request or default to 10
    
    $purchases = Purchase::with(['vendor', 'product'])
        ->orderBy('purchase_date', 'desc')
        ->paginate($perPage);
        
    return view('reports.purchase', compact('purchases'));
}
//show product
    public function showProduct(Product $product)
{
    return view('reports.products.show', compact('product'));
}
    
   public function filterPurchaseReport(Request $request)
{
    $perPage = $request->input('per_page', 10); // Get per_page from request
    
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    $query = Purchase::with(['vendor', 'product']);
    
    if ($fromDate) {
        $query->where('purchase_date', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    
    if ($toDate) {
        $query->where('purchase_date', '<=', Carbon::parse($toDate)->endOfDay());
    }
    
    $purchases = $query->orderBy('purchase_date', 'desc')->paginate($perPage);
    
    return view('reports.purchase', compact('purchases', 'fromDate', 'toDate'));
}

public function exportPurchaseReport(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    $query = Purchase::with(['vendor', 'product']);
    
    if ($fromDate && $fromDate !== 'all') {
        $query->where('purchase_date', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    
    if ($toDate && $toDate !== 'all') {
        $query->where('purchase_date', '<=', Carbon::parse($toDate)->endOfDay());
    }
    
    $purchases = $query->orderBy('purchase_date', 'desc')->get();
    
    $fileName = 'purchase-report-' . ($fromDate ?? 'all') . '-to-' . ($toDate ?? 'all') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];
    
    $callback = function() use ($purchases) {
        $file = fopen('php://output', 'w');
        
        // Header row
        fputcsv($file, ['#', 'Purchase ID', 'Date', 'Vendor', 'Product', 'Quantity']);
        
        // Data rows
        foreach ($purchases as $index => $purchase) {
            fputcsv($file, [
                $index + 1,
                $purchase->purchase_id,
                $purchase->purchase_date->format('Y-m-d'),
                $purchase->vendor->company_name ?? 'N/A',
                $purchase->product->name ?? 'N/A',
                $purchase->quantity
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

// Update these methods in your ReportController:

public function salesReport()
{
    $perPage = request('per_page', 10); // Get per_page from request or default to 10
    
    $sales = Sale::with(['vendor', 'product'])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
        
    return view('reports.sales', compact('sales'));
}

public function filterSalesReport(Request $request)
{
    $perPage = $request->input('per_page', 10); // Get per_page from request
    
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    $query = Sale::with(['vendor', 'product']);
    
    if ($fromDate) {
        $query->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    
    if ($toDate) {
        $query->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
    }
    
    $sales = $query->orderBy('created_at', 'desc')->paginate($perPage);
    
    return view('reports.sales', compact('sales', 'fromDate', 'toDate'));
}

public function exportSalesReport(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    $query = Sale::with(['vendor', 'product']);
    
    if ($fromDate && $fromDate !== 'all') {
        $query->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    
    if ($toDate && $toDate !== 'all') {
        $query->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
    }
    
    $sales = $query->orderBy('created_at', 'desc')->get();
    
    $fileName = 'sales-report-' . ($fromDate ?? 'all') . '-to-' . ($toDate ?? 'all') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];
    
    $callback = function() use ($sales) {
        $file = fopen('php://output', 'w');
        
        // Header row
        fputcsv($file, ['#', 'Sales ID', 'Date', 'Vendor', 'Product', 'Quantity']);
        
        // Data rows
        foreach ($sales as $index => $sale) {
            fputcsv($file, [
                $index + 1,
                $sale->sales_id,
                $sale->created_at->format('Y-m-d'),
                $sale->vendor->company_name ?? 'N/A',
                $sale->product->name ?? 'N/A',
                $sale->quantity
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

}