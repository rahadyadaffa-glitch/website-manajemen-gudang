<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        $query = User::where('minimarket_id', $minimarket->id)
            ->whereHas('role', function($q) {
                $q->where('name', 'user');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.users.partials._user_list', compact('users'))->render();
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        $userRole = Role::where('name', 'user')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $userRole->id,
            'minimarket_id' => $minimarket->id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User gudang berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeUser($user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeUser($user);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

    private function authorizeUser(User $user)
    {
        if ($user->minimarket_id !== auth()->user()->minimarket_id || !$user->isUser()) {
            abort(403);
        }
    }
}
