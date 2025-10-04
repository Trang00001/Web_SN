document.addEventListener("DOMContentLoaded", function () {
  const currentPath = window.location.pathname.replace(/\/index\.html$/, "/");

  const navLinks = document.querySelectorAll(".navbar .nav-link");

  navLinks.forEach((link) => {
    let linkPath = link.getAttribute("href");

    // Nếu link là tương đối thì chuẩn hóa
    if (!linkPath.startsWith("/")) {
      linkPath = "/" + linkPath;
    }

    // Chuẩn hóa /index.html
    linkPath = linkPath.replace(/\/index\.html$/, "/");

    // So sánh: nếu currentPath kết thúc bằng linkPath thì active
    if (currentPath.endsWith(linkPath)) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
});