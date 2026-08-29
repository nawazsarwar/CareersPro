<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Access\ResolvePermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->with('roles')->orderBy('name')->paginate(50),
        ]);
    }

    public function store(AssignRoleRequest $request, User $user): RedirectResponse
    {
        $this->authorize('assignRole', $user);

        $user->roles()->syncWithoutDetaching([
            $request->integer('role_id') => [
                'organisational_unit_id' => $request->input('organisational_unit_id'),
            ],
        ]);

        // Granting access that waits fifteen minutes to take effect is as
        // wrong as revoking one that does.
        ResolvePermissions::invalidate($user);

        return back()->with('status', __('access.role_assigned'));
    }

    public function destroy(User $user, Role $role): RedirectResponse
    {
        $this->authorize('assignRole', $user);

        $user->roles()->detach($role);

        ResolvePermissions::invalidate($user);

        return back()->with('status', __('access.role_revoked'));
    }
}
