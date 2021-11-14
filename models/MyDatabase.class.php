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
        // Odstranění stare profilove fotky ze serveru
        $q = "SELECT * FROM ".TABLE_USERS." WHERE id_user=$userId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $user = $out->fetchAll()[0];
        if(file_exists($user["picture"])) unlink($user["picture"]);

        $q = "UPDATE ".TABLE_USERS." SET picture=:picture WHERE id_user=$userId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":picture", $filepath);
        if ($out->execute()) {
            echo "Změna profilového obrázku byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna profilového obrázku se nezdařila.<br>";
        }
    }

    /* Správa uživatelů */

    /**
     * Vraci pole všech uživatelů, co jsou v DB
     * @return array|false|null Pole všech uživatelů nebo null, nepovede-li se
     */
    public function getAllUsers() {
        $q = "SELECT * FROM ".TABLE_USERS;
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            return $out->fetchAll();
        } else {
            echo "ERROR: Nepodařilo se načíst uživatele z databáze!<br>";
            return null;
        }
    }

    /**
     * Smaže uživatele z databáze
     * @param int $userId Id uživatele, který má být smazán
     */
    public function deleteUser(int $userId) {
        // Nejprve je potřeba smazat hodnoceni spojene s uzivatelem
        $q = "DELETE FROM ".TABLE_RATINGS." WHERE id_user=$userId";
        $out = $this->pdo->prepare($q);
        $out->execute();

        // Nejprve je potřeba smazat clanky spojene s uzivatelem
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE id_user=$userId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $articles = $out->fetchAll();
        foreach ($articles as $article) {
            $this->deleteArticle($article["id_article"]);
        }

        // Odstranění profilove fotky ze serveru
        $q = "SELECT * FROM ".TABLE_USERS." WHERE id_user=$userId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $user = $out->fetchAll()[0];
        if(file_exists($user["picture"])) unlink($user["picture"]);

        $q = "DELETE FROM ".TABLE_USERS." WHERE id_user=$userId";
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            echo "Odebrání uživatele bylo úspěšné.<br>";
        } else {
            echo "ERROR: Odebrání uživatele se nepodařilo!<br>";
        }
    }

    /* Články */

    /**
     * Vraci pole vsech clanku, co jsou v DB
     * @return array|false|null Pole vsechn clanku nebo null, nepovede-li se
     */
    public function getAllArticles() {
        $q = "SELECT * FROM ".TABLE_ARTICLES;
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            return $out->fetchAll();
        } else {
            echo "ERROR: Nepodařilo se načíst články z databáze!<br>";
            return null;
        }
    }

    /**
     * Funkce prida clanek do databaze
     * @param int $user Id uzivatele, ktery je autorem
     * @param string $title Nazev clanku
     * @param string $abstract Abstrakt clanku
     * @param string $filepath Cesta k PDF souboru celeho clanku
     */
    public function addArticle(int $user, string $title, string $abstract, string $filepath) {
        // XSS ošetření
        $title = htmlspecialchars($title);
        $abstract = strip_tags($abstract);
        $status = "zamitnuto";
        $review = "false";

        $q = "INSERT INTO ".TABLE_ARTICLES." (id_user, title, abstract, filepath, status, review) VALUES (:iduser, :title, :abstract, :filepath, :status, :review);";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":iduser", $user);
        $out->bindValue(":title", $title);
        $out->bindValue(":abstract", $abstract);
        $out->bindValue(":filepath", $filepath);
        $out->bindValue(":status", $status);
        $out->bindValue(":review", $review);
        if ($out->execute()) {
            echo "Přidání článku bylo úspěšné.<br>";
        } else {
            echo "ERROR: Přidání článku se nepodařilo!<br>";
        }
    }

    /**
     * Smaže clanek z databáze
     * @param int $articleId Id clanku, který má být smazán
     */
    public function deleteArticle(int $articleId) {
        // Nejprve je potřeba smazat hodnoceni spojene s clankem
        $q = "DELETE FROM ".TABLE_RATINGS." WHERE id_article=$articleId";
        $out = $this->pdo->prepare($q);
        $out->execute();

        // Odstranění PDF souboru ze serveru
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE id_article=$articleId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $article = $out->fetchAll()[0];
        unlink($article["filepath"]);

        $q = "DELETE FROM ".TABLE_ARTICLES." WHERE id_article=$articleId";
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            echo "Odebrání článku bylo úspěšné.<br>";
        } else {
            echo "ERROR: Odebrání článku se nepodařilo!<br>";
        }
    }

    /**
     * Prohazuje status clanku, jestli je ok nebo neni
     * @param int $articleId ID clanku, kde se ma prohodit status
     */
    public function swapArticleStatus(int $articleId) {
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE id_article=$articleId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $article = $out->fetchAll()[0];
        $status = $article["status"];
        $review = $article["review"];
        if ($status == "zamitnuto") {
            $status = "prijato";
            $review = "false";
        } else {
            $status = "zamitnuto";
        }
        $q = "UPDATE ".TABLE_ARTICLES." SET status=:status, review=:review WHERE id_article=$articleId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":status", $status);
        $out->bindValue(":review", $review);
        $out->execute();
    }

    /**
     * Posle clanek vsem recenzentum k posouzeni
     * @param int $articleId ID clanku, ktery ma byt posouzen
     */
    public function articleSendToReview(int $articleId) {
        $q = "UPDATE ".TABLE_ARTICLES." SET review=:review WHERE id_article=$articleId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":review", "true");
        if ($out->execute()) {
            echo "Článek byl odeslán k posouzení.<br>";
        } else {
            echo "ERROR: Nepodařilo se odeslat článek k posouzení!<br>";
        }
    }

    /**
     * Vrati vsechny clanky od daneho uzivatele
     * @param int $userid Autor clanku
     * @return array|false|null Pole clanku, ktere napsal uzivatel nebo null
     */
    public function getAllUserArticles(int $userid) {
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE id_user=$userid";
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            return $out->fetchAll();
        } else {
            echo "ERROR: Nepodařilo se načíst články z databáze!<br>";
            return null;
        }
    }

    /**
     * Provede změny v článku, vcetne zmeny PDF souboru
     * @param int $articleId ID clanku ktery ma byt menen
     * @param string $title Nazev clanku po zmene
     * @param string $abstract Abstrakt clanku po zmene
     * @param string $filepath Nove pdf pro clanek
     */
    public function changeArticleDataAndPDF(int $articleId, string $title, string $abstract, string $filepath) {
        // XSS ošetření
        $title = htmlspecialchars($title);
        $abstract = strip_tags($abstract);

        // Odstranění PDF souboru ze serveru
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE id_article=$articleId";
        $out = $this->pdo->prepare($q);
        $out->execute();
        $article = $out->fetchAll()[0];
        unlink($article["filepath"]);

        $q = "UPDATE ".TABLE_ARTICLES." SET title=:title, abstract=:abstract, filepath=:filepath WHERE id_article=$articleId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":title", $title);
        $out->bindValue(":abstract", $abstract);
        $out->bindValue(":filepath", $filepath);
        if ($out->execute()) {
            echo "Změny v článku byly uloženy.<br>";
        } else {
            echo "ERROR: Změny v článku se nepodařilo uložit!<br>";
        }
    }

    /**
     * Provede zmeny v clanku, beze zmeny cesty k PDF souboru
     * @param int $articleId ID clanku ktery ma byt menen
     * @param string $title Nazev clanku po zmene
     * @param string $abstract Abstrakt clanku po zmene
     */
    public function changeArticleDataNotPDF(int $articleId, string $title, string $abstract) {
        // XSS ošetření
        $title = htmlspecialchars($title);
        $abstract = strip_tags($abstract);

        $q = "UPDATE ".TABLE_ARTICLES." SET title=:title, abstract=:abstract WHERE id_article=$articleId;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":title", $title);
        $out->bindValue(":abstract", $abstract);
        if ($out->execute()) {
            echo "Změny v článku byly uloženy.<br>";
        } else {
            echo "ERROR: Změny v článku se nepodařilo uložit!<br>";
        }
    }

    /* Recenze */

    /**
     * Vrati vsechny clanky, ktere maji "True" v sloupci review
     * @return array|false|null pole vsech takovych clanku nebo null
     */
    public function getAllArticlesForReview() {
        $q = "SELECT * FROM ".TABLE_ARTICLES." WHERE review='true'";
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            return $out->fetchAll();
        } else {
            echo "ERROR: Nepodařilo se načíst články z databáze!<br>";
            return null;
        }
    }

    /**
     * Přidá hodnocení od uživatele na daný článek
     * @param int $userid ID uživatele který hodnotil
     * @param int $articleid ID článku který byl hodnocen
     * @param int $ratingnum číslo od 1 do 10 (ohodnocení)
     * @param string $text textová poznámka
     */
    public function addRating(int $userid, int $articleid, int $ratingnum, string $text) {
        // XSS
        $text = strip_tags($text);

        $q = "INSERT INTO ".TABLE_RATINGS." (id_user, id_article, rating, comment) VALUES (:iduser, :idarticle, :rating, :text);";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":iduser", $userid);
        $out->bindValue(":idarticle", $articleid);
        $out->bindValue(":rating", $ratingnum);
        $out->bindValue(":text", $text);
        if ($out->execute()) {
            echo "Přidání hodnocení bylo úspěšné.<br>";
        } else {
            echo "ERROR: Přidání hodnocení se nepodařilo!<br>";
        }
    }

    /**
     * Vraci pole vsech hodnoceni z DB
     * @return array|false|null pole hodnoceni nebo null
     */
    public function getAllRatings() {
        $q = "SELECT * FROM ".TABLE_RATINGS;
        $out = $this->pdo->prepare($q);
        if ($out->execute()) {
            return $out->fetchAll();
        } else {
            echo "ERROR: Nepodařilo se načíst hodnocení z databáze!<br>";
            return null;
        }
    }

    /**
     * Zmeni hodnoceni od uzivatele an dany clanek
     * @param int $ratingid ID ulozeneho hodnoceni
     * @param int $ratingnum Nove ciselne ohodnoceni
     * @param string $text Novy komentar
     */
    public function changeRating(int $ratingid, int $ratingnum, string $text) {
        // XSS
        $text = strip_tags($text);

        $q = "UPDATE ".TABLE_RATINGS." SET rating=:rating, comment=:comment WHERE id_rating=$ratingid;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":rating", $ratingnum);
        $out->bindValue(":comment", $text);
        if ($out->execute()) {
            echo "Změna hodnocení byla úspěšná.<br>";
        } else {
            echo "ERROR: Změna hodnocení se nezdařila.<br>";
        }
    }

}

?>