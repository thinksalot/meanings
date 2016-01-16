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

$controller = new MeaningController( $app );

$app->get( '/', function( $request, $response, $args ) use( $controller ){
    return $controller->index();
});

$app->get( '/search/{keyword}', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'search' ), $args );
});

$app->get( '/view/{id}', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'view' ), $args );
});

$app->map( ['GET', 'POST'], '/edit/{id}', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'edit' ), $args );
});

$app->get( '/delete/{id}', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'delete' ), $args );
});

$app->map( ['GET', 'POST'], '/api', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'api' ), $args );
});

$app->get( '/import', function( $request, $response, $args ) use( $controller ){
    return call_user_func_array( array( $controller, 'import' ), $args );
});

$app->run();
