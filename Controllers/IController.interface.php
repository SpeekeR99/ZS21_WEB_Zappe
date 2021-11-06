<?php

/**
 * Rozhrani generickeho ovladace
 */
interface IController {

    /**
     * Metoda show musi zajistit spravne vykresleni dane stranky
     *
     * @param string $pageTitle Nazev stranky
     * @return string HTML stranky
     */
    public function show(string $pageTitle):string;

}

?>
