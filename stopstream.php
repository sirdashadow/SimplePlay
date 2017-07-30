<?php
session_start();
echo "<meta http-equiv='refresh' content='2; url=http://las.mine.nu:49152/'>";

/*$processid2=shell_exec("ps -fU www-data | grep vlc | awk '{print $2}'"); 
$processid=shell_exec("ps -ef | grep avconv -i | grep -v $$ | awk '{print $2}'"); 
*/

$processid=shell_exec("ps -ef | grep :800" . $_SESSION['sessionid'] . " | grep -v $$ | awk '{print $2}'"); 


$token = strtok($processid, "\n");

while ($token !== false)
{
echo "$token<br>";
$processid=$token;
$token = strtok("\n");
} 

$output=shell_exec("rm /tmp/avresult.txt");
echo "<pre>pid:$processid</pre>";
 /* pid2:$processid2</pre>";*/
/* exec('nohup sudo kill -9 ' . $processid . ' >> /dev/null 2>&1 echo $!', $pid); */
$output=shell_exec("kill -9 $processid");
/*$output=shell_exec("kill -9 $processid2");*/
echo "<pre>Streaming stopped...</pre>";
?>

