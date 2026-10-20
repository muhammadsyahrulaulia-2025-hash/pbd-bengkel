use db_bengkel;

-- view role
create view v_role as
select * from role;

-- view user (digabung sama role)
create view v_user as
select user.iduser, user.username, role.nama_role
from user, role
where user.idrole = role.idrole;

-- view satuan
create view v_satuan as
select * from satuan;

-- view barang (digabung sama satuan)
create view v_barang as
select barang.idbarang, barang.nama, barang.jenis, satuan.nama_satuan, barang.harga, barang.status
from barang, satuan
where barang.idsatuan = satuan.idsatuan;

-- view vendor
create view v_vendor as
select * from vendor;

-- view margin penjualan
create view v_margin_penjualan as
select * from margin_penjualan;

-- menampilkan semua view
select * from v_role;
select * from v_user;
select * from v_satuan;
select * from v_barang;
select * from v_vendor;
select * from v_margin_penjualan;
