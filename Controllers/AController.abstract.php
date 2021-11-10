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
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public abstract function prepData(string $pageTitle): array;

    /**
     * Metoda zajisti zakladni genericka data spolecna pro vsechny controllery a sablony
     * @param string $pageTitle Nazev stranky
     */
    protected function prepBasicData(string $pageTitle) {
        // Nazev stranky
        $this->data["title"] = $pageTitle;
        // Nav webovky
        $this->data["nav"] = NAV_WEB_PAGES;
    }

    /**
     * Metoda pro zpracovani odeslanych formularu
     */
    protected function processForm() {
        ob_start();
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "register") {
                if (isset($_POST["email"]) && isset($_POST["login"]) && isset($_POST["pass"])) {
                    $this->loginManager->userRegister($_POST["login"], $_POST["email"], $_POST["pass"]);
                }
            }
            else if ($_POST["action"] == "login") {
                if (isset($_POST["login"]) && isset($_POST["pass"])) {
                    $this->data["user"] = $this->loginManager->userLogin($_POST["login"], $_POST["pass"]);
                }
            }
            else if($_POST["action"] == "logout") {
                $this->loginManager->userLogout();
            }
        }
        $this->data["user"]["logged"] = $this->loginManager->isUserLogged();
        $prompt = ob_get_clean();
        if (explode(' ',trim($prompt))[0] == "ERROR:") {
            $this->data["error"] = $prompt;
        } else {
            $this->data["prompt"] = $prompt;
        }
    }

}

?>
