# Task: Modul Admin Absensi Pegawai

## Konteks Project
Project ini adalah aplikasi web desa berbasis Laravel dengan panel admin AdminLTE.

Project sudah memiliki modul:
- Pegawai / SOTK
- Role & permission
- Modul admin lain seperti berita, infografis, bansos, stunting, IDM, SDGS, dll

Fitur yang akan dibuat sekarang adalah **MODUL ADMIN ABSENSI PEGAWAI**.

Fokus saat ini:
- admin panel saja
- belum membuat modul publik

Absensi harus terhubung ke data pegawai / SOTK yang sudah ada.

---

## Tujuan Fitur
Membuat sistem absensi pegawai yang:
- memakai PIN absensi
- mendukung check in dan check out
- punya pengaturan jam kerja dari admin
- bisa validasi radius lokasi
- bisa mengenali hari libur nasional
- punya status hadir, telat, izin, sakit, alpa, libur
- punya rekap bulanan dan tahunan
- bisa export Excel dengan PhpSpreadsheet

---

## Kebutuhan Utama

### 1. PIN Absensi Pegawai
Setiap pegawai harus memiliki `pin_absensi`.

Jika tabel pegawai belum punya field ini, buat migration alter table untuk menambahkan:
- `pin_absensi`

Catatan:
- hanya pegawai aktif yang bisa absen
- pegawai tanpa PIN tidak bisa dipakai untuk absensi

---

### 2. Setting Absensi oleh Admin
Admin harus bisa mengatur setting absensi, minimal:

- `check_in_start`  
  contoh: `06:00`
- `check_in_end`  
  contoh: `07:30`
- `check_out_start`  
  contoh: `15:00`
- `check_out_end`  
  contoh: `23:59`
- `office_latitude`
- `office_longitude`
- `allowed_radius_meter`
- `validate_location` boolean
- `use_holiday_api` boolean
- `disable_sunday_attendance` boolean

Buat tabel misalnya:
- `attendance_settings`

Sistem cukup memakai 1 setting aktif/global.

---

### 3. Data Absensi
Buat tabel absensi misalnya:
- `attendances`

Field yang diinginkan:
- `id`
- `employee_id`
- `attendance_date`
- `check_in_time` nullable
- `check_out_time` nullable
- `status`
- `note` nullable
- `latitude` nullable
- `longitude` nullable
- `distance_meter` nullable
- `is_holiday` boolean default false
- `holiday_name` nullable
- `source` nullable
- timestamps

Tambahkan unique constraint:
- satu pegawai hanya boleh satu absensi per tanggal

---

## Status Absensi
Gunakan status berikut:
- `hadir`
- `telat`
- `izin`
- `sakit`
- `alpa`
- `libur`

Aturan:
- Check in normal => `hadir`
- Check in lewat batas => `telat`
- Hari libur nasional / Minggu jika dinonaktifkan => `libur`
- Admin bisa set manual jadi:
  - `izin`
  - `sakit`
  - `alpa`

---

## Logic Check In
Saat pegawai absen masuk:

1. validasi pegawai aktif
2. validasi PIN absensi
3. cek setting absensi aktif
4. cek apakah hari ini libur nasional
5. cek apakah hari Minggu dan setting disable Sunday aktif
6. validasi lokasi jika `validate_location = true`
7. hitung jarak ke titik kantor/desa
8. cek apakah pegawai sudah punya record absensi hari ini
9. jika belum ada, buat record baru:
   - `attendance_date = today`
   - `check_in_time = now`
   - `latitude`
   - `longitude`
   - `distance_meter`
10. tentukan status:
   - jika check in <= `check_in_end` dan masih dalam batas normal = `hadir`
   - jika check in > `check_in_end` = `telat`
11. jika hari libur, record bisa ditolak atau ditandai `libur` sesuai desain terbaik yang sederhana

---

## Logic Check Out
Saat pegawai absen pulang:

1. validasi pegawai aktif
2. validasi PIN
3. cari record absensi hari ini
4. kalau belum ada check in, tolak
5. kalau sudah ada `check_out_time`, tolak
6. validasi lokasi jika aktif
7. cek jam sekarang apakah sudah masuk rentang check out
8. isi:
   - `check_out_time = now`
   - `latitude`
   - `longitude`
   - `distance_meter`

---

## Logic Hari Libur Nasional
Sistem harus bisa membaca hari libur nasional Indonesia dari API.

Buat service/helper yang configurable.
Jangan hardcode yang sulit diganti.

Kebutuhan:
- ada service class misalnya `HolidayService`
- jika API gagal, jangan merusak sistem
- gunakan cache jika perlu
- hasil API bisa dipakai untuk:
  - menandai `is_holiday`
  - mengisi `holiday_name`

---

## Logic Hari Minggu
Tambahkan opsi setting:
- `disable_sunday_attendance`

Jika aktif:
- hari Minggu dianggap libur
- absensi masuk/pulang tidak bisa dilakukan
- rekap tetap bisa menampilkan hari itu sebagai libur

---

## Cron / Otomatis Alpa
Saya ingin ada proses otomatis untuk menandai pegawai yang tidak absen.

Buat command/logic yang bisa dijalankan via scheduler/cron, misalnya:
- cek semua pegawai aktif
- untuk tanggal tertentu, jika:
  - bukan hari libur nasional
  - bukan hari Minggu (jika disable_sunday_attendance aktif)
  - dan pegawai tidak punya record absensi
- maka buat record absensi status `alpa`

Buat command artisan misalnya:
- `attendance:mark-alpha`

Dan siapkan contoh scheduler di `app/Console/Kernel.php`.

---

## Rekap Absensi
Admin harus punya halaman rekap absensi yang mendukung:

### Filter:
- tanggal
- bulan
- tahun
- pegawai
- status
- search nama pegawai

### Summary cards:
- hadir hari ini
- telat hari ini
- izin hari ini
- sakit hari ini
- alpa hari ini
- libur hari ini
- total pegawai aktif

### Tampilan tabel:
- nama pegawai
- tanggal
- jam masuk
- jam pulang
- status
- keterangan
- lokasi / jarak jika perlu

---

## Rekap Bulanan dan Tahunan
Admin harus bisa melihat rekap:

### Rekap Bulanan
Per pegawai tampil:
- jumlah hadir
- jumlah telat
- jumlah izin
- jumlah sakit
- jumlah alpa
- jumlah libur

### Rekap Tahunan
Per pegawai tampil:
- total hadir
- total telat
- total izin
- total sakit
- total alpa
- total libur

---

## Export Excel
Gunakan **PhpSpreadsheet** karena project ini sudah memakainya.

Buat export:
1. detail absensi
2. rekap bulanan
3. rekap tahunan

Kebutuhan file Excel:
- header rapi
- autosize kolom
- styling dasar
- mudah dibaca admin

---

## Halaman Admin yang Harus Dibuat

### 1. Input Absen
Halaman admin untuk check in / check out.
Tampilan:
- card modern
- pilih pegawai
- input PIN
- latitude/longitude
- tombol Check In
- tombol Check Out
- tampilkan pesan validasi / sukses / gagal
- tampilkan info setting absensi aktif

### 2. Rekap Absensi
Tampilan:
- summary cards
- filter rapi
- tabel modern
- badge status berwarna

Warna status:
- hadir = hijau
- telat = kuning/oranye
- izin = biru
- sakit = cyan
- alpa = merah
- libur = abu / hijau lembut

### 3. Edit / Koreksi Absensi
Admin bisa edit:
- tanggal
- jam masuk
- jam pulang
- status
- catatan

### 4. Setting Absensi
Admin bisa edit:
- jam masuk
- jam pulang
- lokasi kantor/desa
- radius
- opsi validasi lokasi
- opsi holiday API
- opsi nonaktif hari Minggu

### 5. Rekap Bulanan / Tahunan
Tampilan tabel admin + tombol export excel

---

## Struktur Controller yang Disarankan
Pisahkan controller agar rapi:

- `AttendanceController`
  - rekap
  - edit/update
  - export
  - monthly/yearly recap

- `AttendanceCheckController`
  - form input absen
  - checkIn()
  - checkOut()

- `AttendanceSettingController`
  - edit/update setting

Boleh gunakan struktur lain yang lebih baik, tetapi tetap sederhana dan clean.

---

## Struktur Menu Sidebar
Tambahkan menu admin:

### MANAJEMEN ABSEN
Submenu:
- Input Absen
- Rekap Absensi
- Setting Absensi

Gunakan style AdminLTE yang konsisten.

---

## Output yang Diharapkan
Implementasi lengkap berupa:
1. desain database
2. command artisan
3. migration
4. model
5. controller
6. routes/web.php yang perlu ditambah
7. sidebar blade yang perlu ditambah
8. service/helper:
   - holiday API
   - hitung radius koordinat
9. command artisan untuk alpa otomatis
10. scheduler cron contoh
11. blade views lengkap
12. export excel
13. langkah integrasi setelah copy kode

---

## Catatan Penting
- fokus admin dulu
- jangan buat publik dulu
- harus terhubung ke modul pegawai/SOTK yang sudah ada
- gunakan coding style Laravel yang clean
- gunakan Blade + AdminLTE
- buat kode mudah dipahami dan mudah saya copy ke project
- jangan membuat sistem terlalu rumit, tetapi pondasinya harus kuat