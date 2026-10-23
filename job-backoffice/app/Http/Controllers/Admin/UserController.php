<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ToggleUserActiveRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);
        
        $users = User::query()->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->update($request->validated());

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function toggleActive(ToggleUserActiveRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('toggleActive', $user);

        $user->update($request->validated());

        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
