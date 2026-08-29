<?php

declare(strict_types=1);

namespace App\Support\Canonical;

use JsonException;

/**
 * Deterministic JSON serialisation.
 *
 * Two processes, two PHP versions and two machines must produce byte-identical
 * output for the same payload, because the output is hashed and the hash is the
 * identity of an application snapshot and a link in the audit chain
 * (docs/v3/01-design/domain/snapshot-and-audit.md §2.3).
 *
 * PHP's json_encode is not sufficient on its own: object key order follows
 * insertion order, floats round-trip differently across versions, and the
 * default escaping of slashes and Unicode is a presentation choice that must
 * not be allowed to vary. This class fixes all four.
 */
final class CanonicalJson
{
    /**
     * The hash algorithm. Recorded here rather than at call sites so that a
     * future change is one edit and is visible in the diff.
     */
    public const HASH_ALGO = 'sha256';

    /**
     * @param  array<array-key, mixed>  $payload
     *
     * @throws JsonException
     */
    public static function encode(array $payload): string
    {
        return json_encode(
            self::normalise($payload),
            JSON_THROW_ON_ERROR
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_PRESERVE_ZERO_FRACTION,
        );
    }

    /**
     * @param  array<array-key, mixed>  $payload
     *
     * @throws JsonException
     */
    public static function hash(array $payload): string
    {
        return hash(self::HASH_ALGO, self::encode($payload));
    }

    /**
     * Recursively sort associative keys and normalise scalars.
     *
     * A list keeps its order -- order is meaning in a list. An associative
     * array is sorted by key, because insertion order is not meaning and is
     * exactly what varies between two processes building the same payload.
     */
    private static function normalise(mixed $value): mixed
    {
        if (is_array($value)) {
            $isList = array_is_list($value);

            $value = array_map(static fn (mixed $item): mixed => self::normalise($item), $value);

            if (! $isList) {
                ksort($value, SORT_STRING);
            }

            return $value;
        }

        if (is_float($value)) {
            return self::normaliseFloat($value);
        }

        return $value;
    }

    /**
     * Render a float identically on every platform.
     *
     * serialize_precision defaults to 17 in some builds and -1 in others, so
     * 0.1 + 0.2 encodes as 0.30000000000000004 under one and 0.3 under the
     * other. Seventeen significant digits is the round-trip precision of an
     * IEEE-754 double, so this loses nothing and varies nowhere.
     *
     * A float that is integral is returned as a float, not silently narrowed
     * to an int: 2.0 and 2 are different claims about a value.
     */
    private static function normaliseFloat(float $value): float
    {
        if (! is_finite($value)) {
            throw new \InvalidArgumentException(
                'Canonical JSON cannot encode NAN or INF; the payload is not representable.',
            );
        }

        return (float) sprintf('%.17G', $value);
    }
}
