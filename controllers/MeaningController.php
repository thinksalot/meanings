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

            $word = $this->request->getParam( 'word' );
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
}
