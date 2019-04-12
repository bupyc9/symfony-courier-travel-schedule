FROM alpine:3.9

RUN apk add --no-cache \
    php7 \
    php7-fpm \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-sqlite3 \
    php7-curl \
    php7-gd \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-session \
    php7-simplexml \
    php7-sodium \
    php7-tokenizer \
    php7-xml \
    php7-zip \
    php7-dom \
    php7-ctype \
    php7-fileinfo \
    php7-ftp \
    php7-iconv \
    php7-mysqlnd \
    php7-openssl \
    php7-pcntl \
    php7-phar \
    php7-posix \
    php7-xmlreader \
    php7-xmlwriter \
    bash \
    unzip \
    curl

RUN set -x \
	&& addgroup -g 82 -S www-data \
	&& adduser -u 82 -D -S -G www-data www-data

RUN set -eux; \
	[ ! -d /var/www ]; \
	mkdir -p /var/www; \
	chown www-data:www-data /var/www; \
	chmod 777 /var/www

## ------------------------- install extensions -------------------------##

## redis ###################################################

ARG INSTALL_REDIS=false

RUN if [[ "$INSTALL_REDIS" == true ]]; then \
    apk add --no-cache php7-pecl-redis \
;fi

## xdebug ##################################################

ARG INSTALL_XDEBUG=false

RUN if [[ "$INSTALL_XDEBUG" == true ]]; then \
    apk add --no-cache php7-pecl-xdebug \
    && echo "zend_extension=xdebug.so" > /etc/php7/conf.d/xdebug.ini \
;fi

COPY ./xdebug.ini /etc/php7/conf.d/custom_xdebug.ini

## opcache #################################################

ARG INSTALL_OPCACHE=false

RUN if [[ "$INSTALL_OPCACHE" == true ]]; then \
    apk add --no-cache php7-opcache \
;fi

COPY opcache.ini /etc/php7/conf.d/custom_opcache.ini

## soap ####################################################

ARG INSTALL_SOAP=false

RUN if [[ "$INSTALL_SOAP" == true ]]; then \
    apk add --no-cache php7-soap \
;fi

## bcmath ##################################################

ARG INSTALL_BCMATH=false

RUN if [[ "$INSTALL_BCMATH" == true ]]; then \
    apk add --no-cache php7-bcmath \
;fi

## amqp ##################################################

ARG INSTALL_AMQP=false

RUN if [[ "$INSTALL_AMQP" == true ]]; then \
    apk add --no-cache php7-pecl-amqp \
;fi

## -------------------------- end extensions ---------------------------##

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	    && composer --version

COPY ./php.ini /etc/php7/conf.d/custom_php.ini
COPY ./php-fpm-pool.conf /etc/php7/php-fpm.d
COPY ./wait-for-it.sh /

RUN chmod +x /wait-for-it.sh

# Clean up
RUN rm -rf /tmp/* /var/tmp/* \
    && rm -rf /var/cache/apk/* \
    && rm -rf /etc/php7/php-fpm.d/www.conf

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm7", "-F"]