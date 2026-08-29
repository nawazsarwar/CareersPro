<?php

declare(strict_types=1);

namespace App\Models;

class AdvertisementType extends MasterDataModel
{
    protected $table = 'advertisement_types';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
