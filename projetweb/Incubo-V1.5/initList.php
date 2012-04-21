<?php
include ("config.php");

$sql = "SELECT * FROM site";
$q = mysql_query($sql);
$rep=NULL;

$result = mysql_query($sql,$desc) or die ('Erreur : '.mysql_error() );
$total = mysql_num_rows($result);

// si on a récupéré un résultat on l'affiche.
if($total) {
  
    while($row = mysql_fetch_row($result)) {
        
        $rep .= "[\"" .$row[0]."\",\"".$row[1]."\",\"".$row[2]."\",\"".$row[3]."\"],";
    }

}


echo "{\"aaData\": [";
echo substr($rep,0,-1);
echo "]}"
 /*
 while($row = mysql_fetch_array($q)){

  echo "myarray[".$i."]='".$row['id']."';";
      $i++;

 }

 for($i=0; $i < mysql_fetch_lengths($q); $i++)
{
     if($sortie[$i]==$_POST["Pseudo"])
     {
          $trouve = true;
          break;  
     }
}

*/

?>