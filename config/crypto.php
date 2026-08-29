<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Field-level cryptography
|--------------------------------------------------------------------------
|
| Keys used for encrypted columns and blind indexes, kept separate from
| APP_KEY so that either can be rotated without invalidating sessions, signed
| URLs and password-reset tokens (data-protection.md §2).
|
| Rotating the blind-index key invalidates every stored index and requires a
| re-index pass. Rotating APP_KEY does not, and must not be assumed to.
|
*/

return [

    'blind_index_key' => env('BLIND_INDEX_KEY'),

];
