<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bạn bè - Social Network</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    #friend-nav {
        background-color: #ffffff; 
        border-right: 1px solid #dee2e6;
        min-height: 80vh;
    }
    #friend-content {
        background-color: #f1f3f5; 
        padding: 20px;
        min-height: 80vh;
    }
  </style>
</head>
<body>
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar trái -->
    <div class="col-lg-3 mb-3">
      <div class="list-group" id="friend-nav">
        <button class="list-group-item list-group-item-action active" data-tab="all">Tất cả bạn bè</button>
        <button class="list-group-item list-group-item-action" data-tab="suggested">Gợi ý kết bạn</button>
        <button class="list-group-item list-group-item-action" data-tab="requests">Lời mời kết bạn</button>
      </div>
    </div>

    <!-- Nội dung bên phải -->
    <div class="col-lg-9" id="friend-content">
      <div class="text-center text-muted py-5">
        Chọn mục bên trái để xem nội dung.
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Friends JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const navButtons = document.querySelectorAll("#friend-nav button");
    const content = document.getElementById("friend-content");

    navButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            navButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            const tab = this.dataset.tab;
            loadFriendTab(tab);
        });
    });

    function loadFriendTab(tab) {
     fetch('/WEB/Web_SN/app/views/components/item/friend-item.php?tab=' + tab)
            .then(res => res.text())
            .then(html => content.innerHTML = html)
            .catch(err => content.innerHTML = `<div class="text-danger">Lỗi tải dữ liệu</div>`);
    }

    // Load tab mặc định
    document.querySelector("#friend-nav button.active").click();
});
</script>
</body>
</html>
