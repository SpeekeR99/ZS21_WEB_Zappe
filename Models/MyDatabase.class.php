<?php

/**
 * Trida pro praci s databazi
 */
class MyDatabase {

    /** @var PDO $pdo PDO objekt pro praci s databazi  */
    private $pdo;

    /**
     * Konstruktor inicializuje pdo pro praci s databazi
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
    }

}

?>