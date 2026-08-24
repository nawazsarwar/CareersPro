@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Applicant Dashboard</h1>
        <a href="{{ route('frontend.posts.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition">
            Find New Vacancies
        </a>
    </div>

    @if(session('status'))
        <div class="mb-4 bg-green-100 text-green-800 p-4 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Applications</h3>
        </div>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($applications as $app)
                <li class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                {{ $app->post->title ?? 'Unknown Post' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Applied on: {{ $app->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $app->status === 'Paid' || $app->status === 'Paid (Exempt)' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $app->status }}
                            </span>

                            <!-- Actions -->
                            @if($app->status === 'Submitted')
                                <a href="{{ route('frontend.payments.checkout', $app->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Pay Fee
                                </a>
                            @elseif($app->status === 'Paid' || $app->status === 'Paid (Exempt)')
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                    Download PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-gray-500 dark:text-gray-400">
                    You have not applied to any vacancies yet.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
