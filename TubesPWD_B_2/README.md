                            Caritas Cafe - Delivery 2

//Sebelum membuka web

Web ini memakai PHP dan database MySQL dari XAMPP. Lakukan urutan ini terlebih dahulu:

1. Buka XAMPP Control Panel, lalu Start Apache dan Start MySQL.
2. Buka `http://localhost/phpmyadmin`.
3. Klik tab Import, pilih file `database/database.sql`, lalu klik Go.
4. Buka `http://localhost/TubesPWD_B_2/`.

Database harus sudah di-import ke XAMPP sebelum web dibuka. Kalau tidak, web akan menampilkan pesan koneksi database gagal.

//Akun Admin dan Kasir

Akun ini ditulis langsung di kode (`config/config.php`), jadi bisa langsung dipakai login setelah database di-import.

Role (username & paddword) :
1. Admin: admin123
2. Kasir: kasir123

Akun pelanggan dibuat lewat halaman Register.