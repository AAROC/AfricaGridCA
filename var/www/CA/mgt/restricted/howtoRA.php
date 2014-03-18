<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include ("/var/www/CA/phpinc/varsCA.inc");
?>

<HTML>
<HEAD>
<TITLE>How to - user documentation</TITLE>
<meta name="generator" content="HAPedit 3.0">
<style type="text/css">
@import url("../../layout.css");
a#viewcss{color: #00f;font-weight: bold}
</style>
</HEAD>

<?php

   include("/var/www/CA/phpinc/menuCA.inc");

?>
Follows below the recommended procedures for Registration Authorities about user identification  
<h4>Personal certificates</h4>

The user requesting a certificate must be identified personally, via an ID card or a passport. The identification must happen <b>before</b> the user applies for his personal certificate. Once the user is identified, the RA will use <a href="RA.php"> this form </a> to register document type, number, release date and issuer. With the submission of this form, the RA declares that has identified successfully the certificate applicant. Once the form is submitted, a random RA code is sent to the certificate applicant, which will use it to complete the application. 

<h4>Robot certificates</h4>
The user requesting a certificate robot must be identified personally, via an ID card or a passport. The identification must happen <b>before</b> the user applies for his personal certificate. Once the user is identified, the RA will use <a href="RA.php"> this form </a> to register document type, number, release date and issuer. With the submission of this form, the RA declares that has identified successfully the certificate applicant. Once the form is submitted, a random RA code is sent to the certificate applicant, which will use it to complete the application. 

<h4> Host certificates</h4>
The request for host certificates are sent to RA, who will check: 
<ul>
<li>The request email is signed with a valid <?php echo $CAname; ?> Certification Authority</b> personal certificate</li> 
<li>Host name is registered and correctly resolved by a public DNS.</li> 
</ul>
If these requirements are satisfied, the RA will send (as attachment in a signed email) the request file to the CA, who will provide to sign the request and inform the applicant. 

<?php include("/var/www/CA/phpinc/bottom.inc"); ?>
