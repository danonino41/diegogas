<?php
session_start();
require_once '../../incluidos/conexion_bd.php'; 

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

$clientes = [];
$buscarPor = ''; 
$valorBuscar = ''; 

if (isset($_POST['buscar_cliente'])) {
    $buscarPor = $_POST['buscar_por'];
    $valorBuscar = $_POST['valor_buscar'];

    if ($buscarPor === 'telefono') {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE telefono_cliente = :valor");
    } elseif ($buscarPor === 'direccion') {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE direccion_cliente LIKE :valor");
        $valorBuscar = "%$valorBuscar%"; 
    } else { 
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE nombre_cliente LIKE :valor");
        $valorBuscar = "%$valorBuscar%";
    }

    $stmt->bindParam(':valor', $valorBuscar);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (empty($clientes)) {
    $stmt = $conn->prepare("SELECT * FROM clientes");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once 'navbar_recepcion.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Clientes</h1>
            <form method="POST" class="mb-3">
                <div class="mb-3">
                    <label for="buscar_por" class="form-label"><h3>Buscar Cliente Por:</h3></label>
                    <select id="buscar_por" name="buscar_por" class="form-select" required>
                        <option value="telefono">Teléfono</option>
                        <option value="direccion">Dirección</option>
                        <option value="nombre">Nombre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="valor_buscar" class="form-label">Valor de Búsqueda</label>
                    <input type="text" id="valor_buscar" name="valor_buscar" class="form-control" required>
                    <button type="submit" name="buscar_cliente" class="btn btn-primary mt-3">Buscar Cliente</button>
                    <button class="btn btn-success mt-3" id="addClientBtn" data-bs-toggle="modal" data-bs-target="#modalCliente" onclick="clearForm()">+ Agregar Cliente</button>
                </div>
            </form>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Lista de Clientes
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Descripción</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Coordenadas</th>
                                    <th>Fecha de Registro</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['apellido_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['descripcion_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefono_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['direccion_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['coordenadas_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['fecha_registro_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['email_cliente']); ?></td>
                                    <td>
                                        <button class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalCliente" data-cliente='<?php echo json_encode($cliente); ?>'>Editar</button>
                                        <button class="btn btn-danger delete-btn" data-id="<?php echo $cliente['id_cliente']; ?>">Eliminar</button>
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

<!-- Modal para agregar/editar cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClienteLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cliente-form" method="post" action="../../controladores/RegistrarCliente.php">
                    <input type="hidden" id="IdCliente" name="IdCliente" value="0">
                    <div class="mb-3">
                        <label for="cliente-Nombre">Nombre:</label>
                        <input type="text" id="cliente-Nombre" name="Nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Apellido">Apellido:</label>
                        <input type="text" id="cliente-Apellido" name="Apellido" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Descripcion">Descripción:</label>
                        <textarea id="cliente-Descripcion" name="Descripcion" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Telefono">Teléfono:</label>
                        <input type="text" id="cliente-Telefono" name="Telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Direccion">Dirección:</label>
                        <input type="text" id="cliente-Direccion" name="Direccion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Coordenadas">Coordenadas:</label>
                        <input type="text" id="cliente-Coordenadas" name="Coordenadas" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="cliente-Email">Email:</label>
                        <input type="email" id="cliente-Email" name="Email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Guardar Cliente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mostrar datos en el formulario al hacer clic en Editar
        const editButtons = document.querySelectorAll(".edit-btn");
        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                const cliente = JSON.parse(this.getAttribute("data-cliente"));
                document.getElementById("IdCliente").value = cliente.id_cliente;
                document.getElementById("cliente-Nombre").value = cliente.nombre_cliente;
                document.getElementById("cliente-Apellido").value = cliente.apellido_cliente || ""; // Manejo de NULL
                document.getElementById("cliente-Telefono").value = cliente.telefono_cliente || ""; // Manejo de NULL
                document.getElementById("cliente-Direccion").value = cliente.direccion_cliente;
                document.getElementById("cliente-Coordenadas").value = cliente.coordenadas_cliente || ""; // Manejo de NULL
                document.getElementById("cliente-Email").value = cliente.email_cliente || ""; // Manejo de NULL
                document.getElementById("cliente-Descripcion").value = cliente.descripcion_cliente || ""; // Manejo de NULL
                document.getElementById("cliente-form").action = "../../controladores/EditarCliente.php";
            });
        });

        const deleteButtons = document.querySelectorAll(".delete-btn");
        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const idCliente = this.getAttribute("data-id");
                if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
                    window.location.href = "../../controladores/EliminarCliente.php?id=" + idCliente;
                }
            });
        });

        document.getElementById("addClientBtn").addEventListener("click", function () {
            clearForm();
            document.getElementById("cliente-form").action = "../../controladores/RegistrarCliente.php";
        });

        function clearForm() {
            document.getElementById("IdCliente").value = "0";
            document.getElementById("cliente-Nombre").value = "";
            document.getElementById("cliente-Apellido").value = "";
            document.getElementById("cliente-Telefono").value = "";
            document.getElementById("cliente-Direccion").value = "";
            document.getElementById("cliente-Coordenadas").value = "";
            document.getElementById("cliente-Email").value = ""; 
            document.getElementById("cliente-Descripcion").value = "";
        }
    });
</script>
</body>
</html>
