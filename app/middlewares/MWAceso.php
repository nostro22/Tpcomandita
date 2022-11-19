<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAceso
{
    private $tipos = [
        "administrador",
        "bartender",
        "cervecero",
        "mozo",
        "cocinero"
    ];


    public function validarToken($request, $rHandler)
    {
        $header = $request->getHeaderLine('Authorizacion');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);
            $response = $rHandler->handle($request);
        } else {
            $response->getBody()->write(json_encode(array("Token error" => "necesitas token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function esAdministrador($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);

                if ($data->tipo == 'administrador') {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo administradores tienen acceso")));
                    $response = $response->withStatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("Admin error" => "Necesitan el token de Administrador")));
                $response = $response->withStatus(401);
            }

        } catch (\Throwable $th) {
            echo $th->getMessage();
            $response = $response->withStatus(401);
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function esEmpleado($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if (in_array($data->tipo, $this->tipos)) {
                    //TODO: Validate for all the user types

                    if ($data->tipo != "administrador") {
                        // Verify the token of the user.
                        $response = $handler->handle($request);
                    }
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo personal registrado tiene acceso")));
                    $response = $response->withStatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("error" => "Se necesita token")));
                $response = $response->withStatus(401);
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Checks if the user is Barman.
     *
     * @param Request $request The request object.
     * @param RequestHandler $handler The handler object.
     */
    public function esBartender($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "bartender" || $data->tipo == "administrador") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo Bartender o Administrador tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Administrador error" => "Solo token de Bartender o Administrador tienen acceso")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Checks if the user is Barman.
     *
     * @param Request $request The request object.
     * @param RequestHandler $handler The handler object.
     */
    public function esCocinero($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "cocinero" || $data->tipo == "administrador") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo cocinero o Administrador tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Administrador error" => "Solo token de  Cocinero o Administrador tienen acceso")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esMozo($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if (
                    $data->tipo == "mozo"
                    || $data->tipo == "administrador"
                ) {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo Mozo o Administrador tienen acceso")));
                    $response = $response->withStatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("Administrador error" => "Solo token de Mozo o Administrador tienen acceso")));
                $response = $response->withStatus(401);
            }
        } catch (\Throwable $th) {
            
            echo $th->getMessage();
            $response = $response->withStatus(401);
            $response->getBody()->write(json_encode(array("Token error" => "necesitas token")));
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    public function esCervecero($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if (
                    $data->tipo == "cervecero"
                    || $data->tipo == "administrador"
                ) {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo Cervecero o Administrador tienen acceso")));
                    $response = $response->withStatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("Administrador error" => "Solo token de Cervecero o Administrador tienen acceso")));
                $response = $response->withStatus(401);
            }
        } catch (\Throwable $th) {
            
            echo $th->getMessage();
            $response = $response->withStatus(401);
            $response->getBody()->write(json_encode(array("Token error" => "necesitas token")));
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>