<?php

include('config.inc.php');
include('header.php');

if(isset($_POST['DELETE'])) :
   // process as form post
	$WakeUpTmp = explode("-", $_POST['DELETE']);
	$filename = "/var/spool/asterisk/outgoing/".$WakeUpTmp[0].".ext.".$WakeUpTmp[1].".call";
	if (file_exists($filename)) {
		unlink($filename);
}
endif;

if(isset($_POST['INSERT'])) :
   // process as form post

       $HH=$_POST['HH'];
       $MM=$_POST['MM'];
       $Ext=$_POST['RoomsComboBox'];
	$ourFileName = $HH.$MM.".ext.".$Ext.".call";
	$parm_call_dir = '/var/spool/asterisk/outgoing/';
       $callfile = $parm_call_dir.$ourFileName;

	//Check if wake-up exists for this ext and delete it
	//$dir1 = "/var/spool/asterisk/outgoing";
	//$files = glob($dir1."/*.ext.".$Ext.".call");
		//foreach($files as $file) unlink($file);

       $wtime = $HH.$MM;
	$w = getdate();
	$w['hours']   = substr( $wtime, 0, 2 );
	$w['minutes'] = substr( $wtime, 2, 2 );
	$time_wakeup = mktime( substr( $wtime, 0, 2 ), substr( $wtime, 2, 2 ), 0, $w['mon'], $w['mday'], $w['year'] );
	$time_now = time( );
	if ( $time_wakeup <= $time_now )
		$time_wakeup += 86400; // Add One Day on
	//touch($callfile, $time_wakeup, $time_wakeup );

       $wuc = fopen($callfile, 'w');
	$stringData = "channel: Local/".$Ext."@from-internal\n";
       fputs($wuc, $stringData);
	$stringData = "maxretries: 3\n";
       fputs($wuc, $stringData);
	$stringData = "retrytime: 60\n";
       fputs($wuc, $stringData);
	$stringData = "waittime: 60\n";
       fputs($wuc, $stringData);
	$stringData = "callerid: Wake Up Calls <*68>\n";
       fputs($wuc, $stringData);
	$stringData = "application: AGI\n";
       fputs($wuc, $stringData);
	$stringData = "data: wakeconfirm.php\n";
       fputs($wuc, $stringData);
       fclose($wuc);

	touch($callfile, $time_wakeup, $time_wakeup );
endif;

	function CheckWakeUp($file) {
		$myresult = '';
		$file =basename($file);
			$WakeUpTmp = explode(".", $file);
			$Hour = $WakeUpTmp[0];
			$Ext = $WakeUpTmp[2];
			$myresult = $WakeUpTmp[0];
		return $myresult;
   	}


echo "<h1>".$Title7."</h1>" ;
echo "<FORM NAME=\"InsertFORM\" ACTION=\"./wakeup.php\" METHOD=POST>\n";
echo "<CENTER><TABLE cellSpacing=0 cellPadding=0 width=500 border=0>\n" ;
echo "<TR><TD align=\"center\">".$SecLab30."</TD><TD>".$SecLab7." ";
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Rooms open failed");

  	$query = "SELECT `Desc`, `Ext` FROM `Users` WHERE Checkout IS NULL ORDER BY `Room` ASC" ;
 	$result = mysql_query($query) or die ("Couldn't execute SQL query on Hotel-Users table.") ;
	mysql_close($dbconnection);
  	echo " <SELECT ID=\"RoomsComboBox\" NAME=\"RoomsComboBox\">";
  	while ($row = mysql_fetch_array($result))  {
   	echo "<OPTION VALUE=\"$row[1]\">" . $row[0] . "</OPTION>";
  	}
  	echo "</SELECT>";
echo "</TD><TD>HH:MM <INPUT TYPE=\"TEXTBOX\" NAME=\"HH\" SIZE=\"2\" MAXLENGHT=\"2\"/>:<INPUT TYPE=\"TEXTBOX\" NAME=\"MM\" SIZE=\"2\" MAXLENGHT=\"2\"/></TD>";
echo "<TD><INPUT TYPE=\"SUBMIT\" NAME=\"INSERT\" VALUE=\"INSERT\"></TD></TR>";
echo "</TABLE></CENTER><BR />";
echo "</FORM>\n";

echo "<FORM NAME=\"UpdateFORM\" ACTION=\"./wakeup.php\" METHOD=POST>\n";
echo "<TABLE cellSpacing=0 cellPadding=0 width=900 border=0 >\n" ;
echo "<TR><TD>Id</TD><TD>".$SecLab24."</TD><TD>".$SecLab7."</TD><TD>".$SecLab6."</TD><TD>".$SecLab14."</TD><TD>".$SecLab3."</TD></TR>\n" ;
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Users open failed");
	$query = "SELECT * FROM Users WHERE Checkout IS NULL ORDER BY ID ASC";
	$result = mysql_query($query)
    	or die("Web site query failed");

	$count = 0;
	while ($row = mysql_fetch_array($result)) {
	$count++;
	$dir1 = "/var/spool/asterisk/outgoing";
	$files = glob($dir1."/*.ext.".$row["Ext"].".call");
       foreach($files as $file) {
		$myresult = CheckWakeUp($file);
		If ($myresult <> '') {
			$h = substr($myresult,0,2);
			$m = substr($myresult,2,3);
		 		echo "<TR><TD><FONT face=verdana,sans-serif>" . $row["ID"] . "</TD><TD>" . $h .":" . $m . "</TD><TD>" . $row["Desc"]  . "</TD><TD>" .$row["Ext"] ."</TD><TD>".$row["Name"]."</TD><TD><INPUT NAME=\"DELETE\" TYPE=\"SUBMIT\" VALUE=\"". $myresult . "-" . $row["Ext"] ."\"></TD>\n";
			}
		}
	}
echo "</TABLE>\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"num_rows\" VALUE=\"" .$count. "\">\n" ;
echo "</FORM>\n";


mysql_close($dbconnection);
?>

<?php
include('footer.php');
?>