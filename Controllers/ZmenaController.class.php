<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky na zmenu udaju na profilu
 */
class ZmenaController extends AController {

    /**
     * Vraci pole dat pro sablonu na zmenu udaju na profilu
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
