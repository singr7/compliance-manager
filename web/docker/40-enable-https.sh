#!/bin/sh
# Runs as part of the official nginx image's /docker-entrypoint.d/ startup hooks, before
# nginx starts. If cert/key paths are provided *and* the files exist (mounted via a
# docker-compose volume — never baked into the image), render the HTTPS server block;
# otherwise leave the container HTTP-only on :80.
set -eu

if [ -n "${NGINX_SSL_CERT_PATH:-}" ] && [ -n "${NGINX_SSL_KEY_PATH:-}" ] \
  && [ -f "$NGINX_SSL_CERT_PATH" ] && [ -f "$NGINX_SSL_KEY_PATH" ]; then
  envsubst '${NGINX_SSL_CERT_PATH} ${NGINX_SSL_KEY_PATH}' \
    < /etc/nginx/custom-templates/nginx-ssl.conf.template \
    > /etc/nginx/conf.d/ssl.conf
  echo "HTTPS enabled: listening on :443 with $NGINX_SSL_CERT_PATH"
else
  echo "HTTPS not enabled (NGINX_SSL_CERT_PATH/NGINX_SSL_KEY_PATH unset or files missing) — HTTP only on :80"
fi
