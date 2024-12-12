<div class="row">
    <div class="col-md-2 col-6">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Pendientes</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo count($pedidosPendientes); ?> Pedidos</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Preparados</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo count($pedidosPreparados); ?> Pedidos</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">En camino</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo count($pedidosEnCamino); ?> Pedidos</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Entregados</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo count($pedidosEntregados); ?> Pedidos</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Cancelados</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo count($pedidosCancelados); ?> Pedidos</h5>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilo para cuando la pantalla es más pequeña que 768px */
    @media (max-width: 767px) {
        .col-6 {
            flex: 0 0 50%;  /* Ocupa el 50% de la fila */
            max-width: 50%;
        }
    }

    /* Estilo para pantallas medianas entre 768px y 991px (tabletas) */
    @media (min-width: 768px) and (max-width: 991px) {
        .col-md-2 {
            flex: 0 0 33.33%;  /* Ocupa el 33.33% de la fila */
            max-width: 33.33%;
        }
    }

    /* Estilo para pantallas grandes (por encima de 992px) */
    @media (min-width: 992px) {
        .col-md-2 {
            flex: 0 0 20%;  /* Ocupa el 20% de la fila */
            max-width: 20%;
        }
    }
</style>
