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

];
