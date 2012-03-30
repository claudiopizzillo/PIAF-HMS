<?php
include('config.inc.php');
include('header.php');

echo "<h1>" . $Title2 . "</h1>" ;

$Delete = '';

if(isset($_GET['ID'])){

$Delete=$_GET['ID'];
if ($Delete <> "") :
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base open failed");
	$query = "DELETE FROM Rooms WHERE ID = " .$Delete;
  	$result = mysql_query($query)
    		or die("Database deletion failed");
	mysql_close($dbconnection);
	$Delete = "";
endif ;

}  



$Import = '';

if(isset($_GET['Import'])){

$Import=$_GET['Import'];
if ($Import == "true") :
	//Purge Hotel Rooms table
	echo $SecLab4. "\n<br />";
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel open failed");
	$query = "DELETE FROM Rooms";
  	$result = mysql_query($query)
    		or die("Database Hotel-Rooms deletion failed");
	mysql_close($dbconnection);


	//Load Freepbx users from Asterisk database
 	$dbconnection2 = mysql_connect($dbhost2, $dbuser2, $dbpass2)
    		or die("Database connection failed");
	mysql_select_db($dbname2) or die("data base asterisk open failed");

	$query = "SELECT * FROM `users`";
	$result = mysql_query($query)
    		or die("Web site query failed");
	mysql_close($dbconnection2);
	
	//Insert Freepbx users to Hotel Rooms table
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    			or die("Database Hotel connection failed");
	while ($row = mysql_fetch_array($result)) {
		mysql_select_db($dbname) or die("Data base Hotel open failed");
		$query = "INSERT INTO `Hotel`.`Rooms` (`ID`,`Desc`,`Ext`,`Data`) VALUES (NULL, '" .$row["name"]. "','" .$row["extension"]. "', NULL)";
  		$result2 = mysql_query($query)
    			or die("Database Hotel-Rooms insert failed");
	}
	mysql_close($dbconnection);
	$Import = "";
endif ;

} 
?>
<div id="Menu2">
<!--<a href="rooms.php?Insert=true"><?php echo $SecLab2 ?></a>-->  <a href="rooms.php?Import=true"><?php echo $SecLab5 ?></a>
</div>
<?php
echo "<TABLE cellSpacing=0 cellPadding=0 width=900 border=0>\n" ;
echo "<TR><TD>Id</TD><TD>" . $SecLab7 . "</TD><TD>" . $SecLab6 . "</TD><TD>" . $SecLab13 . "</TD><TD>" . $SecLab1 . "</TD>" ;
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Rooms open failed");
	$query = "SELECT * FROM Rooms order by ID asc";
	$result = mysql_query($query)
    	or die("Web site query failed");
	while ($row = mysql_fetch_array($result)) {
 	echo "<TR><TD><FONT face=verdana,sans-serif>" . $row["ID"] . "</TD><TD>" . $row["Desc"]  . "</TD><TD>" .$row["Ext"] ."</TD><TD>" . $row["Data"] . "</TD><TD><a href=\"rooms.php?ID=" .$row["ID"] . "\">".$SecLab3."</a></TD></TR>\n" ;
	}

echo "</TABLE>";

mysql_close($dbconnection);
?>

<?php
include('footer.php');

?>

