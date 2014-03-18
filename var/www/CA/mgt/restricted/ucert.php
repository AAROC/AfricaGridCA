<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML/EN">
<HTML>
<HEAD>
<TITLE>Request a Personal certificate</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php 
  require('/var/www/CA/phpinc/varsCA.inc');
  include ("/var/www/CA/phpinc/menuCA.inc");
  $C = "$ICC";
  $O = "$CAname";
  mt_srand((double)microtime()*1000000);

  $is_msie = stristr($_SERVER['HTTP_USER_AGENT'], "msie");

  if ($is_msie) {
     echo "
<OBJECT CLASSID=\"clsid:127698e4-e730-4e5c-a2b1-21490a70c8a1\"
        CODEBASE=\"$codebase/xenroll.dll\" 
        ID=Enroll>
</OBJECT>
";
  }
  
  if (isset($_POST['submit'])) {
     $L=$_POST['L'];
     $CN=$_POST['CN']; 
     $Email=$_POST['Email'];
     $RAcode=$_POST['RAcode'];
     $SPKAC=$_POST['SPKAC'];
     if (empty($L) || empty($CN) || empty($Email) ) {
        echo 
"<FONT COLOR=red><center><h3>INCOMPLETE DATA</h3></font>
Please, fill <b>all</b> the fields of the form</center>";
        include("/var/www/CA/phpinc/bottom.inc");
        exit;
     }

     $regx = '[òàèùìöüäëï.,:;?]';
     if (ereg($regx, $L)) {
         echo
"<FONT COLOR=red><center><h3>INSTITUTE string is not correct</h3></font>
Please, retry again";
        include("/var/www/CA/phpinc/bottom.inc");
        exit;
     }

     if (ereg($regx, $CN)) {
         echo
"<FONT COLOR=red><center><h3>Your FIRSTNAME and LASTNAME string is not correct</h3></font>
Please, retry again";
        include("/var/www/CA/phpinc/bottom.inc");
        exit;
     }

     $email_verify = ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $Email, $trashed);
     if (!$email_verify) {
         echo
"<FONT COLOR=red><center><h3>EMAIL string is not correct</h3></center></font>
Please, retype again";
        include("/var/www/CA/phpinc/bottom.inc");
        exit;
     }

     $Email = str_replace("\n", "", $Email);
     $request_file=time() . "." . $Email;
     $tmpout = "$CAdir/htdocs/CAtmp/" . $request_file;

     $exists=shell_exec("mysql -s -N -u root -p$dbpass $database -e \"select RAcode from users where email='$Email'\"");

     if ($exists!="") {
     // Netscape
      if (!$is_msie) {
        $CN = str_replace("\n", "", $CN);
        $Email = str_replace("\n", "", $Email);
        $fp = fopen("$tmpout", "w");
        if (!$fp || strlen($SPKAC) < 400) {
	   echo
"<FONT COLOR=red><h3>Your web browser did not generate the request correctly
or a key length less than 1024 bits has been selected. Please, try again and in case of further problems 
<a href='mailto:$webmaster'>
<font color=blue>send us an e-mail</font></a> including the name of the machine from where you tried to submit the
request, date and time, and the web browser you used.";
           include("/var/www/CA/phpinc/bottom.inc");
	   exit;
        } else {
	   fwrite($fp,"C = $C\nO = $O\nL = $L\nOU = Personal Certificate\nCN = $CN\nemailAddress = $Email\nSPKAC = $SPKAC\n");
           fclose($fp);
        }
      }
     // IE
      else {
       if (!$_POST['MSREQ']) {
           echo 
"<center><FONT COLOR=red><h3>Your web browser did not generate the request correctly.</h3></font>
Please, <a href='mailto:$webmaster'><font color=blue>send us an e-mail</font></a></center>";
           include("/var/www/CA/phpinc/bottom.inc");
           exit;
       } 
       else {
	   $tmpout = $tmpout . ".der";
	   $request_file = $request_file . ".der";
           $fp = fopen("$tmpout", "w");
           fwrite($fp, $_POST['MSREQ']);
           fclose($fp);
       }
      }
     $hash1 = abs(crc32(microtime()));
     mail($webmaster, "Certificate request by $CN","$request_file\n$CN\n$L\n$Email","From: $Email\r\n"); 
     mail($Email, "Email address confirmation for $CN request ", "Before to sign your request, we need a reply to this e-mail\nin order to verify your e-mail address.\n\nBest Regards, CA Manager","From: $CAname CA <$webmaster>\r\n"); 
    echo 
"<FONT COLOR=red><center><h3>Your request has successfully been submitted.</h3></font><p>
<h3>As soon as the certificate will be signed by CA manager you will be notified by e-mail with the instructions
to download your $CAname CA personal certificate.</h3></center>";
     include("/var/www/CA/phpinc/bottom.inc");
     
     exit;
   }
   else {
    echo "<center><FONT COLOR=red><h3></font>Failed. RA code or email doesn't match correctly</a></center>";
    include("/var/www/CA/phpinc/bottom.inc");
    exit;
   } 
  }

  if (stristr($HTTP_USER_AGENT, "mozilla")) {
     echo "<center><font color=red><h1>$HTTP_USER_AGENT<br>Browser not supported!</h1></font></center>";
     include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }
  if (stristr($HTTP_USER_AGENT, "msie 4")) {
     echo "<center><font color=red><h1>Use Internet Explorer above version 4!</h1></font></center>";
     include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }

  if ($is_msie) {
     echo "
<dir>
<font color=\"red\" size=+1><b>
WARNING: the procedure requires the application of the security patch you can find at<br>
<a href=\"http://www.microsoft.com/technet/security/bulletin/MS02-048.asp\">
http://www.microsoft.com/technet/security/bulletin/MS02-048.asp</a></b>
</font>
</dir>";
   }

?>


<h3>Request a <?php echo $CAname;?> personal certificate</h3><hr>
<p>
<font color=RED><blink>We support only FIREFOX, CHROME and INTERNET EXPLORER browsers</blink></font>
</P>
<p>
<b>If you did not do it already, please <A HREF="../getCA.php"><font color=blue>download the <?php echo $CAname;?> CA
certificate</font></A> first.</b>
In order to correctly generate a request it is mandatory to fill <b>all</b> fields in the form below. Please,
double check the correctness of the e-mail address that you are going to provide since <b>no verification</b> 
will be performed by the server. 
When the certificate will be signed by the <?php echo $CAname;?> CA manager you will be notified by e-mail with the instructions
to download your <?php echo $CAname;?> CA personal certificate and access the <?php echo $CAname;?> Testbed.
</p>

<FORM ACTION="<?php echo $PHP_SELF?>" NAME="GetData" METHOD=POST>
<INPUT TYPE="HIDDEN" NAME="SessionId" VALUE="<?php echo time()?>">
<center>
<TABLE BORDER=0 CELLPADDING=8 bgcolor=#74B881>
<TR>
<TD ALIGN=RIGHT><b>Institute/University/Company: </TD>
<TD ALIGN=LEFT>
<input type=text size=40 name="L" VALUE="<?php echo $L?>">
</TR>
<TR>
<TD ALIGN=RIGHT><b>First name and last name: </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="CN" MAXLENGTH=125
     SIZE=40 VALUE="<?php echo $CN?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>E-mail: </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="Email" MAXLENGTH=125
     SIZE=40 VALUE="<?php echo $Email?>"> </TD>
</TR>
<TR>
<TR>
<TD ALIGN=RIGHT><b>RA code: </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="RAcode" MAXLENGTH=8
     SIZE=10 VALUE="<?php echo $RAcode?>"> </TD>
</TR>
<TR>
<?php
 if (!$is_msie) {
    echo
"<TD ALIGN=RIGHT><b>KeySize: </TD>
<TD><KEYGEN NAME=\"SPKAC\" CHALLENGE=";
    echo mt_rand();
    echo
"> </TD></TR>";
}?>
</TABLE>
</td></tr>
</TABLE>
</center>
<P>
<CENTER>
<FONT COLOR=RED>
<INPUT TYPE="submit" NAME="submit" VALUE="Submit the request">
</FONT>

<?php
 if ($is_msie) {
  echo "
<SCRIPT LANGUAGE=\"VBScript\">
<!--
Sub submit_OnClick
  Dim TheForm
  set TheForm = Document.GetData
  szName = \"C=$C; O=$O \"                     & _
           \"; OU = Personal Certificate \"    &_
           \"; L=\" & TheForm.L.value          & _
           \"; CN=\" & TheForm.CN.value        & _
           \"; 1.2.840.113549.1.9.1=\"         & _
           TheForm.Email.value
  Enroll.HashAlgorithm = \"MD5\"
  Enroll.KeySpec = 1
  Enroll.GenKeyFlags = 3
  sz10 = Enroll.CreatePKCS10(szName,\"1.3.6.1.5.5.7.3.2\")
  if (sz10 = Empty OR theError <> 0) Then
    sz = \"Si e' verificato l'errore '\" & Hex(theError) & \". \"    & _
         chr(13) & chr(10)                                & _
         \"Richiesta non sottomessa.\"
    result = MsgBox(sz, 0, \"Richiesta Certificati\")
    Exit Sub
  else
    TheForm.MSREQ.value = sz10 
  end if
End Sub
-->
</SCRIPT>
<INPUT TYPE='hidden' NAME='MSREQ'>
";
  }

?>

&nbsp; &nbsp; &nbsp;
<INPUT TYPE="reset" VALUE="Clear form">
</CENTER>
</FORM> 

<?php include("/var/www/CA/phpinc/bottom.inc"); ?>

