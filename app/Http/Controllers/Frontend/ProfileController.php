<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate([], ['first_name' => $user->name, 'verified' => 0, 'locked' => 0]);

        return view('frontend.profile.index', compact('user', 'profile'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // Split name fallback
        $nameParts = explode(' ', $request->name, 2);

        $profile = $user->profile()->firstOrCreate([], ['first_name' => $user->name, 'verified' => 0, 'locked' => 0]);

        // Prevent editing if locked (Application active state)
        if ($profile->locked) {
            return back()->withErrors(['locked' => 'Your profile is currently locked from editing due to an active application.']);
        }

        $user->update([
            'name' => $request->name,
        ]);

        $profile->update([
            'first_name' => $nameParts[0] ?? $user->name,
            'last_name' => $nameParts[1] ?? '',
            'dob' => $request->dob,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'ex_serviceman' => $request->ex_serviceman ?? 0,
            'pwd' => $request->pwd_status ? 'Yes' : 'No',
        ]);

        return redirect()->route('frontend.profile.index')->with('status', 'Profile updated successfully.');
    }
}
