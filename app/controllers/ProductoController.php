<?php
use Firebase\JWT\JWT;
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

  public static function CargarArrayDeProductos($array, $id_orden_asociada)
  {

    try {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $objAccesoDatos->prepararTransacion();
      for ($i = 0; $i < count($array); $i++) {

        $parametros = $array[$i];

        $area = $parametros['area'];
        $estado = "pendiente";
        $descripcion = $parametros['descripcion'];
        $tipo = $parametros['tipo'];
        $precio = $parametros['precio'];

        $prod = new Producto();
        $prod->area = $area;
        $prod->id_orden_asociada = $id_orden_asociada;
        $prod->estado = $estado;
        $prod->descripcion = $descripcion;
        $prod->tipo = $tipo;
        $prod->precio = $precio;

        $prod->crearProducto();
      }
      $objAccesoDatos->ejecutarTransacion();
    } catch (\Throwable $th) {
      if ($objAccesoDatos->existeUnaTransacionEnProceso()) {
        $objAccesoDatos->devolverTransacion();
      }
      $objAccesoDatos->devolverTransacion();
      return false;
    }
    return true;
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

  public function TipoIniciarPreparacionPendientes($request, $response, $args)
  {

    $header = $request->getHeaderLine('Authorization');

    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $lista = Producto::obtenerTodos();
    $filtro = $data->tipo;
    var_dump($data->tipo);

    $new_array = array_filter($lista, function ($obj) use ($filtro) {
      if (isset($obj)) {
        if ($obj->tipo == $filtro && $obj->estado == "pendiente") {
          $obj->estado = "en preparacion";
          $obj->tiempo_entrega = date('Y-m-d H:i:s', strtotime("+30 minutes", time()));
          Producto::modificarProducto($obj);
          return true;
        }
        return false;
      }
    });

    //Producto::imprimirProductos($new_array);
    $payload = json_encode(array("listaProducto" => $new_array));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TipoActualizarListoParaServir($request, $response, $args)
  {

    $header = $request->getHeaderLine('Authorization');

    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $lista = Producto::obtenerTodos();
    $filtro = $data->tipo;

    $new_array = array_filter($lista, function ($obj) use ($filtro) {
      if (isset($obj)) {
        if ($obj->tipo == $filtro && $obj->estado == "en preparacion") {

          $obj->estado = "Listo para servir";
          
          $obj->tiempo_entrega = date('Y-m-d H:i:s', strtotime("+30 minutes", time()));
          Producto::modificarProducto($obj);
          return true;
        }
      }
      return false;

    });

    //Producto::imprimirProductos($new_array);
    $payload = json_encode(array("listaProducto" => $new_array));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
