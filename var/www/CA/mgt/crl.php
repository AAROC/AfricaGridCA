<?php 

  require('/var/www/CA/phpinc/varsCA.inc');

  $scarMsg = "Import into the web browser";

  $menu=$_POST['menu'];
  
  
  function errMsg($descr, $msg) {

     global $webmaster;

     mail($webmaster,$descr,$msg);
     echo "<center><font color=red><h3>Fatal error!<br>";
     echo "$msg</h3></font>";
     echo "<b>The $CAname CA manager has been warned about that</b></center>";
  }
 
  if ($menu == "imp") {
     header("Content-Type: application/x-pkcs7-crl\n\n");
     include("$CRLlist");
     exit;
  }

  if ($menu == "sca") {
     header("Content-Type: application/octet-stream");
     header("Content-Disposition: attachment;filename=crl.der\n\n" );
     include("$CRLlist");
     exit;
  }

?>

<HTML>
<HEAD>
<TITLE>Certificate Revocation List</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php 

   include("/var/www/CA/phpinc/menuCA.inc");

   echo "<h3>Certificate Revocation List (CRL)<br>\n";
   $date = `$OpenSSL crl -lastupdate -inform der -in $CRLlist 2>&1`;
   echo "<font color=red size=-1>" . strtok($date, "\n") . "</font></h3><p><hr><p>\n";

   if ($menu == "lst") {
      echo "<pre>\n";
      passthru("$OpenSSL crl -text -inform der -in $CRLlist 2>&1", $ret);      
      echo "</pre>\n";

   }  
   else {
     $is_msie = stristr($HTTP_USER_AGENT, "msie 5");
     echo "<FORM ACTION='$PHP_SELF' METHOD=POST>";
     if (!$is_msie) {
echo "<TABLE BORDER=0 CELLPADDING=8 bgcolor=$bgcolor align=center>
 <tr>
  <td align=right><INPUT TYPE='RADIO' NAME='menu' VALUE='imp'></td><td><b> Import the CRL into the web browser</td>
 </tr>
 <tr>
  <td align=right><INPUT TYPE='RADIO' NAME='menu' VALUE='sca'></td><td><b> Download the CRL</td>
 </tr>
 <tr>
  <td align=right><INPUT TYPE='RADIO' NAME='menu' VALUE='lst'></td><td><b> Visualize the CRL</td>
 </tr>
</table>
<br>
<center><INPUT TYPE='submit' NAME='submit' VALUE='Submit request'></center>
</FORM>";
     }
   }
  include("/var/www/CA/phpinc/bottom.inc"); 
?>

