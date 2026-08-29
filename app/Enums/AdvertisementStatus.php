<?php

declare(strict_types=1);

namespace App\Enums;

enum AdvertisementStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Published = 'published';
    case Paused = 'paused';
    case Closed = 'closed';
    case Withdrawn = 'withdrawn';

    /**
     * Whether the record may still be edited directly.
     *
     * Once published it may not: a change to a published advertisement is a
     * corrigendum, published and dated, because candidates have already read
     * and relied on what it said.
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::PendingApproval], true);
    }

    public function isPubliclyVisible(): bool
    {
        return in_array($this, [self::Published, self::Paused, self::Closed], true);
    }

    public function acceptsApplications(): bool
    {
        return $this === self::Published;
    }
}
