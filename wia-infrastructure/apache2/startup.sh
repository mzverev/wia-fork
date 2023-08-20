#!/bin/bash

mkdir /etc/apache2/ssl 2>/dev/null

if [ ! -f /etc/apache2/ssl/ssl_site.pem ]; then
  export CAROOT=/caRoot/
  mkcert -cert-file /etc/apache2/ssl/ssl_site.pem -key-file /etc/apache2/ssl/ssl_site-key.pem \
    '*.nip.io' \
    wia-api.182.80.0.101.nip.io \
    wia.182.80.0.101.nip.io \
    wia-api.com \
    wia.com
  mkcert -install
fi

a2enmod rewrite
a2enmod headers
a2enmod proxy proxy_html proxy_http xml2enc ssl http2
service apache2 restart

# Start apache in foreground
/usr/sbin/apache2ctl -D FOREGROUND