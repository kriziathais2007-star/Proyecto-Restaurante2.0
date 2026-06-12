# 🍽️ Sistema de Restaurante — Documentación de Estudio

## ¿Qué es este proyecto?

Sistema web para la gestión de un restaurante. Permite manejar pedidos de mesa y para llevar, controlar pagos, administrar la carta (platos y entradas), registrar asistencias del personal y gestionar usuarios con distintos roles.

Está construido **sin frameworks** — PHP puro con patrón **MVC personalizado**.

---

## 🗂️ Estructura del proyecto

```
Proyecto-Restaurante2.0/
├── app/
│   ├── config/
│   │   └── config.php         ← Constantes: BD, BASE_URL
│   ├── core/
│   │   ├── App.php            ← Punto de arranque (inicia sesión + llama al Router)
│   │   ├── Router.php         ← Lee la URL y decide qué controlador/método ejecutar
│   │   ├── Controller.php     ← Clase base con helpers: view(), soloAdmin(), etc.
│   │   └── Database.php       ← Conexión PDO (Singleton)
│   ├── controllers/           ← Reciben la petición y coordinan modelo + vista
│   ├── models/                ← Hablan con la base de datos (SQL con PDO)
│   ├── views/                 ← HTML + PHP que se muestra al usuario
│   └── index.php              ← Único punto de entrada de la app
├── public/
│   ├── css/                   ← Estilos
│   ├── js/                    ← JavaScript del cliente
│   └── uploads/comprobantes/  ← Fotos de comprobantes Yape subidas
├── .env                       ← Variables de entorno (BD, URL)
├── .htaccess                  ← Redirige todas las peticiones a app/index.php
└── README.md
```

---

## 🔄 ¿Cómo funciona el patrón MVC?

**MVC = Modelo — Vista — Controlador**

```
Navegador  →  .htaccess  →  app/index.php  →  Router  →  Controlador  →  Modelo  →  BD
                                                                      ↓
                                                                    Vista  →  Navegador
```

### Flujo paso a paso con ejemplo real

El usuario entra a `/pedidos/croquis`:

1. **`.htaccess`** convierte la URL en `?url=pedidos/croquis` y la manda a `app/index.php`
2. **`App.php`** inicia la sesión y llama al `Router`
3. **`Router.php`** divide la URL: controlador = `Pedidos`, método = `croquis`
4. Carga `PedidosController.php`, crea una instancia y llama a `->croquis()`
5. **`PedidosController::croquis()`** llama al modelo para obtener los datos
6. **`Pedido::obtenerEstadoMesas()`** ejecuta una query SQL y devuelve los datos
7. El controlador llama a `$this->view('pedidos/croquis', $datos)` que renderiza la vista
8. **`croquis.php`** genera el HTML con los datos y lo envía al navegador

---

## 🗃️ Base de Datos — Tablas principales

| Tabla | ¿Para qué sirve? |
|-------|-----------------|
| `usuario` | Personas que usan el sistema (admin, mesero, cocina) |
| `plato` | Platos del menú (precio fijo S/ 8.00) |
| `entrada` | Entradas del menú (S/ 3.00 sueltas o incluidas con plato) |
| `pedido` | Cada pedido creado (Mesa o Llevar, con estado y total) |
| `detalle_pedido` | Items dentro de un pedido (qué plato, qué entrada, cantidad) |
| `detalle_entrada_extra` | Entradas sueltas dentro de un pedido |
| `pago` | Registro de pagos (Efectivo o Yape, con foto de comprobante) |
| `asistencia` | Registro de entrada/salida del personal |

### Relaciones clave

```
usuario ──< pedido ──< detalle_pedido >── plato
                  ├──< detalle_entrada_extra >── entrada
                  └──< pago
usuario ──< asistencia
plato >── detalle_pedido >── entrada  (entrada incluida en el plato)
```

---

## 👤 Roles de usuario

| Rol | ¿Qué puede hacer? |
|-----|-----------------|
| `admin` | Todo: pedidos, pagos, platos, usuarios, asistencias |
| `mesero` | Pedidos (mesa y llevar), pagos, platos, asistencias |
| `cocina` | Ver reportes de pedidos y asistencias |

---

## 🧩 CRUD — ¿Cómo funciona cada operación?

### ¿Qué es CRUD?
- **C**reate → Crear registros
- **R**ead → Leer/listar registros
- **U**pdate → Editar registros
- **D**elete → Eliminar registros

---

### 📋 CRUD de Platos (`/platos`)

**¿De dónde salen los datos?**
`PlatosController::reportes()` → `Plato::obtenerTodos()` → `SELECT * FROM plato`

**Crear un plato:**
1. El usuario llena el formulario modal en la vista
2. El JS hace `fetch POST /platos/guardar` con los datos
3. `PlatosController::guardar()` valida los datos
4. `Plato::guardarPlato()` ejecuta `INSERT INTO plato (...) VALUES (...)`
5. El JS recibe `{ok: true}` y recarga la página

**Editar un plato:**
1. El usuario hace clic en el botón editar — el JS rellena el modal con `data-*` del botón
2. El JS hace `fetch POST /platos/editar`
3. `PlatosController::editar()` llama a `Plato::editarPlato()`
4. Se ejecuta `UPDATE plato SET ... WHERE id_plato = ?`

**Eliminar un plato:**
1. El usuario confirma con SweetAlert
2. `fetch POST /platos/eliminar` con `id_plato`
3. `Plato::eliminarPlato()` ejecuta `DELETE FROM plato WHERE id_plato = ?`

---

### 👥 CRUD de Usuarios (`/usuarios`)

Solo accesible para `admin` (verificado con `$this->soloAdmin()`).

**¿De dónde salen los datos?**
`UsuariosController::reporte()` → `Usuario::obtenerUsuarios()` → `SELECT * FROM usuario`

**Crear usuario:**
`/usuarios/guardar` → `Usuario::existeUsuario()` verifica duplicados → `Usuario::guardarUsuario()` → `INSERT INTO usuario`

**Editar usuario:**
`/usuarios/editar_usuario` → `Usuario::editarUsuario()` → `UPDATE usuario SET ... WHERE id_usuario = ?`

**Eliminar usuario:**
`/usuarios/eliminar_usuario` → `Usuario::eliminarPorIdUsuario()` → `DELETE FROM usuario WHERE id_usuario = ?`

---

### 🍽️ CRUD de Pedidos (`/pedidos`)

**Crear pedido:**
1. Clic en una mesa libre → `GET /pedidos/crear/Mesa/5`
2. `PedidosController::crear()` llama a `Pedido::crearPedido()`
3. `INSERT INTO pedido (id_usuario, tipo, numero_mesa, estado) VALUES (..., 'Pendiente')`
4. Redirige al detalle del pedido recién creado

**Agregar plato al pedido:**
1. Formulario en detalle → `POST /pedidos/agregarPlato/{id}`
2. `Pedido::agregarItemPlato()` → `INSERT INTO detalle_pedido`
3. `Pedido::actualizarTotal()` → `UPDATE pedido SET total = (SUM detalle + SUM extras)`

**Cambiar estado del pedido:**
`POST /pedidos/cambiarEstado/{id}` con `estado=Preparando`
→ `Pedido::cambiarEstado()` → `UPDATE pedido SET estado = ? WHERE id_pedido = ?`

**Flujo de estados:**
```
Pendiente → Preparando → Entregado → Pagado
                                 ↘ Cancelado
```

**Pagar pedido:**
`POST /pedidos/pagar/{id}` (AJAX desde modal de mesa/llevar)
→ `Pedido::pagarPedido()` → `INSERT INTO pago` + `UPDATE pedido SET estado = 'Pagado'`

**Eliminar pedido:**
Solo si no está `Pagado`. Elimina en cascada: `detalle_pedido` → `detalle_entrada_extra` → `pago` → `pedido`

---

### 💰 CRUD de Pagos (`/pagos`)

**Listar pagos:**
`PagosController::index()` → `Pago::obtenerTodos()` → `SELECT` con JOIN a `pedido` y `usuario`

**Registrar pago manual:**
`POST /pagos/guardar` → verifica con `Pago::existePagoPorPedido()` que no haya duplicado → `Pago::guardar()` → `INSERT INTO pago`

Si el método es Yape, sube la foto a `public/uploads/comprobantes/` y guarda el nombre en BD.

**Eliminar pago:**
`POST /pagos/eliminar` → `Pago::eliminar()` → `DELETE FROM pago WHERE id_pago = ?`

---

### 📅 CRUD de Asistencias (`/asistencias`)

**Registrar entrada:**
`POST /asistencias/registrarEntrada` (AJAX) → `Asistencia::registrarEntrada()` → `INSERT INTO asistencia (id_usuario, fecha, hora_entrada)`

**Registrar salida:**
`POST /asistencias/registrarSalida` → `Asistencia::registrarSalida()` → `UPDATE asistencia SET hora_salida = ? WHERE id_usuario = ? AND fecha = ? AND hora_salida IS NULL`

**Eliminar asistencia:**
`POST /asistencias/eliminar` → `Asistencia::eliminarAsistencia()` → `DELETE FROM asistencia WHERE id_asistencia = ?`

---

## 🔐 Seguridad básica

- **Sesiones PHP**: Cada controlador verifica `$_SESSION['usuario']` antes de mostrar datos
- **Roles**: `$this->soloAdmin()` en `Controller.php` redirige si el rol no es `admin`
- **PDO con parámetros**: Todas las queries usan `prepare()` + `execute([...])` para evitar SQL Injection
- **`htmlspecialchars()`**: Usado en las vistas para evitar XSS al mostrar datos

---

## 🌐 ¿Cómo funciona el Router?

```php
// URL: /pedidos/croquis
// .htaccess lo convierte en: app/index.php?url=pedidos/croquis

$partes = explode('/', 'pedidos/croquis');
// $partes[0] = 'pedidos'  → controlador: PedidosController
// $partes[1] = 'croquis'  → método: croquis()
// $partes[2...] = []      → parámetros: ninguno

// URL con parámetro: /pedidos/crear/Mesa/5
// $partes[0] = 'pedidos'  → PedidosController
// $partes[1] = 'crear'    → crear()
// $partes[2] = 'Mesa'     → $tipo
// $partes[3] = '5'        → $numeroMesa
```

---

## 🔌 ¿Cómo funciona la base de datos? (`Database.php`)

Usa el patrón **Singleton** para que solo exista una conexión durante toda la petición:

```php
// Obtener la conexión (siempre la misma instancia)
$db = Database::getConnection();

// Usar la conexión con PDO
$stmt = $db->prepare("SELECT * FROM plato WHERE id_plato = ?");
$stmt->execute([5]);
$plato = $stmt->fetch(PDO::FETCH_ASSOC);
```

---

## 📡 Peticiones AJAX

Varias acciones usan `fetch()` en JavaScript para no recargar la página:

```
Vista (JS)  →  fetch POST /ruta/accion  →  Controlador  →  Modelo  →  BD
                                        ←  json_encode(['ok' => true])
```

El controlador responde con `header('Content-Type: application/json')` + `echo json_encode(...)`.

Ejemplo: eliminar un plato, registrar un pago, cambiar estado de pedido.

---

## 🏗️ Archivos clave para entender el sistema

| Archivo | ¿Por qué es importante? |
|---------|------------------------|
| `app/core/Router.php` | Decide qué código ejecutar según la URL |
| `app/core/Database.php` | Única conexión a la BD en toda la app |
| `app/core/Controller.php` | Clase base que heredan todos los controladores |
| `app/controllers/PedidosController.php` | El más completo — tiene todo el flujo de pedidos |
| `app/models/Pedido.php` | Todas las queries relacionadas a pedidos |
| `app/views/layouts/sidebar-dashboard.php` | Layout compartido por todas las vistas del panel |
| `.htaccess` | Redirige todo al punto de entrada único |
| `.env` | Configuración del entorno (no subir a Git) |
