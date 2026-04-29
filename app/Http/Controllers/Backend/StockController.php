<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Services;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::where('user_id',auth()->id())->orderbydesc('id')->get();
        $partners = Partner::where('user_id', auth()->id())
            ->get();
        return view('admin.stock.list',[
            'stocks' => $stocks,
            'partners'  => $partners
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|integer|exists:partners,id',
            'product'    => 'required|string|min:3',
            'price'      => 'required|numeric|min:0',
            'qty'        => 'required|numeric|min:1',
        ], [
            'product.min' => 'Məhsul adı minimum 3 hərf olmalıdır !'
        ]);

        $stock = Stock::where('partner_id', $validated['partner_id'])
            ->where('product', $validated['product'])
            ->first();

        if ($stock) {
            $stock->qty += $validated['qty'];
            $stock->price = $validated['price'];
            $stock->user_id = auth()->id();
            $stock->save();
        } else {
            $validated['user_id'] = auth()->id();
            Stock::create($validated);
        }

        return redirect()->back()->with('success', 'Stok əlavə edildi !');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $partners = Partner::where('user_id', auth()->id())
            ->get();
        return view('admin.stock.edit',compact('stock','partners'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'partner_id' => 'required|integer|exists:partners,id',
            'product'    => 'required|string|min:3',
            'price'      => 'required|numeric|min:0',
            'qty'        => 'required|numeric|min:1',
        ], [
            'product.min' => 'Məhsul adı minimum 3 hərf olmalıdır !'
        ]);

        $stock = Stock::findOrFail($id);

        // eyni partner + product var? (özündən başqa)
        $existing = Stock::where('partner_id', $validated['partner_id'])
            ->where('product', $validated['product'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            // merge eliyirik
            $existing->qty += $validated['qty'];
            $existing->price = $validated['price'];
            $existing->user_id = auth()->id();
            $existing->save();

            // köhnəni sil
            $stock->delete();
        } else {
            $stock->update([
                'partner_id' => $validated['partner_id'],
                'product'    => $validated['product'],
                'price'      => $validated['price'],
                'qty'        => $validated['qty'],
                'user_id'    => auth()->id(),
            ]);
        }

        return redirect()->route('admin.stock.list')->with('success', 'Stok yeniləndi !');
    }

    public function delete($id)
    {
        $stock = Stock::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();


        $stock->delete();

        return redirect()->back()->with('success', 'Stock silindi');
    }
}
