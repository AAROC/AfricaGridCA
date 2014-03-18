#!/bin/sh
# 
# This script generates the directory of the input machine
# and the srv.cnf.tmp file related of the same machine
# with the L, CN, EMAIL values inserted from browser request.
#
# Written by Giuseppe Platania - INFN Catania on 2004 April 18
#

L=$1
HOST=$2
EMAIL=$3
CA_PATH="/etc/pki/CA"
SERVER_FILE_PATH="$CA_PATH/servers"
OPENSSL_CONFIG_FILE="$CA_PATH/srv.cnf"
OPENSSL_CONFIG_FILE_TMP="$CA_PATH/srv.cnf.tmp"

sed -e "s/LOCATION/$L/g" -e "s/HOST/$HOST/g" -e "s/EMAIL/$EMAIL/g" $OPENSSL_CONFIG_FILE_TMP > $OPENSSL_CONFIG_FILE

mkdir ${SERVER_FILE_PATH}/${HOST}

openssl req -new -nodes -out $CA_PATH/htdocs/CAtmp/servers/$HOST-req.pem -keyout ${SERVER_FILE_PATH}/${HOST}/$HOST-key.pem -config ${OPENSSL_CONFIG_FILE}
