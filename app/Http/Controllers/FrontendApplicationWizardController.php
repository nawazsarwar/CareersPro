<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendApplicationWizardController extends Controller
{
    public function create(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        return view('frontend.applicationForms.wizard', compact('post'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'declaration' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Ensure profile is complete
        if (!$user->profile) {
            return back()->withErrors(['profile' => 'Please complete your profile before applying.']);
        }

        // Snapshotting claims (Stubbed logic for Application Locking)
        // In real execution, we duplicate the current profile/academic records to a versioned table/JSON payload.

        $application = ApplicationForm::create([
            'user_id' => $user->id,
            'post_id' => $validated['post_id'],
            'status' => 'Submitted',
            // snapshot_payload => json_encode($user->load('profile', 'academic_qualifications', 'research_publications')),
        ]);

        return redirect()->route('frontend.posts.index')->with('status', 'Application successfully submitted and locked.');
    }
}
