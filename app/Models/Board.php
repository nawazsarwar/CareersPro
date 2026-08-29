<?php

declare(strict_types=1);

namespace App\Models;

class Board extends MasterDataModel
{
    protected $table = 'boards';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
