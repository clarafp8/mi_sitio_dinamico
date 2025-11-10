<?php
// modelo/dao/ProductoDAO.php
declare(strict_types=1);

require_once __DIR__ . '/DAO.php';
require_once __DIR__ . '/../Producto.php';

final class ProductoDAO extends DAO
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'productos');
    }

    /** @return Producto */
    protected function crearEntidad(array $fila): object {
        // Verifica si 'descripcion' es null y asigna un valor por defecto
        $descripcion = isset($fila['descripcion']) ? $fila['descripcion'] : '';

        $p = new Producto($fila['nombre'], (float)$fila['precio'], (int)$fila['stock'], $descripcion);
        if (isset($fila['id'])) { $p->setId((int)$fila['id']); }
        return $p;
    }

    /** INSERT o UPDATE según tenga id */
    public function guardar(object $entidad): bool {
        if (!($entidad instanceof Producto)) {
            throw new InvalidArgumentException('Se esperaba Producto');
        }

        $id = $entidad->getId();
        if (empty($id)) {
            $sql = "INSERT INTO {$this->tabla} (nombre, precio, stock, descripcion) VALUES (:n, :p, :s, :d)";
            $st  = $this->pdo->prepare($sql);
            $ok  = $st->execute([':n'=>$entidad->nombre, ':p'=>$entidad->precio, ':s'=>$entidad->stock, ':d'=>$entidad->descripcion]);
            if ($ok) { $entidad->setId((int)$this->pdo->lastInsertId()); }
            return $ok;
        }

        $sql = "UPDATE {$this->tabla} SET nombre=:n, precio=:p, stock=:s, descripcion=:d WHERE id=:id";
        $st  = $this->pdo->prepare($sql);
        return $st->execute([
            ':n'=>$entidad->nombre,
            ':p'=>$entidad->precio,
            ':s'=>$entidad->stock,
            ':d'=>$entidad->descripcion,
            ':id'=>$id
        ]);
    }

    /** @return Producto[] */
    public function listar(): array {
        $sql = "SELECT id, nombre, precio, stock,descripcion FROM {$this->tabla} ORDER BY id ASC";
        $st  = $this->pdo->query($sql);
        $st->setFetchMode(PDO::FETCH_ASSOC);

        $res = [];
        while ($row = $st->fetch()) {
            $p = $this->crearEntidad($row); // consistente con el patrón
            $res[] = $p;
        }
        return $res;
    }
}
