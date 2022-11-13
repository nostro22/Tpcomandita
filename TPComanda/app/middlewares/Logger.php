<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Logger
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $parametros = $request->getParsedBody();
        if (isset($parametros['clave']) && isset($parametros['usuario'])) {
            if ($parametros['clave'] == "" || $parametros['usuario'] == "") {
                $response->getBody()->write("Error Campo vacio ");
            } else {

                $response = $handler->handle($request);
            }
        } else {
            $response->getBody()->write("Datos incompletos");
        }

        return $response;
    }
}