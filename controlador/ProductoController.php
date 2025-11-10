<?php
// controlador/ProductoController.php
declare(strict_types=1);

require_once __DIR__ . '/../nucleo/Database.php';
require_once __DIR__ . '/../modelo/dao/ProductoDAO.php';
require_once __DIR__ . '/../modelo/Producto.php';

final class ProductoController
{
    public static function datosListado(): array {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;

        $pdo = Database::getConnection();
        $dao = new ProductoDAO($pdo);
        $productos = $dao->listar();

        return [$auth, $productos];
    }

    public static function datosFormNuevo(): array {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;
        return [$auth];
    }

    public static function crear(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;
        if (($auth['rol'] ?? 'visitante') !== 'manager') { http_response_code(403); exit('Sin permisos'); }

        $nombre = trim($_POST['nombre'] ?? '');
        $precio = $_POST['precio'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $descripcion = trim ($_POST['descripcion'] ?? '');

        if ($nombre === '' || !is_numeric($precio) || (float)$precio < 0 || (int)$stock<0 || !is_numeric($stock)) {
            http_response_code(422);
            echo self::error('Datos inválidos', 'index.php?p=productos&action=nuevo'); return;
        }

        $pdo = Database::getConnection();
        $dao = new ProductoDAO($pdo);
        $p   = new Producto($nombre, (float)$precio, (int)$stock, $descripcion);
        $dao->guardar($p); // INSERT

        header('Location: index.php?p=contenido'); exit;
    }

    public static function datosFormEditar(): array {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;
        if (($auth['rol'] ?? 'visitante') !== 'manager') { http_response_code(403); exit('Sin permisos'); }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

        $pdo = Database::getConnection();
        $dao = new ProductoDAO($pdo);
        $producto = $dao->buscarPorId($id); // heredado del DAO base
        if (!$producto) { http_response_code(404); exit('No encontrado'); }

        return [$auth, $producto];
    }

    public static function actualizar(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;
        if (($auth['rol'] ?? 'visitante') !== 'manager') { http_response_code(403); exit('Sin permisos'); }

        $id     = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = $_POST['precio'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $descripcion = trim($_POST['descripcion'] ?? '');



        if ($id<=0 || $nombre==='' || !is_numeric($precio) || (float)$precio<0 || !is_numeric($stock) || (int)$stock<0) {
            http_response_code(422);
            echo self::error('Datos inválidos', 'index.php?p=contenido'); return;
        }

        $pdo = Database::getConnection();
        $dao = new ProductoDAO($pdo);
        $p   = new Producto($nombre, (float)$precio, (int)$stock, $descripcion);
        $p->setId($id);
        $dao->guardar($p); // UPDATE

        header('Location: index.php?p=contenido'); exit;
    }

    public static function eliminar(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $auth = $_SESSION['auth'] ?? null;
        if (($auth['rol'] ?? 'visitante') !== 'manager') { http_response_code(403); exit('Sin permisos'); }

        $id = (int)($_POST['id'] ?? 0);
        if ($id<=0) { http_response_code(400); exit('ID inválido'); }

        $pdo = Database::getConnection();
        $dao = new ProductoDAO($pdo);
        $dao->eliminar($id); // heredado del DAO base

        header('Location: index.php?p=contenido'); exit;
    }

    private static function error(string $msg, string $back): string {
        return '<div class="container py-4"><div class="alert alert-danger">' .
               htmlspecialchars($msg) .
               '</div><a class="btn btn-secondary mt-2" href="' . htmlspecialchars($back) .
               '">Volver</a></div>';
    }
}
