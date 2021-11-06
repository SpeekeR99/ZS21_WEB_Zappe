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
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
        </head>
        <body>
            <header class="container fw-bold">
                <h1><?php echo $pageTitle; ?></h1>
            </header>
            <nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <ul class="navbar-nav">
                            <?php
                            foreach(WEB_PAGES as $key => $pInfo) {
                                echo "<li class='nav-item>'><a class='nav-link' href='index.php?page=$key'>$pInfo[title]</a></li>";
                            }
                            ?>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class='nav-item>'><a class='nav-link' href="#"><span class="fa fa-user"></span> Sign Up</a></li>
                            <li class='nav-item>'><a class='nav-link' href="#"><span class="fa fa-sign-in"></span> Login</a></li>
                            <li class='nav-item>'><a class='nav-link' href="#"><span class="fa fa-sign-out"></span> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            <div class="container">
<?php
    }

    /**
     * Vytvori paticku HTML, od obsahu dal
     */
    public function getHTMLFooter() {
        ?>
            </div>
            <br>
            <footer class="container-fluid bg-dark text-white text-center font-weight-bold">
                &copy; 2021<br>
                <span class="fa fa-cog fa-spin"></span>
                <script src="bootstrap/js/bootstrap.js"></script>
            </footer>
        </body>
        </html>
<?php
    }

}

?>
