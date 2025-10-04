<?php
$title = "Trang chủ";
ob_start();
?>
  <h1>Chào mừng bạn đến với MySocial</h1>
  <p>Đây là trang bạn bè.</p>
<?php
$content = ob_get_clean();
require_once __DIR__ .'/../../layouts/main.php';