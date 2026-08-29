<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Authentication Channels (DR-023)
|--------------------------------------------------------------------------
|
| The boot default for how each class of user signs in, and whether a
| verified mobile number is required of them. These values are the floor:
| an administrator may override them per class through `system_settings`
| (M28), and a value stored there wins over a value here.
|
| `default_login_channel` is the channel offered first on the sign-in card.
| Both channels remain reachable for every class -- this decides which one
| the form presents by default, never which one is permitted.
|
*/

return [

    'classes' => [

        'candidate' => [
            'default_login_channel' => 'password',
            'require_verified_mobile' => false,
            'second_factor_required' => false,
        ],

        'staff' => [
            'default_login_channel' => 'password',
            'require_verified_mobile' => true,
            'second_factor_required' => true,
        ],

    ],

    /*
    | Channels a second factor may be delivered over, in the order the
    | picker offers them. `email` is refused for any user holding a staff
    | role -- see M03 §5 and M03-R25 -- which is enforced in the domain,
    | not by removing it here.
    */
    'second_factor_channels' => ['totp', 'sms', 'email'],

];
