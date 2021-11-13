<?php

/**
 * Rozhrani generickeho ovladace
 */
abstract class AController {

    /** @var array $data Data pro sablonu */
    protected $data;
    /** @var UserLoggingManager $loginManager Objekt pro praci se prihlasovanim a odhlasovanim */
    protected $loginManager;
    /** @var MyDatabase $db Objekt pro praci s databazi */
    protected $db;

    /**
     * Konstruktor inicializuje session a db pro praci se Session a Databazi
     */
    public function __construct() {
        require_once(DIR_MODELS."/UserLoggingManager.class.php");
        require_once(DIR_MODELS."/MyDatabase.class.php");
        $this->loginManager = new UserLoggingManager();
        $this->db = new MyDatabase();
    }

    /**
     * Metoda prepData musi zajistit spravna data pro sablonu
     * @param string $pageTitle Nazev stranky
     * @return array Data pro twig sablonu
     */
    public abstract function prepData(string $pageTitle): array;

    /**
     * Metoda zajisti zakladni genericka data spolecna pro vsechny controllery a sablony
     * @param string $pageTitle Nazev stranky
     */
    protected function prepBasicData(string $pageTitle) {
        // Nazev stranky
        $this->data["title"] = $pageTitle;
        // Nav webovky
        $this->data["nav"] = NAV_WEB_PAGES;
        // Prihlaseny uzivatel
        $this->data["user"] = $this->loginManager->getLoggedUserData();
        $this->data["user"]["logged"] = $this->loginManager->isUserLogged();
    }

    /**
     * Metoda pro zpracovani odeslanych formularu
     */
    protected function processForm() {
        if (isset($_POST["action"])) {
            ob_start();
            if ($_POST["action"] == "register") {
                if (isset($_POST["pass"]) && isset($_POST["passagain"])) {
                     if($_POST["pass"] == $_POST["passagain"]) {
                         if (isset($_POST["email"]) && isset($_POST["login"])) {
                             $this->loginManager->userRegister($_POST["login"], $_POST["email"], $_POST["pass"]);
                         }
                     } else {
                         echo "ERROR: Hesla se neshodují!<br>";
                     }
                }
            }
            else if ($_POST["action"] == "login") {
                if (isset($_POST["login"]) && isset($_POST["pass"])) {
                    $this->loginManager->userLogin($_POST["login"], $_POST["pass"]);
                }
            }
            else if ($_POST["action"] == "change") {
                if (isset($_POST["passold"]) && isset($_POST["passagainold"])) {
                    if ($_POST["passold"] == $_POST["passagainold"]) {
                        $user = $this->loginManager->getLoggedUserData();
                        if (password_verify($_POST["passold"], $user["pass"])) {
                            if (isset($_POST["loginnew"]) || isset($_POST["emailnew"]) || (isset($_POST["passnew"]) && isset($_POST["passagainnew"]))) {
                                if ($_POST["passnew"] == $_POST["passagainnew"]) {
                                    if ($_POST["loginnew"] != "") {
                                        $this->db->changeUserLogin($user["id_user"], $_POST["loginnew"]);
                                    }
                                    if ($_POST["emailnew"] != "") {
                                        $this->db->changeUserEmail($user["id_user"], $_POST["emailnew"]);
                                    }
                                    if ($_POST["passnew"] != "") {
                                        $this->db->changeUserPass($user["id_user"], $_POST["passnew"]);
                                    }
                                    if ($_FILES["profilepic"]["tmp_name"] != "") {
                                        $targetDir = "uploads/avatar/";
                                        $targetFile = $targetDir . basename($_FILES['profilepic']['name']);
                                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                                        $uniqueName = $targetDir . time() . uniqid(rand()) . "." . $imageFileType;
                                        $check = getimagesize($_FILES["profilepic"]["tmp_name"]);
                                        if ($check !== false) {
                                            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                                                if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $uniqueName)) {
                                                    echo "Soubor " . htmlspecialchars(basename($_FILES["profilepic"]["name"])) . " byl úspěšně nahrán.<br>";
                                                    $this->db->changeUserProfilePic($user["id_user"], $uniqueName);
                                                } else {
                                                    echo "ERROR: Nastal problém s nahráváním souboru!<br>";
                                                }
                                            } else {
                                                echo "ERROR: Nahraný soubor není typu JPG, JPEG, PNG nebo GIF!<br>";
                                            }
                                        } else {
                                            echo "ERROR: Nahraný soubor není obrázek!<br>";
                                        }
                                    }
                                } else {
                                    echo "ERROR: Nová hesla se neshodují!<br>";
                                }
                            }
                        } else {
                            echo "ERROR: Zadané heslo je špatné!<br>";
                        }
                    } else {
                        echo "ERROR: Stará hesla se neshodují!<br>";
                    }
                }
            }
            else if($_POST["action"] == "logout") {
                $this->loginManager->userLogout();
            }
            else if($_POST["action"] == "addarticle") {
                if (isset($_POST["articletext"]) && isset($_FILES["articlepdf"]) && isset($_POST["articletitle"])) {
                    if ($_POST["articletext"] != "" && $_FILES["articlepdf"]["tmp_name"] != "" && $_POST["articletitle"] != "") {
                        $user = $this->loginManager->getLoggedUserData();
                        $title = $_POST["articletitle"];
                        $abstract = $_POST["articletext"];
                        $targetDir = "uploads/article/";
                        $targetFile = $targetDir . basename($_FILES['articlepdf']['name']);
                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                        $uniqueName = $targetDir . time() . uniqid(rand()) . "." . $imageFileType;
                        if ($imageFileType == "pdf") {
                            if (move_uploaded_file($_FILES["articlepdf"]["tmp_name"], $uniqueName)) {
                                $this->db->addArticle($user["id_user"], $title, $abstract, $uniqueName);
                            } else {
                                echo "ERROR: Nastal problém s nahráváním souboru!<br>";
                            }
                        } else {
                            echo "ERROR: Nahraný soubor není typu PDF!<br>";
                        }
                    } else {
                        echo "ERROR: Musíte vyplnit název článku a abstract a i nahrát pdf soubor!<br>";
                    }
                }
            }
            else if($_POST["action"] == "changearticle") {
                if (isset($_POST["changedtext"]) && isset($_FILES["changedpdf"]) && isset($_POST["changedtitle"]) && isset($_POST["articleid"])) {
                    if ($_POST["changedtext"] != "" && $_FILES["changedpdf"]["tmp_name"] != "" && $_POST["changedtitle"] != "") {
                        $title = $_POST["changedtitle"];
                        $abstract = $_POST["changedtext"];
                        $targetDir = "uploads/article/";
                        $targetFile = $targetDir . basename($_FILES['changedpdf']['name']);
                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                        $uniqueName = $targetDir . time() . uniqid(rand()) . "." . $imageFileType;
                        if ($imageFileType == "pdf") {
                            if (move_uploaded_file($_FILES["changedpdf"]["tmp_name"], $uniqueName)) {
                                $this->db->changeArticleDataAndPDF($_POST["articleid"], $title, $abstract, $uniqueName);
                            } else {
                                echo "ERROR: Nastal problém s nahráváním souboru!<br>";
                            }
                        } else {
                            echo "ERROR: Nahraný soubor není typu PDF!<br>";
                        }
                    } else if ($_POST["changedtext"] != "" && $_POST["changedtitle"] != "") {
                        $title = $_POST["changedtitle"];
                        $abstract = $_POST["changedtext"];
                        $this->db->changeArticleDataNotPDF($_POST["articleid"], $title, $abstract);
                    }
                }
            }
        }
        if (isset($_POST["deleteuser"])) {
            if ($_POST["deleteuser"] != "") {
                $this->db->deleteUser($_POST["deleteuser"]);
            }
        }
        if (isset($_POST["deletearticle"])) {
            if ($_POST["deletearticle"] != "") {
                $this->db->deleteArticle($_POST["deletearticle"]);
            }
        }
        if (isset($_POST["articleswapstatus"])) {
            if ($_POST["articleswapstatus"] != "") {
                $this->db->swapArticleStatus($_POST["articleswapstatus"]);
            }
        }
        if (isset($_POST["articlereview"])) {
            if ($_POST["articlereview"] != "") {
                $this->db->articleSendToReview($_POST["articlereview"]);
            }
        }
        if (isset($_POST["ratinguserid"]) && isset($_POST["ratingarticleid"]) && isset($_POST["ratingnum"])) {
            if ($_POST["ratinguserid"] != "" && $_POST["ratingarticleid"] != "") {
                $this->db->addRating($_POST["ratinguserid"], $_POST["ratingarticleid"], $_POST["ratingnum"], $_POST["ratingtext"]);
            }
        }
        $prompt = ob_get_clean();
        if (explode(' ',trim($prompt))[0] == "ERROR:") {
            $this->data["error"] = $prompt;
        } else {
            $this->data["prompt"] = $prompt;
        }
    }

}

?>
