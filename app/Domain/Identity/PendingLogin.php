<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Enums\AuthFactor;
use App\Models\User;
use Illuminate\Contracts\Session\Session;

/**
 * The half-authenticated state between the first factor and the challenge.
 *
 * The user is identified but not signed in, so `auth` would reject them and
 * `guest` would admit anyone. Keeping it in the session under one key, behind
 * one class, is what stops each controller inventing its own convention.
 */
final class PendingLogin
{
    private const KEY = 'auth.pending';

    public function __construct(private readonly Session $session) {}

    public function start(User $user, AuthFactor $used, bool $remember = false): void
    {
        $this->session->put(self::KEY, [
            'user_id' => $user->getKey(),
            'used' => $used->value,
            'remember' => $remember,
            'started_at' => now()->timestamp,
        ]);
    }

    public function user(): ?User
    {
        $id = $this->data()['user_id'] ?? null;

        return is_numeric($id) ? User::query()->find((int) $id) : null;
    }

    public function factorUsed(): ?AuthFactor
    {
        $used = $this->data()['used'] ?? null;

        return is_string($used) ? AuthFactor::tryFrom($used) : null;
    }

    public function remember(): bool
    {
        return (bool) ($this->data()['remember'] ?? false);
    }

    public function exists(): bool
    {
        return $this->user() !== null && ! $this->hasExpired();
    }

    /**
     * A pending login is not a session. It lapses quickly, because a browser
     * left open at the challenge screen is otherwise an indefinite invitation.
     */
    public function hasExpired(): bool
    {
        $startedAt = $this->data()['started_at'] ?? null;

        if (! is_numeric($startedAt)) {
            return true;
        }

        return now()->timestamp - (int) $startedAt > 600;
    }

    public function forget(): void
    {
        $this->session->forget(self::KEY);
    }

    /**
     * @return array<string, mixed>
     */
    private function data(): array
    {
        $data = $this->session->get(self::KEY);

        return is_array($data) ? $data : [];
    }
}
