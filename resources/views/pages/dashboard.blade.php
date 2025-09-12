{{-- resources/views/pages/dashboard.blade.php --}}

{{-- Memberitahu Blade untuk menggunakan layout `app.blade.php` --}}
@extends('layouts.app')

{{-- Mengisi "slot" title --}}
@section('title', 'Dashboard')

{{-- Mengisi "slot" content --}}
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Free Bootstrap 5 Admin Dashboard</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
            </div>
        </div>
        
        {{-- ... Letakkan SEMUA KONTEN dari <div class="row"> sampai akhir konten halaman di sini ... --}}
        {{-- Contoh: statistik, chart, tabel, dll. --}}
        <div class="row">
            {{-- ... card stats ... --}}
        </div>
        <div class="row">
            {{-- ... card user statistics dan daily sales ... --}}
        </div>
        {{-- ... dan seterusnya ... --}}

    </div>
</div>
@endsection

{{-- Mengisi "slot" scripts dengan script yang hanya dibutuhkan di halaman ini --}}
@push('scripts')
<script>
    // Letakkan semua script inisialisasi chart di sini
    // Contoh:
    // var statisticsChart = new Chart(ctx, { ... });
    
    // Pastikan ID elemennya ada di dalam @section('content') di atas
</script>
@endpush