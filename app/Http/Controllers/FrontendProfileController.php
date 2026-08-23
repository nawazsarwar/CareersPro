<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate([]);

        return view('frontend.profile.index', compact('user', 'profile'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        $profile = $user->profile()->firstOrCreate([]);
        $profile->update($request->validated());

        return redirect()->route('frontend.profile.index')->with('status', 'Profile updated successfully.');
    }
}
