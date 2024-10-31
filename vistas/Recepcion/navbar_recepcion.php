<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepcion</title>
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <!-- Font Awesome y Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<nav class="navbar navbar-expand-lg sticky-top navbar-custom">
    <div class="container-fluid">
        <a href="panel_recepcion.php" class="navbar-brand text-info fw-semibold fs-4">
            <div class="logo">
                <p>Diego Gas</p>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral">
            <span class="navbar-toggler-icon"></span>
        </button>
        <section class="offcanvas offcanvas-start" id="menuLateral" tabindex="-1">
            <div class="offcanvas-header navbar-custom">
                <a href="panel_recepcion.php">
                    <div class="logo">
                        <p>Diego Gas</p>
                    </div>
                </a>
                <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column justify-content-between px-0 Presentacion">
                <ul class="navbar-nav my-2 justify-content-evenly">
                    <li class="nav-item p-3 py-md-1">
                        <a href="clientes_recepcion.php" class="nav-link">Clientes</a>
                    </li>
                    <li class="nav-item p-3 py-md-1">
                        <a href="productos_recepcion.php" class="nav-link">Productos</a>
                    </li>
                    <li class="nav-item p-3 py-md-1">
                        <a href="pedido_recepcion.php" class="nav-link">Realizar Pedido</a>
                    </li>
                    <li class="nav-item dropdown p-3 py-md-1">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownPedidos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pedidos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownPedidos">
                            <li><a class="dropdown-item" href="pedidos_pendientes.php">Pedidos Pendientes</a></li>
                            <li><a class="dropdown-item" href="pedidos_camino.php">Pedidos en Camino</a></li>
                            <li><a class="dropdown-item" href="pedidos_entregados.php">Pedidos Entregados</a></li>
                            <li><a class="dropdown-item" href="pedidos_cancelados.php">Pedidos Cancelados</a></li>
                        </ul>
                    </li>                    
                    <li class="nav-item dropdown p-3 py-md-1">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownVentas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ventas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownVentas">
                            <li><a class="dropdown-item" href="../boletas.php">Boletas</a></li>
                            <li><a class="dropdown-item" href="../historial_ventas.php">Historial de Ventas</a></li>
                        </ul>
                    </li>    
                    <li class="nav-item dropdown p-3 py-md-1">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownPerfil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPerfil">
                            <li><a class="dropdown-item" href="MiPerfil.php"><i class="fas fa-cog"></i> Perfil</a></li>
                            <li><a class="dropdown-item" href="Logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
