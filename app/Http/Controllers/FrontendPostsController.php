<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrontendPostsController extends Controller
{
    /**
     * Display a listing of the public vacancies.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // Implement rich filters
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(10);

        Log::info("Found " . $posts->count() . " posts.");

        if (!\Gate::allows('post_access')) {
            abort(403);
        }

        return view('frontend.posts.index', compact('posts'));
    }

    /**
     * Display the specified advertisement detail.
     */
    public function show(Post $post)
    {
        return view('frontend.posts.show', compact('post'));
    }
}
