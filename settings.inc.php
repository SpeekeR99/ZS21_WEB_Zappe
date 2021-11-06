<?php

/* - - - - - Pripojeni k databazi - - - - - */

/** Adresa serveru */
const DB_SERVER = "localhost";
/** Nazev databaze */
const DB_NAME = "spweb";
/** Prihlasovaci jmeno do databaze */
const DB_USER = "root";
/** Heslo pro uzivatele do databaze */
const DB_PASS = "";

/* - - - - - Tabulky v DB - - - - - */

/** Tabulka s uzivateli */
const TABLE_USERS = "spweb_users";
/** Tabulka s pravy */
const TABLE_RIGHTS = "spweb_rights";
/** Tabulka s recenzemi */
const TABLE_REVIEWS = "spweb_reviews";
/** Tabulka s hodnocenimi */
const TABLE_RATINGS = "spweb_ratings";

/* - - - - - Stanky webu - - - - - */

/** Adresar s ovladaci */
const DIR_CONTROLLERS = "Controllers";
/** Adresar s modely */
const DIR_MODELS = "Models";
/** Adresar s pohledy */
const DIR_VIEWS = "Views";

/** Klic k defaultni webove strance */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Vsechny webove stranky */
const WEB_PAGES = array(
    "uvod" => array(
        "title" => "Úvodní stránka",
        "file_name" => "UvodController.class.php",
        "class_name" => "UvodController",
    )
);

?>