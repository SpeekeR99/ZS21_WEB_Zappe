<?php

require_once(DIR_CONTROLLERS."/AController.abstract.php");

/**
 * Ovladac pro vypis stranky s clanky
 */
class ClankyController extends AController {

    /**
     * Vraci pole dat pro sablonu stranky s clanky
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array {
        $this->processForm();
        $this->prepBasicData($pageTitle);

        $this->data["allusers"] = $this->db->getAllUsers();
        $this->data["allarticles"] = $this->db->getAllArticles();
        $this->data["allratings"] = $this->db->getAllRatings();

        return $this->data;
    }
}

?>
