<?php

declare(strict_types=1);

namespace App\Domain\Migration;

use JsonException;

/**
 * The legacy payload lives in two `longtext` JSON blobs.
 *
 * `applicationforms.basic_details` and `.additional_details` hold the real
 * data; the twenty-six adjacent columns are denormalised regenerations of the
 * same values and disagree with them in places. The blob is authoritative
 * because it is what the form wrote; the columns are what a later report
 * derived.
 *
 * Malformed JSON is quarantined rather than skipped. Over 78,232 rows a silent
 * skip is a candidate whose application vanishes without anybody noticing.
 */
final class DecomposeBlob
{
    /**
     * @return array{ok: true, data: array<string, mixed>}|array{ok: false, reason: string}
     */
    public function handle(?string $json): array
    {
        if ($json === null || trim($json) === '') {
            return ['ok' => false, 'reason' => 'empty_blob'];
        }

        try {
            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return ['ok' => false, 'reason' => 'malformed_json'];
        }

        if (! is_array($decoded)) {
            return ['ok' => false, 'reason' => 'not_an_object'];
        }

        return ['ok' => true, 'data' => $this->flatten($decoded)];
    }

    /**
     * Legacy blobs nest inconsistently -- some rows carry a `data` wrapper and
     * some do not, depending on which version of the form wrote them.
     *
     * @param  array<array-key, mixed>  $decoded
     * @return array<string, mixed>
     */
    private function flatten(array $decoded): array
    {
        if (isset($decoded['data']) && is_array($decoded['data'])) {
            $decoded = $decoded['data'];
        }

        $flat = [];

        foreach ($decoded as $key => $value) {
            $flat[(string) $key] = $value;
        }

        return $flat;
    }
}
