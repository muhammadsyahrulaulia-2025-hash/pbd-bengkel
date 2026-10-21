<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'margin','judul'=>'Margin Penjualan','view'=>'v_margin_penjualan','tabel'=>'margin_penjualan','pk'=>'idmargin_penjualan',
  'field'=>['persen'=>['Persen (%)','number']],
  'pakai_iduser'=>true,   // kolom iduser diisi otomatis dari login
  'aktifkan'=>true,       // tombol Aktifkan -> sp_aktifkan_margin
]);
