<?php
include ("config.php");

$sql = "SELECT * FROM site";
$q = mysql_query($sql);
$rep=NULL;

$res = mysql_query($sql,$desc) or die ('Erreur : '.mysql_error() );






$dom = new DomDocument('1.0', 'utf-8');
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

while ($result = mysql_fetch_array($res)){
    $node = $dom->createElement("marker");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("titre", $result['titre']);
    $newnode->setAttribute("lat", $result['latitude']);
    $newnode->setAttribute("lng", $result['longitude']);
    $newnode->setAttribute("description",
    utf8_encode($result['description']));
}
$xmlfile = $dom->saveXML();
echo $xmlfile;




















// si on a récupéré un résultat on l'affiche.
/*
if($total) {
  
    while($row = mysql_fetch_row($result)) {
        $rep = $rep.xmlrpc_encode(mysql_fetch_assoc($result));
    }

}




$xml = '<query numrows="'.mysql_num_rows($result).'">\n';
 
for($i = 0; $i < mysql_num_rows($result); $i++)
 
{
 
       $xml .= '<row>';
 
       $row= mysql_fetch_row($result);
 
       //pour tous les champs
 
       for($j = 0; $j < mysql_num_fields($result); $j++)
 
              $xml .= '<field name="'.mysql_field_name($result,$j).'">'.$row[$j].'</field>';
 
      $xml .= '</row>\n';
 
}
 
$xml .= '</query>\n';
 
mysql_free_result($result);
 

echo $xml;

*/

?>