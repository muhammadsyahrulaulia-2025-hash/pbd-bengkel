<?php
require 'includes/init.php'; need_login();
page_header('dashboard','Dashboard');
$mg=q("SELECT persen FROM margin_penjualan WHERE status=1")->fetch();
echo '<div class="card"><h3>Dashboard</h3><p>Selamat datang, <b>'.h($_SESSION['u']['username']).'</b>. Margin aktif: <b>'.($mg?h($mg['persen']).'%':'belum ada').'</b></p></div><div class="card"><h3>Stok Barang Saat Ini</h3>'.tbl(q("SELECT * FROM v_stok_barang")->fetchAll()).'</div>';
page_footer();
