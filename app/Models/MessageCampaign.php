<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MessageCampaign extends Model
{
    use Auditable;

    protected $table = 'message_campaigns';

    /** @var list<string> */
    protected $fillable = ['message_template_id', 'post_id', 'name', 'segment', 'status', 'recipients_total', 'recipients_sent', 'recipients_failed', 'created_by_id', 'started_at', 'completed_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['segment' => 'array', 'started_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    /**
     * @param  Builder<MessageCampaign>  $query
     * @return Builder<MessageCampaign>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
