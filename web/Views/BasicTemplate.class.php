<?php

namespace application\Views;

/**
 * Šablona pro všechny použité stránky
 * @author Robert Onder
 */
class BasicTemplate implements IView{
    const PAGE_UVOD = "HomePageTemplate.tpl.php";
    const PAGE_NABIDKA = "NabidkaTemplate.tpl.php";
    const PAGE_PRIHLASENI = "LoginTemplate.tpl.php";
    const PAGE_REGISTRACE = "RegistrationTemplate.tpl.php";
    const PAGE_DODAVATEL = "SupplierTemplate.tpl.php";
    const PAGE_ADMIN = "AdminTemplate.tpl.php";

    public function printOutput(array $templateData, string $pageType = self::PAGE_UVOD): void{
        global $tplData;
        $tplData = $templateData;
        $this->getHeader($templateData['title'],$tplData);
        require_once($pageType);
        $this->getFooter();
    }

    /**
     * Tvorba hlavičky
     * @param string $pageTitle
     * @param $tplData
     * @return void
     */
    public function getHeader(string $pageTitle,$tplData):void{   ?>
        <!doctype html>
        <html lang="cs">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta http-equiv="X-UA-Compatible" content="IE=edgE">

                <title>
                    <?php
                        if($tplData['jePrihlasen'] && $pageTitle == "Přihlášení"){
                            echo "Profil";
                        }else{
                            echo $pageTitle;
                        }
                    ?>
                </title>
                
                <link rel="shortcut icon" href="resources/burger-tab-icon.ico">
                <link rel="stylesheet" href="web/Views/styling/my_styles.css">
                <link rel="stylesheet" href="composer/vendor/fortawesome/font-awesome/css/all.css">
                <link rel="stylesheet" href="composer/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
                <script src="libraries/sweetalert/dist/sweetalert.min.js"></script>

                <style>
                    body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
                        background-color: #5d0000 !important;
                    }
                    .swal2-confirm {
                        background-color: #ff0000 !important; /* Toto změní barvu pozadí tlačítka na červenou */
                        color: #fff !important; /* Toto změní barvu textu tlačítka na bílou */
                        border-color: #ff0000 !important; /* Toto změní barvu okraje tlačítka na červenou */
                    }
                    .swal2-confirm:hover {
                        background-color: #cc0000 !important; /* Toto změní barvu pozadí tlačítka na tmavě červenou při najetí myší */
                        border-color: #cc0000 !important; /* Toto změní barvu okraje tlačítka na tmavě červenou při najetí myší */
                    }
                    .swal2-popup {
                        background-color: #565656 !important;
                        color: #ffffff;
                    }
                    .swal2-title {
                        color: #ffffff !important;
                    }
                    .swal2-content {
                        color: #ffffff;
                    }
                    .swal2-input {
                        color: #ffffff;
                    }
                </style>
                <!-- pro AJAX -->
                <script>
                    function showUser(idpravoprihlaseny,str) {
                        if (str === "") {
                            document.getElementById("txtHint").innerHTML = "";
                        } else {
                            const xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function() {
                                if (this.readyState === 4 && this.status === 200) {
                                    document.getElementById("txtHint").innerHTML = this.responseText;
                                }
                            };
                            xmlhttp.open("GET","web/Ajax/ziskejUzivateleAJAX.php?idpp="+idpravoprihlaseny+"&q="+str,true);
                            xmlhttp.send();
                        }
                    }
                    function zmenPravo(id,idpravo,idpp){
                        const xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState === 4 && this.status === 200) {
                                showUser(idpp,id);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Uživatelské právo změněno',
                                })
                            }
                        };
                        xmlhttp.open("GET","web/Ajax/upravUzivateleAJAX.php?q="+id+"&s="+idpravo+"&idpp="+idpp,true);
                        xmlhttp.send();
                    }
                </script>
            </head>
            <body id="basic_template_body">
                <nav class="navbar navbar-expand-md navbar-dark sticky-top">
                    <div class="container">
                        <!-- Brand -->
                        <a class="navbar-brand" href="index.php?page=uvodni_strana">
                            <span style="color: #fdb92f" class="fa fa-hamburger"></span>
                        </a>

                        <!-- Toggler/collapsibe Button -->
                        <button class="navbar-toggler" id="focusedCollapsibleNavbar" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Navbar links -->
                        <div class="collapse navbar-collapse" id="collapsibleNavbar">
                            <ul class="navbar-nav mx-auto" style="padding: 10px">
                                <li class="nav-item">
                                    <i class="fa fa-home"></i><a class="nav-link" href="index.php?page=uvodni_strana"> Úvod</a>
                                </li>
                                <?php if($tplData['jePrihlasen'] && $tplData['role'] != 2 && $tplData['role'] != 3){ ?>
                                    <li class="nav-item">
                                        <i class="fa fa-hamburger"></i><a class="nav-link" href="index.php?page=nabidka_strana"> Nabídka</a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <i  class="fa fa-sign-in-alt"></i><a class="nav-link" href="index.php?page=prihlasovaci_strana"><?= $tplData['prihlasenTitle']?></a>
                                </li>
                                <?php if(!$tplData['jePrihlasen']){ ?>
                                    <li class="nav-item">
                                        <i class="fa fa-user-plus"></i><a class="nav-link" href="index.php?page=registracni_strana"> Registrace</a>
                                    </li>
                                <?php } ?>
                                <?php if($tplData['jePrihlasen'] && $tplData['role'] % 2 == 1){ ?>
                                    <li class="nav-item">
                                        <i class="fa-solid fa-truck"></i><a class="nav-link" href="index.php?page=dodavatel_strana"> Dodavatelská sekce</a>
                                    </li>
                                <?php } ?>
                                <?php if($tplData['jePrihlasen'] && $tplData['role'] < 3){ ?>
                                    <li class="nav-item">
                                        <i class="fa-solid fa-user-secret"></i><a class="nav-link" href="index.php?page=admin_strana"> Administrátorská sekce</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </nav>
        <?php /** Zde začíná tělo všech stránek */ ?>
                <div id="telo">
<?php
    }

    /**
     * Tvorba patičky
     * @return void
     */
    public function getFooter(){
?>
        <?php /** Zde končí tělo všech stránek */ ?>
                </div>
        <footer id="footer">
            <div id="divFooter">
                <span id="spanFooter"><b>Burger Express @2023</b></span>
            </div>
        </footer>
        <script src="composer/vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
        <script src="libraries/jquery/dist/jquery.slim.min.js"></script>
        <script src="libraries/bootstrap_js/dist/js/bootstrap.min.js"></script>
            </body>
        </html>
    <?php
    }
}
?>






