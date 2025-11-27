# Bug Fix: Duplikasi Data Kendaraan Saat Penghapusan

## Masalah yang Dilaporkan
Saat testing:
1. Isi kendaraan 1 dan simpan ✓
2. Tambah kendaraan 2, isi beberapa data
3. Hapus kendaraan 2
4. Simpan dan buat pengajuan
5. **BUG**: Di nomor pengajuan masih ada 2 kendaraan, dan kendaraan 2 isinya sama dengan kendaraan 1

## Root Cause Analysis

Beberapa issue potensial yang ditemukan:

### Issue 1: Missing State Cleanup (FIXED)
**File**: `resources/views/pengajuan/create.blade.php` (Line 560)

**Masalah**: Ketika `hapusKendaraan()` dipanggil, state `savingState[index]` tidak dihapus. Ini bisa menyebabkan race condition jika ada request save yang sedang pending.

**Fix**: Tambahkan `delete savingState[index];` setelah cleanup state lainnya

```javascript
// BEFORE
clearTimeout(autoSaveTimers[index]);
delete autoSaveTimers[index];
delete formDirtyState[index];
// MISSING: delete savingState[index];

// AFTER
clearTimeout(autoSaveTimers[index]);
delete autoSaveTimers[index];
delete formDirtyState[index];
delete savingState[index];  // ✓ DITAMBAHKAN
```

### Issue 2: Unsafe Renumbering Logic (FIXED)
**File**: `resources/views/pengajuan/create.blade.php` (Line 604-668)

**Masalah**: Fungsi `renumberKendaraans()` melakukan renumber tanpa cek apakah index benar-benar berubah. Juga tidak cleanup `savingState` dan `autoSaveTimers` yang terkait dengan index lama.

**Fix**: Perbaiki logic dengan:
1. Check `if (oldIndex !== newNumber)` sebelum update
2. Cleanup `savingState[oldIndex]` dan `autoSaveTimers[oldIndex]`
3. Buat mapping yang lebih jelas

```javascript
// BEFORE: Selalu execute update walaupun index tidak berubah
forms.forEach((form, newIndex) => {
    const newNumber = newIndex + 1;
    const oldIndex = form.getAttribute('data-kendaraan-index');
    
    // ALWAYS update, tidak ada check
    form.setAttribute('data-kendaraan-index', newNumber);
    // ... etc
});

// AFTER: Check apakah perlu update
forms.forEach((form, newIndex) => {
    const newNumber = newIndex + 1;
    const oldIndex = parseInt(form.getAttribute('data-kendaraan-index'));
    
    if (oldIndex !== newNumber) {  // ✓ ONLY UPDATE IF NEEDED
        form.setAttribute('data-kendaraan-index', newNumber);
        // ... etc
        
        // ✓ CLEANUP OLD STATE
        if (Object.prototype.hasOwnProperty.call(savingState, oldIndex)) {
            delete savingState[oldIndex];
        }
        if (Object.prototype.hasOwnProperty.call(autoSaveTimers, oldIndex)) {
            clearTimeout(autoSaveTimers[oldIndex]);
            delete autoSaveTimers[oldIndex];
        }
    }
});
```

### Issue 3: Debug Logging (ADDED)
**File**: `resources/views/pengajuan/create.blade.php` (Multiple locations)

Tambahkan console.log di beberapa fungsi untuk memudahkan debugging:
- `hapusKendaraan()`: Log saat delete dimulai dan selesai, plus state akhir
- `deleteSavedKendaraan()`: Log saat DELETE API dipanggil dan response

## Testing Steps untuk Memverifikasi Fix

### Test Case 1: Delete Kendaraan Sebelum Auto-save
1. Buka halaman Buat Pengajuan
2. Isi Kendaraan 1 lengkap dengan file → **Simpan** (tunggu 5-10 detik)
3. Tambah Kendaraan 2
4. Isi beberapa field di Kendaraan 2 (TAPI JANGAN TUNGGU AUTO-SAVE)
5. **Hapus Kendaraan 2** → Klik tombol X
6. Lihat dialog "Yakin ingin menghapus Kendaraan 2?" → Klik OK
7. **Seharusnya**: Hanya muncul 1 dialog, tidak perlu delete dari server
8. Kendaraan 2 hilang dari UI

### Test Case 2: Delete Kendaraan Setelah Auto-save  
1. Buka halaman Buat Pengajuan (clear cache dulu)
2. Isi Kendaraan 1 lengkap → **Simpan**
3. Tambah Kendaraan 2
4. Isi beberapa field di Kendaraan 2
5. **Tunggu 3+ detik** (biarkan auto-save trigger)
6. **Hapus Kendaraan 2**
7. Lihat dialog "Yakin ingin menghapus Kendaraan 2?" → Klik OK
8. Lihat dialog "Kendaraan ini sudah tersimpan di server..." → Klik OK
9. **Seharusnya**: Ada 2 dialog, DELETE API dipanggil ke server
10. Monitor di DevTools → Network tab, lihat DELETE request berhasil (200 OK)

### Test Case 3: Full Workflow (Most Important)
1. Clear localStorage: DevTools → Application → Storage → Clear All
2. Isi Kendaraan 1 lengkap (nama, NIK, semua file) → **Simpan**
3. Tambah Kendaraan 2
4. **Copy data dari Kendaraan 1 ke Kendaraan 2** (untuk simulasi copy-paste user)
5. Tunggu 3-5 detik (auto-save)
6. **Delete Kendaraan 2** → Confirm deletion dari server
7. Lihat state di console:
   ```javascript
   console.log(savedKendaraans);
   // Seharusnya: {1: {kendaraan_id: 5, pengajuan_id: 10}, 2: undefined}
   // BUKAN: {1: {kendaraan_id: 5}, 2: {kendaraan_id: 5}} ← WRONG
   ```
8. **Simpan & Finalize Pengajuan**
9. Lihat di halaman nomor pengajuan → **Seharusnya hanya ada 1 kendaraan**

## Browser Console Debugging

### Saat menghapus kendaraan, akan terlihat:
```
[hapusKendaraan] Hapus kendaraan index=2, savedInfo: {kendaraan_id: 6, pengajuan_id: 10}
[hapusKendaraan] Calling DELETE API untuk kendaraan_id=6
[deleteSavedKendaraan] DELETE request untuk kendaraan_id=6
[deleteSavedKendaraan] DELETE berhasil untuk kendaraan_id=6
[hapusKendaraan] Cleanup state untuk index=2
[hapusKendaraan] Selesai. savedKendaraans sekarang: {1: {kendaraan_id: 5, pengajuan_id: 10}}
```

### Jika ada error:
```
[deleteSavedKendaraan] DELETE gagal, status=404 (atau 422)
[deleteSavedKendaraan] Error: Gagal menghapus kendaraan tersimpan.
```

## Files Modified

1. **resources/views/pengajuan/create.blade.php**
   - Line 560: Added `delete savingState[index];`
   - Line 523-577: Added console logging to `hapusKendaraan()`
   - Line 583-607: Added console logging to `deleteSavedKendaraan()`
   - Line 604-668: Refactored `renumberKendaraans()` dengan better logic

## Backend Verification

Backend logic sudah OK:
- `KendaraanController::destroy()` - menghapus kendaraan dengan status check
- `PengajuanController::storeKendaraan()` - menyimpan kendaraan baru
- `KendaraanController::update()` - mengupdate kendaraan existing

Tidak ada perubahan backend diperlukan.

## Next Steps Jika Bug Masih Terjadi

1. Check database secara langsung:
   ```sql
   SELECT * FROM kendaraans WHERE pengajuan_id = 10;
   -- Harus hanya ada 1 record untuk kendaraan 1
   ```

2. Check media files di storage:
   ```bash
   ls -la storage/app/public/
   -- Pastikan file kendaraan 2 sudah dihapus
   ```

3. Trace ke Network tab di DevTools:
   - Lihat POST request saat auto-save
   - Lihat DELETE request saat delete
   - Pastikan response berhasil (200/201)

4. Check laravel.log:
   ```bash
   tail -f storage/logs/laravel.log
   -- Lihat error message jika ada
   ```
