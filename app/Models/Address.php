<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use Auditable;

    protected $table = 'addresses';

    /** @var list<string> */
    protected $fillable = ['user_id', 'type', 'line1', 'line2', 'city', 'district_id', 'province_id', 'postal_code'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ownership only. Every one of these is a candidate's own record, and
     * staff read them through the application they were submitted with, never
     * directly -- which is the distinction the previous build lost when it
     * copied admin CRUD into the frontend namespace.
     *
     * @param  Builder<Address>  $query
     * @return Builder<Address>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->getKey() : null);
    }
}
