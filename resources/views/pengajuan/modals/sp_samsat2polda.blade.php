{{-- Modal Sederhana: SP Samsat (Samsat → Polda) --}}
<div class="modal fade modal-fit-content" id="modalSpSamsat" tabindex="-1" aria-labelledby="modalSpSamsatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSpSamsatLabel">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Surat Pengajuan ke Polda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            
            <div class="col-12">
                <div class="card border-0 shadow-sm panel-card">
                    {{-- Form 'batchUpdate' SEKARANG DIMULAI DI SINI --}}
                    <form action="{{ $signedUrls['sp_ajukan'] ?? '#' }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header panel-header d-flex flex-wrap justify-content-between align-items-center">
                            <h4 class="card-title mb-2 mb-md-0">
                                Daftar Kendaraan (Total: {{ $pengajuan->kendaraans->count() }})
                            </h4>
                        </div>
                        <div class="card-body panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle table-dashboard">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 15%;">NRKB</th>
                                            <th style="width: 15%;">Merk / Tipe</th>
                                            <th style="width: 15%;">Pemilik</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 15%;">Ubah Status</th>
                                            <th style="width: 20%;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pengajuan->kendaraans as $kendaraan)
                                            <tr>
                                                {{-- Info Kendaraan --}}
                                                <td><strong>{{ $kendaraan->nrkb }}</strong></td>
                                                <td>{{ $kendaraan->merk_kendaraan }} / {{ $kendaraan->tipe_kendaraan }}</td>
                                                <td>{{ $kendaraan->pemilik->nama_pemilik ?? '-' }}</td>
                                                
                                                {{-- Status Saat Ini --}}
                                                <td>
                                                    @if($kendaraan->status == 'pengajuan')
                                                        <span class="badge bg-warning text-dark">Diajukan</span>
                                                    @elseif($kendaraan->status == 'diproses')
                                                        <span class="badge bg-info text-dark">Diproses</span>
                                                    @elseif($kendaraan->status == 'selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif($kendaraan->status == 'ditolak')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
    
                                                {{-- Kolom Aksi (Form Input) --}}
                                                <td>
                                                    <span class="badge bg-info text-dark">Diproses</span>
                                                </td>

                                                <input type="hidden" name="status[{{ $kendaraan->id }}]" value="diproses">
    
                                                {{-- Kolom Aksi (View) --}}
                                                <td class="text-center">
                                                    <a href="{{ route('kendaraan.show', $kendaraan) }}" class="btn btn-sm btn-info" title="Lihat Detail & Dokumen" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-3"> {{-- Colspan jadi 8 --}}
                                                    Bundel ini belum memiliki kendaraan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnKirimSpSamsat">
                                <i class="fas fa-save me-1"></i> Kirim
                            </button>
                        </div>
                    {{-- Form 'batchUpdate' SEKARANG BERAKHIR DI SINI --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSpSamsat = document.getElementById('formSpSamsat');
    if (formSpSamsat) {
        formSpSamsat.addEventListener('submit', function(e) {
            document.getElementById('selectStatus').disabled = false;
            const btn = document.getElementById('btnKirimSpSamsat');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
    }
});
</script>
