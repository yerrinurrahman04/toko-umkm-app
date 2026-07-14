<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /**
     * Admin dashboard index.
     */
    public function dashboard()
    {
        if (request()->has('refresh')) {
            Cache::forget('admin_dashboard_data');
            return redirect()->route('admin.dashboard');
        }

        $data = Cache::remember('admin_dashboard_data', 600, function () {
            $totalUsers = User::count();
            $totalSellers = User::where('role', 'seller')->count();
            $totalBuyers = User::where('role', 'buyer')->count();
            
            $totalOrders = Order::count();
            $totalRevenue = Order::where('status', 'completed')->sum('final_amount');
            
            $pendingReviews = Review::where('is_moderated', false)->count();

            // Recent users
            $recentUsers = User::latest()->take(5)->get();

            // Recent orders across the platform
            $recentOrders = Order::with(['buyer', 'shop'])->latest()->take(5)->get();

            // Platform Sales Trend (completed orders)
            $salesTrend = Order::where('status', 'completed')
                ->selectRaw("DATE(updated_at) as date, SUM(final_amount) as total")
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->take(30)
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => date('d M', strtotime($item->date)),
                        'total' => (float)$item->total,
                    ];
                });

            // Top Categories by items sold
            $topCategories = \Illuminate\Support\Facades\DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('category_product', 'order_items.product_id', '=', 'category_product.product_id')
                ->join('categories', 'category_product.category_id', '=', 'categories.id')
                ->where('orders.status', 'completed')
                ->selectRaw('categories.name, SUM(order_items.quantity) as qty')
                ->groupBy('categories.name')
                ->orderBy('qty', 'desc')
                ->take(5)
                ->get();

            return compact(
                'totalUsers',
                'totalSellers',
                'totalBuyers',
                'totalOrders',
                'totalRevenue',
                'pendingReviews',
                'recentUsers',
                'recentOrders',
                'salesTrend',
                'topCategories'
            );
        });

        return view('admin.dashboard', $data);
    }

    /**
     * List all users.
     */
    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Change user role.
     */
    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent editing self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $request->validate([
            'role' => 'required|in:admin,seller,buyer'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}.");
    }

    /**
     * List all reviews for moderation.
     */
    public function reviews()
    {
        $reviews = Review::with(['product', 'buyer'])->latest()->get();

        // 1. Rata-rata rating per produk
        $productRatings = \App\Models\Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->orderBy('reviews_avg_rating', 'desc')
            ->get();

        // 2. Distribusi rating (1 sampai 5 bintang) untuk seluruh produk
        $distribution = Review::selectRaw('rating, COUNT(id) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $distribution[$i] ?? 0;
        }

        // 3. Daftar ulasan terbaru yang perlu dimoderasi admin (pending)
        $pendingReviews = Review::with(['product', 'buyer'])
            ->where('is_moderated', false)
            ->latest()
            ->get();

        return view('admin.reviews.index', compact(
            'reviews',
            'productRatings',
            'ratingDistribution',
            'pendingReviews'
        ));
    }

    /**
     * Moderate a review.
     */
    public function moderateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,delete'
        ]);

        if ($request->action === 'approve') {
            $review->update(['is_moderated' => true]);
            return redirect()->back()->with('success', 'Ulasan disetujui dan diterbitkan.');
        } else {
            $review->delete();
            return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
        }
    }

    /**
     * View seller performance comparisons.
     */
    public function sellerPerformance()
    {
        $limitDate = now()->subMonths(3);

        $sellerPerformance = \App\Models\Shop::with(['user'])
            ->select('shops.*')
            ->selectRaw('(SELECT COALESCE(SUM(final_amount), 0) FROM orders WHERE orders.shop_id = shops.id AND orders.status = "completed" AND orders.updated_at >= ?) as total_revenue', [$limitDate])
            ->selectRaw('(SELECT COUNT(id) FROM orders WHERE orders.shop_id = shops.id AND orders.status = "completed" AND orders.updated_at >= ?) as total_orders', [$limitDate])
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('admin.seller-performance', compact('sellerPerformance'));
    }
}
