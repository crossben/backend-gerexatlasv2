<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function createTenant(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        // Create a new tenant
        $tenant = new Tenant();
        $tenant->unit_id = $request->unit_id;
        $tenant->name = $request->name;
        $tenant->email = $request->email;
        $tenant->phone = $request->phone;
        $tenant->nationality = $request->nationality;
        $tenant->reference = $request->reference ?? uniqid('tnt_');
        $tenant->status = $request->status ?? 'active';
        $tenant->save();

        // Create a payment record for the tenant
        $tenant->payments()->create([
            'amount' => 0, // Default amount, adjust as needed
            'reference' => uniqid('pay_'), // Unique reference for the payment
            'status' => 'pending', // Default payment status
        ]);

        \Log::info('Tenant created', ['tenant_id' => $tenant->id]);

        \Log::debug('Tenant details', $tenant->toArray());

        \Log::notice('Tenant creation process completed', ['tenant_id' => $tenant->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant created successfully!',
            'data' => $tenant,
        ], 201);
    }

    public function updateTenant(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended|max:50',
        ]);

        // Update the tenant
        $tenant = Tenant::find($request->tenant_id);
        if ($request->has('unit_id')) {
            $tenant->unit_id = $request->unit_id;
        }
        if ($request->has('name')) {
            $tenant->name = $request->name;
        }
        if ($request->has('email')) {
            $tenant->email = $request->email;
        }
        if ($request->has('phone')) {
            $tenant->phone = $request->phone;
        }
        if ($request->has('nationality')) {
            $tenant->nationality = $request->nationality;
        }
        if ($request->has('reference')) {
            $tenant->reference = $request->reference;
        }
        if ($request->has('status')) {
            $tenant->status = $request->status;
        }
        $tenant->save();

        \Log::info('Tenant updated', ['tenant_id' => $tenant->id]);

        \Log::debug('Updated tenant details', $tenant->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant updated successfully!',
            'data' => $tenant,
        ], 200);
    }
    public function deleteTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Delete the tenant
        $tenant = Tenant::find($request->tenant_id);
        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant not found!',
            ], 404);
        }
        $tenant->delete();

        \Log::info('Tenant deleted', ['tenant_id' => $tenant->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deleted successfully!',
        ], 200);
    }
    public function getTenantById(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Get the tenant details
        $tenant = Tenant::find($request->tenant_id);

        if (!$tenant) {
            \Log::warning('Tenant not found', ['tenant_id' => $request->tenant_id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant not found!',
            ], 404);
        }

        \Log::info('Tenant retrieved', ['tenant_id' => $tenant->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant retrieved successfully!',
            'data' => $tenant,
        ], 200);
    }
    public function getAllTenants(Request $request)
    {
        // Get all tenants
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tenants found!',
            ], 404);
        }
        \Log::info('All tenants retrieved', ['tenant_count' => $tenants->count()]);

        return response()->json([
            'status' => 'success',
            'message' => 'All tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
    public function getTenantsByBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get tenants by building
        $tenants = Tenant::where('building_id', $request->building_id)->get();

        if ($tenants->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tenants found for this building!',
            ], 404);
        }

        \Log::info('Tenants retrieved by building', ['building_id' => $request->building_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
    public function getTenantsByContractId(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
        ]);

        // Get tenants by contract
        $tenants = Tenant::where('contract_id', $request->contract_id)->get();

        if ($tenants->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tenants found for this contract!',
            ], 404);
        }

        \Log::info('Tenants retrieved by contract', ['contract_id' => $request->contract_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
    public function getTenantsByManagerId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get tenants by manager
        $tenants = Tenant::where('manager_id', $request->manager_id)->get();

        if ($tenants->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tenants found for this manager!',
            ], 404);
        }

        \Log::info('Tenants retrieved by manager', ['manager_id' => $request->manager_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
}
