<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<HTML>
<HEAD>
<TITLE>Renew your <?php echo $CAname;?> Personal certificate</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php 
#phpinfo();
#exit;
  require('/var/www/CA/phpinc/varsCA.inc');
  $C = "$ICC";
  $O = "$CAname";
  #$submit = $_POST['submit'];
  $submit = $_GET['submit'];
  #$SPKAC = $_POST['SPKAC'];
  $SPKAC = $_GET['SPKAC'];
  
  mt_srand((double)microtime()*1000000);

  include("/var/www/CA/phpinc/menuCA.inc");

  echo "<h3>Renew your <?php echo $CAname;?> Personal certificate</h3><hr>";

  $is_msie = stristr($_SERVER['HTTP_USER_AGENT'], "msie");

  if ($is_msie) {
     echo "
<OBJECT CLASSID=\"clsid:127698e4-e730-4e5c-a2b1-21490a70c8a1\"
        CODEBASE=\"$codebase/xenroll.dll\" 
        ID=Enroll>
</OBJECT>
";
  }

  //phpinfo();

  if ($_SERVER['SSL_CLIENT_VERIFY'] != "SUCCESS") {
     echo "<center><h3>The browser not contain any valid certificate</h3><br>\n";
     echo include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }

 $emailAddress=exec("openssl x509 -in $usrCertDir/$_SERVER[SSL_CLIENT_M_SERIAL].pem -noout -email");
 if ($submit) {
  $request_file=time() . "." . $emailAddress;
  $tmpout = "$CAdir/htdocs/CAtmp/" . $request_file;

     //           Netscape
     if (!$is_msie) {
	$fp = fopen("$tmpout", "w");
        if (!$fp || strlen($SPKAC) < 400) {
           echo "
<center><FONT COLOR=blue><h3>Your browser haven't generated the request correctly
<br>(or it was been choosen a lenght of key less than 1024)</h3></font>
Please repeat and, in case the same error still show/work, contact <a href='mailto:$webmaster'>
<font color=blue>to alert</font></a> the name of the node from where the request has been submitted, date, hour and browser used.</center>";
           echo include("/var/www/CA/phpinc/bottom.inc");
	   if ($fp) {
	      fwrite($fp,"L=$_SERVER[SSL_CLIENT_S_DN_L]\nCN=$_SERVER[SSL_CLIENT_S_DN_CN]\nSPKAC = $SPKAC\n");
              fclose($fp);
	   }
	   exit;
	} else {
           fwrite($fp, "Serial=$_SERVER[SSL_CLIENT_M_SERIAL]\n" .
                       "C=$_SERVER[SSL_CLIENT_S_DN_C]\n" .
                       "O=$_SERVER[SSL_CLIENT_S_DN_O]\n" .
                       "L=$_SERVER[SSL_CLIENT_S_DN_L]\n" .
                       "OU=Personal Certificate\n" .
                       "CN=$_SERVER[SSL_CLIENT_S_DN_CN]\n" .
                       "emailAddress=$emailAddress\n" .
                       "SPKAC=$SPKAC\n");
           fclose($fp);
	}
     } else {

        //          IE

        if (!$_GET['MSREQ']) {
#echo $_GET['MSREQ'];
        #if (!$_POST['MSREQ']) {
           echo "
<center><FONT COLOR=blue><h3>Your browser haven't generated the request correctly<br>(or it was been choosen a lenght of key less than 1024)</h3></font>Please repeat and, in case the same error still show/work, contact <a href='mailto:$webmaster'><font color=blue>to alert</font></a> the name of the node from where the request has been submitted, date, hour and browser used.</center>";
           echo include("/var/www/CA/phpinc/bottom.inc");
           exit;
        } else {
	   $tmpout = $tmpout . ".der";
           $request_file = $request_file . ".der";
           $fp = fopen("$tmpout", "w");
           fwrite($fp, $_GET['MSREQ']);
           #fwrite($fp, $_POST['MSREQ']);
           fclose($fp);
        }
     }
     mail($webmaster, "Richiesta rinnovo certificato di $_SERVER[SSL_CLIENT_S_DN_CN]", 
          "$request_file
Richiedente: $_SERVER[SSL_CLIENT_S_DN_CN] <$emailAddress>","From: $emailAddress\r\n");
     echo "
<FONT COLOR=red><center><h3>The request has been submitted to CA to agreeation.</h3></center></font><p>
<h3>When CA Manager will sign your request, you will receive an e-mail message with the instructions to download it.</h3></center>";
     echo include("/var/www/CA/phpinc/bottom.inc");

     exit;

  }
echo"
<center>
<TABLE BORDER=0 CELLPADDING=8 bgcolor=$bgcolor>
 <tr>
  <td align=center colspan=2><h3><b><font color=#AD0000>These are the features of your actual certificate:</h3></td>
 </tr>
 <tr>
  <td align=right><b>Serial number:</td><td><b><font color=blue>$_SERVER[SSL_CLIENT_M_SERIAL]</td>
 </tr>
 <tr>
  <td align=right valign=top><b>Signed to:</td><td><b><font color=blue>$_SERVER[SSL_CLIENT_S_DN_CN]</td>
 </tr>
 <tr>
  <td align=right><b>E-mail:</td><td><b><font color=blue>$emailAddress</td>
 </tr>
 <tr>
  <td align=right><b>Expire on:</td><td><b><font color=blue>$_SERVER[SSL_CLIENT_V_END]</td>
 </tr>
<FORM ACTION=\"$PHP_SELF\" NAME=\"GetData\" METHOD=GET>
";

 if (!$is_msie) {
    echo
"<TD ALIGN=RIGHT><b>KeySize: </TD>
<TD><KEYGEN NAME=\"SPKAC\" CHALLENGE=";
    echo mt_rand();
    echo "> </TD></TR>
</TABLE>
</td></tr>
</TABLE>
</center>
<P>
<CENTER>
<FONT COLOR=RED>
<INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Submit the request\">
</FONT>";
}
 else  {
  echo "
<P><CENTER><INPUT TYPE='submit' NAME='submit' VALUE='Submit the request'>
<SCRIPT LANGUAGE=\"VBScript\">
Sub submit_OnClick
  Dim TheForm
  set TheForm = Document.GetData
  szName = \"C=$C; O=$O; \"                  & _
           \"OU=Personal Certificate; \"     & _
           \"L=$_SERVER[SSL_CLIENT_S_DN_L];   \"      & _
           \"CN=$_SERVER[SSL_CLIENT_S_DN_CN]; \"      & _
           \" 1.2.840.113549.1.9.1=\"        &_
           \"$emailAddress \"
  Enroll.HashAlgorithm = \"MD5\"
  Enroll.KeySpec = 1
  Enroll.GenKeyFlags = 3
  sz10 = Enroll.CreatePKCS10(szName,\"1.3.6.1.5.5.7.3.2\")
  if (sz10 = Empty OR theError <> 0) Then
    sz = \"Si è verificato l'errore '\" & Hex(theError) & \". \"    & _
         chr(13) & chr(10)                                & _
         \"Richiesta non sottomessa.\"
    result = MsgBox(sz, 0, \"Richiesta Certificati\")
    Exit Sub
  else
    TheForm.MSREQ.value = sz10
  end if
End Sub
</SCRIPT>
<INPUT TYPE='hidden' NAME='MSREQ'>
";
}
echo "</td></tr></table>";
include("/var/www/CA/phpinc/bottom.inc"); 
?>
