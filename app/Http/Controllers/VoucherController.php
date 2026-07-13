<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;

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
    public function store(StoreVoucherRequest $request)
    {
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
    public function update(UpdateVoucherRequest $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

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
