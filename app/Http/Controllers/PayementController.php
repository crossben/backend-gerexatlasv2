<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payement;

class PayementController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'receipt' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payement_method' => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'status' => 'nullable|string|in:paid,pending,failed|max:50',
        ]);

        // Create a new payment
        $payment = new Payement();
        $payment->unit_id = $request->unit_id;
        $payment->tenant_id = $request->tenant_id;
        $payment->receipt = $request->receipt;
        $payment->amount = $request->amount;
        $payment->payement_method = $request->payement_method;
        $payment->reference = $request->reference;
        $payment->status = $request->status ?? 'pending'; // Default to 'pending' if not provided
        $payment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully!',
            'data' => $payment,
        ], 201);
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'payement_id' => 'required|exists:payements,id',
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'receipt' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payement_method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:paid,pending,failed|max:50',
        ]);

        // Update the payment
        $payment = Payement::find($request->payement_id);
        if ($request->has('unit_id')) {
            $payment->unit_id = $request->unit_id;
        }
        if ($request->has('tenant_id')) {
            $payment->tenant_id = $request->tenant_id;
        }
        if ($request->has('receipt')) {
            $payment->receipt = $request->receipt;
        }
        if ($request->has('amount')) {
            $payment->amount = $request->amount;
        }
        if ($request->has('payement_method')) {
            $payment->payement_method = $request->payement_method;
        }
        if ($request->has('reference')) {
            $payment->reference = $request->reference;
        }
        if ($request->has('status')) {
            $payment->status = $request->status;
        }
        $payment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully!',
            'data' => $payment,
        ], 200);
    }

    public function deletePayement(Request $request)
    {
        $request->validate([
            'payement_id' => 'required|exists:payements,id',
        ]);

        // Delete the payment
        $payment = Payement::find($request->payement_id);
        $payment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment deleted successfully!',
        ], 200);
    }
    public function getPaymentById(Request $request)
    {
        $request->validate([
            'payement_id' => 'required|exists:payements,id',
        ]);

        // Get the payment details
        $payment = Payement::find($request->payement_id);

        \Log::info('Payment retrieved', ['payement_id' => $payment->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment retrieved successfully!',
            'data' => $payment,
        ], 200);
    }

    public function getAllPayments(Request $request)
    {
        $payments = Payement::all();

        \Log::info('All payments retrieved');

        return response()->json([
            'status' => 'success',
            'message' => 'All payments retrieved successfully!',
            'data' => $payments,
        ], 200);
    }
    public function getPaymentsByContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
        ]);

        // Get all payments for a specific contract
        $payments = Payement::where('contract_id', $request->contract_id)->get();

        \Log::info('Payments retrieved for contract', ['contract_id' => $request->contract_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully!',
            'data' => $payments,
        ], 200);
    }
    public function getPaymentsByTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Get all payments for a specific tenant
        $payments = Payement::whereHas('contract', function ($query) use ($request) {
            $query->where('tenant_id', $request->tenant_id);
        })->get();

        \Log::info('Payments retrieved for tenant', ['tenant_id' => $request->tenant_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully!',
            'data' => $payments,
        ], 200);
    }
    public function getPaymentsByBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get all payments for a specific building
        $payments = Payement::whereHas('contract', function ($query) use ($request) {
            $query->where('building_id', $request->building_id);
        })->get();

        \Log::info('Payments retrieved for building', ['building_id' => $request->building_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully!',
            'data' => $payments,
        ], 200);
    }
}
