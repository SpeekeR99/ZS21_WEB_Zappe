<?php

/**
 * Trida pro spravu prihlasovani / odhlasovani uzivatele
 */
class UserLoggingManager {

    /** @var PDO $pdo PDO objekt pro praci s databazi  */
    private $pdo;
    /** @var MySession $session Objekt pro spravu Session */
    private $session;
    /** @var string USER_SESSION_KEY Klic pro data uzivatele, ktera jsou ulozena v session. */
    private const USER_SESSION_KEY = "current_user_id";

    /**
     * Konstruktor inicializuje session pro praci se Session
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
        require_once("MySession.class.php");
        $this->session = new MySession();
    }

    /**
     * Registrace noveho uzivatele, pridani uzivatele do databaze s jeho zvolenym jmenem, emailem a heslem
     * @param string $login Login jmeno zvolene uzivatelem
     * @param string $email Email uzivatele
     * @param string $pass Heslo uzivatele
     */
    public function userRegister(string $login, string $email, string $pass) {
        // Osetreni XSS
        $login = htmlspecialchars($login);
        $email = htmlspecialchars($email);
        $pass = htmlspecialchars($pass);

        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $q = "INSERT INTO ".TABLE_USERS." (login, email, pass) VALUES (:login, :email, :passw);";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":login", $login);
        $out->bindValue(":email", $email);
        $out->bindValue(":passw", $hash);
        if ($out->execute()) {
            echo "Registrován nový uživatel.";
        } else {
            echo "ERROR: Registrace uživatele se nezdařila.";
        }
    }

    /**
     * Prihlaseni uzivatele za pomoci loginu nebo emailu a spravneho hesla
     * @param string $login Login jmeno nebo Email uzivatele
     * @param string $pass Heslo uzivatele
     * @return array|false|null Uzivatel jako pole nebo NULL
     */
    public function userLogin(string $login, string $pass) {
        // Osetreni XSS
        $login = htmlspecialchars($login);
        $pass = htmlspecialchars($pass);

        $q = "SELECT * FROM ".TABLE_USERS." WHERE login=:login OR email=:login;";
        $out = $this->pdo->prepare($q);
        $out->bindValue(":login", $login);
        if ($out->execute()) {
            $user = $out->fetchAll();
            if (count($user) > 0) {
                if (password_verify($pass, $user[0]["pass"])) {
                    echo "Přihlášení se zdařilo.<br>";
                    $this->session->addSession(self::USER_SESSION_KEY, $user[0]["id_user"]);
                    return $user[0];
                } else {
                    echo "ERROR: Špatné heslo!<br>";
                }
            } else {
                echo "ERROR: Špatný login!<br>";
            }
        }
        return null;
    }

    /**
     * Vraci informace o prihlasenem uzivatele z DB
     * @return array|null pole s informacemi o uzivateli nebo null
     */
    public function getLoggedUserData() {
        if ($this->isUserLogged()) {
            $id = $this->session->readSession(self::USER_SESSION_KEY);
            if($id == null) {
                echo "ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.<br>";
                $this->userLogout();
                return null;
            }
            $q = "SELECT * FROM ".TABLE_USERS." WHERE id_user=$id";
            $userData = $this->pdo->query($q)->fetchAll();
            if(empty($userData)) {
                echo "ERROR: Data přihlášeného uživatele se nenachází v databázi (mohl být smazán), a proto byl uživatel odhlášen.<br>";
                $this->userLogout();
                return null;
            } else {
                return $userData[0];
            }
        }
        return null;
    }

    /**
     * Kontrola, zda-li je uzivatel prihlasen
     * @return bool true pokud je uzivatel prihlaseni, false jinak
     */
    public function isUserLogged() {
        return $this->session->isSession(self::USER_SESSION_KEY);
    }

    /**
     * Odhlaseni uzivatele, odebrani klice ze Session
     */
    public function userLogout() {
        $this->session->removeSession(self::USER_SESSION_KEY);
    }

}

?>
