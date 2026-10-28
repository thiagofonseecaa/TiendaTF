<?php
require_once __DIR__ . '/../auth_guard.php';
verificar_rol('admin');
?>
        <label for="imagen">Foto del producto (opcional)</label>
        <select id="imagen" name="imagen">
            <option value="">Sin foto</option>
            <?php foreach ($imagenes_disponibles as $ruta => $etiqueta): ?>
                <option value="<?= escapar($ruta) ?>"<?= $imagen === $ruta ? ' selected' : '' ?>><?= escapar($etiqueta) ?></option>
            <?php endforeach; ?>
        </select>
        <p class="nota-precios">Elegí una de las fotos de la tienda que corresponda al producto.</p>
        <fieldset class="talles-producto">
            <legend>Talles que se ofrecen</legend>
            <?php foreach ($talles_disponibles as $talle): ?>
                <label><input type="checkbox" name="talles[]" value="<?= escapar($talle) ?>"<?= in_array($talle, $talles, true) ? ' checked' : '' ?>> <?= escapar($talle) ?></label>
            <?php endforeach; ?>
        </fieldset>
