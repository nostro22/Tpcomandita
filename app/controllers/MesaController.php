<?php
use function React\Promise\map;
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_personal = $parametros['id_personal'];
        $estado = $parametros['estado'];

        // Creamos el Mesa
        $usr = new Mesa();
        $usr->id_personal = $id_personal;
        $usr->estado = $estado;
        $usr->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
     
        // Buscamos Mesa por estado
        $id = array_slice($args['codigo_mesa'],2);
        var_dump($id);
        $Mesa = Mesa::obtenerMesa($id);
        $payload = json_encode($Mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {      
        $lista = Mesa::obtenerTodos();
        $filtro="todos";
        if(isset($args['id_personal']))
        {
          $filtro=$args['id_personal'];
        }
        $new_array = array_filter($lista, function($obj) use ($filtro){
          if (isset($obj)) {
              foreach ($obj as $Mesa) {
                if($filtro!="todos")
                {
                  if ($obj->estado != $filtro) 
                  return false;
                }
              }
          }
          return true;
      });
        Mesa::imprimirMesas($lista,$filtro );
        $payload = json_encode(array("listaMesa" =>$new_array));
       $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['id'];
        $codigo_mesa = $parametros['codigo_mesa'];
        $id_personal = $parametros['id_personal'];
        $estado = $parametros['estado'];
        // Creamos el Mesa
        $usr = new Mesa();
        $usr->id = $id;
        $usr->prefix = $codigo_mesa;
        $usr->id_personal = $id_personal;
        $usr->estado = $estado;
        
        
        Mesa::modificarMesa($usr);
        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $MesaId = $args['id'];
        Mesa::borrarMesa($MesaId);

        $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function GetDatosDelToken($request){
      $header = $request->getHeader('Authorization');
      $token = trim(str_replace("Bearer", "", $header[0]));
      $user = AutentificadorJWT::ObtenerData($token);
      
      return $user;
    }

    public function TraerDemoraPedidoMesa($request, $response, $args){

      $codigo_mesa = $args['codigo_mesa'];
      $id_orden = $args['id_orden'];
      $delay = Orden::getMaximaTardanzaOrden($id_orden, $codigo_mesa)[0]['time_order'];
      if ($delay == 0){
          echo '<h2>Table Code: '.$codigo_mesa.'<br>Waiting Time: '.$delay.' minutes.</h2>
          <h2>Your order is ready, it will be dispatched shortly. Thanks for choosing us, Bon Appetit</h2><br>';
      }else{
          echo '<h2>Table Code: '.$codigo_mesa.'<br>Order Will be ready in aprox: '.$delay.' minutes.</h2><br>';
      }
      $payload = json_encode(array("mensaje" => "Waiting Time: ".$delay." minutes"));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }
}



