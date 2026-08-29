<?php

declare(strict_types=1);

namespace App\Domain\Public;

use App\Models\Post;

/**
 * Where a hard copy goes, and by when.
 *
 * The venue differs by post type -- teaching dossiers to the Selection
 * Committee Section, non-teaching to the Non-Teaching section, local
 * appointments to the Dean's office, school teaching to the Directorate. A
 * candidate who posts to the wrong one has effectively not applied, so it is
 * rendered from the post type rather than typed into a description.
 */
final class SubmissionInstructions
{
    /**
     * @return array{venue: string|null, closing_date: string|null, hardcopy_required: bool}
     */
    public function for(Post $post): array
    {
        return [
            'venue' => $post->postType->submission_venue,
            'closing_date' => $post->closing_date?->toDateString(),

            // DR-011: the hard copy is retained, so it is still required. The
            // rectification window that softens it is M18's, not a reason to
            // stop asking for it.
            'hardcopy_required' => true,
        ];
    }
}
