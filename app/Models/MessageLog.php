<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    use Auditable;

    protected $table = 'message_logs';

    /** @var list<string> */
    protected $fillable = ['message_campaign_id', 'user_id', 'application_id', 'channel', 'destination_hash', 'status', 'failure_reason', 'provider_reference', 'sent_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }

    /**
     * @param  Builder<MessageLog>  $query
     * @return Builder<MessageLog>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
