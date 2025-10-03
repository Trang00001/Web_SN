<?php
// app/views/components/layout/navbar.php
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/"><i class="fa-brands fa-hashnode me-2"></i>Social</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/auth/login">Đăng nhập</a></li>
        <li class="nav-item"><a class="nav-link" href="/auth/register">Đăng ký</a></li>
        <li class="nav-item"><a class="nav-link" href="/profile">Hồ sơ</a></li>
      </ul>
    </div>
  </div>
</nav>
