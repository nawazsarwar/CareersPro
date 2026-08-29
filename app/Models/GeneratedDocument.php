<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedDocument extends Model
{
    use Auditable;

    protected $table = 'generated_documents';

    /** @var list<string> */
    protected $fillable = ['application_id', 'post_id', 'type', 'disk', 'path', 'content_hash', 'verification_code', 'generated_by_id', 'generated_at'];

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Scoped through the application, which carries both the ownership and the
     * organisational-unit answer.
     *
     * @param  Builder<GeneratedDocument>  $query
     * @return Builder<GeneratedDocument>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
