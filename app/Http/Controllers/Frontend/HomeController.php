<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;

class FrontendHomeController extends Controller
{
    /**
     * Show the applicant dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch current applications for the dashboard
        $applications = ApplicationForm::with('post')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.home', compact('applications'));
    }
}
