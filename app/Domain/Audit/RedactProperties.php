<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Support\Canonical\CanonicalJson;

/**
 * Strips secrets from what the audit record keeps (M26 §3, M26-R06).
 *
 * The record must show that a value changed without showing the value. What it
 * stores instead is `{"changed": true, "hash": "…"}`, which still answers "was
 * this altered, and when" and still lets two entries be compared, without
 * turning the audit table into a second copy of the Aadhaar column.
 *
 * The previous implementation stored whole rows unredacted, Aadhaar included.
 */
final class RedactProperties
{
    /**
     * Matched case-insensitively against the key, as a substring.
     *
     * A substring rather than an exact match, because the same secret arrives
     * under several names -- `password`, `password_confirmation`, `new_password`
     * -- and a list of exact names is a list that is always one name short.
     *
     * @var list<string>
     */
    private const SENSITIVE = [
        'aadhaar',
        'password',
        'remember_token',
        'otp_code',
        'otp_destination_hash',
        'two_factor_secret',
        'secret',
        'recovery_code',
        'token',
        'api_key',
    ];

    /**
     * Values that are credentials wherever they appear, including inside a URL.
     *
     * DR-024's SMS gateway authenticates by query parameter, so a logged URL is
     * a logged credential. Query strings are stripped from anything URL-shaped.
     *
     * @var list<string>
     */
    private const CREDENTIAL_QUERY_KEYS = ['user', 'password', 'pass', 'pwd', 'key', 'token'];

    /**
     * @param  array<array-key, mixed>  $properties
     * @return array<array-key, mixed>
     */
    public function handle(array $properties): array
    {
        $redacted = [];

        foreach ($properties as $key => $value) {
            if (is_array($value)) {
                $redacted[$key] = $this->handle($value);

                continue;
            }

            if (is_string($key) && $this->isSensitive($key)) {
                $redacted[$key] = $this->fingerprint($value);

                continue;
            }

            $redacted[$key] = is_string($value) ? $this->stripCredentialsFromUrl($value) : $value;
        }

        return $redacted;
    }

    private function isSensitive(string $key): bool
    {
        $needle = strtolower($key);

        foreach (self::SENSITIVE as $sensitive) {
            if (str_contains($needle, $sensitive)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{changed: bool, hash: string|null}
     */
    private function fingerprint(mixed $value): array
    {
        if ($value === null) {
            return ['changed' => true, 'hash' => null];
        }

        return [
            'changed' => true,
            'hash' => hash(CanonicalJson::HASH_ALGO, (string) json_encode($value)),
        ];
    }

    /**
     * A URL carrying credentials in its query string keeps its path and loses
     * the query. The endpoint is useful in an investigation; the credential is
     * the thing that must not be stored.
     */
    private function stripCredentialsFromUrl(string $value): string
    {
        if (! preg_match('#^https?://#i', $value)) {
            return $value;
        }

        $query = parse_url($value, PHP_URL_QUERY);

        if (! is_string($query) || $query === '') {
            return $value;
        }

        parse_str($query, $parameters);

        foreach (array_keys($parameters) as $parameter) {
            if (in_array(strtolower((string) $parameter), self::CREDENTIAL_QUERY_KEYS, true)) {
                return (string) strtok($value, '?').'?[redacted]';
            }
        }

        return $value;
    }
}
