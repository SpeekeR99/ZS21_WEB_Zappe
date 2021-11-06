<?php

require_once(DIR_VIEWS."/TemplateHTML.class.php");

global $data;
$templateHTML = new TemplateHTML();

$templateHTML->getHTMLHeader($data["title"]);

$res = "";

$res .= "<h3>HELLO WORLD!</h3>";

echo $res;

$templateHTML->getHTMLFooter();

?>
