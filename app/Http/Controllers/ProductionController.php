<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Production::query()->with('product')->orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_code'   => 'required|unique:productions,batch_code',
            'product_id'   => 'required|exists:products,id',
            'production_date'   => 'required|date',
            'finished_date'     => 'nullable|date|after_or_equal:start_date',
            'quantity'     => 'required|integer|min:1',
            'failed_qty'   => 'nullable|integer|min:0',
            'notes'        => 'nullable|string'
        ]);

        $data['failed_qty'] = $data['failed_qty'] ?? 0;
        $data['unit'] = $data['unit'] ?? 'pcs';

        $production = Production::create($data);

        return response()->json([
            'message' => 'Production created successfully',
            'data' => $production
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        return $production->load('product');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'batch_code' => 'sometimes|string|max:100',
            'quantity'   => 'sometimes|integer|min:1',
            'failed_qty' => 'sometimes|integer|min:0',
            'start_date' => 'sometimes|date',
            'end_date'   => 'nullable|date',
            'notes'      => 'nullable|string',
            'status'     => 'sometimes|string|max:100'
        ]);

        $production->update($validated);

        return response()->json([
            'message' => 'Production updated successfully',
            'data' => $production->load('product'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production)
    {
        $production->delete();

        return response()->json([
            'message' => 'Production deleted successfully'
        ]);
    }
}
