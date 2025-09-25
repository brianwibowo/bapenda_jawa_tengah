<x-app-layout>
    <x-slot name="header">
        Detail Pengajuan
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail Pengajuan dari: {{ $pengajuan->user->name }}</h4>
            <p class="mb-0">Tanggal: {{ $pengajuan->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <div class="card-body">
            <h5 class="mb-3">Dokumen Terlampir:</h5>
            <ul class="list-group list-group-flush mb-4">
                @forelse ($dokumen as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ $doc->getUrl() }}" target="_blank">
                            <i class="fas fa-file-alt me-2"></i>{{ $doc->file_name }}
                        </a>
                        <span class="badge bg-secondary">{{ $doc->human_readable_size }}</span>
                    </li>
                @empty
                    <li class="list-group-item">Tidak ada dokumen yang dilampirkan.</li>
                @endforelse
            </ul>

            <hr>

            <h5 class="mb-3">Update Status Pengajuan</h5>
             @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label">Ubah Status Menjadi:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="diproses" {{ $pengajuan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="catatan_admin" class="form-label">Catatan untuk Penulis (Opsional)</label>
                    <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3">{{ $pengajuan->catatan_admin }}</textarea>
                    <small class="form-text text-muted">Catatan ini akan bisa dilihat oleh penulis di halaman riwayat pengajuannya.</small>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
</x-app-layout>