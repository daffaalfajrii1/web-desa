# Task: Tweak Frontend Public - Layanan, Infografis, Profil, Wisata, Lapak

## Konteks
Frontend public website desa sudah mulai dibuat, tetapi masih terasa kurang matang.
Saya ingin dilakukan penyempurnaan besar agar tampilannya lebih lengkap, lebih cantik, lebih informatif, dan benar-benar terhubung dengan data dari admin.

Fokus utama revisi saat ini:
- Layanan
- Infografis
- Profil
- Sambutan kepala desa
- Wisata
- Lapak
- Carousel
- Ikon layanan desa

Targetnya adalah frontend public default theme yang lebih polished dan siap dipakai.

---

## Tujuan Utama
Frontend public harus:
1. menarik data real dari modul admin yang sudah ada
2. tampil clean, modern, dan elegan
3. mobile dan desktop sama-sama bagus
4. layanan dan infografis benar-benar lengkap
5. menampilkan profil desa dan kepala desa dengan lebih hidup
6. wisata dan lapak punya halaman detail/listing yang lebih kuat
7. carousel terasa hidup namun tetap smooth

---

## 1. Homepage / Beranda

### A. Sambutan Kepala Desa
Pada homepage, tambahkan section khusus untuk:
- sambutan kepala desa
- foto kepala desa
- nama kepala desa
- visi singkat
- misi singkat

Sumber data:
- identitas desa
- data pegawai/kepala desa jika sudah dihubungkan
- profil desa jika ada

Aturan:
- jika foto kepala desa tersedia, tampilkan
- jika tidak ada, gunakan fallback yang rapi
- jika visi/misi terlalu panjang, tampilkan versi singkat / potongan ringkas
- harus terlihat formal tapi tetap hangat

### B. Profil Desa Singkat
Tambahkan ringkasan profil desa di homepage yang diambil dari konten admin:
- sejarah / sambutan / deskripsi singkat
- link ke halaman profil lengkap

---

## 2. Menu Profil
Menu Profil di frontend harus:
- memakai dropdown dinamis dari **menu profil** yang diinput dari admin
- setiap item dropdown harus mengarah ke halaman profil terkait
- setiap halaman profil harus membaca konten real dari admin

Tampilan halaman profil:
- hero judul halaman
- breadcrumb
- konten rapi
- jika ada gambar, tampilkan elegan
- jika halaman kosong, tampilkan fallback elegan

---

## 3. Menu Layanan
Saat klik **Layanan**, buat landing layanan yang lebih baik.

### Kebutuhan:
- tampilkan semua layanan yang tersedia
- kelompokkan **sesuai tipe**
- jika ada layanan mandiri, tampilkan berdasarkan data admin
- masukkan **Pengaduan** sebagai salah satu jalur layanan publik
- kalau ada layanan informasi, tampilkan juga

### Tampilan:
- card layanan modern
- icon jelas
- nama layanan
- deskripsi singkat
- tombol masuk / ajukan / lihat detail

### Captcha:
Untuk:
- pengaduan
- layanan mandiri
harus ada captcha anti bot

Captcha:
- ringan
- clean
- mudah dipakai user
- validasi backend wajib

---

## 4. Pengaduan
Perbaiki halaman pengaduan public agar:
- lebih modern
- form lebih rapi
- ada captcha
- ada penjelasan singkat cara mengadu
- ada cek status pengaduan jika sudah ada sistemnya

---

## 5. Menu Infografis
Menu **Infografis** harus menjadi pusat seluruh data statistik desa.

### Halaman utama `/infografis`
Buat landing page infografis yang berisi card/menu ke subhalaman:
- Data Dusun
- Ringkasan Penduduk
- Statistik Penduduk
- APBDes
- Program Bansos
- Penerima Bansos
- Chart Bansos
- Cek Bansos (jika memungkinkan)
- Stunting
- IDM
- SDGs

Tampilan:
- icon lebih jelas
- card cantik
- hover halus
- deskripsi singkat tiap modul

---

## 6. Subhalaman Infografis

### A. Data Dusun
Tampilkan:
- daftar dusun
- ringkasan per dusun
- statistik jika ada
- chart jika memungkinkan

### B. Ringkasan Penduduk
Tampilkan ringkasan:
- total penduduk
- laki-laki
- perempuan
- kepala keluarga
- data per dusun jika tersedia

Gunakan chart yang modern dan user-friendly.

### C. Statistik Penduduk
Statistik penduduk harus lengkap seperti di admin tetapi tampil lebih cantik.

Kategori bisa mencakup:
- umur
- pendidikan
- agama
- pekerjaan
- wajib pilih
- perkawinan
- kategori lain yang tersedia di admin

Semua kategori yang tersedia harus punya chart.
Tampilan chart harus lebih polished dari admin.

### D. APBDes
APBDes harus tampil lengkap dan cantik.

Tampilkan:
- pendapatan
- belanja
- pembiayaan penerimaan
- pembiayaan pengeluaran
- surplus / defisit
- netto
- SILPA

Kebutuhan:
- chart utama
- tren per tahun jika ada lebih dari satu tahun
- card ringkasan angka
- tampil resmi dan mudah dipahami masyarakat

### E. Program Bansos
Tampilkan:
- daftar program bansos
- status aktif
- deskripsi singkat
- statistik penerima

### F. Penerima Bansos
Buat halaman yang menampilkan penerima bansos dengan tampilan lebih rapi.

Jika data memungkinkan:
- filter berdasarkan program
- jumlah penerima
- status penyaluran

### G. Chart Bansos
Buat visualisasi bansos:
- jumlah penerima per program
- distribusi status penyaluran
- grafik yang informatif

### H. Cek Bansos
Jika backend/data memungkinkan, buat halaman cek bansos sederhana:
- pencarian penerima bansos
- atau cek berdasarkan identitas tertentu
- tampilkan status jika ditemukan

Jika belum memungkinkan penuh, buat struktur halaman dan fallback elegan.

### I. Stunting
Tampilkan data stunting:
- ringkasan angka
- chart
- filter tahun / dusun jika tersedia

### J. IDM
Halaman IDM harus tampil premium.

Tampilkan:
- skor IDM
- status IDM
- target status
- skor IKS
- skor IKE
- skor IKL
- chart skor tahun ke tahun
- indikator IDM yang rapi

### K. SDGs
Halaman SDGs harus tampil sebagai statistik desa:
- ringkasan nilai
- chart per tujuan jika datanya ada
- tampilan clean

---

## 7. Wisata
Halaman wisata harus lebih bagus.

### Listing wisata:
- card modern
- foto besar
- judul
- lokasi singkat
- ringkasan

### Detail wisata:
Harus menampilkan semua field penting dari admin, seperti:
- nama wisata
- deskripsi
- fasilitas
- jam buka
- jam tutup
- hari buka
- embed peta / map
- galeri / foto jika ada
- informasi tambahan lain yang tersedia

Tampilan detail harus terasa seperti halaman destinasi, bukan hanya artikel biasa.

---

## 8. Lapak
Lapak harus dipisah dengan jelas dari wisata.

### Halaman lapak:
Buat section:
- Produk Terbaru
- Produk Unggulan
- Semua Produk

Jika data kategori tersedia:
- tampilkan filter kategori

Card produk:
- foto produk
- nama produk
- harga jika ada
- penjual
- kontak
- deskripsi singkat

Tampilan harus lebih komersial tapi tetap clean.

---

## 9. Carousel Homepage
Carousel/banner homepage harus:
- mengambil data real dari admin
- autoplay setiap **3 detik**
- transisi smooth
- overlay teks yang nyaman dibaca
- tetap responsif desktop dan mobile

Jika tidak ada banner:
- pakai fallback layout hero yang tetap cantik

---

## 10. Ikon Layanan Desa
Ikon-ikon quick menu dan layanan desa saat ini perlu diperjelas.

### Kebutuhan:
- pakai ikon yang lebih jelas
- konsisten style
- modern
- mudah dikenali masyarakat umum
- lebih tegas secara visual

Berlaku untuk:
- quick menu homepage
- layanan
- infografis
- lapak
- pengaduan
- absensi
- galeri
- produk hukum
- informasi publik

---

## 11. Integrasi Data yang Wajib
Pastikan frontend membaca data dari admin untuk:
- identitas desa
- logo desa
- kepala desa
- foto kepala desa jika tersedia
- visi / misi
- menu profil
- berita
- pengumuman
- agenda
- produk hukum
- informasi publik
- PPID
- lapak
- wisata
- galeri
- layanan mandiri
- pengaduan
- data dusun
- ringkasan penduduk
- statistik penduduk
- APBDes
- bansos
- stunting
- IDM
- indikator IDM
- SDGs

Jika data kosong:
- tampilkan fallback UI yang tetap elegan

---

## 12. UI / UX yang Diinginkan
Style:
- clean modern
- resmi namun tetap hangat
- mobile dan desktop sama-sama bagus
- infografis terasa premium
- layanan terasa jelas
- chart lebih halus dan menarik
- spacing lega
- tipografi lebih rapi
- card modern
- warna tetap selaras dengan default theme

---

## 13. Instruksi Teknis
1. Gunakan data real dari model/controller yang sudah ada
2. Jangan membuat konten statis palsu jika data sudah tersedia
3. Jika perlu, rapikan controller public
4. Jika perlu, tambah route public yang rapi
5. Gunakan partials/components agar struktur frontend rapi
6. Jangan merusak auth admin
7. Jangan merusak modul admin
8. Prioritaskan integrasi yang nyata

---

## 14. Prioritas Pengerjaan
Kerjakan berurutan:
1. homepage: sambutan kepala desa + foto + visi/misi singkat
2. profil dropdown dinamis dari admin
3. landing layanan + captcha + integrasi pengaduan
4. landing infografis
5. subhalaman infografis lengkap dengan chart
6. APBDes lengkap
7. bansos lengkap termasuk penerima / chart / cek bansos
8. wisata detail lebih lengkap
9. lapak lebih bagus dan dipisah section
10. carousel 3 detik
11. perjelas semua ikon quick menu / layanan

---

## 15. Output yang Diharapkan
Codex diharapkan:
- merevisi frontend public yang sudah ada
- menambah/mengubah blade, controller, route, komponen yang dibutuhkan
- mengintegrasikan data admin ke frontend
- membuat layanan dan infografis jauh lebih matang
- membuat homepage lebih hidup
- menghasilkan tampilan yang siap diuji langsung