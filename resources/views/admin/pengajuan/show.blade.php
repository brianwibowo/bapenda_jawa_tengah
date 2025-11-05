<x-app-layout>
    <x-slot name="header">
        Dasbor Bundel Pengajuan: {{ $pengajuan->nomor_pengajuan }}
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
             <h4 class="alert-title">Gagal Menyimpan!</h4>
             <ul class="ps-3 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- === KOLOM UTAMA: TABEL INTERAKTIF KENDARAAN === -->
        <div class="col-12">
            
            <div class="card">
                {{-- Form 'batchUpdate' SEKARANG DIMULAI DI SINI --}}
                <form action="{{ route('admin.pengajuan.batchUpdate', $pengajuan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <h4 class="card-title mb-2 mb-md-0">
                            Daftar Kendaraan (Total: {{ $pengajuan->kendaraans->count() }})
                        </h4>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">NRKB</th>
                                        <th style="width: 15%;">Merk / Tipe</th>
                                        <th style="width: 15%;">Pemilik</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 15%;">Ubah Status</th>
                                        <th style="width: 15%;">Catatan</th>
                                        <th style="width: 15%;">Lampiran</th> {{-- <-- KOLOM BARU --}}
                                        <th style="width: 10%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuan->kendaraans as $kendaraan)
                                        <tr>
                                            {{-- Info Kendaraan --}}
                                            <td><strong>{{ $kendaraan->nrkb }}</strong></td>
                                            <td>{{ $kendaraan->merk_kendaraan }} / {{ $kendaraan->tipe_kendaraan }}</td>
                                            <td>{{ $kendaraan->nama_pemilik }}</td>
                                            
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
                                                <select name="status[{{ $kendaraan->id }}]" class="form-select form-select-sm">
                                                    <option value="pengajuan" {{ $kendaraan->status == 'pengajuan' ? 'selected' : '' }}>Diajukan</option>
                                                    <option value="diproses" {{ $kendaraan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="selesai" {{ $kendaraan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="ditolak" {{ $kendaraan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="catatan[{{ $kendaraan->id }}]" class="form-control form-control-sm" placeholder="Catatan untuk log...">
                                            </td>
                                            <td>
                                                {{-- INPUT FILE BARU (LAMPIRAN) --}}
                                                <input type="file" name="lampiran[{{ $kendaraan->id }}]" class="form-control form-control-sm">
                                            </td>

                                            {{-- Kolom Aksi (View & Delete) --}}
                                            <td class="text-center">
                                                <a href="{{ route('kendaraan.show', $kendaraan) }}" class="btn btn-sm btn-info" title="Lihat Detail & Dokumen" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                {{-- PERBAIKAN: Tombol Hapus sekarang memicu MODAL --}}
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Kendaraan" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $kendaraan->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                {{-- Form 'batchUpdate' SEKARANG BERAKHIR DI SINI --}}
                </form>
            </div>
        </div>

        <!-- Log Histori Gabungan (Opsional) -->
        <div class="col-12 mt-4">
             <div class="card">
                <div class="card-header">
                     <h4 class="card-title mb-0">Log Histori Gabungan (Semua Kendaraan di Bundel Ini)</h4>
                </div>
                 <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>NRKB</th>
                                    <th>Aksi</th>
                                    <th>Status Baru</th>
                                    <th>Oleh</th>
                                    <th>Catatan</th>
                                    <th class="text-center">Lampiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allLogs = $pengajuan->kendaraans->flatMap(function($kendaraan) {
                                        return $kendaraan->logs;
                                    })->sortByDesc('created_at');
                                @endphp
                                
                                @forelse ($allLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                        <td><strong>{{ $log->kendaraan->nrkb ?? 'N/A' }}</strong></td>
                                        <td>{{ $log->aksi }}</td>
                                        <td>
                                            @if($log->status_baru == 'pengajuan') <span class="badge bg-warning text-dark">Diajukan</span>
                                            @elseif($log->status_baru == 'diproses') <span class="badge bg-info text-dark">Diproses</span>
                                            @elseif($log->status_baru == 'selesai') <span class="badge bg-success">Selesai</span>
                                            @elseif($log->status_baru == 'ditolak') <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->user->name ?? 'N/A' }}
                                            @if($log->user && $log->user->unit_kerja)
                                                <br><small class="text-muted">{{ $log->user->unit_kerja }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $log->catatan ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($log->getFirstMedia('lampiran_log'))
                                                <a href="{{ $log->getFirstMediaUrl('lampiran_log') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-paperclip"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            Belum ada histori tindakan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- === MODAL HAPUS KENDARAAN (DI LUAR FORM UTAMA) === -->
    @foreach ($pengajuan->kendaraans as $kendaraan)
        <div class="modal fade" id="deleteModal-{{ $kendaraan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $kendaraan->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $kendaraan->id }}">Hapus Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus kendaraan <strong>{{ $kendaraan->nrkb }}</strong>? Aksi ini akan dicatat dan tidak bisa dibatalkan.
                    </div>
                    <div class="modal-footer">
                        {{-- Ini adalah form hapus yang aman (tidak nested) --}}
                        <form action="{{ route('kendaraan.destroy', $kendaraan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus Kendaraan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>