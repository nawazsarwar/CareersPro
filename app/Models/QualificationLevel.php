<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $rank
 * @property int|null $ncrf_level
 */
class QualificationLevel extends MasterDataModel
{
    /** @var list<string> */
    protected $fillable = ['code', 'name', 'rank', 'ncrf_level'];
}
