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
            'name' => 'required|string|max:255',
            'surface' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:available,rented,under_maintenance|max:50',
            'reference' => 'nullable|string|max:255',
        ]);

        // Use the model to create a new unit
        $unit = Unit::create([
            'building_id' => $request->building_id,
            'name' => $request->name,
            'surface' => $request->surface,
            'type' => $request->type,
            'status' => $request->status ?? 'available',
            'reference' => $request->reference ?? uniqid('unit_'),
        ]);

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
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'surface' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:available,rented,under_maintenance|max:50',
            'reference' => 'nullable|string|max:255',
        ]);

        // Update the unit
        $unit = Unit::find($request->unit_id);
        if ($request->has('building_id')) {
            $unit->building_id = $request->building_id;
        }
        if ($request->has('tenant_id')) {
            $unit->tenant_id = $request->tenant_id;
        }
        if ($request->has('name')) {
            $unit->name = $request->name;
        }
        if ($request->has('surface')) {
            $unit->surface = $request->surface;
        }
        if ($request->has('type')) {
            $unit->type = $request->type;
        }
        if ($request->has('status')) {
            $unit->status = $request->status;
        }
        if ($request->has('reference')) {
            $unit->reference = $request->reference;
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
    public function getUnitById(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // Get the unit with its associated building
        $unit = Unit::with('building')->find($request->unit_id);

        if (!$unit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit not found!',
            ], 404);
        }

        \Log::info('Unit retrieved with building', ['unit_id' => $unit->id]);
        \Log::debug('Retrieved unit details with building', $unit->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Unit retrieved successfully!',
            'data' => $unit,
        ], 200);
    }
    public function getUnitsByBuildingId(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get units by building
        $units = Unit::where('building_id', $request->building_id)->get();

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found for this building!',
            ], 404);
        }
        \Log::info('Units retrieved by building ID', ['building_id' => $request->building_id]);

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

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found!',
            ], 404);
        }

        \Log::info('All units retrieved');
        \Log::debug('Retrieved all units', $units->toArray());

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

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found with this status!',
            ], 404);
        }
        \Log::info('Units retrieved by status', ['status' => $request->status]);
        \Log::debug('Retrieved units with status', $units->toArray());

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

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found for this tenant!',
            ], 404);
        }
        \Log::info('Units retrieved by tenant ID', ['tenant_id' => $request->tenant_id]);
        \Log::debug('Retrieved units for tenant', $units->toArray());

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

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found for this contract!',
            ], 404);
        }

        \Log::info('Units retrieved by contract', ['contract_id' => $request->contract_id]);
        \Log::debug('Retrieved units for contract', $units->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully!',
            'data' => $units,
        ], 200);
    }
}
