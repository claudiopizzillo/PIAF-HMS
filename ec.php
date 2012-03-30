<?php
include('config.inc.php');
include('header.php');


echo "<h1>".$Title8."</h1>" ;

	//Load Rates data from database
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die("Database Hotel connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Rates open failed");
	$query = "SELECT Pref, Min, Risp FROM `Rates`";
	$result = mysql_query($query) or die("Web site query failed");
	$data=array();
	mysql_close($dbconnection);
	if( mysql_num_rows($result) > 0 )
		{
  			while($row = mysql_fetch_array($result))
  		{
    			$data[]=$row;
  		}
	}

	//Load Local extensions in array
 	$dbconnection4 = mysql_connect($dbhost2, $dbuser2, $dbpass2) or die("Database connection failed");
	mysql_select_db($dbname2) or die("data base asterisk open failed");
	$query = "SELECT extension FROM `users`";
	$result = mysql_query($query) or die("Web site query load extensions failed");
	mysql_close($dbconnection4);
	if( mysql_num_rows($result) > 0 )	{
  			while($row = mysql_fetch_array($result))
  			{
    				$extensions[]=$row["extension"];
  			}
	}


	function Coc($dst, $sec, $data) {
		$sum = 0;
		foreach($data as $v) {
			$Pref = $v["Pref"];
			$Min = $v["Min"];
			$Risp = $v["Risp"];
			$strLength = strlen($Pref);
			if (substr($dst,0,$strLength) == $Pref AND $sec > 0) {
				$sum = $Risp + (($Min / 60) * $sec);
			} 
		}
		return round($sum,2);
   	}


	//Load Asteriskcdrdb data from database
 	$dbconnection3 = mysql_connect($dbhost3, $dbuser3, $dbpass3)
    		or die("Database connection failed");
	mysql_select_db($dbname3) or die("data base asteriskcdrdb open failed");

	$query = "SELECT calldate, dst, billsec FROM `cdr` WHERE dstchannel IS NOT NULL AND dcontext = 'from-internal' AND (lastapp = 'Dial' OR lastapp = 'ResetCDR') AND calldate BETWEEN '" . $_GET['Checkin'] . "' AND '" . $_GET['Checkout'] ."' AND channel LIKE '%/" . $_GET['Ext'] ."-%'";
	$result = mysql_query($query)
    		or die("Web site query failed");
	mysql_close($dbconnection3);
	echo "<TABLE cellSpacing=0 cellPadding=0 width=900 border=0>\n" ;
	echo "<TR><TD>".$SecLab25."</TD><TD>".$SecLab26."</TD><TD>".$SecLab27."</TD><TD>".$SecLab28."</TD>\n" ;
	while ($row = mysql_fetch_array($result)) {
		If (in_array($row["dst"], $extensions)) {
			// Do nothing is a local extension
			}
			Else {
 			echo "<TR><TD><FONT face=verdana,sans-serif>" . $row["calldate"] . "</TD><TD>" . $row["dst"]  . "</TD><TD>" .$row["billsec"] ."</TD><TD>" . Coc($row["dst"], $row["billsec"], $data). "</TD></TR>\n" ;
		}
	}
	echo "</TABLE>\n";

include('footer.php');

?>

