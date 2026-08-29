<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Payment (DR-018)
|--------------------------------------------------------------------------
|
| The gateway is frozen onto an advertisement at publish, so orders created
| under it always use the gateway that was in force when candidates read the
| terms. Changing the default here affects future advertisements only.
|
*/

return [

    'default_gateway' => env('PAYMENT_GATEWAY', 'razorpay'),

    /*
    | The registry lives here rather than in App\Domain\Payment so that no
    | vendor is named anywhere in the domain (DR-018). Adding a gateway is an
    | entry here and an adapter class; nothing else moves.
    */
    'gateways' => [
        'mock' => App\Domain\Payment\Gateways\MockGateway::class,
        'razorpay' => App\Domain\Payment\Gateways\RazorpayGateway::class,
        'billdesk' => App\Domain\Payment\Gateways\BilldeskGateway::class,
    ],

    'razorpay' => [
        'key_id' => env('RAZORPAY_KEY_ID'),
        'key_secret' => env('RAZORPAY_KEY_SECRET'),
    ],

    'billdesk' => [
        'merchant_id' => env('BILLDESK_MERCHANT_ID'),
        'security_id' => env('BILLDESK_SECURITY_ID'),
        'checksum_key' => env('BILLDESK_CHECKSUM_KEY'),
    ],

];
