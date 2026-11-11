# POS Baby Spa

Aplikasi PHP dan MySQL untuk operasional baby spa. Membutuhkan PHP 8.1+ dengan PDO MySQL dan database MySQL/MariaDB.

## Pasang di InfinityFree

1. Unggah isi folder `baby-spa` ke folder `htdocs` domain Anda.
2. Buka `config.php` dan isi password MySQL dari panel hosting. Nama database, username, dan host sudah mengikuti gambar yang Anda kirim; cek kembali nilainya di panel sebelum menyimpan.
3. Buka domain. Saat database belum memiliki tabel aplikasi, halaman setup akan meminta nama, username, dan password admin pertama (minimal 10 karakter) lalu membuat tabel otomatis.
4. Login sebagai admin, masukkan customer dan treatment, lalu catat pembayaran di menu Kasir. Omzet mengikuti nominal yang dicatat pada setiap transaksi.

Untuk instalasi lokal, buat `config.local.php` dengan format yang sama. File ini diutamakan oleh aplikasi dan jangan dibagikan.

## Menu

- Ringkasan omzet hari ini/bulan ini, jumlah customer lama (member aktif) dan customer baru (bukan member), serta kunjungan terbaru.
- Profil customer mengikuti kolom orang tua, alamat, WhatsApp, sosial media, data baby/kids, tanggal lahir, keluhan, serta BB/TB.
- Treatment menyimpan kode, nama, tipe, harga normal, harga member, serta fee terapis, dengan edit dan hapus.
- Barang tambahan seperti minuman dikelola terpisah dengan kode, satuan, harga beli, harga jual, margin, dan stok.
- Menu Arus Stok mencatat stok masuk (dengan harga beli), penyesuaian/stok opname, dan stok keluar otomatis dari kasir. Menampilkan nilai persediaan, total masuk/keluar per periode, riwayat per barang, dan posisi stok. Pengguna selain admin perlu diberi hak akses menu Arus Stok di menu Pengguna.
- Form Buat reservasi di menu Reservasi (admin) memilih customer dari master dengan satu kolom pencarian (ketik nama, nama anak, nomor HP, atau nomor member). Hasil dipisah dua kelompok: Member aktif (diawali nomor member) dan Customer biasa (bukan member).
- Reservasi menampilkan customer baru/lama, nomor member, profil customer, treatment, tanggal, dan status.
- Di form reservasi online ada pilihan Customer baru dan Sudah member. Member cukup mengetik nomor member lalu menekan Cari; nama orang tua, nama anak, dan nomor WhatsApp (sebagian disamarkan) muncul otomatis, tanpa mengisi data lagi. Hanya member berstatus aktif dan belum kedaluwarsa yang dikenali. Pencarian dibatasi 12 kali per 10 menit per pengunjung. Setelah reservasi member terkirim, histori tidak dibuka otomatis; cek histori tetap memakai nomor WhatsApp dan kode booking.
- Customer tidak perlu membuat akun/login. Bagikan tautan `https://domain-anda/reservasi.php`; customer mengisi data, memilih jam tersedia sesuai jadwal yang admin atur, lalu memakai nomor WhatsApp dan kode booking untuk melihat status reservasi serta histori treatment.
- Menu Pengaturan menyimpan jam buka, jam tutup, dan durasi setiap slot reservasi di database. Slot yang sudah dipesan dan jam yang lewat untuk hari ini tidak ditampilkan.
- Admin menyetujui atau menolak reservasi dari menu Reservasi (Setujui, Tolak, Batalkan). Setelah disetujui, ditolak, dibatalkan, atau diedit (jadwal, treatment, atau status berubah), muncul kotak hijau dengan tombol Kirim lewat WhatsApp yang membuka WhatsApp dengan pesan yang sesuai situasinya; petugas menekan Kirim di WhatsApp. Di tiap baris tersedia tombol WA (Kabari WA / Kirim WA) untuk menghubungi customer kapan saja. Reservasi yang ditolak atau dibatalkan berstatus Batal dan jamnya dibuka kembali.
- Menu Kasir memakai keranjang. Pilih reservasi: customer/member dan jadwal langsung terisi (kolom customer disembunyikan, tampil ringkasan dengan lencana Member/Bukan member), treatment dari reservasi otomatis masuk keranjang dengan harga member bila member. Untuk walk-in, kosongkan reservasi lalu cari customer (kelompok Member aktif / Customer biasa).
- Pembayaran dari reservasi mengubah statusnya menjadi Selesai. Tiap transaksi boleh memuat beberapa treatment dan barang; semua item dihitung sebagai satu kunjungan pada laporan jarak kunjungan.
- Rekap omzet bulanan per hari (dengan fee terapis), fee terapis per terapis dan per treatment, CSV, dan jarak rata-rata antar kunjungan per customer. Fee diambil dari kolom Fee terapis di master treatment, dikali jumlah sesi, dan disimpan saat transaksi dicatat sehingga perubahan fee di master tidak mengubah laporan lama. CSV berisi angka murni (bukan teks "Rp") agar mudah dihitung di spreadsheet.
- Pengguna dengan hak akses menu individual.
- Terapis diambil dari daftar Pengguna yang aktif (tidak ada master terapis terpisah). Di Kasir, setiap treatment di keranjang punya pilihan Terapis yang wajib diisi; pilihan terakhir diingat untuk treatment berikutnya. Nama terapis tampil di nota dan di rincian riwayat transaksi. Transaksi lama yang belum punya terapis tetap tampil normal tanpa baris Terapis.

Jarak kunjungan memakai selisih hari antara kunjungan pertama dan terakhir dibagi jumlah jeda. Customer dengan satu kunjungan ditampilkan sebagai kunjungan pertama.

## Nota digital dan shift

- Setelah pembayaran disimpan, halaman nota terbuka otomatis. Nota memuat header (nama usaha, alamat, telepon), rincian belanja, total, metode bayar, dan footer. Header dan footer diatur di menu Pengaturan.
- Nota bisa dicetak, atau dikirim ke customer lewat tombol Kirim ke WhatsApp yang menyertakan tautan `nota.php?c=...&t=...`. Tautan memakai token yang tidak bisa ditebak; customer tidak perlu login. Token dibuat dari password database di `config.php`, jadi tautan lama tidak berlaku bila password itu diganti.
- Nota lama bisa dibuka lagi dari kolom Nota di Riwayat transaksi (menu Kasir).
- Menu Shift: buka shift dengan saldo awal kas, lalu tutup dengan memasukkan kas hasil hitung fisik. Sistem menghitung kas seharusnya (saldo awal + penjualan tunai) dan selisihnya. Pembayaran hanya bisa disimpan saat ada shift yang terbuka, dan hanya satu shift boleh terbuka pada satu waktu.
- Pengguna selain admin perlu diberi hak akses menu Shift dan Arus Stok di menu Pengguna.

## Pencarian dan pagination

- Daftar Customer, Member, Treatment, Barang, dan Riwayat transaksi punya kolom pencarian dan pagination. Pencarian membaca beberapa kata sekaligus (misalnya "sari naya" menemukan customer bernama Sari dengan anak Naya). Jumlah baris per halaman bisa dipilih 10, 25, atau 50.
- Riwayat transaksi menampilkan satu baris per nomor transaksi (header: waktu, customer, jumlah item, total, pembayaran, petugas, sumber). Klik baris atau tombol panah untuk melihat rincian item; rincian dimuat saat diklik. Pencarian di sini juga membaca nama item, dan ada filter tanggal.
- Data lama tanpa nomor transaksi tampil sebagai "Riwayat lama #id", satu baris per catatan.
