<?php

declare(strict_types=1);

namespace App\Models;

class MaritalStatus extends MasterDataModel
{
    protected $table = 'marital_statuses';

    /** @var list<string> */
    protected $fillable = ['code', 'name'];
}
