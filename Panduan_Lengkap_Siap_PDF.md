# BUKU PANDUAN PENGGUNA (USER MANUAL GUIDE)
## SISTEM INFORMASI MANAJEMEN PELAYANAN (SIMPEL)
### Pemerintah Kelurahan Patokan, Kecamatan Kraksaan, Kabupaten Probolinggo

---

**INFORMASI DOKUMEN**
* **Judul Dokumen:** Panduan Operasional Sistem Administrator SIMPEL
* **Versi Dokumen:** 1.0 (Final Release)
* **Tanggal Terbit:** Oktober 2026
* **Penyusun:** Tim Pengembang SIMPEL Kelurahan Patokan
* **Klasifikasi Akses:** Terbatas (Administrator dan Staf Pelayanan)

---

## DAFTAR ISI
1. **Bagian 1: Persyaratan Sistem dan Prosedur Autentikasi**
2. **Bagian 2: Arsitektur Tata Letak dan Navigasi Antarmuka**
3. **Bagian 3: Panduan Operasional Modul Sistem**
   - Modul 1: Dashboard Ringkasan Eksekutif
   - Modul 2: Identitas dan Sambutan Pimpinan
   - Modul 3: Visi, Misi, dan Narasi Sejarah
   - Modul 4: Bagan Struktur Organisasi (SOTK)
   - Modul 5: Lembaga Kemasyarakatan
   - Modul 6: Kemitraan dan Tautan Eksternal
   - Modul 7: Statistik Wilayah dan Demografi Kependudukan
   - Modul 8: Maklumat Standar Pelayanan Publik
   - Modul 9: Master Layanan Publik dan Persyaratan SOP
   - Modul 10: Transparansi Realisasi APBD dan Keuangan
   - Modul 11: Repositori Dokumen Publik (PDF)
   - Modul 12: Manajemen Berita dan Artikel
   - Modul 13: Pengumuman Berjalan (Running Text Marquee)
   - Modul 14: Manajemen Agenda Kegiatan Kelurahan
   - Modul 15: Dokumentasi Multimedia (Galeri Foto dan Video)
   - Modul 16: Konfigurasi Spanduk Utama (Hero Banner)
   - Modul 17: Jam Operasional, Kontak, dan Integrasi Peta
   - Modul 18: Kaki Halaman (Footer), Media Sosial, dan Barcode QR
   - Modul 19: Manajemen Akun Operator dan Tingkat Otoritas
   - Modul 20: Audit Jejak Aktivitas Sistem (System Activity Log)
   - Modul 21: Manajemen Berkas dan Media Server (Media Library)
   - Modul 22: Pengelolaan Struktur Menu Navigasi (Navbar)
   - Modul 23: Konfigurasi Sistem, Batas Kuota, dan Mode Pemeliharaan
4. **Bagian 4: Mitigasi Kendala Teknis (Troubleshooting)**

---

# BAGIAN 1: PERSYARATAN SISTEM DAN PROSEDUR AUTENTIKASI

## 1.1 Persyaratan Teknis Minimum Lingkungan Pengguna
Untuk memastikan seluruh antarmuka dan fungsi Rich Text Editor berjalan optimal, pastikan perangkat kerja memenuhi spesifikasi berikut:

| Komponen Sistem | Spesifikasi Rekomendasi | Batas Toleransi Minimum |
|---|---|---|
| **Peramban Web** | Google Chrome 90+, Mozilla Firefox 90+, Microsoft Edge terbaru | Mendukung JavaScript ES6 dan CSS Grid |
| **Koneksi Jaringan** | Jalur internet stabil minimal 5 Mbps | 1 Mbps (stabil) |
| **Resolusi Layar** | 1920 x 1080 piksel (Full HD) | 1366 x 768 piksel |
| **Perangkat Keras** | PC Desktop / Laptop Kerja | Tablet / Smartphone (Mode Responsif) |

> **Perhatian:** Sistem SIMPEL dibangun menggunakan standar web modern. Penggunaan peramban versi lama yang tidak mendukung standar web mutakhir tidak disarankan.

---

## 1.2 Prosedur Masuk Sistem (Login)
Langkah-langkah autentikasi akun administrator ke dalam portal pengelolaan:
1. Jalankan peramban web dan akses alamat resmi sistem login pengelola.
2. Masukkan data autentikasi resmi pada kolom yang tersedia:
   - **Username / Email**: Alamat surel resmi terdaftar (contoh: `admin@gmail.com`).
   - **Password**: Kata sandi akun pengelola (perhatikan penggunaan huruf besar dan kecil).
3. Periksa tampilan **Kode Verifikasi Captcha**. Masukkan kombinasi teks acak yang tertera pada kotak verifikasi. Jika karakter captcha kurang terbaca, klik tombol muat ulang untuk memperbarui kode verifikasi.
4. Berikan tanda centang pada opsi **Ingat Saya** untuk menyimpan sesi autentikasi pada perangkat kerja pribadi.
5. Klik tombol **Masuk Ke System**. Sistem akan memvalidasi data dan mengarahkan pengguna ke halaman utama Dashboard Admin.

![Halaman Login SIMPEL](assets/img/01_halaman_login.png)
*Gambar 1.1: Tampilan Form Autentikasi Pengelola SIMPEL Kelurahan Patokan.*

---

## 1.3 Prosedur Pemulihan Kredensial (Lupa Password)
Sistem menyediakan mekanisme reset kata sandi mandiri melalui validasi berlapis tanpa harus membuka konfigurasi basis data:
1. Pada halaman login, klik tautan teks **Lupa Password?** di bagian bawah.
2. Jendela dialog pemulihan akun akan terbuka.
3. Masukkan data validasi akun:
   - **Email Resmi / Username Login**: Masukkan akun pengelola yang terdaftar.
   - **No. WhatsApp (Validasi 1)**: Nomor telepon aktif diawali angka 0 dengan panjang 11 hingga 13 digit.
   - **Kode Referral (Validasi 2)**: Kode rahasia pemulihan sepanjang 6 karakter (kombinasi 3 huruf kapital dan 3 angka).
   - **Password Baru**: Masukkan kata sandi baru yang memenuhi kriteria keamanan (minimal 8 karakter, mencakup kombinasi huruf kapital, huruf kecil, angka, dan karakter khusus).
   - **Konfirmasi Password Baru**: Masukkan ulang kata sandi baru secara identik.
4. Klik tombol **Reset Password Akun**. Kata sandi akan segera diperbarui dan dapat langsung digunakan.

![Modal Lupa Password](assets/img/02_modal_lupa_password.png)
*Gambar 1.2: Antarmuka Pemulihan Akun dengan Parameter Validasi Ganda.*

---

## 1.4 Prosedur Keluar Sistem (Logout)
Guna menjaga keamanan akun setelah operasional selesai:
1. Arahkan kursor pada panel informasi akun di sudut kiri bawah bilah navigasi.
2. Klik tombol keluar atau ikon navigasi keluar di samping identitas pengelola.
3. Sistem akan menghapus seluruh data sesi aktif dan mengembalikan tampilan ke halaman login awal.

---

# BAGIAN 2: ARSITEKTUR TATA LETAK DAN NAVIGASI ANTARMUKA

## 2.1 Struktur Ruang Kerja Panel Admin
Antarmuka tata kelola SIMPEL terbagi ke dalam 3 zona operasional kerja:

| Zona Antarmuka | Posisi Tampilan | Fungsi Utama |
|---|---|---|
| **Sidebar Menu** | Bilah vertikal sebelah kiri | Pusat navigasi sistem bertingkat untuk mengakses seluruh modul aplikasi. |
| **Top Header Bar** | Bilah horizontal bagian atas | Memuat penanda waktu server, judul modul aktif, dan tombol pintas ke situs publik. |
| **Workspace Area** | Ruang utama tengah layar | Menampilkan kartu data, tabel interaktif, dan formulir pengisian data operasional. |

![Tampilan Layout Antarmuka Utama](assets/img/03_layout_dashboard.png)
*Gambar 2.1: Tata Letak Ruang Kerja Panel Administrator Kelurahan Patokan.*

---

## 2.2 Pengelompokan Menu Sidebar
Menu operasional dikelompokkan ke dalam 6 bagian fungsional:
* **1. Dashboard Utama:** Memuat modul ringkasan eksekutif.
* **2. Profil dan Data Kelurahan:** Mengelola profil instansi, sambutan, visi-misi, SOTK, kelembagaan, tautan instansi, dan parameter statistik.
* **3. Layanan dan Transparansi:** Mengelola maklumat pelayanan, SOP layanan, laporan realisasi keuangan APBD, dan repositori dokumen publik.
* **4. Konten dan Publikasi:** Mengatur materi warta berita, teks pengumuman berjalan, agenda kegiatan, serta dokumentasi galeri.
* **5. Tampilan dan Kontak Web:** Mengonfigurasi spanduk hero utama, kontak dinas, jejaring sosial, dan kode QR layanan.
* **6. Pengaturan Sistem:** Mengatur akun operator, catatan jejak audit, pustaka media, struktur navbar, dan konfigurasi global sistem.

---

# BAGIAN 3: PANDUAN OPERASIONAL MODUL SISTEM

---

## MODUL 1: DASHBOARD RINGKASAN EKSEKUTIF
*(Akses Menu: Dashboard Utama -> Dashboard Admin)*

**Tujuan Fungsional:** Menyajikan ringkasan indikator data publikasi dan aktivitas operasional website secara terpusat.

**Langkah Operasional:**
* Pantau kartu ringkasan untuk mengetahui jumlah akumulasi Artikel dan Berita, Pengumuman Aktif, serta Album Galeri yang tersimpan di sistem.
* Periksa tabel Publikasi Berita Terbaru untuk melihat daftar rilis berita terkini.
* Gunakan tombol tautan pada masing-masing kartu untuk membuka menu pengelolaan terkait secara langsung.

![Halaman Dashboard Admin](assets/img/03_layout_dashboard.png)
*Gambar 3.1: Halaman Ringkasan Data Operasional dan Publikasi Terkini.*

---

## MODUL 2: IDENTITAS DAN SAMBUTAN PIMPINAN
*(Akses Menu: Profil dan Data Kelurahan -> Identitas dan Sambutan)*

**Tujuan Fungsional:** Mengatur nama resmi kelurahan dan narasi sambutan pimpinan yang ditampilkan di halaman beranda.

**Langkah Operasional:**
1. Masukkan **Nama Kelurahan** resmi pada kolom yang tersedia.
2. Perhatikan bagian informasi keterpaduan data; foto dan nama Kepala Kelurahan disinkronkan otomatis dari struktur pimpinan pada modul SOTK.
3. Pada bagian Sambutan Kepala Kelurahan, isi **Judul Sambutan** serta naskah sambutan pada editor teks yang disediakan.
4. Klik tombol **Simpan Identitas** untuk menyimpan perubahan data.

![Halaman Identitas dan Sambutan](assets/img/04_identitas_sambutan.png)
*Gambar 3.2: Pengaturan Identitas Instansi dan Naskah Sambutan Pimpinan.*

---

## MODUL 3: VISI, MISI, DAN NARASI SEJARAH
*(Akses Menu: Profil dan Data Kelurahan -> Visi, Misi dan Sejarah)*

**Tujuan Fungsional:** Menyediakan informasi dasar mengenai pedoman pembangunan, visi misi, serta catatan historis berdirinya kelurahan.

**Langkah Operasional:**
1. Masukkan pernyataan visi kelurahan pada kolom **Teks Visi Kelurahan**.
2. Susun butir prioritas misi pada kolom **Teks Misi Kelurahan** menggunakan fitur daftar penomoran pada editor.
3. Tuliskan uraian latar belakang pendirian wilayah pada bagian **Pengaturan Sejarah Kelurahan**.
4. Klik tombol **Simpan Perubahan** untuk memperbarui tampilan pada website publik.

![Halaman Visi Misi dan Sejarah](assets/img/05_visi_misi_sejarah.png)
*Gambar 3.3: Formulir Pengisian Visi, Butir Misi, dan Catatan Sejarah Wilayah.*

---

## MODUL 4: BAGAN STRUKTUR ORGANISASI (SOTK)
*(Akses Menu: Profil dan Data Kelurahan -> Struktur Organisasi)*

**Tujuan Fungsional:** Mengelola bagan hierarki aparatur kelurahan dan hubungan koordinasi struktural antarpegawai.

**Langkah Operasional:**
1. Buka tampilan bagan struktur, kemudian pilih tombol **Tambah Anggota**.
2. Pada formulir penambahan pegawai, masukkan:
   - **Nama Lengkap dan Gelar** serta **Jabatan Resmi**.
   - **Tugas Pokok dan Fungsi (Tupoksi)** serta **NIP**.
   - Tentukan **Atasan (Parent)** dari daftar hierarki untuk menghubungkan garis koordinasi bagan.
   - Unggah berkas **Foto Profil** resmi aparatur (maksimal 5 MB).
3. Klik tombol **Simpan Anggota**.

![Bagan Interaktif SOTK](assets/img/06_bagan_sotk.png)
*Gambar 3.4a: Antarmuka Bagan Struktur Organisasi dan Tata Kerja.*

![Formulir Tambah Aparatur SOTK](assets/img/07_form_tambah_sotk.png)
*Gambar 3.4b: Formulir Penambahan Aparatur dan Penetapan Garis Koordinasi.*

---

## MODUL 5: LEMBAGA KEMASYARAKATAN
*(Akses Menu: Profil dan Data Kelurahan -> Lembaga Kemasyarakatan)*

**Tujuan Fungsional:** Mempublikasikan profil organisasi kemasyarakatan yang bermitra dengan pemerintah kelurahan.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Lembaga** di atas tabel data.
2. Pada jendela pengisian, lengkapi:
   - **Nama Lembaga**, **Singkatan**, dan nama **Ketua/Pimpinan**.
   - **Deskripsi Singkat** mengenai peran kelembagaan.
   - Unggah berkas gambar **Logo Lembaga**.
3. Klik tombol **Simpan Lembaga**. Data yang disimpan akan langsung masuk ke tabel manajemen kelembagaan.

![Tabel Lembaga Kemasyarakatan](assets/img/08_daftar_lembaga.png)
*Gambar 3.5a: Tabel Manajemen Profil Lembaga Kemasyarakatan.*

![Modal Tambah Lembaga](assets/img/09_modal_tambah_lembaga.png)
*Gambar 3.5b: Jendela Formulir Pendaftaran Lembaga Kemasyarakatan Baru.*

---

## MODUL 6: KEMITRAAN DAN TAUTAN EKSTERNAL
*(Akses Menu: Profil dan Data Kelurahan -> Link Terkait)*

**Tujuan Fungsional:** Menyediakan tautan rujukan cepat ke website dinas vertikal, pemerintah daerah, dan portal kementerian terkait.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Link**.
2. Lengkapi formulir pendaftaran tautan:
   - **Nama Tautan / Link**: Tuliskan nama instansi atau layanan.
   - **Tautan / URL**: Masukkan alamat website lengkap diawali format protokol `https://`.
   - **Deskripsi Singkat**: Keterangan layanan instansi.
   - **Logo Link**: Unggah logo identitas instansi terkait.
3. Klik tombol **Simpan Link**.

![Tabel Link Kemitraan](assets/img/10_daftar_link_terkait.png)
*Gambar 3.6a: Katalog Tautan Terkait dan Kemitraan Eksternal.*

![Modal Input Link](assets/img/11_modal_tambah_link.png)
*Gambar 3.6b: Jendela Pendaftaran Alamat Tautan Eksternal Baru.*

---

## MODUL 7: STATISTIK WILAYAH DAN DEMOGRAFI KEPENDUDUKAN
*(Akses Menu: Profil dan Data Kelurahan -> Statistik dan Demografi)*

**Tujuan Fungsional:** Mengelola data agregat jumlah penduduk, rentang usia, tingkat pendidikan, pekerjaan, serta fasilitas sarana publik.

**Langkah Operasional:**
* **Kartu Statistik Beranda:** Aktifkan tombol sakelar (*toggle*) pada indikator utama agar tampil pada kartu statistik halaman depan.
* **Demografi Penduduk:** Masukkan kuantitas penduduk berdasarkan jenis kelamin laki-laki dan perempuan; kalkulasi total populasi diproses otomatis oleh sistem.
* **Kelompok Usia, Pendidikan, dan Profesi:** Perbarui angka riil kelompok masyarakat secara berkala.
* **Batas Wilayah dan Fasilitas:** Masukkan luas wilayah, jumlah RT/RW, serta inventaris sarana ibadah, kesehatan, dan pendidikan.
* Klik tombol **Simpan Perubahan** untuk memperbarui data statistik portal publik.

![Halaman Pengaturan Statistik](assets/img/12_statistik_demografi.png)
*Gambar 3.7: Halaman Pembaruan Data Kependudukan dan Sarana Prasarana Wilayah.*

---

## MODUL 8: MAKLUMAT STANDAR PELAYANAN PUBLIK
*(Akses Menu: Layanan dan Transparansi -> Maklumat Pelayanan)*

**Tujuan Fungsional:** Mengumumkan piagam komitmen kesanggupan pelayanan dari aparatur kelurahan kepada warga masyarakat.

**Langkah Operasional:**
1. Masukkan **Judul Kartu Maklumat** dan ringkasan pengantar pelayanan.
2. Tuliskan naskah janji pelayanan pada kolom **Kutipan Maklumat (Quote)**.
3. Unggah berkas pindai resmi pada bagian **Foto Maklumat Pelayanan**.
4. Tuliskan teks keterangan pelengkap pada kolom **Teks Maklumat Alternatif**.
5. Klik tombol **Simpan Maklumat**.

![Formulir Maklumat Pelayanan](assets/img/13_maklumat_pelayanan.png)
*Gambar 3.8: Panel Pengaturan Maklumat Komitmen Pelayanan Publik.*

---

## MODUL 9: MASTER LAYANAN PUBLIK DAN PERSYARATAN SOP
*(Akses Menu: Layanan dan Transparansi -> Layanan dan Standar SOP)*

**Tujuan Fungsional:** Menampilkan katalog administrasi kependudukan, berkas persyaratan surat pengantar, dan formulir permohonan.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Layanan Baru**.
2. Lengkapi parameter permohonan pada formulir dialog:
   - **Nama Layanan** dan **Kode Surat** (contoh: *Surat Keterangan Usaha* / `SKU`).
   - **Dokumen Persyaratan Wajib**: Rincian berkas yang wajib dipersiapkan warga pemohon.
   - Masukkan informasi jam buka layanan, durasi waktu proses, dan tarif administrasi pelayanan.
   - **Upload File PDF Formulir**: Lampirkan blangko formulir pengajuan jika disediakan.
   - Aktifkan opsi penayangan beranda serta status aktif layanan.
3. Klik tombol **Simpan Layanan Baru**.

![Katalog Master Layanan SOP](assets/img/14_master_layanan_sop.png)
*Gambar 3.9a: Master Data Prosedur Pelayanan dan Persyaratan Administrasi.*

![Modal Tambah Layanan Baru](assets/img/15_modal_tambah_layanan.png)
*Gambar 3.9b: Formulir Penetapan Persyaratan Dokumen dan Blangko Permohonan.*

---

## MODUL 10: TRANSPARANSI REALISASI APBD DAN KEUANGAN
*(Akses Menu: Layanan dan Transparansi -> Transparansi dan APBD)*

**Tujuan Fungsional:** Mempublikasikan rincian anggaran fiskal serta laporan pertanggungjawaban penyerapan anggaran tahunan secara transparan.

**Langkah Operasional:**
1. Tambahkan periode tahun baru dengan menekan **+ Tambah Tahun APBD**, atau pilih tombol **Kelola Data Anggaran** pada kartu tahun anggaran yang sedang berjalan.
2. Sistem menyajikan tiga tabel alokasi anggaran:
   - **1. Pendapatan Desa**: Mengelola pos transfer penerimaan daerah.
   - **2. Belanja Desa**: Mengelola biaya program kegiatan pembangunan dan pelayanan.
   - **3. Pembiayaan Desa**: Mengelola penerimaan pembiayaan.
3. Gunakan tombol **+ Tambah** pada masing-masing tabel untuk memasukkan nama item, pagu Anggaran (Rp), serta capaian Realisasi (Rp).

![Riwayat Tahun Anggaran](assets/img/16_transparansi_tahun.png)
*Gambar 3.10a: Daftar Riwayat Periode Tahun Anggaran Transparansi Keuangan.*

![Rincian Alokasi Realisasi APBD](assets/img/17_transparansi_rincian.png)
*Gambar 3.10b: Tabel Pengisian Pos Pendapatan, Belanja, dan Pembiayaan.*

---

## MODUL 11: REPOSITORI DOKUMEN PUBLIK (PDF)
*(Akses Menu: Layanan dan Transparansi -> Dokumen Publik)*

**Tujuan Fungsional:** Menyediakan media unduhan resmi dokumen keputusan, surat edaran kelurahan, dan produk regulasi bagi masyarakat.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Dokumen** di sudut kanan atas.
2. Masukkan judul dan penjelasan berkas pada kotak keterangan persyaratan.
3. Pada bagian Daftar File PDF, tetapkan periode **Bulan** dan **Tahun**, lalu tentukan berkas PDF melalui tombol **Choose File**. Gunakan tombol **+ Tambah File** jika berkas memiliki lampiran tambahan.
4. Berikan tanda centang pada opsi **Tampilkan di halaman publik**, kemudian klik **Simpan Dokumen**.

![Daftar Dokumen Publik](assets/img/18_daftar_dokumen_publik.png)
*Gambar 3.11a: Tabel Manajemen Repositori Berkas Dokumen Publik.*

![Modal Upload Dokumen PDF](assets/img/19_modal_tambah_dokumen.png)
*Gambar 3.11b: Formulir Pengunggahan Berkas Dokumen dan Penetapan Periode.*

---

## MODUL 12: MANAJEMEN BERITA DAN ARTIKEL
*(Akses Menu: Konten dan Publikasi -> Berita dan Artikel)*

**Tujuan Fungsional:** Mengelola penerbitan siaran pers, warta pembangunan kelurahan, serta kegiatan kemasyarakatan.

**Langkah Operasional:**
1. Klik tombol **+ Tulis Berita Baru** pada bagian atas tabel.
2. Masukkan **Judul Berita / Artikel**, tentukan **Kategori Berita**, dan atur **Foto Sampul (Thumbnail)** melalui unggahan berkas atau tautan URL.
3. Tuliskan ringkasan isi berita pada kolom **Kutipan Ringkas (Excerpt)**.
4. Tuliskan teks naskah lengkap berita menggunakan editor teks yang disediakan.
5. Klik tombol **Publikasikan Berita** untuk menayangkan warta ke portal publik.

![Daftar Publikasi Berita](assets/img/20_daftar_berita.png)
*Gambar 3.12a: Tabel Manajemen Publikasi Berita dan Artikel Kelurahan.*

![Modal Penulisan Artikel](assets/img/21_modal_tulis_berita.png)
*Gambar 3.12b: Formulir Penulisan Berita dan Penetapan Foto Sampul.*

---

## MODUL 13: PENGUMUMAN BERJALAN (RUNNING TEXT MARQUEE)
*(Akses Menu: Konten dan Publikasi -> Pengumuman dan Marquee)*

**Tujuan Fungsional:** Menayangkan teks berjalan (*running text*) di bilah atas situs untuk menyampaikan informasi penting atau imbauan mendesak.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Pengumuman / Marquee**.
2. Lengkapi formulir pengumuman:
   - **Judul Pengumuman** dan **Kategori Pengumuman**.
   - **Tipe Warning Badge**: Pilih skema tampilan (*Info Layanan*, *Peringatan*, atau *Mendesak*).
   - **Isi Teks Marquee / Running Text**: Masukkan kalimat ringkas yang akan bergulir di bilah atas beranda.
   - Tentukan status tayang dan tandai opsi prioritas tinggi jika pengumuman bersifat darurat.
3. Klik tombol **Simpan Pengumuman**.

![Tabel Pengumuman Marquee](assets/img/22_daftar_pengumuman.png)
*Gambar 3.13a: Tabel Manajemen Pengumuman dan Teks Berjalan.*

![Modal Tambah Pengumuman](assets/img/23_modal_tambah_pengumuman.png)
*Gambar 3.13b: Formulir Penambahan Teks Pengumuman dan Penetapan Status Tayang.*

---

## MODUL 14: MANAJEMEN AGENDA KEGIATAN KELURAHAN
*(Akses Menu: Konten dan Publikasi -> Agenda Kegiatan)*

**Tujuan Fungsional:** Mengelola publikasi jadwal pertemuan dinas, musyawarah rencana pembangunan, dan agenda kegiatan warga.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Agenda Baru**.
2. Masukkan rincian kegiatan:
   - **Judul Kegiatan** dan **Lokasi** pertemuan dinas.
   - Tentukan **Tgl Mulai** dan **Jam Mulai**, serta batas waktu penutupan pada **Tgl Selesai** dan **Jam Selesai** bila kegiatan berlangsung beberapa hari.
   - Tuliskan rincian kegiatan pada kolom **Deskripsi Singkat**.
3. Beri tanda centang pada opsi **Langsung Tayangkan** dan klik tombol **Simpan Agenda**. Agenda yang tanggal operasionalnya telah selesai akan dipindahkan ke tab Arsip Agenda secara otomatis.

![Daftar Agenda Kegiatan](assets/img/24_daftar_agenda.png)
*Gambar 3.14a: Tabel Manajemen Kalender Agenda Kegiatan Kelurahan.*

![Modal Entri Jadwal Baru](assets/img/25_modal_tambah_agenda.png)
*Gambar 3.14b: Formulir Penjadwalan Waktu dan Lokasi Agenda Kegiatan.*

---

## MODUL 15: DOKUMENTASI MULTIMEDIA (GALERI FOTO DAN VIDEO)
*(Akses Menu: Konten dan Publikasi -> Galeri Foto dan Video)*

**Tujuan Fungsional:** Mendokumentasikan kegiatan pelayanan dan kemasyarakatan dalam bentuk arsip visual foto maupun tautan video YouTube.

**Langkah Operasional:**
1. Klik tombol **Unggah Foto Kegiatan Baru**.
2. Tentukan format media pada jendela unggahan:
   - **Foto Kegiatan**: Mendukung pengunggahan gambar langsung hingga 10 berkas secara bersamaan dengan kapasitas maksimal 5 MB per foto.
   - **Video YouTube**: Masukkan tautan URL video kegiatan.
3. Masukkan **Judul / Nama Kegiatan**, **Kategori Kegiatan**, serta keterangan singkat pada kolom **Keterangan Foto (Caption)**.
4. Berikan tanda centang pada opsi tayang di halaman utama bila dokumentasi ingin disorot pada beranda, lalu klik **Unggah Foto**.

![Katalog Grid Galeri Kegiatan](assets/img/26_daftar_galeri.png)
*Gambar 3.15a: Tampilan Kisi Dokumentasi Galeri Foto dan Video.*

![Modal Unggah Dokumentasi](assets/img/27_modal_unggah_galeri.png)
*Gambar 3.15b: Formulir Pengunggahan Dokumentasi Foto dan Tautan Video.*

---

## MODUL 16: KONFIGURASI SPANDUK UTAMA (HERO BANNER)
*(Akses Menu: Tampilan dan Kontak Web -> Hero Banner dan Slider)*

**Tujuan Fungsional:** Menentukan spanduk foto utama (*hero image*) pada bagian teratas website publik sebagai identitas visual instansi.

**Langkah Operasional:**
1. Klik tombol **Choose File** untuk memilih gambar spanduk beresolusi tinggi (rasio aspek 16:9, resolusi rekomendasi minimal 1920 x 1080 piksel).
2. Tinjau tampilan pada kotak pratinjau gambar aktif di bagian bawah.
3. Klik tombol **Simpan Perubahan** untuk memperbarui tampilan. Kolom pemilihan berkas dapat dikosongkan apabila tidak ingin memperbarui spanduk yang ada.

![Konfigurasi Hero Banner](assets/img/28_hero_banner.png)
*Gambar 3.16: Halaman Pengaturan Spanduk Citra Utama Beranda.*

---

## MODUL 17: JAM OPERASIONAL, KONTAK, DAN INTEGRASI PETA
*(Akses Menu: Tampilan dan Kontak Web -> Kontak dan Alamat Lokasi)*

**Tujuan Fungsional:** Menampilkan informasi jadwal kerja aparatur, saluran komunikasi, serta peta lokasi kantor kelurahan.

**Langkah Operasional:**
* **Jam Kerja:** Tentukan jam kerja operasional untuk hari Senin sampai Kamis serta hari Jumat.
* **Saluran Komunikasi:** Masukkan **No. Telepon Kantor**, nomor **WhatsApp Darurat**, dan alamat **Email Resmi** instansi.
* **Layanan WhatsApp:** Masukkan narasi sambutan pada editor **Teks Halaman Layanan WhatsApp**.
* **Alamat dan Peta:** Tuliskan alamat kantor kelurahan, lalu masukkan alamat URL atribut `src` dari sematan peta Google Maps pada kolom **Link Embed Google Maps**.
* Klik tombol **Simpan Kontak dan Lokasi**.

![Formulir Kontak dan Peta](assets/img/29_kontak_alamat.png)
*Gambar 3.17: Pengaturan Jadwal Jam Kerja, Data Kontak, dan Sematan Peta Lokasi.*

---

## MODUL 18: KAKI HALAMAN (FOOTER), MEDIA SOSIAL, DAN BARCODE QR
*(Akses Menu: Tampilan dan Kontak Web -> Footer dan Media Sosial)*

**Tujuan Fungsional:** Mengatur identitas penutup situs pada area kaki halaman, kanal media sosial, dan kode QR layanan mandiri.

**Langkah Operasional:**
1. Masukkan profil ringkas instansi pada bagian editor teks **Deskripsi Singkat**.
2. Tuliskan tautan akun resmi kelurahan pada kolom Instagram, YouTube, TikTok, dan tautan layanan WhatsApp. Kolom tautan dapat dikosongkan jika jenis media sosial tersebut belum tersedia.
3. Pada bagian **QR Code Pelayanan**, unggah gambar kode QR (disarankan berformat gambar bujur sangkar atau persegi).
4. Klik tombol **Simpan Perubahan** di bagian bawah.

![Konfigurasi Footer dan Sosmed](assets/img/30_footer_sosmed.png)
*Gambar 3.18: Panel Pengaturan Kaki Halaman, Media Sosial, dan Kode QR Pelayanan.*

---

## MODUL 19: MANAJEMEN AKUN OPERATOR DAN TINGKAT OTORITAS
*(Akses Menu: Pengaturan Sistem -> Operator dan Hak Akses)*

**Tujuan Fungsional:** Mengatur pendaftaran akun login petugas serta pembagian hak akses antara Administrator dan Anggota Staf.

**Langkah Operasional:**
1. Klik tombol **+ Tambah Pengguna Baru** di atas tabel akun.
2. Lengkapi isian data akun:
   - **Username Login** dan penentuan **Role Hak Akses** (*Administrator* atau *Anggota Staf*).
   - **Email Resmi Administrator** (wajib menggunakan alamat surel dengan domain `@gmail.com`).
   - **No. WhatsApp (Validasi 1)** dan **Kode Referral (Validasi 2)** untuk mekanisme verifikasi pemulihan sandi mandiri.
   - **Password Keamanan**: Tentukan kata sandi awal yang memenuhi indikator standar keamanan sistem.
3. Klik tombol **Simpan Data User**.

![Tabel Manajemen Pengguna](assets/img/31_daftar_operator.png)
*Gambar 3.19a: Tabel Pengelolaan Akun Pengguna dan Tingkatan Hak Akses.*

![Modal Tambah Pengguna Baru](assets/img/32_modal_tambah_operator.png)
*Gambar 3.19b: Formulir Penambahan Akun Pengguna dan Parameter Validasi.*

---

## MODUL 20: AUDIT JEJAK AKTIVITAS SISTEM (SYSTEM ACTIVITY LOG)
*(Akses Menu: Pengaturan Sistem -> Log Aktivitas Sistem)*

**Tujuan Fungsional:** Mencatat seluruh tindakan perubahan data yang dilakukan oleh pengelola untuk menjaga akuntabilitas dan audit sistem.

**Fitur Audit Sistem:**
* **Kartu Pemantauan:** Menampilkan statistik rekapitulasi Total Log, Log Hari Ini, Pengubahan Data, dan Riwayat Login.
* **Filter Log:** Memilah riwayat berdasarkan kategori tindakan menggunakan tombol filter **LOGIN**, **CREATE**, **UPDATE**, atau **DELETE**.
* **Data Log:** Menyajikan informasi waktu transaksi data, nama dan ID pengelola, label tindakan, keterangan aktivitas, serta alamat IP dan tipe peramban yang digunakan.
* **Pembersihan Log:** Gunakan tombol **Bersihkan Riwayat Log** jika ingin merapikan catatan riwayat lama.

![Tabel Audit Aktivitas Sistem](assets/img/33_log_aktivitas.png)
*Gambar 3.20: Halaman Catatan Jejak Audit Transaksi Operasional Sistem.*

---

## MODUL 21: MANAJEMEN BERKAS DAN MEDIA SERVER (MEDIA LIBRARY)
*(Akses Menu: Pengaturan Sistem -> File Manager dan Media)*

**Tujuan Fungsional:** Memantau seluruh berkas digital yang tersimpan pada direktori server kelurahan serta membersihkan file sampah (*orphan files*) guna menjaga kapasitas memori server.

**Langkah Operasional:**
* Periksa nama berkas, kapasitas ukuran berkas (KB/MB), serta alamat penyimpanan folder server pada setiap kartu media.
* Klik tombol **Salin URL** untuk menyalin tautan berkas langsung ke papan klip komputer.
* **Fitur Proteksi Aset Sistem:** File yang terikat pada data penting (seperti foto Berita, Dokumen PDF, Galeri, atau Logo) akan dilabeli dengan tanda **Aset Sistem** dan dinonaktifkan tombol hapusnya. Hal ini untuk mencegah file terhapus secara tidak sengaja yang dapat merusak tampilan website.
* **Penghapusan File Sampah:** Tombol Hapus (ikon tempat sampah) hanya tersedia untuk file-file bebas (biasanya di folder `uploads`) yang merupakan sisa gambar dari *Text Editor* atau file gagal tersimpan. Gunakan tombol ini untuk membersihkan server.

> **Catatan Keamanan:** Menu ini didesain murni sebagai *File Explorer* (Alat Pantau). Fitur *upload* manual sengaja ditiadakan untuk mencegah penumpukan file liar. Untuk mengunggah foto baru, lakukan secara langsung melalui menu fitur terkait (Berita, Galeri, Dokumen).

![Manajemen Media Server](assets/img/34_file_manager.png)
*Gambar 3.21: Halaman Repositori Berkas Media dan Pemantauan Kapasitas Server.*

---

## MODUL 22: PENGELOLAAN STRUKTUR MENU NAVIGASI (NAVBAR)
*(Akses Menu: Pengaturan Sistem -> Kelola Navigasi Menu)*

**Tujuan Fungsional:** Mengatur tata letak urutan menu, sub-menu bertingkat, serta penambahan halaman informasi baru pada navigasi portal publik.

**Langkah Operasional:**
1. Tinjau bagan struktur menu utama: Beranda, Profil, Layanan, Dokumen, Informasi, dan Hubungi.
2. Sub-menu untuk kelompok Layanan dan Dokumen disinkronkan secara otomatis ketika data modul layanan atau dokumen diperbarui.
3. Untuk menambahkan tautan atau halaman baru pada kelompok profil, klik tombol **+ Sub-Menu**.
4. Lengkapi formulir halaman baru:
   - **Label Tautan**: Nama menu yang tampil di bilah navigasi.
   - **Teks Header / Judul Halaman Utama**: Judul utama pada halaman informasi.
   - **Deskripsi Lengkap Dibawah Header**: Kalimat pengantar di bawah judul halaman.
   - **Foto Banner**: Gambar spanduk latar belakang halaman informasi.
   - **Isi Teks Utama Lengkap**: Masukkan uraian naskah pada editor teks.
5. Klik tombol **Simpan Menu Baru**.

![Struktur Hierarki Menu Navbar](assets/img/35_navigasi_menu.png)
*Gambar 3.22a: Pengaturan Struktur Hierarki Bilah Navigasi Website Publik.*

![Modal Tambah Halaman Kustom](assets/img/36_modal_tambah_menu.png)
*Gambar 3.22b: Formulir Penambahan Halaman Informasi dan Tautan Navigasi Baru.*

---

## MODUL 23: KONFIGURASI SISTEM, BATAS KUOTA, DAN MODE PEMELIHARAAN
*(Akses Menu: Pengaturan Sistem -> Konfigurasi Sistem dan SEO)*

**Tujuan Fungsional:** Mengatur konfigurasi global aplikasi, batasan ukuran berkas unggahan, serta sakelar penonaktifan sementara sistem untuk pemeliharaan.

**Langkah Operasional:**
* **Nama Aplikasi dan Subtitle**: Masukkan nama representasi sistem yang muncul pada bilah judul peramban web.
* **Logo Aplikasi**: Unggah berkas gambar lambang resmi instansi (format rasio 1:1, ukuran minimal 512 x 512 piksel).
* **Background Login**: Unggah gambar latar pada sisi kiri form login (disarankan rasio aspek 8:9, resolusi minimal 960 x 1080 piksel).
* **Parameter Sistem**: Tentukan batas maksimal unggah berkas foto serta dokumen PDF dalam satuan Megabyte (MB).
* **Mode Pemeliharaan (Maintenance)**: Berikan tanda centang hanya saat server sedang menjalani perbaikan teknis.
* Klik tombol **Simpan Semua Pengaturan** untuk menerapkan konfigurasi ke seluruh sistem.

![Konfigurasi Parameter Sistem](assets/img/37_konfigurasi_sistem.png)
*Gambar 3.23: Halaman Konfigurasi Identitas Portal, Batas Unggahan, dan Status Pemeliharaan.*

---

# BAGIAN 4: MITIGASI KENDALA TEKNIS (TROUBLESHOOTING)

Petunjuk penanganan mandiri terhadap permasalahan teknis operasional yang umum terjadi:

| No | Gejala Permasalahan | Analisis Kemungkinan Penyebab | Langkah Solusi Mandiri |
|---|---|---|---|
| 1 | Autentikasi Gagal: Muncul keterangan kata sandi keliru | Pengetikan huruf kapital yang tidak tepat atau kode captcha salah ketik | Periksa status tombol Caps Lock pada papan ketik. Perbarui karakter captcha menggunakan tombol muat ulang. Jika akses tetap tertolak, lakukan prosedur pemulihan kata sandi. |
| 2 | Berkas Foto Ditolak Sistem Saat Disimpan | Ukuran berkas gambar melampaui batasan Megabyte yang ditetapkan pada parameter sistem | Kompres ukuran berkas gambar terlebih dahulu menggunakan alat bantu kompresi daring sebelum diunggah kembali ke sistem. |
| 3 | Dokumen Publik Gagal Diunggah | Berkas yang diunggah tidak berekstensi PDF atau melampaui batasan kuota berkas dokumen | Pastikan berkas dokumen berformat resmi .pdf dan kapasitas filenya berada di bawah batas maksimal upload PDF. |
| 4 | Pembaruan Data Tersimpan Namun Tidak Muncul di Beranda | Peramban web menampilkan berkas arsip singgahan lokal (cache) | Lakukan pembersihan cache peramban dengan menekan kombinasi tombol Ctrl + F5 pada papan ketik untuk memuat ulang data terbaru. |
| 5 | Portal Publik Menampilkan Halaman Pemeliharaan Sistem | Pengaturan Mode Pemeliharaan (Maintenance) berada dalam status tercentang atau aktif | Buka modul Konfigurasi Sistem dan SEO, hilangkan tanda centang pada opsi Mode Pemeliharaan, lalu simpan kembali pengaturan. |
| 6 | Pengelola Tidak Dapat Melakukan Reset Kata Sandi | Lupa nomor WhatsApp validasi atau kehilangan kode referral akun | Hubungi Administrator Utama untuk memeriksa nomor telepon dan kode referral akun pengelola melalui modul Operator dan Hak Akses. |

---

*Buku panduan operasional ini disusun secara formal sebagai pedoman standar pemanfaatan dan kesinambungan tata kelola pelayanan publik digital di lingkungan Pemerintah Kelurahan Patokan.*

**Pemerintah Kelurahan Patokan, Kecamatan Kraksaan, Kabupaten Probolinggo (2026).**