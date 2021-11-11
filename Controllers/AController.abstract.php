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
        // Prihlaseny uzivatel
        $this->data["user"] = $this->loginManager->getLoggedUserData();
        $this->data["user"]["logged"] = $this->loginManager->isUserLogged();
    }

    /**
     * Metoda pro zpracovani odeslanych formularu
     */
    protected function processForm() {
        if (isset($_POST["action"])) {
            ob_start();
            if ($_POST["action"] == "register") {
                if (isset($_POST["pass"]) && isset($_POST["passagain"])) {
                     if($_POST["pass"] == $_POST["passagain"]) {
                         if (isset($_POST["email"]) && isset($_POST["login"])) {
                             $this->loginManager->userRegister($_POST["login"], $_POST["email"], $_POST["pass"]);
                         }
                     } else {
                         echo "ERROR: Hesla se neshodují!";
                     }
                }
            }
            else if ($_POST["action"] == "login") {
                if (isset($_POST["login"]) && isset($_POST["pass"])) {
                    $this->loginManager->userLogin($_POST["login"], $_POST["pass"]);
                }
            }
            else if ($_POST["action"] == "change") {
                if (isset($_POST["passold"]) && isset($_POST["passagainold"])) {
                    if ($_POST["passold"] == $_POST["passagainold"]) {
                        $user = $this->loginManager->getLoggedUserData();
                        if (password_verify($_POST["passold"], $user["pass"])) {
                            if (isset($_POST["loginnew"]) || isset($_POST["emailnew"]) || (isset($_POST["passnew"]) && isset($_POST["passagainnew"]))) {
                                if ($_POST["passnew"] == $_POST["passagainnew"]) {
                                    if ($_POST["loginnew"] != "") {
                                        echo "Změna loginu<br>";
//                                        $this->db->changeUserLogin($user["id_user"], $_POST["loginnew"]);
                                    }
                                    if ($_POST["emailnew"] != "") {
                                        echo "Změna emailu<br>";
//                                        $this->db->changeUserEmail($user["id_user"], $_POST["emailnew"]);
                                    }
                                    if ($_POST["passnew"] != "") {
                                        echo "Změna hesla<br>";
//                                        $this->db->changeUserPass($user["id_user"], $_POST["passnew"]);
                                    }
                                } else {
                                    echo "ERROR: Nová hesla se neshodují!";
                                }
                            }
                        } else {
                            echo "ERROR: Zadané heslo je špatné!";
                        }
                    } else {
                        echo "ERROR: Stará hesla se neshodují!";
                    }
                }
            }
            else if($_POST["action"] == "logout") {
                $this->loginManager->userLogout();
            }
            $prompt = ob_get_clean();
            if (explode(' ',trim($prompt))[0] == "ERROR:") {
                $this->data["error"] = $prompt;
            } else {
                $this->data["prompt"] = $prompt;
            }
        }
    }

}

?>
