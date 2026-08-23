<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchPublication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'publisher_journal',
        'issn_isbn',
        'is_peer_reviewed',
        'is_ugc_care_listed',
        'impact_factor',
        'authorship_position',
        'number_of_coauthors',
        'link_doi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
