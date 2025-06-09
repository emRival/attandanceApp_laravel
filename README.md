
# Aplikasi Absensi Cerdas 🏢

<p align="center">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/Sockets.io-010101?style=for-the-badge&logo=socket.io&logoColor=white" alt="Sockets.io">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/Filament-F79230?style=for-the-badge&logoColor=white" alt="Filament">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

Aplikasi Absensi Cerdas adalah aplikasi web berbasis PHP yang dirancang untuk mempermudah proses pencatatan kehadiran. Dengan integrasi Laravel dan potensi penggunaan Socket.io, aplikasi ini menawarkan pengalaman real-time dan efisien dalam manajemen absensi.

## Fitur Utama ✨

*   **Manajemen Kelas dan Tingkatan 📚**: Pengelolaan data kelas dan tingkatan siswa yang terstruktur.
*   **Otentikasi dan Otorisasi Berbasis Peran 🔐**: Kontrol akses yang ketat dengan sistem peran (admin, guru, siswa).
*   **Antarmuka Pengguna Modern dengan Filament 🎨**: Tampilan yang intuitif dan responsif menggunakan Filament PHP.
*   **Potensi Integrasi Real-time dengan Socket.IO ⚡**: Peningkatan responsivitas dan notifikasi instan untuk absensi.

## Tech Stack 🛠️

*   Bahasa: PHP
*   Framework: Laravel
*   UI Framework: Filament PHP
*   Real-time Communication: Socket.IO (Potensial)
*   Database: (Kemungkinan MySQL, namun periksa konfigurasi `.env`)
*   Frontend: JavaScript

## Instalasi & Menjalankan 🚀

1.  Clone repositori:
    ```bash
    git clone https://github.com/emRival/attandanceApp_laravel
    ```

2.  Masuk ke direktori:
    ```bash
    cd attandanceApp_laravel
    ```

3.  Install dependensi:
    ```bash
    composer install
    ```

4.  Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

5.  Konfigurasi database pada file `.env` (pastikan MySQL atau database lain yang Anda gunakan sudah terinstall dan berjalan).

6.  Generate application key:
    ```bash
    php artisan key:generate
    ```

7.  Migrasi database dan seeder (opsional, jika ada data awal):
    ```bash
    php artisan migrate --seed
    ```

8.  Jalankan proyek:
    ```bash
    php artisan serve
    ```

    Aplikasi akan berjalan pada `http://localhost:8000`.

## Cara Berkontribusi 🤝

1.  Fork repositori ini.
2.  Buat branch dengan nama fitur Anda: `git checkout -b feature/nama-fitur`.
3.  Commit perubahan Anda: `git commit -m 'Menambahkan fitur baru'`.
4.  Push ke branch Anda: `git push origin feature/nama-fitur`.
5.  Buat Pull Request.

## Lisensi 📄

Tidak disebutkan.

---
*Dibuat dengan ❤️ oleh [emRival](https://github.com/emRival)*

