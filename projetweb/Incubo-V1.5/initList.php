<?php
include ("config.php");

$sql = "SELECT * FROM site";


$rep=NULL;
$result = mysql_query($sql,$desc) or die ('Erreur : '.mysql_error() );
$total = mysql_num_rows($result);

// si on a récupéré un résultat on l'affiche.
if($total) {
  
    $i=0;
while($row = mysql_fetch_assoc($result)) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['id'],
    $row['codeSite'],
    $row['latitude'],
    $row['longitude'],
    $row['titre'],
    $row['adresse'],
    $row['commune'],
    $row['departement'],
    $row['lieuxDit'],
    $row['date'],
    $row['description'],
    $row['auteurFiche'],
    $row['operation'],
    $row['structArcheo'],
    $row['mobilierArcheo'],
    $row['sourcesHisto'],
    $row['sourcesEpigra'],
    $row['datation'],
    $row['piecesJointes']
    );
    $i++;
}        


}

print json_encode($responce);






















?>