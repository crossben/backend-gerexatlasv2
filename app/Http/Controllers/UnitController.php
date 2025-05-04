<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function createUnit(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'unit_number' => 'required|string|max:255',
            'size' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:available,rented,under_maintenance|max:50',
        ]);

        // Create a new unit
        $unit = new Unit();
        $unit->building_id = $request->building_id;
        $unit->unit_number = $request->unit_number;
        $unit->size = $request->size;
        $unit->bedrooms = $request->bedrooms;
        $unit->bathrooms = $request->bathrooms;
        $unit->status = $request->status ?? 'available'; // Default status to available if not provided
        $unit->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit created successfully!',
            'data' => $unit,
        ], 201);
    }

    public function updateUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'unit_number' => 'nullable|string|max:255',
            'size' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:available,rented,under_maintenance|max:50',
        ]);

        // Update the unit
        $unit = Unit::find($request->unit_id);
        if ($request->has('unit_number')) {
            $unit->unit_number = $request->unit_number;
        }
        if ($request->has('size')) {
            $unit->size = $request->size;
        }
        if ($request->has('bedrooms')) {
            $unit->bedrooms = $request->bedrooms;
        }
        if ($request->has('bathrooms')) {
            $unit->bathrooms = $request->bathrooms;
        }
        if ($request->has('status')) {
            $unit->status = $request->status;
        }
        $unit->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit updated successfully!',
            'data' => $unit,
        ], 200);
    }
    public function deleteUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // Delete the unit
        $unit = Unit::find($request->unit_id);
        $unit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit deleted successfully!',
        ], 200);
    }
    public function getUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // Get the unit
        $unit = Unit::find($request->unit_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Unit retrieved successfully!',
            'data' => $unit,
        ], 200);
    }
    public function getUnitsByBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get units by building
        $units = Unit::where('building_id', $request->building_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
    public function getAllUnits(Request $request)
    {
        // Get all units
        $units = Unit::all();

        return response()->json([
            'status' => 'success',
            'message' => 'All units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
    public function getUnitsByStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:available,rented,under_maintenance|max:50',
        ]);

        // Get units by status
        $units = Unit::where('status', $request->status)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
    public function getUnitsByTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Get units by tenant
        $units = Unit::where('tenant_id', $request->tenant_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
    public function getUnitsByContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
        ]);

        // Get units by contract
        $units = Unit::where('contract_id', $request->contract_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
}
