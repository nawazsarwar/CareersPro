<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostalCode extends MasterDataModel
{
    /** @var list<string> */
    protected $fillable = ['district_id', 'code', 'office'];

    /**
     * @return BelongsTo<District, $this>
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
