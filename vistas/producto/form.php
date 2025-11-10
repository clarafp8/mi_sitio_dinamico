<?php
// Variables: $auth (array|null)
// Si $producto está definido, estamos en modo edición
$rol = $auth['rol'] ?? 'visitante';
$esManager = ($rol === 'manager');

$esEdicion = isset($producto); // Producto
$titulo = $esEdicion ? 'Editar producto' : 'Nuevo producto';
$action = $esEdicion ? 'actualizar' : 'crear';

$valNombre = $esEdicion ? $producto->nombre : '';
$valPrecio = $esEdicion ? (string)$producto->precio : '';
$valStock = $esEdicion ? (string)$producto->stock : '';
$valDescripcion = $esEdicion ? $producto->descripcion : '';
$valId     = $esEdicion ? (int)$producto->getId() : 0;
?>
<h2 class="text-success text-center mt-4"><?= htmlspecialchars($titulo) ?></h2>

<?php if (!$esManager): ?>
  <div class="alert alert-danger w-75 mx-auto">No tienes permisos para <?= $esEdicion ? 'editar' : 'crear' ?> productos.</div>
  <?php return; ?>
<?php endif; ?>

<form class="w-75 mx-auto mt-4" method="post" action="index.php?p=productos&action=<?= htmlspecialchars($action) ?>">
  <?php if ($esEdicion): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$valId) ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input class="form-control" name="nombre" maxlength="120" required
           value="<?= htmlspecialchars($valNombre) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Precio (€)</label>
    <input class="form-control" name="precio" type="number" step="0.01" min="0" required
           value="<?= htmlspecialchars($valPrecio) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input class="form-control" name="stock" type="number" min="0" required
           value="<?= htmlspecialchars($valStock) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Descripcion</label>
    <input class="form-control" name="descripcion" maxlength="400" required
           value="<?= htmlspecialchars($valDescripcion) ?>">
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit"><?= $esEdicion ? 'Guardar cambios' : 'Crear producto' ?></button>
    <a class="btn btn-secondary" href="index.php?p=contenido">Cancelar</a>
  </div>
</form>
