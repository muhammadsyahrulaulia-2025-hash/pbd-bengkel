<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'role','judul'=>'Role','view'=>'v_role','tabel'=>'role','pk'=>'idrole',
  'field'=>['nama_role'=>['Nama Role','text']],
]);
