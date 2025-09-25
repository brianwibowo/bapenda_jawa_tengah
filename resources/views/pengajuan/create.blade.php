<x-app-layout>
    <x-slot name="header">
        Buat Pengajuan Berkas Baru
    </x-slot>

    {{-- Menampilkan pesan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="ps-3 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="surat_permohonan" class="form-label">1. Surat permohonan penghapusan (PDF/DOCX)</label>
                    <input type="file" class="form-control" id="surat_permohonan" name="surat_permohonan" required>
                </div>
                <div class="mb-3">
                    <label for="surat_pernyataan" class="form-label">2. Surat pernyataan kepemilikan (PDF/DOCX)</label>
                    <input type="file" class="form-control" id="surat_pernyataan" name="surat_pernyataan" required>
                </div>
                <div class="mb-3">
                    <label for="ktp" class="form-label">3. Tanda bukti identitas pemilik (KTP)</label>
                    <input type="file" class="form-control" id="ktp" name="ktp" required>
                </div>
                 <div class="mb-3">
                    <label for="bpkb" class="form-label">4. BPKB</label>
                    <input type="file" class="form-control" id="bpkb" name="bpkb" required>
                </div>
                 <div class="mb-3">
                    <label for="tbpkp" class="form-label">5. TBPKP</label>
                    <input type="file" class="form-control" id="tbpkp" name="tbpkp" required>
                </div>
                <div class="mb-3">
                    <label for="cek_fisik" class="form-label">6. Hasil pemeriksaan cek fisik</label>
                    <input type="file" class="form-control" id="cek_fisik" name="cek_fisik" required>
                </div>
                <div class="mb-3">
                    <label for="foto_ranmor" class="form-label">7. Foto Kendaraan (JPG/PNG)</label>
                    <input type="file" class="form-control" id="foto_ranmor" name="foto_ranmor" required>
                </div>
                <div class="mb-3">
                    <label for="stnk" class="form-label">8. STNK</label>
                    <input type="file" class="form-control" id="stnk" name="stnk" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ajukan Berkas</button>
            </form>
        </div>
    </div>
</x-app-layout>