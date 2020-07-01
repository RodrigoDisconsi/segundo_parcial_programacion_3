<?php
use Slim\App;
use App\Middleware\AfterMiddleware;
use App\Middleware\JWTMiddleware;

return function (App $app){
    $app->addBodyParsingMiddleware();

    // $app->add(new JWTMiddleware());
    $app->add(new AfterMiddleware());
};