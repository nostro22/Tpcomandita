<?php
use function React\Promise\map;

require_once './models/Orden.php';
require_once './interfaces/IApiUsable.php';
require_once './models/UploadManager.php';
require_once './controllers/ProductoController.php';


class OrdenController extends Orden implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id_mesa = $parametros['id_mesa'];
    $estado = $parametros['estado'];
    $nombre_cliente = $parametros['nombre_cliente'];
    $costo = $parametros['costo'];

    // Creamos el Orden
    $usr = new Orden();
    $usr->id_mesa = $id_mesa;
    $usr->estado = $estado;
    $usr->nombre_cliente = $nombre_cliente;
    $usr->costo = $costo;
    $usr->id = $usr->crearOrden();

    $imagesDirectory = './ImagenesOrden/';
    new UploadManager($imagesDirectory, $usr->id, $_FILES);
    $usr->imagen = UploadManager::getImageName($usr);
    var_dump($usr);
    Orden::updateImagen($usr);

    $payload = json_encode(array("mensaje" => "Orden creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {

    // Buscamos Orden por nombre_cliente
    $usr = $args['id_orden'];
    $Orden = Orden::obtenerOrden($usr);
    $payload = json_encode($Orden);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Orden::obtenerTodos();
    $filtro = "todos";
    //posibles filtros todos , en preparación, listo para servir, canceladas, servido
    if (isset($args['estado'])) {
      $filtro = $args['estado'];
    }
    switch ($filtro) {
      case 'pendientes':
        $filtro = "en preparacion";
        break;
      case 'listos':
        $filtro = "Listo Para Servir";
        break;
      case 'cancelados':
        $filtro = "cancelada";
        break;
      case 'servidos':
        $filtro = "servido";
        break;
    }
    $new_array = array_filter($lista, function ($obj) use ($filtro) {
      if (isset($obj)) {
        foreach ($obj as $Orden) {
          if ($filtro != "todos") {
            if ($obj->estado != $filtro)
              return false;
          }
        }
      }
      return true;
    });
    Orden::imprimirOrdens($lista, $filtro);
    $payload = json_encode(array("listaOrden" => $new_array));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function TraerDemoraPedidoCliente($request, $response, $args)
  {
    if (isset($args['codigo_mesa']) && isset($args['codigo_orden'])) {
      $codigo_orden = $args['codigo_orden'];
      $codigo_mesa = $args['codigo_mesa'];
    }

    $demora = ProductoController::obtenerMaxProductoDemoraOrden($codigo_orden,$codigo_mesa);
    if(isset($demora))
    {
      
      $payload = json_encode(array("Pedido listo en aproximadamente" => $demora . " minutos"));
      
    }
    else
    {
      $payload = json_encode(array("Pedido no encontrado" => "verifique codigos"));
      
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function TraerDemoraPedidosAdmin($request, $response, $args)
  {
    $lista = Orden::obtenerTodos();
    $estado = "listo para servir";
  
    $new_array = array_filter($lista, function ($obj) use ($estado) {
      if (isset($obj)) {
          if ($estado == $obj->estado) {
              return false;
          }
          else{
            return true;
          }
        }
    });
    Orden::imprimirOrdensConDemora($new_array);
    $payload = json_encode(array("listaOrden" => $new_array));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $args['id'];
    $estado = $parametros['estado'];
    $costo = $parametros['costo'];
    // Creamos el Orden
    $usr = new Orden();
    $usr->id = $id;
    $usr->estado = $estado;
    $usr->costo = $costo;

    Orden::modificarOrden($usr);
    $payload = json_encode(array("mensaje" => "Orden modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $OrdenId = $args['id'];
    Orden::borrarOrden($OrdenId);

    $payload = json_encode(array("mensaje" => "Orden borrado con exito"));

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



  public function crearOrdenCompletaMozo($request, $response, $args)
  {

    $datos = ($request->getParsedBody());
    $orden = $datos["orden"];
    $pedidos = $datos["pedidos"];
    $header = $request->getHeader('Authorization');
    $token = trim(str_replace("Bearer", "", $header[0]));
    $user = AutentificadorJWT::ObtenerData($token);

    $costo = 0;
    foreach ($pedidos as $pedido) {
      $costo += $pedido["precio"];
    }

    $imagesDirectory = "";
    $parametros = $orden;
    $id_mesa = $parametros['id_mesa'];
    $estado = "pendiente";
    $nombre_cliente = $parametros['nombre_cliente'];


    // Creamos el Orden
    $ord = new Orden();
    $ord->id_mesa = $id_mesa;
    $ord->estado = $estado;
    $ord->nombre_cliente = $nombre_cliente;
    $ord->costo = $costo;
    $ord->id = $ord->crearOrden();
    $codigoMesa = $ord->prefix . sprintf("%03d", $ord->id);

    //cargo los pedidos
    ProductoController::CargarArrayDeProductos($pedidos, $codigoMesa);
    //Cambio el estado de la mesa a "con cliente esperando pedido"
    $idDelMozo =$user->id;
    $mesa = new Mesa();
    $mesa->id=substr($id_mesa,2);
    $mesa->estado = "con cliente esperando pedido";
    $mesa->id_personal = $idDelMozo;
    MesaController::modificarMesa($mesa);
    $payload = json_encode(array("Codigo del Pedido" => $codigoMesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');

  }

  public function CargarFoto($request, $response, $args)
  {
    $imagesDirectory = "./OrderImages/";
    // Buscamos Orden por nombre_cliente
    $parametros = $request->getParsedBody();
    var_dump($parametros);
    $id = $parametros['id_orden'];
    $ord = new Orden();
    $ord->id = $id;

    $imagesDirectory = './ImagenesOrden/';
    new UploadManager($imagesDirectory, $id, $_FILES);
    $ord->imagen = UploadManager::getImageName($ord);
    Orden::updateImagen($ord);

    $payload = json_encode(array("mensaje" => "foto vinculada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

}