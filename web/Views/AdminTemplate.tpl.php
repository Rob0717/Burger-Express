<?php
global $tplData;

/**
 * Šablona pro administrátorskou sekci
 */

?>

<?php
if($tplData['jePrihlasen']){
    if($tplData['role'] == 1 || $tplData['role'] == 2){
?>
        <div class="container">
            <h2 style="color: white;padding-top: 20px;">O uživatelích</h2>
            <div class="table-responsive">
                <table class="table table-success table-hover">
                    <tr><th>Jméno</th><th>Příjmení</th><th>O uživateli</th></tr>
                    <?php
                    if(isset($tplData['vsichniUzivatele'])){
                        foreach($tplData['vsichniUzivatele'] as $uzivatel){
                            $o_mne = trim($uzivatel['o_mne']) == "" ? "Nebyla vložena žádná data." : trim($uzivatel['o_mne']);
                            echo "<tr><td>$uzivatel[jmeno]</td><td>$uzivatel[prijmeni]</td><td>$o_mne</td></tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        <form>
            <div class="container">
                <label>
                    <select name="users" onchange="showUser(<?= $tplData['role'] ?>,this.value)">
                        <option value="" selected>Zvolte osobu:</option>
                        <?php
                        if(isset($tplData['vsichniUzivatele'])){
                            foreach($tplData['vsichniUzivatele'] as $u){
                                if($tplData['role'] == 1){
                                    if($u['id_pravo'] > 1){
                                        echo "<option value='$u[id]'>$u[jmeno] $u[prijmeni]</option>";
                                    }
                                }
                                if($tplData['role'] == 2){
                                    if($u['id_pravo'] > 2){
                                        echo "<option value='$u[id]'>$u[jmeno] $u[prijmeni]</option>";
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                </label>
            </div>
        </form>
        <br>
        <div id="txtHint"></div>
<?php
    }else{
?>
        <h2 class="text-center" style="padding-top: 20px;color: white">Pouze pro administrátory.</h2>
<?php
    }
}else{
?>
    <h2 class="text-center" style="padding-top: 20px;color: white">Pouze pro přihlášené.</h2>
<?php
}
?>






