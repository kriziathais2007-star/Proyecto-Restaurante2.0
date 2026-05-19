<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- TITLE_BUSINESS es la constante que definimos en config/config.php -->
    <title><?php echo TITLE_BUSINESS; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/login.css">
</head>

<body>

    <!-- Si el controlador encontró un error, lo mostramos aquí.
         htmlspecialchars() convierte caracteres peligrosos (< > " &) a texto plano
         para evitar que alguien inyecte código HTML o JavaScript malicioso. -->
    <?php if (isset($error) && $error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
<!-- ===== LOGIN SCREEN ===== -->
<div class="login-screen" id="loginScreen">
  <div class="login-left">
    <div class="login-logo-wrap">
      <div class="login-logo-circle">
        <img src="<?php echo BASE_URL; ?>/public/recursos/logo.png" alt="Logo">
      </div>
      <p class="login-brand">Restaurante<br><strong>Milagros</strong></p>
    </div>
  </div>
  <div class="login-right">
    <div class="login-box">
      <div class="login-avatar"><i class="fa-solid fa-user"></i></div>
      <h2 class="login-title">INICIAR SESIÓN</h2>
      <div class="login-field">
        <label>USUARIO:</label>
        <input type="text" id="inputUser" placeholder="Tu usuario" autocomplete="off">
      </div>
      <div class="login-field">
        <label>CONTRASEÑA:</label>
        <input type="password" id="inputPass" placeholder="••••••••">
      </div>
      <button class="btn-ingresar" id="btnLogin">INGRESAR</button>
      <p class="login-error" id="loginError"></p>
    </div>
  </div>
</div>



</body>

</html>