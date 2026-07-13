<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard index.
     */
    public function dashboard()
    {
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

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalBuyers',
            'totalOrders',
            'totalRevenue',
            'pendingReviews',
            'recentUsers',
            'recentOrders'
        ));
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
        return view('admin.reviews.index', compact('reviews'));
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
}
