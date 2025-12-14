<?php

namespace App\Http\Controllers;

use App\Models\DirectCost;
use Illuminate\Http\Request;

class DirectCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DirectCost::with('production')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => 'required|exists:productions,id',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $directCost = DirectCost::create($validated);

        return response()->json($directCost, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DirectCost $directCost)
    {
        return $directCost->load('production');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DirectCost $directCost)
    {
        $validated = $request->validate([
            'production_id' => 'sometimes|exists:productions,id',
            'name' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'note' => 'nullable|string',
        ]);

        $directCost->update($validated);

        return $directCost;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DirectCost $directCost)
    {
        $directCost->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
