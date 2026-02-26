<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UserController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $query = User::with('role');
        
        // Global search using trait
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'name', 'email', 'email_valid', 'phone', 'position',
                'role' => ['name']
            ]);
        }
        
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $roles = Role::where('is_superadmin', false)->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'email_valid' => 'nullable|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        // HAPUS join_date dari sini, biarkan boot method di User model yang handle
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'email_valid' => $request->email_valid,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'position' => $request->position,
            'is_active' => true,
            // join_date TIDAK PERLU diisi, otomatis oleh boot()
        ];

        $user = User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $roles = Role::where('is_superadmin', false)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'email_valid' => 'nullable|string|email|max:255',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'profile_photo' => 'nullable|image',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->email_valid = $request->email_valid;
        $user->role_id = $request->role_id;
        $user->phone = $request->phone;
        $user->position = $request->position;
        $user->is_active = $request->is_active;
        
        // Join date TIDAK DIUPDATE

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            }
            
            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo = $filename;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }
        
        if ($user->role->is_superadmin) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete superadmin account.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
