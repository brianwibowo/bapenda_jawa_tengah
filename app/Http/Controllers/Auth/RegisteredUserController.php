<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Regency;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $regencies = Regency::query()
            ->where('province_id', '33') // Provinsi Jawa Tengah
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('auth.register', compact('regencies'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'domisili_regency_id' => ['required', 'exists:regencies,id'],
            'terms_law' => ['accepted'],
        ]);

        $regency = Regency::query()->findOrFail($request->string('domisili_regency_id')->toString());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'unit' => 'Wajib Pajak',
            'domisili_regency_id' => $regency->id,
            'cabang_id' => $this->resolveCabangIdFromRegency($regency),
            'law_compliance_statement_accepted_at' => now(),
        ]);

        // Ensure role wajib_pajak always exists for self-register flow.
        $roleWp = Role::firstOrCreate(['name' => 'wajib_pajak', 'guard_name' => 'web']);
        $user->assignRole($roleWp);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan login dengan akun baru Anda.');
    }

    private function resolveCabangIdFromRegency(Regency $regency): ?int
    {
        $normalizedDomisili = $this->normalizeRegionName($regency->name);

        $matchingCabang = Cabang::query()
            ->get(['id', 'wilayah'])
            ->first(function (Cabang $cabang) use ($normalizedDomisili) {
                $normalizedWilayah = $this->normalizeRegionName($cabang->wilayah);

                return $normalizedWilayah === $normalizedDomisili
                    || str_contains($normalizedDomisili, $normalizedWilayah)
                    || str_contains($normalizedWilayah, $normalizedDomisili);
            });

        return $matchingCabang?->id;
    }

    private function normalizeRegionName(string $name): string
    {
        return trim(strtolower(str_replace(['kabupaten', 'kota'], '', $name)));
    }
}
