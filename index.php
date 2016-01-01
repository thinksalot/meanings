<?php

session_start();
ini_set( 'display_errors', true );

require 'config.php';
require 'vendor/autoload.php';

require 'controllers/Controller.php';
require 'controllers/MeaningController.php';

$app = new \Slim\App( [ 'settings' => [ 'displayErrorDetails' => 'true' ] ] );

$container = $app->getContainer();
$container['view'] = function( $container ){ return new \Slim\Views\PhpRenderer( 'views/' ); };
$container['flash'] = function(){ return new \Slim\Flash\Messages(); };

$app->get( '/', function( $request, $response, $args ) use( $app ){

    $controller = new MeaningController( $app );
    return $controller->index();
});

$app->get( '/view/{id}', function( $request, $response, $args ) use( $app ){

    $controller = new MeaningController( $app );
    return call_user_func_array( array( $controller, 'view' ), $args );
} );

$app->map( ['GET', 'POST'], '/edit/{id}', function( $request, $response, $args ) use( $app ){

    $controller = new MeaningController( $app );
    return call_user_func_array( array( $controller, 'edit' ), $args );
} );

$app->get( '/delete/{id}', function( $request, $response, $args ) use( $app ){

    $controller = new MeaningController( $app );
    return call_user_func_array( array( $controller, 'delete' ), $args );
} );

$app->run();
