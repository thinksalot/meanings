<?php

class Quizlet{

    public static $authUrl = 'https://quizlet.com/authorize';

    public static $tokenUrl = 'https://api.quizlet.com/oauth/token';

    protected function _getCurl(){

        $curl = new \Curl\Curl();
        return $curl;
    }

    public function getAccessToken( $code, $redirectTo ){

        $curl = $this->_getCurl();
        $curl->setOpt( CURLOPT_USERPWD, QUIZLET_CLIENT_ID . ':' . QUIZLET_CLIENT_SECRET );
        $curl->post( self::$tokenUrl, array(
            'code'         => $code                ,
            'redirect_uri' => $redirectTo          ,
            'grant_type'   => 'authorization_code'
        ) );

        if( $curl->error ){
            throw new Exception( 'Curl Error: ' . $curl->errorCode . ':' . $curl->errorMessage );
            return;
        }

        return $curl->response->access_token;
    }

    public function getTerms(){

        $curl = $this->_getCurl();
        $curl->setHeader( 'Authorization' , 'Bearer ' . $_SESSION['access_token'] );
        $curl->get( 'https://api.quizlet.com/2.0/sets/' . QUIZLET_SET_ID . '/terms' );

        if( $curl->error ){
            throw new Exception( 'Curl Error: ' . $curl->errorCode . ':' . $curl->errorMessage );
            return;
        }

        return $curl->response;
    }
}
