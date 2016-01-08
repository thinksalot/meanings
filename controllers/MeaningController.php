<?php

class MeaningController extends Controller{

    public function index(){

        $sortBy = strtolower( $this->request->getParam( 'sort' ) );
        $sortBy = in_array( $sortBy, array( 'created', 'word' ) ) ? $sortBy : 'created';

        $words = Meaning::query( 'SELECT * FROM meanings ORDER BY ' . $sortBy . ' ASC' );
        $this->render( 'meaning/index.php', [ 'words' => $words, 'sorting' => $sortBy, 'title' => 'Index' ] );
    }

    public function search( $keyword ){

        $sortBy = strtolower( $this->request->getParam( 'sort' ) );
        $sortBy = in_array( $sortBy, array( 'created', 'word' ) ) ? $sortBy : 'created';

        $words = Meaning::query(
            "SELECT * FROM meanings WHERE word LIKE ? ORDER BY " . $sortBy . " ASC",
            array( '%' . $keyword . '%' )
        );

        $this->render( 'meaning/index.php', [ 'words' => $words, 'sorting' => $sortBy, 'keyword' => $keyword ] );
    }

    public function view( $id ){

        $row = Meaning::findOne( array( 'id' => $id ) );

        if( !$row ){
            return $this->response->withRedirect( '/' );
        }

        return $this->render( 'meaning/view.php', [ 'item' => $row ] );
    }

    public function edit( $id ){

        $row = NULL;

        if( $id != 'new' ){

            $row = Meaning::findOne( array( 'id' => $id ) );

            if( !$row ){
                return $this->response->withRedirect( '/' );
            }
        }

        if( $this->request->isPost() ){

            $word = trim($this->request->getParam( 'word' ) );
            $meaning = $this->request->getParam( 'meaning' );

            if( empty( $word ) || empty( $meaning ) ){
                $this->addError( 'Empty fields' );
                return $this->response->withRedirect( '/edit/' . $id );
            }

            if( $row ){

                $row->update( array(
                    'word'    => $word    ,
                    'meaning' => $meaning
                ) );

                $this->addSuccess( 'Meaning updated' );
                return $this->response->withRedirect( '/' );
            }

            $insertId = Meaning::insert( array(
                'word'    => $word                 ,
                'meaning' => $meaning              ,
                'created' => date( 'Y-m-d H:i:s' )
            ) );

            $this->addSuccess( 'Meaning added' );
            return $this->response->withRedirect( '/' );
        }

        return $this->render( 'meaning/edit.php', [ 'item' => $row ] );
    }

    public function delete( $id ){

        $row = Meaning::findOne( array( 'id' => $id ) );

        if( !$row ){
            $this->addError( 'Meaning not found' );
            return $this->response->withRedirect( '/' );
        }

        if( $row->delete() ){
            $this->addSuccess( 'Meaning deleted' );
        }
        else{
            $this->addError( 'Error deleting' );
        }

        return $this->response->withRedirect( '/' );
    }

    public function authorize( $redirectTo = '/' ){

        $code = $this->request->getParam( 'code' );
        $state = isset( $_SESSION['state'] ) ? $_SESSION['state'] : NULL;

        if( !empty( $code ) && $this->request->getParam( 'state') == $state ){

            $quizlet = new Quizlet;
            $accessToken = $quizlet->getAccessToken( $code, SITE_URL . $redirectTo );
            $_SESSION['access_token'] = $accessToken;
            return $this->response->withRedirect( $redirectTo );
        }

        $_SESSION['state'] = md5( mt_rand().microtime( true ) );

        $urlParams = array(
            'client_id'     => QUIZLET_CLIENT_ID        ,
            'client_secret' => QUIZLET_CLIENT_SECRET    ,
            'response_type' => 'code'                   ,
            'scope'         => 'read write_set'         ,
            'state'         => $_SESSION['state']       ,
            'redirect_uri'  => SITE_URL . $redirectTo
        );

        $url = Quizlet::$authUrl . '?' . http_build_query( $urlParams );
        return $this->response->withRedirect( $url );
    }

    public function import(){

        if( !isset( $_SESSION['access_token'] ) ){
            return $this->authorize( 'import' );
        }

        $quizlet = new Quizlet;
        $terms = $quizlet->getTerms();

        if( empty( $terms ) ){
            $this->addSuccess( '0 terms downloaded' );
            return $this->response->withRedirect( '/' );
        }

        $insertCount = 0;

        foreach( $terms as $term ){

            $row = Meaning::findOne( array( 'word' => $term->term ) );

            # meaning already exists
            if( $row ){
                continue;
            }

            $insertId = Meaning::insert( array(
                'word'    => $term->term       ,
                'meaning' => $term->definition
            ) );

            $insertCount++;
        }

        $this->addSuccess( $insertCount . ' terms downloaded' );
        return $this->response->withRedirect( '/' );
    }
}
