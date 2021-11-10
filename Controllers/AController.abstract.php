<?php

/**
 * Rozhrani generickeho ovladace
 */
abstract class AController {

    /** @var array $data Data pro sablonu */
    protected $data;
    /** @var UserLoggingManager $loginManager Objekt pro praci se prihlasovanim a odhlasovanim */
    protected $loginManager;
    /** @var MyDatabase $db Objekt pro praci s databazi */
    protected $db;

    /**
     * Konstruktor inicializuje session a db pro praci se Session a Databazi
     */
    public function __construct() {
        require_once(DIR_MODELS."/UserLoggingManager.class.php");
        require_once(DIR_MODELS."/MyDatabase.class.php");
        $this->loginManager = new UserLoggingManager();
        $this->db = new MyDatabase();
    }

    /**
     * Metoda prepData musi zajistit spravna data pro sablonu
     *
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public abstract function prepData(string $pageTitle): array;

    /**
     * Metoda zajisti zakladni genericka data spolecna pro vsechny controllery a sablony
     * @param string $pageTitle Nazev stranky
     */
    protected function prepBasicData(string $pageTitle) {
        // Data pro sablonu
        $this->data = [];
        // Nazev stranky
        $this->data["title"] = $pageTitle;
        // Nav
        $this->data["nav"] = NAV_WEB_PAGES;
        // Info o uzivateli
        $this->data["user"] = [
//            "logged" => $this->session->isSession($this->userSessionKey),
            "logged" => $this->loginManager->isUserLogged(),
            "nick" => "NickName Here",
            "pravo" => 3,
            "picture" => "data/my_avatar.png"
        ];
    }

    /**
     * Metoda pro zpracovani odeslanych formularu
     */
    protected function processForm() {
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "login") {
                $this->loginManager->userLogin();
            }
            else if($_POST["action"] == "logout") {
                $this->loginManager->userLogout();
            }
        }
    }

}

?>
