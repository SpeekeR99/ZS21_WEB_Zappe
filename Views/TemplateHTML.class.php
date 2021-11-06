<?php

/**
 * Trida pro vytvoreni hlavicky a paticky HTML
 */
class TemplateHTML {

    /**
     * Vytvori hlavicku HTML stranky, az po obsah
     * @param string $pageTitle Nazev stranky
     */
    public function getHTMLHeader(string $pageTitle) {
        ?>
        <!doctype html>
        <html lang="cs">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle; ?></title>
            <link rel="stylesheet" href="Views/style.css">
        </head>
        <body>
            <h1><?php echo $pageTitle; ?></h1>
            <nav>
                <?php
                    foreach(WEB_PAGES as $key => $pInfo){
                        echo "<a href='index.php?page=$key'>$pInfo[title]</a>";
                    }
                ?>
            </nav>
            <br>
<?php
    }

    /**
     * Vytvori paticku HTML, od obsahu dal
     */
    public function getHTMLFooter() {
        ?>
            <br>
            <footer>
                &copy; 2021
            </footer>
        </body>
        </html>
<?php
    }

}

?>
