<?php
class conf {
    static private $data = array(
        'dbhost' => 'localhost',
        'dbname' => 'Projet',
        'login' => 'root',
        'password' => ''
    );

    public static function getHostname() {
        return self::$data['dbhost'];
    }

    public static function getDatabaseName() {
        return self::$data['dbname'];
    }

    public static function getLogin() {
        return self::$data['login'];
    }

    public static function getPassword() {
        return self::$data['password'];
    }
}
?>
