@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Complete Your Payment</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">You are paying the processing fee for the post of <strong>{{ $application->post->title }}</strong>.</p>

        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-8">
            ₹{{ number_format($feeAmount, 2) }}
        </div>

        <form action="{{ route('frontend.payments.callback') }}" method="POST">
            @csrf
            <!-- Simulated Razorpay Inputs -->
            <input type="hidden" name="razorpay_order_id" value="{{ $transaction->transaction_id }}">
            <input type="hidden" name="razorpay_payment_id" value="pay_{{ Str::random(10) }}">

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105">
                Pay with Razorpay (Simulated)
            </button>
        </form>
    </div>
</div>
@endsection
