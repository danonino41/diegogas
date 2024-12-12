<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

// Obtener el ID del cliente desde la URL
if (!isset($_GET['id_cliente']) || empty($_GET['id_cliente'])) {
    die("ID del cliente no proporcionado.");
}

$id_cliente = $_GET['id_cliente'];

// Obtener datos del cliente
$cliente = obtenerClientePorId($id_cliente);
if (!$cliente) {
    die("Cliente no encontrado.");
}

// Obtener el teléfono principal del cliente
$telefono_principal = obtenerTelefonoPrincipal($id_cliente);

// Obtener las direcciones del cliente
$direcciones = obtenerDireccionesPorCliente($id_cliente);

// Obtener el historial de compras del cliente
$historialCompras = obtenerHistorialComprasCliente($id_cliente);

// Mensaje para retroalimentación
$mensaje = '';

// Manejar actualización de descripción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_descripcion'])) {
    $nueva_descripcion = $_POST['descripcion_cliente'];
    if (actualizarDescripcionCliente($id_cliente, $nueva_descripcion)) {
        $cliente['descripcion_cliente'] = $nueva_descripcion;
        $mensaje = "Descripción actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar la descripción.";
    }
}

// Manejar actualización de cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_cliente'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'] ?? null;
    $telefono = $_POST['telefono'];
    $dni_cliente = $_POST['dni_cliente'] ?? null;
    $email = $_POST['email'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;

    if (actualizarCliente($id_cliente, $nombre, $apellido, $email, $descripcion, $dni_cliente)) {
        // Actualizar teléfono principal
        if (!actualizarTelefonoPrincipal($id_cliente, $telefono)) {
            $mensaje = "Cliente actualizado, pero ocurrió un error al actualizar el teléfono principal.";
        } else {
            $mensaje = "Cliente y teléfono principal actualizados correctamente.";
        }
        $cliente = obtenerClientePorId($id_cliente); // Recargar cliente actualizado
        $telefono_principal = obtenerTelefonoPrincipal($id_cliente);
    } else {
        $mensaje = "Error al actualizar cliente.";
    }
}

// Manejar agregar, editar y eliminar direcciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_direccion'])) {
    $id_direccion = $_POST['id_direccion'];
    $direccion = $_POST['direccion'];
    $coordenadas = $_POST['coordenadas'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $es_principal = isset($_POST['es_principal']) ? true : false;

    if (!empty($id_direccion)) {
        if (actualizarDireccionCliente($id_direccion, $direccion, $coordenadas, $descripcion, $es_principal)) {
            $mensaje = "Dirección actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar la dirección.";
        }
    } else {
        if (agregarDireccionCliente($id_cliente, $direccion, $coordenadas, $descripcion, $es_principal)) {
            $mensaje = "Nueva dirección agregada correctamente.";
        } else {
            $mensaje = "Error al agregar la nueva dirección.";
        }
    }
    $direcciones = obtenerDireccionesPorCliente($id_cliente);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar_direccion'])) {
    $id_direccion = $_GET['eliminar_direccion'];
    if (eliminarDireccionCliente($id_direccion)) {
        $mensaje = "Dirección eliminada correctamente.";
        $direcciones = obtenerDireccionesPorCliente($id_cliente);
    } else {
        $mensaje = "Error al eliminar la dirección.";
    }
}

// Manejar agregar o actualizar teléfono
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_telefono'])) {
    $id_telefono = $_POST['id_telefono'];
    $telefono = $_POST['telefono'];
    $es_principal = isset($_POST['es_principal']) ? true : false;

    if (!empty($id_telefono)) {
        if (actualizarTelefonoCliente($id_telefono, $telefono, $es_principal)) {
            $mensaje = "Teléfono actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el teléfono.";
        }
    } else {
        if (agregarTelefonoCliente($id_cliente, $telefono, $es_principal)) {
            $mensaje = "Nuevo teléfono agregado correctamente.";
        } else {
            $mensaje = "Error al agregar el nuevo teléfono.";
        }
    }
}

// Manejar eliminación de teléfono
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar_telefono'])) {
    $id_telefono = $_GET['eliminar_telefono'];
    if (eliminarTelefonoCliente($id_telefono)) {
        $mensaje = "Teléfono eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el teléfono.";
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Cliente</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Detalles del Cliente</h1><br>
        <?php if ($mensaje): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Información General del Cliente -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Información del Cliente
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente['nombre_cliente'] . ' ' . $cliente['apellido_cliente']); ?></p>
                <p><strong>DNI:</strong> <?php echo htmlspecialchars($cliente['dni_cliente'] ?? 'No registrado'); ?></p>
                <p><strong>Teléfono Principal:</strong> <?php echo htmlspecialchars($telefono_principal ?? 'No registrado'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente['email_cliente'] ?? 'No registrado'); ?></p>
                <form method="POST" action="">
                    <p><strong>Descripción:</strong></p>
                    <textarea name="descripcion_cliente" class="form-control" rows="3"><?php echo htmlspecialchars($cliente['descripcion_cliente'] ?? 'Sin descripción'); ?></textarea>
                    <button type="submit" name="actualizar_descripcion" class="btn btn-warning mt-2">Actualizar Descripción</button>
                </form>
                <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#modalEditarCliente">
                    <i class="fas fa-edit"></i> Editar Cliente
                </button>
            </div>
        </div>

        <!-- Teléfonos del Cliente -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                Teléfonos
            </div>
            <div class="card-body">
                <?php
                $telefonos = obtenerTelefonosPorCliente($id_cliente);
                if (!empty($telefonos)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Teléfono</th>
                                <th>Principal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($telefonos as $telefono): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($telefono['telefono']); ?></td>
                                    <td><?php echo $telefono['es_principal'] ? "Sí" : "No"; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalTelefono"
                                            data-id="<?php echo $telefono['id_telefono']; ?>"
                                            data-telefono="<?php echo htmlspecialchars($telefono['telefono']); ?>"
                                            data-es-principal="<?php echo $telefono['es_principal']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <a href="detalles_cliente.php?id_cliente=<?php echo $id_cliente; ?>&eliminar_telefono=<?php echo $telefono['id_telefono']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este teléfono?');">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron teléfonos asociados al cliente.</p>
                <?php endif; ?>
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalTelefono">
                    <i class="fas fa-plus"></i> Agregar Teléfono
                </button>
            </div>
        </div>

        <!-- Direcciones del Cliente -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                Direcciones
            </div>
            <div class="card-body">
                <?php if (!empty($direcciones)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Dirección</th>
                                <th>Coordenadas</th>
                                <th>Descripción</th>
                                <th>Principal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($direcciones as $direccion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($direccion['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($direccion['coordenadas']); ?></td>
                                    <td><?php echo htmlspecialchars($direccion['descripcion']); ?></td>
                                    <td><?php echo $direccion['es_principal'] ? "Sí" : "No"; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalDireccion"
                                            data-id="<?php echo $direccion['id_direccion']; ?>"
                                            data-direccion="<?php echo htmlspecialchars($direccion['direccion']); ?>"
                                            data-coordenadas="<?php echo htmlspecialchars($direccion['coordenadas']); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($direccion['descripcion']); ?>"
                                            data-es-principal="<?php echo $direccion['es_principal']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <a href="detalles_cliente.php?id_cliente=<?php echo $id_cliente; ?>&eliminar_direccion=<?php echo $direccion['id_direccion']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta dirección?');">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron direcciones asociadas al cliente.</p>
                <?php endif; ?>
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalDireccion">
                    <i class="fas fa-plus"></i> Agregar Dirección
                </button>
            </div>
        </div>

        <!-- Historial de Compras -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Historial de Compras
            </div>
            <div class="card-body">
                <?php if (!empty($historialCompras)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historialCompras as $compra): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($compra['id_pedido']); ?></td>
                                    <td><?php echo htmlspecialchars($compra['fecha_pedido']); ?></td>
                                    <td>S/ <?php echo htmlspecialchars($compra['total_pedido']); ?></td>
                                    <td><?php echo htmlspecialchars($compra['estado']); ?></td>
                                    <td>
                                        <a href="detalles_pedido.php?id_pedido=<?php echo $compra['id_pedido']; ?>" class="btn btn-info">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron compras asociadas al cliente.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modales -->

    <div class="modal fade" id="modalTelefono" tabindex="-1" aria-labelledby="modalTelefonoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="detalles_cliente.php?id_cliente=<?php echo $id_cliente; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTelefonoLabel">Agregar Teléfono</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_telefono" id="telefono-id">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="es_principal" name="es_principal">
                            <label class="form-check-label" for="es_principal">Es principal</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="guardar_telefono">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar direcciones -->
    <div class="modal fade" id="modalDireccion" tabindex="-1" aria-labelledby="modalDireccionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="detalles_cliente.php?id_cliente=<?php echo $id_cliente; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDireccionLabel">Agregar Dirección</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_direccion" id="direccion-id">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="mb-3">
                            <label for="coordenadas" class="form-label">Coordenadas</label>
                            <input type="text" class="form-control" id="coordenadas" name="coordenadas">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="es_principal" name="es_principal">
                            <label class="form-check-label" for="es_principal">Es principal</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="guardar_direccion">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar cliente -->
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="detalles_cliente.php?id_cliente=<?php echo $id_cliente; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre_cliente']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" value="<?php echo htmlspecialchars($cliente['apellido_cliente'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="dni_cliente" class="form-label">DNI</label>
                            <input type="text" class="form-control" name="dni_cliente" value="<?php echo htmlspecialchars($cliente['dni_cliente'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($telefono_principal ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($cliente['email_cliente'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion"><?php echo htmlspecialchars($cliente['descripcion_cliente'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_cliente" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalDireccion");
            const idInput = document.getElementById("direccion-id");
            const direccionInput = document.getElementById("direccion");
            const coordenadasInput = document.getElementById("coordenadas");
            const descripcionInput = document.getElementById("descripcion");
            const esPrincipalInput = document.getElementById("es_principal");

            modal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const id = button.getAttribute("data-id");

                if (id) {
                    idInput.value = id;
                    direccionInput.value = button.getAttribute("data-direccion");
                    coordenadasInput.value = button.getAttribute("data-coordenadas");
                    descripcionInput.value = button.getAttribute("data-descripcion");
                    esPrincipalInput.checked = button.getAttribute("data-es-principal") === "1";
                    document.getElementById("modalDireccionLabel").textContent = "Editar Dirección";
                } else {
                    idInput.value = "";
                    direccionInput.value = "";
                    coordenadasInput.value = "";
                    descripcionInput.value = "";
                    esPrincipalInput.checked = false;
                    document.getElementById("modalDireccionLabel").textContent = "Agregar Dirección";
                }
            });
        });
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalTelefono");
            const idInput = document.getElementById("telefono-id");
            const telefonoInput = document.getElementById("telefono");
            const esPrincipalInput = document.getElementById("es_principal");

            modal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const id = button.getAttribute("data-id");

                if (id) {
                    idInput.value = id;
                    telefonoInput.value = button.getAttribute("data-telefono");
                    esPrincipalInput.checked = button.getAttribute("data-es-principal") === "1";
                    document.getElementById("modalTelefonoLabel").textContent = "Editar Teléfono";
                } else {
                    idInput.value = "";
                    telefonoInput.value = "";
                    esPrincipalInput.checked = false;
                    document.getElementById("modalTelefonoLabel").textContent = "Agregar Teléfono";
                }
            });
        });
    </script>

    <script src="../../recursos/js/cliente.js"></script>
</body>

</html>