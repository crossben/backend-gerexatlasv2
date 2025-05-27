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
            'manager_id' => 'required|exists:managers,id',
            // 'receipt' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payement_method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:paid,pending,failed|max:50',
        ]);

        // Create a new payment using the model
        $payment = Payement::create([
            'unit_id' => $request->unit_id,
            'manager_id' => $request->manager_id,
            // 'receipt' => $request->receipt,
            'amount' => $request->amount,
            'payement_method' => $request->payement_method,
            'reference' => $request->reference ?? uniqid('pay_'),
            'status' => $request->status ?? 'pending',
        ]);

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
            'manager_id' => 'required|exists:managers,id',
            // 'receipt' => 'required|string|max:255',
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
        if ($request->has('manager_id')) {
            $payment->manager_id = $request->manager_id;
        }
        // if ($request->has('receipt')) {
        //     $payment->receipt = $request->receipt;
        // }
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

    public function getPaymentsByManagerId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get all payments for a specific manager
        $payments = Payement::with(['unit', 'building'])
            ->where('manager_id', $request->manager_id)
            ->get();

        \Log::info('Payments retrieved for manager', ['manager_id' => $request->manager_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully by manager ID!',
            'data' => $payments,
        ], 200);
    }
    public function getPaymentsByUnitId(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // Get all payments for a specific unit
        $payments = Payement::where('unit_id', $request->unit_id)->get();

        \Log::info('Payments retrieved for unit', ['unit_id' => $request->unit_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully by unit ID!',
            'data' => $payments,
        ], 200);
    }
    public function getPaymentsByTenantId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:tenants,id',
        ]);

        // Get all payments for a specific tenant
        $payments = Payement::where('manager_id', $request->manager_id)->get();

        \Log::info('Payments retrieved for tenant', ['manager_id' => $request->manager_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully by tenant ID!',
            'data' => $payments,
        ], 200);
    }

    public function getPaymentsByBuildingId(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get all payments for a specific building
        $payments = Payement::where('building_id', $request->building_id)->get();

        \Log::info('Payments retrieved for building', ['building_id' => $request->building_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully by building ID!',
            'data' => $payments,
        ], 200);
    }

    public function getAllPaymentForCurrentMonthByManagerId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get all payments for the current month by manager ID
        $payments = Payement::where('manager_id', $request->manager_id)
            ->whereMonth('created_at', now()->month)
            ->get();

        \Log::info('Payments for current month retrieved for manager', ['manager_id' => $request->manager_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payments for current month retrieved successfully by manager ID!',
            'data' => $payments,
        ], 200);
    }
}
