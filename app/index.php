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
// require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';

require_once './middlewares/logInMiddleware.php';
require_once './middlewares/requestTimeMiddleware.php';
require_once './middlewares/jwtChecker.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{usuarioId}', \UsuarioController::class . ':BorrarUno');
    $group->post('/login', \UsuarioController::class . ':Login')->add(new VerificadorMiddleWare());
})->add(new jwtChecker()); // valido que tenga un jwt en la cabecera

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write('<a href="https://www.youtube.com/watch?v=kKERx6iP9eE"> Ver Video </a>');

    sleep(4);

    
    return $response;

})->add(new requestTimeMiddleware());;


$app->group('/jwt', function (RouteCollectorProxy $group) {

  $group->post('/login', \UsuarioController::class . ':Login')->add(new VerificadorMiddleWare());


});






$app->run();
