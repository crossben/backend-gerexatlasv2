<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function createContract(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'payment_frequency' => 'required|string|in:monthly,quarterly,yearly',
            'status' => 'nullable|string|in:active,inactive,terminated|max:50',
        ]);

        // Create a new contract
        $contract = new Contract();
        $contract->building_id = $request->building_id;
        $contract->tenant_id = $request->tenant_id;
        $contract->start_date = $request->start_date;
        $contract->end_date = $request->end_date;
        $contract->rent_amount = $request->rent_amount;
        $contract->payment_frequency = $request->payment_frequency;
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
}
