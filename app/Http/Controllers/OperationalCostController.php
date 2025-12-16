<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationalCost;

class OperationalCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OperationalCost::latest();

        $perPage = $request->get('per_page', 10);
        $operationalCosts = $query->paginate($perPage);

        return response()->json($operationalCosts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $operationalCost = OperationalCost::create($validated);

        return response()->json($operationalCost, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationalCost $operationalCost)
    {
        return $operationalCost;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationalCost $operationalCost)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'category' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $operationalCost->update($validated);

        return $operationalCost;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationalCost $operationalCost)
    {
        $operationalCost->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
