// ====== GLOBAL JS ======

// Bootstrap tooltip & toast khởi tạo tự động
document.addEventListener('DOMContentLoaded', () => {
  // Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

  // Toast container: show bằng JS
  window.showToast = (selector) => {
    const toastEl = document.querySelector(selector);
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
  };
});

// ====== MODAL AUTH ======
// Đóng/mở modal login/register dùng Bootstrap
function openLoginModal() {
  const modal = new bootstrap.Modal(document.getElementById('modalLogin'));
  modal.show();
}
function openRegisterModal() {
  const modal = new bootstrap.Modal(document.getElementById('modalRegister'));
  modal.show();
}
window.openLoginModal = openLoginModal;
window.openRegisterModal = openRegisterModal;

// ====== AJAX HELPER ======
async function ajaxRequest(url, method = 'GET', data = null) {
  const options = { method };
  if (data) {
    options.headers = { 'Content-Type': 'application/json' };
    options.body = JSON.stringify(data);
  }
  const res = await fetch(url, options);
  if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
  return res.json();
}
window.ajaxRequest = ajaxRequest;
