<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{

    public function registerManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:managers,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $manager = Manager::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'reference' => uniqid('mgr_'), // Generate a unique reference
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return response()->json([
            'token' => $manager->createToken('manager-api-token')->plainTextToken,
            'manager' => $manager,
        ], 201);
    }


    // public function loginManager(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $manager = Manager::where('email', $request->email)->first();

    //     if (!$manager || !Hash::check($request->password, $manager->password)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }

    //     return response()->json([
    //         'token' => $manager->createToken('manager-api-token')->plainTextToken,
    //         'manager' => $manager,
    //     ]);
    // }


    public function loginManager(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Authenticate the manager
        $manager = Manager::where('email', $request->email)->first();

        if ($manager && Hash::check($request->password, $manager->password)) {
            \Log::info('Manager logged in', ['manager_id' => $manager->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful!',
                'data' => $manager,
                'token' => $manager->createToken('manager-api-token')->plainTextToken,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        \Log::info('Manager logged out');

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful!',
        ], 200);
    }

    public function updateManager(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|integer|exists:managers,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            // 'email' => 'sometimes|required|email|max:255|unique:managers,email',
            'phone' => 'sometimes|required|string|max:20',
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|nullable|string|in:admin,manager|max:50',
            'reference' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|in:active,inactive,suspended|max:50',
        ]);
        $manager = Manager::findOrFail($request->manager_id);
        if ($request->has('first_name')) {
            $manager->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
            $manager->last_name = $request->last_name;
        }
        if ($request->has('email')) {
            $manager->email = $request->email;
        }
        if ($request->has('phone')) {
            $manager->phone = $request->phone;
        }
        if ($request->has('password')) {
            $manager->password = Hash::make($request->password);
        }
        if ($request->has('role')) {
            $manager->role = $request->role;
        }
        if ($request->has('reference')) {
            $manager->reference = $request->reference;
        }
        if ($request->has('address')) {
            $manager->address = $request->address;
        }
        if ($request->has('city')) {
            $manager->city = $request->city;
        }
        if ($request->has('status')) {
            $manager->status = $request->status;
        }
        if ($request->has('country')) {
            $manager->country = $request->country;
        }

        // Save the updated manager
        $manager->save();

        \Log::info('Manager updated', ['manager_id' => $manager->id]);

        \Log::debug('Updated manager details', $manager->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Manager updated successfully!',
            'data' => $manager,
        ], 200);
    }

    public function deleteManager(Request $request, $id)
    {
        // Validate the ID
        $request->validate([
            'id' => 'required|integer|exists:managers,id',
        ]);

        // Check if the manager is logged in
        if (auth()->user()->id != $id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this manager.',
            ], 403);
        }

        // Find the manager
        $manager = Manager::findOrFail($id);

        // Delete the manager
        $manager->delete();

        \Log::info('Manager deleted', ['manager_id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Manager deleted successfully!',
        ], 200);
    }

    public function getManagerById(Request $request)
    {
        // Find the manager
        $manager = Manager::findOrFail($request->manager_id);

        \Log::info('Manager retrieved', ['manager_id' => $manager->id]);

        return response()->json([
            'status' => 'success',
            'data' => $manager,
        ], 200);
    }

    public function getAllManagers()
    {
        // Get all managers
        $managers = Manager::all();

        \Log::info('All managers retrieved');

        return response()->json([
            'status' => 'success',
            'data' => $managers,
        ], 200);
    }
    public function getManagersByStatus($status)
    {
        // Validate the status
        if (!in_array($status, ['active', 'inactive', 'suspended'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid status provided.',
            ], 400);
        }

        // Get managers by status
        $managers = Manager::where('status', $status)->get();

        \Log::info('Managers retrieved by status', ['status' => $status]);

        return response()->json([
            'status' => 'success',
            'data' => $managers,
        ], 200);
    }
}
