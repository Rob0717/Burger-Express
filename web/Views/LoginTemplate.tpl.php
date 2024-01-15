<?php
global $tplData;

/**
 * Šablona pro přihlašovací sekci / uživatelskou sekci
 */

if(isset($_POST['prihlas']) && $tplData['prihlaseniUspesne']){
    ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Uživatel je úspěšně přihlášen',
    });
    </script>
    <?php
}elseif(isset($_POST['prihlas']) && !$tplData['prihlaseniUspesne']){
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Nepodařilo se přihlásit uživatele!',
        })
    </script>
    <?php
}
?>

<?php
if(isset($_POST['zmenHeslo']) && $tplData['hesloZmeneno']){
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Heslo úspěšně změněno',
            });
        </script>
        <?php
}else if(isset($_POST['zmenHeslo']) && !$tplData['hesloZmeneno']){
?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Heslo se nepodařilo změnit!',
        });
    </script>
<?php
}
?>

<?php if(!$tplData['jePrihlasen']){ ?>
<div class="container" id="prihlasovaciFormular">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="text-center">Přihlašovací formulář</h2>
            <form method="POST">
                <div class="row">
                    <div class="col mt-2">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Zadejte e-mail">
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-2">
                        <label for="heslo">Heslo</label>
                        <input type="password" class="form-control" name="heslo" id="heslo" placeholder="Zadejte heslo">
                    </div>
                </div>
                <div class="text-center col mt-4">
                    <button type="submit" name="prihlas" class="btn btn-primary">Přihlásit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }else{ ?>
    <div class="container">
        <form method="POST">
            <h2 style="color: white;padding-top: 20px">
                Uživatelská sekce
                <button type="submit" name="odhlas" class="btn btn-primary">
                    Odhlásit se
                </button>
            </h2>
        </form>

        <div class="table-responsive">
            <form method="POST">
                <table class="table table-striped table-dark table-hover">
                    <tr><th>Jméno</th><td><?= $tplData['jmenoPrihlaseny'] ?></td></tr>
                    <tr><th>Příjmení</th><td><?= $tplData['prijmeniPrihlaseny'] ?></td></tr>
                    <tr><th>E-mail</th><td><?= $tplData['emailPrihlaseny'] ?></td></tr>
                    <tr><th>Okres</th><td><?= $tplData['okresPrihlaseny'] ?></td></tr>
                    <tr><th>Město</th><td><?= $tplData['mestoPrihlaseny'] ?></td></tr>
                    <tr><th>Ulice</th><td><?= $tplData['ulicePrihlaseny'] ?></td></tr>
                    <tr><th>Č.P.</th><td><?= $tplData['cpPrihlaseny'] ?></td></tr>
                    <tr><th>PSČ</th><td><?= $tplData['pscPrihlaseny'] ?></td></tr>
                    <tr><th>ID právo</th><td><?= $tplData['idpravoPrihlaseny'] ?></td></tr>
                    <tr><th>Současné heslo</th>
                        <td>
                            <label for="stareHeslo">
                                <input type="password" id="stareHeslo" name="stareHeslo">
                            </label>
                        </td>
                    </tr>
                    <tr><th>Nové heslo</th>
                        <td>
                            <label for="noveHeslo">
                                <input type="password" id="noveHeslo" name="noveHeslo">
                            </label>
                        </td>
                    </tr>
                    <tr><th>Nové heslo znovu</th>
                        <td>
                            <label for="noveHesloZnovu">
                                <input type="password" id="noveHesloZnovu" name="noveHesloZnovu">
                            </label>
                            <button type="submit" name="zmenHeslo" class="btn btn-secondary">Změnit heslo</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php if($tplData['role'] != 2 && $tplData['role'] != 3){ ?>
        <div class="container">
            <h2 style="color: white;padding-top: 20px;">Moje Objednávky</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-responsive">
                    <tr>
                        <th>ID Objednávky</th><th>Název produktu</th><th>Počet ks</th>
                    </tr>
                    <?= $tplData['objednavkyRadek']; ?>
                </table>
            </div>
        </div>
    <?php } ?>

    <script src="libraries/jquery/dist/jquery.slim.min.js"></script>
    <link rel="stylesheet" href="libraries/summernote/summernote-lite.min.css">
    <script src="libraries/summernote/summernote-lite.min.js"></script>

    <div class="container">
        <h2 style="color: white;padding-top: 20px;">O mně</h2>
    </div>
    <div style="background-color: #b9b9b9; padding: 0;" class="container">
        <form method="post">
                <textarea style="color: white" id="summernote" name="obsah"></textarea>
            <label for="summernote"></label>
            <div class="container">
                <button type="submit" name="uloz" class="container btn btn-secondary"><b>Ulož</b></button>
            </div>
        </form>
    </div>
    <script>
        $('#summernote').summernote({
            placeholder: 'O mně',
            tabSize: 2,
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'clear']],
                ['fontsize', ['fontsize']],
            ],
        });
    </script>

    <?php
    if(isset($_POST['uloz'])){
    ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Popisek byl úspěšně uložen',
            })
        </script>
    <?php
    }
    ?>

<?php } ?>
