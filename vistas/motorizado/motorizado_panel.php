<div class="container mt-4">
                <h1>Productos</h1>
                <form method="GET" action="productos_recepcion.php" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="busqueda" class="form-control" placeholder="Buscar producto por nombre, categoría o subcategoría" value="<?php echo htmlspecialchars($busqueda); ?>">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </form>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Lista de Productos
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio Venta</th>
                                        <th>Existencias</th>
                                        <th>Subcategoría</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($productos)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No se encontraron productos.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <tr>
                                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['existencias']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                                    <input type="hidden" name="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>">
                                                    <input type="hidden" name="precio_venta" value="<?php echo $producto['precio_venta']; ?>">
                                                    <input type="number" name="cantidad" value="1" min="1" class="form-control d-inline" style="width: 60px;">
                                                    <button class="btn btn-success btn-sm" type="submit" name="agregar_producto">Agregar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>