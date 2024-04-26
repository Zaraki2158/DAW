<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . '\..\Config/config.php'));
require_once(realpath(dirname(__FILE__) . '\..\Model\modele_Quiz.php'));

class Controleur_Quiz {

    public static function read(){
        Modele_Quiz::read();
    }
    public static function readAll() {
        Modele_Quiz::readAll();
    }

    public static function readAll_Admin() {
        Modele_Quiz::readAll_Admin();
    }

    public static function add(){
        Modele_Quiz::add();
    }

    public static function addQCM(){
        Modele_Quiz::addQCM();
    }
    public static function supprimer(){
        Modele_Quiz::supprimer();
		exit();
    }

    public static function save(){
        Modele_Quiz::save();
    }
    public static function readQuiz()
    {
        Modele_Quiz::readQuiz();
    }

}

?>