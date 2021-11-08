<?php

require_once(DIR_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac pro vypis uvodni stranky
 */
class UvodController implements IController {

    /**
     * Vraci pole dat pro sablonu uvodni stranky
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        // Data pro sablonu
        $data = [];
        // Nazev stranky
        $data["title"] = $pageTitle;
        // Nav
        $data["nav"] = WEB_PAGES;
        // Obsah stranky
        $data["obsah"] = "<h3>HELLO WORLD!</h3>";

        return $data;
    }
}

?>
