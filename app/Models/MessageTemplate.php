<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use Auditable;

    protected $table = 'message_templates';

    /** @var list<string> */
    protected $fillable = ['code', 'name', 'channel', 'subject', 'body', 'placeholders'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['placeholders' => 'array'];
    }

    /**
     * @param  Builder<MessageTemplate>  $query
     * @return Builder<MessageTemplate>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
