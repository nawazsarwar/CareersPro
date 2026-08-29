<?php

declare(strict_types=1);

namespace App\Domain\Public;

use App\Enums\AdvertisementStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

/**
 * What the public may see, and when.
 *
 * A draft advertisement is not merely unlisted: a candidate who guesses its
 * slug must not reach it either, because an unpublished vacancy that leaks is
 * an advantage handed to whoever found it.
 */
final class VacancyVisibility
{
    /**
     * @return Builder<Post>
     */
    public function query(): Builder
    {
        return Post::query()
            ->where('withdrawn', false)
            ->whereHas('advertisement', function (Builder $query): void {
                $query->whereIn('status', [
                    AdvertisementStatus::Published->value,
                    AdvertisementStatus::Paused->value,
                    AdvertisementStatus::Closed->value,
                ])->whereNotNull('published_at');
            });
    }

    /**
     * Open means the advertisement is published AND the closing date has not
     * passed. A closed advertisement stays readable -- candidates need to see
     * what they applied to -- but stops accepting applications.
     *
     * @return Builder<Post>
     */
    public function openQuery(): Builder
    {
        return $this->query()
            ->whereHas('advertisement', fn (Builder $q) => $q->where('status', AdvertisementStatus::Published->value))
            ->whereDate('closing_date', '>=', now()->toDateString());
    }
}
