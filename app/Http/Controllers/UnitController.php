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
            'manager_id' => 'nullable|exists:managers,id',
            'name' => 'required|string|max:255',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_email' => 'nullable|email|max:255',
            'tenant_phone' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'nullable|numeric|min:0',
            'contract_type' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:disponible,louer,sous_maintenance,indisponible|max:50',
        ]);

        // Use the model to create a new unit
        $unit = Unit::create([
            'building_id' => $request->building_id,
            'manager_id' => $request->manager_id,
            'name' => $request->name,
            'tenant_name' => $request->tenant_name,
            'tenant_email' => $request->tenant_email,
            'tenant_phone' => $request->tenant_phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'rent_amount' => $request->rent_amount,
            'contract_type' => $request->contract_type,
            'reference' => $request->reference ?? uniqid('unit_'),
            'status' => $request->status ?? 'available',
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
            'manager_id' => 'nullable|exists:managers,id',
            'name' => 'required|string|max:255',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_email' => 'nullable|email|max:255',
            'tenant_phone' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'nullable|numeric|min:0',
            'contract_type' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:disponible,louer,sous_maintenance,indisponible|max:50',
        ]);

        // Update the unit
        $unit = Unit::find($request->unit_id);
        if ($request->has('building_id')) {
            $unit->building_id = $request->building_id;
        }
        if ($request->has('manager_id')) {
            $unit->manager_id = $request->manager_id;
        }
        if ($request->has('name')) {
            $unit->name = $request->name;
        }
        if ($request->has('tenant_name')) {
            $unit->tenant_name = $request->tenant_name;
        }
        if ($request->has('tenant_email')) {
            $unit->tenant_email = $request->tenant_email;
        }
        if ($request->has('tenant_phone')) {
            $unit->tenant_phone = $request->tenant_phone;
        }
        if ($request->has('start_date')) {
            $unit->start_date = $request->start_date;
        }
        if ($request->has('end_date')) {
            $unit->end_date = $request->end_date;
        }
        if ($request->has('rent_amount')) {
            $unit->rent_amount = $request->rent_amount;
        }
        if ($request->has('contract_type')) {
            $unit->contract_type = $request->contract_type;
        }
        if ($request->has('reference')) {
            $unit->reference = $request->reference;
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

    public function getUnitById(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // Get the unit with its associated building
        $unit = Unit::with(['building', 'payements'])->find($request->unit_id);

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

    public function getUnitsByManagerId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get units by manager
        $units = Unit::where('manager_id', $request->manager_id)->with('building')->get();

        if ($units->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No units found for this manager!',
            ], 404);
        }

        \Log::info('Units retrieved by manager ID', ['manager_id' => $request->manager_id]);

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
