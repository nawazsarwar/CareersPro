<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Profile;
use App\Models\Advertisement;
use App\Models\Post;

class HomeController
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'profiles' => Profile::count(),
            'advertisements' => Advertisement::count(),
            'posts' => Post::count(),
        ];

        return view('home', compact('stats'));
    }
}
