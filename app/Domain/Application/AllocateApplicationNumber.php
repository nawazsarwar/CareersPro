<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Models\Post;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * A gapless number per post, allocated under a row lock.
 *
 * max()+1 races: two candidates submitting in the same second get the same
 * number, and the unique index then rejects one of them after their payment
 * has been taken. The counter row is taken FOR UPDATE inside the submitting
 * transaction, so a rolled-back submission returns its number.
 */
final class AllocateApplicationNumber
{
    public function __construct(private readonly ConnectionInterface $connection) {}

    public function next(Post $post): string
    {
        if ($this->connection->transactionLevel() === 0) {
            throw new RuntimeException('An application number must be allocated inside a transaction.');
        }

        $this->connection->table('application_number_sequences')
            ->insertOrIgnore(['post_id' => $post->getKey(), 'next_value' => 1]);

        $row = $this->connection->table('application_number_sequences')
            ->where('post_id', $post->getKey())
            ->lockForUpdate()
            ->first();

        $next = (int) ($row->next_value ?? 1);

        $this->connection->table('application_number_sequences')
            ->where('post_id', $post->getKey())
            ->update(['next_value' => $next + 1]);

        // Post id and a zero-padded serial: readable aloud over a telephone,
        // which is how a candidate most often quotes it.
        return sprintf('%d%06d', $post->getKey(), $next);
    }
}
