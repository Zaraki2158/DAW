<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . '/../Config/config.php'));

class Model {
    public static $pdo;

    public static function connexion() {
        if (!isset(self::$pdo)) {
            try {
                $host = conf::getHostName();
                $name = conf::getDataBaseName();
                $login = conf::getLogin();
                $psswd = conf::getPassword();

                self::$pdo = new PDO("mysql:host=$host;dbname=$name", $login, $psswd);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
            }
        }
    }

    
}
Model::connexion();
?>
