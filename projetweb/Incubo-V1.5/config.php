<?php
//On demarre les sessions
session_start();

/******************************************************
----------------Configuration Obligatoire--------------
Veuillez modifier les variables ci-dessous pour que l'
espace membre puisse fonctionner correctement.
******************************************************/

//On se connecte a la base de donnee
$desc = mysql_connect('tunnel.pagodabox.com:3306', 'rolande', 'ndN1A4Pc');
mysql_select_db('incubo-bdd');

//Email du webmaster
$mail_webmaster = 'example@example.com';

//Adresse du dossier de la top site
$url_root = 'http://www.localhost\projetweb\membre';

/******************************************************
----------------Configuration Optionelle---------------
******************************************************/

//Nom du fichier de laccueil
$url_home = 'map.php';

//Nom du design
$design = 'default';
?>
