<?php
global $tplData;

/**
 * Šablona pro sekci s nabídkou
 * produktů pro uživatele
 */

?>

<?php
foreach($tplData['prod'] as $p){
    $id = $p['id'];
    if(isset($_POST["btnDoKosiku$id"]) && $tplData['jeVKosiku'] && $tplData['lzeVlozitDoKosiku']){
?>
    <script>
        Swal.fire({
                icon: 'info',
                title: 'Zboží je již v košíku',
            });
    </script>
<?php
    }else if(isset($_POST["btnDoKosiku$id"]) && !$tplData['jeVKosiku'] && $tplData['lzeVlozitDoKosiku']){
?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Zboží přidáno do košíku',
        });
    </script>
<?php
    }else if(isset($_POST["btnDoKosiku$id"]) && !$tplData['jeVKosiku'] && !$tplData['lzeVlozitDoKosiku']){
?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Zboží nelze přidat do košíku',
        });
    </script>
<?php
    }
}
?>

<?php
if(isset($tplData['upravaProduktu'])){
?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Množství produktu \n <b style="color: yellow;text-shadow: -1px 1px 0 #000,1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;"><?= $tplData['upravenyProduktNazev'] ?></b> \n bylo upraveno',
            showConfirmButton: false,
            timer: 1000,
        })
    </script>
<?php
}
?>
<?php
if(isset($tplData['odebraniProduktu'])){
?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Produkt \n <b style="color: yellow;text-shadow: -1px 1px 0 #000,1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;"><?= $tplData['odebranyProduktNazev'] ?></b> \n úspěšně odebrán \n z košíku',
            showConfirmButton: false,
            timer: 1000,
        })
    </script>
<?php
}
?>

<?php
if(isset($_POST['btnObjednat']) && $tplData['jeObjednano']){
    $tplData['produktRadekVTabulce'] = "";
    $tplData['tlacitkoObjednat'] = "";
    $tplData['cenaCelkemRadek'] = "";
?>
    <script>
        Swal.fire({
                icon: 'success',
                title: 'Zboží je úspěšně objednáno',
            });
    </script>
<?php
}elseif(isset($_POST['btnObjednat']) && !$tplData['jeObjednano']){
?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Zboží se nepodařilo objednat',
        });
    </script>
<?php
}
?>

<?php if(!$tplData['jePrihlasen']){ ?>
    <h2 style="color: white;padding-top: 20px;" class="text-center">Nabídka produktů jen pro přihlášené uživatele.</h2>
<?php }else{
    if($tplData['role'] == 2 || $tplData['role'] == 3){
?>
        <h2 style="color: white;padding-top: 20px;" class="text-center">Nabídka produktů jen pro konzumenty.</h2>
<?php
    }else{
?>
        <div class="table-responsive">
            <form method="POST">
                <table class="table table-hover">
                    <tr style="font-size: 28px"><th>Můj Košík</th><th></th><th></th></tr>
                    <?= $tplData['produktRadekVTabulce']; ?>
                    <?= $tplData['cenaCelkemRadek']; ?>
                    <?= $tplData['tlacitkoObjednat']; ?>
                </table>
            </form>
        </div>

    <?= $tplData['hlavickaVsechProduktu']; ?>
    <?= $tplData['vsechnyProdukty']; ?>
    <?= $tplData['patickaVsechProduktu']; ?>
<?php   } ?>
<?php } ?>
