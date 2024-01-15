<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Ziskani uzivatele</title>
</head>
<body>

<?php

/**
 * Získání uživatel z databáze za použití AJAXU
 */

$q = intval($_GET['q']);
$idpp = intval($_GET['idpp']);

$con = mysqli_connect('localhost','root','');
if (!$con) {
    die('Nelze se připojit: ' . mysqli_error($con));
}

mysqli_select_db($con,"web");
$sql="SELECT * FROM uzivatel WHERE id ='".$q."'";
$result = mysqli_query($con,$sql);

echo "<div class='container'><div class='table-responsive'><table class='table table-hover table-warning'>
<tr>
<th>ID</th>
<th>Jméno</th>
<th>Příjmení</th>
<th>E-mail</th>
<th>ID Právo</th>
<th>Město</th>
</tr>";
$idUziv = 0;
while($row = mysqli_fetch_array($result)){
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>"; $idUziv = $row['id'];
    echo "<td>" . $row['jmeno'] . "</td>";
    echo "<td>" . $row['prijmeni'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['id_pravo'] . "</td>";
    echo "<td>" . $row['mesto'] . "</td>";
    echo "</tr>";
}
echo "<tr>
            <td colspan='6'>
                <select name='pravo' onchange='zmenPravo($idUziv,this.value,$idpp)'>
                    <option><b>Zvolte právo pro změnu:</b></option>";
        if($idpp == 1){
            echo   "<option value='2'>Admin</option>";
        }
            echo   "<option value='3'>Dodavatel</option>
                    <option value='4'>Konzument</option>
                </select>
            </td>
         </tr>";
echo "</table></div></div>";
mysqli_close($con);
?>
</body>
</html>