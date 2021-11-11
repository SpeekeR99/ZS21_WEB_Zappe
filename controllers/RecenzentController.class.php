<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky pro vyplneni formulare na zadost na stani se recenzentem
 */
class RecenzentController extends AController {

    /**
     * Vraci pole dat pro sablonu pro stranku pro zadost na stani se recenzentem
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        // Obsah stranky
        $this->data["obsah"] = "RECENZENT CHCES BYT JO?";

        return $this->data;
    }
}

?>
