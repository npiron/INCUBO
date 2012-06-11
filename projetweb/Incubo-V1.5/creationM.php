<?php
include('config.php');

echo $_POST['titre']."</br>".$_POST['adresse']."</br>".$_POST['date']."</br>".$_POST['description'];

$sql = "INSERT INTO site (
    codeSite, 
    latitude, 
    longitude, 
    titre, 
    adresse, 
    commune, 
    departement, 
    lieuxDit, 
    date, 
    description, 
    auteurFiche, 
    operation, 
    structArcheo, 
    mobilierArcheo, 
    sourcesHisto, 
    sourcesEpigra, 
    datation)
    VALUES (
    '$_POST[codeSite]', 
    '$_POST[latitude]', 
    '$_POST[longitude]', 
    '$_POST[titre]', 
    '$_POST[adresse]', 
    '$_POST[commune]',
    '$_POST[departement]', 
    '$_POST[lieuxDit]',
    '$_POST[date]', 
    '$_POST[description]', 
    '$_POST[auteurFiche]', 
    '$_POST[operation]', 
    '$_POST[structArcheo]',   
    '$_POST[mobilierArcheo]',
    '$_POST[sourcesHisto]',
    '$_POST[sourcesEpigra]',
    '$_POST[datation]'                                        
)"; 

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