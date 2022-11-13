<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/checkDataMiddleware.php';
require_once './middlewares/Logger.php';
require_once './middlewares/MWAceso.php';

require_once './controllers/LoginController.php';
require_once './controllers/MesaController.php';
require_once './controllers/OrdenController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Usuario
$app->group(
  '/usuarios', function (RouteCollectorProxy $group) {
    $group->get('/listar/[{tipo}]', \UsuarioController::class . ':TraerTodos')->add(\MWAceso::class . ':esAdministrador');
    $group->get('/{nombre_usuario}', \UsuarioController::class . ':TraerUno')->add(\MWAceso::class . ':esAdministrador');
    $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->delete('/{usuarioId}', \UsuarioController::class . ':BorrarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->put('/{usuarioId}', \UsuarioController::class . ':ModificarUno')->add(\MWAceso::class . ':esAdministrador');
   // $group->post('/login[/]', \UsuarioController::class . ':Login')->add(new Logger());
  }
);



//Mesa
$app->group(
  '/mesas', function (RouteCollectorProxy $group) {
    $group->get('/listar/[{estado}]', \MesaController::class . ':TraerTodos')->add(\MWAceso::class . ':esAdministrador');
    $group->get('/{codigo_mesa}/{id_orden}', \MesaController::class . ':TraerDemoraPedidoMesa');
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->delete('/{id}', \MesaController::class . ':BorrarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->put('/{id}', \MesaController::class . ':ModificarUno')->add(\MWAceso::class . ':esAdministrador');
   // $group->post('/login[/]', \UsuarioController::class . ':Login')->add(new Logger());
  }
);

//Ordenes
$app->group(
  '/ordenes', function (RouteCollectorProxy $group) {
    $group->get('/listar/[{estado}]', \OrdenController::class . ':TraerTodos')->add(\MWAceso::class . ':esAdministrador');
    $group->get('/{codigo_mesa}/{id_orden}', \OrdenController::class . ':TraerDemoraPedidoMesa');
    $group->post('[/]', \OrdenController::class . ':CargarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->delete('/{id}', \OrdenController::class . ':BorrarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->put('/{id}', \OrdenController::class . ':ModificarUno')->add(\MWAceso::class . ':esAdministrador');
   // $group->post('/login[/]', \UsuarioController::class . ':Login')->add(new Logger());
  }
);

//Productos
$app->group(
  '/productos', function (RouteCollectorProxy $group) {
    $group->get('/listar/[{estado}]', \ProductoController::class . ':TraerTodos');
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno')->add(\MWAceso::class . ':esAdministrador');
    $group->put('/{id}', \ProductoController::class . ':ModificarUno')->add(\MWAceso::class . ':esAdministrador');
   // $group->post('/login[/]', \UsuarioController::class . ':Login')->add(new Logger());
  }
);


 //* LOGIN AREA
 $app->group('/login', function (RouteCollectorProxy $group) {
  // Take from get method
  $group->post('[/]', \LoginController::class . ':verificarUsuario'); //* It Works
});

$app->get(
  '[/]', function (Request $request, Response $response) {
    $response->getBody()->write("Slim Framework 4 PHP Eduardo Sosa");
    return $response;
  }
);

$app->run();