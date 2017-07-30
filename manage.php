<?php

class MyDB extends SQLite3 {
 function __construct()
 {
          $this->open('stations.db');
  }
}


$db = new MyDB();

?>
<style> table, th, td { border: 1px solid black; text-align: left; } </style> 
<table style='width:70%'>


<script>
function reqListener () {
      console.log(this.responseText);
    }

function edits(id) {
 var year = document.getElementById(year_id).value;

    callPage('edit.php?id='+id,document.getElementById(targetId));
}

function addStation(id){
var url = prompt("Please enter/paste the station URL", "");
var name = prompt("Please enter the station name", "");

 if (url != null && url != "" && name != null && name != "") {
var oReq = new XMLHttpRequest(); 
oReq.onload = function() {var ck=this.responseText;
      document.location.href="addstation.php?StationName="+name+"&StationUrl="+url+"&ck="+ck;
	    };
oReq.open("get", "hashme.php?&u=" + url + "&n=" + name, true);
oReq.send();
 }
  
}

function editStation(id){
   alert("The Edit ID is id " + id);
  }


function delStation(id){
   if (confirm("Are you sure you want to delete #" + id + "?"))
      {
        var oReq = new XMLHttpRequest(); 
        oReq.onload = function() {var ck=this.responseText;
        document.location.href="deletestation.php?id="+id+"&ck="+ ck;
	    }
      oReq.open("get", "hashme.php?&u=" + id + "&n=none", true);
      oReq.send();

	  }

    }


</script>


<tr><th>Id</th><th>StationName</th><th>StationUrl</th><th>Shortcut</th><th>Edit</th><th>Delete</th></tr>
<?php
$res=$db->query('SELECT * FROM Stations');

while ($result=$res->fetchArray(SQLITE3_NUM))

{
/* $res=$db->query('SELECT StationName FROM Stations WHERE StationOrder=id'); */
/* result[0] has the id */
 $cnt=count($result);
 for($x=0;$x<$cnt;$x++) { 
    echo "<td>" . $result[$x] . "</td>";
    }
    $sid=$result[0];
    echo "<td><button onclick=\"editStation($sid)\">Edit</button></td>";
    echo "<td><button onclick=\"delStation($sid)\">Delete</button></td>";
    echo "</tr>";
}

?>
</table>
<BR><button onclick="addStation($sid)">Add Station</button>
&nbsp&nbsp&nbsp&nbsp

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Import M3U Playlist" name="submit">
</form>

<BR>Active Streams<BR>

<?php
$x=shell_exec("ps -ef | grep :800 | grep -v $$ ");
$x=str_replace("\n","<BR>",$x);
$x=substr($x,strlen($x)-17,17);
echo $x;
?>