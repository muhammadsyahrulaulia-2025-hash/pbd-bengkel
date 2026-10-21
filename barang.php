<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'barang','judul'=>'Barang','view'=>'v_barang','tabel'=>'barang','pk'=>'idbarang',
  'field'=>[
    'jenis'=>['Jenis','sel',['O'=>'Oli','S'=>'Sparepart','A'=>'Aksesoris']],
    'nama'=>['Nama','text'],
    'idsatuan'=>['Satuan','sel','SELECT idsatuan v,nama_satuan l FROM satuan WHERE status=1'],
    'harga'=>['Harga Pokok','number'],
  ],
  'status'=>[1,0],
]);
