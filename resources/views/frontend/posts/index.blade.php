@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Public Vacancies</h1>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-8">
        <form action="{{ route('frontend.posts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" placeholder="Search vacancies..." class="flex-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" value="{{ request('search') }}">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Search</button>
        </form>
    </div>

    <!-- Vacancy List -->
    <div class="grid gap-6">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $post->title }}</h2>
                <a href="{{ route('frontend.posts.show', $post) }}" class="text-blue-500 hover:underline mt-2 inline-block">View Details</a>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No active vacancies found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
