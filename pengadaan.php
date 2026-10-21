<?php
require 'includes/init.php'; need_login();
$uid=$_SESSION['u']['iduser'];

if($_SERVER['REQUEST_METHOD']=='POST'){
  try{
    $it=items();if(!$it)throw new Exception('Isi minimal 1 barang dan jumlahnya');
    $pdo->beginTransaction();$sub=0;$rw=[];
    foreach($it as [$b,$j]){
      $h=(int)q("SELECT harga FROM barang WHERE idbarang=?",[$b])->fetchColumn();
      $rw[]=[$h,$j,$h*$j,$b];$sub+=$h*$j;
    }
    $ppn=(int)round($sub*0.11);
    q("INSERT INTO pengadaan (user_iduser,status,vendor_idvendor,subtotal_nilai,ppn,total_nilai) VALUES (?,'P',?,?,?,?)",[$uid,$_POST['vendor'],$sub,$ppn,$sub+$ppn]);
    $id=$pdo->lastInsertId();
    foreach($rw as $r)q("INSERT INTO detail_pengadaan (harga_satuan,jumlah,sub_total,idbarang,idpengadaan) VALUES (?,?,?,?,?)",array_merge($r,[$id]));
    $pdo->commit();
    flash('ok','Transaksi tersimpan. Total Rp '.number_format($sub+$ppn,0,',','.'));
  }catch(Exception $e){fail($e);}
  redirect('pengadaan.php');
}

page_header('pengadaan','Pengadaan');
echo '<div class="card"><h3>Buat Pengadaan (PPN 11%)</h3><form method="post">Vendor: '.sel('vendor',"SELECT idvendor v,nama_vendor l FROM vendor WHERE status='A'").rows3().'<button>Simpan Pengadaan</button></form></div>';
echo '<div class="card"><h3>Riwayat Pengadaan</h3>'.tbl(q("SELECT p.idpengadaan,p.`timestamp` tanggal,v.nama_vendor,p.total_nilai,IF(p.status='P','Menunggu penerimaan','Selesai') status FROM pengadaan p JOIN vendor v ON v.idvendor=p.vendor_idvendor ORDER BY 1 DESC LIMIT 10")->fetchAll()).'</div>';
page_footer();
