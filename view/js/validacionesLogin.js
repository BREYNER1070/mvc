function showError(fieldId, msg) {
  const input = document.getElementById(fieldId);
  const span  = document.getElementById('err-' + fieldId);
  if (input) input.classList.add('input-error');
  if (span)  span.textContent = msg;
}

function clearError(fieldId) {
  const input = document.getElementById(fieldId);
  const span  = document.getElementById('err-' + fieldId);
  if (input) input.classList.remove('input-error');
  if (span)  span.textContent = '';
}

function clearAllErrors() {
  document.querySelectorAll('.js-error').forEach(el => el.textContent = '');
  document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
  document.getElementById('js-alert-general').style.display = 'none';
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateLogin() {
  clearAllErrors();
  let hayErrores = false;

  const correo   = document.getElementById('correo').value.trim();
  const password = document.getElementById('password').value.trim();

  if (correo === '') {
    showError('correo', 'El correo es obligatorio');
    hayErrores = true;
  } else if (!isValidEmail(correo)) {
    showError('correo', 'El correo no es válido');
    hayErrores = true;
  }

  if (password === '') {
    showError('password', 'La contraseña es obligatoria');
    hayErrores = true;
  }

  if (hayErrores) {
    document.getElementById('js-alert-general').style.display = 'block';
  }

  return !hayErrores;
}

['correo', 'password'].forEach(function(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.addEventListener('blur', validateLogin);
  el.addEventListener('input', function() { clearError(id); });
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
  if (!validateLogin()) e.preventDefault();
});