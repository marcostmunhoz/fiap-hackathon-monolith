FROM serversideup/php:8.4-fpm-nginx-alpine as base

FROM base as development

USER root

RUN install-php-extensions xdebug \
  && echo "xdebug.mode=none" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.trigger_value=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && mkdir -p /opt/phpstorm-coverage \
  && chmod 777 /opt/phpstorm-coverage

ARG USER_ID
ARG GROUP_ID

RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx

USER www-data

FROM base as production

ENV PHP_OPCACHE_ENABLE=1

COPY --chown=www-data:www-data . /var/www/html
