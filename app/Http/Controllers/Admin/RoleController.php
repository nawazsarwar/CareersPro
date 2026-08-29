<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Role::class);

        return view('admin.roles.index', [
            'roles' => Role::query()->with('permissions')->orderBy('name')->get(),
        ]);
    }

    public function show(Role $role): View
    {
        $this->authorize('view', $role);

        return view('admin.roles.show', ['role' => $role->load('permissions')]);
    }
}
