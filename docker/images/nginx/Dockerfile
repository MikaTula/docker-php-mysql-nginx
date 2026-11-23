FROM nginx:1.30.7-alpine

# alpine based = ubuntu => apk istead apt
RUN apk update && \
  apk add mc && \
  apk add htop 
RUN rm /etc/nginx/conf.d/default.conf
