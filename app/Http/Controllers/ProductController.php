<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function catalog(Request $request)
    {
        $categories = Category::all();
        
        $products = Product::query()
            ->search($request->search)
            ->category($request->category)
            ->priceBetween($request->min_price, $request->max_price)
            ->with(['shop', 'categories'])
            ->latest()
            ->paginate(12);

        return view('welcome', compact('products', 'categories'));
    }

    /**
     * Show a single product's detail page.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['shop', 'categories', 'variants', 'reviews.buyer'])->firstOrFail();
        
        // Only load moderated reviews
        $reviews = $product->reviews()->where('is_moderated', true)->latest()->get();
        $averageRating = $reviews->avg('rating') ?: 0;

        return view('products.show', compact('product', 'reviews', 'averageRating'));
    }

    /**
     * List seller's own products.
     */
    public function index()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('seller.dashboard');
        }

        $products = Product::where('shop_id', $shop->id)->with('categories')->latest()->get();
        return view('seller.products.index', compact('products'));
    }

    /**
     * Show form to create new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Store new product.
     */
    public function store(StoreProductRequest $request)
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('seller.dashboard');
        }

        $data = $request->only(['name', 'price', 'stock', 'discount_percentage', 'description']);
        $data['shop_id'] = $shop->id;
        $data['slug'] = Str::slug($request->name) . '-' . rand(100, 999);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product = Product::create($data);
        $product->categories()->sync([$request->category_id]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show product edit form.
     */
    public function edit($id)
    {
        $shop = auth()->user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->with('variants')->firstOrFail();
        $categories = Category::all();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $shop = auth()->user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        $data = $request->only(['name', 'price', 'stock', 'discount_percentage', 'description']);
        $data['slug'] = Str::slug($request->name) . '-' . rand(100, 999);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);
        $product->categories()->sync([$request->category_id]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product.
     */
    public function destroy($id)
    {
        $shop = auth()->user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Add product variant.
     */
    public function addVariant(Request $request, $productId)
    {
        $shop = auth()->user()->shop;
        $product = Product::where('id', $productId)->where('shop_id', $shop->id)->firstOrFail();

        $request->validate([
            'variant_name' => 'required|string|max:255',
            'variant_price' => 'required|numeric|min:0',
            'variant_stock' => 'required|integer|min:0',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => $request->variant_name,
            'price' => $request->variant_price,
            'stock' => $request->variant_stock,
        ]);

        return redirect()->back()->with('success', 'Varian produk berhasil ditambahkan!');
    }

    /**
     * Delete product variant.
     */
    public function deleteVariant($id)
    {
        $shop = auth()->user()->shop;
        $variant = ProductVariant::findOrFail($id);
        
        // Ensure variant belongs to a product owned by this seller
        if ($variant->product->shop_id !== $shop->id) {
            abort(403);
        }

        $variant->delete();
        return redirect()->back()->with('success', 'Varian produk berhasil dihapus!');
    }
}
