<?php
include('config.php');

echo $_POST['titre']."</br>".$_POST['adresse']."</br>".$_POST['date']."</br>".$_POST['description'];

$sql = "INSERT INTO site (titre, latitude, longitude, adresse, date, description)
VALUES ('$_POST[titre]', '$_POST[latitude]', '$_POST[longitude]', '$_POST[adresse]', '$_POST[date]', '$_POST[description]')"; 

// on envoie la requête 
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
/*
// on fait une boucle qui va faire un tour pour chaque enregistrement 
while($data = mysql_fetch_assoc($req)) 
    { 
    // on affiche les informations de l'enregistrement en cours 
    echo '<b>'.$data['nom'].' '.$data['prenom'].'</b> ('.$data['statut'].')'; 
    echo ' <i>date de naissance : '.$data['date'].'</i><br>'; 
    } 
*/
// on ferme la connexion à mysql 
mysql_close(); 



?>