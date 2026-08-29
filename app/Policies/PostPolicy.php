<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'post.view');
    }

    public function view(User $user, Post $post): bool
    {
        return $this->permits($user, 'post.view', $post);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'post.create');
    }

    public function update(User $user, Post $post): bool
    {
        if (! $post->advertisement->status->isEditable()) {
            return false;
        }

        return $this->permits($user, 'post.update', $post);
    }
}
