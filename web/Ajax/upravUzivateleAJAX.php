<?php

/**
 * Úprava uživatelské role za použití AJAXU
 */

$q = intval($_GET['q']);
$idpravo = intval($_GET['s']);
$idpp = intval($_GET['idpp']);

$con = mysqli_connect('localhost','root','');
if (!$con) {
    die('Chyba připojení: ' . mysqli_error($con));
}

mysqli_select_db($con,"web");
$sql = "UPDATE uzivatel SET id_pravo=$idpravo WHERE id=$q;";
$result = mysqli_query($con,$sql);
mysqli_close($con);