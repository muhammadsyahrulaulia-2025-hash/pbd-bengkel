-- ============================================
-- STORED PROCEDURE & FUNCTION - DATABASE BENGKEL
-- Tugas UTS Pemrograman Basis Data
-- ============================================

use db_bengkel;

delimiter //

-- ============================================
-- BAGIAN 1 : FUNCTION
-- function = mengembalikan 1 nilai, dipanggil pakai SELECT
-- ============================================

-- 1. function untuk melihat stok terakhir barang
drop function if exists fn_stok_barang//
create function fn_stok_barang(p_idbarang int)
returns int
reads sql data
begin
  declare v_stok int default 0;

  select stock into v_stok
  from kartu_stok
  where idbarang = p_idbarang
  order by idkartu_stok desc
  limit 1;

  return v_stok;
end//

-- 2. function untuk menghitung ppn 11%
drop function if exists fn_hitung_ppn//
create function fn_hitung_ppn(p_nilai int)
returns int
deterministic
begin
  return round(p_nilai * 0.11);
end//

-- 3. function untuk menghitung harga jual
--    harga jual = harga barang + margin yang aktif
drop function if exists fn_harga_jual//
create function fn_harga_jual(p_idbarang int)
returns int
reads sql data
begin
  declare v_hasil double;

  select barang.harga * (1 + margin_penjualan.persen / 100) into v_hasil
  from barang, margin_penjualan
  where barang.idbarang = p_idbarang
  and margin_penjualan.status = 1;

  return round(v_hasil);
end//

-- ============================================
-- BAGIAN 2 : STORED PROCEDURE
-- procedure = kumpulan perintah SQL, dipanggil pakai CALL
-- ============================================

-- 1. procedure tambah satuan
drop procedure if exists sp_tambah_satuan//
create procedure sp_tambah_satuan(in p_nama varchar(45))
begin
  insert into satuan (nama_satuan, status)
  values (p_nama, 1);
end//

-- 2. procedure tambah barang
drop procedure if exists sp_tambah_barang//
create procedure sp_tambah_barang(
  in p_jenis char(1),
  in p_nama varchar(45),
  in p_idsatuan int,
  in p_harga int
)
begin
  -- harga tidak boleh 0 atau minus
  if p_harga <= 0 then
    signal sqlstate '45000' set message_text = 'Harga harus lebih dari 0';
  end if;

  insert into barang (jenis, nama, idsatuan, status, harga)
  values (p_jenis, p_nama, p_idsatuan, 1, p_harga);
end//

-- 3. procedure ubah harga barang
drop procedure if exists sp_ubah_harga_barang//
create procedure sp_ubah_harga_barang(in p_idbarang int, in p_harga int)
begin
  update barang
  set harga = p_harga
  where idbarang = p_idbarang;
end//

-- 4. procedure tambah vendor
drop procedure if exists sp_tambah_vendor//
create procedure sp_tambah_vendor(in p_nama varchar(100), in p_badan char(1))
begin
  insert into vendor (nama_vendor, badan_hukum, status)
  values (p_nama, p_badan, 'A');
end//

-- 5. procedure aktifkan margin
--    semua margin dinonaktifkan dulu, baru 1 margin diaktifkan
drop procedure if exists sp_aktifkan_margin//
create procedure sp_aktifkan_margin(in p_idmargin int)
begin
  update margin_penjualan set status = 0 where status = 1;
  update margin_penjualan set status = 1 where idmargin_penjualan = p_idmargin;
end//

-- 6. procedure melihat kartu stok 1 barang
drop procedure if exists sp_lihat_kartu_stok//
create procedure sp_lihat_kartu_stok(in p_idbarang int)
begin
  select kartu_stok.idkartu_stok, barang.nama, kartu_stok.jenis_transaksi,
         kartu_stok.masuk, kartu_stok.keluar, kartu_stok.stock
  from kartu_stok, barang
  where kartu_stok.idbarang = barang.idbarang
  and kartu_stok.idbarang = p_idbarang
  order by kartu_stok.idkartu_stok;
end//

delimiter ;

-- ============================================
-- BAGIAN 3 : MENCOBA FUNCTION
-- ============================================
select fn_stok_barang(1);
select fn_hitung_ppn(100000);
select idbarang, nama, harga, fn_stok_barang(idbarang) as stok from barang;

-- fn_harga_jual hasilnya kosong (null) kalau belum ada margin aktif
-- aktifkan dulu margin nomor 3 (30%), baru coba
-- call sp_aktifkan_margin(3);
-- select fn_harga_jual(1);

-- ============================================
-- BAGIAN 4 : MENCOBA PROCEDURE
-- (hapus tanda -- di depan baris, lalu jalankan satu-satu)
-- ============================================
-- call sp_tambah_satuan('Liter');
-- call sp_tambah_barang('S', 'Filter Oli', 4, 35000);
-- call sp_ubah_harga_barang(1, 52000);
-- call sp_tambah_vendor('Mitra Lubricant', 'C');
-- call sp_aktifkan_margin(3);
-- call sp_lihat_kartu_stok(1);

-- coba harga salah, harusnya muncul pesan error
-- call sp_tambah_barang('S', 'Barang Gratis', 4, 0);

-- ============================================
-- MELIHAT DAFTAR FUNCTION DAN PROCEDURE
-- ============================================
show function status where db = 'db_bengkel';
show procedure status where db = 'db_bengkel';
