<?php
class MyDB extends SQLite3 {
 function __construct()
 { 
          $this->open('stations.db'); 
  } 
} 
$StationName = htmlspecialchars($_GET["StationName"]);
$StationUrl =  htmlspecialchars($_GET["StationUrl"]);
$ck = htmlspecialchars($_GET["ck"]);
/* $stream=str_replace("+", " ", $stream); */


echo "<BR>Adding ".$StationName. " with url ". $StationUrl;
$chek=hash("sha256", $StationName . $StationUrl . "BECAUSE ENCRYPTION IS A WAY OF LIFE AND NOTHING ELSE MATTERS");
/* echo "<BR>$ck"; */
/* echo "<BR>$chek"; */

if ($ck != $chek) 
{	$StationId = $StationId + rand(99999,99999);
}

else
{
  $qry="SELECT StationName FROM Stations WHERE StationUrl LIKE '" . trim($StationName) . "'";
  $res=$db->query($qry);
  $row=$res->fetchArray(SQLITE3_NUM);
}  
  
echo "<BR>Sucessfully added with ID #" . $StationId; 







?>
