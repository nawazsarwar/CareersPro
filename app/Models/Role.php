<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\RoleSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $slug
 * @property bool $is_system
 * @property bool $requires_organisational_unit
 */
class Role extends Model
{
    // M26-R08. The previous trait covered 27 of 34 models and omitted this
    // one, so role changes -- the most security-relevant edits in the system
    // -- went unrecorded.
    use Auditable;

    /** @var list<string> */
    protected $fillable = ['name', 'slug', 'description', 'is_system', 'requires_organisational_unit'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'requires_organisational_unit' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('organisational_unit_id');
    }

    public function enum(): ?RoleSlug
    {
        return RoleSlug::tryFrom($this->slug);
    }
}
