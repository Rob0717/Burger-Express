<?php
global $tplData;

/**
 * Šablona pro sekci registrace
 */

    if(isset($_POST['registruj']) && $tplData['jeRegistrovan']){
        ?>
        <script type="text/javascript">
            Swal.fire({
                icon: 'success',
                title: 'Registrace proběhla úspěšně',
                showConfirmButton: true,
                allowOutsideClick: false,
                confirmButtonText: `OK`,
                customClass: {
                    confirmButton: 'order-1',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace("index.php?page=uvodni_strana");
                }
            });
        </script>
        <?php
    }elseif(isset($_POST['registruj']) && !$tplData['jeRegistrovan']){
        ?>
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Nepodařilo se registrovat uživatele!',
                showConfirmButton: true,
                allowOutsideClick: false,
                confirmButtonText: `OK`,
                customClass: {
                    confirmButton: 'order-1',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace("index.php?page=registracni_strana");
                }
            });
        </script>
    <?php
    }
    ?>



<?php
if(!$tplData['jePrihlasen']){
?>
<div class="container" id="registracniFormular">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="text-center">Registrační formulář</h2>
            <form method="POST">
                <div class="row">
                    <div class="col mt-2">
                        <label for="name">Jméno</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Zadejte jméno">
                    </div>
                    <div class="col mt-2">
                        <label for="surname">Příjmení</label>
                        <input type="text" class="form-control" name="surname" id="surname" placeholder="Zadejte příjmení">
                    </div>
                </div>
                <div class="col mt-2">
                    <label for="email">Emailová adresa</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Zadejte email">
                </div>
                <div class="row">
                    <div class="col mt-2">
                        <label for="password1">Heslo</label>
                        <input type="password" class="form-control" name="password1" id="password1" placeholder="Zadejte heslo">
                    </div>
                    <div class="col mt-2">
                        <label for="password2">Heslo znovu</label>
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="Zadejte heslo znovu">
                    </div>
                </div>
                <h4 class="text-center col mt-4">Bydliště</h4>
                <div class="row">
                    <div class="col mt-2">
                        <label for="street">Ulice</label>
                        <input type="text" class="form-control" name="street" id="street" placeholder="ulice">
                    </div>
                    <div class="col mt-2">
                        <label for="housenumber">ČP</label>
                        <input type="number" min="1" max="1000" class="form-control" name="housenumber" id="housenumber" placeholder="čp">
                    </div>
                    <div class="col mt-2">
                        <label for="psc">PSČ</label>
                        <input type="number" min="10000" max="79999" class="form-control" name="psc" id="psc" placeholder="psč">
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-2">
                        <label for="city">Město</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="město">
                    </div>
                    <div class="col mt-2">
                        <label for="okres">Okres</label>
                        <input list="okresy" class="form-control" name="okres" id="okres">
                        <datalist id="okresy">
                            <?php
                            $okresy = array("Benešov","Beroun","Blansko","Brno-město","Brno-venkov","Bruntál",
                                "Břeclav","Česká Lípa","České Budějovice","Český Krumlov","Děčín","Domažlice",
                                "Frýdek-Místek","Havlíčkův Brod","Hodonín","Hradec Králové","Cheb","Chomutov",
                                "Chrudim","Jablonec nad Nisou","Jeseník","Jičín","Jihlava","Jindřichův Hradec",
                                "Karlovy Vary","Karviná","Kladno","Klatovy","Kolín","Kroměříž","Kutná Hora",
                                "Liberec","Litoměřice","Louny","Mělník","Mladá Boleslav","Most","Náchod",
                                "Nový Jičín","Nymburk","Olomouc","Opava","Ostrava-město","Pardubice","Pelhřimov",
                                "Písek","Plzeň-jih","Plzeň-město","Plzeň-sever","Praha-východ","Praha-západ",
                                "Prachatice","Prostějov","Přerov","Příbram","Rakovník","Rokycany","Rychnov nad Kněžnou",
                                "Semily","Sokolov","Strakonice","Svitavy","Šumperk","Tábor","Tachov","Teplice","Trutnov",
                                "Třebíč","Uherské Hradiště","Ústí nad Labem","Ústí nad Orlicí","Vsetín","Vyškov","Zlín",
                                "Znojmo","Žďár nad Sázavou");
                            echo "<option value=''>";
                            foreach($okresy as $okres){
                                echo "<option value='$okres'>$okres";
                            }
                            ?>
                        </datalist>
                    </div>
                </div>
                <div class="text-center col mt-4">
                    <button type="submit" name="registruj" class="btn btn-primary">Registrovat</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}else{
?>
    <h2 class="text-center" style="padding-top: 20px;color:white">Pouze pro nepřihlášené uživatele.</h2>
<?php
}
?>
