<?php

require_once("settings.inc.php");

// Test, zda na pozadavek na zmenu stranky, pripadne nastaveni na default
if (isset($_GET["page"]) && array_key_exists($_GET["page"], WEB_PAGES)) {
    $pageKey = $_GET["page"]; // Pokud existuje pozadavek, nastavim stranku na ni
} else {
    $pageKey = DEFAULT_WEB_PAGE_KEY; // Jinak default
}
// Info z WEB_PAGES pod klicem co je v pageKey
$pageInfo = WEB_PAGES[$pageKey];
// Pripojim si odpovidajici ovladac
require_once(DIR_CONTROLLERS."/".$pageInfo["file_name"]);
// Vytvorim instanci odpovidajiciho ovladace
/** @var AController $controller */
$controller = new $pageInfo["class_name"];
// Necham ovladac vyrobit data
$data = $controller->prepData($pageInfo["title"]);

// Pripojim Twig
require_once 'composer/vendor/autoload.php';
// Cesta k adresari se sablonami - od tohoto souboru
$loader = new \Twig\Loader\FilesystemLoader(DIR_VIEWS);
// Nacteni prostredi s "nacitacem" sablon
$twig = new \Twig\Environment($loader, [
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// render vrati kompletni vyplnenou sablonu pro vypis
echo $twig->render($pageInfo["template"], $data);

?>