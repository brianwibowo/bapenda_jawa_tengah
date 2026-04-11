<x-app-layout>
    <x-slot name="title">Edit Profil</x-slot>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Profil Saya</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white pb-0 border-bottom-0">
                    <h4 class="card-title fw-bold">Informasi Profil</h4>
                    <p class="text-muted small">Perbarui data diri dan alamat email akun Anda.</p>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white pb-0 border-bottom-0">
                    <h4 class="card-title fw-bold">Ganti Password</h4>
                    <p class="text-muted small">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan.</p>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
