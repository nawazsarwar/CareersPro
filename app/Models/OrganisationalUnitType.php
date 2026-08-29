<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $category
 * @property bool $is_recruitment_eligible
 */
class OrganisationalUnitType extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\OrganisationalUnitTypeFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['parent_id', 'title', 'code', 'category', 'is_recruitment_eligible', 'datalake_id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_recruitment_eligible' => 'boolean'];
    }

    /**
     * @return HasMany<OrganisationalUnit, $this>
     */
    public function units(): HasMany
    {
        return $this->hasMany(OrganisationalUnit::class, 'type_id');
    }
}
