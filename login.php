<?php
require 'includes/init.php';
if(isset($_SESSION['u']))redirect('index.php');
$err='';
if(isset($_POST['login'])){
  $u=q("SELECT u.iduser,u.username,r.nama_role FROM user u JOIN role r ON r.idrole=u.idrole WHERE u.username=? AND u.password=SHA2(?,256)",[$_POST['username'],$_POST['password']])->fetch();
  if($u){$_SESSION['u']=$u;redirect('index.php');}
  $err='Username atau password salah';
}
?>
<!doctype html><meta charset="utf-8"><title>Login Bengkel</title>
<link rel="stylesheet" href="assets/style.css">
<div class="card login">
  <h3>Login Bengkel</h3>
  <?php if($err): ?><div class="er"><?= h($err) ?></div><?php endif; ?>
  <form method="post">
    <input name="username" placeholder="username" required><br>
    <input name="password" type="password" placeholder="password" required><br>
    <button name="login">Masuk</button>
  </form>
  <p class="mut">Contoh: admin / admin123</p>
</div>
