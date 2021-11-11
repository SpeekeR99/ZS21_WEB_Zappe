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

    /* Změny týkající se uživatele, když si změní údaje na svém profilu */

    /**
     * Zmena uzivatelskeho loginu
     * @param int $userId ID prihlaseneho uzivatele, ktery zada o zmenu
     * @param string $newLogin Novy login, ktery chce uzivatel pouzivat
     */
    public function changeUserLogin(int $userId, string $newLogin) {
        // XSS Ošetření
        $newLogin = htmlspecialchars($newLogin);

        $q = "UPDATE ".TABLE_USERS." SET login=:login WHERE id_user=$userId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":login", $newLogin);
        if ($out->execute()) {
            echo "Změna loginu byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna loginu se nezdařila.<br>";
        }
    }

    /**
     * Zmena uzivatelskeho e-mailu
     * @param int $userId ID prihlaseneho uzivatele, ktery zada o zmenu
     * @param string $newEmail Novy email, ktery chce uzivatel pouzivat
     */
    public function changeUserEmail(int $userId, string $newEmail) {
        // XSS Ošetření
        $newEmail = htmlspecialchars($newEmail);

        $q = "UPDATE ".TABLE_USERS." SET email=:email WHERE id_user=$userId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":email", $newEmail);
        if ($out->execute()) {
            echo "Změna e-mailu byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna e-mailu se nezdařila.<br>";
        }
    }

    /**
     * Zmena uzivatelskeho hesla
     * @param int $userId ID prihlaseneho uzivatele, ktery zada o zmenu
     * @param string $newPass Nove heslo, ktere chce uzivatel pouzivat
     */
    public function changeUserPass(int $userId, string $newPass) {
        // XSS Ošetření
        $newPass = htmlspecialchars($newPass);

        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $q = "UPDATE ".TABLE_USERS." SET pass=:passw WHERE id_user=$userId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":passw", $hash);
        if ($out->execute()) {
            echo "Změna hesla byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna hesla se nezdařila.<br>";
        }
    }

    /**
     * Zmena profiloveho obrazku
     * @param int $userId ID prihlaseneho uzivatele, ktery zada o zmenu
     * @param string $filepath Cesta k profilovemu obrazku, ktery musel projit uploadem na server
     */
    public function changeUserProfilePic(int $userId, string $filepath) {
        $q = "UPDATE ".TABLE_USERS." SET picture=:picture WHERE id_user=$userId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":picture", $filepath);
        if ($out->execute()) {
            echo "Změna profilového obrázku byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna profilového obrázku se nezdařila.<br>";
        }
    }

}

?>