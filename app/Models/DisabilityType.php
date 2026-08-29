<?php

declare(strict_types=1);

namespace App\Models;

class DisabilityType extends MasterDataModel
{
    protected $table = 'disability_types';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
