<!-- Tarjetas Resumen de Pedidos -->
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
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">Confirmados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosConfirmados); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Preparados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosPreparados); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">En Ruta</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosEnRuta); ?> Pedidos</h5>
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
