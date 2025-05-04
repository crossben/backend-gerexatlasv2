<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function createTenant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Create a new tenant
        $tenant = new Tenant();
        $tenant->name = $request->name;
        $tenant->email = $request->email;
        $tenant->phone = $request->phone;
        $tenant->address = $request->address;
        $tenant->city = $request->city;
        $tenant->status = $request->status ?? 'active'; // Default status to active if not provided
        $tenant->save();

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
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:tenants,email,' . $request->tenant_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Update the tenant
        $tenant = Tenant::find($request->tenant_id);
        if ($request->has('name')) {
            $tenant->name = $request->name;
        }
        if ($request->has('email')) {
            $tenant->email = $request->email;
        }
        if ($request->has('phone')) {
            $tenant->phone = $request->phone;
        }
        if ($request->has('address')) {
            $tenant->address = $request->address;
        }
        if ($request->has('city')) {
            $tenant->city = $request->city;
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
        $tenant->delete();

        \Log::info('Tenant deleted', ['tenant_id' => $tenant->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deleted successfully!',
        ], 200);
    }
    public function getTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Get the tenant details
        $tenant = Tenant::find($request->tenant_id);

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

        \Log::info('All tenants retrieved');

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

        \Log::info('Tenants retrieved by building', ['building_id' => $request->building_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
    public function getTenantsByContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
        ]);

        // Get tenants by contract
        $tenants = Tenant::where('contract_id', $request->contract_id)->get();

        \Log::info('Tenants retrieved by contract', ['contract_id' => $request->contract_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
    public function getTenantsByManager(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get tenants by manager
        $tenants = Tenant::where('manager_id', $request->manager_id)->get();

        \Log::info('Tenants retrieved by manager', ['manager_id' => $request->manager_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully!',
            'data' => $tenants,
        ], 200);
    }
}
