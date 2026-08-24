<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
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
