<header class="text-center bg-white p-3 rounded shadow-sm">
  <h1 class="text-primary">Bienvenido a Camas – Mi Sitio Dinámico PHP</h1>
  <p class="text-muted">Usando include() por primera vez</p>

  <?php

session_start();
$_SESSION['usuario'] = 'juan'; //Simulamos usuario logueado
$p= $_GET['p'] ?? 'inicio'; //Página principal
unset($_SESSION['usuario']);

    $menu= [
      'inicio' => 'Inicio',
      'contenido' => 'Productos',
      'contacto' => 'Contacto',
      'cerrarSesion' => 'Cerrar sesión'
    ];

    $menuInicio = [
      'iniciarSesion' => 'Iniciar Sesion',
      'inicio' => 'Inicio',
      'contenido' => 'Productos',
      'contacto' => 'Contacto'
    ];

    $menuActual = isset($_SESSION['usuario']) ? $menu : $menuInicio;
    ?>

    <ul class="nav nav-pills">
  <?php foreach ($menuActual as $clave => $texto): ?>
    <li class="nav-item">
      <a class="nav-link <?= ($p === $clave) ? 'active' : '' ?>"
         href="index.php?p=<?= $clave ?>">
         <?= htmlspecialchars($texto) ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
  </header> 

