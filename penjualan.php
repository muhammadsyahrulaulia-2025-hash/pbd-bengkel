<?php
require 'includes/init.php'; need_login();
$uid=$_SESSION['u']['iduser'];

if($_SERVER['REQUEST_METHOD']=='POST'){
  try{
    $it=items();if(!$it)throw new Exception('Isi minimal 1 barang dan jumlahnya');
    $pdo->beginTransaction();$sub=0;$rw=[];
    $mg=q("SELECT * FROM margin_penjualan WHERE status=1")->fetch();
    if(!$mg)throw new Exception('Belum ada margin aktif. Aktifkan dulu di Master > Margin.');
    foreach($it as [$b,$j]){
      $h=(int)q("SELECT harga FROM barang WHERE idbarang=?",[$b])->fetchColumn();
      $h=(int)round($h*(1+$mg['persen']/100));
      $rw[]=[$h,$j,$h*$j,$b];$sub+=$h*$j;
    }
    $ppn=(int)round($sub*0.11);
    q("INSERT INTO penjualan (subtotal_nilai,ppn,total_nilai,iduser,idmargin_penjualan) VALUES (?,?,?,?,?)",[$sub,$ppn,$sub+$ppn,$uid,$mg['idmargin_penjualan']]);
    $id=$pdo->lastInsertId();
    foreach($rw as $r)q("INSERT INTO detail_penjualan (harga_satuan,jumlah,subtotal,idbarang,penjualan_idpenjualan) VALUES (?,?,?,?,?)",array_merge($r,[$id]));
    $pdo->commit();
    flash('ok','Transaksi tersimpan. Total Rp '.number_format($sub+$ppn,0,',','.'));
  }catch(Exception $e){fail($e);}
  redirect('penjualan.php');
}

page_header('penjualan','Penjualan');
$mg=q("SELECT persen FROM margin_penjualan WHERE status=1")->fetch();
echo '<div class="card"><h3>Kasir Penjualan</h3><p>Margin aktif: <b>'.($mg?h($mg['persen']).'%':'belum ada (aktifkan di Master > Margin)').'</b> | PPN 11%</p><form method="post">'.rows3().'<button>Simpan Penjualan</button></form></div>';
echo '<div class="card"><h3>Riwayat Penjualan</h3>'.tbl(q("SELECT p.idpenjualan,p.created_at,m.persen margin,p.subtotal_nilai,p.ppn,p.total_nilai FROM penjualan p JOIN margin_penjualan m ON m.idmargin_penjualan=p.idmargin_penjualan ORDER BY 1 DESC LIMIT 10")->fetchAll()).'</div>';
page_footer();
