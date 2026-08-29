<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\Cadre;
use App\Enums\SelectionMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $code
 * @property Cadre $cadre
 * @property string|null $group
 * @property SelectionMethod $selection_method
 * @property int|null $min_age
 * @property int|null $max_age
 */
class Designation extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\DesignationFactory> */
    use HasFactory;

    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'code', 'name', 'name_short', 'cadre', 'group', 'pay_level', 'pay_range',
        'retirement_age', 'min_age', 'max_age', 'essential_qualification',
        'desirable_qualification', 'experience_rules', 'method_of_recruitment',
        'committee_composition', 'selection_method', 'status', 'remarks',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cadre' => Cadre::class,
            'selection_method' => SelectionMethod::class,
            'essential_qualification' => 'array',
            'desirable_qualification' => 'array',
            'experience_rules' => 'array',
            'method_of_recruitment' => 'array',
            'committee_composition' => 'array',
        ];
    }

    /**
     * @return BelongsToMany<OrganisationalUnit, $this>
     */
    public function organisationalUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrganisationalUnit::class, 'organisational_unit_designation')
            ->withPivot(['sanctioned_count', 'filled_count', 'sanction_order_ref', 'sanctioned_on'])
            ->withTimestamps();
    }

    /**
     * Master data is university-wide reference material: it describes posts,
     * not people, and carries no organisational unit of its own. Restricting
     * it by subtree would leave a Dean's-office user unable to read the
     * definition of the post they are scrutinising.
     *
     * @param  Builder<Designation>  $query
     * @return Builder<Designation>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
