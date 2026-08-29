<?php

declare(strict_types=1);

namespace App\Models;

class Degree extends MasterDataModel
{
    protected $table = 'degrees';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
