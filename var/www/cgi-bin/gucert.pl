#!/usr/bin/perl -T

#print "Content-type: text/html\n\n";
#print "Content-type: text/plain\n\n";
$cert_root  = "/etc/pki/CA";
$index_file = "$cert_root/index.txt";
$cert_dir   = "$cert_root/newcerts";         

#$is_msie=$ENV{'HTTP_USER_AGENT'} =~ /MSIE/;  # Internet Explorer?

$var=$ENV{'HTTP_USER_AGENT'};
$var =~ s|\n|\\n|g;
$var =~ s|"|\\"|g;
$is_msie="\"${var}\"\n";

if (@ARGV && $ARGV[0]) {

  if ($is_msie =~ /MSIE/) {
    open(CERT, "<$cert_dir/$ARGV[0].pkcs7") or Err("Wrong certificate number!");
print <<"EOF";
<HTML>
<HEAD>
<TITLE>Download of the certificate</TITLE>
</HEAD>
<BODY LANGUAGE=\"VBScript\" ONLOAD=\"InstallCert\">
<SCRIPT LANGUAGE=\"VBSCRIPT\">
Sub InstallCert
  On Error Resume Next
  credentials = \"\" \& _
EOF

    while(<CERT>) {
      next if (/END/ || /BEGIN/);
      s/^/        \"/;
      s/$/\" & _/;
      print;
    }

    print <<"EOF";
  ""
Call Enroll.AcceptPKCS7(credentials)
html = \"\" \& _
  \"<H2>Download of the certificate</H2>\" \& chr(13) \& chr(10)
  If err.Number <> 0 Then 
    html = html \& _
    \"An error occurred during the download of the certificate:\" \& chr(13) \& chr(10) \& _
    \"Errore code: 0x\" \& Hex(err) \& chr(13) \& chr(10)
  else
    html = html \& _
    \"Certificate downloaded!\" \& chr(13) \& chr(10)
  End if
  document.write(html)
End sub
</SCRIPT>
</BODY>
</HTML>
EOF

  } else {     # Netscape
    open(CERT, "<$cert_dir/$ARGV[0].pem") or Err("Wrong certificate number!");
    print "Content-Type: application/x-x509-user-cert\n\n";
    $cert = join('', $_, <CERT>);
    print $cert;

  }

} else {

 Err("Certificate number non-existent!");

} 

exit(0);


sub Err {

  my($msg) = @_;
  
  print <<"EOF";
Content-type: text/html

<HTML>
<HEAD>
<TITLE>$msg</TITLE>
</HEAD>
<BODY>
<CENTER>
<FONT COLOR=\"\#ff0000\">
<H2>$msg</H2>
</FONT>
</CENTER>
</BODY>
</HTML>
EOF

  exit(0);

}

