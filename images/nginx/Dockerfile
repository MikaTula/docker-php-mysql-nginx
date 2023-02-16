FROM nginx:1.23-alpine

# apline based = ubuntu => apk istead apt
RUN apk update && apk add openssl && apk add mc
RUN rm /etc/nginx/conf.d/default.conf

RUN openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048

RUN openssl req -x509 -out /etc/ssl/certs/docker-cert.crt -keyout /etc/ssl/certs/docker-cert.key \
-newkey rsa:2048 -nodes -sha256 \
-subj '/CN=hello-docker.loc' -extensions EXT -config <( \
printf "[dn]\nCN=hello-docker.loc\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:hello-docker.loc\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

COPY ssl-params.conf /etc/nginx/ssl-params.conf