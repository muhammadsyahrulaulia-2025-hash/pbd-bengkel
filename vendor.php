<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'vendor','judul'=>'Vendor','view'=>'v_vendor','tabel'=>'vendor','pk'=>'idvendor',
  'field'=>[
    'nama_vendor'=>['Nama Vendor','text'],
    'badan_hukum'=>['Badan Hukum','sel',['P'=>'PT','C'=>'CV','U'=>'UD']],
  ],
  'status'=>['A','N'],
]);
