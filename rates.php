<?php

include('config.inc.php');
include('header.php');


echo "<h1>". $Title3 ."</h1>\n" ;


$Action = '';

if(isset($_GET['Action'])){

$Action = $_GET['Action'];
If ($Action == "Delete") :
$ID=$_GET['ID'];
	if ($ID <> "") :
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database Hotel connection failed");
	mysql_select_db($dbname) or die("data base open failed");
	$query = "DELETE FROM Rates WHERE ID = " .$ID;
  	$result = mysql_query($query)
    		or die("Database Hotel deletion failed");
	mysql_close($dbconnection);
	$ID = "";
	$Action = "";
	endif ;
endif ;

}  

$Desc=''; 
$Type=''; 
$Pref=''; 
$Min='';
$Risp='';

if(isset($_REQUEST['Desc'])){
$Desc=$_REQUEST['Desc'];
}
if(isset($_REQUEST['Type'])){
$Type=$_REQUEST['Type'];
} 
if(isset($_REQUEST['Pref'])){
$Pref=$_REQUEST['Pref'];
} 
if(isset($_REQUEST['Min'])){
$Min=$_REQUEST['Min'];
} 
if(isset($_REQUEST['Risp'])){
$Risp=$_REQUEST['Risp'];
} 

if(isset($_POST['InsertButton'])) :
   // process as form post
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database Hotel connection failed");
	mysql_select_db($dbname) or die("data base open failed");
  	$query = "INSERT INTO `Rates` (`ID`, `Desc`, `Type`, `Pref`, `Min`, `Risp`) VALUES (NULL, '$Desc', '$Type', '$Pref', '$Min', '$Risp')";
  	$result = mysql_query($query)
   		or die("Database Hotel-Rates insert failed");
	mysql_close($dbconnection);
endif;




echo "<TABLE cellSpacing=0  cellPadding=0 width=900 border=0>\n" ;
echo "<TR><TD>Id</TD><TD>". $SecLab8 ."</TD><TD>". $SecLab9 ."</TD><TD>". $SecLab10 ."</TD><TD>". $SecLab11 ."</TD><TD>". $SecLab12 ."</TD><TD>Action</TD>" ;
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Rates open failed");
	$query = "SELECT * FROM Rates order by ID asc";
	$result = mysql_query($query)
    	or die("Web site query failed");
	while ($row = mysql_fetch_array($result)) {
 	echo "<TR><TD><FONT face=verdana,sans-serif>" . $row["ID"] . "</TD><TD>" . $row["Desc"]  . "</TD><TD>" .$row["Type"] ."</TD><TD>" . $row["Pref"] . "</TD><TD>" . $row["Min"] . "</TD><TD>" . $row["Risp"] . "</TD><TD><a href=\"rates.php?Action=Delete&ID=" .$row["ID"] . "\">". $SecLab3 ."</a></TD></TR>\n" ;
	}
echo "<FORM NAME=\"InsertFORM\" ACTION=\"./rates.php\" METHOD=POST>\n";
echo "<TR><TD></TD><TD><INPUT TYPE=TEXT NAME=\"Desc\" VALUE=\"$Desc\" ID=\"Desc\"></TD><TD><INPUT TYPE=TEXT NAME=\"Type\" VALUE=\"$Type\" ID=\"Type\"></TD><TD><INPUT TYPE=TEXT NAME=\"Pref\" VALUE=\"$Pref\" ID=\"Pref\"></TD><TD><INPUT TYPE=TEXT NAME=\"Min\" VALUE=\"$Min\" ID=\"Min\"></TD><TD><INPUT TYPE=TEXT NAME=\"Risp\" VALUE=\"$Risp\" ID=\"Risp\"></TD><TD><INPUT TYPE=SUBMIT NAME=\"InsertButton\" VALUE=\"". $SecLab2 ."\" ID=\"Insert\"></TD>" ;
echo "</FORM>\n";
echo "</TABLE>\n";


mysql_close($dbconnection);
?>


<?php
include('footer.php');
?>