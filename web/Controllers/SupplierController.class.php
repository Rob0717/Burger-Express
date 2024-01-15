<?php

namespace application\Controllers;
use application\Models\MyDatabaseModel;

/**
 * Kontroler pro dodavatelskou sekci
 * @author Robert Onder
 */
class SupplierController implements IController{

    /** @var MyDatabaseModel  */
    private MyDatabaseModel $db;
    public function __construct(){
        $this->db = new MyDatabaseModel();
    }

    /**
     * @param string $pageTitle
     * @return array
     */
    public function show(string $pageTitle): array{
        global $tplData;
        $tplData = [];
        /** Titulek stránky */
        $tplData['title'] = $pageTitle;
        /** Titulek stránky přihlášeného uživatele pro uživatelskou sekci */
        $tplData['prihlasenTitle'] = $this->db->jeUzivatelPrihlasen() ? " Profil" : " Přihlášení";
        $tplData['jePrihlasen'] = $this->db->jeUzivatelPrihlasen();
        if ($tplData['jePrihlasen']) {
            /** Získání práva, pokud je uživatel přihlášen */
            $tplData['role'] = $this->db->ziskejDataUzivatele()['id_pravo'];
        }
        /** Deklarace proměnných daného kontroleru pro danou šablonu */
        $tplData['produkty'] = "";
        $tplData['existuje'] = false;

        /** Přidání nového produktu */
        if(isset($_POST['vlozNovy'])){
            if(isset($_POST['nazevNovy']) && isset($_POST['popisNovy']) &&
                isset($_POST['cenaNovy']) && isset($_POST['ksNovy']) &&
                trim($_POST['nazevNovy']) != "" && trim($_POST['popisNovy']) != "" &&
                isset($_FILES["souborNovy"]["name"])){

                $adr = "resources";
                // Není souborem
                if(is_file($adr)){
                    echo "Nelze vytvořit adresář DATA.<br>";
                }
                // Není souborem a neexistuje?
                elseif(!file_exists($adr)) {
                    mkdir($adr);
                }
                // Nemám adresář resources?
                if(!is_dir($adr)){
                    echo "Adresář resources nelze použít.<br>";
                }

                /** Pokud typem souboru je foto a zároveň již neexistuje
                 *  foto se stejným názvem, přidám daný produkt
                 */
                $type = $_FILES["souborNovy"]["type"];
                $extensions = array("image/jpg","image/jpeg","image/png","image/gif");
                if(in_array($type, $extensions)){
                    $nazev = basename( $_FILES["souborNovy"]["name"]);
                    $celyNazev = $adr."/".$nazev;
                    $celyNazev = iconv("UTF-8", "WINDOWS-1250", $celyNazev);

                    $jizExistuje = $this->db->kontrolaExistenceProduktu($_FILES['souborNovy']['name']);
                    if($jizExistuje){
                        $tplData['existuje'] = false;
                    }else{
                        $tplData['existuje'] = true;
                        $this->db->vlozNovyProdukt($_POST['nazevNovy'],$_POST['cenaNovy'],$_POST['ksNovy'],$nazev,$_POST['popisNovy']);
                        move_uploaded_file($_FILES["souborNovy"]["tmp_name"],$celyNazev);
                    }
                }
            }
        }

        /** Úprava množství dostupných produktů v dodavatelské sekci */
        $tplData['vsechnyProdukty'] = $this->db->vratProdukty();
        foreach($tplData['vsechnyProdukty'] as $p){
            $idProduktu = $p['id'];
            if(isset($_POST["btnUpravPocet$idProduktu"])){
                $tplData['staryPocet'] = $_POST['puvodni_pocet'];
                $this->db->upravMnozstviUskladnenychProduktu($idProduktu,$_POST["KonecnyPocet$idProduktu"]);
                $pr = $this->db->vratProdukt($idProduktu);
                $tplData['novyPocet'] = $pr['uskladneno_ks'];
            }
            if(isset($_POST["btnOdstran$idProduktu"])){
                $this->db->odeberProduktZNabidky($idProduktu);
            }
        }

        /** Výpis produktů v dodavatelské sekci */
        $produkty = $this->db->vratProdukty();
        $tplData['produkty'] .= "<div class='row uvod_kupon'>";
        foreach($produkty as $produkt){
            $tplData['produkty'] .= "<div class='col-auto uvod_kupon_inside'>
                                        <img class='img-center' width='250' height='200'  src='resources/".$produkt['foto']."' alt='".$produkt['nazev']."'>
                                        <h2>".$produkt['nazev']."</h2>
                                        <label> Uskladněno:</label>
                                        <form method='post'>
                                            <div class='input-group mb-4'>
                                                <input class='form-control' id='KonecnyPocet$produkt[id]' name='KonecnyPocet$produkt[id]' type='number' min='1' max='1000' value='$produkt[uskladneno_ks]'>
                                                <div class='input-group-prepend'>
                                                    <button class='btn btn-outline-primary' id='btnUpravPocet$produkt[id]' name='btnUpravPocet$produkt[id]'>Upravit</button>
                                                </div>
                                                <script>
                                                    var msg = 'Opravdu chcete odebrat tento produkt?';
                                                </script>
                                                <button onclick='return confirm(msg)' id='btnOdstran$produkt[id]' name='btnOdstran$produkt[id]' class='btn btn-outline-danger'>Odebrat</button>
                                            </div>
                                                                 
                                            <input type='hidden' name='puvodni_pocet' value='$produkt[uskladneno_ks]'>
                                            <input type='hidden' name='id_produkt' value='$produkt[id]'>
                                            
                                            <input type='hidden' name='nazev' value='$produkt[nazev]'>
                                            <input type='hidden' name='uskladneno_ks' value='$produkt[uskladneno_ks]'>
                                            <input type='hidden' name='foto' value='$produkt[foto]'>
                                        </form>
                                      </div>";
        }
        $tplData['produkty'] .= "</div>";
        return $tplData;
    }
}

