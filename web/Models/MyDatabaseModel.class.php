<?php

namespace application\Models;
use PDO;

/**
 * Databázový model pro práci s daty
 * @author Robert Onder
 */
class MyDatabaseModel{

    /** @var PDO  */
    private PDO $pdo;

    /** @var MySession  */
    private MySession $mySession;

    /** @var string */
    private string $userSessionKey = "current_user_id";

    public function __construct(){
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USER,DB_PASS);
        $this->pdo->exec("set names utf8");
        require_once("MySessions.class.php");
        $this->mySession = new MySession();
    }
    /****************************************************************************************************************/
    /**
     * @param $email
     * @param $heslo
     * @return bool
     */
    public function prihlasUzivatele($email,$heslo):bool{
        $email = htmlspecialchars($email);
        $heslo = htmlspecialchars($heslo);
        $user = $this->vratUzivatele($email);
        if(!empty($user)){
            if(password_verify($heslo,$user[0]['heslo'])){
                $_SESSION[$this->userSessionKey] = $user[0]['id'];
                return true;
            }
        }
        return false;
    }

    /**
     * @return void
     */
    public function odhlasUzivatele():void{
        unset($_SESSION[$this->userSessionKey]);
    }

    /**
     * @return bool
     */
    public function jeUzivatelPrihlasen():bool{
        return isset($_SESSION[$this->userSessionKey]);
    }

    /**
     * @param $email
     * @return false|array|null
     */
    public function vratUzivatele($email):false|array|null{
        $q = "SELECT * FROM ".TABLE_UZIVATEL." WHERE email=:uLogin;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":uLogin",$email);
        if($vystup->execute()){
            $user = $vystup->fetchAll();
            if(empty($user)){
                return null;
            }else{
                return $user;
            }
        }else{
            return null;
        }
    }

    /**
     * @param $jmeno
     * @param $prijmeni
     * @param $email
     * @param $heslo
     * @param $okres
     * @param $mesto
     * @param $ulice
     * @param $cp
     * @param $psc
     * @return bool
     */
    public function registrujUzivatele($jmeno,$prijmeni,$email,$heslo,$okres,$mesto,$ulice,$cp,$psc):bool{
        $jmeno = htmlspecialchars($jmeno);
        $prijmeni = htmlspecialchars($prijmeni);
        $email = htmlspecialchars($email);
        $heslo = htmlspecialchars($heslo);
        $okres = htmlspecialchars($okres);
        $mesto = htmlspecialchars($mesto);
        $ulice = htmlspecialchars($ulice);
        $cp = htmlspecialchars($cp);
        $psc = htmlspecialchars($psc);
        $uzivatel = $this->vratUzivatele($email);
        if(!isset($uzivatel) || count($uzivatel)==0){
            $q = "INSERT INTO ".TABLE_UZIVATEL." (jmeno,prijmeni,email,heslo,okres,mesto,ulice,cp,psc,id_pravo) VALUES (:jmeno,:prijmeni,:email,:heslo,:okres,:mesto,:ulice,:cp,:psc,:id_pravo);";
            $vystup = $this->pdo->prepare($q);
            $vystup->bindValue(":jmeno",$jmeno);
            $vystup->bindValue(":prijmeni",$prijmeni);
            $vystup->bindValue(":email",$email);
            $vystup->bindValue(":heslo",password_hash($heslo,PASSWORD_BCRYPT));
            $vystup->bindValue(":okres",$okres);
            $vystup->bindValue(":mesto",$mesto);
            $vystup->bindValue(":ulice",$ulice);
            $vystup->bindValue(":cp",$cp);
            $vystup->bindValue(":psc",$psc);
            $vystup->bindValue(":id_pravo",4);
            if($vystup->execute()){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * @return array|null
     */
    public function ziskejDataUzivatele():array|null{
        if($this->jeUzivatelPrihlasen()){
            $userId = $_SESSION[$this->userSessionKey];
            if($userId == null){
                $this->odhlasUzivatele();
                return null;
            }else{
                $q = "SELECT * FROM ".TABLE_UZIVATEL." WHERE id=:id;";
                $vystup = $this->pdo->prepare($q);
                $vystup->bindValue(":id",$userId);
                if($vystup->execute()){
                    $userData = $vystup->fetchAll();
                }
                if(empty($userData)){
                    $this->odhlasUzivatele();
                    return null;
                }else{
                    return $userData[0];
                }
            }
        }else{
            return null;
        }
    }

    /**
     * @param $id_uzivatel
     * @param $heslo
     * @return bool
     */
    public function zmenHesloUzivatele($id_uzivatel,$heslo):bool{
        $q = "UPDATE ".TABLE_UZIVATEL." SET heslo=:heslo WHERE id=:id";
        $vstup = $this->pdo->prepare($q);
        $h = htmlspecialchars($heslo);
        $vstup->bindValue(":id",$id_uzivatel);
        $vstup->bindValue(":heslo",$h);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id_uzivatel
     * @param $o_mne
     * @return bool
     */
    public function upravPopisekUzivatele($id_uzivatel,$o_mne):bool{
        $q = "UPDATE ".TABLE_UZIVATEL." SET o_mne=:o_mne WHERE id=:id;";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":o_mne",$o_mne);
        $vstup->bindValue(":id",$id_uzivatel);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return array|null
     */
    public function vratVsechnyUzivatele():array|null{
        $q = "SELECT * FROM ".TABLE_UZIVATEL.";";
        $vystup = $this->pdo->prepare($q);
        if($vystup->execute()){
            return $vystup->fetchAll();
        }else{
            return null;
        }
    }
    /****************************************************************************************************************/
    /**
     * @return bool
     */
    public function vytvorObjednavku():bool{
        $q = "INSERT INTO ".TABLE_OBJEDNAVKA." (dokoncena,datum,id_uzivatel) VALUES (:dokoncena,:datum,:id_uzivatel);";
        $vstup = $this->pdo->prepare($q);
        $u = $this->ziskejDataUzivatele();
        $date = date("Y-m-d");
        $vstup->bindValue(':dokoncena',0);
        $vstup->bindValue(':datum',$date);
        $vstup->bindValue(':id_uzivatel',$u['id']);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return false|array
     */
    public function vratVytvorenouNeodeslanouObjednavku():false|array{
        $q = "SELECT * FROM ".TABLE_OBJEDNAVKA." WHERE id_uzivatel=:id_uzivatel AND dokoncena=:dokoncena;";
        $vystup = $this->pdo->prepare($q);
        $u = $this->ziskejDataUzivatele();
        $vystup->bindValue(':id_uzivatel',$u['id']);
        $vystup->bindValue(':dokoncena',0);
        if($vystup->execute()){
            $objednavka = $vystup->fetchAll();
            if(empty($objednavka)){
                return false;
            }else{
                return $objednavka[0];
            }
        }else{
            return false;
        }
    }

    /**
     * @param $id_objednavka
     * @return bool
     */
    public function objednejZbozi($id_objednavka): bool{
        $q = "UPDATE ".TABLE_OBJEDNAVKA." SET dokoncena=:dokoncena,datum=:datum WHERE id=:id;";
        $vstup = $this->pdo->prepare($q);
        $date = date("Y-m-d");
        $vstup->bindValue(":dokoncena",1);
        $vstup->bindValue(":id",$id_objednavka);
        $vstup->bindValue(":datum",$date);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id_uzivatel
     * @return array|null
     */
    public function vratHotoveObjednavkyUzivatele($id_uzivatel):array|null{
        $q = "SELECT * FROM ".TABLE_OBJEDNAVKA." WHERE id_uzivatel=:id_uzivatel AND dokoncena=:dokoncena;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_uzivatel",$id_uzivatel);
        $vystup->bindValue(":dokoncena",1);
        if($vystup->execute()){
            $objednavky = $vystup->fetchAll();
            if(empty($objednavky)){
                return null;
            }else{
                return $objednavky;
            }
        }else{
            return null;
        }
    }
    /****************************************************************************************************************/
    /**
     * @param $id_objednavka
     * @return array|null
     */
    public function vratProduktyZKosiku($id_objednavka):array|null{
        $q = "SELECT * FROM ".TABLE_OBSAHUJE." WHERE id_objednavka=:id_objednavka;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_objednavka",$id_objednavka);
        if($vystup->execute()){
            $produkty = $vystup->fetchAll();
            if(empty($produkty)){
                return null;
            }else{
                return $produkty;
            }
        }else{
            return null;
        }
    }

    /**
     * @param $id_objednavka
     * @return array|null
     */
    public function vratProduktyZHotoveObjednavky($id_objednavka):array|null{
        $q = "SELECT * FROM ".TABLE_OBSAHUJE." WHERE id_objednavka=:id_objednavka;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_objednavka",$id_objednavka);
        if($vystup->execute()){
            $produkty = $vystup->fetchAll();
            if(empty($produkty)){
                return null;
            }else{
                return $produkty;
            }
        }else{
            return null;
        }
    }

    /**
     * @param $id_objednavka
     * @param $id_produkt
     * @return array|null
     */
    public function vratProduktZKosiku($id_objednavka,$id_produkt):array|null{
        $q = "SELECT * FROM ".TABLE_OBSAHUJE." WHERE id_objednavka=:id_objednavka AND id_produkt=:id_produkt;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_objednavka",$id_objednavka);
        $vystup->bindValue(":id_produkt",$id_produkt);
        if($vystup->execute()){
            $ziskanyProdukt = $vystup->fetchAll();
            if(empty($ziskanyProdukt)){
                return null;
            }else{
                return $ziskanyProdukt[0];
            }
        }else{
            return null;
        }
    }

    /**
     * @param $id_produkt
     * @return bool
     */
    public function vlozDoKosiku($id_produkt):bool{
        $q = "INSERT INTO ".TABLE_OBSAHUJE." (id_objednavka,id_produkt,pocet_ks) VALUES (:id_objednavka,:id_produkt,:pocet_ks);";
        $objednavka = $this->vratVytvorenouNeodeslanouObjednavku();
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":id_objednavka",$objednavka['id']);
        $vstup->bindValue(":id_produkt",$id_produkt);
        $vstup->bindValue(":pocet_ks",1);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id_objednavka
     * @param $id_produkt
     * @param $pocet_ks
     * @return bool
     */
    public function upravProduktVKosiku($id_objednavka,$id_produkt,$pocet_ks):bool{
        $q = "UPDATE ".TABLE_OBSAHUJE." SET pocet_ks=:pocet_ks WHERE id_objednavka=:id_objednavka AND id_produkt=:id_produkt;";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":pocet_ks",$pocet_ks);
        $vstup->bindValue(":id_objednavka",$id_objednavka);
        $vstup->bindValue(":id_produkt",$id_produkt);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id_objednavka
     * @param $id_produkt
     * @return bool
     */
    public function odeberProduktZKosiku($id_objednavka,$id_produkt):bool{
        $q = "DELETE FROM ".TABLE_OBSAHUJE." WHERE id_objednavka=:id_objednavka AND id_produkt=:id_produkt;";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":id_objednavka",$id_objednavka);
        $vstup->bindValue(":id_produkt",$id_produkt);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }
    /****************************************************************************************************************/
    /**
     * @param $id
     * @return array|null
     */
    public function vratProdukt($id):array|null{
        $q = "SELECT * FROM ".TABLE_PRODUKT." WHERE id=:id;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id",$id);
        if($vystup->execute()){
            $produkt = $vystup->fetchAll();
            if(empty($produkt)){
                return null;
            }else{
                return $produkt[0];
            }
        }else{
            return null;
        }
    }

    /**
     * @return false|array|null
     */
    public function vratProdukty():false|array|null{
        $q = "SELECT * FROM ".TABLE_PRODUKT;
        $vystup = $this->pdo->prepare($q);
        if($vystup->execute()){
            $produkty = $vystup->fetchAll();
            if(empty($produkty)){
                return null;
            }else{
                return $produkty;
            }
        }else{
            return null;
        }
    }

    /**
     * @param $id
     * @param $uskladneno
     * @return bool
     */
    public function upravMnozstviUskladnenychProduktu($id,$uskladneno):bool{
        $q = "UPDATE ".TABLE_PRODUKT." SET uskladneno_ks=:uskladneno_ks WHERE id=:id";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":id",$id);
        $vstup->bindValue(":uskladneno_ks",$uskladneno);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $nazev
     * @param $cena
     * @param $uskladneno
     * @param $foto
     * @param $popis
     * @return bool
     */
    public function vlozNovyProdukt($nazev,$cena,$uskladneno,$foto,$popis):bool{
        $q = "INSERT INTO ".TABLE_PRODUKT." (nazev,cena,uskladneno_ks,foto,popis) VALUES (:nazev,:cena,:uskladneno_ks,:foto,:popis);";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":nazev",htmlspecialchars($nazev));
        $vstup->bindValue(":cena",$cena);
        $vstup->bindValue(":uskladneno_ks",$uskladneno);
        $vstup->bindValue(":foto",htmlspecialchars($foto));
        $vstup->bindValue(":popis",htmlspecialchars($popis));
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $foto
     * @return bool
     */
    public function kontrolaExistenceProduktu($foto):bool{
        $q = "SELECT * FROM ".TABLE_PRODUKT." WHERE foto=:foto";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":foto",$foto);
        if($vstup->execute()){
            $vystup = $vstup->fetchAll();
            if(empty($vystup)){
                return false;
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function odeberProduktZNabidky($id):bool{
        $q = "UPDATE ".TABLE_PRODUKT." SET uskladneno_ks=:uskladneno_ks WHERE id=:id;";
        $vstup = $this->pdo->prepare($q);
        $vstup->bindValue(":id",$id);
        $vstup->bindValue(":uskladneno_ks",0);
        if($vstup->execute()){
            return true;
        }else{
            return false;
        }
    }
}
