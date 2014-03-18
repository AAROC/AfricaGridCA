<?php 

  require('/var/www/CA/phpinc/varsCA.inc');
  
  if ($_POST['CAformat'] == "DER") {
     $cert = `$OpenSSL x509 -outform der -in $CAcert 2>&1`;
     if (strlen($cert) > 500) {
        if (!$is_msie) {  // Netscape
           header("Content-type: application/x-x509-ca-cert\n\n");
	} else {          // IE
	   header("Content-type: application/pkix-cert\n\n");
	}
        echo $cert;
        exit;
     }
  }

?>

<HTML>
<HEAD>
<TITLE>Download the <?php echo $CAname;?> CA certificate</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>
<?php  
 include ("/var/www/CA/phpinc/menuCA.inc");

 $is_msie = stristr($_SERVER['HTTP_USER_AGENT'], "msie");
 
 if ($_POST['CAformat'] == "DER") {
    mail($webmaster,"Error in getCA.php","Error: $cert");
    echo "<center><font color=red><h3>Fatal error!</h3>";
    echo "</font><b>The CA manager has been warned about that</b></center>";
    echo include("/var/www/CA/phpinc/bottom.inc");
    exit;
 }

 if ($_POST['submit']) {
    echo "<pre>";
    #echo $CAcert;
    @include $CAcert;
    echo "</pre>";
    if ($php_errormsg) {
       mail($webmaster,"Error in getCA.php",$php_errormsg);
       echo "<center><font color=red><h3>Fatal error!<br>";
       echo "$php_errormsg</h3></font>";
       echo "<b>The CA manager has been warned about that</b></center>";
    }
    // phpinfo();
    #echo include("/var/www/CA/phpinc/bottom.inc");
    #exit;
 }
 else {
?>
<p>
This is the fingerprint of the <?echo $CAname;?> CA certificate:  
<p>
<dir>
<font size=+1>
<b><? shell_exec("openssl x509 -in $CAcert -noout -fingerprint");?></b> 
</font>
</dir>
<p>

<h3>Instructions</h3>

<?php
if (!$is_msie) {
                      // Netcape
   echo
"<p>For the automatic installation in your web browser, select the <b>DER</b> format and in the dialog form 
which will appear, select <b>all</b> the following functionalities:
<ul>
<li>network sites
<li>e-mail users
<li>software developers
</ul>

If no dialog form shows up, please 
<a href=\"mailto:$webmaster\"><font color=blue>contact the $CAname CA manager</font></a>.
</p>"; 

   } else {
                     // IE  
   echo
"<p>For the automatic installation in your web browser, select the <b>DER</b> format and save the $CAname CA certificate on
your local disk with the <b>.der</b> extension in the filename. Then, double click on the filename and, when the
installation dialog starts, choose the option 'Select automatically the archive'.</p>";
   }
?>

<FORM ACTION="<?php echo $PHP_SELF?>" METHOD=POST>
Formato:
<SELECT NAME="CAformat">
   <OPTION VALUE="DER">DER</OPTION>
   <OPTION VALUE="PEM">PEM</OPTION>
</OPTION>
</SELECT>

<CENTER>
<FONT COLOR=RED>
<INPUT TYPE="submit" NAME="submit" VALUE="Download/Visualize certificate">
</FONT>
</CENTER>
</FORM> 
</td></tr>
</TABLE>
<?php 
  }
include("/var/www/CA/phpinc/bottom.inc"); 
?>

