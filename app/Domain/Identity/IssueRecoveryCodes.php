<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Ten single-use codes, shown once at enrolment and never again.
 *
 * Stored hashed, so a database read does not yield a set of working second
 * factors. "Never again" is the security property: a code the user can
 * re-display is a code an attacker with a live session can re-display.
 */
final class IssueRecoveryCodes
{
    private const COUNT = 10;

    /**
     * @return list<string>
     */
    public function handle(User $user): array
    {
        return DB::transaction(function () use ($user): array {
            $user->recoveryCodes()->delete();

            $codes = [];

            for ($i = 0; $i < self::COUNT; $i++) {
                $code = strtolower(Str::random(5).'-'.Str::random(5));
                $codes[] = $code;

                $user->recoveryCodes()->create(['code_hash' => Hash::make($code)]);
            }

            return $codes;
        });
    }
}
