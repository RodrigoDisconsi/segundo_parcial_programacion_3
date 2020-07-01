<?php

namespace App\Clases;
 
use \Firebase\JWT\JWT;

class Auth{

    public static function generarToken($id){
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => "1356999524",
            "nbf" => "1357000000",
            "iss" => "http://example.org",
            "id" => $id
        );

        $jwt = JWT::encode($payload, "clave_secreta");

        return $jwt;
    }

    public static function validarToken($token){
        if(empty($token) || $token === ""){
            return false;
        }
        try{
            $decodificado = JWT::decode($token, "clave_secreta", ['HS256']);
            return $decodificado;
        }
        catch (\Exception $e){
            return false;
        }
    }
}