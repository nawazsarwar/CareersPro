<?php

declare(strict_types=1);

namespace App\Models;

class HorizontalCategory extends MasterDataModel
{
    protected $table = 'horizontal_categories';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
