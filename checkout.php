<?php

include('config.inc.php');
include('header.php');


echo "<h1>".$Title5."</h1>\n" ;

if(isset($_POST['SaveButton'])) :
   // process as form post

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


	function DeleteVM($ext) {
		$dir1 = "/var/spool/asterisk/voicemail/default/".$ext."/INBOX";
		$dir2 = "/var/spool/asterisk/voicemail/default/".$ext."/Old";
		$dir3 = "/var/spool/asterisk/voicemail/default/".$ext."/tmp";
		$dir4 = "/var/spool/asterisk/voicemail/default/".$ext."/Urgent";

		$files = glob($dir1."/*.txt");
			foreach($files as $file) unlink($file);
		$files = glob($dir1."/*.wav");
			foreach($files as $file) unlink($file); 
		$files = glob($dir1."/*.WAV");
			foreach($files as $file) unlink($file); 

		$files = glob($dir2."/*.txt");
			foreach($files as $file) unlink($file); 
		$files = glob($dir2."/*.wav");
			foreach($files as $file) unlink($file); 
		$files = glob($dir2."/*.WAV");
			foreach($files as $file) unlink($file); 

		$files = glob($dir3."/*.txt");
			foreach($files as $file) unlink($file); 
		$files = glob($dir3."/*.wav");
			foreach($files as $file) unlink($file); 
		$files = glob($dir3."/*.WAV");
			foreach($files as $file) unlink($file); 

		$files = glob($dir4."/*.txt");
			foreach($files as $file) unlink($file); 
		$files = glob($dir4."/*.wav");
			foreach($files as $file) unlink($file); 
		$files = glob($dir4."/*.WAV");
			foreach($files as $file) unlink($file); 

   	}

	function DeleteWU($ext) {
		$dir1 = "/var/spool/asterisk/outgoing";
		$files = glob($dir1."/*.ext.".$ext.".call");
			foreach($files as $file) unlink($file);

   	}




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


		for ($i=1; $i<=$_POST['num_rows']; $i++) {
				//UPdate checkout date
			 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    					or die("Database Hotel connection failed");
				if (isset($_POST["cb". $i])) {

				$Room = $_POST["ID". $i] ;

				mysql_select_db($dbname) or die("data base Hotel-Users update failed");
  				$query = "UPDATE `Users` SET Checkout = NOW() WHERE ID = ".$Room;
  				$result = mysql_query($query)
   					or die("Database Hotel-Users update failed");
				//mysql_close($dbconnection);

				//load user data for loop
				//mysql_select_db($dbname) or die("data base Hotel-Users select failed");
  				$query = "SELECT * FROM Users WHERE `ID` = ".$Room;
  				$result = mysql_query($query) or die("Database Hotel-Users select failed");
				mysql_close($dbconnection);
				$row = mysql_fetch_array($result);

				$Ext = '';
				$Checkin = '';
				$Checkout = '';

				$Ext = $row["Ext"];
				$Checkin = $row["Checkin"];
				$Checkout = $row["Checkout"];

				// Delete Voicemail
				DeleteVM($Ext);

				// Delete Wake-up
				DeleteWU($Ext);

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


				//Load Asteriskcdrdb data from database
 				$dbconnection3 = mysql_connect($dbhost3, $dbuser3, $dbpass3) or die("Database connection failed");
				mysql_select_db($dbname3) or die("data base asteriskcdrdb open failed");
				$query = "SELECT calldate, dst, billsec FROM `cdr` WHERE dstchannel IS NOT NULL AND (lastapp = 'Dial' OR lastapp = 'ResetCDR') AND dcontext = 'from-internal' AND calldate BETWEEN '" . $Checkin . "' AND '" . $Checkout ."' AND channel LIKE '%/" . $Ext ."-%'";
				$result = mysql_query($query) or die("Web site query load cdr failed");
				mysql_close($dbconnection3);

				//Calculate total looping cdr database
				$total = 0;
				while ($row = mysql_fetch_array($result)) {
					If (in_array($row["dst"], $extensions)) {
						// Do nothing is a local extension
						}
						Else {
 							$total = $total + Coc($row["dst"], $row["billsec"], $data);
						}
				}
				// Update user data with total
			 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die("Database Hotel connection failed");
				mysql_select_db($dbname) or die("data base Hotel-Users update failed");
  				$query = "UPDATE `Users` SET Total = '". $total. "' WHERE ID = ".$Room;
  				$result = mysql_query($query)
   					or die("Database Hotel-Users update failed");
				mysql_close($dbconnection);				}
		}
endif;

echo "<FORM NAME=\"UpdateFORM\" ACTION=\"./checkout.php\" METHOD=POST>\n";

echo "<TABLE cellSpacing=0  cellPadding=0 width=900 border=0>\n" ;
echo "<TR><TD>Id</TD><TD>".$SecLab7."</TD><TD>".$SecLab6."</TD><TD>".$SecLab14."</TD><TD>".$SecLab17."</TD></TR>\n" ;
 	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass)
    		or die("Database connection failed");
	mysql_select_db($dbname) or die("data base Hotel-Rates open failed");
	$query = "SELECT * FROM Users WHERE Checkout IS NULL ORDER BY ID ASC";
	$result = mysql_query($query)
    	or die("Web site query failed");

	$count = 0;
	while ($row = mysql_fetch_array($result)) {
	$count++;
 	echo "<TR><TD><FONT face=verdana,sans-serif>" . $row["ID"] . "</TD><TD>" . $row["Desc"]  . "</TD><TD>" .$row["Ext"] ."</TD><TD>".$row["Name"]."</TD><TD><input type=\"checkbox\" name=\"cb".$count."\" id=\"cb" .$count. "\" value=\"" .$row["Ext"] . "\"><INPUT TYPE=\"HIDDEN\" name=\"ID".$count."\" id=\"ID".$count."\" VALUE=\"" . $row["ID"]."\"></TD>\n";
	}
echo "</TABLE>\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"num_rows\" VALUE=\"" .$count. "\">\n" ;
echo "<BR><BR><INPUT TYPE=SUBMIT NAME=\"SaveButton\" VALUE=\"".$SecLab16."\" ID=\"Save\">\n" ;
echo "</FORM>\n";


mysql_close($dbconnection);
?>


<?php
include('footer.php');
?>