<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SelectionMethod;

/**
 * Seven live rows (DR-007). The apparent duplicates are the General and Local
 * appointment regimes of DR-010: different committees, different
 * administration, identical eligibility.
 *
 * @property SelectionMethod $default_selection_method
 */
class PostType extends MasterDataModel
{
    /** @var list<string> */
    protected $fillable = [
        'code', 'name', 'default_selection_method', 'submission_venue',
        'has_scrutiny_gate', 'has_written_test_gate', 'has_interview_gate',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_selection_method' => SelectionMethod::class,
            'has_scrutiny_gate' => 'boolean',
            'has_written_test_gate' => 'boolean',
            'has_interview_gate' => 'boolean',
        ];
    }

    /**
     * The gates that actually apply, driven by the selection method rather
     * than all three regardless -- which is what the legacy modal did, letting
     * an officer record a written-test decision for a post with no written
     * test.
     *
     * @return list<string>
     */
    public function activeGates(): array
    {
        return $this->default_selection_method->activeGates();
    }
}
