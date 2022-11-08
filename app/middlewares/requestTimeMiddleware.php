<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class requestTimeMiddleware{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $timeStart = new DateTime(date("H:i:s") );

        // Continua al controller
        $response = $handler->handle($request);
    
        // Despues
        $timeEnd = new DateTime(date('H:i:s'));
        
        $diffInSeconds = $timeEnd->getTimestamp() - $timeStart->getTimestamp();

        $payload = json_encode(array("Tiempo Transcurrido" => $diffInSeconds));

        $response->getBody()->write($payload);
        
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>