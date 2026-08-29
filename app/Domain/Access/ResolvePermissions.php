<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * The permission slugs a user holds (M25-R10, M25-R11).
 *
 * Cached for fifteen minutes per user. What this replaces ran two queries and
 * then defined 162 Gate closures on **every** request, uncached -- the cost of
 * which is paid on the dashboard of a 78,232-row system by every member of
 * staff, all day.
 *
 * The cache key carries a per-user version counter rather than being flushed by
 * tag: tags are unavailable on the file and database stores, and a cache that
 * silently fails to invalidate on one driver is worse than no cache at all.
 */
final class ResolvePermissions
{
    private const TTL_MINUTES = 15;

    /**
     * @return list<string>
     */
    public function for(User $user): array
    {
        return Cache::remember(
            self::keyFor($user),
            now()->addMinutes(self::TTL_MINUTES),
            static fn (): array => $user->roles()
                ->with('permissions:id,slug')
                ->get()
                ->flatMap(static fn ($role) => $role->permissions->pluck('slug'))
                ->unique()
                ->values()
                ->all(),
        );
    }

    public function has(User $user, string $permission): bool
    {
        return in_array($permission, $this->for($user), true);
    }

    /**
     * Called whenever a role or permission changes. Bumping the version
     * invalidates this user's entry without touching anybody else's and
     * without needing tag support.
     */
    public static function invalidate(User $user): void
    {
        Cache::forget(self::keyFor($user));
        Cache::increment(self::versionKey($user));
    }

    private static function keyFor(User $user): string
    {
        return sprintf('permissions:%d:v%d', $user->getKey(), self::version($user));
    }

    private static function versionKey(User $user): string
    {
        return sprintf('permissions:%d:version', $user->getKey());
    }

    private static function version(User $user): int
    {
        return (int) Cache::get(self::versionKey($user), 0);
    }
}
