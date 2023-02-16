# docker-php-mysql-nginx
fork of https://phptoday.ru/post-preview/gotovim-lokalnuyu-sredu-docker-dlya-razrabotki-na-php


# Install:
  docker-compose up
  or
  docker-compose up -d

# After instal
  1. Run get-cert.sh
     It makes docker-cert-to-trusted-root.crt
     You need tetup it like Trusted Root Cert
  
  2. Add to C:\Windows\System32\drivers\etc\hosts 
     127.0.0.1 hello-docker.loc
  