<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface  as Request;

use App\Models\User;
use App\Clases\Auth;

class UsuariosController{

    public function login(Request $request, Response $response){
        $body = $request->getParsedBody();
        $email = $body['email'];
        $password = $body['clave'];

        $user = User::where('email', $email)->firstOrFail();

        if(password_verify($password, $user->clave)){
            $rta = json_encode(Auth::generarToken($user->id));
        }
        else{
            $rta = json_encode('Error verifique los parametros');
        }

        $response->getBody()->write($rta);
        return $response;
    }

    public function add(Request $request, Response $response){

        $body = $request->getParsedBody();
        $email = $body['email'];
        $password = password_hash($body['clave'], PASSWORD_DEFAULT);
        $nombre = $body['nombre'];
        $legajo = $body['legajo'];
        $tipo = $body['tipo'];
        
        $obj = [
                'nombre' => $nombre,
                'clave' => $password,
                'tipo_id' => $tipo,
                'legajo' => $legajo
        ];

        $user = User::firstOrNew(['email' => $email], $obj);
        
        if(isset($user->id)){
            $response->getBody()->write("Mail ya creado.");
        }
        else{
            $response->getBody()->write(json_encode($user->save()));
        }

        return $response;
    }
}