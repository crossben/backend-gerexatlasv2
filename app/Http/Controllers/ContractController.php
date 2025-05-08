<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function createContract(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'contract_type' => 'required|string|in:fixed,periodic',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,terminated|max:50',
        ]);

        // Create a new contract
        $contract = new Contract();
        $contract->unit_id = $request->unit_id;
        $contract->tenant_id = $request->tenant_id;
        $contract->contract_type = $request->contract_type;
        $contract->start_date = $request->start_date;
        $contract->end_date = $request->end_date;
        $contract->rent_amount = $request->rent_amount;
        $contract->reference = $request->reference;
        $contract->status = $request->status ?? 'active'; // Default status to active if not provided
        $contract->save();

        \Log::info('Contract created', ['contract_id' => $contract->id]);

        \Log::debug('Contract details', $contract->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Contract created successfully!',
            'data' => $contract,
        ], 201);
    }

    public function updateContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'contract_type' => 'required|string|in:fixed,periodic',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,terminated|max:50',
        ]);

        // Update the contract
        $contract = Contract::findOrFail($request->contract_id);
        if ($request->has('tenant_id')) {
            $contract->tenant_id = $request->tenant_id;
        }
        if ($request->has('unit_id')) {
            $contract->unit_id = $request->unit_id;
        }
        if ($request->has('contract_type')) {
            $contract->contract_type = $request->contract_type;
        }
        if ($request->has('start_date')) {
            $contract->start_date = $request->start_date;
        }
        if ($request->has('end_date')) {
            $contract->end_date = $request->end_date;
        }
        if ($request->has('rent_amount')) {
            $contract->rent_amount = $request->rent_amount;
        }
        if ($request->has('reference')) {
            $contract->reference = $request->reference;
        }
        if ($request->has('status')) {
            $contract->status = $request->status;
        }

        $contract->save();

        \Log::info('Contract updated', ['contract_id' => $contract->id]);

        \Log::debug('Updated contract details', $contract->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Contract updated successfully!',
            'data' => $contract,
        ], 200);
    }

    public function deleteContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
        ]);

        // Delete the contract
        $contract = Contract::findOrFail($request->contract_id);
        $contract->delete();

        \Log::info('Contract deleted', ['contract_id' => $contract->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contract deleted successfully!',
        ], 200);
    }

    public function getContracts(Request $request)
    {
        $contracts = Contract::with(['tenant', 'unit'])->get();

        \Log::info('Fetched contracts', ['count' => $contracts->count()]);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ], 200);
    }
    public function getContractsByManagerId(Request $request)
    {
        $contracts = Contract::with(['tenant', 'unit'])->where('manager_id', $request->manager_id)->get();

        \Log::info('Fetched contracts', ['count' => $contracts->count()]);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ], 200);
    }

    public function getContractById($id)
    {
        $contract = Contract::with(['tenant', 'unit'])->findOrFail($id);

        \Log::info('Fetched contract by ID', ['contract_id' => $contract->id]);

        return response()->json([
            'status' => 'success',
            'data' => $contract,
        ], 200);
    }

    public function getContractsByTenantId($tenant_id)
    {
        $contracts = Contract::with(['tenant', 'unit'])->where('tenant_id', $tenant_id)->get();

        \Log::info('Fetched contracts by tenant ID', ['tenant_id' => $tenant_id, 'count' => $contracts->count()]);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ], 200);
    }

    public function getContractsByUnitId($unit_id)
    {
        $contracts = Contract::with(['tenant', 'unit'])->where('unit_id', $unit_id)->get();

        \Log::info('Fetched contracts by unit ID', ['unit_id' => $unit_id, 'count' => $contracts->count()]);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ], 200);
    }
    public function getContractsByBuildingId($building_id)
    {
        $contracts = Contract::with(['tenant', 'unit'])->whereHas('unit', function ($query) use ($building_id) {
            $query->where('building_id', $building_id);
        })->get();

        \Log::info('Fetched contracts by building ID', ['building_id' => $building_id, 'count' => $contracts->count()]);

        return response()->json([
            'status' => 'success',
            'data' => $contracts,
        ], 200);
    }
}
