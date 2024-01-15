<?php

namespace application\Controllers;
use application\Models\MyDatabaseModel;
use HTMLPurifier;

/**
 * Kontroler přihlašovací stránky / kontroler uživatelské sekce
 * @author Robert Onder
 */
class LoginController implements IController{

    /** @var MyDatabaseModel  */
    private MyDatabaseModel $db;

    public function __construct(){
        $this->db = new MyDatabaseModel();
    }

    /**
     * @param string $pageTitle
     * @return array
     */
    public function show(string $pageTitle):array{
        global $tplData;
        $tplData = [];
        /** Titulek stránky */
        $tplData['title'] = $pageTitle;
        $tplData['prihlaseniUspesne'] = $this->db->jeUzivatelPrihlasen();
        $tplData['jePrihlasen'] = $this->db->jeUzivatelPrihlasen();
        if($tplData['jePrihlasen']){
            /** Získání práva, pokud je uživatel přihlášen */
            $tplData['role'] = $this->db->ziskejDataUzivatele()['id_pravo'];
        }
        /** Titulek stránky přihlášeného uživatele */
        $tplData['prihlasenTitle'] = $this->db->jeUzivatelPrihlasen() ? " Profil" : " Přihlášení";
        /** Deklarace proměnných daného kontroleru pro danou šablonu */
        $tplData['hesloZmeneno'] = false;
        $tplData['objednavkyUzivatele'] = [];
        $tplData['objednavkyRadek'] = "";
        $tplData['celkovaCena'] = 0;

        /** Změna hesla */
        if(isset($_POST['zmenHeslo']) && isset($_POST['stareHeslo']) && isset($_POST['noveHeslo']) && isset($_POST['noveHesloZnovu']) &&
            trim($_POST['noveHeslo']) != "" && trim($_POST['noveHesloZnovu'] != "") &&
            trim($_POST['stareHeslo']) != trim($_POST['noveHeslo'])){
            if($_POST['noveHeslo'] == $_POST['noveHesloZnovu']){
                $user = $this->db->ziskejDataUzivatele();
                $tplData['hesloPrihlaseny'] = $user['heslo'];
                /** Pokud se staré heslo a nově zadané heslo shodují, změň ho */
                if(password_verify($_POST['stareHeslo'],$tplData['hesloPrihlaseny'])){
                    $zmenaHesla = $this->db->zmenHesloUzivatele($user['id'],password_hash($_POST['noveHeslo'],PASSWORD_BCRYPT));
                    if($zmenaHesla){
                        $tplData['hesloZmeneno'] = true;
                    }else{
                        $tplData['hesloZmeneno'] = false;
                    }
                }
            }else{
                $tplData['hesloZmeneno'] = false;
            }
        }

        /** Přihlášení */
        if(isset($_POST['prihlas']) && isset($_POST['email']) && isset($_POST['heslo']) &&
           trim($_POST['email']) != "" && trim($_POST['heslo']) != ""){
            $res = $this->db->prihlasUzivatele($_POST['email'],$_POST['heslo']);
            $tplData['prihlaseniUspesne'] = $this->db->jeUzivatelPrihlasen();
            if($res){
                /** Pokud je přihlášení úspěšné, získám uživatelské právo */
                $tplData['role'] = $this->db->ziskejDataUzivatele()['id_pravo'];
            }
        }

        $user = $this->db->ziskejDataUzivatele();
        if(isset($user)){
            /** Pokud je daný uživatel nastaven, získám jeho data */
            $tplData['jmenoPrihlaseny'] = $user['jmeno'];
            $tplData['prijmeniPrihlaseny'] = $user['prijmeni'];
            $tplData['emailPrihlaseny'] = $user['email'];
            $tplData['okresPrihlaseny'] = $user['okres'];
            $tplData['mestoPrihlaseny'] = $user['mesto'];
            $tplData['ulicePrihlaseny'] = $user['ulice'];
            $tplData['cpPrihlaseny'] = $user['cp'];
            $tplData['pscPrihlaseny'] = $user['psc'];
            $tplData['idpravoPrihlaseny'] = $user['id_pravo'];
        }

        /** Odhlášení uživatele */
        if(isset($_POST['odhlas'])){
            $this->db->odhlasUzivatele();
        }

        /** Získání titulku stránky */
        $tplData['prihlasenTitle'] = $this->db->jeUzivatelPrihlasen() ? " Profil" : " Přihlášení";
        $tplData['jePrihlasen'] = $this->db->jeUzivatelPrihlasen();

        if($this->db->jeUzivatelPrihlasen()){
            $user = $this->db->ziskejDataUzivatele();
            /** Vrácení hotových objednávek uživatele */
            $tplData['objednavkyUzivatele'] = $this->db->vratHotoveObjednavkyUzivatele($user['id']);
        }

        /** Pokud jsou u daného uživatele dokončené
         *  objednávky, vytvořím jejich výpis
         */
        if(isset($tplData['objednavkyUzivatele'])){
            foreach($tplData['objednavkyUzivatele'] as $objednavkaUzivatele){
                $id_obj = $objednavkaUzivatele['id'];
                $produkt = $this->db->vratProduktyZHotoveObjednavky($id_obj);
                if(!empty($produkt)){
                    foreach($produkt as $p){ // $p produkt v kosiku
                        $pr = $this->db->vratProdukt($p['id_produkt']); // $pr produkt z tabulky produktu
                        $tplData['objednavkyRadek'] .= "<tr><td>$id_obj</td><td>$pr[nazev]</td><td>$p[pocet_ks]</td></tr>";
                        $tplData['celkovaCena'] += $p['pocet_ks'] * $pr['cena'];
                    }
                    $tplData['objednavkyRadek'] .= "<tr><th>Cena celkem</th><td colspan='2'><b style='font-size: 20px;color: #3bb000'>$tplData[celkovaCena] Kč</b></td></tr>";
                    $tplData['objednavkyRadek'] .= "<tr><td class='table-dark' colspan='3'></td></tr>";
                    $tplData['celkovaCena'] = 0;
                }
            }
        }

        /** Uložení popisku daného uživatele */
        if(isset($_POST['uloz'])){
            $uziv = $this->db->ziskejDataUzivatele();
            $purifier = new HTMLPurifier();
            $clean_o_mne = $purifier->purify(html_entity_decode($_POST['obsah']));
            $this->db->upravPopisekUzivatele($uziv['id'],$clean_o_mne);
        }

        return $tplData;
    }
}