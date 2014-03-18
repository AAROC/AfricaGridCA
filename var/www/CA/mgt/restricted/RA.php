<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML/EN">
<HTML>
<HEAD>
<TITLE>Generate a RA code</TITLE>
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


  if ($_SERVER['SSL_CLIENT_VERIFY'] != "SUCCESS") {
     echo "<center><h3>The browser not contain any valid certificate</h3><br>\n";
     echo include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }

  $DN=shell_exec("mysql -s -N -u root -p$dbpass $database -e \"select DN from users where DN='$_SERVER[SSL_CLIENT_S_DN]' and isRA='Y'\"");
  if ($DN==""){
     echo "<center><h3>You are not authorized to access to this page. Only registered RAs can access</h3><br>\n";
     echo include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }

  if (isset($_POST['submit'])) {
     $name=$_POST['name'];
     $Email=$_POST['Email'];
     $Doctype=$_POST['Doctype']; 
     $Docnum=$_POST['Docnum']; 
     $Auth=$_POST['Auth'];
     $DoI=$_POST['DoI'];
     if (empty($name) || empty($Doctype) || empty($Email) || empty($DoI) || empty($Auth) ) {
        echo "<FONT COLOR=red><center><h3>INCOMPLETE DATA</h3></font>Please, fill <b>all</b> the fields of the form</center>";
        include("/var/www/CA/phpinc/bottom.inc");
        exit;
     }
     
     $RAcode=mt_rand(10000000, 99999999);

     $connection=mysql_connect( "localhost", "root", "$dbpass") or die ( "Can't connect to Database on <b>$db_host");
     mysql_select_db ("$database", $connection) or die ( "Can't select the Database $database<br>");
     $query =  "insert into users (FLname,email,DN,Institute,doctype,docnumber,authority,date_of_issue,RAcode,isRA) values('$name','$Email','','','$Doctype','$Docnum','$Auth','$DoI','$RAcode','N')";
     $result = mysql_query ($query,$connection) or die ( "Can't submit the request $query");
     mysql_close($connection);

     mail($Email, "Authorization for $name","Dear $name,\n\nplease go to https://$_SERVER[SERVER_ADDR]/CA/mgt/restricted/ucert.php\nand insert the following RA code: $RAcode\n\n\nBest Regards, CA Manager","From: $CAname CA <$webmaster>\r\n"); 
     echo "<FONT COLOR=red><center><h3>RA code generated with success</h3></font><p>";
     include("/var/www/CA/phpinc/bottom.inc");
     exit;
  }
?>


<h3>Registration Authority</h3><hr>

<p><a href=howtoRA.php>HOWTO</a> (RA Instructions)</p>

<FORM ACTION="<?php echo $PHP_SELF?>" NAME="GetData" METHOD=POST>
<INPUT TYPE="HIDDEN" NAME="SessionId" VALUE="<?php echo time()?>">
<center>
<TABLE BORDER=0 CELLPADDING=8 bgcolor=#74B881>
<TR>
<TD ALIGN=RIGHT><b>Name and Surname:  </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="name" MAXLENGTH=40
     SIZE=40 VALUE="<?php echo $name?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>Email address:  </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="Email" MAXLENGTH=40
     SIZE=40 VALUE="<?php echo $Email?>"> </TD>
</TR>
<TD ALIGN=RIGHT><b>Document Type: </TD>
<TD>
        <SELECT name="Doctype">
        <option>&nbsp;</option>
        <option value="Identity Card">ID Card</option>
        <option value="passport">Passaport</option>
        </SELECT>
</TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>Document Number: </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="Docnum" MAXLENGTH=25
     SIZE=25 VALUE="<?php echo $Docnum?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>Authority:  </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="Auth" MAXLENGTH=40
     SIZE=40 VALUE="<?php echo $Auth?>"> </TD>
</TR>
<TR>
<TD ALIGN=RIGHT><b>Date of issue: (yyyy-mm-dd)  </TD>
<TD ALIGN=LEFT><INPUT TYPE="TEXT" NAME="DoI" MAXLENGTH=10
     SIZE=10 VALUE="<?php echo $DoI?>"> </TD>
</TR>
<TR>
</TABLE>
</td></tr>
</TABLE>
</center>
<P>
<CENTER>
<FONT COLOR=RED>
<INPUT TYPE="submit" NAME="submit" VALUE="Submit the request">
</FONT>

&nbsp; &nbsp; &nbsp;
<INPUT TYPE="reset" VALUE="Clear form">
</CENTER>
</FORM> 

<?php include("/var/www/CA/phpinc/bottom.inc"); ?>

