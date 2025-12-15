<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with('items.product')->latest();

        $perPage = $request->get('per_page', 10);
        $sales = $query->paginate($perPage);

        return response()->json($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'customer' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $sale = DB::transaction(function () use ($request) {

            $sale = Sale::create([
                'date' => $request->date,
                'customer' => $request->customer,
                'total_amount' => 0,
                'description' => $request->description,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['price'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $sale->update(['total_amount' => $total]);

            return $sale->load('items');
        });

        return response()->json([
            'message' => 'Penjualan berhasil disimpan.',
            'data' => $sale,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return new SaleResource($sale->load('items.product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'date' => 'required|date',
            'customer' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $sale = DB::transaction(function () use ($request, $sale) {

            $sale->update([
                'date' => $request->date,
                'customer' => $request->customer,
                'description' => $request->description,
            ]);

            $sale->items()->delete();

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['price'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $sale->update(['total_amount' => $total]);

            return $sale->load('items');
        });

        return response()->json([
            'message' => 'Penjualan berhasil diperbarui.',
            'data' => $sale,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json([
            'message' => 'Penjualan berhasil dihapus.',
        ]);
    }
}
