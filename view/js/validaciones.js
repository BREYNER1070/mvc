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

function validateForm() {
  clearAllErrors();
  let hayErrores = false;

  const docType  = document.getElementById('document_type_id').value;
  const docNum   = document.getElementById('document_number').value.trim();
  const nombre   = document.getElementById('nombre').value.trim();
  const correo   = document.getElementById('correo').value.trim();
  const password = document.getElementById('password').value.trim();
  const confirm  = document.getElementById('confirmar_password').value.trim();

  if (docType === '') {
    showError('document_type_id', 'El tipo de documento es obligatorio');
    hayErrores = true;
  }
  if (docNum === '') {
    showError('document_number', 'El número de documento es obligatorio');
    hayErrores = true;
  } else if (!/^[0-9]+$/.test(docNum)) {
    showError('document_number', 'Solo puede contener números');
    hayErrores = true;
  }
  if (nombre === '') {
    showError('nombre', 'El nombre es obligatorio');
    hayErrores = true;
  }
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
  } else if (password.length < 6) {
    showError('password', 'Mínimo 6 caracteres');
    hayErrores = true;
  }
  if (confirm === '') {
    showError('confirmar_password', 'Confirme su contraseña');
    hayErrores = true;
  } else if (password !== confirm) {
    showError('confirmar_password', 'Las contraseñas no coinciden');
    hayErrores = true;
  }

  if (hayErrores) {
    document.getElementById('js-alert-general').style.display = 'block';
  }

  return !hayErrores;
}

// Validación en tiempo real
const campos = ['document_type_id', 'document_number', 'nombre', 'correo', 'password', 'confirmar_password'];
campos.forEach(function(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.addEventListener('blur', validateForm);
  el.addEventListener('input', function() { clearError(id); });
});

// Submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
  if (!validateForm()) e.preventDefault();
});