<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Three states, never two.
 *
 * NULL is `pending` and is not the same as `rejected`. The legacy UI rendered
 * a merged "Pending / Not Eligible" label over both, on a decision a rejected
 * candidate can challenge -- so a candidate nobody had yet examined looked
 * identical to one who had been refused.
 */
enum GateDecision: string
{
    case Eligible = 'eligible';
    case Rejected = 'rejected';

    /**
     * Always a glyph and a word, never colour alone: WCAG 1.4.1, and a
     * scrutiny decision is exactly the wrong place to rely on hue.
     */
    public static function label(?self $decision): string
    {
        return match ($decision) {
            self::Eligible => '✓ '.__('application.decision_eligible'),
            self::Rejected => '✕ '.__('application.decision_rejected'),
            null => '◦ '.__('application.decision_pending'),
        };
    }
}
