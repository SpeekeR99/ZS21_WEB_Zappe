<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky se spravou uzivatelu
 */
class SpravaUzivateluController extends AController {

    /**
     * Vraci pole dat pro sablonu stranky se spravou uzivatelu
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        // Obsah stranky
        $this->data["obsah"] = "SPRÁVA UŽIVATELŮ";

        return $this->data;
    }
}

?>
