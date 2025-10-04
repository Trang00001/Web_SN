(function () {
  // Kiểm tra định dạng email
  function isEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  // Áp dụng trạng thái invalid cho input
  function applyInvalid(input, cond, messageEl) {
    if (cond) {
      input.classList.add('is-invalid');
      if (messageEl) messageEl.style.display = 'block';
    } else {
      input.classList.remove('is-invalid');
      if (messageEl) messageEl.style.display = 'none';
    }
  }

  // Hàm attach validation cho form
  window.attachBasicValidation = function (formSelector, opts) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    opts = Object.assign({ confirm: false }, opts || {});

    const email = form.querySelector('input[type="email"]');
    const pass = form.querySelector('input[type="password"][name="password"]');
    const confirm = form.querySelector('input[name="confirm"]');

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // Ngăn submit mặc định

      let bad = false;

      // Validate email
      if (email) {
        const invalid = !(email.value && isEmail(email.value));
        applyInvalid(email, invalid, email.nextElementSibling);
        bad = bad || invalid;
      }

      // Validate password
      if (pass) {
        const invalid = !(pass.value && pass.value.length >= 6);
        applyInvalid(pass, invalid, pass.nextElementSibling);
        bad = bad || invalid;
      }

      // Validate confirm password nếu cần
      if (opts.confirm && confirm) {
        const invalid = !(confirm.value && confirm.value === pass.value);
        applyInvalid(confirm, invalid, confirm.nextElementSibling);
        bad = bad || invalid;
      }

      if (!bad) {
        // Nếu form login (không cần DB), hardcode kiểm tra email/password
        const emailVal = email ? email.value : '';
        const passVal = pass ? pass.value : '';

        if ((emailVal === "admin@example.com" && passVal === "12345678") ||
            (emailVal === "user@example.com" && passVal === "password")) {
          // Login thành công → chuyển sang trang Home
          window.location.href = "/SN_Web/home/index.html";
        } else {
          alert("Email hoặc mật khẩu không đúng");
        }
      }
    });
  }
})();
