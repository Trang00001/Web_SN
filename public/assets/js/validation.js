// Kiểm tra email hợp lệ & không để trống. Áp dụng cho cả popup & full page.
(function () {
  function isEmail(v) {
    // regex vừa phải, tránh quá chặt
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(v).toLowerCase());
  }

  function setInvalid(input, msg) {
    if (!input) return;
    input.classList.add('is-invalid');
    const fb = input.parentElement.querySelector('.invalid-feedback');
    if (fb && msg) fb.textContent = msg;
  }
  function clearInvalid(input) {
    if (!input) return;
    input.classList.remove('is-invalid');
  }

  function handleLogin(form) {
    let ok = true;
    const identity = form.querySelector('[name="identity"]');
    const password = form.querySelector('[name="password"]');

    clearInvalid(identity); clearInvalid(password);

    if (!identity.value.trim()) { setInvalid(identity); ok = false; }
    if (!password.value || password.value.length < 6) {
      setInvalid(password, 'Mật khẩu tối thiểu 6 ký tự.'); ok = false;
    }
    return ok;
  }

  function handleRegister(form) {
    let ok = true;
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const pw = form.querySelector('[name="password"]');
    const pw2 = form.querySelector('[name="password_confirm"]');
    const agree = form.querySelector('#agree, #agree_full');

    [name, email, pw, pw2].forEach(clearInvalid);

    if (!name.value.trim()) { setInvalid(name); ok = false; }
    if (!isEmail(email.value)) { setInvalid(email, 'Email không hợp lệ.'); ok = false; }
    if (!pw.value || pw.value.length < 6) { setInvalid(pw, 'Mật khẩu tối thiểu 6 ký tự.'); ok = false; }
    if (pw2.value !== pw.value) { setInvalid(pw2, 'Mật khẩu nhập lại chưa khớp.'); ok = false; }
    if (agree && !agree.checked) { agree.classList.add('is-invalid'); ok = false; } else if (agree) { agree.classList.remove('is-invalid'); }
    return ok;
  }

  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-validate]');
    if (!form) return;

    const type = form.getAttribute('data-validate');
    let valid = true;
    if (type === 'login') valid = handleLogin(form);
    if (type === 'register') valid = handleRegister(form);

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
    }
  }, true);
})();
