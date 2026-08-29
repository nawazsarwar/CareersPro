<?php

declare(strict_types=1);

namespace App\Models;

class Religion extends MasterDataModel
{
    protected $table = 'religions';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
