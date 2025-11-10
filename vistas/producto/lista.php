<?php
// vistas/producto/lista.php
// Variables disponibles: $auth (array|null), $productos (Producto[])

$rol = $auth['rol'] ?? 'visitante';
$nombre = $auth['nombre'] ?? 'Invitado';
$esManager = ($rol === 'manager');
?>
<h2 class="text-success text-center mt-4">Productos locales de Camas</h2>
<p class="text-center text-muted">Bienvenido, <?= htmlspecialchars($nombre) ?></p>

<?php if ($esManager): ?>
  <div class="text-center mb-3">
    <a href="index.php?p=productos&action=nuevo" class="btn btn-primary">➕ Añadir producto</a>
  </div>
<?php endif; ?>

<table class="table table-bordered table-striped w-75 mx-auto mt-4 text-center align-middle">
  <thead class="table-primary">
    <tr>
      <th>Producto</th>
      <th>Precio (€)</th>
      <th>Stock</th>
      <th>Descripcion</th>

      <?php if ($esManager): ?><th>Acciones</th><?php endif; ?>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($productos as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p->nombre) ?></td>
      <td><?= number_format((float)$p->precio, 2, ',', '.') ?></td>
      <td><?= $p->stock ?></td>
      <td><?= htmlspecialchars($p->descripcion) ?></td>

      <?php if ($esManager): ?>
        <td>
          <a href="index.php?p=productos&action=editar&id=<?= htmlspecialchars((string)$p->getId()) ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
          <!-- Eliminar SIEMPRE por POST, no GET -->
          <form method="post" action="index.php?p=productos&action=eliminar" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
            <input type="hidden" name="id" value="<?= htmlspecialchars((string)$p->getId()) ?>">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
            <button class="btn btn-sm btn-danger" type="submit">🗑️ Eliminar</button>
          </form>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
  <?php if (empty($productos)): ?>
    <tr><td colspan="<?= $esManager ? 3 : 2 ?>" class="text-center text-muted">No hay productos aún.</td></tr>
  <?php endif; ?>
  </tbody>
</table>
