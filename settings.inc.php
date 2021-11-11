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
const TABLE_USERS = "users";
/** Tabulka s clanky */
const TABLE_ARTICLES = "articles";
/** Tabulka s hodnocenimi */
const TABLE_RATINGS = "ratings";

/* - - - - - Stanky webu - - - - - */

/** Adresar s ovladaci */
const DIR_CONTROLLERS = "controllers";
/** Adresar s modely */
const DIR_MODELS = "models";
/** Adresar s pohledy */
const DIR_VIEWS = "views";

/** Klic k defaultni webove strance */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Vsechny webove stranky */
const WEB_PAGES = array(
    "uvod" => array(
        "title" => "Úvodní stránka",
        "file_name" => "UvodController.class.php",
        "class_name" => "UvodController",
        "template" => "UvodTemplate.twig"
    ),
    "clanky" => array(
        "title" => "Články",
        "file_name" => "ClankyController.class.php",
        "class_name" => "ClankyController",
        "template" => "ClankyTemplate.twig"
    ),
    "novy_clanek" => array(
        "title" => "Přidat nový článek",
        "file_name" => "PridatClanekController.class.php",
        "class_name" => "PridatClanekController",
        "template" => "PridatClanekTemplate.twig"
    ),
    "sprava" => array(
        "title" => "Správa uživatelů",
        "file_name" => "SpravaUzivateluController.class.php",
        "class_name" => "SpravaUzivateluController",
        "template" => "SpravaUzivateluTemplate.twig"
    ),
    "registrace" => array(
        "title" => "Registrace",
        "file_name" => "RegistraceController.class.php",
        "class_name" => "RegistraceController",
        "template" => "RegistraceTemplate.twig"
    ),
    "login" => array(
        "title" => "Přihlášení",
        "file_name" => "LoginController.class.php",
        "class_name" => "LoginController",
        "template" => "LoginTemplate.twig"
    ),
    "profil" => array(
        "title" => "Můj profil",
        "file_name" => "ProfilController.class.php",
        "class_name" => "ProfilController",
        "template" => "ProfilTemplate.twig"
    ),
    "recenze" => array(
        "title" => "Recenze",
        "file_name" => "RecenzeController.class.php",
        "class_name" => "RecenzeController",
        "template" => "RecenzeTemplate.twig"
    ),
    "mojeclanky" => array(
        "title" => "Moje Články",
        "file_name" => "MojeClankyController.class.php",
        "class_name" => "MojeClankyController",
        "template" => "MojeClankyTemplate.twig"
    ),
    "recenzent" => array(
        "title" => "Staňte se recenzentem",
        "file_name" => "RecenzentController.class.php",
        "class_name" => "RecenzentController",
        "template" => "RecenzentTemplate.twig"
    ),
    "zmena" => array(
        "title" => "Změna údajů",
        "file_name" => "ZmenaController.class.php",
        "class_name" => "ZmenaController",
        "template" => "ZmenaTemplate.twig"
    )
);

/** Webové stránky pro navigaci */
const NAV_WEB_PAGES = array(
    "uvod" => array(
        "pravo" => 0,
        "href" => "index.php?page=uvod",
        "title" => "Úvodní stránka"
    ),
    "clanky" => array(
        "pravo" => 0,
        "href" => "index.php?page=clanky",
        "title" => "Články"
    ),
    "recenzent" => array(
        "pravo" => 1,
        "href" => "index.php?page=recenzent",
        "title" => "Staňte se recenzentem"
    ),
    "recenze" => array(
        "pravo" => 3,
        "href" => "index.php?page=recenze",
        "title" => "Recenze"
    ),
    "sprava" => array(
        "pravo" => 5,
        "href" => "index.php?page=sprava",
        "title" => "Správa uživatelů"
    )
);

?>