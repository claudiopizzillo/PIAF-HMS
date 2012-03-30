<?php
include('config.inc.php');

$result = '';	

if(isset($_GET['Ext'])){
	$Ext = $_GET['Ext'];
	if ($Ext <> "") :
		// Check if extension exists in Rooms Table
 		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die("Database connection failed");
		mysql_select_db($dbname) or die("data base Hotel-Rooms open failed");
		$query = "SELECT `ID` FROM `Rooms` WHERE `Ext` = '".$Ext."'";
		$result = mysql_query($query) or die("Web site query failed");
		$row = mysql_fetch_array($result);
		mysql_close($dbconnection);
	 	$ID = $row["ID"];
		If ($ID > 0) {
			//Check if Ext is enabled: check in yes and check out null
 			$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die("Database connection failed");
			mysql_select_db($dbname) or die("data base Hotel-Users open failed");
			$query = "SELECT `ID` FROM `Users` WHERE `Checkout` IS NULL AND `Ext` = '".$Ext."'";
			$result = mysql_query($query) or die("Web site query failed");
			$row = mysql_fetch_array($result);
			mysql_close($dbconnection);
	 		$ID = $row["ID"];
				If ($ID > 0) {
					$result = 'OK';
				}
				else {
					$result = 'KO';
				}
			}
		else {
			$result = 'OK';
		}
	endif ;
	echo $result;
}
?>