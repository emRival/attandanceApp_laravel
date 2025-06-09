
# Aplikasi Absensi Cerdas ⏱️

<p align="center">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img style="margin-right: 8px;" src="https://img.shields.io/badge/Filament-009688?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAzBJREFUSEvVlU1v01AUhZ+lK7GzD5t4E9gB8Qa07wE9iLwB7QD2IPAGdAd0B+iHQGfQ3kR3QHegOxKqD/gLso5xW0s8c3t5d7c33vP/ec7959f+7sC46wDqCg8R4QkQ8o+hG5D6iY4mI44kY0z4kR5LhSZHk+F9J0y3M0KzLky3ckw3Uoy/1fH+6nJ0mF/8oN23J6v73T5b4G/wHlC4RzicQp7A3S62z3dG+e04P3u2/WnC+61z3V4H/7X3k79u3e3O/e/9D75dE4V196+2XjYn+vV96+33jYn/94+623t3i/x+9g15xL+X+K1w/y3882lE/3a921b/kU95t9yXo7w53j5n6y19v518L77XvW3y/+N92P8b74v/FfVq5u/l95J7+804684jW8N6w4Y/V5UjP96M3301f/wG4t5Q33pU4d92zL9z9m/y923W/35d7/i/Hl09J/yQ8N7xTz463U4180/z/3g8tq+J2W7sE2i6178l10U937dY6v876v0z5P9Q/G77vE774D659114R1l8n0S0J637/J74vM0mI7d5O/U3S7/T/L9d0m00J+U09x6/o773d47k96O9F0y0h0r3G6P+X+4t7f6W610e0J7T4O6U0P8o/eH6z1f1u/k6n+J9u0g+51V7Y5/u3g5l0i1I8p5I/vPzXh/yT5f8W/1/178T/1/0P30P9wNl/A01o25t7yLgAAAAAElFTkSuQmCC" alt="Filament">
</p>

Aplikasi Absensi Cerdas adalah aplikasi web berbasis Laravel yang dirancang untuk menyederhanakan dan mengotomatiskan proses pencatatan kehadiran. Aplikasi ini memberikan solusi efisien dan terpusat untuk memantau kehadiran karyawan atau siswa, menghasilkan laporan yang akurat, dan mengelola data kehadiran dengan mudah.

Aplikasi ini dikembangkan dengan mempertimbangkan kemudahan penggunaan dan fleksibilitas, sehingga dapat disesuaikan dengan berbagai kebutuhan organisasi atau institusi pendidikan.

## Fitur Utama ✨

*   **Manajemen Data Nilai 📊**: Memungkinkan administrator untuk mengelola dan memperbarui data nilai dengan mudah.
*   **Manajemen Peran Pengguna 🧑‍💼**: Kontrol akses dan izin pengguna dengan sistem peran yang fleksibel.
*   **Antarmuka Pengguna yang Intuitif 📱**: Dirancang agar mudah digunakan oleh semua pengguna, bahkan yang tidak memiliki latar belakang teknis.
*   **Filament Integration 🔌**: Memanfaatkan Filament untuk administrasi cepat dan powerful.

## Tech Stack 🛠️

*   Bahasa Pemrograman: PHP 🐘
*   Framework: Laravel 🚀
*   Admin Panel: Filament 🧵
*   Database: (Kemungkinan MySQL atau MariaDB) 🗄️

## Instalasi & Menjalankan 🚀

Ikuti langkah-langkah di bawah ini untuk menginstal dan menjalankan aplikasi:

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

4.  Konfigurasi environment:
    ```bash
    cp .env.example .env
    ```
    Kemudian, sesuaikan pengaturan database di file `.env`.

5.  Generate application key:
    ```bash
    php artisan key:generate
    ```

6.  Migrasi database dan seed (jika diperlukan):
    ```bash
    php artisan migrate --seed
    ```

7.  Jalankan proyek:
    ```bash
    php artisan serve
    ```

    Aplikasi akan berjalan di `http://localhost:8000`.

## Cara Berkontribusi 🤝

Kami sangat senang menerima kontribusi dari komunitas! Berikut adalah cara Anda dapat berkontribusi:

1.  Fork repositori ini.
2.  Buat branch untuk fitur atau perbaikan Anda: `git checkout -b feature/nama-fitur`.
3.  Commit perubahan Anda: `git commit -m 'Tambahkan fitur baru'`.
4.  Push ke branch Anda: `git push origin feature/nama-fitur`.
5.  Buat Pull Request.

## Lisensi 📄

Tidak disebutkan.

---

*Dibuat dengan ❤️ oleh [emRival](https://github.com/emRival)*
