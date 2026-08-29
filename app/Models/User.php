<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Placeholder.
 *
 * Wave 1 (M03) owns this model and the `users` migration together: the
 * dual-identifier `username` column, the status enum, the login-channel
 * preference and the second-factor relations all arrive there, with the
 * table that backs them.
 *
 * It exists now only because config/auth.php names it as the provider model,
 * so the container cannot resolve the guard without it. It has no table until
 * Wave 1 creates one.
 *
 * HasApiTokens is present from the start: its absence, together with a missing
 * sanctum guard and no personal_access_tokens table, is why 27 endpoints sat
 * behind auth:sanctum that could never authenticate anyone (M29 §2).
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
