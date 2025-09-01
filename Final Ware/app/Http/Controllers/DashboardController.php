<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{

public function index()
{
    $totalVendors = Vendor::count();
    $todaySalesTotal = Sale::count();
    $todayproductTotal = Product::count();
    $totalUsers = User::count(); // Add this line

    $latestUser = User::latest()->first();
    $userRegisteredAgo = $latestUser
        ? $latestUser->created_at->diffForHumans()
        : null;

    $latestProduct = Product::latest()->first();
    $productAddedAgo = $latestProduct
        ? $latestProduct->created_at->diffForHumans()
        : null;

    $latestSale = Sale::latest()->first();
    $saleAddedAgo = $latestSale
        ? $latestSale->created_at->diffForHumans()
        : null;

    return view('dashboard.index', compact(
        'totalVendors',
        'todaySalesTotal',
        'todayproductTotal',
        'totalUsers', // Pass to view
        'latestUser',
        'userRegisteredAgo',
        'latestProduct',
        'productAddedAgo',
        'latestSale',
        'saleAddedAgo'
    ));

}

}
