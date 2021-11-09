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
     * @param string $key klic ktery se kotnroluje
     * @return bool true pokud Session existuje, false jinak
     */
    public function isSession(string $key) {
        return isset($_SESSION[$key]);
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
