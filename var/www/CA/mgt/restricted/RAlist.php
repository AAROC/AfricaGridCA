<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML/EN">
<HTML>
<HEAD>
<TITLE>List of accredited RAs</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php 
  require('/var/www/CA/phpinc/varsCA.inc');
  include ("/var/www/CA/phpinc/menuCA.inc");

echo"
<center>
<h3>Accredited Registration Authorities</h3>

<TABLE BORDER=1 CELLPADDING=8>
<tr>
 <th>Institute</th>
 <th>Registration Authority name</th>
 <th>e-mail address</th>
</tr>";

 $connection=mysql_connect( "localhost", "root", "$dbpass") or die ( "Can't connect to Database on <b>$db_host");
 mysql_select_db ("$database", $connection) or die ( "Can't select the Database $database<br>");
 $query =  "Select Institute,FLname,email from users where isRA='Y'";
 $result = mysql_query ($query,$connection) or die ( "Can't submit the request $query");

 while($rows = mysql_fetch_row($result)) {
  echo '<tr>';
  foreach($rows as $key=>$value) {
    echo "<td>$value</td>";
  }
  echo '</tr>';
 }
 echo '</table><br />';


 mysql_close($connection);

?>
</center>

<?php include("/var/www/CA/phpinc/bottom.inc"); ?>

