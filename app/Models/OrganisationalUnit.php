<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $code
 * @property string $path
 * @property string $status
 * @property int|null $parent_id
 */
class OrganisationalUnit extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\OrganisationalUnitFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['parent_id', 'type_id', 'title', 'title_hindi', 'title_urdu', 'code', 'status', 'datalake_id'];

    protected static function booted(): void
    {
        // The path is derived, never supplied. Dean-scoped authorisation runs
        // on it for every admin request, so a hand-written value would be an
        // authorisation defect rather than a data error.
        static::saving(static function (OrganisationalUnit $unit): void {
            // On insert this writes the parent's path only -- the row has no
            // key yet, and its own key is the last segment. The `created` hook
            // below completes it. The column stays NOT NULL throughout, which
            // is what stops a unit ever existing without one.
            $unit->path = $unit->buildPath();
        });

        // A new unit has no key until it is inserted, so the value is
        // completed here.
        static::created(static function (OrganisationalUnit $unit): void {
            $unit->path = $unit->buildPath();
            $unit->saveQuietly();
        });

        // A rename does not move a unit; a re-parent does, and every
        // descendant moves with it.
        static::updated(static function (OrganisationalUnit $unit): void {
            if ($unit->wasChanged('path')) {
                $unit->refreshDescendantPaths();
            }
        });
    }

    /**
     * @return BelongsTo<OrganisationalUnit, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<OrganisationalUnit, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return BelongsTo<OrganisationalUnitType, $this>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(OrganisationalUnitType::class, 'type_id');
    }

    public function buildPath(): string
    {
        $parentPath = $this->parent_id === null
            ? '/'
            : (string) self::query()->whereKey($this->parent_id)->value('path');

        if ($parentPath === '') {
            $parentPath = '/';
        }

        return $this->getKey() === null ? $parentPath : $parentPath.$this->getKey().'/';
    }

    public function refreshDescendantPaths(): void
    {
        foreach ($this->children()->get() as $child) {
            $child->path = $child->buildPath();
            $child->save();
        }
    }

    /**
     * Every unit at or below this one, by one indexed LIKE rather than a
     * recursive walk.
     *
     * @param  Builder<OrganisationalUnit>  $query
     * @return Builder<OrganisationalUnit>
     */
    public function scopeInSubtreeOf(Builder $query, string $path): Builder
    {
        return $query->where('path', 'like', $path.'%');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
