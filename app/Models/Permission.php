<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Get user-friendly alias/name for the permission in Indonesian.
     */
    public function getAliasAttribute(): string
    {
        $aliases = [
            'view_dashboard' => 'Lihat Dashboard',
            'view_menu_buat_pengajuan' => 'Lihat Menu Buat Pengajuan',
            'create_pengajuan_baru' => 'Buat Pengajuan Baru',
            'view_menu_daftar_pengajuan' => 'Lihat Menu Daftar Pengajuan',
            'edit_kendaraan_pengajuan_sendiri' => 'Ubah Kendaraan Pengajuan Sendiri',
            'delete_kendaraan_pengajuan_sendiri' => 'Hapus Kendaraan Pengajuan Sendiri',
            'view_menu_manajemen_pengajuan' => 'Lihat Menu Manajemen Pengajuan (Verifikator)',
            'approve_status_pengajuan' => 'Persetujuan / Verifikasi Status Pengajuan',
            'delete_pengajuan_publik' => 'Hapus Pengajuan Publik',
            'view_log_histori' => 'Lihat Log & Histori',
            'view_own_sk' => 'Lihat SK Sendiri / Terbit',
            'create_sk' => 'Buat Surat Keputusan',
            'create_pdf_pengajuan' => 'Cetak PDF Surat Pengajuan',
            'create_pdf_pengajuan_bapenda_jr' => 'Cetak PDF Surat Pengajuan Bapenda & Jasa Raharja',
            'create_pdf_balasan_polda' => 'Cetak PDF Surat Balasan Polda',
            'create_pdf_balasan_samsat' => 'Cetak PDF Surat Balasan Samsat',
            'view_dokumen_surat_pengajuan' => 'Lihat File Dokumen Surat Pengajuan',
            'view_dokumen_surat_balasan' => 'Lihat File Dokumen Surat Balasan',
            'view_menu_hak_akses' => 'Lihat Menu Hak Akses',
            'create_hak_akses' => 'Tambah Hak Akses',
            'view_menu_akses_group' => 'Lihat Menu Akses Group',
            'create_akses_group' => 'Tambah Akses Group',
            'edit_akses_group' => 'Ubah Akses Group',
            'delete_akses_group' => 'Hapus Akses Group',
            'view_menu_cabang' => 'Lihat Menu Cabang',
            'create_cabang' => 'Tambah Cabang',
            'edit_cabang' => 'Ubah Cabang',
            'delete_cabang' => 'Hapus Cabang',
            'view_menu_pengguna' => 'Lihat Menu Pengguna (Legacy)',
            'create_pengguna' => 'Tambah Pengguna',
            'edit_pengguna' => 'Ubah Pengguna',
            'delete_pengguna' => 'Hapus Pengguna',
            'view_menu_pengguna_wp' => 'Lihat Menu Pengguna WP',
            'view_menu_pengguna_stakeholder' => 'Lihat Menu Pemangku Kepentingan (Stakeholder)',
            'scoped_to_own_branch' => 'Batasi Akses Cabang Sendiri (Samsat Scoping)',
            'auto_process_on_action' => 'Proses Otomatis saat Aksi',
            'request_revision' => 'Minta Revisi Berkas',
            'submit_revision' => 'Kirim Revisi Berkas',
            'view_daftar_kendaraan' => 'Lihat Panel Daftar Kendaraan',
        ];

        return $aliases[$this->name] ?? ucwords(str_replace('_', ' ', $this->name));
    }
}
