<?php
use function React\Promise\map;
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre_usuario = $parametros['nombre_usuario'];
        $clave = $parametros['clave'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre_usuario = $nombre_usuario;
        $usr->clave = $clave;
        $usr->nombre = $nombre;
        $usr->tipo = $tipo;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {

     
        // Buscamos usuario por nombre
        $usr = $args['nombre_usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      
        $lista = Usuario::obtenerTodos();
        $filtro="todos";
        if(isset($args['tipo']))
        {
          $filtro=$args['tipo'];
        }
        $new_array = array_filter($lista, function($obj) use ($filtro){
          if (isset($obj)) {
              foreach ($obj as $usuario) {
                if($filtro!="todos")
                {
                  if ($obj->tipo != $filtro) 
                  return false;
                }
              }
          }
          return true;
      });
        Usuario::imprimirUsuarios($lista,$filtro );
        $payload = json_encode(array("listaUsuario" =>$new_array));
       $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuarioId = $args['usuarioId'];
        $nombre_usuario = $parametros['nombre_usuario'];
        $clave = $parametros['clave'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $fechaAlta = $parametros['fecha_alta'];
        $fechaBaja = $parametros['fecha_baja'];
        // Creamos el usuario
        $usr = new Usuario();
        $usr->id = $usuarioId;
        $usr->nombre_usuario = $nombre_usuario;
        $usr->clave = $clave;
        $usr->nombre = $nombre;
        $usr->tipo = $tipo;
        $usr->fecha_alta = $fechaAlta;
        $usr->fecha_baja = $fechaBaja;
        
        Usuario::modificarUsuario($usr);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $usuarioId = $args['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Login($request, $response, $args)
    {

      $parametros = $request->getParsedBody();
      // Creamos el usuario
      $usr = new Usuario();
      $usr->nombre_usuario = $parametros['usuario'];
      $usr->clave = $parametros['clave'];
      $usuario = Usuario::obtenerUsuario($usr->nombre_usuario);
      
      if($usuario->nombre_usuario==$usr->nombre_usuario &&$usuario->clave == $usr->clave){
        $response->getBody()->write("logeado exitosamente");
      }else{
        $response->getBody()->write("Error en clave y/o usuario");
      }

        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function GetDatosDelToken($request){
      $header = $request->getHeader('Authorization');
      $token = trim(str_replace("Bearer", "", $header[0]));
      $user = AutentificadorJWT::ObtenerData($token);
      
      return $user;
    }
}



