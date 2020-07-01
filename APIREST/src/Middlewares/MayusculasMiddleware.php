<?php
namespace App\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Clases\Auth;

class MayusculaMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $resp = $handler->handle($request);
        $existingContent = json_decode((string)$resp->getBody());
        foreach ($existingContent as $element) {
            if($element->vacantes == 0){
                $element->materia = strtoupper($element->materia); 
                $element->profesor = strtoupper($element->profesor); 
                $element->email = strtoupper($element->email); 
            }
        }
        $response->getBody()->write(json_encode($existingContent));
        return $response;
    }
}