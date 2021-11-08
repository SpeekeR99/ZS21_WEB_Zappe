<?php

/**
 * Rozhrani generickeho ovladace
 */
interface IController {

    /**
     * Metoda prepData musi zajistit spravna data pro sablonu
     *
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public function prepData(string $pageTitle): array;

}

?>
