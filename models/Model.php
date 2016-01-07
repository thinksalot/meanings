<?php

class Model{

    const TABLE = '';

    protected static $_pdo = NULL;

    protected function _queryPDO( $sql, $bind ){

        $pdo = self::getPDO();
        $query = $pdo->prepare( $sql );

        if( !$query->execute( $bind ) ){
            $errorInfo = $query->errorInfo();
            throw new ModelException( $errorInfo[2] );
            return;
        }

        return $query;
    }

    public static function getPDO(){

        if( !self::$_pdo ){
            $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';';
            self::$_pdo = new PDO( $dsn , DB_USER, DB_PASSWORD );
        }

        return self::$_pdo;
    }

    public static function fromArray( $data ){

        $instance = new static();

        foreach( $data as $key => $value ){
            $instance->$key = $value;
        }

        return $instance;
    }

    public static function query( $sql, $bind = array() ){

        $query = self::_queryPDO( $sql, $bind );

        $list = array();

        foreach( $query->fetchAll( PDO::FETCH_ASSOC ) as $item ){
            $list[] = self::fromArray( $item );
        }

        return $list;
    }

    public static function insert( $values ){

        $keys = array_keys( $values );
        $bind = array_map( function( $key ){
            return ':' . $key;
        }, $keys );

        $sql = "INSERT INTO " . static::TABLE;
        $sql .= "(" . implode( ',', $keys ) .")";
        $sql .= " VALUES(" . implode( ',', $bind ) . ")";

        $pdo = self::getPDO();
        $sql = static::query( $sql, $values );

        return $pdo->lastInsertId();
    }

    public static function find( $criteria ){

        $sql   = "SELECT * FROM " . static::TABLE . " WHERE ";
        $where = array();

        foreach( $criteria as $col => $value ){
            $where[] = '`' . $col . '`=:' . $col;
        }

        $sql .= implode( ' AND ', $where );

        return static::query( $sql, $criteria );
    }

    public static function findOne( $criteria ){

        $rows = static::find( $criteria );
        return count( $rows ) ? reset( $rows ) : NULL;
    }

    public function update( $data ){

        $sql = "UPDATE " . static::TABLE . " SET ";

        $list = array();

        foreach( $data as $key => $value ){
            $list[] = '`' . $key . '`=:' . $key;
        }

        $sql .= implode( ',', $list );
        $sql .= " WHERE `id`=:id";

        self::_queryPDO( $sql, $data + array( 'id' => $this->id ) );

        foreach( $data as $key => $value ){
            $this->$key = $value;
        }
    }

    public function delete(){

        $sql = "DELETE FROM " . static::TABLE . " WHERE `id`=:id";
        $query = self::_queryPDO( $sql, array( 'id' => $this->id ) );
        return $query->rowCount();
    }
}

class ModelException extends Exception{}
