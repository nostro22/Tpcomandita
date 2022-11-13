<?php
use function React\Promise\map;

require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
require_once './models/UploadManager.php';


class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {    
    $parametros = $request->getParsedBody();
    $area = $parametros['area'];
    $id_orden_asociada = $parametros['id_orden_asociada'];
    $estado = $parametros['estado'];
    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $precio = $parametros['precio'];

    // Creamos el Producto
    $usr = new Producto();
    $usr->area = $area;
    $usr->id_orden_asociada = $id_orden_asociada;
    $usr->estado = $estado;
    $usr->descripcion = $descripcion;
    $usr->tipo = $tipo;
    $usr->precio = $precio;
    $usr->crearProducto();

    $payload = json_encode(array("mensaje" => "Producto creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {

    // Buscamos Producto por id_orden_asociada
    $usr = $args['id_Producto'];
    $Producto = Producto::obtenerProducto($usr);
    $payload = json_encode($Producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $filtro = "todos";
    //posibles filtros todos , disponible, retirado
    if (isset($args['estado'])) {
      $filtro = $args['estado'];
    }

    $new_array = array_filter($lista, function ($obj) use ($filtro) {
      if (isset($obj)) {
        foreach ($obj as $Producto) {
          if ($filtro != "todos") {
            if ($obj->estado != $filtro)
              return false;
          }
        }
      }
      return true;
    });
    
    Producto::imprimirProductos($new_array);
    $payload = json_encode(array("listaProducto" => $new_array));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $args['id'];
    $estado = $parametros['estado'];
    $tiempo_entrega = $parametros['tiempo_entrega'];
    // Creamos el Producto
    $usr = new Producto();
    $usr->id = $id;
    $usr->estado = $estado;
    $usr->tiempo_entrega = $tiempo_entrega;

    Producto::modificarProducto($usr);
    $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $ProductoId = $args['id'];
    Producto::borrarProducto($ProductoId);

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function GetDatosDelToken($request)
  {
    $header = $request->getHeader('Authorization');
    $token = trim(str_replace("Bearer", "", $header[0]));
    $user = AutentificadorJWT::ObtenerData($token);

    return $user;
  }


}