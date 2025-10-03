document.addEventListener("DOMContentLoaded", function () {
  const navButtons = document.querySelectorAll("#friend-nav button");
  const content = document.getElementById("friend-content");

  navButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      navButtons.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      const tab = this.dataset.tab;
      loadFriendTab(tab);
    });
  });

  function loadFriendTab(tab) {
    fetch("friend-item.php?tab=" + tab)
      .then((res) => res.text())
      .then((html) => (content.innerHTML = html))
      .catch(
        (err) =>
          (content.innerHTML = `<div class="text-danger">Lỗi tải dữ liệu</div>`)
      );
  }

  // Load tab mặc định
  document.querySelector("#friend-nav button.active").click();
});
