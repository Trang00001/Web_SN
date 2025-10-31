<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand" href="/">
      <i class="fa-brands fa-instagram"></i> MySocial
    </a>

    <!-- Toggle khi mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      <!-- Menu trái -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item mx-1">
          <a class="nav-link active" href="/home">
            <i class="fa-solid fa-house"></i>
            <span class="d-none d-lg-inline">Home</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/friends">
            <i class="fa-solid fa-user-friends"></i>
            <span class="d-none d-lg-inline">Bạn bè</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/messages">
            <i class="fa-solid fa-comments"></i>
            <span class="d-none d-lg-inline">Tin nhắn</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/profile">
            <i class="fa-solid fa-user"></i>
            <span class="d-none d-lg-inline">Trang cá nhân</span>
          </a>
        </li>
      </ul>

      <!-- Thanh tìm kiếm -->
      <?php include __DIR__ . '/search-bar.php'; ?>

      <!-- Menu phải -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item mx-1">
          <a class="nav-link" href="/notifications">
            <i class="fa-solid fa-bell"></i>
            <span class="d-none d-lg-inline">Thông báo</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/settings">
            <i class="fa-solid fa-gear"></i>
            <span class="d-none d-lg-inline">Cài đặt</span>
          </a>
        </li>
      </ul>

    </div>
  </div>
</nav>