FROM nginx:alpine

COPY ./nginx.conf /etc/nginx/

RUN mkdir /etc/nginx/common

COPY ./common/cache.conf /etc/nginx/common/cache
COPY ./common/security.conf /etc/nginx/common/security
COPY ./common/deny.conf /etc/nginx/common/deny

RUN apk update \
    && apk upgrade \
    && apk add --no-cache bash \
    && adduser -D -H -u 1000 -s /bin/bash www-data

RUN rm /etc/nginx/conf.d/default.conf

WORKDIR /var/www

CMD ["nginx"]

EXPOSE 80