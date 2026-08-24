@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Document Vault: Photo & Signature</h2>

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

        <form action="{{ route('frontend.photos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Photograph Upload -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Passport Size Photograph</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Format: JPG/PNG. Max size: 2MB.</p>

                    @if($photoRecord->photo)
                        <div class="mb-4 flex justify-center">
                            <img src="{{ $photoRecord->photo->getUrl() }}" alt="Current Photo" class="h-32 w-32 object-cover rounded shadow">
                        </div>
                    @endif

                    <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-white">
                </div>

                <!-- Signature Upload -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Scanned Signature</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Format: JPG/PNG. Max size: 2MB.</p>

                    @if($photoRecord->signature)
                        <div class="mb-4 flex justify-center">
                            <img src="{{ $photoRecord->signature->getUrl() }}" alt="Current Signature" class="h-16 w-32 object-contain border bg-white shadow">
                        </div>
                    @endif

                    <input type="file" name="signature" accept=".jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-white">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 transition">Upload Documents</button>
            </div>
        </form>
    </div>
</div>
@endsection
