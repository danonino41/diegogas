<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

$mensaje = '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$clientes = obtenerClientes($busqueda);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $coordenadas = $_POST['coordenadas'];
    $email = $_POST['email'];
    $descripcion = $_POST['descripcion'];

    if (agregarCliente($nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion)) {
        $mensaje = "Cliente agregado correctamente.";
    } else {
        $mensaje = "Error al agregar cliente.";
    }
    $clientes = obtenerClientes();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_cliente'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $coordenadas = $_POST['coordenadas'];
    $email = $_POST['email'];
    $descripcion = $_POST['descripcion'];

    if (actualizarCliente($id, $nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion)) {
        $mensaje = "Cliente actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar cliente.";
    }
    $clientes = obtenerClientes();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    if (eliminarCliente($id)) {
        $mensaje = "Cliente eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar cliente.";
    }
    $clientes = obtenerClientes();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Clientes</h1>
        <?php if ($mensaje): ?>
        <div class="alert alert-info" id="alert" style="opacity: 1;
            transition: opacity 1s ease;">
            <?php echo $mensaje; ?>
        </div>
        <?php endif; ?>

        <form method="GET" action="clientes_recepcion.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar cliente por nombre, teléfono o dirección" value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarEditar">
            <i class="fas fa-plus"></i> Agregar Cliente
        </button>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Lista de Clientes
            </div>
            <div class="card-body" style="max-height: 550px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Descripción</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Coordenadas</th>
                                <th>Email</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($cliente['nombre_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['apellido_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['descripcion_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['telefono_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['direccion_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['coordenadas_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['email_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['fecha_registro_cliente']); ?>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalAgregarEditar"
                                        data-id="<?php echo $cliente['id_cliente']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($cliente['nombre_cliente']); ?>"
                                        data-apellido="<?php echo htmlspecialchars($cliente['apellido_cliente']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($cliente['descripcion_cliente']); ?>"
                                        data-telefono="<?php echo htmlspecialchars($cliente['telefono_cliente']); ?>"
                                        data-direccion="<?php echo htmlspecialchars($cliente['direccion_cliente']); ?>"
                                        data-coordenadas="<?php echo htmlspecialchars($cliente['coordenadas_cliente']); ?>"
                                        data-email="<?php echo htmlspecialchars($cliente['email_cliente']); ?>">

                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <a href="clientes_recepcion.php?eliminar=<?php echo $cliente['id_cliente']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este cliente?');"><i
                                            class="fas fa-trash-alt"></i> Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
    </div>

    <div class="modal fade" id="modalAgregarEditar" tabindex="-1" aria-labelledby="modalAgregarEditarLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="clientes_recepcion.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarEditarLabel">Agregar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="cliente-id" name="id">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="cliente-nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="cliente-apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="cliente-descripcion" name="descripcion"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="cliente-telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="cliente-direccion" name="direccion" required>
                        </div>
                        <div class="mb-3">
                            <label for="coordenadas" class="form-label">Coordenadas</label>
                            <input type="text" class="form-control" id="cliente-coordenadas" name="coordenadas">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="cliente-email" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="agregar_cliente"
                            id="btnAgregarCliente">Guardar</button>
                        <button type="submit" class="btn btn-primary" name="editar_cliente" id="btnEditarCliente"
                            style="display: none;">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalAgregarEditar");
            const clienteIdInput = document.getElementById("cliente-id");
            const clienteNombreInput = document.getElementById("cliente-nombre");
            const clienteApellidoInput = document.getElementById("cliente-apellido");
            const clienteTelefonoInput = document.getElementById("cliente-telefono");
            const clienteDireccionInput = document.getElementById("cliente-direccion");
            const clienteCoordenadasInput = document.getElementById("cliente-coordenadas");
            const clienteEmailInput = document.getElementById("cliente-email");
            const clienteDescripcionInput = document.getElementById("cliente-descripcion");
            const btnAgregarCliente = document.getElementById("btnAgregarCliente");
            const btnEditarCliente = document.getElementById("btnEditarCliente");

            modal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const id = button.getAttribute("data-id");

                if (id) {
                    clienteIdInput.value = id;
                    clienteNombreInput.value = button.getAttribute("data-nombre");
                    clienteApellidoInput.value = button.getAttribute("data-apellido");
                    clienteTelefonoInput.value = button.getAttribute("data-telefono");
                    clienteDireccionInput.value = button.getAttribute("data-direccion");
                    clienteCoordenadasInput.value = button.getAttribute("data-coordenadas");
                    clienteEmailInput.value = button.getAttribute("data-email");
                    clienteDescripcionInput.value = button.getAttribute("data-descripcion");

                    document.getElementById("modalAgregarEditarLabel").textContent = "Editar Cliente";
                    btnAgregarCliente.style.display = "none";
                    btnEditarCliente.style.display = "inline-block";
                } else {
                    clienteIdInput.value = "";
                    clienteNombreInput.value = "";
                    clienteApellidoInput.value = "";
                    clienteTelefonoInput.value = "";
                    clienteDireccionInput.value = "";
                    clienteCoordenadasInput.value = "";
                    clienteEmailInput.value = "";
                    clienteDescripcionInput.value = "";

                    document.getElementById("modalAgregarEditarLabel").textContent = "Agregar Cliente";
                    btnAgregarCliente.style.display = "inline-block";
                    btnEditarCliente.style.display = "none";
                }
            });
        });

        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.opacity = '0';
            }
        },1000);
    </script>
</body>

</html>