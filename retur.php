<?php
require 'includes/init.php'; need_login();
$uid=$_SESSION['u']['iduser'];

if($_SERVER['REQUEST_METHOD']=='POST'){
  try{
    $d=q("SELECT * FROM detail_penerimaan WHERE iddetail_penerimaan=?",[$_POST['dp']??0])->fetch();
    $j=(int)($_POST['jumlah']??0);
    if(!$d||$j<1||$j>$d['jumlah_terima'])throw new Exception('Jumlah retur tidak valid (maks '.($d['jumlah_terima']??0).')');
    $pdo->beginTransaction();
    q("INSERT INTO retur (idpenerimaan,iduser) VALUES (?,?)",[$d['idpenerimaan'],$uid]);$rid=$pdo->lastInsertId();
    q("INSERT INTO detail_retur (jumlah,alasan,idretur,iddetail_penerimaan) VALUES (?,?,?,?)",[$j,$_POST['alasan'],$rid,$d['iddetail_penerimaan']]);
    $pdo->commit();
    flash('ok','Retur tersimpan, kartu stok berkurang otomatis (trigger)');
  }catch(Exception $e){fail($e);}
  redirect('retur.php');
}

page_header('retur','Retur');
echo '<div class="card"><h3>Retur ke Vendor</h3><form method="post">Barang diterima: '.sel('dp',"SELECT d.iddetail_penerimaan v,CONCAT('Terima #',d.idpenerimaan,' - ',b.nama,' (',d.jumlah_terima,')') l FROM detail_penerimaan d JOIN barang b ON b.idbarang=d.barang_idbarang").'<input type="number" name="jumlah" min="1" placeholder="jumlah" required><input name="alasan" placeholder="alasan retur" required><button>Simpan Retur</button></form></div>';
echo '<div class="card"><h3>Riwayat Retur</h3>'.tbl(q("SELECT r.idretur,r.created_at,b.nama barang,d.jumlah,d.alasan FROM retur r JOIN detail_retur d ON d.idretur=r.idretur JOIN detail_penerimaan p ON p.iddetail_penerimaan=d.iddetail_penerimaan JOIN barang b ON b.idbarang=p.barang_idbarang ORDER BY 1 DESC LIMIT 10")->fetchAll()).'</div>';
page_footer();
