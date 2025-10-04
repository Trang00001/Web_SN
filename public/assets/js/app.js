document.addEventListener("DOMContentLoaded", function () {
  // Lấy đường dẫn hiện tại, chuẩn hóa /index.html
  let currentPath = window.location.pathname;
  currentPath = currentPath.replace(/\/index\.html$/, "/");

  // Chọn tất cả link navbar
  const navLinks = document.querySelectorAll(".navbar .nav-link");

  navLinks.forEach((link) => {
    let linkPath = link.getAttribute("href");

    // Chuẩn hóa linkPath
    if (!linkPath.startsWith("/")) {
      // Nếu là link tương đối, chuyển thành từ root dự án
      linkPath = "/" + linkPath;
    }
    linkPath = linkPath.replace(/\/index\.html$/, "/");

    // Nếu currentPath chứa linkPath, active
    if (currentPath.endsWith(linkPath) || currentPath === linkPath) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
});
