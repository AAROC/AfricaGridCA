<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include ("/var/www/CA/phpinc/varsCA.inc");
?>

<html><head>
<title><?php echo $CAname; ?> Certification Authority's home page</title><meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</head>
<body>
<?php
include ("/var/www/CA/phpinc/menuCA.inc");
?>
<p>The <b><? echo $CAname; ?> Certification Authority</b> issues one year Personal and Robot public key certificates (compliant with the X.509 standard). 
</p>

<p>

For any problem, please contact:
<dir>
e-mail: <a href="mailto:<?php echo $webmaster;?>">
<font color=blue><?php echo $webmaster;?></font></a>
</dir>

<p>

In order to inspect the <?php echo $CAname; ?> CA certificate and/or save it in your web browser (necessary to validate your personal 
certificate) click on <b><?php echo $CAname; ?> CA certificate</b> in the left part of this page. 

<p>
Please, note that Mozilla, Chrome and Internet Explorer are the only 
presently supported web browsers.  The use of any other web browsers could induce some
visualization mismatches and/or server misbehaviours and is not currently suggested.

<?php
include ("/var/www/CA/phpinc/bottom.inc");
?>
