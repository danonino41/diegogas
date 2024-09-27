<nav class="navbar navbar-expand-lg navbar navbar-light mb-2 shadow rounded"> 
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand" href="#">
      <div class="logo">
        <p>Diego Gas</p>
      </div>
    </a>

    <!-- Botón de navegación móvil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Enlaces de navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Inicio -->
        <li class="nav-item">
          <a class="nav-link active" href="admin_panel.php">
            <i class="fa fa-home"></i> Inicio
          </a>
        </li>
        
        <!-- Usuario -->
        <li class="nav-item">
          <a class="nav-link active" href="../../vistas/usuarios.php">
            <i class="fa fa-home"></i> Usuario
          </a>
        </li>

        <!-- Productos -->
        <li class="nav-item">
          <a class="nav-link active" href="productos.php">
            <i class="fa fa-shopping-cart"></i> Productos
          </a>
        </li>

        <!-- Clientes -->
        <li class="nav-item">
          <a class="nav-link active" href="clientes.php">
            <i class="fa fa-user-friends"></i> Clientes
          </a>
        </li>

        <!-- Vender -->
        <li class="nav-item">
          <a class="nav-link active" href="vender.php">
            <i class="fa fa-cash-register"></i> Vender
          </a>
        </li>

        <!-- Reporte de Ventas -->
        <li class="nav-item">
          <a class="nav-link active" href="reporte_ventas.php">
            <i class="fa fa-file-alt"></i> Reporte de Ventas
          </a>
        </li>
      </ul>

      <!-- Opciones de perfil y cerrar sesión -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="perfil.php" class="btn btn-info">Perfil</a>
        </li>
        &nbsp;
        <li class="nav-item">
          <a href="../../vistas/cerrar_sesion.php" class="btn btn-warning">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
