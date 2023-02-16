#!/bin/sh
docker cp nginx_ssl:/etc/ssl/certs/docker-cert.crt docker-cert-to-trusted-root.crt
