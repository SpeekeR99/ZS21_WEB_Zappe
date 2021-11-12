<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky pro recenzenty
 */
class RecenzeController extends AController {

    /**
     * Vraci pole dat pro sablonu pro stranku pro recenzenty
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        $this->data["allusers"] = $this->db->getAllUsers();
        $this->data["articles"] = $this->db->getAllArticlesForReview();

        return $this->data;
    }
}

?>
