# kledo

langkah-langkah menggunakan aplikasi
1. Clone Project dahulu dari github.
2. selanjutnya buka project dari cmd lalu ketikkan composer install.
3. setelah sukses buat file .env dari file .env.example
4. selanjutnya buat database dari mysql dan di file.env diarahkan ke database yang telah dibuat
5. selanjutnya jalankan perintah di cmd dengan ketikkan "php artisan migrate --seed"
6. selanjutnya ujicoba setiap api yg berada dilokasi localhost://namaproject/api/namaroute
7. untuk ujicoba api disetujui-massal sebelumnya di cmd ketikkan "php artisan queue:work" lalu dijalankan apinya
8. selanjutnya untuk menjalankan unit testnya bisa mengetikkan "php artisan test" dicmd
