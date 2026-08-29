<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Models\FeeRule;
use App\Models\Post;
use App\Models\User;

/**
 * What this candidate owes for this post.
 *
 * Fee facts, fixed by the advertisements: ₹500 per application form, one form
 * per post, non-refundable, and a candidate with a benchmark disability is
 * exempt on a valid certificate.
 *
 * The exemption is checked here rather than at the payment screen because a
 * PwD candidate must never have an order created at all -- an order for zero
 * that then needs refunding is the failure mode this avoids.
 */
final class ComputeFee
{
    public function for(User $user, Post $post): Money
    {
        $profile = $user->profile;

        // The disability exemption, on a certificate rather than a claim: the
        // relaxation depends on the document, and scrutiny verifies it later.
        if ($profile?->is_pwd === true && $profile->disability_certificate_authority !== null) {
            return new Money(0);
        }

        $rule = $this->resolveRule($post, $profile?->category?->code);

        if ($rule?->is_exempt === true) {
            return new Money(0);
        }

        if ($rule !== null) {
            return new Money((int) $rule->amount_paise);
        }

        // Falling back to the post and then the advertisement, both of which
        // were fixed when the advertisement was published.
        $fee = $post->fee ?? $post->advertisement->default_fee;

        return new Money((int) ($fee ?? 0) * 100);
    }

    private function resolveRule(Post $post, ?string $categoryCode): ?FeeRule
    {
        // Most specific first: a rule for this post and this category beats a
        // rule for the advertisement, which beats the default.
        return FeeRule::query()
            ->where(function ($query) use ($post): void {
                $query->where('post_id', $post->getKey())
                    ->orWhere('advertisement_id', $post->advertisement_id);
            })
            ->where(function ($query) use ($categoryCode): void {
                $query->whereNull('category');

                if ($categoryCode !== null) {
                    $query->orWhere('category', $categoryCode);
                }
            })
            ->orderByRaw('CASE WHEN post_id IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw('CASE WHEN category IS NULL THEN 1 ELSE 0 END')
            ->first();
    }
}
