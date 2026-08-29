<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The application lifecycle.
 *
 * A backed enum rather than the nullable integer the previous build used --
 * into which the wizard wrote the *string* 'Submitted', so the column held
 * neither a valid integer nor a usable state.
 *
 * This is one of four orthogonal dimensions, not the whole story: submission
 * state, payment state and the three independent eligibility gates each move
 * on their own. Collapsing them into one chain is what made the legacy status
 * column unable to express a paid application awaiting scrutiny.
 */
enum LifecycleState: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderScrutiny = 'under_scrutiny';
    case Deficient = 'deficient';
    case ScrutinyCleared = 'scrutiny_cleared';
    case Rejected = 'rejected';
    case Shortlisted = 'shortlisted';
    case TestScheduled = 'test_scheduled';
    case Interviewed = 'interviewed';
    case Selected = 'selected';
    case Waitlisted = 'waitlisted';
    case NotSelected = 'not_selected';
    case Withdrawn = 'withdrawn';
    case Archived = 'archived';

    /**
     * Whether the candidate may still change the dossier behind this
     * application.
     *
     * Only in draft. After submission the dossier is locked -- but the legacy
     * hard lock after *payment*, with no way back at all, is what this
     * replaces: a deficiency reopens a specific field for a bounded window
     * (M18), rather than the candidate being locked out irreversibly.
     */
    public function isEditable(): bool
    {
        return $this === self::Draft;
    }

    public function isSubmitted(): bool
    {
        return $this !== self::Draft && $this !== self::Withdrawn;
    }

    public function isTerminal(): bool
    {
        return in_array($this, [
            self::Rejected, self::Selected, self::NotSelected,
            self::Withdrawn, self::Archived,
        ], true);
    }
}
