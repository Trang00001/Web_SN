document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("notifBtn");
  const list = document.getElementById("notifList");
  const count = document.getElementById("notifCount");

  // Hàm lấy thông báo từ server
  async function fetchNotifications() {
    try {
      const res = await fetch("/api/notifications");
      const data = await res.json();

      if (!Array.isArray(data)) return;

      // Cập nhật số lượng
      count.textContent = data.filter(n => !n.IsRead).length > 0 
        ? data.filter(n => !n.IsRead).length 
        : "";

      // Render danh sách
      list.innerHTML = data
        .map(
          (n) => `
            <div class="notif-item ${n.IsRead ? "read" : ""}">
                ${n.Content}
                <button class="mark-read" data-id="${n.NotificationID}">
                  Đã đọc
                </button>
            </div>
          `
        )
        .join("");

    } catch (err) {
      console.error("Fetch notifications error:", err);
    }
  }

  // Toggle hiển thị danh sách
  btn.addEventListener("click", () => {
    list.style.display = list.style.display === "block" ? "none" : "block";
  });

  // Xử lý nhấn nút "Đã đọc"
  list.addEventListener("click", async (e) => {
    if (e.target.classList.contains("mark-read")) {
      const id = e.target.dataset.id;

      try {
        const res = await fetch("/api/notifications/read", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `notification_id=${id}`,
        });

        if (!res.ok) throw new Error("Failed to mark read");

        // Reload danh sách sau khi đánh dấu đã đọc
        await fetchNotifications();

      } catch (err) {
        console.error(err);
      }
    }
  });

  // Tải thông báo khi load trang
  fetchNotifications();

  // Tự động reload mỗi 10 giây
  setInterval(fetchNotifications, 10000);
});
