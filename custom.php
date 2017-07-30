<?php
require_once('stopstream.php');


$codec = htmlspecialchars($_GET["codec"]);
$bitrate =  htmlspecialchars($_GET["bitrate"]);
$stream = htmlspecialchars($_GET["stream"]);
$stream=str_replace("+", " ", $stream);
$container="ogg";
$channels = htmlspecialchars($_GET["channels"]);


if (empty($codec))
 $codec="opus";

if (empty($bitrate))
 $bitrate="32";

if ($codec=="mp4a")
 $container="mp4";



$intro=' "/var/www/html/uploads/CalancoPiIntro.mp3" ';

if (strpos($stream,"youtube") == FALSE && strpos($stream,"youtu.be") == FALSE && strpos($stream,"soundcloud.com") == FALSE && strpos($stream,"fbcdn") == FALSE )  
   $command='/home/pi/ss ' . $codec . ' ' . $bitrate. ' ' . $channels. ' ' . $container . ' "' . $stream . '"' . " " . $_SESSION['sessionid'];
else
   $command='/home/pi/ss.bak ' . $codec . ' ' . $bitrate. ' ' . $channels. ' ' . $container . ' "' . $stream . '"'.  " " . $_SESSION['sessionid'];


/* exec('nohup ' . $command . ' >> /dev/null 2>&1 echo $!', $pid); */
exec('nohup ' . $command . ' >> /tmp/avresult.txt 2>&1 echo $!', $pid);

echo "<pre>Starting ... $command</pre>";
?>
