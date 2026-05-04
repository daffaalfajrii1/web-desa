# Task: Integrasi Pegawai dengan Absensi

## Konteks
Project ini adalah aplikasi web desa berbasis Laravel dengan panel admin AdminLTE.

Modul Pegawai / SOTK dan modul Absensi sudah ada.
Sekarang perlu revisi agar integrasi keduanya lebih rapi.

Fokus revisi:
1. PIN absensi langsung diinput saat tambah/edit pegawai
2. pegawai aktif otomatis dipakai oleh modul absensi
3. nama pegawai otomatis muncul di rekap absensi
4. pegawai baru yang aktif dan punya PIN otomatis ikut sistem absensi

---

## Tujuan
Saya ingin alur seperti ini:

1. Admin menambah pegawai
2. Admin langsung mengisi `pin_absensi` saat tambah pegawai
3. Jika pegawai aktif dan punya PIN, maka:
   - pegawai otomatis muncul di halaman input absensi
   - pegawai otomatis muncul di rekap absensi
4. Modul absensi tidak lagi memakai nama manual
5. Semua absensi harus memakai relasi ke tabel pegawai

---

## Revisi yang Diinginkan

### A. Form Pegawai
Pada form tambah/edit pegawai:
- field `pin_absensi` harus tersedia
- field `pin_absensi` dibuat wajib
- tampilkan helper text:
  "Wajib diisi agar pegawai bisa digunakan untuk absensi."

Validasi:
- required
- minimal 4 digit/karakter
- jika memungkinkan unik per pegawai

---

### B. Model dan Database Pegawai
Pastikan:
- kolom `pin_absensi` ada di tabel pegawai
- `pin_absensi` masuk ke `$fillable`
- relasi pegawai ke absensi sudah benar

Relasi:
- Employee/Pegawai hasMany Attendances
- Attendance belongsTo Employee/Pegawai

---

### C. Input Absensi
Halaman input absensi harus:
- mengambil daftar pegawai dari tabel pegawai
- hanya menampilkan pegawai aktif
- hanya menampilkan pegawai yang punya `pin_absensi`
- tidak memakai input nama manual

Filter pegawai:
- `is_active = true`
- `pin_absensi` tidak null
- `pin_absensi` tidak kosong

---

### D. Rekap Absensi
Rekap absensi harus:
- mengambil nama pegawai dari relasi tabel pegawai
- bukan dari input manual
- jika rekap bulanan berbentuk grid:
  - baris pegawai diambil dari tabel pegawai aktif
  - bukan hanya dari tabel attendances
- jadi walaupun pegawai belum absen, dia tetap muncul di grid rekap

Artinya:
- pegawai aktif + punya PIN harus otomatis muncul di rekap bulanan/tahunan

---

## Query yang Diharapkan

### Data pegawai untuk absensi
Gunakan query seperti:
- pegawai aktif
- pin_absensi tidak null
- pin_absensi tidak kosong
- urutkan berdasarkan urutan tampil dan nama

### Data pegawai untuk rekap
Sumber utama rekap harus dari tabel pegawai, bukan dari tabel attendances saja.

Lalu attendance dijadikan map per tanggal.

---

## File yang Perlu Dicek / Direvisi
Tolong cek dan revisi seperlunya pada:

1. form create pegawai
2. form edit pegawai
3. controller pegawai (store/update validation)
4. model pegawai
5. migration pegawai jika perlu
6. controller input absensi
7. controller rekap absensi
8. blade input absensi
9. blade rekap absensi

---

## Target Akhir
Target akhir implementasi:

1. Saat tambah pegawai, admin wajib mengisi PIN absensi
2. Pegawai aktif yang punya PIN otomatis bisa dipakai di input absensi
3. Pegawai aktif yang punya PIN otomatis muncul di rekap absensi
4. Rekap absensi memakai relasi ke pegawai, bukan nama manual
5. Pegawai baru tidak perlu dibuat ulang di modul absensi

---

## Output yang Diharapkan dari Codex
Tolong kerjakan revisi ini dan tampilkan:

1. file yang direvisi
2. validasi pegawai yang direvisi
3. query input absensi yang direvisi
4. query rekap absensi yang direvisi
5. blade form pegawai yang direvisi
6. penjelasan singkat perubahan

Jangan ubah modul lain yang tidak terkait.
Gunakan gaya Laravel + AdminLTE yang konsisten dengan project ini.