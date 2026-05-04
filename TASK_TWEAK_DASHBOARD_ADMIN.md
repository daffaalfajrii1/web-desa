# Task: Tweak Dashboard Admin Web Desa

## Konteks Project
Project ini adalah aplikasi web desa berbasis Laravel dengan panel admin AdminLTE.

Sudah ada banyak modul admin seperti:
- Identitas Desa
- Profil Desa
- Berita
- Produk Hukum
- Informasi Publik
- PPID
- Lapak Desa
- Wisata
- Galeri Desa
- Layanan Mandiri
- Pengaduan
- Infografis
- Pegawai / SOTK
- Absensi
- Role & Permission
- Manajemen User

Saya ingin men-tweak **Dashboard Admin** agar tampilannya lebih keren, informatif, rapi, dan benar-benar berguna untuk operator/admin desa.

Fokus saat ini:
- admin dashboard saja
- jangan ubah frontend/public
- jangan ubah modul lain yang tidak terkait

---

## Tujuan Dashboard Baru
Saya ingin dashboard admin menjadi ringkasan utama sistem, sehingga saat admin login dia langsung melihat:

1. statistik cepat dari modul-modul penting
2. informasi identitas desa singkat
3. ringkasan aktivitas terbaru
4. shortcut ke menu penting
5. tampilan yang modern dan enak dilihat

Dashboard harus lebih bagus dari dashboard polos biasa.

---

## Struktur Tampilan yang Diinginkan

### A. Header Ringkasan Desa
Di bagian atas dashboard tampilkan ringkasan identitas desa:
- logo desa
- nama desa
- kecamatan
- kabupaten
- provinsi
- nama kepala desa
- kontak singkat (telepon / email / whatsapp jika ada)

Jika data identitas desa belum lengkap, tampilkan fallback yang aman.

---

### B. Welcome Card
Tampilkan card sambutan admin:
- nama user yang login
- role user
- tanggal hari ini
- jam saat ini
- pesan singkat seperti:
  "Selamat datang di panel admin website desa"

Kalau bisa tampil lebih modern dengan icon/ilustrasi ringan.

---

### C. Statistik Cepat / Summary Cards
Buat summary cards yang rapi dan berwarna untuk modul-modul penting.

Minimal tampilkan:
- jumlah berita
- jumlah produk hukum
- jumlah informasi publik
- jumlah pengumuman
- jumlah agenda
- jumlah layanan mandiri aktif
- jumlah pengaduan masuk
- jumlah pegawai aktif
- jumlah banner/carousel aktif

Kalau memungkinkan, tampilkan juga:
- jumlah absensi hadir hari ini
- jumlah pengaduan diproses
- jumlah pengajuan layanan mandiri terbaru

Summary cards harus:
- ringkas
- icon jelas
- warna berbeda
- klik ke modul terkait jika memungkinkan

---

### D. Ringkasan Layanan
Buat section khusus LAYANAN berisi:
- total pengaduan masuk
- total pengaduan diproses
- total pengaduan selesai
- total layanan mandiri aktif
- total pengajuan layanan terbaru jika struktur submission sudah ada
- shortcut ke:
  - Pengaduan
  - Layanan Mandiri

---

### E. Ringkasan Infografis / Data Desa
Buat section ringkas untuk data infografis:
- jumlah dusun
- jumlah data statistik penduduk
- jumlah data APBDes
- jumlah program bansos
- jumlah data stunting
- jumlah data IDM
- jumlah data SDGS

Tampilkan dengan card kecil atau info-box yang rapi.

---

### F. Ringkasan Absensi Hari Ini
Jika modul absensi sudah ada, tampilkan card atau mini-panel:
- hadir hari ini
- telat hari ini
- izin hari ini
- sakit hari ini
- alpa hari ini
- libur hari ini

Buat tampilannya menarik dan gampang dibaca.

---

### G. Aktivitas / Data Terbaru
Buat section “Data Terbaru” atau “Aktivitas Terbaru”, misalnya:
- 5 berita terbaru
- 5 pengaduan terbaru
- 5 agenda terbaru
- 5 layanan mandiri terbaru
- 5 pegawai terbaru

Kalau terlalu banyak, pilih 2 atau 3 widget yang paling penting.

Tiap widget harus punya tombol:
- Lihat semua

---

### H. Shortcut Menu
Buat panel shortcut cepat ke menu penting:
- Identitas Desa
- Berita
- Produk Hukum
- Pengaduan
- Layanan Mandiri
- Pegawai / SOTK
- Absensi
- Carousel Desa

Tampilan bisa berupa tombol/icon grid.

---

### I. Informasi Sistem
Buat section kecil di bawah dashboard:
- jumlah user admin
- jumlah role
- tema aktif
- status data identitas desa
- apakah logo desa sudah diupload
- apakah banner desa sudah ada

Ini tidak perlu terlalu besar, cukup informatif.

---

## Gaya UI yang Diinginkan
Gunakan Blade + AdminLTE.

Dashboard harus:
- modern
- clean
- tidak terlalu padat
- memakai card dengan jarak yang nyaman
- icon yang sesuai
- warna konsisten
- responsif
- lebih enak dilihat dari dashboard default biasa

Kalau perlu:
- gunakan card
- small-box
- info-box
- grid shortcut
- section title yang rapi

Jangan terlalu ramai, tapi tetap informatif.

---

## Logika Data
Tolong ambil data dashboard dari model yang sudah ada di project.
Gunakan query yang efisien.

Contoh:
- count untuk summary
- latest() untuk data terbaru
- filter aktif jika perlu
- absensi hari ini pakai tanggal hari ini
- pengaduan pakai status
- layanan mandiri pakai data aktif

Jika ada model yang belum pasti namanya, sesuaikan dengan struktur project yang ada.

---

## File yang Perlu Direvisi
Tolong cek dan revisi seperlunya:
1. route dashboard admin
2. controller dashboard admin atau closure dashboard
3. blade dashboard admin
4. styling kecil jika perlu
5. query data dashboard

Jika saat ini dashboard masih memakai route closure sederhana, refactor ke controller agar lebih rapi.

---

## Struktur yang Disarankan
Kalau belum ada controller dashboard, buat:
- `Admin/DashboardController.php`

Method:
- `index()`

Controller ini menyiapkan semua data dashboard lalu dikirim ke blade dashboard.

---

## Output yang Diharapkan
Tolong kerjakan dan tampilkan perubahan lengkap:
1. file yang dibuat / direvisi
2. controller dashboard
3. route dashboard yang direvisi
4. query data ringkasan
5. blade dashboard yang direvisi
6. penjelasan singkat perubahan

---

## Catatan Penting
- fokus hanya dashboard admin
- jangan ubah frontend/public
- jangan ubah modul lain yang tidak terkait
- gunakan style Laravel + Blade + AdminLTE yang konsisten
- buat kode yang mudah dibaca dan mudah dipelihara
- kalau ada data yang belum tersedia, tampilkan fallback yang aman
- dashboard harus terlihat keren tapi tetap ringan