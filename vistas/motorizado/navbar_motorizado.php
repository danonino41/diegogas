<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Logo o nombre de la aplicación -->
        <a class="navbar-brand" href="motorizado_panel.php">Diego Gas - Motorizado</a>
        
        <!-- Botón de colapso para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menú de navegación -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Opciones del menú -->
                <li class="nav-item">
                    <a class="nav-link" href="pedidos_asignados_motorizado.php">Pedidos Asignados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="historial_pedidos_motorizado.php">Historial de Pedidos</a>
                </li>
            </ul>
            
            <!-- Dropdown de perfil en la esquina derecha -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown p-3 py-md-1">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownPerfil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPerfil">
                        <li><a class="dropdown-item" href="MiPerfil_motorizado.php"><i class="fas fa-cog"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="../../controladores/cerrar_sesion.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
