<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display seller's vouchers.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('seller.vouchers.index', compact('vouchers'));
    }

    /**
     * Show voucher create form.
     */
    public function create()
    {
        return view('seller.vouchers.create');
    }

    /**
     * Store new voucher.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->min_spend,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil dibuat!');
    }

    /**
     * Show voucher edit form.
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('seller.vouchers.edit', compact('voucher'));
    }

    /**
     * Update voucher.
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->min_spend,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    /**
     * Delete voucher.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil dihapus!');
    }
}
