<?php
use function React\Promise\map;

require_once './models/Orden.php';
require_once './interfaces/IApiUsable.php';
require_once './models/UploadManager.php';


class OrdenController extends Orden implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $imagesDirectory = "./OrderImages/";
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


}