<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

// Variables para mensajes y búsqueda
$mensaje = '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$clientes = obtenerClientesConTelefonoPrincipal($busqueda);

// Agregar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'] ?? null;
    $telefono = $_POST['telefono'];
    $dni_cliente = $_POST['dni_cliente'] ?? null;
    $email = $_POST['email'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $direccion = $_POST['direccion'] ?? null;

    if (empty($dni_cliente) || !preg_match('/^\d+$/', $dni_cliente) || strlen($dni_cliente) > 20) {
        $mensaje = "El DNI no es válido. Debe ser un número y no exceder 20 caracteres.";
    } elseif (verificarDniDuplicado($dni_cliente)) {
        $mensaje = "El DNI ya está registrado. No se puede agregar el cliente.";
    } else {
        $id_cliente = agregarCliente($nombre, $apellido, $dni_cliente, $email, $descripcion, $telefono, $direccion);

        if ($id_cliente) {
            $mensaje = "Cliente agregado correctamente.";
        } else {
            $mensaje = "Error al agregar cliente.";
        }
    }

    $clientes = obtenerClientesConTelefonoPrincipal(); // Actualizar lista de clientes
}


// Verificar si un DNI está duplicado (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar_dni'])) {
    $dni_cliente = $_POST['dni_cliente'];
    if (verificarDniDuplicado($dni_cliente)) {
        echo "duplicado";
    } else {
        echo "disponible";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>
    <div class="container mt-4">
        <h1>Clientes</h1>

        <!-- Mensaje -->
        <?php if ($mensaje): ?>
            <div class="alert alert-danger" id="alertaDNI">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="clientes_recepcion.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar cliente por nombre, teléfono, DNI o dirección" value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>

        <!-- Botón para agregar cliente -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarEditar">
            <i class="fas fa-plus"></i> Agregar Cliente
        </button>

        <!-- Tabla de clientes -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Lista de Clientes
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Teléfono Principal</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                                    <td><?php echo !empty($cliente['apellido_cliente'])? htmlspecialchars($cliente['apellido_cliente']): 'No registrado';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cliente['dni_cliente'] ?? 'No registrado'); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefono_principal'] ?? 'Sin teléfono'); ?></td>
                                    <td><?php echo !empty($cliente['email_cliente'])? htmlspecialchars($cliente['email_cliente']): 'No registrado';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cliente['direccion_principal'] ?? 'No registrado'); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['fecha_registro_cliente']); ?></td>
                                    <td>
                                        <a href="detalles_cliente.php?id_cliente=<?php echo $cliente['id_cliente']; ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-info-circle"></i> Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar o editar cliente -->
    <div class="modal fade" id="modalAgregarEditar" tabindex="-1" aria-labelledby="modalAgregarEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formAgregarCliente" method="POST" action="clientes_recepcion.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarEditarLabel">Agregar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="alertaError" class="alert alert-danger" style="display:none;"></div>
                        <input type="hidden" id="cliente-id" name="id">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="cliente-nombre" name="nombre" required>
                            <small class="text-danger" id="error-nombre" style="display:none;"></small>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="cliente-apellido" name="apellido">
                            <small class="text-danger" id="error-apellido" style="display:none;"></small>
                        </div>
                        <div class="mb-3">
                            <label for="dni_cliente" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="cliente-dni" name="dni_cliente" required>
                            <small class="text-danger" id="error-dni" style="display:none;"></small>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="cliente-telefono" name="telefono">
                            <small class="text-danger" id="error-telefono" style="display:none;"></small>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="cliente-direccion" name="direccion">
                            <small class="text-danger" id="error-direccion" style="display:none;"></small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="cliente-email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="cliente-descripcion" name="descripcion"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="agregar_cliente">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#cliente-nombre').on('input', function() {
                const nombre = $(this).val();
                if (!/^[a-zA-Z\sáéíóúÁÉÍÓÚ]+$/.test(nombre)) {
                    $('#error-nombre').text('El nombre solo puede contener letras y espacios.').show();
                } else {
                    $('#error-nombre').hide();
                }
            });

            $('#cliente-apellido').on('input', function() {
                const apellido = $(this).val();
                if (!/^[a-zA-Z\sáéíóúÁÉÍÓÚ]+$/.test(apellido)) {
                    $('#error-apellido').text('El apellido solo puede contener letras y espacios.').show();
                } else {
                    $('#error-apellido').hide();
                }
            });

            $('#cliente-telefono').on('input', function() {
                const telefono = $(this).val();
                if (!/^[0-9]+$/.test(telefono)) {
                    $('#error-telefono').text('El teléfono solo puede contener números.').show();
                } else {
                    $('#error-telefono').hide();
                }
            });

            $('#cliente-dni').on('input', function() {
                const dni = $(this).val();
                if (!/^\d+$/.test(dni)) {
                    $('#error-dni').text('El DNI solo puede contener números.').show();
                } else {
                    $('#error-dni').hide();
                }

                if (dni.length >= 8) {
                    $.post('clientes_recepcion.php', {
                        verificar_dni: true,
                        dni_cliente: dni
                    }, function(data) {
                        if (data === 'duplicado') {
                            $('#error-dni').text('El DNI ya está registrado.').show();
                        } else {
                            $('#error-dni').hide();
                        }
                    });
                }
            });

            $('#cliente-direccion').on('input', function() {
                const direccion = $(this).val();
                if (direccion.length < 5) {
                    $('#error-direccion').text('La dirección debe tener al menos 5 caracteres.').show();
                } else {
                    $('#error-direccion').hide();
                }
            });

            $('#formAgregarCliente').on('submit', function(event) {
                if ($('#error-nombre').is(':visible') || $('#error-apellido').is(':visible') ||
                    $('#error-dni').is(':visible') || $('#error-direccion').is(':visible')) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>