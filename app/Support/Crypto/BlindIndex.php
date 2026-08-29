<?php

declare(strict_types=1);

namespace App\Support\Crypto;

use RuntimeException;

/**
 * Equality matching over an encrypted column (data-protection.md §2).
 *
 * Field-level encryption is not searchable: two encryptions of the same value
 * differ, which is the point. Where a lookup is genuinely required -- Aadhaar
 * duplicate detection, the per-destination OTP cap -- the blind index stores
 * HMAC-SHA256(normalised value, index key) alongside the ciphertext.
 *
 * The key is separate from APP_KEY so it can be rotated on its own, and an
 * HMAC rather than a bare hash so that possessing the index without the key
 * does not permit a dictionary attack over a small domain such as ten-digit
 * mobile numbers.
 */
final class BlindIndex
{
    public static function of(string $value): string
    {
        return hash_hmac('sha256', self::normalise($value), self::key());
    }

    /**
     * Normalising before hashing is what makes the index usable: `+91 98765
     * 43210` and `9876543210` are the same handset and must produce the same
     * index, or the cap they exist to enforce is trivially bypassed.
     */
    private static function normalise(string $value): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/', '', $value) ?? $value));
    }

    private static function key(): string
    {
        $key = config('crypto.blind_index_key');

        if (! is_string($key) || $key === '') {
            throw new RuntimeException(
                'No blind-index key is configured. Set BLIND_INDEX_KEY; it must not be APP_KEY.'
            );
        }

        return $key;
    }
}
