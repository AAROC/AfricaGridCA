<?php 

  require('/var/www/CA/phpinc/varsCA.inc');

  $scarMsg = "Download on the web browser";
  $findMsg = "Check";

  function errMsg($descr, $msg) {

   global $webmaster;

   mail($webmaster,$descr,$msg);
       echo "<center><font color=red><h3>Fatal error!<br>";
       echo "$msg</h3></font>";
       echo "<b>The CA manager has been warned about that</b></center>";
  }
 
  $submit=$_POST['submit'];
  $Email=$_POST['Email'];
  $CN=$_POST['CN'];
  $rev=$_POST['rev'];
  $sca=$_POST['sca'];

  if ($submit == $scarMsg) {
     $crt = $_POST['crt'];
     $cert = file($crt);

     header("Content-Type: application/x-x509-email-cert\n\n");
      
     $out = 0;
     while (list($line_num,$line) = each($cert)) {
       if (!$out) {
          if (!ereg("-BEGIN CERTIFICATE-", $line)) {
             continue;
          } else {
            echo $line;
            $out = 1;
            continue;
          }
       }
       echo $line;
     }
     exit;
  }

?>

<HTML>
<HEAD>
<TITLE>Check a personal certificate</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php include("/var/www/CA/phpinc/menuCA.inc"); ?>

<td valign=top>
<h3>Check a personal certificate</h3><hr>
<p>

<?php 

 $is_msie = stristr($HTTP_USER_AGENT, "msie 5");

 if ($submit == "$findMsg") {
    $index = @file($INDEX);
    if ($php_errormsg) {
       errMsg("Errore file indice", $php_errormsg);
       echo include("/var/www/CA/phpinc/bottom.inc");
       exit;
    }

    $n = 0;
    echo "<FORM ACTION='$PHP_SELF' METHOD=POST>\n";
    echo "<table border=0 cellspacing=2 cellpadding=5 width=100%>\n";
  
    while (list($line_num,$line) = each($index)) {
       $fields = split("\t", $line);
       if (!$rev && $fields[0] == "R") { continue; }
       if (!$sca && $fields[0] == "E") { continue; }
       if ($Email && !eregi("emailAddress=.*$Email", $fields[5])) { continue; }
       if ($CN && !eregi("CN=.*$CN", $fields[5])) { continue; }
       $fields[5] = str_replace("\n", "", $fields[5]);
       $fields[5] = str_replace("/C=", "C=", $fields[5]);
       $fields[5] = str_replace(" ", "&nbsp;", $fields[5]);
       $fields[5] = str_replace("/", " ", $fields[5]);
       $fields[5] = str_replace("Email", "EMAIL", $fields[5]);
       if ($fields[0] == "R" || $fields[0] == "E") { 
          $fgcol = "#a0a0a0";
          if ($fields[0] == "R") { $fields[1] = $fields[2]; }
       } else {
          $fgcol = "black";
       }
       if (!$n) {
          echo "<tr>\n <td bgcolor=\"$bgcolor\"><font size=-1>Number</td>\n";
          echo " <td bgcolor=\"$bgcolor\"><font size=-1>Status</td>\n";
          echo " <td bgcolor=\"$bgcolor\" align=center><font size=-1>?</td>\n";
          echo " <td bgcolor=\"$bgcolor\" width=75%><font size=-1>Subject</td>\n";
          echo " <td bgcolor=\"$bgcolor\"><font size=-1>Expiration</td>\n</tr>";
       }
       echo "<tr>\n <td valign=center><font size=-1 color='$fgcol'>0x$fields[3]</td>\n";
       echo " <td valign=center><font size=-1 color='$fgcol'>$fields[0]</td>\n";
       echo " <td><font size=-1><INPUT TYPE=\"radio\" NAME=\"dett\" VALUE=\"$fields[3]\"></td>\n";
       echo " <td width=75%><font size=-1 color='$fgcol'>$fields[5]</td>\n";
       echo " <td><font size=-1 color='$fgcol'>".
            substr($fields[1],4,2) . "/" . substr($fields[1],2,2) . "/" . substr($fields[1],0,2) .
            "</td>\n</tr>";
       $n++;
    } 

    echo "</table>\n<p>";

    if ($n) {
       echo "<hr><p>";
    }
    echo "$n certificates found<p>\n";
    if ($n) {
       echo "<center><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Details\"></center>";
    }
    echo "</form>\n";
    include("/var/www/CA/phpinc/bottom.inc");
    exit;	

 }

 if ($submit == "Details") {
    $dett = $_POST['dett'];	
    if (!$dett) {
       echo "No certificate specified";
       include("/var/www/CA/phpinc/bottom.inc");
       exit;	
    }

    $ucert = 0;
    if (file_exists("$usrCertDir/$dett.pem")) {
       $file = "$usrCertDir/$dett.pem";
       $ucert = 1;
    } elseif (file_exists("$srvCertDir/$dett.pem")) {
         $file = "$srvCertDir/$dett.pem";
    } elseif (file_exists("$objCertDir/$dett.pem")) {
         $file = "$objCertDir/$dett.pem";
    } elseif (file_exists("$revCertDir/$dett.pem")) {
         $file = "$revCertDir/$dett.pem";
    } else {
       errMsg("Index file not consistent", "Index file not consistent");
       echo include("/var/www/CA/phpinc/bottom.inc");
       exit;
    }
       
    echo "<table border=0 cellpadding=5><tr><td bgcolor='$bgcolor'>Certificate (format: text and PEM)</td></tr>\n";
    echo "<tr><td><pre>"; 
    include("$file"); 
    echo "</pre></td></tr>\n";
    $pem = `$OpenSSL x509 -fingerprint -in $file 2>&1`;
    echo "<tr><td bgcolor='$bgcolor'>Fingerprint</td></tr>";
    echo "<tr><td><pre>" . strtok($pem, "\n") . "</pre></td></tr>";
    echo "</table>";
   
    if (!$is_msie && $ucert) {
       echo "<FORM ACTION='$PHP_SELF' METHOD=POST>\n";
       echo "<INPUT NAME='crt' TYPE='HIDDEN' VALUE=\"$file\">";
       echo "<center><INPUT TYPE='submit' NAME='submit' VALUE='$scarMsg'></center></form>";
    }
    echo include("/var/www/CA/phpinc/bottom.inc");
    exit;
 }


?>

The values provided in the form below will be used in logical <b>AND</b> during the check.<p>

<FORM ACTION="<?php echo $PHP_SELF?>" METHOD=POST>
<center>
<TABLE BORDER=0 CELLPADDING=3 bgcolor=<?php echo $bgcolor;?>>
<TR>
<TD ALIGN=RIGHT><b>First name and last name: </TD>
<TD><INPUT TYPE="TEXT" NAME="CN" MAXLENGTH=125 SIZE=40 VALUE="<?php echo $CN?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>E-mail: </TD>
<TD><INPUT TYPE="TEXT" NAME="E-mail" MAXLENGTH=125 SIZE=40 VALUE="<?php echo $Email?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><INPUT TYPE = "checkbox" NAME="rev" VALUE="rev"></td><td><b> as well as revocated certificates</td>
</TR>
<TR>
<TD ALIGN=RIGHT><INPUT TYPE = "checkbox" NAME="sca" VALUE="sca"></td><td><b> as well as expired certificates</td>
</TR>
</table>
<br>
<INPUT TYPE="submit" NAME="submit" VALUE="<?php echo $findMsg?>">
<INPUT TYPE="reset" VALUE="Clear form">
</TR>
</table>
</CENTER>
</FORM> 

<?php include("/var/www/CA/phpinc/bottom.inc"); ?>

