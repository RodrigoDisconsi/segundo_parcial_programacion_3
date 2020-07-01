<?php
namespace App\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Clases\Auth;

class JWTMiddleware
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

        $token = $request->getHeader('token')[0];
        if (Auth::validarToken($token)) {
            $response = new Response();
            $resp = $handler->handle($request);
            $existingContent = (string) $resp->getBody();
            $response->getBody()->write($existingContent);
        } else {
            $response = new Response();
            $response->getBody()->write('NO autorizado');
        }
        return $response;
    }
}