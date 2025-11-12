<?php
declare(strict_types=1);

// /nucleo/Datos.php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../config.php';

/**
 * Vacía una tabla de forma segura (MySQL).
 * Nota: TRUNCATE hace commit implícito; por eso debe ejecutarse fuera de una transacción.
 */
function resetTabla(PDO $pdo, string $tabla): void
{
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec("TRUNCATE TABLE `{$tabla}`");
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

/**
 * 🌱 Inserta productos de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function semillaProductosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $productos = [
        ['producto' => 'Pan de Camas',                   'precio' => 1.20 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Aceitunas aliñadas de Camas',    'precio' => 2.50 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Tortas de aceite',               'precio' => 3.00 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Aceite Virgen Extra “Aljarafe”', 'precio' => 6.80 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Jamón ibérico de recebo',        'precio' => 12.50 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Queso de cabra payoya',          'precio' => 4.75 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Miel de azahar del Aljarafe',    'precio' => 5.20 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Almendras fritas estilo barra',  'precio' => 3.40 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Bollos de anís tradicionales',   'precio' => 2.30 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Paté de aceituna verde',         'precio' => 3.10 , 'stock' => 0, 'descripcion' => null] ,
        ['producto' => 'Vino blanco DO “Aljarafe”',      'precio' => 8.50 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Dulce de membrillo artesano',    'precio' => 2.90 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Anchoas en aceite de oliva',     'precio' => 7.20 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Chorizo casero del Aljarafe',    'precio' => 4.60 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Flor de sal del Guadalquivir',   'precio' => 2.70 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Mermelada de higo de la zona',   'precio' => 3.30 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Cervezas artesanas sevillanas',  'precio' => 2.80 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Tomate seco en aceite',          'precio' => 4.20 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Aceite arbequina 250 ml',        'precio' => 5.60 , 'stock' => 0, 'descripcion' => null],
        ['producto' => 'Picos de pan artesanos',         'precio' => 1.80 , 'stock' => 0, 'descripcion' => null],
    ];

    // Si vas a resetear, hazlo SIEMPRE fuera de la transacción
    if ($reset) {
        resetTabla($pdo, 'productos');
    }

    $sql = "INSERT INTO productos (nombre, precio, stock, descripcion) VALUES (:nombre, :precio, :stock, :descripcion)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($productos as $p) {
            $stmt->execute([
                ':nombre' => (string)($p['producto'] ?? ''),
                ':precio' => (float)($p['precio'] ?? 0.0),
                ':stock' => (int)($p['stock'] ?? 0),
                ':descripcion' => (string)($p['descripcion']),
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed productos error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}

/**
 * 🌱 Inserta usuarios de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function seedUsuariosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $usuarios = [
        ['admin',    'admin123', 'Administrador General', 'admin'],
        ['manager1', 'manager1', 'Laura Gestora',         'manager'],
        ['manager2', 'manager2', 'Carlos Supervisor',     'manager'],
        ['user1',    'user1',    'María Compradora',      'usuario'],
        ['user2',    'user2',    'Pedro Cliente',         'usuario'],
        ['user3',    'user3',    'Lucía Compradora',      'usuario'],
        ['user4',    'user4',    'Manuel Perez',          'usuario'],
         ['user5',    'user5',    'Tess test',          'usuario'],
    ];

    if ($reset) {
        resetTabla($pdo, 'usuarios');
    }

    $sql = "INSERT INTO usuarios (usuario, password, nombre, rol)
            VALUES (:usuario, :password, :nombre, :rol)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($usuarios as [$usuario, $clave, $nombre, $rol]) {
            $stmt->execute([
                ':usuario'  => $usuario,
                ':password' => password_hash($clave, PASSWORD_DEFAULT),
                ':nombre'   => $nombre,
                ':rol'      => $rol,
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed usuarios error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}
