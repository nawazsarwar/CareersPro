<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $token_hash
 * @property int $actor_id
 * @property int $target_id
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $consumed_at
 */
class ImpersonationToken extends Model
{
    // M26-R08: every model, with no exemption for the ones whose columns
    // are themselves secrets -- RedactProperties fingerprints those.
    use Auditable;

    /** @var list<string> */
    protected $guarded = [];

    /** @var list<string> */
    protected $hidden = ['token_hash'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}
