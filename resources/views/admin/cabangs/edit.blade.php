<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Ubah Cabang Samsat</h2>
            <a href="{{ route('admin.cabangs.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </x-slot>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.cabangs.update', $cabang) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Cabang</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $cabang->nama) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wilayah (Kota / Kabupaten)</label>
                    <input type="text" name="wilayah" class="form-control" value="{{ old('wilayah', $cabang->wilayah) }}" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Cabang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
