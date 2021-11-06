<?php

/**
 * Spoustec aplikace
 * Trida se stara o nastavovani zobrazovane stranky
 */
class AppStart {

    /**
     * Pripojeni IController pro vytvareni instanci ovladacu
     */
    public function __construct() {
        require_once(DIR_CONTROLLERS."/IController.interface.php");
    }

    /**
     * Spousteci funkce
     */
    public function run() {
        // Test, zda na pozadavek na zmenu stranky, pripadne nastaveni na default
        if (isset($_GET["page"]) && array_key_exists($_GET["page"], WEB_PAGES)) {
            $pageKey = $_GET["page"]; // Pokud existuje pozadavek, nastavim stranku na ni
        } else {
            $pageKey = DEFAULT_WEB_PAGE_KEY; // Jinak default
        }
        // Info z WEB_PAGES pod klicem co je v pageKey
        $pageInfo = WEB_PAGES[$pageKey];
        // Pripojim si odpovidajici ovladac
        require_once(DIR_CONTROLLERS."/".$pageInfo["file_name"]);
        // Vytvorim instanci odpovidajiciho ovladace
        /** @var IController $controller */
        $controller = new $pageInfo["class_name"];
        // Necham ovladac stranku vykreslit
        echo $controller->show($pageInfo["title"]);
    }

}

?>
