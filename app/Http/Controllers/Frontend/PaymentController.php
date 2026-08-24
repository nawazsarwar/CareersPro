<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Models\ApplicationForm;
use App\Models\PaymentTransaction;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FrontendPaymentController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function checkout(ApplicationForm $application)
    {
        if ($application->user_id !== Auth::id() || $application->status !== 'Submitted') {
            abort(403);
        }

        $feeAmount = $this->feeService->calculateFee($application->post, Auth::user());

        if ($feeAmount <= 0) {
            // Auto-complete if fee is zero
            $application->update(['status' => 'Paid (Exempt)']);
            return redirect()->route('frontend.home')->with('status', 'Application submitted successfully. Fee Exempted.');
        }

        // Stub Razorpay Order Creation
        $orderId = 'order_' . Str::random(10);
        $transaction = PaymentTransaction::create([
            'user_id' => Auth::id(),
            'application_form_id' => $application->id,
            'transaction_id' => $orderId,
            'amount' => $feeAmount,
            'status' => 'pending'
        ]);

        return view('frontend.payments.checkout', compact('application', 'transaction', 'feeAmount'));
    }

    public function callback(Request $request)
    {
        // Stub Razorpay Webhook/Callback Processing
        $transaction = PaymentTransaction::where('transaction_id', $request->razorpay_order_id)->firstOrFail();

        // Assume signature verification passed
        $transaction->update([
            'payment_id' => $request->razorpay_payment_id,
            'status' => 'success',
            'gateway_response' => json_encode($request->all())
        ]);

        $transaction->applicationForm->update(['status' => 'Paid']);

        return redirect()->route('frontend.home')->with('status', 'Payment successful. Application finalized.');
    }
}
