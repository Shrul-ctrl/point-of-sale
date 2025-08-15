## cara instalasi
:composer create-project laravel/laravel:^9.0 Project_name
:php artisan make:migration create_name_table --create=name
:php artisan migrate
:php artisan make:seeder NamesTableSeeder
:php artisan make:model Name ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎
:php artisan migrate:refresh --seed
:php artisan make:controller NameController
:composer require laravel/ui                     
:php artisan ui:auth

## Cara Menggunakan
1. Masuk ke halaman **Produk**, tambahkan produk baru.
2. Masuk ke halaman **Transaksi**, pilih pelanggan.
3. Klik tombol **Tambah** untuk menambahkan produk ke keranjang.
4. Atur jumlah qty jika perlu, total dan diskon akan dihitung otomatis.
5. Klik **Simpan Transaksi** untuk menyimpan transaksi.
6. Cek **Riwayat Transaksi** untuk melihat detail setiap transaksi.


## fitur yang di implementasikan
- **Manajemen Produk**: Tambah, edit, hapus produk beserta harga, stok, dan foto.
- **Transaksi Kasir**: Pilih pelanggan, tambah beberapa produk ke keranjang, atur jumlah (qty), dan hitung subtotal otomatis.
- **Diskon Otomatis**: Diskon persentase otomatis berdasarkan total transaksi.
- **Riwayat Transaksi**: Lihat semua transaksi yang telah dilakukan, termasuk detail produk setiap transaksi.

## Foto Database
<img width="1216" height="748" alt="image" src="https://github.com/user-attachments/assets/52e2e236-4773-4b9a-94f4-5fc871d15ad5" />

