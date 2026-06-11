<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/usuario.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <header>
        <h1>Usuarios Registrados</h1>
        
        <button class="btn-registrar" id="btnAbrirRegistro">
        <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
        </button>
    </header>

    <!-- Modal Registro -->
    <div class="modal-overlay" id="modalRegistroOverlay"></div>
    <div class="modal-editar" id="modalRegistro">
        <button class="modal-cerrar" id="modalRegistroCerrar">&times;</button>
        <h2 class="modal-title">Nuevo Usuario</h2>
        <form class="modal-form" id="formRegistro">
            <div class="modal-group">
                <label for="reg-nombre">Nombre:</label>
                <input type="text" id="reg-nombre" name="nombre" required>
            </div>
            <div class="modal-group">
                <label for="reg-usuario">Usuario:</label>
                <input type="text" id="reg-usuario" name="usuario" required>
            </div>
            <div class="modal-group">
                <label for="reg-clave">Clave:</label>
                <input type="password" id="reg-clave" name="clave" required>
            </div>
            <div class="modal-group">
                <label for="reg-rol">Rol:</label>
                <select id="reg-rol" name="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="admin">Admin</option>
                    <option value="mesero">Mesero</option>
                    <option value="cocina">Cocinero</option>
                </select>
            </div>
            <button type="submit" class="btn-guardar">Registrar</button>
        </form>
    </div>

    <div class="main-content">
        <div class="table-responsive">
            <?php if (empty($usuarios)): ?>
                <p>No hay usuarios disponibles.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Clave</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['clave']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td>
                                    <button class="btn-editar"
                                        data-id="<?php echo $usuario['id_usuario']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                        data-usuario="<?php echo htmlspecialchars($usuario['usuario']); ?>"
                                        data-clave="<?php echo htmlspecialchars($usuario['clave']); ?>"
                                        data-rol="<?php echo htmlspecialchars($usuario['rol']); ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-eliminar"
                                        data-id="<?php echo $usuario['id_usuario']; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal Editar -->
<div class="modal-overlay" id="modalEditarOverlay"></div>
<div class="modal-editar" id="modalEditar">
    <button class="modal-cerrar" id="modalCerrar">&times;</button>
    <h2 class="modal-title">Editar Usuario</h2>
    <form class="modal-form" id="formEditar">
        <input type="hidden" id="id_usuario" name="id_usuario">
        <div class="modal-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="modal-group">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div class="modal-group">
            <label for="clave">Clave:</label>
            <input type="password" id="clave" name="clave" required>
        </div>
        <div class="modal-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="admin">Admin</option>
                <option value="mesero">Mesero</option>
                <option value="cocina">Cocinero</option>
            </select>
        </div>
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
    </form>
</div>

<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/usuarios.js"></script>
</body>
</html>