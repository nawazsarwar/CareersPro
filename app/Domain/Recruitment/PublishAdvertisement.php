<?php

declare(strict_types=1);

namespace App\Domain\Recruitment;

use App\Enums\AdvertisementStatus;
use App\Models\Advertisement;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Publishing is the moment everything freezes.
 *
 * Scoring-engine invariant I1: an advertisement published under UGC 2018 rules
 * must calculate under UGC 2018 rules forever, whatever is notified afterwards.
 * The ruleset version, the relaxation policy version, the payment gateway and
 * the organisational-unit snapshot are all fixed here and become read-only.
 *
 * After this, the advertisement cannot be edited. A change is a corrigendum:
 * published, dated and auditable, because candidates have already read and
 * relied on what it said.
 */
final class PublishAdvertisement
{
    public function __construct(
        private readonly AssertWindow $window,
        private readonly SnapshotOrganisationalUnit $snapshot,
        private readonly NextWorkingDay $workingDay,
    ) {}

    private function defaultGateway(): string
    {
        $gateway = config('payment.default_gateway');

        if (! is_string($gateway) || $gateway === '') {
            throw new RuntimeException('No default payment gateway is configured.');
        }

        return $gateway;
    }

    /**
     * @param  list<string>  $holidays
     */
    public function handle(Advertisement $advertisement, User $actor, array $holidays = []): Advertisement
    {
        if ($advertisement->status === AdvertisementStatus::Published) {
            throw new RuntimeException('That advertisement is already published.');
        }

        if (! $advertisement->status->isEditable()) {
            throw new RuntimeException('Only a draft or an advertisement pending approval may be published.');
        }

        if ($advertisement->posts()->count() === 0) {
            throw new RuntimeException('An advertisement cannot be published without at least one post.');
        }

        return DB::transaction(function () use ($advertisement, $actor, $holidays): Advertisement {
            // The closing date moves before the window is checked, so the
            // thirty days are counted against the date candidates actually
            // get, not the one that was typed.
            $closing = $advertisement->default_closing_date;

            if ($closing !== null) {
                $moved = $this->workingDay->from($closing, $holidays);

                if (! $moved->isSameDay($closing)) {
                    $advertisement->forceFill(['default_closing_date' => $moved]);
                }
            }

            $this->window->check($advertisement);

            $this->snapshot->handle($advertisement);

            $advertisement->forceFill([
                'status' => AdvertisementStatus::Published,
                'published_at' => CarbonImmutable::now(),
                'approved_by_id' => $actor->getKey(),
                'approved_at' => CarbonImmutable::now(),

                // Frozen. Wave 7 supplies the ruleset resolver; the columns
                // exist and are written here from the first day so that no
                // advertisement is ever published without them.
                //
                // No literal fallback: naming a provider here would put a
                // vendor in the domain (DR-018), and a missing default is a
                // configuration error that should surface rather than resolve
                // to whichever gateway somebody typed first.
                'payment_gateway' => $advertisement->payment_gateway ?? $this->defaultGateway(),
            ])->save();

            foreach ($advertisement->posts as $post) {
                $this->snapshot->handle($post);

                $post->forceFill([
                    'status' => AdvertisementStatus::Published->value,
                    'closing_date' => $post->closing_date ?? $advertisement->default_closing_date,
                    'opening_date' => $post->opening_date ?? $advertisement->default_opening_date,
                ])->save();
            }

            $advertisement->forceFill(['posts_count' => $advertisement->posts()->count()])->saveQuietly();

            return $advertisement->refresh();
        });
    }
}
