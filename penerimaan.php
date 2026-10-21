<?php
require 'includes/init.php'; need_login();
$uid=$_SESSION['u']['iduser'];

if($_SERVER['REQUEST_METHOD']=='POST'){
  try{
    $id=$_POST['pengadaan']??'';if(!$id)throw new Exception('Pilih pengadaan');
    $pdo->beginTransaction();
    q("INSERT INTO penerimaan (status,idpengadaan,iduser) VALUES ('S',?,?)",[$id,$uid]);$pn=$pdo->lastInsertId();
    q("INSERT INTO detail_penerimaan (idpenerimaan,barang_idbarang,jumlah_terima,harga_satuan_terima,sub_total_terima) SELECT ?,idbarang,jumlah,harga_satuan,sub_total FROM detail_pengadaan WHERE idpengadaan=?",[$pn,$id]);
    q("UPDATE pengadaan SET status='S' WHERE idpengadaan=?",[$id]);
    $pdo->commit();
    flash('ok','Barang diterima, kartu stok bertambah otomatis (trigger)');
  }catch(Exception $e){fail($e);}
  redirect('penerimaan.php');
}

page_header('penerimaan','Penerimaan');
echo '<div class="card"><h3>Terima Barang dari Pengadaan</h3><form method="post">Pengadaan: '.sel('pengadaan',"SELECT idpengadaan v,CONCAT('#',idpengadaan,' - total Rp ',total_nilai) l FROM pengadaan WHERE status='P'",'-- pilih pengadaan --').'<button>Terima Semua Barang</button></form><p class="mut">Hanya pengadaan yang belum diterima. Setelah disimpan, trigger mengisi kartu stok.</p></div>';
echo '<div class="card"><h3>Riwayat Penerimaan</h3>'.tbl(q("SELECT idpenerimaan,created_at,idpengadaan FROM penerimaan ORDER BY 1 DESC LIMIT 10")->fetchAll()).'</div>';
page_footer();
