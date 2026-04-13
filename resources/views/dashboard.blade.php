<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>


    {{-- Header Dashboard & Identity --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold">Sistem Monitoring Bapenda</h2>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }} WIB
            </p>
        </div>
    </div>

    {{-- Statistik Terkini --}}
    <div class="d-flex align-items-center mb-3 mt-2">
        <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
            style="width: 32px; height: 32px;">
            <i class="fas fa-clock text-white small"></i>
        </div>
        <h4 class="mb-0 fw-bold">Terkini (Hari Ini)</h4>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 99%, #FECFEF 100%); border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 fw-semibold text-white-50 text-uppercase tracking-wider">Pengajuan Baru
                        </p>
                        <h1 class="display-4 font-weight-bold mb-0">{{ $statsTerkini['pengajuan']->total ?? 0 }}</h1>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 fw-semibold text-white-50 text-uppercase tracking-wider">Berkas
                            Diproses</p>
                        <h1 class="display-4 font-weight-bold mb-0">{{ $statsTerkini['diproses']->total ?? 0 }}</h1>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 fw-semibold text-white-50 text-uppercase tracking-wider">Selesai</p>
                        <h1 class="display-4 font-weight-bold mb-0">{{ $statsTerkini['selesai']->total ?? 0 }}</h1>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Bulan Ini --}}
    <div class="d-flex align-items-center mb-3 mt-3">
        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
            style="width: 32px; height: 32px;">
            <i class="fas fa-calendar-alt text-white small"></i>
        </div>
        <h4 class="mb-0 fw-bold">Akumulasi Bulan Ini</h4>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase tracking-wider">Total Pengajuan
                        </p>
                        <h1 class="display-4 font-weight-bold text-dark mb-0">
                            {{ $statsBulanIni['pengajuan']->total ?? 0 }}
                        </h1>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-folder-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase tracking-wider">Total Diproses
                        </p>
                        <h1 class="display-4 font-weight-bold text-dark mb-0">
                            {{ $statsBulanIni['diproses']->total ?? 0 }}
                        </h1>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-sync-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: transform 0.3s ease;"
                onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase tracking-wider">Total Selesai</p>
                        <h1 class="display-4 font-weight-bold text-dark mb-0">
                            {{ $statsBulanIni['selesai']->total ?? 0 }}
                        </h1>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-award fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>