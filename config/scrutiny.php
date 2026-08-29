<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Scrutiny
|--------------------------------------------------------------------------
|
| The rectification window is the differentiator the legacy portal and
| CU-Chayan both lack. Its length is configurable because it is a University
| policy decision, not a statutory one -- but it always has an end, and the
| end is always stated to the candidate.
|
*/

return [

    'rectification_window_days' => (int) env('SCRUTINY_RECTIFICATION_DAYS', 7),

];
