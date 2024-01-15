<?php

namespace application\Controllers;
use application\Models\MyDatabaseModel;

/**
 * Kontroler administrátorské sekce
 * @author Robert Onder
 */
class AdminController implements IController{

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
        /** Vrácení uživatel z databáze */
        $tplData['vsichniUzivatele'] = $this->db->vratVsechnyUzivatele();
        return $tplData;
    }
}

