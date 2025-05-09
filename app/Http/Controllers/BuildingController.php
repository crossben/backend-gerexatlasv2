<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    public function createBuilding(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'number_of_units' => 'required|integer|min:1',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Create a new building using the model
        $building = Building::create([
            'manager_id' => $request->manager_id,
            'name' => $request->name,
            'type' => $request->type,
            'number_of_units' => $request->number_of_units,
            'city' => $request->city,
            'address' => $request->address,
            'description' => $request->description,
            'reference' => $request->reference ?? uniqid('imb_'),
            'status' => $request->status ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Building created successfully!',
            'data' => $building,
        ], 201);
    }

    public function updateBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'number_of_units' => 'nullable|integer|min:1',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended|max:50',
        ]);

        // Update the building
        $building = Building::find($request->building_id);
        if ($request->has('name')) {
            $building->name = $request->name;
        }
        if ($request->has('type')) {
            $building->type = $request->type;
        }
        if ($request->has('number_of_units')) {
            $building->number_of_units = $request->number_of_units;
        }
        if ($request->has('city')) {
            $building->city = $request->city;
        }
        if ($request->has('address')) {
            $building->address = $request->address;
        }
        if ($request->has('description')) {
            $building->description = $request->description;
        }
        if ($request->has('reference')) {
            $building->reference = $request->reference;
        }
        if ($request->has('status')) {
            $building->status = $request->status;
        }
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Building updated successfully!',
            'data' => $building,
        ], 200);
    }
    public function deleteBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Delete the building
        $building = Building::find($request->building_id);
        $building->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Building deleted successfully!',
        ], 200);
    }
    public function getBuildingById(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
        ]);

        // Get the building
        $building = Building::find($request->building_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Building retrieved successfully!',
            'data' => $building,
        ], 200);
    }

    public function getAllBuildings(Request $request)
    {
        // Get all buildings
        $buildings = Building::all();

        return response()->json([
            'status' => 'success',
            'message' => 'All buildings retrieved successfully!',
            'data' => $buildings,
        ], 200);
    }
    public function getBuildingsByManagerId(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:managers,id',
        ]);

        // Get buildings by manager
        $buildings = Building::where('manager_id', $request->manager_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Buildings retrieved successfully!',
            'data' => $buildings,
        ], 200);
    }
}
