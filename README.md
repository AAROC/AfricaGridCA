# AfricaGrid CA
Originally taken from [The GILDA AfricaGrid CA guide](https://gilda.ct.infn.it/wikimain?p_p_id=54_INSTANCE_t9W0&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_count=1&_54_INSTANCE_t9W0_struts_action=%2Fwiki_display%2Fview&_54_INSTANCE_t9W0_nodeName=Main&_54_INSTANCE_t9W0_title=Instructions%20to%20create%20a%20Certification%20Authority)


# About 
The Africa Grid Certification Authority Template allows one to configure and operate a fully-operating Certificate Authority (based on OpenSSL), capable of signing X509 certificates valid for one year, for hosts, users and robots 
The template also provides a basic configuration of the tools needed to operate the CA, such as 
 1. The web server
 1. Creation of Certificate Revocation Lists (CRL)
 1. Generation of an rpm for distribution of the root CA cert
 1. Creation of Registration Authorities

The CA is managed via a script `runCA` which has a graphical frontend (zenity); an ncurses version is also under development.

# Prerequisites
The installation assumes a LAMP setup : 
 1. CEntOS server (x86_64)
 1. mysql server
 1. Apache web server with php

Ansible is used to execute the configuration


# Installation

the AfricaGrid CA Template is available in this repository. You can install the CA with the provided Ansible playbooks. Previous versions of software are  distributed via [rpm](http://gilda.ct.infn.it/africagrid-certification-authority-template).

## Updating from an existing installation
If you have already installed an older version of the CA Template (<1.14),
and you want to preserve the existing configuration, issued certificates, etc use the [update script](../blob/master/updateCA.sh) in the top-level directory. This will update the internal database to allow for the creation of RA's, which can bed done at this point.

## Installing from scratch

An Ansible playbook is provided to make basic configuration changes:
 1. Firewall: httpd needs to be reachable from the outside (ports 443 and 80
 1. Sendmail: needs to be working properly, as well as capable of delivering email.
 1. SELinux: disabled

## First-time configuration

Once you've run the Ansible pre-config, the first-time configuration of the CA is done.  This is currently done with runCA, but could be done with Ansible as well. 
There are a few important variables which need to be set in order to complete first-time configuration:
 1. **Country Code**: The ISO Country Code is a two-letter code identifying your Country and appears in every certificate subject. See http://en.wikipedia.org/wiki/ISO_3166-1
 1. **Organization Unit**: a string that will uniquely identify your CA. You
may put the name of your project, experiment or your institution. *Note: choose a name with no spaces*.
 1. **The CA passphrase**: this will be used to decrypt the private key which is required to sign all requests. Of course, this passphrase should be kept extremely safe; if it's lost or stolen, you will have to recreate the CA from scratch, and the certificates already signed would be no longer valid.
 1. **Logo**: The initial script provides for making a small customization to the website template by uploading a logo. This will be placed in the top-lefthand-side of the CA web site.
 1. **Administrator email**:  This is the email address of the administrator of the CA. Note that you must be able to read email at this address, since it is associated with the first issued certificate and Registration Authority (see below).
 
Insertion of email will trigger the creation of the CA root certificate and configures the website and webserver. Once the CA has passed initial configuration, you will be presented with an *RA code*. The RA code shown at this stage is associated to the email of the CA manager inserted above, which is also the first certificate created, immediately after the CA configuration. 
Save this code and use it to apply for the first certificate using the email previously inserted.

You should be able to see the newly configured CA running at http://<your host>/CA

# Distribution

In order to trust the certificates issued by your CA, you need the clients to have access to the public key; if included in the IGTF roll, the CA cert will also need to be distributed in RPM format. 
The `runCA` script creates this RPM and pushes the public keys, CRL and signing policy to `/var/www/CA`, which makes them available via the webserver as well. The RPM created takes the form:
`ca_<OU Name>-<version number>.noarch.rpm`
The RPM is noarch because it only contains the following files:
 - `.0` is the CA public key.
 - `.crl_url` it is needed by the sites accepting the CA in order to get the CRL updates 
 - `.r0` is the Certificate Revocation List file. This file is updated automatically by scripts that, reading periodically the CRL URL, and downloading the latest CRL version issued by the CA.
 - `.signing_policy` contains some general info about the CA, like subject
prefix, conditional subjects.

## Certificate Revocation Lists
The Certificate Revocation List (CRL) is a file that lists all the certificates
revoked by the CA. The CRL is authenticated by the CA public key signature. It has to be publicly available, so the CRL file is created under `/var/www/CA/crl`, which corresponds to the URL `http://IP address/CA/crl/crl.crl`
This file is updated every time a certificate is revoked.

# Layout of the website
A very basic template of a website is provided for you to get started, based on PHP. This consists of a front page and a side menu which helps the user or RA to use the CA. The sections should be self-explanatory, but we provide a brief outline below.

## Home 
Go to the home page, where general CA information is shown.

## CA certificate 
This allows the user to download the CA public key and/or import in the browser. Before requesting a Personal or a Robot certificate, users have to import the CA certificate, so that the request can be properly generated. *** Note that this may not work automatically on Chrome/Chromium browsers - in this case, download and import the file manually (in PEM format). 

## Documentation
This section contains user information and instructions for requesting user, robot and host certificates.

## Request a Personal certificate 
This section allows for the generation of a request for a personal certificate. In order to successfully submit this form, requestor must have been identified by a Registration Authority, who will generate the code to be inserted in the `RA code` field. 
The code created by the RA is sent via email by the CA itself to the requestor. Users are also required to insert the following information:
 - Institution name
 - First and last name
 - valid email address. 
Upon submission of the request, the user will receive a confirmation email from the CA, requesting them to reply. This is a manual check in order to prevent against spam or identity theft. The CA manager must receive this confirmation, before signing the certificate request, according to the CP/CPS.

## Request a Robot Certificate
In order to successfully submit this form, the requestor must have been identified by a Registration Authority, who will generate the necessary RA code The code created by the RA via the form is again sent via email to the requestor. Users are requested to insert 
 - Institution name
 - Purpose of the certificate 
 - valid email address.
Similarly to the case of requesting a user certificate,  an email from
the CA is sent to the requestor to confirm the email address. The CA manager
must wait to receive this confirmation, before signing the certificate request. 

## Certificate renewal
Certificates issued are valid for one year. When a personal or a robot certificate is close to expiration, the certificate owner receives an email reminding them of this deadline. While the certificate remains valid and loaded in the owner's web browser, they can use the `Renew Personal or Robot certificate` section for the certificate renewal.

Host certificate renewal reminders are generated in the same way, via an email to the owner, however the renewal should be requested with a signed email to the RA, containing the certificate renewal request in `pem` format. 

## Registration Authority (RA)
Registration Authorities (RA's) are individuals which hold delegated authority with the CA to identify individuals. This identification should take place face-to-face with the user. The user should produce national ID or passport in order to prove their identity, a copy of which should be stored by the RA safely. 
Once this identification has taken place, the RA fills out a form - only available to RA's, identified by their personal certificates - in this section of the website, in order to generate the RA code for the user. The RA code is automatically generated and sent to the user independently of the RA via email.

## Certificate Repository 
The CA should provide public access to the list of issued certificates. In this section of the web site, visitors can check if certificates have been issued for a given Common Name and email. Both arguments have to be specified. This search function does not require a certificate loaded on the web browser.

# Operating the CA
Operation of the CA essentially consists of executing a few tasks, via the runCA script. These are described below.

## Sign a request
This is the most common task done by the CA. Upong selecting this option, you are presented with a choice to sign user or server certificates. This brings up a dialog to the file manager, showing the related pending requests. Note that only User/Robot certificate requests are automatically included in the directory, since host certificates have to be sent by email and are not possible via the website.
 - User/Robot Certificates: The user requests are automatically copied into directory collecting request, so there’s no additional work required at this step. 
- Server (Host) Certificates: When someone applies for an host certificate,
request do not appear automatically in the directory collecting requests,
because they are sent first to the RA. After validation, the host certificate
requests are sent from RA as a signed email attachment. The CA manager copies the request file to `/etc/pki/CA/htdocs/CAtmp/servers/<hostname req>.pem` 
and the request will appear in the Server directory when launching runCA. 

In the future, this will be done with an Ansible playbook.

When a request is signed, the submitter is informed via mail that the certificate requested is ready. The same email contains also a link for the certificate download, and related instructions.

## Certificate Revocation
In order to revoke a certificate, choose *Revoke a Certificate*. This will take a certificate serial number and remove it from the roll; the serial numbers can easily be checked via the *Certificate Repository* section of the website (** note that you have to remove the `0x` prefix, shown on the web page*). If the
serial number inserted is valid, complete the certificate revocation by inserting the CA private key password. A cron job, running every hour, updates the CRL file.

## Update CRL
This is self-explanatory: a new CRL is generated and published to the website.

## Add/Remove RA
This option manages the Registration Authority identities. Users are of course identified by their personal certificates, so an RA candidate must already have a certificate issued. The email address of the user in question is required to match the serial number in the database, after which this is added to the db as an accredited RA. 

Conversely, if an RA is to be deleted, insert the email address and the certificate serial number will be removed from the database.

## Reset CA

Clearly, you do not want to touch this unless WWIII has broken out.
