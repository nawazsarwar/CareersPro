<?php

declare(strict_types=1);

namespace App\Domain\Examination;

use App\Models\Application;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * Gapless roll numbers from a per-post sequence.
 *
 * Never user-entered and never derived from max()+1. A duplicate roll number
 * on an attendance sheet is two candidates the invigilator cannot tell apart,
 * and a gap is a candidate somebody will spend the morning looking for.
 */
final class AllocateRollNumber
{
    public function __construct(private readonly ConnectionInterface $connection) {}

    public function handle(Application $application): string
    {
        if ($application->roll_no !== null) {
            // Idempotent: re-running an allocation must not renumber a
            // candidate who has already been told their roll number.
            return $application->roll_no;
        }

        if ($this->connection->transactionLevel() === 0) {
            throw new RuntimeException('A roll number must be allocated inside a transaction.');
        }

        $postId = (int) $application->post_id;

        $this->connection->table('roll_number_sequences')
            ->insertOrIgnore(['post_id' => $postId, 'next_value' => 1]);

        $row = $this->connection->table('roll_number_sequences')
            ->where('post_id', $postId)
            ->lockForUpdate()
            ->first();

        $next = (int) ($row->next_value ?? 1);

        $this->connection->table('roll_number_sequences')
            ->where('post_id', $postId)
            ->update(['next_value' => $next + 1]);

        $rollNumber = sprintf('%d%05d', $postId, $next);

        $application->forceFill(['roll_no' => $rollNumber])->save();

        return $rollNumber;
    }
}
