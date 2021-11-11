<?php

/**
 * Trida pro praci se Session
 */
class MySession {

    /**
     * Pri vytvoreni instance tridy se zahaji Session
     */
    public function __construct() {
        session_start();
    }

    /**
     * Ulozeni hodnoty pod klic do super globalni promenne SESSION
     * @param string $key klic pod ktery se uklada hodnota
     * @param mixed $value hodnota ukladajici se pod klic
     */
    public function addSession(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Zkontrolovani, jestli Session pod klicem $key existuje
     * @param string $key klic ktery se kontroluje
     * @return bool true pokud Session existuje, false jinak
     */
    public function isSession(string $key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Vraci ID prihlaseneho uzivatele, ID z databaze
     * @param string $key klic ktereho hodnota se vraci
     * @return mixed|null ID z DB uzivatele, ktery je prihlasen nebo null
     */
    public function readSession(string $key) {
        if($this->isSession($key)) return $_SESSION[$key];
        return null;
    }

    /**
     * Odstrani danou Session
     * @param string $key klic Session, ktera se ma odstranit
     */
    public function removeSession(string $key) {
        unset($_SESSION[$key]);
    }

}

?>
