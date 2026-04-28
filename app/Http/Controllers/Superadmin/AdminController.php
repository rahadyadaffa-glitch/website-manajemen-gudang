<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Minimarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->with('minimarket')->latest()->get();

        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        $minimarkets = Minimarket::active()->get();
        return view('superadmin.admins.create', compact('minimarkets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'minimarket_id' => 'required|exists:minimarkets,id',
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $adminRole->id,
            'minimarket_id' => $validated['minimarket_id'],
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        $minimarkets = Minimarket::active()->get();
        return view('superadmin.admins.edit', compact('admin', 'minimarkets'));
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $admin->id,
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'minimarket_id' => 'required|exists:minimarkets,id',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'minimarket_id' => $validated['minimarket_id'],
            'is_active' => $validated['is_active'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        $admin->delete();
        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
