<?php

namespace application\Controllers;
use application\Models\MyDatabaseModel;

/**
 * Kontroler pro registraci nového uživatele
 * @author Robert Onder
 */
class RegistrationController implements IController{

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
        $tplData['jeRegistrovan'] = false;
        $tplData['jePrihlasen'] = $this->db->jeUzivatelPrihlasen();
        /** Titulek stránky přihlášeného uživatele pro uživatelskou sekci */
        $tplData['prihlasenTitle'] = $this->db->jeUzivatelPrihlasen() ? " Profil" : " Přihlášení";
        if($tplData['jePrihlasen']){
            /** Získání práva, pokud je uživatel přihlášen */
            $tplData['role'] = $this->db->ziskejDataUzivatele()['id_pravo'];
        }

        /** Registrace uživatele */
        if(isset($_POST['registruj']) && isset($_POST['name']) && isset($_POST['surname']) &&
            isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['city']) &&
            isset($_POST['street']) && isset($_POST['housenumber']) && isset($_POST['psc']) &&
            isset($_POST['okres']) &&

            isset($_POST['password2']) &&

            trim($_POST['name']) != "" && trim($_POST['surname']) != "" && trim($_POST['email']) != "" &&
            trim($_POST['password1']) != "" && trim($_POST['password2']) != "" && trim($_POST['city']) != "" &&
            trim($_POST['street']) != "" && trim($_POST['housenumber']) != "" && trim($_POST['psc']) != "" &&
            trim($_POST['okres']) != "" &&

            $_POST['password1'] == $_POST['password2']
        ){
            /** Pokud uživatel již není registrován, zaregistruji ho */
            $res = $this->db->registrujUzivatele($_POST['name'],$_POST['surname'],$_POST['email'],$_POST['password1'],$_POST['okres'],$_POST['city'],$_POST['street'],$_POST['housenumber'],$_POST['psc']);
            if($res){
                $tplData['jeRegistrovan'] = true;
            }else{
                $tplData['jeRegistrovan'] = false;
            }
        }

        return $tplData;
    }
}