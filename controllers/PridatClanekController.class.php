<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky, kde se pridava novy clanek
 */
class PridatClanekController extends AController {

    /**
     * Vraci pole dat pro sablonu pro stranku, kde se pridava novy clanek
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
