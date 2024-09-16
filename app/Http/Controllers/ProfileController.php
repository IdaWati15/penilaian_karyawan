<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Added this line

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('profile_image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->profile_image->extension();  
            $request->profile_image->move(public_path('images'), $imageName);
            $user->profile_image = 'images/' . $imageName;
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update password: ' . $e->getMessage()]);
        }

        return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
    }
}