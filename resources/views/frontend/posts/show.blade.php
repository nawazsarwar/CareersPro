@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">{{ $post->description ?? 'No description provided.' }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 border-t pt-6 dark:border-gray-700">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->subject ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Vacancies</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->vacancies ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->location ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pay Level</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->pay_level ?? '-' }} ({{ $post->pay_range ?? '-' }})</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fee</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">₹{{ number_format($post->fee ?? 0, 2) }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->status ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 border-t pt-6 dark:border-gray-700">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Opening Date</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->opening_date ? \Carbon\Carbon::parse($post->opening_date)->format('d M, Y') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Closing Date</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->closing_date ? \Carbon\Carbon::parse($post->closing_date)->format('d M, Y') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Closing Date</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->payment_closing_date ? \Carbon\Carbon::parse($post->payment_closing_date)->format('d M, Y') : '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 border-t pt-6 dark:border-gray-700">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Test Date</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->test_date ? \Carbon\Carbon::parse($post->test_date)->format('d M, Y') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Interview Date</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->interview_date ? \Carbon\Carbon::parse($post->interview_date)->format('d M, Y') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Interview Venue</h3>
                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $post->interview_venue ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <a href="{{ route('frontend.application-forms.create', ['post_id' => $post->id]) }}" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-3 rounded-lg font-medium shadow-sm transition">
                Apply Now
            </a>
            <a href="{{ route('frontend.posts.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 px-6 py-3 rounded-lg font-medium shadow-sm transition">
                Back to Vacancies
            </a>
        </div>
    </div>
</div>
@endsection
