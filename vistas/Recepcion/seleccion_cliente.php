<?php
// Gestión de clientes y selección en sesión
$clientes = [];
$clienteSeleccionado = $_SESSION['cliente_seleccionado'] ?? null;
$mensaje = ''; // Mensaje para mostrar alertas

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buscar clientes
    if (isset($_POST['buscar_cliente'])) {
        $busqueda = $_POST['busqueda_cliente'];
        $clientes = obtenerClientesConDetalles($busqueda);

        if (empty($clientes)) {
            $mensaje = "No se encontraron clientes con el criterio de búsqueda.";
        }
    }

    // Seleccionar cliente
    if (isset($_POST['seleccionar_cliente'])) {
        $id_cliente = $_POST['id_cliente'];
        $clienteSeleccionado = obtenerClientePorId($id_cliente);
        $_SESSION['cliente_seleccionado'] = $clienteSeleccionado;
    }

    // Quitar cliente
    if (isset($_POST['quitar_cliente'])) {
        unset($_SESSION['cliente_seleccionado']);
        $clienteSeleccionado = null;
    }
}
?>

<!-- Mostrar selección de cliente -->
<div>
    <h2>Seleccionar Cliente</h2>

    <!-- Mostrar mensaje de alerta si no se encuentran clientes -->
    <?php if ($mensaje): ?>
        <div class="alert alert-warning">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <!-- Buscar Cliente -->
    <form method="POST" action="#clientes">
        <div class="input-group mb-3">
            <input type="text" name="busqueda_cliente" class="form-control" placeholder="Buscar cliente por nombre, DNI o teléfono" required>
            <button class="btn btn-primary" type="submit" name="buscar_cliente">Buscar</button>
        </div>
    </form>

    <?php if ($clienteSeleccionado): ?>
        <!-- Cliente Seleccionado -->
        <div class="card mb-4">
            <div class="card-header">Cliente Seleccionado</div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($clienteSeleccionado['nombre_cliente']); ?></p>
                <p><strong>DNI:</strong> <?php echo htmlspecialchars($clienteSeleccionado['dni_cliente']); ?></p>
                <p><strong>Teléfono Principal:</strong> <?php echo htmlspecialchars($clienteSeleccionado['telefono_principal']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($clienteSeleccionado['email_cliente']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($clienteSeleccionado['descripcion_cliente'] ?? 'Sin descripción'); ?></p>

                <!-- Selección de Dirección -->
                <div class="mb-3">
                    <label for="direccion_cliente" class="form-label">Dirección de Envío</label>
                    <select name="direccion_cliente" id="direccion_cliente" class="form-select">
                        <?php foreach ($clienteSeleccionado['direcciones'] as $direccion): ?>
                            <option value="<?php echo htmlspecialchars($direccion['direccion']); ?>" <?php echo $direccion['es_principal'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($direccion['direccion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selección de Teléfono -->
                <div class="mb-3">
                    <label for="telefono_cliente" class="form-label">Teléfono</label>
                    <select name="telefono_cliente" id="telefono_cliente" class="form-select">
                        <?php foreach ($clienteSeleccionado['telefonos'] as $telefono): ?>
                            <option value="<?php echo htmlspecialchars($telefono['telefono']); ?>" <?php echo $telefono['es_principal'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($telefono['telefono']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botón para quitar cliente -->
                <form method="POST" action="#clientes">
                    <button type="submit" class="btn btn-warning" name="quitar_cliente">Quitar Cliente</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Lista de Clientes -->
        <div class="card">
            <div class="card-header">Clientes Encontrados</div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <?php if (!empty($clientes)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Teléfono Principal</th>
                                <th>Dirección Principal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['apellido_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['dni_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefono_principal']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['direccion_principal']); ?></td>
                                    <td>
                                        <form method="POST" action="#clientes">
                                            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                                            <button class="btn btn-success btn-sm" type="submit" name="seleccionar_cliente">Seleccionar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No se encontraron clientes.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
