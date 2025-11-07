<?php
require_once __DIR__ . '/../models/Producto.php';

class CatalogoController{

    public function listarTodos(){
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        $lista = Producto::obtenerDatos();
        echo json_encode($lista);
        return;
    }

    public function agregarProducto(){
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $nombre = $_POST['nombre'] ?? null;
        $precio = $_POST['precio'] ?? null;
        $cantidad = $_POST['cantidad'] ?? null;


        if (empty($nombre) || empty($precio) || empty($cantidad)) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los datos son obligatorios']);
            return;
        }

        if (!is_numeric($precio)){
            http_response_code(400);
            echo json_encode(['error' => 'El precio tiene que ser un numero']);
            return;
        }

        if (!($precio >= 0)){
            http_response_code(400);
            echo json_encode(['error' => 'El precio tiene que ser mayor o igual a 0']);
            return;
        }

        if (!($cantidad >= 0)){
            http_response_code(400);
            echo json_encode(['error' => 'La cantidad no puede ser negativa']);
            return;
        }

        $añadir = new Producto();
        $exitoso = $añadir->crearProducto($nombre, $precio , $cantidad);
        if(!$exitoso){
             echo json_encode(['error' => "No se pudo crear el producto"]);
            return;
        } 
        echo json_encode(['success' => true, 'nombre' => $nombre, "precio" => $precio, "cantidad" => $cantidad]);
        return;
    }

    public function eliminar(){
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
    
        parse_str(file_get_contents('php://input'), $data);
        $id = $data['id'] ?? null;

        if(empty($id)){
            echo json_encode(['error' => "No se pudo eliminar el producto"]);
            return;
        }

        $eliminar = new Producto();
        $eliminar->eliminarProducto($id);
        echo json_encode(["success" => true]);
        return;
    }

    public function actualizar(){
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
       
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $precio = $_POST['precio'] ?? null;
        $cantidad = $_POST['cantidad'] ?? null;


        if (empty($nombre) || empty($precio) || empty($cantidad)) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los datos son obligatorios']);
            return;
        }

        if (!is_numeric($precio)){
            http_response_code(400);
            echo json_encode(['error' => 'El precio tiene que ser un numero']);
            return;
        }

        if (!($precio >= 0)){
            http_response_code(400);
            echo json_encode(['error' => 'El precio tiene que ser mayor o igual a 0']);
            return;
        }

        if (!($cantidad >= 0)){
            http_response_code(400);
            echo json_encode(['error' => 'La cantidad no puede ser negativa']);
            return;
        }

        $actualizar = new Producto();
        $actualizar->actualizarProducto($id, $nombre, $precio, $cantidad);
        echo json_encode(['success' => true, 'nombre' => $nombre, "precio" => $precio, "cantidad" => $cantidad]);
        return;

    }
}