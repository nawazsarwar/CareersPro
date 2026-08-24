@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'personal' }">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Applicant Profile</h2>

        @if(session('status'))
            <div class="mb-4 bg-green-100 text-green-800 p-4 rounded">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
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
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
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
                        <label class="flex items-center mt-6">
                            <input type="checkbox" name="pwd_status" value="1" {{ old('pwd_status', $profile->pwd == 'Yes') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Person with Benchmark Disability (PwBD)</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" name="ex_serviceman" value="1" {{ old('ex_serviceman', $profile->ex_serviceman) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ex-Serviceman</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address (Read-only)</label>
                        <input type="email" value="{{ $user->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-600 dark:text-gray-300 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $profile->mobile ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 transition" {{ $profile->locked ? 'disabled' : '' }}>
                    {{ $profile->locked ? 'Profile Locked' : 'Save Profile' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
