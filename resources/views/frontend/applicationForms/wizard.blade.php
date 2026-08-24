@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ step: 1 }">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Application Wizard</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Applying for: <strong>{{ $post->title }}</strong></p>

        @if($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step 1: Verification -->
        <div x-show="step === 1">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Step 1: Verify Your Profile</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Please ensure your Profile, Academic Qualifications, and Research Publications are completely updated. <strong>Once you submit this application, your records will be locked for this specific post and cannot be edited.</strong></p>

            <div class="flex justify-end mt-6">
                <button @click="step = 2" type="button" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 transition">Next Step</button>
            </div>
        </div>

        <!-- Step 2: Final Submission -->
        <div x-show="step === 2" x-cloak>
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Step 2: Declaration & Submit</h3>
            <form action="{{ route('frontend.application-forms.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="declaration" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">I hereby declare that all information provided in my profile and attachments is true and correct. I understand that submitting this application will lock my claims for this post.</span>
                    </label>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="step = 1" type="button" class="bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-white px-6 py-2 rounded shadow hover:bg-gray-400 transition">Back</button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Confirm & Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
