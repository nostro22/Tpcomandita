<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class checkDataMiddleware
{

    private $tipos = [
        "administrador",
        "bartender",
        "cervecero",
        "mozo",
        "cocinero"
    ];
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $parametros = $request->getParsedBody();
        $nombre_usuario = $parametros['nombre_usuario'];
        $clave = $parametros['clave'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];

        try {
            if (!empty($nombre_usuario) && !empty($clave) && !empty($tipo) && !empty($nombre)) {
                $nombre_usuarioRegistrado = Usuario::obtenerUsuario($nombre_usuario);
                if (in_array($tipo, $this->tipos)) { 
                    if($nombre_usuarioRegistrado==false)
                    {
                        $response = $handler->handle($request);
                    }
                    else
                    {
                        $response->getBody()->write(json_encode(array("error" => "Nombre de usuario ya registrado se cancela el registro")));
                        $response = $response->withstatus(401);
                    }
                    
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Tipo incorrecto de usuario")));
                    $response = $response->withstatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("error" => "Faltan datos necesarios")));
                $response = $response->withstatus(401);
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }        
        return $response;
    }
}