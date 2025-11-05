<div class="search-bar mx-2">
  <form action="/home" method="get" class="d-flex">
    <input class="form-control me-2" type="text" name="search" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-light" type="submit">
      <i class="fa-solid fa-search"></i>
    </button>
  </form>
</div>
