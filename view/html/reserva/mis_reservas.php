<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mis Reservas — Grand Éclat Hotel</title>
  <link rel="stylesheet" href="<?= SITE_URL ?>view/img/styles.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>view/img/reserva.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body>

<nav class="nav scrolled">
  <div class="nav__logo">Grand <em>Éclat</em></div>
  <ul class="nav__links">
    <li><a href="<?= SITE_URL ?>index.php?action=home">Inicio</a></li>
    <li><a href="<?= SITE_URL ?>index.php?action=getFormReserva" class="nav__cta">+ Nueva reserva</a></li>
    <li><span class="nav__user">👤 <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span></li>
    <li><a href="<?= SITE_URL ?>index.php?action=logoutUser">Cerrar sesión</a></li>
  </ul>
</nav>

<div id="toast" class="toast toast--hidden"></div>

<!-- ══ MODAL EDITAR ══════════════════════════════════════════════════════════ -->
<div id="modal-overlay" class="modal-overlay modal-overlay--hidden">
  <div class="modal">
    <button class="modal__close" id="modal-close" title="Cerrar">✕</button>
    <div class="modal__header">
      <span class="reserva-card__label">Editar reserva</span>
      <h2 class="modal__title">Actualizar <em>reserva</em></h2>
    </div>

    <form id="editForm" novalidate>
      <input type="hidden" id="edit_id" name="id"/>

      <div class="form-row">
        <div class="form-group">
          <label for="edit_fecha_inicio">Llegada</label>
          <input type="date" id="edit_fecha_inicio" name="fecha_inicio" required/>
          <span class="js-error" id="err-edit_fecha_inicio"></span>
        </div>
        <div class="form-group">
          <label for="edit_fecha_final">Salida</label>
          <input type="date" id="edit_fecha_final" name="fecha_final" required/>
          <span class="js-error" id="err-edit_fecha_final"></span>
        </div>
      </div>

      <div class="form-group form-group--full">
        <label for="edit_numero_personas">N.º personas</label>
        <input type="number" id="edit_numero_personas" name="numero_personas" min="1" max="10"/>
        <span class="js-error" id="err-edit_numero_personas"></span>
      </div>

      <div class="form-group form-group--full" style="margin-top:.8rem">
        <label for="edit_categoria_id">Categoría</label>
        <select id="edit_categoria_id" name="categoria_id">
          <option value="">— Seleccione —</option>
        </select>
        <span class="js-error" id="err-edit_categoria_id"></span>
      </div>

      <div id="edit-seccion-hab" style="display:none; margin-top:1rem">
        <div id="edit-hab-loader" class="hab-loader" style="display:none">
          <span class="spinner"></span> Buscando habitaciones…
        </div>
        <div id="edit-hab-grid" class="hab-grid"></div>
        <input type="hidden" id="edit_habitacion_id" name="habitacion_id"/>
        <input type="hidden" id="edit_precio_noche"  name="precio_noche"/>
        <span class="js-error" id="err-edit_habitacion_id"></span>
        <div id="edit-precio-resumen" class="precio-resumen" style="display:none">
          <span id="edit-resumen-noches" class="precio-resumen__noches"></span>
          <span id="edit-resumen-total"  class="precio-resumen__total"></span>
        </div>
      </div>

      <div class="form-footer">
        <button type="button" class="btn-cancel-modal" id="btn-cancel-modal">Cancelar</button>
        <button type="submit" class="btn-reserve" id="btn-guardar">
          <span id="btn-guardar-texto">Guardar cambios</span>
          <span id="btn-guardar-spinner" class="spinner spinner--btn" style="display:none"></span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ══ MODAL CONFIRMAR CANCELACIÓN ══════════════════════════════════════════ -->
<div id="modal-cancel-overlay" class="modal-overlay modal-overlay--hidden">
  <div class="modal modal--sm">
    <div class="modal__header">
      <h2 class="modal__title">¿Cancelar <em>reserva</em>?</h2>
      <p class="modal__sub">Esta acción no se puede deshacer.</p>
    </div>
    <input type="hidden" id="cancel_id"/>
    <div class="form-footer form-footer--center">
      <button class="btn-cancel-modal" id="btn-no-cancel">No, volver</button>
      <button class="btn-danger" id="btn-confirm-cancel">
        <span id="btn-cancel-texto">Sí, cancelar</span>
        <span id="btn-cancel-spinner" class="spinner spinner--btn" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

<main class="reserva-main">
  <div class="reserva-card reserva-card--wide">
    <div class="reserva-card__header">
      <span class="reserva-card__label">Grand Éclat Hotel</span>
      <h1 class="reserva-card__title">Mis <em>reservas</em></h1>
      <p class="reserva-card__sub">Las reservas activas se pueden editar o cancelar.</p>
    </div>
    <a href="<?= SITE_URL ?>index.php?action=descargarExcelReservas" 
   class="btn-excel">
   ⬇ Descargar Excel
</a>
<style>.btn-excel {
    display: inline-flex;
    align-items: left;
    gap: 4px;
    padding: 5px 11px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid #1e7e34;
    background: transparent;
    color: #1e7e34;
    transition: background .2s, color .2s;
}
.btn-excel:hover {
    background: #1e7e34;
    color: #fff;
}</style>

    <?php if (empty($reservas)): ?>
      <div class="mis-reservas-empty">
        <p>Aún no tienes reservas registradas.</p>
        <a href="<?= SITE_URL ?>index.php?action=getFormReserva" class="btn-reserve">Hacer una reserva</a>
      </div>
    <?php else: ?>
      <div class="mis-reservas-tabla">
        <table class="rtabla" id="tabla-reservas">
          <thead>
            <tr>
              
              <th>Llegada</th>
              <th>Salida</th>
              <th>Personas</th>
              <th>Categoría</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r):
              $activa      = ($r['estado'] !== 'Cancelada');
              $estado_label = $r['estado'];
              $estado_clase = strtolower($r['estado']);
            ?>
            <tr id="fila-<?= $r['id'] ?>">
              
              <td><?= date('d/m/Y', strtotime($r['fecha_inicio'])) ?></td>
              <td><?= date('d/m/Y', strtotime($r['fecha_final'])) ?></td>
              <td><?= $r['numero_personas'] ?></td>
              <td><?= htmlspecialchars($r['categoria']) ?></td>
              <td>$<?= number_format($r['precio'], 2, '.', ',') ?></td>
              <td>
                <span class="badge badge--<?= $estado_clase ?>" id="badge-<?= $r['id'] ?>">
                  <?= $estado_label ?>
                </span>
              </td>
              <td class="acciones-cell">
                <?php if ($activa): ?>
                  <button class="btn-accion btn-editar"   data-id="<?= $r['id'] ?>" title="Editar">✏ Editar</button>
                  <button class="btn-accion btn-cancelar" data-id="<?= $r['id'] ?>" title="Cancelar">✕ Cancelar</button>
                <?php else: ?>
                  <span class="sin-acciones">—</span>
                <?php endif; ?>
                <a class="btn-accion btn-pdf"
                   href="<?= SITE_URL ?>index.php?action=descargarPDFReserva&id=<?= $r['id'] ?>"
                   target="_blank"
                   title="Descargar comprobante PDF">⬇ PDF</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</main>

<footer class="footer">
  <div class="footer__brand">Grand <em>Éclat</em></div>
  <p>© 2026 Grand Éclat Hotel · Cartagena de Indias, Colombia</p>
</footer>

<style>
.btn-pdf {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 5px 11px;
  border-radius: 4px;
  font-size: 12px;
  font-family: 'Jost', sans-serif;
  font-weight: 500;
  letter-spacing: .4px;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid #8b6914;
  background: transparent;
  color: #8b6914;
  transition: background .2s, color .2s;
}
.btn-pdf:hover {
  background: #8b6914;
  color: #fff;
}
</style>

<script>
const SITE_URL = "<?= SITE_URL ?>";

// ── AJAX helper ──────────────────────────────────────────────────────────────
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

// ── Toast ────────────────────────────────────────────────────────────────────
function showToast(msg, tipo = 'success') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = `toast toast--${tipo}`;
  setTimeout(() => { t.className = 'toast toast--hidden'; }, 4500);
}

// ── Errores de formulario ────────────────────────────────────────────────────
function showErr(prefix, campo, msg) {
  const el  = document.getElementById(`err-${prefix}${campo}`);
  const inp = document.getElementById(`${prefix}${campo}`);
  if (el)  el.textContent = msg;
  if (inp) inp.classList.add('input-error');
}
function clearErrs(prefix) {
  document.querySelectorAll(`[id^="err-${prefix}"]`).forEach(e => e.textContent = '');
  document.querySelectorAll(`[id^="${prefix}"]`).forEach(e => e.classList.remove('input-error'));
}

// ── Modales ──────────────────────────────────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.remove('modal-overlay--hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('modal-overlay--hidden'); }

document.getElementById('modal-close').addEventListener('click',      () => closeModal('modal-overlay'));
document.getElementById('btn-cancel-modal').addEventListener('click',  () => closeModal('modal-overlay'));
document.getElementById('btn-no-cancel').addEventListener('click',     () => closeModal('modal-cancel-overlay'));
['modal-overlay','modal-cancel-overlay'].forEach(id => {
  document.getElementById(id).addEventListener('click', e => { if (e.target.id === id) closeModal(id); });
});

// ── Cargar categorías en el modal ────────────────────────────────────────────
function cargarCategoriasEdit(selectedId) {
  return new Promise((resolve, reject) => {
    apiPost('getCategorias', new FormData()).then(data => {
      const sel = document.getElementById('edit_categoria_id');
      sel.innerHTML = '<option value="">— Seleccione —</option>';
      if (data.success) {
        data.categorias.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.id; opt.textContent = c.nombre;
          if (c.id == selectedId) opt.selected = true;
          sel.appendChild(opt);
        });
      }
      resolve();
    }).catch(reject);
  });
}

// ── Buscar habitaciones en el modal ──────────────────────────────────────────
let editReservaId = null;

function buscarHabsEdit() {
  const fi  = document.getElementById('edit_fecha_inicio').value;
  const ff  = document.getElementById('edit_fecha_final').value;
  const cat = document.getElementById('edit_categoria_id').value;
  if (!fi || !ff || !cat) return;

  document.getElementById('edit_habitacion_id').value = '';
  document.getElementById('edit_precio_noche').value  = '';
  document.getElementById('edit-precio-resumen').style.display = 'none';
  document.getElementById('edit-hab-grid').innerHTML = '';
  document.getElementById('edit-seccion-hab').style.display = 'block';
  document.getElementById('edit-hab-loader').style.display  = 'flex';

  const fd = new FormData();
  fd.append('categoria_id', cat);
  fd.append('fecha_inicio',  fi);
  fd.append('fecha_final',   ff);
  if (editReservaId) fd.append('excluir_id', editReservaId);

  apiPost('getHabitacionesDisponibles', fd).then(data => {
    document.getElementById('edit-hab-loader').style.display = 'none';
    const grd = document.getElementById('edit-hab-grid');
    if (!data.success || !data.habitaciones.length) {
      grd.innerHTML = `<p class="hab-empty">${data.mensaje || 'Sin habitaciones disponibles.'}</p>`; return;
    }
    renderHabsEdit(data.habitaciones);
  }).catch(() => {
    document.getElementById('edit-hab-loader').style.display = 'none';
    document.getElementById('edit-hab-grid').innerHTML = '<p class="hab-empty">Error al buscar habitaciones.</p>';
  });
}

function renderHabsEdit(habs) {
  const grd = document.getElementById('edit-hab-grid');
  grd.innerHTML = '';
  habs.forEach(h => {
    const card = document.createElement('div');
    card.className = 'hab-card';
    card.innerHTML = `
      <div class="hab-card__top">
        <span class="hab-card__cat">${h.categoria}</span>
        <span class="hab-card__precio">$${parseFloat(h.precio).toLocaleString('es-CO')} <small>/noche</small></span>
      </div>
      <p class="hab-card__desc">${h.descripcion || 'Habitación confortable'}</p>
      <div class="hab-card__meta">
        <span>🛏 ${h.numero_camas} cama(s)</span>
        <span>👥 Máx. ${h.max_personas}</span>
        ${h.aseo ? '<span>✔ Aseo</span>' : ''}
      </div>
      <button type="button" class="hab-card__btn">Seleccionar</button>`;
    card.querySelector('.hab-card__btn').addEventListener('click', () => seleccionarHabEdit(card, h));
    grd.appendChild(card);
  });
}

function seleccionarHabEdit(card, h) {
  document.querySelectorAll('#edit-hab-grid .hab-card').forEach(c => c.classList.remove('hab-card--selected'));
  card.classList.add('hab-card--selected');
  document.getElementById('edit_habitacion_id').value = h.id;
  document.getElementById('edit_precio_noche').value  = h.precio;

  const fi = new Date(document.getElementById('edit_fecha_inicio').value);
  const ff = new Date(document.getElementById('edit_fecha_final').value);
  const noches = Math.round((ff - fi) / 86400000);
  const total  = noches * parseFloat(h.precio);
  document.getElementById('edit-resumen-noches').textContent = `${noches} noche(s)`;
  document.getElementById('edit-resumen-total').textContent  = `Total: $${total.toLocaleString('es-CO', {minimumFractionDigits:2})}`;
  document.getElementById('edit-precio-resumen').style.display = 'flex';
}

// ── Abrir modal editar ────────────────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-editar');
  if (!btn) return;
  editReservaId = btn.dataset.id;

  document.getElementById('editForm').reset();
  clearErrs('edit_');
  document.getElementById('edit-seccion-hab').style.display   = 'none';
  document.getElementById('edit-precio-resumen').style.display = 'none';
  document.getElementById('edit-hab-grid').innerHTML = '';

  openModal('modal-overlay');

  const fd = new FormData();
  fd.append('id', editReservaId);

  apiPost('getReserva', fd).then(data => {
    if (!data.success) { showToast('✖ ' + data.mensaje, 'error'); closeModal('modal-overlay'); return; }
    const r = data.reserva;
    cargarCategoriasEdit(r.categoria_id).then(() => {
      document.getElementById('edit_id').value              = r.id;
      document.getElementById('edit_fecha_inicio').value    = r.fecha_inicio;
      document.getElementById('edit_fecha_final').value     = r.fecha_final;
      document.getElementById('edit_numero_personas').value = r.numero_personas;
      const hoy = new Date().toISOString().split('T')[0];
      document.getElementById('edit_fecha_inicio').min = hoy;
      document.getElementById('edit_fecha_final').min  = r.fecha_inicio;
      buscarHabsEdit();
    });
  }).catch(() => { showToast('Error de conexión', 'error'); closeModal('modal-overlay'); });
});

['edit_fecha_inicio','edit_fecha_final','edit_categoria_id'].forEach(id => {
  document.getElementById(id).addEventListener('change', buscarHabsEdit);
});
document.getElementById('edit_fecha_inicio').addEventListener('change', function() {
  document.getElementById('edit_fecha_final').min = this.value;
});

// ── Submit editar ─────────────────────────────────────────────────────────────
document.getElementById('editForm').addEventListener('submit', function(e) {
  e.preventDefault();
  clearErrs('edit_');

  const fd  = new FormData(this);
  const btnT = document.getElementById('btn-guardar-texto');
  const btnS = document.getElementById('btn-guardar-spinner');
  const btn  = document.getElementById('btn-guardar');
  btnT.style.display = 'none'; btnS.style.display = 'inline-block'; btn.disabled = true;

  apiPost('actualizarReserva', fd).then(data => {
    btnT.style.display = 'inline'; btnS.style.display = 'none'; btn.disabled = false;
    if (data.success) {
      showToast('✔ ' + data.mensaje, 'success');
      closeModal('modal-overlay');
      setTimeout(() => location.reload(), 1200);
    } else {
      if (data.errores) Object.entries(data.errores).forEach(([k, v]) => showErr('edit_', k, v));
      if (data.mensaje) showToast('✖ ' + data.mensaje, 'error');
    }
  }).catch(() => {
    btnT.style.display = 'inline'; btnS.style.display = 'none'; btn.disabled = false;
    showToast('Error de conexión', 'error');
  });
});

// ── Abrir modal cancelar ──────────────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-cancelar');
  if (!btn) return;
  document.getElementById('cancel_id').value = btn.dataset.id;
  openModal('modal-cancel-overlay');
});

// ── Confirmar cancelación ─────────────────────────────────────────────────────
document.getElementById('btn-confirm-cancel').addEventListener('click', function() {
  const id   = document.getElementById('cancel_id').value;
  const btnT = document.getElementById('btn-cancel-texto');
  const btnS = document.getElementById('btn-cancel-spinner');
  this.disabled = true;
  btnT.style.display = 'none'; btnS.style.display = 'inline-block';

  const fd = new FormData();
  fd.append('id', id);

  apiPost('cancelarReserva', fd).then(data => {
    btnT.style.display = 'inline'; btnS.style.display = 'none'; this.disabled = false;
    closeModal('modal-cancel-overlay');
    if (data.success) {
      showToast('✔ ' + data.mensaje, 'success');
      // Actualizar fila en el DOM sin recargar
      const badge = document.getElementById(`badge-${id}`);
      if (badge) { badge.textContent = 'Cancelada'; badge.className = 'badge badge--cancelada'; }
      const fila = document.getElementById(`fila-${id}`);
      if (fila) fila.querySelector('.acciones-cell').innerHTML = '<span class="sin-acciones">—</span>';
    } else {
      showToast('✖ ' + data.mensaje, 'error');
    }
  }).catch(() => {
    btnT.style.display = 'inline'; btnS.style.display = 'none'; this.disabled = false;
    showToast('Error de conexión', 'error');
  });
});
</script>
</body>
</html>
