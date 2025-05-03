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

        // Create a new building
        $building = new Building();
        $building->manager_id = $request->manager_id;
        $building->name = $request->name;
        $building->type = $request->type;
        $building->number_of_units = $request->number_of_units;
        $building->city = $request->city;
        $building->address = $request->address;
        $building->description = $request->description;
        $building->reference = $request->reference;
        $building->status = $request->status ?? 'active'; // Default status to active if not provided
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Building created successfully!',
            'data' => $building,
        ], 201);
    }
}
