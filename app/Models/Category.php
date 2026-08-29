<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A vertical category: UR, SC, ST, OBC-NCL, EWS.
 *
 * Orthogonal to HorizontalCategory. A candidate is SC *and* a person with
 * disability, never one instead of the other, which is why these are two
 * tables and not one enum.
 */
class Category extends MasterDataModel
{
    /** @var list<string> */
    protected $fillable = ['code', 'name', 'requires_certificate', 'certificate_expires'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'requires_certificate' => 'boolean',
            'certificate_expires' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Caste, $this>
     */
    public function castes(): HasMany
    {
        return $this->hasMany(Caste::class);
    }
}
