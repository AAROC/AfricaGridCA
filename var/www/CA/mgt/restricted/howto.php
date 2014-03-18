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
<h4>Personal certificates</h4>

In order to request a new personal certificate, you first need to be identified by a competent <b>Registration Authority (RA)</b>. Use the <a href="RAlist.php">RA list</a> to find the most convenient for you. If the identification is successful, the RA send you via mail a code : use it to fill the Personal Certificate Request form that you can find on the link on the left menu; usage of <b>Firefox</b> is warmly recommended for this task.
If you want to renew your still valid certificate, you should select the Personal certificate renewal link on the left menu. Your certificate will be renewed after the approval of the Registration Authority.

<h4>Robot certificates</h4>
In order to request a new robot certificate, you first need to be identified by a competent <b>Registration Authority (RA)</b>. Use the <a href="RAlist.php">RA list</a> to find the most convenient for you. If the identification is successful, the RA send you via mail a code : use it to fill the Personal Certificate Request form that you can find on the link on the left menu; usage of <b>Firefox</b> is warmly recommended for this task.

<h4> Host certificates</h4>
Host name must be registered and correctly resolved by a public DNS. The request for a new host certificate, as well as the renewal request for an existing one, must be sent, via email to a competent Registration Authority. Find in the <a href="RAlist.php">RA list</a> the one most convenient for you. <br>
The mail message must be signed with a personal certificate. If you don't know how to sign your email messages, have a look here <a href="http://www.signfiles.com/manuals/DigitalSignatureThunderbird.pdf">Thunderbird</a>, or here for <a href="http://www.signfiles.com/manuals/DigitalSignatureEncryptionOutlook.pdf">Outlook</a>. If you use another mail client, you can check out its documentation to see how it digitally signs mail messages.<br> 
The request generation is done command line via the openssl command: it is warmly suggested the usage of <a href="resources/host.conf">this file</a>, in order to reduce the number of values requested interactively by the openssl command. <b>NB: The Department Name must be the same as the "L" value in the personal certificate of the requestor</b>
<br>
Find below a complete example  

<div style="background:#d0d0d0; margin:15px; padding:6px; border:2px solid black">
<pre>
> ls
host.conf

> openssl req -new -nodes -out hostreq.pem -keyout hostkey.pem -config host.conf
Generating a 1024 bit RSA private key
...........................++++++
...........++++++
writing new private key to 'hostkey.pem'
-----
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Nation []:IT
Organization []:GILDA
Certificate file []:Host
Department Name (i.e. Cape Town) []:Catania
Server Fully Qualified Domain [ ]:hermes.ct.infn.it
Server Manager Email [ ]:name.surname@ct.infn.it

> ls
host.conf	hostreq.pem	hostkey.pem

</pre>
</div>
The resulting <i>hostreq.pem</i> is your certificate request : this file, and <b>only this</b>, must be sent to the RA, as attachment in a signed message, for the validation. The other file generated, <i>hostkey.pem</i>, is the private key of your host certificate, and must be kept safely.   

<?php
include("/var/www/CA/phpinc/bottom.inc");
?>
