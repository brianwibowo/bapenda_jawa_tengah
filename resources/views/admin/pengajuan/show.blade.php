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
                            <i class="fas fa-save me-1"></i> Simpan Status
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
                                                <select name="status[{{ $kendaraan->id }}]" class="form-select form-select-sm">
                                                    <option value="pengajuan" {{ $kendaraan->status == 'pengajuan' ? 'selected' : '' }}>Diajukan</option>
                                                    <option value="diproses" {{ $kendaraan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="selesai" {{ $kendaraan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="ditolak" {{ $kendaraan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </td>

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
                {{-- Form 'batchUpdate' SEKARANG BERAKHIR DI SINI --}}
                </form>
            </div>
        </div>

        <!-- Log Histori Gabungan (Opsional) -->
        <div class="col-12 mt-4">
            @includeWhen(true, 'pengajuan.partials.logs', ['admin' => true])
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