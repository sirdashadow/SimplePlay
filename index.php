<?php
session_start();

if (!isset($_SESSION["sessionid"]))  {
$_SESSION["sessionid"] = rand(1,8);	
}
$id=$_SESSION["sessionid"];

if (!empty($_GET["s"]))  {
  $id=htmlspecialchars($_GET["s"]);
  $_SESSION["sessionid"] = $id;

}
	
$temperature=shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
$temperature=(int)$temperature/1000; 
$temperature=(int)$temperature;

/* $processid2=shell_exec("ps -fU www-data | grep vlc | grep -v $$ | awk '{print $2}'");*/
/*$processid=shell_exec("ps ax | grep avconv |grep content_type| grep -v $$ | awk '{print $8}'");*/
$processid=shell_exec("ps -ef | grep :800". $id . " | grep -v $$ | awk '{print $2}'"); 

/*$processid2=shell_exec("ps -fU www-data | grep vlc |grep -v grep | awk '{print $14}'"); 
$processid=shell_exec("ps ax | grep avconv -i | grep -v grep | awk '{print $7}'");*/

class MyDB extends SQLite3 {
 function __construct()
 { 
          $this->open('stations.db'); 
  } 
} 

echo <<<EOL
<!DOCTYPE HTML>
<HTML>
<head>
<link rel="stylesheet" type="text/css" href="buttons.css"> 
<Title id="titleid"></Title>
</head>
<body onload="myFunction(99);">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->
<BR>
<form id="idform" action="custom.php" method="get" >
<Center>
<!-- Enter URL or select from menu
-->
<input list="streams" name="stream">
<datalist id="streams">

EOL;


$db = new MyDB(); 

$res=$db->query('SELECT StationOrder, StationName, StationURL, StationOrder FROM Stations ORDER BY StationOrder');

while ($result=$res->fetchArray(SQLITE3_NUM))
{
 $cnt=count($result);
 for($x=1;$x<$cnt;$x=$x+3) { 
     echo "<option value='" . $result[$x+1] . "'>". $result[$x] . "</option>\n"; 
      }

}

echo "<option value=\"-Podcasts\">Podcasts</option>";

/* What's on the folder podcasts */
$list=shell_exec("ls /home/pi/podcasts/?*.*");

/* What's on RAM recorded */
$list=$list . shell_exec("ls -l /tmp/ram/?*.opus | awk '{print $8 \" \" $9}'");

/* What's on the shared drive on kickass mp3s */
$list=$list . shell_exec("find /mnt/kickass/books/audiobooks/Alvarez\ Guedes |grep mp3");
/* $list=$list . shell_exec("find /mnt/kickass/Music |grep mp3"); */

/* SBS Broadcasting (La Mega, etc) podcasts */
$sbspodcasts =shell_exec("/home/pi/sbspodcasts");
$sbspodcasts = explode("\n", $sbspodcasts);
$i=1;
while (!empty($sbspodcasts[$i])) {
$single=$sbspodcasts[$i];
$sinpos=strpos($single,".mp3");
$single="http://mega1069.sbs.co/wp-content/uploads/sites/17/2017/07/". substr($single,6,$sinpos-2);
$list= $list . $single . "\n";
 $i=$i+1;
}


$podcasts = explode("\n", $list);
$i=0;
while (!empty($podcasts[$i])) {
$filename=$podcasts[$i];
 echo "<option data-value='" . substr($podcasts[$i],18,30) . "' value='". $filename. "'>" . substr($podcasts[$i],18,30) . "</option>\n";
 $i=$i+1;
}

echo"</datalist>";


echo <<<EOL

<Select name="bitrate">
  <option value="6">6k</option>  <option value="8">8k</option> <option value="12">12k</option> <option value="16">16k</option>
  <option value="24">24k</option>  <option selected value="32">32k</option>  <option value="48">48k</option>
  <option value="64">64k</option>  <option value="96">96k</option>  <option value="112">112k</option>  <option value="128">128k</option>
</Select>


<Select name="codec">
  <option selected value="opus">opus</option> <option value="mp4a">mp4a</option> <option value="vorb">vorb</option> <option value="mp3">mp3</option>
</Select>


<select name="channels">
  <option selected value="2">Streo</option> <option value="1">Mono</option>
</select>
<BR><BR>
<input class=buttongreen type="submit" value="           Play Stream           ">

</center>
</form>
<Center>
<BR><button class=button onclick="document.location.href='manage.php'">Options</button><BR><BR>

EOL;
$res=$db->query('SELECT StationName, StationURL, StationOrder FROM Stations WHERE Shortcut = 1 ORDER BY StationOrder');
while ($result=$res->fetchArray(SQLITE3_NUM))

{
 $cnt=count($result);
 for($x=0;$x<$cnt;$x=$x+3) { 
     echo "<button class=button onclick=\"myFunction('". $result[$x+1]."')\">" . substr($result[$x],0,8) . "</button>";
      }

}
 
$today = date("Y-m-d");
 
echo <<<EOL

<BR><BR>
<Select id="Show">
  <option selected value="agitando-el-show-">Agitando</option>
  <option value="calanco-">Calanco Pi</option>
  <option value="la-perrera-">La Perrera</option>
  <option value="jesse-y-bebe-">Jesse Y Bebe</option>
</Select>

<label for="meeting">Date<a href="http://las.mine.nu:49152/?s=9">:</a> </label><input id="meeting" type="date" value="$today"/>

<button class=button onclick="myFunction(9)">Play Podcast</button><BR><BR>

<script>
function checkdate(){
var dt=new Date(getSelectedText('Year'),document.getElementById('Month').selectedIndex,getSelectedText('Day'));
if ( (dt.getDay() == 0) || (dt.getDay() == 6) ) {
 alert("Date is Saturday or Sunday " + dt);
 }
}

function getSelectedText(elementId) {
 var elt = document.getElementById(elementId);
 if (elt.selectedIndex == -1) return null;
  return elt.options[elt.selectedIndex].value;
}

function IsNumeric(input)
{
    return (input - 0) == input && (''+input).trim().length > 0;
}


function dummy()
{
	var z="coocoo";
}

function myFunction(n){
  switch (n) {
	case 6: document.getElementById('player').volume = 0.7;
	        document.getElementById('player').play();
    break;
	
    case 8:
          	document.location.href='http://las.mine.nu:49152/destroy_session.php';
			setTimeout(dummy, 5000);
    break;	
	
   case 9:
   
   var x = document.getElementsByName('stream')[0].value;

   
   if (IsNumeric(x) || x == "0")
   {
	 document.location.href='http://las.mine.nu:49152?s=' + x;
	   setTimeout(dummy, 5000);
   }
  else
  {	  
   if (document.getElementById('player'))
	{   document.getElementById('player').src =""; 
         document.getElementById('player').pause;
		 document.getElementById('player').currentTime =0;
    }
   var mes=["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
   document.location.href='custom2.php?bitrate='+document.getElementById("idform").elements["bitrate"].value +'&codec='+document.getElementById("idform").elements["codec"].value   +'&channels='+document.getElementById("idform").elements["channels"].value +'&stream=https://soundcloud.com/salsoulfm-991/'+ getSelectedText('Show') + Math.floor(meeting.value.substring(8,10)) + '-' + mes[meeting.value.substring(5,7)-1] + '-' + meeting.value.substring(0,4);
  } 

   break;

   case 10:
    if (document.getElementById('player'))
	{    document.getElementById('player').src ="";
         document.getElementById('player').pause;
		 document.getElementById('player').currentTime =0;
	}
    document.location.href='http://las.mine.nu:49152/destroy_session.php';
   break;
   
   case 99:
	document.getElementById("titleid").innerHTML = "SimplePlay ($id) $temperature °C";
	myFunction(6);
    break;
   
   default:
     document.location.href='custom.php?bitrate='+document.getElementById("idform").elements["bitrate"].value +'&codec='+document.getElementById("idform").elements["codec"].value   +'&channels='+document.getElementById("idform").elements["channels"].value +'&stream=' + n;
   
   
  }
}
</script>

</Center>
EOL;


/* echo "pid1:" . $processid . "pid2:" . $processid2 . "<BR>" .strlen($processid). " ".strlen($processid2);*/

if ( strlen($processid) > 2)
 {
echo <<<EOL
 <Center>
 <audio id="player" source src="http://las.mine.nu:800$id/stream$id" type="audio/ogg" />
 Your browser does not support the audio element.
 </audio>

<div>
<button class=buttonred onclick="document.getElementById('player').pause()">Mute</button> 
<button class=buttongreen onclick="document.getElementById('player').play()">P l a y</button> 
<button class=buttonred onclick="myFunction(10)">Stop</button><BR><BR> 
Volume <input type="range"  min="0" max="1" step="0.01" value="0.7" onchange="document.getElementById('player').volume = this.value "/>
</div><BR>

EOL;

$statname="";
 if (strlen($processid) > 2 ) {
 $statname=shell_exec("ps -ef |grep :800". $id. "|grep -v grep | awk '{print $11}'"); 
 }
if (strpos($statname,"volume") > 0) {
 $statname=shell_exec("ps -ef |grep :800". $id. "|grep -v grep | awk '{print $16}'"); 
 }
  
  $qry="SELECT StationName, StationURL FROM Stations WHERE StationUrl LIKE '" . trim($statname) . "'";
  $res=$db->query($qry);
  $row=$res->fetchArray(SQLITE3_NUM);

if ($row[0]=="") {
     $row[0]=$statname;
	 $row[1]=$statname;
  }
  echo "Streaming <A HREF='". $row[1]."'>". $row[0] . "</a></center>";
  
 $info=shell_exec("cat /tmp/avresult.txt |grep \"Stream #0.0: Audio:\""); 
 $inf1=substr($info,  strpos($info, "kb/s")-4, 3);
 $info=substr($info, $inf1 + 10);
 $inf2=substr($info,  strpos($info, "kb/s")-4, 3);
 $inf1=$inf1 / 8 * 3.6;
 $inf2=$inf2 / 8 * 3.6;
 
/* $temp=shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
 $temp=$temp/1000; */
 
 if ($inf1 != 0) {
	 echo "<Center>" . $inf1 . "MB/hr->" . $inf2 . "MB/hr, savings of ". ($inf1 - $inf2) .  "MB/hr " . $temperature . "°C </center>";
 }
}
/* echo "Session Number is:" . $_SESSION["sessionid"];*/


echo "</body>";
echo "</HTML>";


?>
