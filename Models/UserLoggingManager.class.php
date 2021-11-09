<?php

/**
 * Trida pro spravu prihlasovani / odhlasovani uzivatele
 */
class UserLoggingManager {

    /** @var MySession $session Objekt pro spravu Session */
    private $session;
    /** @var string USER_SESSION_KEY Klic pro data uzivatele, ktera jsou ulozena v session. */
    private const USER_SESSION_KEY = "current_user_id";

    /**
     * Konstruktor inicializuje session pro praci se Session
     */
    public function __construct() {
        require_once("MySession.class.php");
        $this->session = new MySession();
    }

    /**
     * Prihlaseni uzivatele, pridani klice do Session
     * @return bool true pokud se prihlaseni podarilo, false jinak
     */
    public function userLogin() {
        $this->session->addSession(self::USER_SESSION_KEY, "id");
        return true;
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
