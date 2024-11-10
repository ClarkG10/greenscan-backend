<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    // Get all history records for a specific tree
    public function index(Request $request)
    {
        $query = History::query();

        if ($request->keyword) {
            $query->where(function ($query) use ($request) {
                $query->where('tree_id', 'like', '%' . $request->keyword . '%')
                    ->orWhere('action', 'like', '%' . $request->keyword . '%');
            });
        }

        // Order by created_at in ascending order
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $history = $query->paginate(10);

        return response()->json($history);
    }

    // Get a specific history record by id
    public function show($id)
    {
        // Fetch the specific history record
        $history = History::with('tree', 'user')->find($id);

        if (!$history) {
            return response()->json(['message' => 'History record not found'], 404);
        }

        return response()->json($history);
    }
}
