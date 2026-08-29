<?php

declare(strict_types=1);

namespace App\Domain\Examination;

use App\Models\Post;
use Carbon\CarbonImmutable;
use RuntimeException;

/**
 * The admit-card and interview-letter windows.
 *
 * These four columns exist in production and were dropped by the previous
 * redesign, so download-window enforcement had no backing data at all. Without
 * them an admit card is downloadable the moment it exists, which means a
 * candidate can hold one for an examination whose venue has since changed.
 */
final class AssertDownloadWindow
{
    public const ADMIT_CARD = 'admit_card';

    public const INTERVIEW_LETTER = 'interview_letter';

    public function check(Post $post, string $type, ?CarbonImmutable $at = null): void
    {
        $at ??= CarbonImmutable::now();

        [$opens, $closes] = match ($type) {
            self::ADMIT_CARD => [$post->admit_card_opening_date, $post->admit_card_closing_date],
            self::INTERVIEW_LETTER => [$post->interview_letter_opening_date, $post->interview_letter_closing_date],
            default => throw new RuntimeException(sprintf('Unknown document type [%s].', $type)),
        };

        if ($opens === null || $closes === null) {
            throw new RuntimeException('No download window is set for this document.');
        }

        if ($at->lt(CarbonImmutable::parse($opens))) {
            throw new RuntimeException(sprintf(
                'That document is available from %s.',
                CarbonImmutable::parse($opens)->format('d-m-Y H:i'),
            ));
        }

        if ($at->gt(CarbonImmutable::parse($closes))) {
            throw new RuntimeException(sprintf(
                'That download closed on %s.',
                CarbonImmutable::parse($closes)->format('d-m-Y H:i'),
            ));
        }
    }
}
