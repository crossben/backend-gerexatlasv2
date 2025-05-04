<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payement;

class PayementController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'status' => 'nullable|string|in:paid,pending,failed|max:50',
        ]);

        // Create a new payment
        $payment = new Payement();
        $payment->contract_id = $request->contract_id;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->payment_method = $request->payment_method;
        $payment->status = $request->status ?? 'pending'; // Default status to pending if not provided
        $payment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully!',
            'data' => $payment,
        ], 201);
    }

    public function updatePayement(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:paid,pending,failed|max:50',
        ]);

        // Update the payment
        $payment = Payement::find($request->payment_id);
        if ($request->has('amount')) {
            $payment->amount = $request->amount;
        }
        if ($request->has('payment_date')) {
            $payment->payment_date = $request->payment_date;
        }
        if ($request->has('payment_method')) {
            $payment->payment_method = $request->payment_method;
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
            'payment_id' => 'required|exists:payments,id',
        ]);

        // Delete the payment
        $payment = Payement::find($request->payment_id);
        $payment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment deleted successfully!',
        ], 200);
    }
    public function getPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        // Get the payment details
        $payment = Payement::find($request->payment_id);

        \Log::info('Payment retrieved', ['payment_id' => $payment->id]);

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
