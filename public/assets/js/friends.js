document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll("#friend-nav button");
  const content = document.getElementById("friend-content");

  // ------------------ Load tab bằng AJAX ------------------
  function loadTab(action) {
    if (action === "suggested") {
      content.innerHTML = `
        <div class="text-center py-5 text-muted">
          Chưa có gợi ý.
        </div>`;
      return;
    }

    fetch(`/app/controllers/FriendController.php?action=${action}`)
      .then((res) => res.text())
      .then((html) => (content.innerHTML = html))
      .catch((err) => console.error(err));
  }

  // ------------------ Chuyển tab ------------------
  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");
      loadTab(this.dataset.tab);
    });
  });

  // ------------------ Xử lý click nút ------------------
  content.addEventListener("click", function (e) {
    const target = e.target;
    const id = target.dataset.id;
    if (!id) return;

    let action = "";
    if (target.classList.contains("btn-accept-request")) action = "accept";
    else if (target.classList.contains("btn-reject-request")) action = "reject";
    else if (target.classList.contains("btn-remove-friend")) action = "remove";
    else if (target.classList.contains("btn-send-request"))
      action = "send_request";
    if (!action) return;

    fetch(`/app/controllers/FriendController.php?action=${action}`, {
      method: "POST",
      body: new URLSearchParams({ id }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (!data.success) {
          alert(data.message || "Có lỗi xảy ra!");
          return;
        }

        // ------------------ Xóa card hiện tại ------------------
        const card = target.closest(".friend-item");
        if (card) card.remove();

        // ------------------ Append bạn mới vào tab All Friends ------------------
        if (
          (action === "accept" || action === "send_request") &&
          data.newFriend
        ) {
          const friendInfo = data.newFriend;
          const newFriend = document.createElement("div");
          newFriend.classList.add("col-md-6", "mb-3", "friend-item");
          newFriend.innerHTML = `
          <div class="card p-3 friend-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <img src="${
                friendInfo.AvatarURL ||
                "/Web_SN/public/assets/images/default-avatar.png"
              }" 
                   alt="avatar" class="rounded-circle me-3" width="50" height="50">
              <span class="fw-bold">${friendInfo.Username}</span>
            </div>
            <button class="btn btn-sm btn-danger btn-remove-friend" data-id="${
              friendInfo.AccountID
            }">
              Xóa bạn
            </button>
          </div>`;

          const activeTab = document.querySelector("#friend-nav button.active");
          if (activeTab && activeTab.dataset.tab === "all") {
            let rowContainer = content.querySelector(".row");
            if (!rowContainer) {
              rowContainer = document.createElement("div");
              rowContainer.classList.add("row", "g-3");
              content.appendChild(rowContainer);
            }
            rowContainer.prepend(newFriend);
          }
        }
      })
      .catch((err) => console.error(err));
  });

  // ------------------ Load tab mặc định ------------------
  if (tabs.length > 0) tabs[0].click();
});
