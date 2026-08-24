<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Models\ResearchPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendResearchPublicationsController extends Controller
{
    public function index()
    {
        $publications = ResearchPublication::where('user_id', Auth::id())->get();
        return view('frontend.researchPublications.index', compact('publications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string',
            'publisher_journal' => 'required|string',
            'is_peer_reviewed' => 'boolean',
            'is_ugc_care_listed' => 'boolean',
            'impact_factor' => 'nullable|numeric',
            'authorship_position' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        ResearchPublication::create($validated);

        return redirect()->route('frontend.research-publications.index')->with('status', 'Publication added for API calculation.');
    }
}
