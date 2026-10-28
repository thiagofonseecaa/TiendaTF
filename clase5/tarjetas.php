<div class="productos-grid">
<?php foreach ($resultados as $producto): ?>
    <article class="tarjeta-producto">
        <div class="foto-producto"><img src="<?= escapar($base_sitio . $producto['imagen']) ?>" alt="<?= escapar($producto['nombre']) ?>" loading="lazy"></div>
        <div class="datos-producto">
            <p class="categoria-producto"><?= escapar($producto['categoria']) ?></p>
            <h3><?= escapar($producto['nombre']) ?></h3>
            <p class="precio-producto"><?= escapar(precio_ars($producto['precio'])) ?></p>
            <?php if ($producto['talles']): ?>
            <form class="form-compra" method="post" action="<?= escapar($base_sitio) ?>carrito.php">
                <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
                <input type="hidden" name="accion" value="agregar">
                <input type="hidden" name="producto" value="<?= escapar($producto['id']) ?>">
                <label for="talle-<?= escapar($producto['id']) ?>">Talle / medida</label>
                <select id="talle-<?= escapar($producto['id']) ?>" name="talle" required>
                    <option value="">Elegí una opción</option>
                    <?php foreach ($producto['talles'] as $talle): ?><option value="<?= escapar($talle) ?>"><?= escapar($talle) ?></option><?php endforeach; ?>
                </select>
                <button type="submit">Agregar al carrito</button>
            </form>
            <?php else: ?><p>Disponible próximamente.</p><?php endif; ?>
        </div>
    </article>
<?php endforeach; ?>
</div>
