<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    {{-- KONTEN UTAMA HALAMAN DASHBOARD --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">Sistem Monitoring</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }}</p>
        </div>
    </div>

    <h3 class="mb-3">Terkini (Hari Ini)</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsTerkini['pengajuan']->total ?? 0 }}</h1>
                    <p class="card-text">Pengajuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsTerkini['diproses']->total ?? 0 }}</h1>
                    <p class="card-text">Berkas Diproses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsTerkini['selesai']->total ?? 0 }}</h1>
                    <p class="card-text">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5 mb-3">Bulan Ini</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsBulanIni['pengajuan']->total ?? 0 }}</h1>
                    <p class="card-text">Pengajuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsBulanIni['diproses']->total ?? 0 }}</h1>
                    <p class="card-text">Berkas Diproses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background-color: #F5F5DC;">
                <div class="card-body">
                    <h1 class="display-3 font-weight-bold">{{ $statsBulanIni['selesai']->total ?? 0 }}</h1>
                    <p class="card-text">Selesai</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>