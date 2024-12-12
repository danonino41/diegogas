<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$usuario = obtenerDatosUsuario($id_usuario);

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_contrasena'])) {
    $contrasena_actual = $_POST['contrasena_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($nueva_contrasena !== $confirmar_contrasena) {
        $mensaje = "La nueva contraseña y la confirmación no coinciden.";
    } else {
        if (verificarContrasena($id_usuario, $contrasena_actual)) {
            if (cambiarContrasena($id_usuario, $nueva_contrasena)) {
                $mensaje = "Contraseña actualizada correctamente.";
            } else {
                $mensaje = "Error al actualizar la contraseña.";
            }
        } else {
            $mensaje = "Contraseña actual incorrecta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'navbar_recepcion.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Perfil de Usuario</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Información del Usuario -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title text-center"><i class="fas fa-user-circle"></i> Información del Usuario</h4>
                    <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($usuario['nombre_usuario'] ?? 'No disponible'); ?></p>
                    <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($usuario['fecha_registro_usuario'] ?? 'No disponible'); ?></p>
                </div>
            </div>
        </div>

        <!-- Información del Empleado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title text-center"><i class="fas fa-id-badge"></i> Información del Empleado</h4>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre_empleado'] ?? 'No disponible'); ?></p>
                    <p><strong>Apellido:</strong> <?php echo htmlspecialchars($usuario['apellido_empleado'] ?? 'No disponible'); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono_empleado'] ?? 'No disponible'); ?></p>
                    <p><strong>Tipo de Documento:</strong> <?php echo htmlspecialchars($usuario['tipo_documento'] ?? 'No disponible'); ?></p>
                    <p><strong>Número de Documento:</strong> <?php echo htmlspecialchars($usuario['numero_documento'] ?? 'No disponible'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Cambiar Contraseña -->
    <div class="text-center">
        <button type="button" class="btn btn-warning btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#cambiarContrasenaModal">
            <i class="fas fa-key"></i> Cambiar Contraseña
        </button>
    </div>
</div>
<div class="modal fade" id="cambiarContrasenaModal" tabindex="-1" aria-labelledby="cambiarContrasenaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="MiPerfil.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="cambiarContrasenaModalLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
                    </div>
                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    </div>
                    <input type="hidden" name="cambiar_contrasena" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<BR>
</body>
</html>
