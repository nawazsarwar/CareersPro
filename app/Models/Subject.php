<?php

declare(strict_types=1);

namespace App\Models;

class Subject extends MasterDataModel
{
    protected $table = 'subjects';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
