<?php
global $tplData;

/**
 * Šablona pro dodavatelskou sekci
 */

?>

<?php
if((isset($tplData['role']) && $tplData['role'] % 2 == 0) || !$tplData['jePrihlasen']){
?>
    <h2 class="text-center" style="padding-top: 20px;color: white">Pouze pro dodavatele.</h2>
<?php
}else{
?>
    <div class="container">
        <h2 style="color: white;padding-top: 20px">Přidání produktu</h2>
        <div class="table-responsive">
            <form method="post" enctype="multipart/form-data">
                <table class="table table-hover table-dark">
                    <tr>
                        <th>Název produktu</th>
                        <td>
                            <label>
                                <input type="text" name="nazevNovy" id="nazevNovy" placeholder="název produktu">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Soubor</th>
                        <td>
                            <label>
                                <input type="file" name="souborNovy" id="souborNovy">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Cena</th>
                        <td>
                            <label>
                                <input type="number" name="cenaNovy" id="cenaNovy" min="50" max="1000"> Kč
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Počet ks k uskladnění</th>
                        <td>
                            <label>
                                <input type="number" name="ksNovy" id="ksNovy" min="0" max="1000"> ks
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Popis</th>
                        <td>
                            <label>
                                <input type="text" name="popisNovy" id="popisNovy" placeholder="popis">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <label>
                                <input class="btn btn-primary" type="submit" name="vlozNovy" id="vlozNovy" value="Uložit">
                            </label>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php
    if(isset($_POST['vlozNovy'])){
        if($tplData['existuje']){
    ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Nový produkt byl nahrán'
                })
            </script>
    <?php
        }else{
    ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Nepodařilo se nahrát nový produkt'
                })
            </script>
    <?php
        }
    }
    ?>

    <?php
    foreach($tplData['vsechnyProdukty'] as $produkt){
        $id_produktu = $produkt['id'];
        if(isset($_POST["btnUpravPocet$id_produktu"])){
            ?>
            <script type="text/javascript">
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Množství produktu \n <b style="color: yellow;text-shadow: -1px 1px 0 #000,1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;"><?= $produkt['nazev'] ?></b> \n bylo změněno z \n <?= $tplData['staryPocet'] ?> na <?= $tplData['novyPocet'] ?>',
                    showConfirmButton: false,
                    timer: 2000,
                })
            </script>
            <?php
        }
        if(isset($_POST["btnOdstran$id_produktu"])){
            ?>
            <script type="text/javascript">
                Swal.fire({
                    icon: 'success',
                    title: 'Produkt byl odebrán z nabídky',
                })
            </script>
            <?php
        }
    }
    echo $tplData['produkty'];
}
?>