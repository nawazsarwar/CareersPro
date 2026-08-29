<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The five recruitment tracks (M03 §3.4 of the plan; DR-007).
 *
 * School teachers are a track of their own because they are governed by
 * neither the 2018 Regulations nor the non-teaching CRR -- AMU runs about ten
 * schools and their rules are a separate instrument (DOC-005, still
 * outstanding).
 */
enum Cadre: string
{
    case Teaching = 'teaching';
    case NonTeaching = 'non_teaching';
    case Library = 'library';
    case PhysicalEducation = 'physical_education';
    case SchoolTeacher = 'school_teacher';

    /**
     * Only non-teaching posts carry a Group. A Group on a teaching cadre is a
     * data error, not an empty field.
     */
    public function hasGroup(): bool
    {
        return $this === self::NonTeaching;
    }

    /**
     * Whether a screening score may enter the merit list.
     *
     * For teaching it never may (UGC 2018 cl. 4.1 Note, cl. 5.3): merit is the
     * interview alone. For non-teaching the written papers are additive with a
     * 20% interview weighting. A screening score leaking into a teaching merit
     * list is a statutory violation, not a bug, which is why this lives on the
     * cadre rather than in a controller.
     */
    public function screeningScoreIsAdditive(): bool
    {
        return $this === self::NonTeaching;
    }

    public function label(): string
    {
        return match ($this) {
            self::Teaching => __('establishment.cadre_teaching'),
            self::NonTeaching => __('establishment.cadre_non_teaching'),
            self::Library => __('establishment.cadre_library'),
            self::PhysicalEducation => __('establishment.cadre_physical_education'),
            self::SchoolTeacher => __('establishment.cadre_school_teacher'),
        };
    }
}
