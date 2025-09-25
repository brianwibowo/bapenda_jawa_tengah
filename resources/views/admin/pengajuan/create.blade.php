<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Berkas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="surat_permohonan" class="form-label">Surat permohonan penghapusan regident ranmor (PDF/DOCX)</label>
                            <input type="file" class="form-control" id="surat_permohonan" name="surat_permohonan" required>
                        </div>
                        <div class="mb-3">
                            <label for="surat_pernyataan" class="form-label">Surat pernyataan kepemilikan ranmor (PDF/DOCX)</label>
                            <input type="file" class="form-control" id="surat_pernyataan" name="surat_pernyataan" required>
                        </div>
                        <div class="mb-3">
                            <label for="ktp" class="form-label">Tanda bukti identitas pemilik motor (KTP)</label>
                            <input type="file" class="form-control" id="ktp" name="ktp" required>
                        </div>
                         <div class="mb-3">
                            <label for="bpkb" class="form-label">BPKB</label>
                            <input type="file" class="form-control" id="bpkb" name="bpkb" required>
                        </div>
                         <div class="mb-3">
                            <label for="tbpkp" class="form-label">TBPKP</label>
                            <input type="file" class="form-control" id="tbpkp" name="tbpkp" required>
                        </div>
                        <div class="mb-3">
                            <label for="cek_fisik" class="form-label">Hasil pemeriksaan cek fisik ranmor</label>
                            <input type="file" class="form-control" id="cek_fisik" name="cek_fisik" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto_ranmor" class="form-label">Foto Ranmor (JPG/PNG)</label>
                            <input type="file" class="form-control" id="foto_ranmor" name="foto_ranmor" required>
                        </div>
                        <div class="mb-3">
                            <label for="stnk" class="form-label">STNK</label>
                            <input type="file" class="form-control" id="stnk" name="stnk" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>