<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>


    {{-- Header Dashboard & Identity --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold">Sistem Monitoring SIP-Hapus</h2>
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

    {{-- Kepemilikan Kumulatif --}}
    <div class="d-flex align-items-center mb-3 mt-3">
        <div class="bg-info rounded-circle me-2 d-flex align-items-center justify-content-center"
            style="width:32px;height:32px;">
            <i class="fas fa-users text-white small"></i>
        </div>
        <h4 class="mb-0 fw-bold">Kepemilikan Kumulatif</h4>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:20px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase">Perseorangan</p>
                        <h1 class="display-4 fw-bold text-dark mb-0">{{ $statsKepemilikan['perorangan']->total ?? 0 }}</h1>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width:60px;height:60px;">
                        <i class="fas fa-users text-white fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:20px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase">Instansi</p>
                        <h1 class="display-4 fw-bold text-dark mb-0">{{ $statsKepemilikan['instansi']->total ?? 0 }}</h1>
                    </div>
                    <div class="bg-purple bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:60px;height:60px;background-color:#f3e8ff!important;">
                        <i class="fas fa-building fa-2x" style="color:#7c3aed;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengajuan per Jenis & Wilayah (side-by-side) --}}
    <div class="d-flex align-items-center mb-3 mt-3">
        <div class="bg-success rounded-circle me-2 d-flex align-items-center justify-content-center"
            style="width:32px;height:32px;">
            <i class="fas fa-map-marker-alt text-white small"></i>
        </div>
        <h4 class="mb-0 fw-bold">Pengajuan per Jenis Kendaraan & Wilayah Samsat</h4>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:20px;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase">Pengajuan per Jenis Kendaraan</p>
                    </div>
                    <table id="jenis-table" class="table table-hover w-100">
                        <thead>
                            <tr>
                                <th>Jenis Kendaraan</th>
                                <th>Total Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsJenisKendaraan as $row)
                            <tr>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->total_pengajuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:20px;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <p class="card-text mb-1 text-muted fw-semibold text-uppercase">Pengajuan per Wilayah Samsat</p>
                    </div>
                    <table id="wilayah-table" class="table table-hover w-100">
                        <thead>
                            <tr>
                                <th>Wilayah Samsat</th>
                                <th>Total Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsWilayah as $row)
                            <tr>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->total_pengajuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart: Kendaraan per Tahun Pembuatan --}}
    <div class="d-flex align-items-center mb-3 mt-3">
        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
            <i class="fas fa-chart-bar text-white small"></i>
        </div>
        <h4 class="mb-0 fw-bold">Kendaraan per Tahun Pembuatan</h4>
    </div>

    <div class="card border-0 shadow-sm mb-5" style="border-radius:20px;">
        <div class="card-body p-4">
            <canvas id="tahun-chart" height="260" style="width:100%; height:260px;"></canvas>
        </div>
    </div>

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function () {
    $('#jenis-table, #wilayah-table').DataTable({
        pageLength: 10,
        order: [[1, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            paginate: {
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            },
            emptyTable: 'Tidak ada data tersedia'
        }
    });

    // Chart: Kendaraan per Tahun Pembuatan
    const tahunLabels = {!! json_encode($chartYears) !!} || [];
    const tahunData = {!! json_encode($chartTotals) !!} || [];

    const ctxEl = document.getElementById('tahun-chart');
    if (ctxEl) {
        // If there's no data, show a friendly placeholder
        if (!tahunLabels.length) {
            ctxEl.style.display = 'none';
            const noData = document.createElement('div');
            noData.className = 'text-center py-5 text-muted';
            noData.innerText = 'Tidak ada data tahun pembuatan tersedia';
            ctxEl.parentNode.appendChild(noData);
        } else {
            const ctx = ctxEl.getContext('2d');

            // gradient background for bars
            const grad = ctx.createLinearGradient(0, 0, 0, ctxEl.height || 300);
            grad.addColorStop(0, 'rgba(99, 102, 241, 0.9)');
            grad.addColorStop(1, 'rgba(79, 70, 229, 0.6)');

            const maxVal = Math.max(...tahunData, 1);
            const suggestedMax = maxVal <= 5 ? 5 : Math.ceil(maxVal * 1.2);

            // small plugin to draw values above bars
            const dataLabelPlugin = {
                id: 'dataLabel',
                afterDatasetsDraw(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const value = dataset.data[index];
                            ctx.font = '600 13px Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            const barHeight = Math.abs(bar.height || (bar.base - bar.y));

                            if (barHeight > 28) {
                                ctx.fillStyle = '#ffffff';
                                const centerY = bar.y + (bar.height / 2);
                                ctx.fillText(value, bar.x, centerY);
                            } else {
                                ctx.fillStyle = '#111827';
                                ctx.fillText(value, bar.x, bar.y - 8);
                            }
                        });
                    });
                }
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: tahunLabels,
                    datasets: [{
                        label: 'Jumlah Kendaraan',
                        data: tahunData,
                        backgroundColor: grad,
                        borderColor: 'rgba(79,70,229,1)',
                        borderWidth: 0,
                        borderRadius: 10,
                        maxBarThickness: 96,
                        barPercentage: 0.85,
                        categoryPercentage: 0.9
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Tahun Pembuatan' },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: suggestedMax,
                            ticks: { precision: 0 },
                            title: { display: true, text: 'Jumlah' }
                        }
                    }
                },
                plugins: [dataLabelPlugin]
            });
        }
    }
});
</script>
@endpush

</x-app-layout>