<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Pilih Surat Keputusan</h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex align-items-center">
                <a href="{{ !empty($admin) && $admin ? route('admin.pengajuan.show', $pengajuan->id) : route('pengajuan.show', $pengajuan->id) }}"
                    class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-4">
                <button type="button" class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;"
                    data-bs-toggle="modal" data-bs-target="#modalSkRegident">
                    <i class="fas fa-file-alt mb-2 fs-3 d-block text-center"></i>
                    SURAT KETERANGAN PENGHAPUSAN REGIDENT RANMOR (ABDUL)
                </button>
            </div>
            <div class="col-md-6 col-lg-4">
                <button class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;">
                    <i class="fas fa-file-contract mb-2 fs-3 d-block text-center"></i>
                    SK JASA RAHARJA
                </button>
            </div>
            <div class="col-md-6 col-lg-4">
                <button class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;">
                    <i class="fas fa-shield-alt mb-2 fs-3 d-block text-center"></i>
                    SK POLDA
                </button>
            </div>
            <div class="col-md-6 col-lg-4">
                <button class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;"
                    data-bs-toggle="modal" data-bs-target="#modalSkPembebasan">
                    <i class="fas fa-building mb-2 fs-3 d-block text-center"></i>
                    SK KEPALA BAPENDA
                </button>
            </div>
            <div class="col-md-6 col-lg-4">
                <button class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;">
                    <i class="fas fa-envelope mb-2 fs-3 d-block text-center"></i>
                    SURAT BALASAN JASA RAHARJA
                </button>
            </div>
            <div class="col-md-6 col-lg-4">
                <button class="btn w-100 py-3 fw-bold text-dark shadow-sm hover-shadow"
                    style="background-color: #FEC014; border: none; font-size: 14px;">
                    <i class="fas fa-file-excel mb-2 fs-3 d-block text-center"></i>
                    SK PENGHAPUSAN REGIDENT (FREYSIA)
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form SK Regident di-include dari file terpisah -->
    @include('pengajuan.modals.sk_regident')
    @include('pengajuan.modals.sk_bapenda_pembebasan')
</x-app-layout>