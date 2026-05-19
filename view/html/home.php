<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservación — Grand Éclat Hotel</title>
    <link rel="stylesheet" href="view/img/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<script>
  window.addEventListener('scroll', () => {
    document.querySelector('.nav').classList.toggle('scrolled', window.scrollY > 20);
  });
</script>
<body>
<nav class="nav nav--solid">
  <!-- NAV -->
  <ul class="nav__links">
    <li><a href="#">Habitaciones</a></li>
    <li><a href="#">Experiencias</a></li>
    <li><a href="#">Gastronomía</a></li>
    <?php if (isset($_SESSION['usuario'])): ?>
      <li>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></li>
      <li class ><a href="<?= SITE_URL ?>index.php?action=getFormReserva" class="nav__cta">Reservar</a></li>
      <li><a href="<?= SITE_URL ?>index.php?action=logoutUser">Cerrar sesión</a></li>
    <?php else: ?>
      <li><a href="<?= SITE_URL ?>index.php?action=getFormLoginUser" class="nav__cta">Iniciar sesión</a></li>
    <?php endif; ?>
  </ul>
</nav>

  <!-- HERO --> 
  <header class="hero">
    <div class="hero__overlay"></div>
    <div class="hero__content">
      <p class="hero__tag">Cartagena de Indias · Colombia</p>
      <h1 class="hero__title">Una estancia<br/><em>inolvidable</em></h1>
      <p class="hero__sub">Descubra el lujo donde el mar Caribe y la historia colonial se encuentran.</p>
    </div>
    <div class="hero__scroll">↓</div>
  </header>

  <!-- BOOKING FORM -->
  <section class="booking-section">
    <div class="booking-card">

      <div class="booking-card__header">
        <span class="booking-card__label">Reserve su habitación</span>
        <h2 class="booking-card__title">Comience su escapada</h2>
      </div>

      <form class="booking-form" novalidate>

        <!-- Fila 1 -->
        <div class="form-row">
          <div class="form-group">
            <label for="check-in">Llegada</label>
            <div class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              <input type="date" id="check-in" name="check-in" required />
            </div>
          </div>
          <div class="form-group">
            <label for="check-out">Salida</label>
            <div class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              <input type="date" id="check-out" name="check-out" required />
            </div>
          </div>
        </div>

        <!-- Fila 2 -->
        <div class="form-row">
          <div class="form-group">
            <label for="adults">Adultos</label>
            <div class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <select id="adults" name="adults">
                <option>1 adulto</option>
                <option selected>2 adultos</option>
                <option>3 adultos</option>
                <option>4 adultos</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="children">Niños</label>
            <div class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="7" r="4"/><path d="M6 21v-1a6 6 0 0 1 12 0v1"/></svg>
              <select id="children" name="children">
                <option selected>Sin niños</option>
                <option>1 niño</option>
                <option>2 niños</option>
                <option>3 niños</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Tipo de habitación -->
        <div class="form-group form-group--full">
          <label for="room-type">Tipo de habitación</label>
          <div class="input-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
            <select id="room-type" name="room-type">
              <option>Suite Clásica — Vista ciudad</option>
              <option>Suite Deluxe — Vista mar</option>
              <option>Suite Junior — Balcón privado</option>
              <option>Suite Presidencial — Terraza y jacuzzi</option>
            </select>
          </div>
        </div>

        <!-- Servicios adicionales -->
        <div class="form-group form-group--full">
          <label>Servicios adicionales</label>
          <div class="extras-grid">
            <label class="extra-item">
              <input type="checkbox" name="extras" value="spa" />
              <span class="extra-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2C4 17.5 12 22 12 22z"/></svg>
                Spa & Wellness
              </span>
            </label>
            <label class="extra-item">
              <input type="checkbox" name="extras" value="breakfast" checked />
              <span class="extra-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                Desayuno incluido
              </span>
            </label>
            <label class="extra-item">
              <input type="checkbox" name="extras" value="transfer" />
              <span class="extra-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h5l3 3v5h-8V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Traslado aeropuerto
              </span>
            </label>
            <label class="extra-item">
              <input type="checkbox" name="extras" value="tour" />
              <span class="extra-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                Tour ciudad amurallada
              </span>
            </label>
          </div>
        </div>

        <!-- Datos personales -->
        <div class="form-divider"><span>Datos del huésped</span></div>

        <div class="form-row">
          <div class="form-group">
            <label for="first-name">Nombre</label>
            <input type="text" id="first-name" name="first-name" placeholder="María" required />
          </div>
          <div class="form-group">
            <label for="last-name">Apellido</label>
            <input type="text" id="last-name" name="last-name" placeholder="González" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" placeholder="maria@correo.com" required />
          </div>
          <div class="form-group">
            <label for="phone">Teléfono</label>
            <input type="tel" id="phone" name="phone" placeholder="+57 300 000 0000" />
          </div>
        </div>

        <div class="form-group form-group--full">
          <label for="requests">Solicitudes especiales</label>
          <textarea id="requests" name="requests" rows="3" placeholder="Cama king, piso alto, flores de bienvenida..."></textarea>
        </div>

        <div class="form-footer">
          <p class="form-note">Cancelación gratuita hasta 48 h antes. Sin cargos ocultos.</p>
          <button type="submit" class="btn-reserve">
            Confirmar reservación
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>

      </form>
    </div>

    <!-- Lateral info -->
    <aside class="booking-aside">
      <div class="aside-card">
        <div class="aside-badge">★ 4.9 / 5</div>
        <h3>¿Por qué Grand Éclat?</h3>
        <ul class="aside-list">
          <li>
            <span class="aside-icon">✦</span>
            <span>Mejor precio garantizado directamente con el hotel</span>
          </li>
          <li>
            <span class="aside-icon">✦</span>
            <span>Check-in exprés sin filas y conserjería 24/7</span>
          </li>
          <li>
            <span class="aside-icon">✦</span>
            <span>Acceso prioritario al spa y piscina panorámica</span>
          </li>
          <li>
            <span class="aside-icon">✦</span>
            <span>Wifi de alta velocidad y minibar de cortesía</span>
          </li>
        </ul>
      </div>

      <div class="aside-image">
        <div class="aside-image__overlay">
          <p>"La terraza al atardecer fue un momento que guardaré para siempre."</p>
          <span>— Luciana R., Bogotá</span>
        </div>
      </div>
    </aside>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer__brand">Grand <em>Éclat</em></div>
    <p>© 2026 Grand Éclat Hotel · Cartagena de Indias, Colombia</p>
    <div class="footer__links">
      <a href="#">Privacidad</a>
      <a href="#">Términos</a>
      <a href="#">Contacto</a>
    </div>
  </footer>
  

</body>
</html>