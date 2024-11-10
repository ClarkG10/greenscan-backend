<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'fullname' => 'required',
            'phone_num' => 'nullable',
            'office_location' => 'nullable',
            'office_hours' => 'nullable',
            'department' => 'nullable',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  // Add image validation
        ]);

        // Check if an image file is uploaded
        if ($request->hasFile('image_path')) {
            // Store the image in the 'public/images' directory and get the stored file path
            $imagePath = $request->file('image_path')->store('images', 'public');
        } else {
            $imagePath = null;  // Set null if no image is uploaded
        }

        // Create a new user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'fullname' => $request->fullname,
            'phone_num' => $request->phone_num,
            'office_location' => $request->office_location,
            'office_hours' => $request->office_hours,
            'department' => $request->department,
            'image_path' => $imagePath,  // Save the image path in the 'image' field
        ]);

        return response()->json($user, 201);
    }

    // Retrieve all users
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->keyword) {
            $query->where(function ($query) use ($request) {
                $query->where('fullname', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('phone_num', 'like', '%' . $request->keyword . '%')
                    ->orWhere('department', 'like', '%' . $request->keyword . '%');
            });
        }

        // Paginate the results
        $users = $query->paginate(10);

        return response()->json($users);
    }

    // Retrieve a single user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'email' => 'sometimes|email|unique:App\Models\User,email,' . $id,  // Exclude the current user from uniqueness check
            'role' => 'sometimes',
            'fullname' => 'sometimes',
            'phone_num' => 'sometimes',
            'office_location' => 'sometimes',
            'office_hours' => 'sometimes',
            'department' => 'sometimes',
            'image_path' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',  // Validate image
        ]);

        // If an image is uploaded, store the new image
        if ($request->hasFile('image_path')) {
            // Store the image in the 'public/images' directory and get the stored file path
            $imagePath = $request->file('image_path')->store('images', 'public');

            // Optionally, delete the old image if needed
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }

            // Set the new image path
            $validated['image_path'] = $imagePath;
        }

        // Update the user with the validated data
        $user->update($validated);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }



    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
