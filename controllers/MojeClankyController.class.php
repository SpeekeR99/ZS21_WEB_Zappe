<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky s clanky od uzivatele
 */
class MojeClankyController extends AController {

    /**
     * Vraci pole dat pro sablonu pro stranku clanku pridanych od uzivatele
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        // Obsah stranky
        $this->data["obsah"] = "MOJE ČLÁNKY HERE";

        return $this->data;
    }
}

?>