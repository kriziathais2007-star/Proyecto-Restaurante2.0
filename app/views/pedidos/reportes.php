<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/../public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/../public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/../public/css/pedido-style.css">
</head>
<body>

    <?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

    <main>
        <nav class="breadcrumb">
            <span>Dashboard</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Pedidos</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span id="breadcrumb-page">Reportes</span>
        </nav>

        <div class="main-content">

            <div class="reporte-header">
                <h2 class="reporte-titulo">
                    <i class="fa-solid fa-receipt" style="margin-right:8px; color:#d4597e;"></i>
                    Pedidos
                </h2>
                <a href="<?php echo BASE_URL; ?>/pedidos/registro" class="btn-nuevo">
                    <i class="fa-solid fa-plus"></i> Nuevo Pedido
                </a>
            </div>

            <?php if (empty($pedidos)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <p>No hay pedidos registrados aún.</p>
                </div>

            <?php else: ?>
                <div class="table-responsive">
                    <table class="tabla-pedidos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mesa</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Platos</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Empleado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $p): ?>
                                <?php
                                    $claseEstado = match($p['estado_pedido']) {
                                        'Pendiente'      => 'est-pendiente',
                                        'En preparacion' => 'est-preparacion',
                                        'Servido'        => 'est-servido',
                                        'Entregado'      => 'est-entregado',
                                        default          => '',
                                    };

                                    $horaFormateada = (!empty($p['hora_pedido']) && $p['hora_pedido'] !== '0000-00-00 00:00:00')
                                        ? date('H:i', strtotime($p['hora_pedido']))
                                        : '—';
                                ?>
                                <tr>
                                    <td><?php echo $p['Id_Pedido']; ?></td>

                                    <td>
                                        <span class="mesa-badge">
                                            Mesa <?php echo $p['mesa']; ?>
                                        </span>
                                    </td>

                                    <td><?php echo date('d/m/Y', strtotime($p['fecha'])); ?></td>

                                    <td><?php echo $horaFormateada; ?></td>

                                    <td>
                                        <?php if (!empty($p['platos'])): ?>
                                            <div class="platos-lista">
                                                <?php foreach (explode('||', $p['platos']) as $item): ?>
                                                    <div class="plato-linea">
                                                        <span><?php echo htmlspecialchars($item); ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:#c47a95; font-size:12px;">Sin platos</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="total-precio">
                                            $<?php echo number_format($p['total'] ?? 0, 2); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <form class="estado-form"
                                              action="<?php echo BASE_URL; ?>/pedidos/cambiarEstado"
                                              method="POST">
                                            <input type="hidden" name="Id_Pedido"
                                                   value="<?php echo $p['Id_Pedido']; ?>">
                                            <select name="estado_pedido"
                                                    class="estado-select <?php echo $claseEstado; ?>"
                                                    onchange="this.form.submit()">
                                                <?php
                                                $estados = ['Pendiente', 'En preparacion', 'Servido', 'Entregado'];
                                                foreach ($estados as $e):
                                                    $sel = ($p['estado_pedido'] === $e) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $e; ?>" <?php echo $sel; ?>>
                                                        <?php echo $e; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>

                                    <td><?php echo htmlspecialchars($p['nombre_usuario']); ?></td>

                                    <td>
                                        <button class="btn-accion btn-editar" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn-accion btn-eliminar" title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <script src="<?php echo BASE_URL; ?>/../public/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.estado-select').forEach(sel => {
            sel.addEventListener('change', function () {
                const map = {
                    'Pendiente':      'est-pendiente',
                    'En preparacion': 'est-preparacion',
                    'Servido':        'est-servido',
                    'Entregado':      'est-entregado',
                };
                this.className = 'estado-select ' + (map[this.value] || '');
            });
        });
    </script>
</body>
</html>