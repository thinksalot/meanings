<?php

class Controller{

    protected $app = NULL;
    protected $pdo = NULL;

    protected $container = NULL;
    protected $response = NULL;
    protected $request = NULL;

    public function __construct( $app ){

        $this->app = $app;
        $this->container = $app->getContainer();
        $this->response = $this->container->get( 'response' );
        $this->request = $this->container->get( 'request' );

        $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';';
        $this->pdo = new PDO( $dsn , DB_USER, DB_PASSWORD );

    }

    public function parseView( $view, $data ){

        ob_start();
        extract( $data );
        include( VIEW_DIR . '/' . $view );
        $view = ob_get_clean();
        return $view;
    }

    public function render( $template, $data ){

        $view = $this->parseView( $template, $data );

        ob_start();
        extract( array( 'view' => $view ) );
        include( VIEW_DIR . 'base.php' );
        $output = ob_get_clean();

        $this->response->getBody()->write( $output );
    }

    public function addSuccess( $msg ){
        $this->container->flash->addMessage( '', array( 'type' => 'success', 'msg' => $msg ) );
    }

    public function addError( $msg ){
        $this->container->flash->addMessage( '', array( 'type' => 'error', 'msg' => $msg ) );
    }

}
