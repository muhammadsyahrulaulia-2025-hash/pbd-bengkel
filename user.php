<?php
require 'includes/init.php'; require 'includes/master.php';
master_page([
  'key'=>'user','judul'=>'User','view'=>'v_user','tabel'=>'user','pk'=>'iduser',
  'field'=>[
    'username'=>['Username','text'],
    'password'=>['Password','password'],
    'idrole'=>['Role','sel','SELECT idrole v,nama_role l FROM role'],
  ],
]);
