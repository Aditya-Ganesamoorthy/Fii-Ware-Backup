<?php

namespace App\Http\Controllers;

use App\Models\Returns;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    protected function userHasPermission($page)
    {
        return DB::table('role_pages')
            ->where('role_id', Auth::user()->role_id)
            ->where('page_name', $page)
            ->exists();
    }

  public function index()
    {
        $search = request('search');
        $perPage = request('perPage', 10); // Default to 10 if not specified

        $query = Returns::with(['staff','product:id,name,pid,sku'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sales_id', 'like', "%$search%")
                ->orWhere('reason', 'like', "%$search%")
                ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('sku', 'like', "%$search%");
                })
                ->orWhereHas('staff', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }

        $returns = $query->paginate($perPage);

        // Get allowed route names for the current user's role
        $allowedRoutes = DB::table('role_pages')
            ->where('role_id', Auth::user()->role_id)
            ->pluck('page_name')
            ->toArray();

        return view('returns.index', compact('returns', 'allowedRoutes', 'perPage'));
    }

    public function create()
    {
        if (!$this->userHasPermission('returns.create')) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch distinct sales IDs
        $sales = Sale::distinct('sales_id')->pluck('sales_id');
        return view('returns.create', compact('sales'));
    }

    public function getProductsBySale($salesId)
    {
        $products = Sale::where('sales_id', $salesId)
        ->join('products', 'sales.pid', '=', 'products.pid')
        ->select('products.id', 'products.name', 'products.pid', 'sales.quantity as sold_quantity')
        ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        if (!$this->userHasPermission('returns.create')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'sales_id' => 'required|string',
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $createdReturns = 0;
            
            foreach ($request->products as $index => $productData) {
                // Skip if this product wasn't selected for return
                if (empty($productData['quantity_returned']) || empty($productData['reason'])) {
                    continue;
                }
                
                // Validate individual product
                $productValidator = Validator::make($productData, [
                    'pid' => 'required|exists:products,pid',
                    'quantity_returned' => 'required|integer|min:1',
                    'reason' => 'required|string',
                ]);
                
                if ($productValidator->fails()) {
                    throw new \Exception("Invalid data for product at index {$index}");
                }
                
                $product = Product::where('pid', $productData['pid'])->first();
                if (!$product) {
                    throw new \Exception("Product with PID {$productData['pid']} not found");
                }
                
                $sale = Sale::where('sales_id', $request->sales_id)
                            ->where('pid', $product->pid)
                            ->first();
                
                if (!$sale) {
                    throw new \Exception("Sale record not found for product {$product->name}");
                }

                if ($productData['quantity_returned'] > $sale->quantity) {
                    throw new \Exception("Quantity for {$product->name} cannot exceed sold quantity ({$sale->quantity})");
                }

                // Create the return record with product_name
                Returns::create([
                    'sales_id' => $request->sales_id,
                    'product_name' => $product->name, // Added this line
                    'pid' => $product->pid,
                    'quantity_returned' => $productData['quantity_returned'],
                    'reason' => $productData['reason'],
                    'returned_by' => Auth::id(),
                    'status' => 'pending'
                ]);
                
                $createdReturns++;
            }

            if ($createdReturns === 0) {
                throw new \Exception("No products selected for return");
            }

            DB::commit();
            return redirect()->route('returns.index')->with('success', 'Return submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function accept(Returns $return)
    {
        if (!$this->userHasPermission('returns.accept')) {
            abort(403, 'Unauthorized action.');
        }

        if ($return->status !== 'pending') {
            return redirect()->back()->with('error', 'This return has already been processed.');
        }

        DB::beginTransaction();

        try {
            // Step 1: Update return status
            $return->update(['status' => 'accepted']);

            // Step 2: Update product stock (increment)
            $product = DB::table('products')->where('pid', $return->pid)->first();
            if (!$product) {
                throw new \Exception("Product with PID {$return->pid} not found");
            }

            DB::table('products')
                ->where('pid', $return->pid)
                ->increment('quantity', $return->quantity_returned);

            // Step 3: Update sales quantity (decrement)
            $sale = Sale::where('sales_id', $return->sales_id)
                        ->where('pid', $return->pid)
                        ->first();

            if (!$sale) {
                throw new \Exception("Sale record not found for sales ID: {$return->sales_id} and product PID: {$return->pid}");
            }

            // Ensure we don't decrement below zero
            if ($sale->quantity < $return->quantity_returned) {
                throw new \Exception("Cannot return more than was sold. Sale quantity: {$sale->quantity}, Return quantity: {$return->quantity_returned}");
            }

            $sale->decrement('quantity', $return->quantity_returned);
                 
            DB::commit();

            return redirect()->route('returns.index')->with('success', 'Return accepted and product stock updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $return->update(['status' => 'pending']);
            return redirect()->route('returns.index')->with('error', 'Failed to accept return: ' . $e->getMessage());
        }
    }

    public function decline(Returns $return)
    {
        if (!$this->userHasPermission('returns.decline')) {
            abort(403, 'Unauthorized action.');
        }

        if ($return->status !== 'pending') {
            return redirect()->back()->with('error', 'This return has already been processed.');
        }

        $return->update(['status' => 'declined']);
        return redirect()->route('returns.index')->with('success', 'Return declined successfully!');
    }
}