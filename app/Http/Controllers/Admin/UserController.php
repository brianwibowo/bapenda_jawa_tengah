<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::with(['roles', 'cabang']);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%")
                  ->orWhereHas('cabang', function ($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%")
                         ->orWhere('wilayah', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(7)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        $dbUnitKerjas = User::select('unit_kerja')->whereNotNull('unit_kerja')->where('unit_kerja', '!=', '')->distinct()->pluck('unit_kerja')->all();
        $unitKerjas = array_unique(array_merge(['Polda', 'Jasa Raharja', 'Samsat', 'Bapenda'], $dbUnitKerjas));
        $branches = Cabang::orderBy('wilayah')->get();
        return view('admin.users.create', compact('roles', 'unitKerjas', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string'],
            'new_unit_kerja' => ['nullable', 'string', 'required_if:unit_kerja,Lainnya'],
            'cabang_id' => ['nullable', 'exists:cabangs,id'],
            'roles' => ['required', 'array']
        ]);

        $finalUnitKerja = ($request->unit_kerja === 'Lainnya' && $request->filled('new_unit_kerja')) 
                        ? $request->new_unit_kerja 
                        : $request->unit_kerja;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'unit_kerja' => $finalUnitKerja,
            'cabang_id' => $request->cabang_id,
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $dbUnitKerjas = User::select('unit_kerja')->whereNotNull('unit_kerja')->where('unit_kerja', '!=', '')->distinct()->pluck('unit_kerja')->all();
        $unitKerjas = array_unique(array_merge(['Polda', 'Jasa Raharja', 'Samsat', 'Bapenda'], $dbUnitKerjas));
        $branches = Cabang::orderBy('wilayah')->get();
        $userRoles = $user->roles->pluck('name','name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'unitKerjas', 'branches', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string'],
            'new_unit_kerja' => ['nullable', 'string', 'required_if:unit_kerja,Lainnya'],
            'cabang_id' => ['nullable', 'exists:cabangs,id'],
            'roles' => ['required', 'array']
        ]);

        $input = $request->except('password', 'password_confirmation', 'new_unit_kerja');
        
        $input['unit_kerja'] = ($request->unit_kerja === 'Lainnya' && $request->filled('new_unit_kerja')) 
                             ? $request->new_unit_kerja 
                             : $request->unit_kerja;
        $input['cabang_id'] = $request->cabang_id;
        
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->route('admin.users.index')->with('error', 'Akun Superadmin tidak boleh dihapus.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
