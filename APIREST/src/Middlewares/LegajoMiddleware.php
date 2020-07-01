<?php
namespace App\Middleware;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LegajoMiddleware
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
        $body = $request->getParsedBody();
        $legajo = (int)$body['legajo'];
        $esta = User::where('legajo', $legajo)->get();
        
        if ($legajo >= 1000 && $legajo <= 2000 && count($esta) == 0) {
            $response = new Response();
            $resp = $handler->handle($request);
            $existingContent = (string) $resp->getBody();
            $response->getBody()->write($existingContent);
        } else {
            $response = new Response();
            $response->getBody()->write('Legajo invalido.');
        }
        return $response;
    }
}