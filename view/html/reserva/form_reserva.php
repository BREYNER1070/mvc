<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservar — Grand Éclat Hotel</title>
  <link rel="stylesheet" href="<?= SITE_URL ?>view/img/styles.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>view/img/reserva.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body>

<nav class="nav scrolled">
  <div class="nav__logo">Grand <em>Éclat</em></div>
  <ul class="nav__links">
    <li><a href="<?= SITE_URL ?>index.php?action=home">Inicio</a></li>
    <li><a href="<?= SITE_URL ?>index.php?action=getMisReservas">Mis reservas</a></li>
    <li><span class="nav__user">👤 <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span></li>
    <li><a href="<?= SITE_URL ?>index.php?action=logoutUser">Cerrar sesión</a></li>
  </ul>
</nav>

<div id="toast" class="toast toast--hidden"></div>

<main class="reserva-main">
  <div class="reserva-card">
    <div class="reserva-card__header">
      <span class="reserva-card__label">Grand Éclat Hotel</span>
      <h1 class="reserva-card__title">Haga su <em>reserva</em></h1>
      <p class="reserva-card__sub">Complete los datos para asegurar su estancia.</p>
    </div>

    <form id="reservaForm" novalidate>

      <!-- PASO 1: Fechas y personas -->
      <fieldset class="rform__section">
        <legend class="rform__legend">1 · Fechas y ocupación</legend>
        <div class="form-row">
          <div class="form-group">
            <label for="fecha_inicio">Llegada</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required/>
            <span class="js-error" id="err-fecha_inicio"></span>
          </div>
          <div class="form-group">
            <label for="fecha_final">Salida</label>
            <input type="date" id="fecha_final" name="fecha_final" required/>
            <span class="js-error" id="err-fecha_final"></span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="numero_personas">N.º de personas</label>
            <input type="number" id="numero_personas" name="numero_personas" min="1" max="10" placeholder="2" required/>
            <span class="js-error" id="err-numero_personas"></span>
          </div>
        </div>
      </fieldset>

      <!-- PASO 2: Categoría -->
      <fieldset class="rform__section">
        <legend class="rform__legend">2 · Tipo de habitación</legend>
        <div class="form-group form-group--full">
          <label for="categoria_id">Categoría</label>
          <select id="categoria_id" name="categoria_id">
            <option value="">— Cargando categorías… —</option>
          </select>
          <span class="js-error" id="err-categoria_id"></span>
        </div>
      </fieldset>

      <!-- PASO 3: Habitación -->
      <fieldset class="rform__section" id="seccion-habitaciones" style="display:none">
        <legend class="rform__legend">3 · Habitación disponible</legend>
        <div id="habitaciones-loader" class="hab-loader" style="display:none">
          <span class="spinner"></span> Buscando habitaciones…
        </div>
        <div id="habitaciones-lista" class="hab-grid"></div>
        <input type="hidden" id="habitacion_id" name="habitacion_id"/>
        <input type="hidden" id="precio_noche"  name="precio_noche"/>
        <span class="js-error" id="err-habitacion_id"></span>
        <div id="precio-resumen" class="precio-resumen" style="display:none">
          <span class="precio-resumen__noches" id="resumen-noches"></span>
          <span class="precio-resumen__total"  id="resumen-total"></span>
        </div>
      </fieldset>

      <div class="form-footer">
        <p class="form-note">Cancelación gratuita hasta 48 h antes. Sin cargos ocultos.</p>
        <button type="submit" class="btn-reserve" id="btn-reservar">
          <span id="btn-texto">Confirmar reserva</span>
          <span id="btn-spinner" class="spinner spinner--btn" style="display:none"></span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
      </div>
    </form>
  </div>
</main>

<footer class="footer">
  <div class="footer__brand">Grand <em>Éclat</em></div>
  <p>© 2026 Grand Éclat Hotel · Cartagena de Indias, Colombia</p>
</footer>

<script>
const SITE_URL = "<?= SITE_URL ?>";

function apiPost(action, formData) {
  return new Promise((resolve, reject) => {
    fetch(`${SITE_URL}index.php?action=${action}`, {
      method: 'POST', body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
    .then(resolve).catch(reject);
  });
}

function showToast(msg, tipo = 'success') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = `toast toast--${tipo}`;
  setTimeout(() => { t.className = 'toast toast--hidden'; }, 4500);
}

function showError(campo, msg) {
  const el  = document.getElementById(`err-${campo}`);
  const inp = document.getElementById(campo);
  if (el)  el.textContent = msg;
  if (inp) inp.classList.add('input-error');
}
function clearErrors() {
  document.querySelectorAll('.js-error').forEach(e => e.textContent = '');
  document.querySelectorAll('.input-error').forEach(e => e.classList.remove('input-error'));
}

// ── Cargar categorías ────────────────────────────────────────────────────────
function cargarCategorias() {
  return new Promise((resolve, reject) => {
    apiPost('getCategorias', new FormData())
      .then(data => {
        const sel = document.getElementById('categoria_id');
        sel.innerHTML = '<option value="">— Seleccione una categoría —</option>';
        if (data.success && data.categorias.length > 0) {
          data.categorias.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id; opt.textContent = c.nombre;
            sel.appendChild(opt);
          });
          resolve(data.categorias);
        } else {
          sel.innerHTML = '<option value="">Sin categorías disponibles</option>';
          resolve([]);
        }
      }).catch(reject);
  });
}

// ── Buscar habitaciones disponibles ──────────────────────────────────────────
function buscarHabitaciones() {
  const fi  = document.getElementById('fecha_inicio').value;
  const ff  = document.getElementById('fecha_final').value;
  const cat = document.getElementById('categoria_id').value;
  if (!fi || !ff || !cat) return;

  document.getElementById('habitacion_id').value = '';
  document.getElementById('precio_noche').value  = '';
  document.getElementById('precio-resumen').style.display = 'none';
  document.getElementById('habitaciones-lista').innerHTML = '';
  document.getElementById('seccion-habitaciones').style.display = 'block';
  document.getElementById('habitaciones-loader').style.display  = 'flex';

  const fd = new FormData();
  fd.append('fecha_inicio', fi);
  fd.append('fecha_final',  ff);
  fd.append('categoria_id', cat);

  apiPost('getHabitacionesDisponibles', fd)
    .then(data => {
      document.getElementById('habitaciones-loader').style.display = 'none';
      const lista = document.getElementById('habitaciones-lista');
      if (!data.success) { lista.innerHTML = `<p class="hab-empty">${data.mensaje}</p>`; return; }
      if (!data.habitaciones.length) { lista.innerHTML = '<p class="hab-empty">No hay habitaciones disponibles para esas fechas.</p>'; return; }
      renderHabitaciones(data.habitaciones);
    })
    .catch(() => {
      document.getElementById('habitaciones-loader').style.display = 'none';
      document.getElementById('habitaciones-lista').innerHTML = '<p class="hab-empty">Error al buscar habitaciones.</p>';
    });
}

function renderHabitaciones(habitaciones) {
  const lista = document.getElementById('habitaciones-lista');
  lista.innerHTML = '';
  habitaciones.forEach(hab => {
    const card = document.createElement('div');
    card.className = 'hab-card';
    card.dataset.id     = hab.id;
    card.dataset.precio = hab.precio;
    card.innerHTML = `
      <div class="hab-card__top">
        <span class="hab-card__cat">${hab.categoria}</span>
        <span class="hab-card__precio">$${parseFloat(hab.precio).toLocaleString('es-CO')} <small>/noche</small></span>
      </div>
      <p class="hab-card__desc">${hab.descripcion || 'Habitación confortable'}</p>
      <div class="hab-card__meta">
        <span>🛏 ${hab.numero_camas} ${hab.numero_camas > 1 ? 'camas' : 'cama'}</span>
        <span>👥 Máx. ${hab.max_personas} personas</span>
        ${hab.aseo ? '<span>✔ Aseo</span>' : ''}
      </div>
      <button type="button" class="hab-card__btn">Seleccionar</button>`;
    card.querySelector('.hab-card__btn').addEventListener('click', () => seleccionarHabitacion(card, hab));
    lista.appendChild(card);
  });
}

function seleccionarHabitacion(card, hab) {
  document.querySelectorAll('.hab-card').forEach(c => c.classList.remove('hab-card--selected'));
  card.classList.add('hab-card--selected');
  document.getElementById('habitacion_id').value = hab.id;
  document.getElementById('precio_noche').value  = hab.precio;

  const fi = new Date(document.getElementById('fecha_inicio').value);
  const ff = new Date(document.getElementById('fecha_final').value);
  const noches = Math.round((ff - fi) / 86400000);
  const total  = noches * parseFloat(hab.precio);

  document.getElementById('resumen-noches').textContent = `${noches} noche(s)`;
  document.getElementById('resumen-total').textContent  = `Total estimado: $${total.toLocaleString('es-CO', {minimumFractionDigits:2})}`;
  document.getElementById('precio-resumen').style.display = 'flex';
}

// ── Submit ───────────────────────────────────────────────────────────────────
document.getElementById('reservaForm').addEventListener('submit', function(e) {
  e.preventDefault();
  clearErrors();

  const fd      = new FormData(this);
  const btnT    = document.getElementById('btn-texto');
  const btnS    = document.getElementById('btn-spinner');
  const btn     = document.getElementById('btn-reservar');
  btnT.style.display = 'none'; btnS.style.display = 'inline-block'; btn.disabled = true;

  apiPost('crearReserva', fd)
    .then(data => {
      btnT.style.display = 'inline'; btnS.style.display = 'none'; btn.disabled = false;
      if (data.success) {
        showToast(`✔ ${data.mensaje} (${data.noches} noche(s) — $${data.precio})`, 'success');
        this.reset();
        document.getElementById('seccion-habitaciones').style.display = 'none';
        document.getElementById('precio-resumen').style.display = 'none';
        cargarCategorias(); // restaurar select
      } else {
        if (data.errores) Object.entries(data.errores).forEach(([k, v]) => showError(k, v));
        if (data.mensaje) showToast(`✖ ${data.mensaje}`, 'error');
      }
    })
    .catch(() => {
      btnT.style.display = 'inline'; btnS.style.display = 'none'; btn.disabled = false;
      showToast('Error de conexión. Intenta de nuevo.', 'error');
    });
});

// ── Eventos de cambio ────────────────────────────────────────────────────────
['fecha_inicio', 'fecha_final', 'categoria_id'].forEach(id => {
  document.getElementById(id).addEventListener('change', buscarHabitaciones);
});

const hoy = new Date().toISOString().split('T')[0];
document.getElementById('fecha_inicio').min = hoy;
document.getElementById('fecha_final').min  = hoy;
document.getElementById('fecha_inicio').addEventListener('change', function() {
  document.getElementById('fecha_final').min = this.value;
});

// ── Inicio ───────────────────────────────────────────────────────────────────
cargarCategorias()
  .then(() => console.log('Categorías cargadas'))
  .catch(() => showToast('No se pudieron cargar las categorías', 'error'));
</script>
</body>
</html>
