<?php

declare(strict_types=1);

namespace App\Models;

class PayLevel extends MasterDataModel
{
    protected $table = 'pay_levels';

    /** @var list<string> */
    protected $fillable = ['code', 'name', 'entry_pay'];
}
