<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Regency;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Legacy index — redirect ke stakeholder.
     */
    public function index(Request $request)
    {
        return $this->indexStakeholder($request);
    }

    /**
     * Daftar Wajib Pajak.
     */
    public function indexWp(Request $request)
    {
        $search = $request->query('search');

        $query = User::with(['roles', 'domisiliRegency'])
            ->role('wajib_pajak');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhereHas('domisiliRegency', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'type' => 'wp',
        ]);
    }

    /**
     * Daftar Pemangku Kepentingan (non-WP).
     */
    public function indexStakeholder(Request $request)
    {
        $search = $request->query('search');

        $query = User::with(['roles', 'cabang'])
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'wajib_pajak'));

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

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'type' => 'stakeholder',
        ]);
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'wajib_pajak')->get();
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

        return redirect()->route('admin.users.stakeholder.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Edit form untuk Stakeholder (non-WP).
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'wajib_pajak')->get();
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

        return redirect()->route('admin.users.stakeholder.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Edit form khusus untuk Wajib Pajak.
     */
    public function editWp(User $user)
    {
        $regencies = Regency::query()
            ->where('province_id', '33')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.users.edit-wp', compact('user', 'regencies'));
    }

    /**
     * Update khusus Wajib Pajak.
     */
    public function updateWp(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'no_hp' => ['required', 'string', 'max:20'],
            'domisili_regency_id' => ['required', 'exists:regencies,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'domisili_regency_id' => $request->domisili_regency_id,
        ];

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);

        return redirect()->route('admin.users.wp.index')->with('success', 'Data Wajib Pajak berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->back()->with('error', 'Akun Superadmin tidak boleh dihapus.');
        }

        $isWp = $user->hasRole('wajib_pajak');
        $user->delete();

        $redirectRoute = $isWp ? 'admin.users.wp.index' : 'admin.users.stakeholder.index';
        return redirect()->route($redirectRoute)->with('success', 'User berhasil dihapus.');
    }
}
