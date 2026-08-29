<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * What a data principal agreed to, and to which version of the notice.
 *
 * Append-only by intent: consent is evidence of a past state, so a withdrawal
 * is a new record rather than an edit to the old one.
 */
class ConsentRecord extends Model
{
    // M26-R08: every model, with no exemption for the ones whose columns
    // are themselves secrets -- RedactProperties fingerprints those.
    use Auditable;

    public $timestamps = false;

    /** @var list<string> */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purposes' => 'array',
            'recorded_at' => 'immutable_datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
