<?php

/**
 * Rozhrani generickeho ovladace
 */
abstract class AController {

    /** @var array $data Data pro sablonu */
    protected $data;

    /**
     * Metoda prepData musi zajistit spravna data pro sablonu
     *
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public abstract function prepData(string $pageTitle): array;

    /**
     * Metoda zajisti zakladni genericka data spolecna pro vsechny controllery a sablony
     * @param string $pageTitle Nazev stranky
     */
    public function prepBasicData(string $pageTitle) {
        // Data pro sablonu
        $this->data = [];
        // Nazev stranky
        $this->data["title"] = $pageTitle;
        // Nav
        $this->data["nav"] = WEB_PAGES;
        // Info o uzivateli
        $this->data["uzivatel"] = [
//            "logged" => $this->session->isSession($this->userSessionKey),
            "logged" => true,
            "nick" => "Admin"
        ];
    }

}

?>
