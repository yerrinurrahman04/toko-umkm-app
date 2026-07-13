<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store new product review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $orderItem = OrderItem::with('order')->findOrFail($request->order_item_id);

        // Ensure current user is the buyer of the order
        if ($orderItem->order->buyer_id !== auth()->id()) {
            abort(403);
        }

        // Create Review (pending moderation by default)
        Review::create([
            'product_id' => $orderItem->product_id,
            'buyer_id' => auth()->id(),
            'order_item_id' => $orderItem->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_moderated' => false // needs admin approval
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim! Menunggu moderasi admin.');
    }
}
