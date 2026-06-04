# Panduan Penggunaan & Panduan Teknis (Manual Detail) - Aplikasi Hapus Regident Bapenda Jawa Tengah

Dokumen ini berisi panduan lengkap untuk melakukan instalasi, konfigurasi, serta panduan operasional penggunaan aplikasi bagi Wajib Pajak dan Petugas Instansi (Samsat, Polda, Bapenda, Jasa Raharja).

---

## BAGIAN I: PANDUAN INSTALASI & KONFIGURASI (DEVELOPER & IT)

### 1. Prasyarat Sistem (Prerequisites)
- PHP `>= 8.1`
- Composer `>= 2.0`
- Node.js & NPM (untuk kompilasi aset Frontend dengan Vite)
- Database: MySQL 8.x / PostgreSQL / SQLite
- Extension PHP yang dibutuhkan: `GD`, `Imagick`, `DOM`, `XML`, `ZIP`

### 2. Langkah-Langkah Instalasi
Jalankan perintah berikut pada terminal di server atau mesin lokal Anda:

1. **Clone Repositori dan Masuk ke Direktori Project**:
   ```bash
   git clone <repository_url>
   cd bapenda_jawa_tengah
   ```

2. **Instalasi Dependency PHP & JavaScript**:
   ```bash
   composer install
   npm install
   ```

3. **Duplikasi File Environment dan Konfigurasi**:
   ```bash
   cp .env.example .env
   ```
   *Buka file `.env` menggunakan text editor dan sesuaikan kredensial database Anda:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bapenda_jawa_tengah
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding Wilayah IndoRegion**:
   Sistem menggunakan package `indoregion` untuk data wilayah Indonesia. Jalankan perintah ini secara berurutan:
   ```bash
   php artisan migrate
   php artisan indoregion:publish
   composer dump-autoload
   php artisan db:seed --class=IndoRegionSeeder
   ```

6. **Seeding Hak Akses (RBAC) dan Data Awal**:
   ```bash
   php artisan db:seed
   ```
   *Catatan: Pastikan seeder membuat akun default untuk masing-masing instansi (Samsat, Polda, Bapenda, Jasa Raharja, dan Wajib Pajak).*

7. **Konfigurasi File Storage Link**:
   Aplikasi menggunakan Spatie Media Library yang memerlukan symbolic link untuk publikasi berkas lampiran:
   ```bash
   php artisan storage:link
   ```

8. **Kompilasi Aset dan Menjalankan Aplikasi**:
   ```bash
   # Di terminal 1: Jalankan compiler aset
   npm run dev

   # Di terminal 2: Jalankan web server lokal Laravel
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses secara default di `http://127.0.0.1:8000`.

---

## BAGIAN II: MANUAL OPERASIONAL PENGGUNAAN APLIKASI

---

### A. Panduan untuk Wajib Pajak (Warga / Dealer / Badan Usaha)

Wajib Pajak bertanggung jawab untuk mendaftarkan dan mengajukan kendaraan bermotor yang akan dihapus datanya dari sistem registrasi kepolisian.

#### 1. Membuat Pengajuan Baru
1. Masuk ke halaman **Dashboard** menggunakan akun Wajib Pajak Anda.
2. Pada menu sidebar kiri, klik **Buat Pengajuan**.
3. Pilih **Kantor Cabang Samsat** tujuan sesuai dengan lokasi berkas kendaraan Anda terdaftar.
4. Klik tombol **Tambah Kendaraan** untuk memunculkan formulir data kendaraan pertama.
5. Isi formulir informasi pemilik kendaraan:
   - Nama Pemilik
   - NIK (Nomor Induk Kependudukan)
   - Alamat Lengkap
   - Nomor Telepon & Email
6. Isi spesifikasi kendaraan:
   - NRKB (Nomor Registrasi Kendaraan Bermotor / Plat Nomor)
   - Merek, Tipe, Jenis, dan Model Kendaraan
   - Tahun Pembuatan & Isi Silinder (CC)
   - Nomor Rangka & Nomor Mesin
   - Warna TNKB & Nomor BPKB
7. Unggah berkas persyaratan wajib (format PDF/Gambar):
   - KTP Pemilik
   - STNK Kendaraan
   - BPKB Kendaraan
   - Foto Fisik Kendaraan (tampak depan, samping, nomor rangka)
8. *Fitur Auto-Save*: Sistem akan menyimpan data Anda secara otomatis ke server setiap 3 detik selama pengisian untuk mencegah hilangnya data jika koneksi terputus.
9. Jika Anda ingin mengajukan penghapusan lebih dari satu kendaraan sekaligus dalam satu berkas pengajuan, klik tombol **Tambah Kendaraan** lagi dan isi formulir berikutnya.
10. Klik **Simpan & Kirim Pengajuan** untuk mengirimkannya ke petugas verifikator Samsat.

#### 2. Memantau Progress & Mengoreksi Revisi
- Buka menu **Daftar Pengajuan Saya**.
- Anda dapat melihat status pengajuan Anda (`Draft`, `Pengajuan`, `Diproses`, `Selesai`, atau `Ditolak`).
- Klik **Detail** pada salah satu pengajuan untuk melihat progress persentase dan riwayat log.
- **Jika Status "Ditolak / Revisi"**: 
  1. Klik tombol **Edit** pada kendaraan yang ditolak.
  2. Baca **Catatan Admin** yang disertakan oleh petugas di dalam log histori.
  3. Perbaiki berkas atau isian data yang salah, lalu klik **Kirim Revisi** untuk mengirim kembali berkas tersebut ke petugas verifikator.

---

### B. Panduan untuk Petugas Samsat (Verifikator Awal)

Petugas Samsat bertindak sebagai gerbang pertama verifikasi berkas pengajuan yang diajukan oleh Wajib Pajak.

#### 1. Memproses Pengajuan yang Masuk
1. Masuk ke dashboard menggunakan akun **Petugas Samsat / Admin Cabang**.
2. Buka menu **Manajemen Pengajuan**.
3. Pilih pengajuan yang berstatus `Pengajuan` (berwarna kuning).
4. Periksa kecocokan data fisik kendaraan, nomor rangka, nomor mesin, dan kesesuaian berkas lampiran (KTP, STNK, BPKB) yang diunggah Wajib Pajak.
5. **Jika Berkas Kurang/Salah**:
   - Tulis catatan revisi pada kolom **Catatan Internal/Revisi**.
   - Ubah status kendaraan tersebut menjadi **Ditolak** (atau dikembalikan untuk direvisi).
6. **Jika Berkas Lengkap & Sah**:
   - Berikan tanda centang verifikasi berkas.
   - Ubah status kendaraan menjadi **Diproses**.
   - Sistem akan otomatis memicu pembuatan **Surat Pengajuan (SP) ke Polda** untuk tahapan validasi hukum dan regident lanjutan.

---

### C. Panduan untuk Polda (Polisi Daerah / Ditlantas)

Polda bertanggung jawab memvalidasi hukum kendaraan (tidak diblokir, tidak terlibat kriminalitas) dan menerbitkan Surat Keputusan (SK) Polda.

#### 1. Memvalidasi Surat Pengajuan Samsat
1. Masuk menggunakan akun **Petugas Polda / Regident**.
2. Pada menu **Persetujuan Surat Pengajuan**, cari nomor pengajuan yang sedang diproses.
3. Berikan status **Approved** pada persetujuan instansi Polda.
4. Sistem akan meneruskan Surat Pengajuan secara digital ke pihak Bapenda Jawa Tengah dan Jasa Raharja untuk pengecekan status fiskal/keuangan secara paralel.

#### 2. Menerbitkan SK Polda
1. Setelah Bapenda dan Jasa Raharja memberikan persetujuan, masuk kembali ke halaman detail pengajuan.
2. Klik tombol **Generate SK Polda** (sistem akan membuat draft PDF SK Polda secara dinamis).
3. Cetak dokumen PDF tersebut, lakukan penandatanganan (basah atau elektronik oleh Direktur Lalu Lintas Polda), dan unggah kembali salinan SK yang telah ditandatangani ke dalam kolom **Lampiran SK Polda**.

---

### D. Panduan untuk Bapenda & Jasa Raharja (Pengecekan Fiskal)

Bapenda dan Jasa Raharja memverifikasi aspek kepatuhan keuangan (Pajak & Sumbangan Wajib).

#### 1. Verifikasi Keuangan & Penerbitan SK
1. Masuk menggunakan masing-masing akun **Petugas Bapenda** atau **Petugas Jasa Raharja**.
2. Pada dasbor persetujuan, periksa apakah kendaraan memiliki tunggakan pajak (PKB) di sistem Bapenda atau tunggakan santunan kecelakaan (SWDKLLJ) di Jasa Raharja.
3. Klik **Setujui Surat Pengajuan** jika seluruh kewajiban finansial kendaraan tersebut dinyatakan lunas/bebas.
4. Klik **Generate SK Pembebasan / SK Jasa Raharja**, tandatangani dokumen tersebut, dan unggah salinannya ke kolom lampiran instansi masing-masing.

---

### E. Penyelesaian Pengajuan (Selesai)
- Setelah berkas pengajuan memiliki **3 Surat Keputusan (SK)** yang diunggah lengkap:
  1. SK Polda (Penghapusan Regident)
  2. SK Bapenda (Pembebasan Pajak)
  3. SK Jasa Raharja (Bebas SWDKLLJ)
- Status pengajuan bundel otomatis akan berubah secara sistem menjadi **Selesai**.
- Wajib Pajak akan mendapatkan notifikasi (melalui WhatsApp/Email jika dikonfigurasi) dan dapat mengunduh berkas rekapan pengajuan serta salinan resmi ketiga SK tersebut sebagai bukti sah bahwa kendaraan telah dihapus datanya secara permanen dari server registrasi nasional.
