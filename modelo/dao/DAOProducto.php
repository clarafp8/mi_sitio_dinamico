<?php
declare(strict_types=1);

require_once __DIR__ . '/DAO.php';
require_once __DIR__ . '/../Producto.php';

class DAOProducto extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'productos');
    }

    public function guardar(object $producto): bool
    {
        if (!$producto instanceof Producto) {
            throw new InvalidArgumentException("El objeto proporcionado no es de tipo Producto.");
        }

        $query = "INSERT INTO {$this->tabla} (nombre, precio) VALUES (:nombre, :precio)";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':nombre', $producto->nombre);
        $stmt->bindValue(':precio', $producto->precio);

        return $stmt->execute();
    }

    // Implementación del método buscarPorId (para buscar un producto por su ID)
    public function buscarPorId(int $id): ?object
    {
        $query = "SELECT id, nombre, precio FROM {$this->tabla} WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Si se encuentra el producto, lo retornamos como un objeto Producto
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $producto = new Producto();
            $producto->setId((int)$row['id']);
            $producto->nombre = $row['nombre'];
            $producto->precio = (float)$row['precio'];
            return $producto;
        }

        return null; // Si no se encuentra el producto
    }




    /**
     * Devuelve todos los productos.
     * @return Producto[]
     */
    public function listar(): array
    {
        $stmt = $this->pdo->query("SELECT id, nombre, precio
                                     FROM {$this->tabla}
                                 ORDER BY id ASC");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $productos = [];
        while ($row = $stmt->fetch()) {
            $p = new Producto();
            $p->setId((int)$row['id']);
            $p->nombre = $row['nombre'];
            $p->precio  = (float)$row['precio'];
            
            $productos[] = $p;
        }
        return $productos;
    }
}