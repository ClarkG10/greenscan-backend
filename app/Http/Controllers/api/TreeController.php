<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Tree;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TreeController extends Controller
{
    // Display a listing of trees
    public function list(Request $request)
    {
        $query = Tree::query();

        if ($request->keyword) {
            $query->where(function ($query) use ($request) {
                $query->where('common_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('scientific_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('family_name', 'like', '%' . $request->keyword . '%');
            });
        }

        $tree = $query->get();

        return response()->json($tree);
    }

    // Display a listing of trees
    public function paginateIndex(Request $request)
    {
        $query = Tree::query();

        if ($request->keyword) {
            $query->where(function ($query) use ($request) {
                $query->where('common_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('scientific_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('family_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('iucn_status', 'like', '%' . $request->keyword . '%')
                    ->orWhere('tree_health', 'like', '%' . $request->keyword . '%');
            });
        }

        // Paginate the results
        $trees = $query->paginate(10);

        return response()->json($trees);
    }


    // Store a newly created tree in the database
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'common_name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'economic_use' => 'nullable|string|max:255',
            'iucn_status' => 'nullable|string|max:255',
            'dbh' => 'nullable|numeric',
            'dab' => 'nullable|numeric',
            't_height' => 'nullable|numeric',
            'tree_volume' => 'nullable|numeric',
            'biomass' => 'nullable|numeric',
            'carbon_stored' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'tree_health' => 'nullable|string',
            'price' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'user_id' => 'required|integer',
        ]);

        // Create a new tree record
        $tree = Tree::create($validated);

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Log the update action to the history table
        History::create([
            'tree_id' => $tree->tree_id,
            'user_id' => $userId, // Assuming the user is authenticated
            'action' => 'created',
            'old_data' => null,
            'new_data' => json_encode($tree),
        ]);

        return response()->json(['message' => 'Tree created successfully', 'tree' => $tree]);
    }

    // Display the specified tree details
    public function show($id)
    {
        // Find tree by id
        $tree = Tree::findOrFail($id);

        // Check if tree exists
        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        return response()->json($tree);
    }

    // Update the specified tree details
    public function update(Request $request, string $id)
    {
        // Find the tree by ID
        $tree = Tree::findOrFail($id);

        // Check if tree exists
        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        // Capture old data with default values if fields are null
        $oldData = array_map(function ($value) {
            return $value ?? "N/A";
        }, $tree->toArray());

        // Validate incoming request
        $validated = $request->validate([
            'common_name' => 'sometimes|string|max:255',
            'scientific_name' => 'sometimes|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'economic_use' => 'nullable|string|max:255',
            'iucn_status' => 'nullable|string|max:255',
            'dbh' => 'nullable|numeric',
            'dab' => 'nullable|numeric',
            't_height' => 'nullable|numeric',
            'tree_volume' => 'nullable|numeric',
            'biomass' => 'nullable|numeric',
            'carbon_stored' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'tree_health' => 'nullable|string',
            'price' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Update the tree's details
        $tree->update($validated);

        // Capture new data after update, with default values if fields are null
        $newData = array_map(function ($value) {
            return $value ?? "N/A";
        }, $tree->toArray());

        // Log the update action to the history table
        History::create([
            'tree_id' => $tree->tree_id,
            'user_id' => auth()->user()->id, // Assuming the user is authenticated
            'action' => 'updated',
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($newData),
        ]);

        return response()->json(['message' => 'Tree updated successfully', 'tree' => $tree]);
    }


    // Update tree location based on latitude and longitude (called after QR code scanning)
    public function updateLocation(Request $request, $id)
    {
        // Find the tree by ID
        $tree = Tree::find($id);
        if (!$tree) {
            Log::warning("Tree not found with ID: {$id}");
            return response()->json(['message' => 'Tree not found'], 404);
        }

        // Validate the incoming location data
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Update the tree's location
        $tree->update($validated);

        return response()->json(['message' => 'Tree location updated successfully', 'tree' => $tree]);
    }


    // Remove the specified tree from the database
    public function destroy($id)
    {
        // Find the tree by id
        $tree = Tree::find($id);

        // Check if tree exists
        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        // Delete the tree record
        $tree->delete();

        return response()->json(['message' => 'Tree deleted successfully']);
    }
}
