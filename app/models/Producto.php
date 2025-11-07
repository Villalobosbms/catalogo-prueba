<?php

class Producto{
    
    private static function conectar(){
        
        $host = 'localhost';
        $dbname = 'catalogo';
        $user = 'root';
        $pass = '1234';

        try {
            // Crear una instancia de PDO
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

            // Configurar modo de errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } 
        catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    public static function obtenerDatos() {
        $pdo = self::conectar();
        $sql = $pdo->query("SELECT * FROM producto");
        return $sql->fetchAll();
    }

    public function crearProducto($nombre, $precio, $cantidad){
        try{
            $pdo = self::conectar();
            $sql = ("INSERT INTO producto (nombre, precio, cantidad) VALUES (:nombre, :precio, :cantidad)");
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nombre' => $nombre,
                ':precio' => $precio,
                ':cantidad' => $cantidad,
            ]);

            return true;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return;
        }
    }

    public function eliminarProducto($id){
        try{
            $pdo = self::conectar();
            $sql = ("DELETE  FROM  producto WHERE id = :id" );
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':id' => $id,
            ]);

            return true;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return;
        }
    }

    public function actualizarProducto($id, $nombre, $precio, $cantidad){
        try{
            $pdo = self::conectar();
            $sql = ("UPDATE producto SET nombre = :nombre, precio = :precio, cantidad = :cantidad WHERE id = :id");
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":id" => $id,
                ":nombre" => $nombre,
                ":precio" => $precio,
                ":cantidad" => $cantidad
            ]);
        } catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            return;
        }
    }
}