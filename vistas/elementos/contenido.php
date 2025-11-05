<?php
require_once __DIR__ . '/plantillas.php';
require_once __DIR__ . '/../../config.php';

$contenido = mostrarListadoProductos();
echo generarPaginaHTML('Administración de Productos', $contenido);


/* Estructura asociativa: producto => precio
$productos = [
  "Pan de Camas"        => 1.20,
  "Aceitunas aliñadas"  => 2.50,
  "Tortas de aceite"    => 3.00
];
*/
?>

<h2 class="text-success text-center mt-4">Productos locales de Camas</h2>

<table class="table table-bordered table-striped w-75 mx-auto mt-4 text-center align-middle">
  <thead class="table-primary">
    <tr>
      <th>Producto</th>
      <th>Precio (€)</th>
      <th>Opciones</th>

    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p-> $nombre) ?></td>
        <td><?= number_format($precio, 2, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>