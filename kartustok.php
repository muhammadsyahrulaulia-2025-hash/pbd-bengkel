<?php
require 'includes/init.php'; need_login();
page_header('kartustok','Kartu Stok');
echo '<div class="card"><h3>Kartu Stok <small class="mut">(diisi otomatis oleh trigger)</small></h3>'.tbl(q("SELECT k.idkartu_stok,k.created_at,b.nama barang,CASE k.jenis_transaksi WHEN 'T' THEN 'Terima' WHEN 'R' THEN 'Retur' ELSE 'Jual' END jenis,k.masuk,k.keluar,k.stock FROM kartu_stok k JOIN barang b ON b.idbarang=k.idbarang ORDER BY k.idkartu_stok DESC")->fetchAll()).'</div>';
page_footer();
