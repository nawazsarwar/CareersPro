@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showForm: false }">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Research Publications & API Claims</h2>
        <button @click="showForm = !showForm" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
            <span x-text="showForm ? 'Cancel' : 'Add Publication'"></span>
        </button>
    </div>

    @if(session('status'))
        <div class="mb-4 bg-green-100 text-green-800 p-4 rounded">{{ session('status') }}</div>
    @endif

    <div x-show="showForm" x-cloak class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <form action="{{ route('frontend.research-publications.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Publication Type</label>
                    <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="Journal">Journal Article</option>
                        <option value="Book">Book</option>
                        <option value="Chapter">Book Chapter</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Journal / Publisher</label>
                    <input type="text" name="publisher_journal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Authorship Position</label>
                    <select name="authorship_position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="First">First Author</option>
                        <option value="Corresponding">Corresponding Author</option>
                        <option value="Co-Author">Co-Author</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Impact Factor</label>
                    <input type="number" step="0.01" name="impact_factor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex items-center space-x-4 pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_peer_reviewed" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Peer Reviewed</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_ugc_care_listed" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">UGC-CARE Listed</span>
                    </label>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">Save Publication</button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UGC API Flags</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($publications as $pub)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $pub->type }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white max-w-xs truncate">{{ $pub->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $pub->authorship_position }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $pub->is_peer_reviewed ? 'PR' : '' }} {{ $pub->is_ugc_care_listed ? '| UGC' : '' }} (IF: {{ $pub->impact_factor ?? '0' }})
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No publications added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
