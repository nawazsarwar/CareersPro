<?php

declare(strict_types=1);

namespace App\Models;

class Country extends MasterDataModel
{
    protected $table = 'countries';

    /** @var list<string> */
    protected $fillable = ['code', 'name', 'iso2', 'iso3'];
}
