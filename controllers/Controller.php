<?php

class Controller{

    protected $app = NULL;

    protected $container = NULL;
    protected $response = NULL;
    protected $request = NULL;

    public function __construct( $app ){

        $this->app = $app;
        $this->container = $app->getContainer();
        $this->response = $this->container->get( 'response' );
        $this->request = $this->container->get( 'request' );
    }

    public function parseView( $view, $data ){

        ob_start();
        extract( $data );
        include( VIEW_DIR . '/' . $view );
        $view = ob_get_clean();
        return $view;
    }

    public function render( $template, $data ){

        $url = (string) $this->request->getUri()->getBaseUrl()
            . $this->request->getUri()->getPath()
        ;
        $data['_url'] = $url;
        $view = $this->parseView( $template, $data );

        ob_start();
        extract( array( 'view' => $view, 'data' => $data ) );
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

    public function addInfo( $msg ){
        $this->container->flash->addMessage( '', array( 'type' => 'info', 'msg' => $msg ) );
    }

    public function renderFlash(){
        $html = $this->parseView( 'flash-messages.php', array() );
        echo $html;
    }
}
