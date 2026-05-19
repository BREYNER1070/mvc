<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login — Grand Éclat Hotel</title>
  <link rel="stylesheet" href="<?= SITE_URL ?>view/img/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body>

<?php
  $errores = $_SESSION['errors'] ?? [];
  $old     = $_SESSION['old']    ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);
?>

<section class="booking-section" style="justify-content:center; padding-top: 4rem;">
  <div class="booking-card" style="max-width:480px; width:100%;">

    <div class="booking-card__header">
      <span class="booking-card__label">Acceso</span>
      <h2 class="booking-card__title">Iniciar sesión</h2>
    </div>

    <form id="loginForm" method="POST" action="<?= SITE_URL ?>index.php?action=loginUser" class="booking-form">

      <?php if (!empty($errores['general'])): ?>
        <div class="alert-error"><?= htmlspecialchars($errores['general']) ?></div>
      <?php endif; ?>

      <div id="js-alert-general" class="alert-error" style="display:none;">
        Por favor corrige los errores antes de continuar.
      </div>

      <!-- Correo -->
      <div class="form-group form-group--full">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo"
               placeholder="correo@email.com"
               value="<?= htmlspecialchars($old['correo'] ?? '') ?>" />
        <?php if (!empty($errores['correo'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['correo']) ?></span>
        <?php endif; ?>
        <span class="form-error js-error" id="err-correo"></span>
      </div>

      <!-- Contraseña -->
      <div class="form-group form-group--full">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="********" />
        <?php if (!empty($errores['password'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['password']) ?></span>
        <?php endif; ?>
        <span class="form-error js-error" id="err-password"></span>
      </div>

      <div class="form-footer">
        <p class="form-note">
          ¿No tiene cuenta? <a href="<?= SITE_URL ?>index.php?action=getFormRegisterUser">Regístrese aquí</a>
        </p>
        <button type="submit" class="btn-reserve">Ingresar &rarr;</button>
        
      </div>

    </form>
  </div>
</section>

<script src="<?= SITE_URL ?>view/js/validacionesLogin.js"></script>
</body>
</html>