<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\AuthFactor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property AuthFactor $type
 * @property string|null $secret
 * @property \Illuminate\Support\Carbon|null $confirmed_at
 * @property bool $is_default
 */
class TwoFactorMethod extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = ['user_id', 'type', 'secret', 'confirmed_at', 'is_default'];

    /** @var list<string> */
    protected $hidden = ['secret'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AuthFactor::class,
            'secret' => 'encrypted',
            'confirmed_at' => 'datetime',
            'last_used_at' => 'datetime',
            'is_default' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed_at !== null;
    }
}
