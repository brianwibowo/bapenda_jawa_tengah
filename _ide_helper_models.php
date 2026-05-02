<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $wilayah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengajuan> $pengajuans
 * @property-read int|null $pengajuans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereWilayah($value)
 */
	class Cabang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pengajuan_id
 * @property string $nama_pemilik
 * @property string $nik_pemilik
 * @property string $alamat_pemilik
 * @property string $telp_pemilik
 * @property string $email_pemilik
 * @property string $nrkb
 * @property string $jenis_kendaraan
 * @property string $model_kendaraan
 * @property string $merk_kendaraan
 * @property string $tipe_kendaraan
 * @property string $tahun_pembuatan
 * @property string $isi_silinder
 * @property string $jenis_bahan_bakar
 * @property string $nomor_rangka
 * @property string $nomor_mesin
 * @property string $warna_tnkb
 * @property string $nomor_bpkb
 * @property string $status
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KendaraanLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Pengajuan $pengajuan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereAlamatPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereEmailPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereIsiSilinder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereJenisBahanBakar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereJenisKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereMerkKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereModelKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNamaPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNikPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorBpkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorRangka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNrkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTahunPembuatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTelpPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTipeKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereWarnaTnkb($value)
 * @mixin \Eloquent
 * @property int|null $pemilik_id
 * @property-read \App\Models\Pemilik|null $pemilik
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan wherePemilikId($value)
 */
	class Kendaraan extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kendaraan_id
 * @property int $user_id
 * @property string $aksi
 * @property string $status_baru
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kendaraan $kendaraan
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereKendaraanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereStatusBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $tipe tipe log: komentar|revisi|admin|system
 */
	class KendaraanLog extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_pemilik
 * @property string $nik_pemilik
 * @property string $alamat_pemilik
 * @property string $telp_pemilik
 * @property string|null $email_pemilik
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kendaraan> $kendaraans
 * @property-read int|null $kendaraans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereAlamatPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereEmailPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereNamaPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereNikPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereTelpPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemilik whereUpdatedAt($value)
 */
	class Pemilik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $nomor_pengajuan
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $cabang_id
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kendaraan> $kendaraans
 * @property-read int|null $kendaraans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SuratKeputusan> $suratKeputusans
 * @property-read int|null $surat_keputusans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SuratPengajuan> $suratPengajuan
 * @property-read int|null $surat_pengajuan_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereNomorPengajuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan withoutTrashed()
 */
	class Pengajuan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pengajuan_id
 * @property int $user_id
 * @property string $unit_kerja
 * @property string $nomor_sk
 * @property string $perihal
 * @property string $isi_putusan
 * @property \Illuminate\Support\Carbon $tanggal_ditetapkan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereIsiPutusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereNomorSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereTanggalDitetapkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $pembuat
 * @property-read \App\Models\Pengajuan $pengajuan
 */
	class SuratKeputusan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pengajuan_id
 * @property string $nomor_sp
 * @property \Illuminate\Support\Carbon $tanggal_surat
 * @property array $persetujuan_unit_kerja
 * @property string|null $catatan_instansi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereCatatanInstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereNomorSp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan wherePersetujuanUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereTanggalSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Pengajuan $pengajuan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan wherePengajuanId($value)
 */
	class SuratPengajuan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $unit_kerja
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, KendaraanLog> $kendaraanLogs
 * @property-read int|null $kendaraan_logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengajuan> $pengajuans
 * @property-read int|null $pengajuans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 * @property string|null $profile_photo_path
 * @property string|null $jabatan
 * @property int|null $cabang_id
 * @property-read \App\Models\Cabang|null $cabang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 */
	class User extends \Eloquent {}
}

