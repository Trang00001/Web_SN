document.addEventListener("DOMContentLoaded", () => {
  const navButtons = document.querySelectorAll("#friend-nav button");
  const content = document.getElementById("friend-content");

  // --------------------------
  // Xử lý click các tab
  // --------------------------
  navButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      navButtons.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      const tab = this.dataset.tab;
      loadFriendTab(tab);
    });
  });

  function loadFriendTab(tab) {
    fetch("/Web_SN/app/views/components/item/friend-item.php?tab=" + tab)
      .then((res) => res.text())
      .then((html) => {
        content.innerHTML = html;
        initFriendActions(); // Gọi để thêm sự kiện cho các nút mới
      })
      .catch((err) => {
        content.innerHTML = `<div class="text-danger">Lỗi tải dữ liệu</div>`;
      });
  }

  // Load tab mặc định
  document.querySelector("#friend-nav button.active")?.click();

  // --------------------------
  // Xử lý các nút tương tác
  // --------------------------
  function initFriendActions() {
    // Kết bạn (Suggested)
    document.querySelectorAll(".btn-primary").forEach((btn) => {
      btn.addEventListener("click", function () {
        this.disabled = true;
        this.textContent = "Đã gửi ✅";
        console.log(
          "Đã gửi lời mời kết bạn đến:",
          this.previousElementSibling.textContent
        );
      });
    });

    // Chấp nhận
    document.querySelectorAll(".btn-success").forEach((btn) => {
      btn.addEventListener("click", function () {
        const card = this.closest(".card");
        const name = card.querySelector("span").textContent;
        card.innerHTML = `<div class="text-center text-success py-2">✅ Bạn đã chấp nhận lời mời của ${name}</div>`;
        console.log("Chấp nhận:", name);
      });
    });

    // Từ chối
    document.querySelectorAll(".btn-danger").forEach((btn) => {
      btn.addEventListener("click", function () {
        const card = this.closest(".card");
        const name = card.querySelector("span").textContent;
        card.innerHTML = `<div class="text-center text-secondary py-2">❌ Bạn đã từ chối lời mời của ${name}</div>`;
        console.log("Từ chối:", name);
      });
    });
  }
});
