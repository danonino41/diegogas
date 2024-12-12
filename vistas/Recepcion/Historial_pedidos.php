
<h3>Historial de Pedidos del Día de Hoy</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pedidosHoy)): ?>
                <?php foreach ($pedidosHoy as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                        <td>S/ <?php echo number_format(htmlspecialchars($pedido['total']), 2); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay pedidos hoy.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>