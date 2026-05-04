# Task: Frontend Public Website Desa - Default Theme First, Theme System Ready

## Konteks
Project ini adalah website desa berbasis Laravel.
Data konten sudah dikelola dari panel admin.

Sudah ada modul admin seperti:
- Identitas Desa
- Logo Desa
- Carousel / Banner Desa
- Profil Desa
- Berita
- Produk Hukum
- Informasi Publik
- Pengumuman
- Agenda
- PPID
- Lapak Desa
- Wisata
- Galeri Desa
- Infografis:
  - Penduduk
  - APBDes
  - Bansos
  - Stunting
  - IDM
  - SDGS
- Layanan Mandiri
- Pengaduan
- Pegawai / SOTK
- Absensi

Sekarang saya ingin membuat FRONTEND PUBLIC dengan fokus:
- default theme dulu
- clean modern
- desktop dan mobile sama-sama keren
- mobile punya bottom navigation seperti app
- desktop tetap terasa seperti website profesional
- semua modul admin siap ditampilkan di frontend
- ada login admin
- ada absensi pegawai publik sederhana
- ada loading/preloader
- ada captcha untuk layanan dan pengaduan

---

## Tujuan Utama
Membuat frontend public yang:
1. clean dan modern
2. desktop dan mobile sama-sama menarik
3. mobile-first tetapi desktop tetap elegan
4. memakai default theme untuk implementasi awal
5. memiliki homepage yang kuat
6. memiliki quick menu mobile
7. memiliki bottom navigation khusus mobile
8. memiliki widget kunjungan
9. memiliki widget aksesibilitas
10. memiliki view counter per modul
11. menyediakan menu Login Admin
12. menyediakan menu Absensi Pegawai publik sederhana
13. memiliki preloader/loading screen
14. memiliki captcha pada form penting agar tidak mudah diserang bot
15. sudah siap untuk multi-theme dari admin

---

## Controller Namespace
Untuk frontend/public, gunakan namespace controller:
- `App\Http\Controllers\Public\...`

Contoh:
- `Public\HomeController`
- `Public\PostController`
- `Public\LegalProductController`
- `Public\PublicInformationController`
- `Public\AnnouncementController`
- `Public\AgendaController`
- `Public\PpidController`
- `Public\LapakController`
- `Public\WisataController`
- `Public\GalleryController`
- `Public\InfografisController`
- `Public\SelfServiceController`
- `Public\ComplaintController`
- `Public\AttendanceController`

Gunakan namespace `Public` untuk semua halaman frontend/public.

---

## Theme System (PENTING)
Saya ingin sistem frontend mendukung **3 tema**, tetapi **yang diimplementasikan visual penuh sekarang hanya theme `default`**.

### Daftar tema yang harus disiapkan strukturnya:
1. `default`
2. `blue`
3. `earth`

### Aturan penting:
- Sekarang fokus implementasi penuh hanya pada **theme `default`**
- Theme `blue` dan `earth` belum perlu dibuat full secara visual, tetapi struktur theme system-nya harus sudah siap
- Theme dipilih dari admin melalui setting desa, misalnya field:
  - `theme_active`
- Frontend harus membaca nilai `theme_active` tersebut
- Jika `theme_active` berisi:
  - `default` → pakai theme default
  - `blue` → siapkan switch class/variables
  - `earth` → siapkan switch class/variables
- Jika tema belum tersedia penuh, fallback ke `default`

### Yang saya inginkan dari Codex:
1. Buat arsitektur theme system yang rapi
2. Siapkan class body/theme wrapper seperti:
   - `theme-default`
   - `theme-blue`
   - `theme-earth`
3. Atau gunakan CSS variables yang dibedakan per theme
4. Pastikan pemilihan theme nanti mudah dikontrol dari admin
5. Jangan implementasi semua tema sekarang
6. Implementasi visual penuh sekarang hanya `default`
7. Tetapi kode harus siap dikembangkan ke 3 tema tanpa refactor besar

---

## Karakter Theme Default
Theme yang dikerjakan sekarang:
- `default`

Karakter:
- hijau-putih netral
- clean
- modern
- profesional
- cocok untuk semua kalangan
- ramah mobile
- tidak terlalu ramai

---

## Perbedaan UI Desktop dan Mobile

### Desktop
Desktop harus tampil seperti website pemerintahan modern:
- navbar atas rapi
- hero/banner lebar
- section jelas
- card informatif
- grid lega
- footer informatif
- quick access tetap ada tapi lebih elegan
- jangan terasa seperti aplikasi mobile yang diperbesar

### Mobile
Mobile harus terasa lebih praktis seperti app ringan:
- hero lebih ringkas
- quick menu icon grid jelas
- bottom navigation tetap di bawah
- tombol penting mudah dijangkau jempol
- akses ke login admin, galeri, peta, menu, beranda jelas
- floating widget tetap rapi dan tidak saling tabrak

### Aturan Penting
- bottom navigation hanya tampil di mobile
- navbar desktop tetap tampil normal di desktop/tablet besar
- desktop dan mobile tetap memakai identitas visual yang sama
- coding layout harus responsive dan clean

---

## Layout Global Frontend
Buat layout frontend global reusable dengan Blade.

Struktur:
- topbar / navbar desktop
- mobile header
- hero/banner
- content sections
- footer
- floating buttons
- bottom navigation mobile
- preloader/loading overlay

Buat partial kalau perlu, misalnya:
- `resources/views/public/layouts/app.blade.php`
- `resources/views/public/partials/navbar.blade.php`
- `resources/views/public/partials/mobile-header.blade.php`
- `resources/views/public/partials/footer.blade.php`
- `resources/views/public/partials/mobile-bottom-nav.blade.php`
- `resources/views/public/partials/accessibility-widget.blade.php`
- `resources/views/public/partials/visit-widget.blade.php`
- `resources/views/public/partials/preloader.blade.php`

---

## Preloader / Loading Screen
Saya ingin ada loading / preloader saat halaman dimuat.

Kebutuhan:
1. tampil saat halaman pertama kali load
2. menampilkan:
   - logo desa
   - nama desa / website resmi desa
   - tanggal hari ini
3. boleh tampil jam berjalan jika desainnya bagus
4. animasi sederhana, halus, modern
5. warna mengikuti theme aktif, tetapi saat ini minimal cocok untuk theme default
6. tidak terlalu lama dan tidak mengganggu

---

## Homepage yang Diinginkan
Homepage harus menjadi pusat navigasi website desa.

### Desktop homepage
Tampilkan:
1. hero/banner utama dari carousel desa
2. identitas desa singkat
3. sambutan kepala desa
4. berita terbaru
5. produk hukum terbaru
6. informasi publik terbaru
7. lapak unggulan
8. wisata unggulan
9. galeri preview
10. infografis ringkas
11. CTA layanan mandiri
12. CTA pengaduan
13. CTA absensi pegawai
14. map/lokasi desa
15. footer

### Mobile homepage
Tampilkan:
1. hero lebih ringkas / visual kuat
2. logo desa
3. nama desa / website resmi desa
4. alamat singkat
5. quick menu grid
6. berita / info penting
7. CTA pengaduan
8. CTA layanan mandiri
9. CTA absensi pegawai
10. footer ringan
11. bottom navigation

---

## Quick Menu Mobile
Pada mobile, tampilkan quick menu grid seperti menu aplikasi ringan.

Prioritaskan menu:
- Peta Desa
- Produk Hukum
- Informasi Publik
- Lapak
- Arsip Berita
- Album Galeri
- Pengaduan
- Pembangunan / Infografis
- Status IDM / Infografis
- Login Admin
- Absensi Pegawai

Desain:
- card putih
- rounded
- shadow ringan
- icon berwarna
- label singkat
- mudah disentuh di HP
- spacing rapi

---

## Bottom Navigation Mobile
Buat bottom navigation khusus mobile.

Item default:
- Beranda
- Peta
- Menu
- Login
- Galeri

Opsional:
- jika desain masih rapi, bisa tambahkan akses cepat ke Absensi

Ketentuan:
- fixed bottom
- hanya tampil di mobile
- icon + label
- active state jelas
- tinggi nyaman disentuh
- tidak menutupi konten utama
- aman terhadap floating widget

---

## Login Admin
Sediakan akses mudah ke login admin dari frontend.

Kebutuhan:
1. menu "Login Admin"
2. route menuju halaman login admin yang sudah ada
3. tampil di:
   - navbar
   - quick menu mobile
   - bottom navigation jika cocok

Desain:
- jelas tapi tidak terlalu dominan
- tetap konsisten dengan theme aktif

---

## Absensi Pegawai Public Sederhana
Saya ingin ada halaman public sederhana untuk absensi pegawai.

Konsep:
- halaman absensi pegawai dari frontend/public
- user memilih pegawai dari dropdown
- user memasukkan PIN absensi
- tombol:
  - Absen Masuk
  - Absen Pulang

Kebutuhan:
1. dropdown pegawai mengambil data dari pegawai aktif yang punya `pin_absensi`
2. input PIN
3. tampilkan pesan hasil:
   - berhasil check in
   - berhasil check out
   - PIN salah
   - pegawai tidak valid
   - sudah check in
   - belum check in
   - sudah check out
   - di luar radius jika fitur radius aktif
4. tampilan harus simpel, bersih, mudah dipakai
5. gunakan controller public misalnya `Public\AttendanceController`

Catatan:
- jika backend absensi admin sudah ada, frontend public harus memanfaatkan logic absensi yang sama sejauh mungkin
- jangan duplikasi logic berlebihan kalau bisa reuse service/controller/helper

---

## Widget Kunjungan
Saya ingin ada widget kecil seperti:
- “Kunjungan Hari Ini”

Kebutuhan:
1. sistem kunjungan sederhana
2. tampilkan kunjungan hari ini
3. widget bisa tampil mengambang
4. nanti bisa dikembangkan

---

## View Counter per Modul
Saya ingin tiap modul public punya counter dilihat.

Minimal siapkan untuk:
- berita
- produk hukum
- informasi publik
- pengumuman
- agenda
- wisata
- lapak
- galeri jika perlu

Saat halaman detail dibuka:
- `views` bertambah

Jika field `views` belum ada, tambahkan migration alter table yang dibutuhkan.

---

## Aksesibilitas
Saya ingin ada tombol aksesibilitas floating.

Fitur minimal:
- perbesar teks
- perkecil teks
- tambah jarak teks
- kurangi jarak teks
- tambah tinggi baris
- kurangi tinggi baris
- balik warna
- mode abu-abu
- garis bawah teks
- perbesar kursor
- alat bantu baca
- matikan animasi
- reset

Tidak harus terlalu kompleks, tapi UI dan JS harus rapi dan bekerja.

---

## Floating Buttons
Siapkan floating buttons:
1. aksesibilitas
2. pengaduan
3. kunjungan

Posisi:
- aman di desktop
- aman di mobile
- tidak bertabrakan dengan bottom nav mobile
- tidak menutupi CTA penting

---

## Captcha untuk Pengaduan dan Layanan Mandiri
Saya ingin form penting memiliki captcha agar tidak diserang bot.

Form yang wajib memakai captcha:
1. form pengaduan publik
2. form layanan mandiri publik

Kebutuhan:
1. siapkan captcha yang ringan, bersih, dan mudah dipakai
2. boleh pakai captcha sederhana internal jika belum memakai layanan eksternal
3. atau siapkan struktur yang mudah diganti ke Google reCAPTCHA / Cloudflare Turnstile nanti
4. validasi captcha wajib terjadi di backend
5. tampilan captcha harus tetap clean dan tidak merusak UI mobile
6. tampilkan pesan error yang jelas jika captcha salah

---

## Modul yang Harus Siap Ditampilkan
Frontend default harus membaca data dari admin untuk:
- identitas desa
- logo desa
- banner aktif
- profil desa
- berita
- produk hukum
- informasi publik
- pengumuman
- agenda
- PPID
- lapak
- wisata
- galeri
- infografis
- layanan mandiri
- pengaduan
- pegawai / SOTK jika diperlukan
- absensi pegawai public sederhana

Tidak semua modul harus selesai penuh sekaligus, tetapi homepage dan layout harus siap menghubungkan semuanya.

---

## Branding yang Harus Dipakai
Frontend harus memakai data:
- logo desa
- nama desa
- kepala desa
- sambutan
- kontak
- embed map
- banner aktif
- tema aktif

Jika data belum ada, tampilkan fallback aman.

---

## Gaya UI / UX
Style yang diinginkan:
- clean
- modern
- desktop tetap elegan
- mobile terasa ringan seperti app
- tidak terlalu ramai
- cocok untuk semua kalangan
- card modern
- spacing lega
- button jelas
- nyaman di HP dan enak di monitor/laptop

Utamakan:
- responsive
- readability
- usability

---

## Halaman yang Diprioritaskan

### Tahap 1
- routes frontend/public
- controller dasar namespace `Public`
- layout frontend global
- theme system 3 tema siap pakai
- implementasi visual penuh theme `default`
- homepage responsive desktop + mobile
- quick menu mobile
- mobile bottom navigation
- widget kunjungan
- widget aksesibilitas
- integrasi logo/banner/identitas desa
- menu login admin
- halaman absensi pegawai public sederhana
- preloader/loading screen

### Tahap 2
- berita
- produk hukum
- informasi publik
- galeri
- lapak
- wisata

### Tahap 3
- infografis
- layanan mandiri
- pengaduan
- PPID
- halaman lain
- captcha pada form pengaduan dan layanan
- aktivasi tampilan tema lain jika diperlukan

---

## Struktur Route yang Diinginkan
Gunakan route frontend/public yang bersih.

Contoh pola:
- `/`
- `/profil-desa`
- `/berita`
- `/berita/{slug}`
- `/produk-hukum`
- `/informasi-publik`
- `/galeri`
- `/lapak`
- `/wisata`
- `/infografis/...`
- `/layanan-mandiri`
- `/pengaduan`
- `/login` atau route login admin yang sudah ada
- `/absensi-pegawai`

Sesuaikan dengan route existing project.

---

## Output yang Diharapkan
Tolong kerjakan bertahap dan tampilkan:
1. routes frontend/public
2. controller public dasar
3. layout frontend global
4. theme system 3 tema siap pakai
5. implementasi visual penuh theme default
6. homepage responsive desktop + mobile
7. quick menu mobile
8. mobile bottom navigation
9. widget kunjungan
10. widget aksesibilitas
11. preloader/loading screen
12. halaman absensi pegawai public sederhana
13. struktur captcha untuk pengaduan dan layanan mandiri
14. migration untuk visit counter / views jika perlu
15. penjelasan file yang dibuat dan diubah

---

## Catatan Penting
- fokus dulu ke frontend default theme
- theme system harus siap untuk 3 tema:
  - default
  - blue
  - earth
- visual penuh sekarang hanya default
- pemilihan theme nanti dari admin melalui `theme_active`
- jika theme selain default belum lengkap, fallback ke default
- controller frontend gunakan namespace `Public`
- desktop dan mobile harus sama-sama keren
- mobile harus punya bottom nav seperti app
- ada menu Login Admin
- ada halaman Absensi Pegawai public sederhana
- dropdown pegawai untuk absensi harus mudah dipakai
- semua modul admin harus siap dihubungkan ke frontend
- view counter harus disiapkan
- widget kunjungan harus ada
- aksesibilitas harus ada
- preloader/loading harus ada
- captcha untuk pengaduan dan layanan harus disiapkan
- jangan buat tema lain full dulu
- gunakan Laravel + Blade + style clean modern
- jangan ubah modul admin yang tidak terkait