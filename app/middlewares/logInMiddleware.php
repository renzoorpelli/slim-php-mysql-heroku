<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';



class VerificadorMiddleWare
{
    private $suggestedClaims = array("admin", "super_admin");
   
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $reponse = new Response();

        $parametros = $request->getParsedBody();

        if(isset($parametros["clave"]) && isset($parametros["usuario"]))
        {
            if($parametros["clave"] != "" && $parametros["usuario"] != ""){

                $reponse = $handler->handle($request); // llama al controllador
            }else{
                $reponse->getBody()->write("Error hay campos vacios");
            }
        }else{
            $reponse->getBody()->write("Faltan completar campos");
        }

        return $reponse;

    }
}