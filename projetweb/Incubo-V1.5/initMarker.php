<?php
include ("config.php");

$sql = "SELECT * FROM site";
$q = mysql_query($sql);
$rep=NULL;

/*
 * Requete SQL : récuperation de tous les markers
 */
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

?>