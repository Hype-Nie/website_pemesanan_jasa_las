# Website Pemesanan Jasa Las

Selamat datang di repositori **Website Pemesanan Jasa Las**! Proyek ini adalah sebuah aplikasi berbasis web yang dirancang untuk memudahkan pelanggan dalam memesan layanan bengkel las secara online, sekaligus membantu pemilik bengkel mengelola pesanan dengan lebih efisien.

## 📖 Tentang Proyek Ini

Aplikasi ini dibuat untuk menjembatani antara bengkel las dan pelanggan. Bayangkan ini seperti aplikasi pesan antar makanan, tetapi khusus untuk memesan jasa las (seperti pembuatan pagar, kanopi, teralis, dan lain-lain).

**Apa yang bisa dilakukan di website ini?**

- **Pelanggan** dapat melihat daftar layanan yang ditawarkan, melihat contoh hasil kerja, dan langsung melakukan pemesanan (booking) jasa secara online tanpa harus datang ke bengkel terlebih dahulu.
- **Pemilik Bengkel (Admin)** dapat menerima pesanan, mengelola jadwal pengerjaan, dan melihat riwayat transaksi pelanggan dengan mudah.

## 💻 Informasi Teknis

Aplikasi ini dibangun menggunakan framework **Laravel**, sebuah framework PHP yang kuat dan modern.

### Fitur Utama

- Sistem Autentikasi dan Manajemen Pengguna (Admin & Pelanggan)
- Katalog Layanan (Pembuatan Kanopi, Teralis, Pagar, dll)
- Sistem Pemesanan (Booking System)
- Manajemen Status Pesanan
- Desain Responsif (Nyaman diakses lewat HP maupun Laptop)

### Persyaratan Sistem (System Requirements)

Pastikan perangkat Anda telah memenuhi prasyarat perangkat lunak berikut sebelum menjalankan aplikasi:

- **PHP**: versi 8.1 atau lebih baru
- **Composer**: untuk manajemen dependensi PHP
- **Database**: MySQL atau MariaDB
- **Node.js & NPM**: untuk kompilasi aset antarmuka pengguna (Vite)

### Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah di bawah ini untuk menginstal dan menjalankan proyek ini di komputer lokal Anda:

1. **Kloning Repositori**
   Unduh kode sumber proyek ini ke komputer Anda.

    ```bash
    git clone <url-repositori-anda>
    cd website_pemesanan_jasa_las
    ```

2. **Instal Dependensi PHP**
   Gunakan Composer untuk menginstal semua library yang dibutuhkan oleh Laravel.

    ```bash
    composer install
    ```

3. **Instal Dependensi Frontend**
   Gunakan NPM untuk menginstal library Javascript dan CSS.

    ```bash
    npm install
    ```

4. **Konfigurasi Environment**
   Salin file konfigurasi contoh dan sesuaikan dengan pengaturan database Anda.

    ```bash
    cp .env.example .env
    ```

    _Buka file `.env` dengan teks editor, lalu atur bagian `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai dengan konfigurasi server MySQL lokal Anda._

5. **Buat Kunci Aplikasi**
   Buat kunci keamanan unik (Application Key) untuk aplikasi ini.

    ```bash
    php artisan key:generate
    ```

6. **Migrasi Database**
   Jalankan perintah ini untuk membuat tabel-tabel yang diperlukan di dalam database Anda.

    ```bash
    php artisan migrate
    ```

    _(Opsional: Jika Anda ingin mengisi database dengan data contoh (dummy), gunakan `php artisan migrate --seed` jika tersedia)._

7. **Jalankan Aplikasi**
   Mulai server lokal bawaan Laravel untuk mengakses website.

    ```bash
    php artisan serve
    ```

    _Buka browser web Anda dan kunjungi alamat `http://localhost:8000`_

8. **Kompilasi Aset Frontend (Opsional namun disarankan)**
   Buka terminal baru di folder yang sama dan jalankan perintah ini agar perubahan desain (CSS/JS) dapat dimuat secara langsung.
    ```bash
    npm run dev
    ```

## 🤝 Berkontribusi

Jika Anda memiliki ide untuk peningkatan atau menemukan masalah (_bug_), silakan buat _Pull Request_ atau laporkan masalah di bagian _Issues_ pada repositori ini. Semua masukan sangat dihargai untuk membuat website ini menjadi lebih baik!

## 📄 Lisensi

Proyek ini bersifat sumber terbuka (_open-source_) dan menggunakan lisensi [MIT License](https://opensource.org/licenses/MIT).
