@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'personal' }">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Applicant Profile</h2>

        @if(session('status'))
            <div class="mb-4 bg-green-100 text-green-800 p-4 rounded">{{ session('status') }}</div>
        @endif

        <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
            <button @click="activeTab = 'personal'" :class="{'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'personal', 'text-gray-500 hover:text-gray-700': activeTab !== 'personal'}" class="py-2 px-4 font-medium text-sm focus:outline-none">Personal Details</button>
            <button @click="activeTab = 'contact'" :class="{'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'contact', 'text-gray-500 hover:text-gray-700': activeTab !== 'contact'}" class="py-2 px-4 font-medium text-sm focus:outline-none">Contact Info</button>
        </div>

        <form action="{{ route('frontend.profile.update') }}" method="POST">
            @csrf

            <!-- Personal Tab -->
            <div x-show="activeTab === 'personal'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                        <select name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $profile->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $profile->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $profile->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Category</option>
                            <!-- Would normally map categories here -->
                            <option value="1">General / UR</option>
                            <option value="2">OBC (NCL)</option>
                            <option value="3">SC</option>
                            <option value="4">ST</option>
                            <option value="5">EWS</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" readonly>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
