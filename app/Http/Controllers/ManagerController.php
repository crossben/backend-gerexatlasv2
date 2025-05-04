<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager;

class ManagerController extends Controller
{
    public function RegisterManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:managers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Create a new manager
        $manager = new Manager();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        $manager->address = $request->address;
        $manager->city = $request->city;
        $manager->status = $request->status ?? 'active'; // Default status to active if not provided
        $manager->save();

        \Log::info('Manager created', ['manager_id' => $manager->id]);

        \Log::debug('Manager details', $manager->toArray());


        \Log::notice('Manager creation process completed', ['manager_id' => $manager->id]);


        return response()->json([
            'status' => 'success',
            'message' => 'Manager created successfully!',
            'data' => $manager,
        ], 201);
    }

    public function loginManager(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Authenticate the manager
        if (auth()->attempt($request->only('email', 'password'))) {
            $manager = auth()->user();

            \Log::info('Manager logged in', ['manager_id' => $manager->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful!',
                'data' => $manager,
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

    public function updateManager(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:managers,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Find the manager
        $manager = Manager::findOrFail($id);

        // Update the manager details
        if ($request->has('name')) {
            $manager->name = $request->name;
        }
        if ($request->has('email')) {
            $manager->email = $request->email;
        }
        if ($request->has('phone')) {
            $manager->phone = $request->phone;
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
    public function deleteManager($id)
    {
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
    public function getManager($id)
    {
        // Find the manager
        $manager = Manager::findOrFail($id);

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
