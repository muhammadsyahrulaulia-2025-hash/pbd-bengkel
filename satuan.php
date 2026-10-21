<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'satuan','judul'=>'Satuan','view'=>'v_satuan','tabel'=>'satuan','pk'=>'idsatuan',
  'field'=>['nama_satuan'=>['Nama Satuan','text']],
  'status'=>[1,0],
]);
