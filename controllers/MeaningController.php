<?php

class MeaningController extends Controller{

    public function index(){

        $query = $this->pdo->query( 'SELECT * FROM meanings' );
        $words = $query->fetchAll( PDO::FETCH_OBJ );

        $this->render( 'meaning/index.php', [ 'words' => $words ] );
    }

    public function view( $id ){

        $query = $this->pdo->prepare( "SELECT * FROM meanings WHERE id=:id" );
        $query->execute( array( 'id' => $id ) );
        $row = $query->fetch( PDO::FETCH_OBJ );

        if( !$row ){
            return $this->response->withRedirect( '/' );
        }

        return $this->render( 'meaning/view.php', [ 'item' => $row ] );
    }

    public function edit( $id ){

        $row = NULL;

        if( $id != 'new' ){

            $query = $this->pdo->prepare( "SELECT * FROM meanings WHERE id=:id" );
            $query->execute( array( 'id' => $id ) );
            $row = $query->fetch( PDO::FETCH_OBJ );

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

                $query = $this->pdo->prepare( 'UPDATE meanings SET word=:word, meaning=:meaning WHERE id=:id' );
                $query->execute( array( 'word' => $word, 'meaning' => $meaning, 'id' => $id ) );

                $query = $this->pdo->prepare( "SELECT * FROM meanings WHERE id=:id" );
                $query->execute( array( 'id' => $id ) );
                $row = $query->fetch( PDO::FETCH_OBJ );

                $this->addSuccess( 'Meaning updated' );
                return $this->response->withRedirect( '/' );
            }

            $query = $this->pdo->prepare( 'INSERT INTO meanings(word, meaning, created) VALUES(:word, :meaning, :date)' );
            $query->execute( array( 'word' => $word, 'meaning' => $meaning, 'date' => date( 'Y-m-d H:i:s' ) ) );

            $this->addSuccess( 'Meaning added' );
            return $this->response->withRedirect( '/' );
        }

        return $this->render( 'meaning/edit.php', [ 'item' => $row ] );
    }

    public function delete( $id ){

        $query = $this->pdo->prepare( "DELETE FROM meanings WHERE id=:id" );
        $query->execute( array( 'id' => $id ) );

        $this->addSuccess( 'Meaning deleted' );
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

            $query = $this->pdo->prepare( 'SELECT * FROM meanings WHERE word=:term' );
            $query->execute( array( 'term' => $term->term ) );

            if( $query->rowCount() ){
                continue;
            }

            $query = $this->pdo->prepare( 'INSERT INTO meanings (word, meaning) VALUES(:term, :definition)' );
            $query->execute( array( 'term' => $term->term, 'definition' => $term->definition ) );

            $insertCount++;
        }

        $this->addSuccess( $insertCount . ' terms downloaded' );
        return $this->response->withRedirect( '/' );
    }
}
