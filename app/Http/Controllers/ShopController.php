<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Seller dashboard KPI and overview.
     */
    public function sellerDashboard()
    {
        $user = auth()->user();
        
        // Ensure seller has a shop profile
        $shop = $user->shop;
        if (!$shop) {
            $shop = Shop::create([
                'user_id' => $user->id,
                'name' => 'Toko ' . $user->name,
                'slug' => Str::slug('toko-' . $user->name . '-' . rand(100, 999)),
                'description' => 'Profil toko baru.',
                'operating_hours' => '08:00 - 17:00',
                'shipping_area' => 'Lokal'
            ]);
        }

        if (request()->has('refresh')) {
            \Illuminate\Support\Facades\Cache::forget('seller_dashboard_data_' . $user->id);
            return redirect()->route('seller.dashboard');
        }

        $data = \Illuminate\Support\Facades\Cache::remember('seller_dashboard_data_' . $user->id, 600, function () use ($shop) {
            // Real-time KPI summary
            $totalOrders = Order::where('shop_id', $shop->id)->count();
            $pendingOrders = Order::where('shop_id', $shop->id)->where('status', 'pending')->count();
            $completedOrders = Order::where('shop_id', $shop->id)->where('status', 'completed')->get();
            
            $totalRevenue = $completedOrders->sum('final_amount');
            $uniqueBuyers = Order::where('shop_id', $shop->id)
                ->distinct('buyer_id')
                ->count('buyer_id');

            // Low stock products warning
            $lowStockProducts = Product::where('shop_id', $shop->id)
                ->where('stock', '<', 5)
                ->get();

            // Recent orders
            $recentOrders = Order::where('shop_id', $shop->id)
                ->with(['buyer', 'payment'])
                ->latest()
                ->take(5)
                ->get();

            // 1. Sales Trend (last 7 days)
            $salesTrend = \DB::table('orders')
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, SUM(final_amount) as total')
                ->where('shop_id', $shop->id)
                ->where('status', 'completed')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // 2. Best Selling Products (top 5)
            $topProducts = \DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('products.name, SUM(order_items.quantity) as qty')
                ->where('orders.shop_id', $shop->id)
                ->where('orders.status', 'completed')
                ->groupBy('products.name')
                ->orderByDesc('qty')
                ->take(5)
                ->get();

            // 3. Rating & Reviews count
            $reviewsDistribution = \DB::table('reviews')
                ->join('products', 'reviews.product_id', '=', 'products.id')
                ->selectRaw('rating, COUNT(reviews.id) as count')
                ->where('products.shop_id', $shop->id)
                ->groupBy('rating')
                ->orderBy('rating')
                ->get();

            return compact(
                'totalOrders',
                'pendingOrders',
                'totalRevenue',
                'uniqueBuyers',
                'lowStockProducts',
                'recentOrders',
                'salesTrend',
                'topProducts',
                'reviewsDistribution'
            );
        });

        $data['shop'] = $shop;
        return view('seller.dashboard', $data);
    }

    /**
     * Edit shop details.
     */
    public function edit()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('seller.dashboard');
        }
        return view('seller.shop.edit', compact('shop'));
    }

    /**
     * Update shop details.
     */
    public function update(Request $request)
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('seller.dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'shipping_area' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'address', 'operating_hours', 'shipping_area']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $shop->update($data);

        return redirect()->route('seller.dashboard')->with('success', 'Profil Toko berhasil diperbarui!');
    }

    /**
     * Display a public shop's profile and products.
     */
    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        $products = Product::where('shop_id', $shop->id)->latest()->get();

        return view('shops.show', compact('shop', 'products'));
    }
}
