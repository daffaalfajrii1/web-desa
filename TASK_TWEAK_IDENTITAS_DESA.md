# Task: Tweak Modul Identitas Desa

## Konteks
Project ini adalah aplikasi web desa berbasis Laravel dengan panel admin AdminLTE.

Sudah ada modul **Identitas Desa** di admin, tetapi saya ingin men-tweak modul ini agar:
- form lebih rapi
- lebih modern
- lebih terintegrasi dengan data pegawai
- siap dipakai untuk tampilan public juga

Fokus saat ini:
- admin panel dulu
- tetapi field dan struktur data harus siap dipakai di public

---

## Tujuan Revisi
Saya ingin modul Identitas Desa mendukung:

1. Kepala desa bisa dipilih dari data **pegawai / SOTK**
2. Tetap boleh ada input manual nama kepala desa jika dibutuhkan
3. Field **jabatan kepala desa** dihapus
4. Ada upload **logo desa**
5. Ada upload **carousel/banner** untuk tampilan public
6. Ada preview **map/lokasi desa**
7. Form admin lebih rapi dan enak dipakai

---

## Revisi yang Diinginkan

### A. Kepala Desa
Saat ini ada field:
- nama kepala desa
- jabatan kepala desa

Saya ingin diubah menjadi:

1. Tambahkan opsi memilih **Kepala Desa dari data Pegawai**
   - misalnya dropdown `employee_id` atau `village_head_employee_id`
   - ambil dari data pegawai aktif
   - jika dipilih, nama kepala desa otomatis mengikuti data pegawai

2. Tetap sediakan opsi manual, misalnya:
   - `village_head_name_manual`
   - dipakai jika belum ada data pegawai atau ingin manual

3. Hapus field:
   - `jabatan_kepala_desa`
   karena jabatan kepala desa tidak perlu diinput manual lagi

4. Desain terbaik:
   - ada dropdown "Pilih Kepala Desa dari Pegawai"
   - ada input manual "Nama Kepala Desa (opsional)"
   - jika dropdown terisi, maka sistem prioritaskan nama dari pegawai
   - jika dropdown kosong, gunakan nama manual

---

### B. Logo Desa
Tambahkan field upload:
- `logo_desa`

Kebutuhan:
1. upload gambar logo desa
2. simpan ke storage/public
3. tampilkan preview logo saat edit
4. siapkan agar logo ini nanti bisa dipakai:
   - di public frontend
   - di admin sidebar/header jika diperlukan

Validasi:
- image
- mime yang aman
- ukuran file dibatasi

---

### C. Carousel / Banner Public
Tambahkan fitur carousel/banner untuk public.

Saya ingin admin bisa mengelola banner/carousel desa.

Desain paling sederhana dan bagus:
- buat tabel terpisah misalnya `village_banners`
- field:
  - id
  - title nullable
  - subtitle nullable
  - image_path
  - is_active
  - sort_order
  - timestamps

Fitur admin:
1. tambah banner
2. edit banner
3. hapus banner
4. aktif/nonaktif banner
5. urutkan banner

Kebutuhan tampilan admin:
- index banner berupa card/list
- preview gambar
- tampilkan urutan dan status aktif

Tambahkan juga keterangan ukuran gambar yang disarankan, misalnya:
- Rekomendasi ukuran: 1600x600 px
- rasio landscape
- format JPG/PNG/WebP

Saya ingin teks bantuan ukuran banner tampil di form admin.

---

### D. Map / Lokasi Desa
Saya ingin field embed peta tetap ada, tapi ditweak lebih bagus.

Kebutuhan:
1. tetap ada field `embed_map`
2. ada juga field:
   - latitude
   - longitude
3. tampilkan preview map jika embed map diisi
4. jika hanya latitude dan longitude yang diisi, siapkan preview sederhana jika memungkinkan
5. form lokasi dibuat lebih rapi

Desain:
- section khusus "Lokasi Desa"
- input embed map
- input latitude
- input longitude
- preview map di bawahnya

---

### E. Form Identitas Desa Lebih Rapi
Saya ingin layout form admin dirapikan.

Tolong susun section menjadi beberapa card/section:

1. Informasi Dasar
   - nama desa
   - kecamatan
   - kabupaten
   - provinsi
   - alamat
   - kode pos

2. Kontak Desa
   - email
   - telepon
   - whatsapp

3. Pemerintahan Desa
   - pilih kepala desa dari pegawai
   - atau nama kepala desa manual
   - sambutan
   - visi
   - misi

4. Branding Desa
   - logo desa
   - tema aktif
   - preview logo

5. Lokasi Desa
   - embed map
   - latitude
   - longitude
   - preview map

Card/section harus lebih nyaman dipakai daripada form panjang satu blok.

---

### F. Kebutuhan Data dan Logika
Tolong revisi struktur data seperlunya.

Jika tabel setting/identitas desa belum punya field ini, tambahkan migration yang aman:
- `village_head_employee_id` nullable
- `village_head_name_manual` nullable
- `logo_path` nullable
- tetap pertahankan field penting lain yang sudah ada
- hapus atau stop pakai field jabatan kepala desa jika sudah tidak dibutuhkan

Untuk kepala desa:
- jika `village_head_employee_id` terisi, ambil nama dari relasi pegawai
- jika kosong, gunakan `village_head_name_manual`

---

### G. Modul Banner / Carousel
Selain tweak identitas desa, buat modul admin kecil untuk banner/carousel.

Buat:
- migration
- model
- controller
- routes
- sidebar/menu jika perlu
- views admin lengkap:
  - index
  - create
  - edit

Tampilan:
- card banner
- preview gambar
- status aktif/nonaktif
- urutan

---

### H. Output yang Diharapkan
Tolong kerjakan dan tampilkan perubahan lengkap:

1. migration yang perlu ditambah
2. model yang direvisi
3. controller yang direvisi
4. routes yang ditambah
5. blade form identitas desa yang direvisi
6. blade modul banner/carousel
7. logic kepala desa dari pegawai
8. logic upload logo
9. preview map
10. file mana saja yang diubah

---

## Catatan Penting
- fokus admin dulu
- tapi struktur datanya harus siap dipakai di public
- gunakan Laravel + Blade + AdminLTE
- form harus lebih rapi dan modern
- jangan ubah modul lain yang tidak terkait
- banner/carousel akan dipakai untuk frontend public
- logo desa juga akan dipakai di public dan admin