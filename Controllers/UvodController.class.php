<?php

require_once(DIR_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac pro vypis uvodni stranky
 */
class UvodController implements IController {

    /**
     * Vraci HTML obsah uvodni stranky
     * @param string $pageTitle Nazev stranky
     * @return string HTML z view
     */
    public function show(string $pageTitle): string {
        // Data pro view
        global $data;
        $data = [];
        // Nazev stranky
        $data["title"] = $pageTitle;

        // Output buffer
        ob_start();
        // Pripojeni + vykonani sablony
        require(DIR_VIEWS."/UvodTemplate.tpl.php");
        // Output buffer do promenne obsah a ta se vrati (obsah stranky)
        $obsah = ob_get_clean();
        return $obsah;
    }
}

?>
