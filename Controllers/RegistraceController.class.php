<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis uvodni stranky
 */
class RegistraceController extends AController {

    /**
     * Vraci pole dat pro sablonu registracni stranku
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        // Obsah stranky
        $this->data["obsah"] = "<h3>Registrace</h3>";

        return $this->data;
    }
}

?>
