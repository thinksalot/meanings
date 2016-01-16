<?php

class Setting extends Model{

    const TABLE = 'settings';
    const PRIMARY_KEY = 'key';

    public static function getAccessToken(){
        return static::findOne( array( 'key' => 'quizlet_access_token' ) );
    }
}
