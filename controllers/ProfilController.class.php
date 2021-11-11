<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis profilove stranky
 */
class ProfilController extends AController {

    /**
     * Vraci pole dat pro sablonu profilove stranku
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        return $this->data;
    }
}

?>
