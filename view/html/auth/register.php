<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro — Grand Éclat Hotel</title>
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

  <div class="booking-card" style="max-width:600px; width:100%;">

    <div class="booking-card__header">
      <span class="booking-card__label">Bienvenido</span>
      <h2 class="booking-card__title">Crear cuenta</h2>
    </div>

    <form id="registerForm" method="POST" action="<?= SITE_URL ?>index.php?action=registerUser" class="booking-form" novalidate>

      <!-- Alerta general PHP (servidor) -->
      <?php if (!empty($errores['general'])): ?>
        <div class="alert-error"><?= htmlspecialchars($errores['general']) ?></div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <!-- Alerta general JS (se muestra solo si JS detecta errores) -->
      <div class="alert-error" id="js-alert-general" style="display:none;">
        Por favor corrige los errores antes de continuar.
      </div>

      <!-- Tipo de documento -->
      <div class="form-group form-group--full">
        <label for="document_type_id">Tipo de documento</label>
        <select id="document_type_id" name="document_type_id"
                class="<?= !empty($errores['document_type_id']) ? 'input-error' : '' ?>">
          <option value="">Seleccione</option>
          <option value="1" <?= ($old['document_type_id'] ?? '') == '1' ? 'selected' : '' ?>>Cédula de ciudadanía</option>
          <option value="2" <?= ($old['document_type_id'] ?? '') == '2' ? 'selected' : '' ?>>Cédula de extranjería</option>
          <option value="3" <?= ($old['document_type_id'] ?? '') == '3' ? 'selected' : '' ?>>Tarjeta de identidad</option>
        </select>
        <!-- Error PHP -->
        <?php if (!empty($errores['document_type_id'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['document_type_id']) ?></span>
        <?php endif; ?>
        <!-- Error JS -->
        <span class="form-error js-error" id="err-document_type_id"></span>
      </div>

      <!-- Número de documento -->
      <div class="form-group form-group--full">
        <label for="document_number">Número de documento</label>
        <input type="text" id="document_number" name="document_number"
               placeholder="123456789"
               value="<?= htmlspecialchars($old['document_number'] ?? '') ?>"
               class="<?= !empty($errores['document_number']) ? 'input-error' : '' ?>" />
        <?php if (!empty($errores['document_number'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['document_number']) ?></span>
        <?php endif; ?>
        <span class="form-error js-error" id="err-document_number"></span>
      </div>

      <!-- Nombre -->
      <div class="form-group form-group--full">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre"
               placeholder="Tu nombre"
               value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
               class="<?= !empty($errores['nombre']) ? 'input-error' : '' ?>" />
        <?php if (!empty($errores['nombre'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['nombre']) ?></span>
        <?php endif; ?>
        <span class="form-error js-error" id="err-nombre"></span>
      </div>

      <!-- Correo -->
      <div class="form-group form-group--full">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo"
               placeholder="correo@email.com"
               value="<?= htmlspecialchars($old['correo'] ?? '') ?>"
               class="<?= !empty($errores['correo']) ? 'input-error' : '' ?>" />
        <?php if (!empty($errores['correo'])): ?>
          <span class="form-error"><?= htmlspecialchars($errores['correo']) ?></span>
        <?php endif; ?>
        <span class="form-error js-error" id="err-correo"></span>
      </div>

      <!-- Contraseñas -->
      <div class="form-row">

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password"
                 placeholder="Mínimo 6 caracteres"
                 class="<?= !empty($errores['password']) ? 'input-error' : '' ?>" />
          <?php if (!empty($errores['password'])): ?>
            <span class="form-error"><?= htmlspecialchars($errores['password']) ?></span>
          <?php endif; ?>
          <span class="form-error js-error" id="err-password"></span>
        </div>

        <div class="form-group">
          <label for="confirmar_password">Confirmar</label>
          <input type="password" id="confirmar_password" name="confirmar_password"
                 placeholder="Repita la contraseña"
                 class="<?= !empty($errores['confirmar_password']) ? 'input-error' : '' ?>" />
          <?php if (!empty($errores['confirmar_password'])): ?>
            <span class="form-error"><?= htmlspecialchars($errores['confirmar_password']) ?></span>
          <?php endif; ?>
          <span class="form-error js-error" id="err-confirmar_password"></span>
        </div>

      </div>

      <div class="form-footer">
        <p class="form-note">Al registrarte aceptas nuestros términos y políticas.</p>
        <button type="submit" class="btn-reserve">Registrarse &rarr;</button>
      </div>

    </form>
  </div>
</section>

<script src="<?= SITE_URL ?>view/js/validaciones.js"></script>