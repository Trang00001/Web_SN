// public/assets/js/validation.js
(function(){
  function isEmail(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
  function applyInvalid(input, cond, messageEl){
    if(cond){
      input.classList.add('is-invalid');
      if(messageEl){ messageEl.style.display='block'; }
    }else{
      input.classList.remove('is-invalid');
      if(messageEl){ messageEl.style.display='none'; }
    }
  }
  window.attachBasicValidation = function(formSelector, opts){
    const form = document.querySelector(formSelector);
    if(!form) return;
    opts = Object.assign({confirm: false}, opts||{});
    const email = form.querySelector('input[type="email"]');
    const pass  = form.querySelector('input[type="password"][name="password"]');
    const confirm = form.querySelector('input[name="confirm"]');
    form.addEventListener('submit', function(e){
      let bad = false;
      if(email){
        const invalid = !(email.value && isEmail(email.value));
        applyInvalid(email, invalid, email.nextElementSibling);
        bad = bad || invalid;
      }
      if(pass){
        const invalid = !(pass.value && pass.value.length >= 6);
        applyInvalid(pass, invalid, pass.nextElementSibling);
        bad = bad || invalid;
      }
      if(opts.confirm && confirm){
        const invalid = !(confirm.value && confirm.value === pass.value);
        applyInvalid(confirm, invalid, confirm.nextElementSibling);
        bad = bad || invalid;
      }
      if(bad){ e.preventDefault(); }
    });
  }
})();