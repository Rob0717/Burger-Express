<?php

namespace application\Controllers;
use application\Models\MyDatabaseModel;

/**
 * Kontroler nabídky produktů s možností přidání
 * do košíku a následným objednáním
 * @author Robert Onder
 */
class NabidkaController implements IController{

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
        $tplData['prihlasenyUzivatel'] = $this->db->ziskejDataUzivatele();
        if($tplData['jePrihlasen']){
            /** Získání práva, pokud je uživatel přihlášen */
            $tplData['role'] = $this->db->ziskejDataUzivatele()['id_pravo'];
        }
        /** Deklarace proměnných daného kontroleru pro danou šablonu */
        $tplData['produkty'] = $this->db->vratProdukty();
        $tplData['produktRadekVTabulce'] = "";
        $tplData['tlacitkoObjednat'] = "";
        $tplData['cenaCelkemRadek'] = "";
        $tplData['cenaCelkem'] = 0;
        $tplData['jeVKosiku'] = false;
        $tplData['jeObjednano'] = false;
        $tplData['hlavickaVsechProduktu'] = "<div class='row uvod_kupon'>";
        $tplData['patickaVsechProduktu'] = "</div>";
        $tplData['vsechnyProdukty'] = "";
        $tplData['lzeVlozitDoKosiku'] = false;

        /** Pokud je uživatel přihlášen, vytvořím košík, popřípadě
         *  získám neodeslanou objednávku
         */
        if($tplData['jePrihlasen']){
            $tplData['neodeslanaObjednavka'] = $this->db->vratVytvorenouNeodeslanouObjednavku();
            if(empty($tplData['neodeslanaObjednavka'])){
                $this->db->vytvorObjednavku();
                $tplData['neodeslanaObjednavka'] = $this->db->vratVytvorenouNeodeslanouObjednavku();
            }
            $tplData['id_neodeslanaObjednavka'] = $tplData['neodeslanaObjednavka']['id'];

            /** Správa produktů v sekci nabídka */
            $tplData['prod'] = $this->db->vratProdukty();
            foreach($tplData['prod'] as $p){
                $idp = $p['id'];
                if(isset($_POST["btnDoKosiku$idp"]) && isset($_POST['id_produkt']) && isset($_POST['uskladneno_ks'])){
                    $tplData['produkt'] = $this->db->vratProdukt($_POST['id_produkt']);
                    $tplData['produktZKosiku'] = $this->db->vratProduktZKosiku($tplData['id_neodeslanaObjednavka'],$_POST['id_produkt']);
                    if(empty($tplData['produktZKosiku'])){
                        $tplData['jeVKosiku'] = false;
                        if($tplData['produkt']['uskladneno_ks'] > 0){
                            $tplData['lzeVlozitDoKosiku'] = true;
                            $tplData['vlozenoDoKosiku'] = $this->db->vlozDoKosiku($_POST['id_produkt']);
                        }else{
                            $tplData['lzeVlozitDoKosiku'] = false;
                        }
                    }else{
                        if($tplData['produkt']['uskladneno_ks'] > 0) {
                            $tplData['lzeVlozitDoKosiku'] = true;
                            $tplData['jeVKosiku'] = true;
                        }
                    }
                }
            }

            /** Správa košíku */
            $produkty = $this->db->vratProdukty();
            foreach($produkty as $produkt) {
                $produktID = $produkt['id'];
                if(isset($_POST["upravit$produktID"])) {
                    $tplData['upravaProduktu'] = $_POST["upravit$produktID"];
                    $tplData['upravenyProduktNazev'] = $produkt['nazev'];
                    $this->db->upravProduktVKosiku($_POST['id_objednavka'],$produktID,$_POST["pocet_ks$produktID"]);
                }
                if(isset($_POST["odebrat$produktID"])){
                    $tplData['odebraniProduktu'] = $_POST["odebrat$produktID"];
                    $tplData['odebranyProduktNazev'] = $produkt['nazev'];
                    $this->db->odeberProduktZKosiku($_POST['id_objednavka'],$produktID);
                }
            }

            /** Výpis košíku */
            $tplData['produktyVKosiku'] = $this->db->vratProduktyZKosiku($tplData['neodeslanaObjednavka']['id']);
            if(!empty($tplData['produktyVKosiku'])){
                foreach($tplData['produktyVKosiku'] as $produkt){
                    $id = $produkt['id_produkt'];
                    $ziskanyProdukt = $this->db->vratProdukt($id);
                    if($ziskanyProdukt['uskladneno_ks'] != 0){
                        $tplData['produktRadekVTabulce'] .=
                            "<tr><td>$ziskanyProdukt[nazev]</td>
                             <td>$ziskanyProdukt[cena] Kč</td>
                             <td>
                                <div class='input-group'>
                                    <div class='input-group-prepend'>
                                        <b class='form-control'><b>Počet ks v košíku:</b> </b>
                                    </div>
                                    <input type='number' name='pocet_ks$produkt[id_produkt]' min='1' max='$ziskanyProdukt[uskladneno_ks]' value='$produkt[pocet_ks]'>
                                    <input type='hidden' name='id_objednavka' value='$produkt[id_objednavka]'>
                                    <button type='submit' name='upravit$produkt[id_produkt]' value='$ziskanyProdukt[id]' class='btn btn-outline-primary'>Upravit</button>
                                    <button type='submit' name='odebrat$produkt[id_produkt]' value='$produkt[id_produkt]' class='btn btn-outline-danger'>Odebrat</button>
                                </div>
                             </td>
                         </tr>";

                    $tplData['cenaCelkem'] += $produkt['pocet_ks'] * $ziskanyProdukt['cena'];
                    $tplData['tlacitkoObjednat'] = "<td><button type='submit' class='btn btn-outline-primary' name='btnObjednat'>Objednat</button></td></tr>";
                    $tplData['cenaCelkemRadek'] = "<tr><td><strong>OBJEDNÁVKA</strong></td><td>Cena celkem: <a style='background-color: #77ff6a;border: dashed #4effea;padding: 2px;border-radius: 10px'>$tplData[cenaCelkem]</a> Kč</td>";
                    }
                }
            }

            /** Objednání zboží */
            if(isset($_POST['btnObjednat'])){
                $objednavka = $this->db->vratVytvorenouNeodeslanouObjednavku();
                $id_objednavka = $objednavka['id'];
                $produktyVKosiku = $this->db->vratProduktyZKosiku($id_objednavka);
                $muzeObjednat = true;
                if(!empty($produktyVKosiku)){
                    foreach($produktyVKosiku as $produkt){
                        $ziskany = $this->db->vratProdukt($produkt['id_produkt']);
                        if($ziskany['uskladneno_ks'] == 0){
                            $muzeObjednat = false;
                            $tplData['jeObjednano'] = false;
                            $this->db->odeberProduktZKosiku($id_objednavka,$produkt['id_produkt']);
                        }
                    }
                }else{
                    $muzeObjednat = false;
                }

                if($muzeObjednat){
                    $staleMuze = true;
                    foreach ($produktyVKosiku as $produkt){
                        $ziskanyPr = $this->db->vratProdukt($produkt['id_produkt']);
                        $ziskanyPr['uskladneno_ks'] -= $produkt['pocet_ks'];
                        if($ziskanyPr['uskladneno_ks'] < 0){
                            $staleMuze = false;
                            $tplData['jeObjednano']  = false;
                        }
                    }

                    if($staleMuze){
                        foreach($produktyVKosiku as $produkt){
                            $tplData['jeObjednano'] = $this->db->objednejZbozi($id_objednavka);
                            $ziskanyPr = $this->db->vratProdukt($produkt['id_produkt']);
                            $ziskanyPr['uskladneno_ks'] -= $produkt['pocet_ks'];
                            $this->db->upravMnozstviUskladnenychProduktu($ziskanyPr['id'],$ziskanyPr['uskladneno_ks']);
                        }
                    }
                }else{
                    $tplData['jeObjednano'] = false;
                }
            }

            /** Výpis všech dostupných produktů */
            $p = $this->db->vratProdukty();
            foreach($p as $produkt) {
                if ($produkt['uskladneno_ks'] > 0) {
                    $tplData['vsechnyProdukty'] .=
                        "<div class='col-auto uvod_kupon_inside'>
                                <img class='img-center' width='250' height='200'  src='resources/".$produkt['foto']."' alt='".$produkt['nazev']."'>
                                <h2>".$produkt['nazev']."</h2>
                                <p>".$produkt['popis']."</p>
                                <p>".$produkt['cena']." Kč</p>
                                <form method='post'>
                                    <button id='btnDoKosiku$produkt[id]' name='btnDoKosiku$produkt[id]' class='btn btn-primary btnDoKosiku'>Přidat do košíku</button>
                                    <input type='hidden' name='id_produkt' value='$produkt[id]'>
                                    <input type='hidden' name='nazev' value='$produkt[nazev]'>
                                    <input type='hidden' name='uskladneno_ks' value='$produkt[uskladneno_ks]'>
                                    <input type='hidden' name='foto' value='$produkt[foto]'>
                                </form>
                            </div>";
                }
            }

        }

        $tplData['prod'] = $this->db->vratProdukty();
        return $tplData;
    }
}