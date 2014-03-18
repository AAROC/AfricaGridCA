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
