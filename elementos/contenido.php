<?php
// Estructura asociativa: producto => precio
$productos = [
  "Pan de Camas"        => 1.20,
  "Aceitunas aliñadas"  => 2.50,
  "Tortas de aceite"    => 3.00
];
?>

<h2 class="text-success text-center mt-4">Productos locales de Camas</h2>

<table class="table table-bordered table-striped w-75 mx-auto mt-4 text-center align-middle">
  <thead class="table-primary">
    <tr>
      <th>Producto</th>
      <th>Precio (€)</th>
    </tr>
  </thead>
  <tbody>
    
    
      <?php 
      $claves = array_keys($productos);   // ['Pan de Camas', 'Aceitunas aliñadas', 'Tortas de aceite']
      $valores = array_values($productos); // ['1.2','2.5','3.0']

      for ($i = 0; $i < count($productos); $i++){
        $nombre=$claves[$i];
        $precio=$valores[$i];
      ?>

      <tr>
        <td><?= htmlspecialchars($nombre) ?></td>
        <td><?= number_format($precio, 2, ',', '.') ?></td>
      </tr>
    <?php 
     }
    ?>
     
    
  </tbody>
</table>
