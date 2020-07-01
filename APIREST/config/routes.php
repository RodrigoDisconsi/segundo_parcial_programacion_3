<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuariosController;
use App\Controllers\MateriasController;
use App\Middleware\JWTMiddleware;
use App\Middleware\LegajoMiddleware;
use App\Middleware\MayusculaMiddleware;

return function($app){
    $app->post('/usuario', UsuariosController::class.':add')->add(new LegajoMiddleware());

    $app->post('/login', UsuariosController::class.':login');

    $app->group('/materias', function (RouteCollectorProxy $group){
        $group->get('[/]', MateriasController::class.':get')->add(new MayusculaMiddleware());
        $group->post('[/]', MateriasController::class.':add');
        $group->get('/{id}', MateriasController::class.':getXId');
        $group->put('/{id}/{profesor}', MateriasController::class.':setProfesor');
        $group->put('/{id}', MateriasController::class.':inscribirse');
    })->add(new JWTMiddleware());

    // $app->group('/turnos', function (RouteCollectorProxy $group){
    //     $group->post('/mascota', TurnosController::class.':add')->add(new ValidarHorarioMiddleware());
    //     $group->get('/{id_usuario}', TurnosController::class.':getXId');
    // })->add(new JWTMiddleware());


};