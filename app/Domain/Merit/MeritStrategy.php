<?php

declare(strict_types=1);

namespace App\Domain\Merit;

interface MeritStrategy
{
    /**
     * @param  list<array<string, mixed>>  $inputs  one row per candidate
     * @return list<array<string, mixed>> ranked, highest first
     */
    public function rank(array $inputs): array;
}
